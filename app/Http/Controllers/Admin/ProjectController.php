<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
            $projects = Project::with([
                'tasks',
                'users:id,name,section_id',
                'subSections:id,name',
                'createdBy:id,section_id',
                'createdBy.section:id,name',
                'updatedBy:id,name',
            ])
                ->whereHas('users', function ($query) {
                    return $query->whereIn('section_id', [1, 2, 3, 4])
                        ->orWhere('sub_section_id', user()->sub_section_id);
                });

            return DataTables::of($projects)
                ->addIndexColumn()
                ->addColumn('job_description', function ($row) {
                    return '<div>' . $row->job_description . '</div>';
                })
                ->addColumn('deadline', function ($row) {
                    return bdDate($row->deadline);
                })
                ->addColumn('progress', function ($row) {
                    $totalTasks = $row->tasks->count();
                    $completedTasks = $row->tasks->where('status', 3)->count();

                    if ($totalTasks > 0) {
                        $percentage = ($completedTasks / $totalTasks) * 100;
                    } else {
                        $percentage = 0;
                    }
                    if ($percentage < 20) {
                        $bg = 'bg-danger';
                    } elseif ($percentage < 40) {
                        $bg = 'bg-warning';
                    } elseif ($percentage < 60) {
                        $bg = 'bg-info';
                    } elseif ($percentage < 80) {
                        $bg = 'bg-primary';
                    } else {
                        $bg = 'bg-success';
                    }

                    return '<div class="progress" role="progressbar" aria-valuenow="' . $percentage . '" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar ' . $bg . '" style="width:' . $percentage . '%">' . $percentage . '%</div>
                            </div>';
                })
                ->addColumn('users', function ($row) {
                    return $row->users->map(function ($user) {
                        return '<span class="badge text-bg-success">' . $user->name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('sub_sections', function ($row) {
                    return $row->subSections->map(function ($subSection) {
                        return '<span class="badge text-bg-primary">' . $subSection->name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('status', function ($row) {
                    if ($row->status != 3 && $row->deadline < date('Y-m-d')) {
                        return '<span class="badge text-bg-danger">Deadline Over</span>';
                    } else {
                        return projectStatus($row->status);
                    }
                })
                // ->addColumn('image', function ($row) {
                //     $path = imagePath('project', $row->image);
                //     return '<img src="' . $path . '" width="70px" alt="image">';
                // })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= view('button', ['type' => 'show', 'route' => 'admin.projects', 'row' => $row]);
                    if (userCan('project-edit')) {
                        $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.projects.edit', $row->id), 'row' => $row]);
                    }
                    if (userCan('project-delete')) {
                        $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.projects.destroy', $row->id), 'row' => $row, 'src' => 'dt']);
                    }

                    return $btn;
                })
                ->rawColumns(['job_description', 'progress', 'users', 'sub_sections', 'status', 'action'])
                ->make(true);
        }

        return view('admin.project.index');
    }

    public function task(Request $request, $projectId)
    {
        if ($error = $this->authorize('project-manage')) {
            return $error;
        }

        if ($request->ajax()) {
            $tasks = Task::with([
                'users:id,name,email,section_id',
                'createdBy:id,section_id',
                'createdBy.section:id,name',
                'updatedBy:id,name',
            ])->whereProjectId(1)
                ->whereHas('users', function ($query) {
                    return $query->whereIn('section_id', [1, 2, 3, 4])
                        ->orWhere('sub_section_id', user()->sub_section_id);
                });

            return DataTables::of($tasks)
                ->addIndexColumn()
                ->addColumn('priority', function ($row) {
                    return priority($row->priority);
                })
                ->addColumn('task_description', function ($row) {
                    return '<div>' . $row->task_description . '</div>';
                })
                ->addColumn('user', function ($row) {
                    return $row->users->map(function ($user) {
                        return '<span class="badge text-bg-success">' . $user->name . '</span>';
                    })->implode(' ');
                })
                // ->addColumn('image', function ($row) {
                //     $path = imagePath('project', $row->image);
                //     return '<img src="' . $path . '" width="70px" alt="image">';
                // })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= view('button', ['type' => 'ajax-show', 'route' => route('admin.tasks.show', $row->id), 'row' => $row]);
                    if (userCan('project-edit')) {
                        $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.tasks.edit', $row->id), 'row' => $row]);
                    }
                    if (userCan('project-delete')) {
                        $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.tasks.destroy', $row->id), 'row' => $row, 'src' => 'dt']);
                    }

                    return $btn;
                })
                ->rawColumns(['priority', 'user', 'task_description', 'action'])
                ->make(true);
        }

        return view('admin.project.show');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        if ($error = $this->authorize('project-add')) {
            return $error;
        }
        if (actionCondition()) {
            return response()->json(['message' => 'You can not access this action'], 500);
        }

        $data = $request->validated();
        $data['created_by'] = user()->id;
        // if ($request->hasFile('image')) {
        //     $data['image'] = imgWebpStore($request->image, 'project', [1920, 1080]);
        // }

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
        if ($error = $this->authorize('project-show')) {
            return $error;
        }
        $project->load([
            'users:id,name,email,section_id',
            'createdBy:id,section_id',
            'createdBy.section:id,name',
            'updatedBy:id,name',
        ]);

        return view('admin.project.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Project $project)
    {
        if ($error = $this->authorize('project-edit')) {
            return $error;
        }

        $this->actionCondition($project);

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
        $projectUsers = $project->load([
            'users:id,section_id,sub_section_id'
        ]);
        $projectSectionId = array_merge($projectUsers->users->pluck('section_id')->toArray(), [1, user()->subSection?->section_id]);
        $projectSubSectionId = array_merge($projectUsers->users->pluck('sub_section_id')->toArray(), [user()->sub_section_id]);
        if (!in_array(user()->section_id, $projectSectionId) || !in_array(user()->sub_section_id, $projectSubSectionId)) {
            return response()->json(['message' => 'You can not access this action'], 500);
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
        $projectUsers = $project->load([
            'users:id,section_id,sub_section_id'
        ]);
        $projectSectionId = array_merge($projectUsers->users->pluck('section_id')->toArray(), [1, user()->subSection?->section_id]);
        $projectSubSectionId = array_merge($projectUsers->users->pluck('sub_section_id')->toArray(), [user()->sub_section_id]);
        if (!in_array(user()->section_id, $projectSectionId) || !in_array(user()->sub_section_id, $projectSubSectionId)) {
            return response()->json(['message' => 'You can not access this action'], 500);
        }
        try {
            // imgUnlink('project', $project->image);
            $project->delete();

            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }

    public function actionCondition($project)
    {
        $projectUsers = $project->load(['users:id,section_id,sub_section_id']);
        $subSectionToSectionIds = $projectUsers->users->map(function ($user) {
            return $user->subSection ? $user->subSection->section_id : null;
        })->filter()->toArray();
        $projectSectionId = array_merge($projectUsers->users->pluck('section_id')->toArray(), [1, user()->subSection?->section_id],$subSectionToSectionIds);
        $projectSubSectionId = array_merge($projectUsers->users->pluck('sub_section_id')->toArray(), [user()->sub_section_id]);
        if (!in_array(user()->section_id, $projectSectionId) || !in_array(user()->sub_section_id, $projectSubSectionId)) {
            return response()->json(['message' => 'You can not access this action'], 500);
        }
    }
}
