<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SubSection;
use App\Models\User;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function select2(Request $request)
    {
        if ($request->ajax()) {
            switch ($request->type) {
                case 'getUser':
                    $response = User::select('id', 'name')
                        ->where('name', 'like', "%{$request->q}%")
                        ->whereIsActive(1)
                        ->orderBy('name')
                        ->limit(10)
                        ->get()->map(function ($data) {
                            return [
                                'id' => $data->id,
                                'text' => $data->name,
                            ];
                        })->toArray();
                    break;
                case 'getSubSection':
                    $response = SubSection::select('id', 'name')
                        ->where('name', 'like', "%{$request->q}%")
                        ->whereIsActive(1)
                        ->orderBy('name')
                        ->limit(15)
                        ->get()->map(function ($data) {
                            return [
                                'id' => $data->id,
                                'text' => $data->name,
                            ];
                        })->toArray();
                    break;
                case 'getProject':
                    $response = Project::with([
                        'users:id,name,section_id',
                    ])->select('id', 'job_name')
                        ->whereHas('users', function ($query) {
                            return $query->whereIn('section_id', [1, 2, 3, 4])
                                ->orWhere('sub_section_id', user()->sub_section_id);
                        })
                        ->limit(15)
                        ->get()->map(function ($data) {
                            return [
                                'id' => $data->id,
                                'text' => $data->job_name,
                            ];
                        })->toArray();

                    // $response = Project::select('id', 'job_name')
                    //     ->where('job_name', 'like', "%{$request->q}%")
                    //     // ->whereIsActive(1)
                    //     ->orderBy('job_name')
                    //     ->limit(15)
                    //     ->get()->map(function ($data) {
                    //         return [
                    //             'id' => $data->id,
                    //             'text' => $data->job_name,
                    //         ];
                    //     })->toArray();
                    break;
                    // case 'getProduct':
                    //     $query = Product::with(['generic' => function ($q) {
                    //         $q->select('id', 'name');
                    //     }])->select('id', 'name', 'generic_id', 'company')
                    //         ->where(venQuery())
                    //         ->whereIsActive(1)
                    //         ->where('name', 'like', "%{$request->q}%")
                    //         ->orderBy('name')
                    //         ->limit(10);

                    //     if (! empty($request->data['company'])) {
                    //         $query->whereCompany($request->data['company']);
                    //     }

                    //     $response = $query->get()
                    //         ->map(function ($data) {
                    //             return [
                    //                 'id' => $data->id,
                    //                 'text' => $data->name,
                    //             ];
                    //         })->toArray();
                    //     break;
                default:
                    $response = [];
                    break;
            }
            $name = preg_split('/(?=[A-Z])/', str_replace('get', '', $request->type), -1, PREG_SPLIT_NO_EMPTY);
            $name = implode(' ', $name);
            array_unshift($response, ['id' => ' ', 'text' => 'All '.$name]);

            return $response;
        }

        return abort(404);
    }
}
