<?php

namespace App\Http\Controllers\Admin;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
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

        if ($request->ajax()) {
            if (user()->designation_id == 1) {
                $tasks = Task::with(['users', 'createdBy', 'updatedBy']);
            } else {
                $tasks = Task::with(['users', 'createdBy', 'updatedBy'])
                    ->whereHas('users', function ($query) {
                        $query->where('user_id', auth()->id());
                    });
            }
            return DataTables::of($tasks)
                ->addIndexColumn()
                ->addColumn('priority', function ($row) {
                    return priority($row->priority);
                })
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
                    $btn .= view('button', ['type' => 'ajax-show', 'route' => route('admin.tasks.show', $row->id), 'row' => $row]);
                    // if (userCan('project-edit')) {
                    //     $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.projects.edit', $row->id), 'row' => $row]);
                    // }
                    if (userCan('project-delete')) {
                        $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.tasks.destroy', $row->id), 'row' => $row, 'src' => 'dt']);
                    }
                    return $btn;
                })
                ->rawColumns(['priority', 'user', 'content', 'is_active', 'action'])
                ->make(true);
        }
        return view('admin.project.show');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        if ($error = $this->authorize('task-add')) {
            return $error;
        }
        $data = $request->validated();
        $data['created_by'] = user()->id;
        // if ($request->hasFile('image')) {
        //     $data['image'] = imgWebpStore($request->image, 'project', [1920, 1080]);
        // }

        try {
            $task = Task::create($data);
            $task->users()->sync($request->user_id);
            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            // return response()->json(['message' => $e->getMessage()], 500);
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Task $task)
    {
        // return $task;
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
        if ($request->ajax()) {
            $modal = view('admin.project.edit')->with(['task' => $task])->render();
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
        try {
            // imgUnlink('project', $project->image);
            $task->delete();
            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
