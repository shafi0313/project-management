<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($error = $this->authorize('project-manage')) {
            return $error;
        }

        if ($request->ajax()) {
            $projects = Project::with(['users', 'createdBy', 'updatedBy']);
            return DataTables::of($projects)
                ->addIndexColumn()
                ->addColumn('content', function ($row) {
                    return '<div>' . $row->content . '</div>';
                })
                ->addColumn('user', function ($row) {
                    return $row->users->map(function ($user) {
                        return '<span class="badge text-bg-success">' . $user->name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('image', function ($row) {
                    $path = imagePath('project', $row->image);
                    return '<img src="' . $path . '" width="70px" alt="image">';
                })
                ->addColumn('is_active', function ($row) {
                    if (userCan('project-edit')) {
                        return view('button', ['type' => 'is_active', 'route' => route('admin.projects.is_active', $row->id), 'row' => $row->is_active]);
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (userCan('project-edit')) {
                        $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.projects.edit', $row->id), 'row' => $row]);
                    }
                    if (userCan('project-delete')) {
                        $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.projects.destroy', $row->id), 'row' => $row, 'src' => 'dt']);
                    }
                    return $btn;
                })
                ->rawColumns(['user','content', 'is_active', 'action'])
                ->make(true);
        }
        return view('admin.project.index');
    }

    function status(project $project)
    {
        if ($error = $this->authorize('project-edit')) {
            return $error;
        }
        $project->is_active = $project->is_active  == 1 ? 0 : 1;
        try {
            $project->save();
            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        if ($error = $this->authorize('project-add')) {
            return $error;
        }
        $data = $request->validated();
        $data['created_by'] = user()->id;
        if ($request->hasFile('image')) {
            $data['image'] = imgWebpStore($request->image, 'project', [1920, 1080]);
        }

        try {
            $project = Project::create($data);
            $project->users()->sync($request->user_id);
            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            // return response()->json(['message' => $e->getMessage()], 500);
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        if ($error = $this->authorize('project-edit')) {
            return $error;
        }
        if ($request->ajax()) {
            $modal = view('admin.project.edit')->with(['project' => $project])->render();
            return response()->json(['modal' => $modal], 200);
        }
        return abort(500);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        if ($error = $this->authorize('project-add')) {
            return $error;
        }
        $data = $project->validated();
        $image = $project->image;
        if ($request->hasFile('image')) {
            $data['image'] = imgWebpUpdate($request->image, 'user', [1920, 1080], $image);
        }
        try {
            $project->update($data);
            return response()->json(['message' => 'The information has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if ($error = $this->authorize('project-delete')) {
            return $error;
        }
        try {
            imgUnlink('project', $project->image);
            $project->delete();
            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
