<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesignationRequest;
use App\Http\Requests\UpdateDesignationRequest;
use App\Models\Designation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($error = $this->authorize('designation-manage')) {
            return $error;
        }

        if ($request->ajax()) {
            $designations = Designation::query();

            return DataTables::of($designations)
                ->addIndexColumn()
                ->addColumn('is_active', function ($row) {
                    if (userCan('designation-edit')) {
                        return view('button', ['type' => 'is_active', 'route' => route('admin.designations.is_active', $row->id), 'row' => $row->is_active]);
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (userCan('designation-edit')) {
                        $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.designations.edit', $row->id), 'row' => $row]);
                    }
                    if (userCan('designation-delete')) {
                        $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.designations.destroy', $row->id), 'row' => $row, 'src' => 'dt']);
                    }

                    return $btn;
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }

        return view('admin.designation.index');
    }

    public function status(Designation $designation)
    {
        if ($error = $this->authorize('designation-edit')) {
            return $error;
        }
        $designation->is_active = $designation->is_active == 1 ? 0 : 1;
        try {
            $designation->save();

            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDesignationRequest $request)
    {
        if ($error = $this->authorize('designation-add')) {
            return $error;
        }
        $data = $request->validated();

        try {
            Designation::create($data);

            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Designation $designation)
    {
        if ($error = $this->authorize('designation-edit')) {
            return $error;
        }
        if ($request->ajax()) {
            $modal = view('admin.designation.edit')->with(['designation' => $designation])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDesignationRequest $request, Designation $designation)
    {
        if ($error = $this->authorize('designation-add')) {
            return $error;
        }
        $data = $designation->validated();
        $image = $designation->image;
        if ($request->hasFile('image')) {
            $data['image'] = imgWebpUpdate($request->image, 'user', [1920, 1080], $image);
        }
        try {
            $designation->update($data);

            return response()->json(['message' => 'The information has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        if ($error = $this->authorize('designation-delete')) {
            return $error;
        }
        try {
            $designation->delete();

            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
