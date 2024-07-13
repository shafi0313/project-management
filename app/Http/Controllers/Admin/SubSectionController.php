<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubSectionRequest;
use App\Http\Requests\UpdateSubSectionRequest;
use App\Models\Section;
use App\Models\SubSection;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($error = $this->authorize('sub-section-manage')) {
            return $error;
        }

        if ($request->ajax()) {
            $subSections = SubSection::join('sections', 'sub_sections.section_id', '=', 'sections.id')
                ->selectRaw('sub_sections.id as id, sub_sections.name as name, sub_sections.is_active as is_active, sections.id as section_id, sections.name as section_name')
                ->orderBy('sections.name')
                ->orderBy('sub_sections.name');

            return DataTables::of($subSections)
                ->addIndexColumn()
                ->addColumn('is_active', function ($row) {
                    if (userCan('sub-section-edit')) {
                        return view('button', ['type' => 'is_active', 'route' => route('admin.sub_sections.is_active', $row->id), 'row' => $row->is_active]);
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (userCan('sub-section-edit')) {
                        $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.sub-sections.edit', $row->id), 'row' => $row]);
                    }
                    if (userCan('sub-section-delete')) {
                        $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.sub-sections.destroy', $row->id), 'row' => $row, 'src' => 'dt']);
                    }

                    return $btn;
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }
        $sections = Section::where('is_active', 1)->get();

        return view('admin.sub-section.index', compact('sections'));
    }

    public function status(SubSection $subSection)
    {
        if ($error = $this->authorize('sub-section-edit')) {
            return $error;
        }
        $subSection->is_active = $subSection->is_active == 1 ? 0 : 1;
        try {
            $subSection->save();

            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubSectionRequest $request)
    {
        if ($error = $this->authorize('sub-section-add')) {
            return $error;
        }
        $data = $request->validated();

        try {
            SubSection::create($data);

            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, SubSection $subSection)
    {
        if ($error = $this->authorize('sub-section-edit')) {
            return $error;
        }
        if ($request->ajax()) {
            $sections = Section::where('is_active', 1)->get();
            $modal = view('admin.sub-section.edit')->with(['subSection' => $subSection, 'sections' => $sections])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubSectionRequest $request, SubSection $subSection)
    {
        if ($error = $this->authorize('sub-section-add')) {
            return $error;
        }
        $data = $request->validated();

        try {
            $subSection->update($data);

            return response()->json(['message' => 'The information has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubSection $subSection)
    {
        if ($error = $this->authorize('sub-section-delete')) {
            return $error;
        }
        try {
            $subSection->delete();

            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
