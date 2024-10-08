<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($error = $this->authorize('project-manage')) {
            return $error;
        }
        // return $tasks = Task::with([
        //     'project:id,job_name',
        //     'users:id,name,email,section_id,sub_section_id',
        //     'createdBy:id,section_id',
        //     'createdBy.section:id,name',
        //     'updatedBy:id,name',
        // ])
        //     // ->whereProjectId(1)
        //     ->whereHas('users', function ($query) {
        //         return $query->whereIn('section_id', [1, 2, 3, 4])
        //             ->orWhere('sub_section_id', user()->sub_section_id);
        //     })
        //     ->get();
        if ($request->ajax()) {
            $tasks = Task::with([
                'project:id,job_name',
                'users:id,name,email,section_id',
                'createdBy:id,section_id',
                'createdBy.section:id,name',
                'updatedBy:id,name',
            ])
                // ->whereProjectId(1)
                ->whereHas('users', function ($query) {
                    return $query->whereIn('section_id', [1, 2, 3, 4])
                        ->orWhere('sub_section_id', user()->sub_section_id);
                });

            return DataTables::of($tasks)
                ->addIndexColumn()
                ->addColumn('deadline', function ($row) {
                    return bdDate($row->deadline);
                })
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
                ->addColumn('image', function ($row) {
                    $path = imagePath('project', $row->image);

                    return '<img src="' . $path . '" width="70px" alt="image">';
                })
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
                ->rawColumns(['priority', 'user', 'task_description', 'is_active', 'action'])
                ->make(true);
        }

        return view('admin.task.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        if ($error = $this->authorize('task-add')) {
            return $error;
        }
        // if (actionCondition()) {
        //     return response()->json(['message' => 'You can not access this action'], 500);
        // }

        $data = $request->validated();
        $data['created_by'] = user()->id;
        if (user()->sub_section_id) {
            $data['task_request'] = 1;
        }

        try {
            $task = Task::create($data);
            $task->users()->sync($request->user_id);
            // $task->subSections()->sync($request->sub_section_id);
            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Task $task)
    {
        if ($error = $this->authorize('task-show')) {
            return $error;
        }
        if ($request->ajax()) {
            $task->load(['project', 'users', 'createdBy', 'updatedBy']);
            $modal = view('admin.task.show')->with(['task' => $task])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Task $task)
    {
        if ($error = $this->authorize('task-show')) {
            return $error;
        }
        $taskUsers = $task->load(['users:id,section_id,sub_section_id']);
        $subSectionToSectionIds = $taskUsers->users->map(function ($user) {
            return $user->subSection ? $user->subSection->section_id : null;
        })->filter()->toArray();
        $taskSectionId = array_merge($taskUsers->users->pluck('section_id')->toArray(), [1, user()->subSection?->section_id], $subSectionToSectionIds);
        $taskSubSectionId = array_merge($taskUsers->users->pluck('sub_section_id')->toArray(), [user()->sub_section_id]);
        if (!in_array(user()->section_id, $taskSectionId) || !in_array(user()->sub_section_id, $taskSubSectionId)) {
            return response()->json(['message' => 'You can not access this action'], 500);
        }

        if ($request->ajax()) {
            $modal = view('admin.task.edit')->with(['task' => $task])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if ($error = $this->authorize('task-delete')) {
            return $error;
        }
        if (actionCondition()) {
            return response()->json(['message' => 'You can not access this action'], 500);
        }
        try {
            // imgUnlink('project', $project->image);
            $task->delete();

            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
