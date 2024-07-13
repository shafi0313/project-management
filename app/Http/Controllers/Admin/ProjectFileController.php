<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectFileRequest;
use App\Models\ProjectFile;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ProjectFileController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sections = ProjectFile::where('project_id', $request->project_id);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('download', function ($row) {
                    return '<a href="'.asset('uploads/project-files/'.$row->file).'" download="'.$row->file.'">Download</a>';
                })
                ->addColumn('size', function ($row) {
                    return readableSize(File::size('uploads/project-files/'.$row->file));
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (userCan('project-file-delete')) {
                        $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.project_files.delete', $row->id), 'row' => $row, 'src' => 'dt']);
                    }

                    return $btn;
                })
                ->rawColumns(['size', 'download', 'action'])
                ->make(true);
        }
        // return view('admin.section.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function store(StoreProjectFileRequest $request)
    {
        if ($request->file('file')) {
            $file = $request->file('file');
            $filename = uniqueId().'-'.$file->getClientOriginalName();
            $file->move(public_path('uploads/project-files/'), $filename);

            ProjectFile::create([
                'project_id' => $request->project_id,
                'file' => $filename,
                // 'file_size' => $file->getSize(),
            ]);

            return response()->json(['filename' => $filename]);
        }

        return response()->json(['error' => 'File not uploaded'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectFile $projectFile)
    {
        //
    }

    public function destroy(Request $request)
    {
        $filename = $request->get('filename');
        $path = public_path('uploads/project-files/'.$filename);

        if (File::exists($path)) {
            ProjectFile::whereProjectId($request->project_id)->whereFile($filename)->first()->delete();
            File::delete($path);

            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        }

        return response()->json(['success' => false, 'message' => $path], 404);
    }

    public function delete($id)
    {
        $projectFile = ProjectFile::findOrFail($id);
        $filename = $projectFile->file;
        $path = public_path('uploads/project-files/'.$filename);

        if (File::exists($path)) {
            $projectFile->delete();
            File::delete($path);

            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        }

        return response()->json(['success' => false, 'message' => $path], 404);
    }
}
