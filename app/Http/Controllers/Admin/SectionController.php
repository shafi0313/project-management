<?php

namespace App\Http\Controllers\Admin;

use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($error = $this->authorize('section-manage')) {
            return $error;
        }

        if ($request->ajax()) {
            $sections = Section::orderBy('name');
            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('is_active', function ($row) {
                    if (userCan('section-edit')) {
                        return view('button', ['type' => 'is_active', 'route' => route('admin.sections.is_active', $row->id), 'row' => $row->is_active]);
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (userCan('section-edit')) {
                        $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.sections.edit', $row->id), 'row' => $row]);
                    }
                    if (userCan('section-delete')) {
                        $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.sections.destroy', $row->id), 'row' => $row, 'src' => 'dt']);
                    }
                    return $btn;
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }
        return view('admin.section.index');
    }

    function status(section $section)
    {
        if ($error = $this->authorize('section-edit')) {
            return $error;
        }
        $section->is_active = $section->is_active  == 1 ? 0 : 1;
        try {
            $section->save();
            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSectionRequest $request)
    {
        if ($error = $this->authorize('section-add')) {
            return $error;
        }
        $data = $request->validated();

        try {
            Section::create($data);
            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Section $section)
    {
        if ($error = $this->authorize('section-edit')) {
            return $error;
        }
        if ($request->ajax()) {
            $modal = view('admin.section.edit')->with(['section' => $section])->render();
            return response()->json(['modal' => $modal], 200);
        }
        return abort(500);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSectionRequest $request, Section $section)
    {
        if ($error = $this->authorize('section-add')) {
            return $error;
        }
        $data = $request->validated();

        try {
            $section->update($data);
            return response()->json(['message' => 'The information has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        if ($error = $this->authorize('section-delete')) {
            return $error;
        }
        try {
            $section->delete();
            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
