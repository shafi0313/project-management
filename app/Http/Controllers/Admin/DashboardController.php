<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        if ($error = $this->authorize('project-manage')) {
            return $error;
        }
        return view('admin.dashboard');
    }

    public function project(Request $request)
    {
        if ($error = $this->authorize('project-manage')) {
            return $error;
        }
        if ($request->section_id == 1) {
            $sectionId = [1, 2, 3, 4];
        } else {
            $sectionId = [(int) $request->section_id];
        }

        if ($request->ajax()) {
            $projects = Project::with([
                'tasks',
                'users:id,name,section_id',
                'subSections:id,name',
                'createdBy:id,section_id',
                'createdBy.section:id,name',
                'updatedBy:id,name'
            ])
                ->whereHas('users', function ($query) use ($sectionId) {
                    return $query->whereIn('section_id', $sectionId);
                    // ->orWhere('sub_section_id', user()->sub_section_id);
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
        return view('admin.dashboard');
    }
}
