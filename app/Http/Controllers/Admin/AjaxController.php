<?php

namespace App\Http\Controllers\Admin;

use App\Models\Size;
use App\Models\User;
use App\Models\Brand;
use App\Models\Vendor;
use App\Models\Generic;
use App\Models\Product;
use App\Models\Upazila;
use App\Models\Customer;
use App\Models\District;
use App\Models\Division;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
                case 'getCustomer':
                    $response = Customer::select('id', 'name')
                        ->whereVendorId(venId())->where('name', 'like', "%{$request->q}%")
                        ->orderBy('name')
                        ->whereIsActive(1)
                        ->limit(10)
                        ->get()->map(function ($data) {
                            return [
                                'id' => $data->id,
                                'text' => $data->name,
                            ];
                        })->toArray();
                    break;
                case 'getCustomerByCompany':
                    $response = Customer::select('id', 'name')
                        ->whereVendorId(venId())
                        ->where('name', 'like', "%{$request->q}%")
                        ->whereCompany($request->data['company'])
                        ->orderBy('name')
                        ->whereIsActive(1)
                        ->limit(10)
                        ->get()
                        ->map(function ($data) {
                            return [
                                'id' => $data->id,
                                'text' => $data->name,
                            ];
                        })
                        ->toArray();
                    break;
                case 'getProduct':
                    $query = Product::with(['generic' => function ($q) {
                        $q->select('id', 'name');
                    }])->select('id', 'name', 'generic_id', 'company')
                        ->where(venQuery())
                        ->whereIsActive(1)
                        ->where('name', 'like', "%{$request->q}%")
                        ->orderBy('name')
                        ->limit(10);

                    if (! empty($request->data['company'])) {
                        $query->whereCompany($request->data['company']);
                    }

                    $response = $query->get()
                        ->map(function ($data) {
                            return [
                                'id' => $data->id,
                                'text' => $data->name,
                            ];
                        })->toArray();
                    break;
                case 'getPAProduct':
                    $response = Product::with(['generic' => fn ($q) => $q->select('id', 'name')])
                        ->select('id', 'name', 'generic_id')
                        ->whereVendorId(1)
                        ->whereCompany($request->data['company'])
                        ->where('name', 'like', "%{$request->q}%")
                        ->whereIsActive(1)
                        ->orderBy('name')
                        ->limit(20)
                        ->get()->map(function ($data) {
                            return [
                                'id' => $data->id,
                                'text' => $data->name,
                            ];
                        })->toArray();
                    break;
                case 'getPPProduct':
                    $response = Product::with(['generic' => fn ($q) => $q->select('id', 'name')])
                        ->select('id', 'name', 'generic_id')
                        ->whereVendorId(1)
                        ->whereCompany(1)
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
                case 'getGeneric':
                    $response = Generic::select('id', 'name')->where(venQuery())
                        ->whereIsActive(1)
                        ->where('name', 'like', "%{$request->q}%")
                        ->limit(30)
                        ->orderBy('name')->get()->map(function ($generic) {
                            return [
                                'id' => $generic->id,
                                'text' => $generic->name,
                            ];
                        })->toArray();
                    break;
                case 'getBrand':
                    $response = Brand::select('id', 'name')->where(venQuery())
                        ->whereIsActive(1)
                        ->where('name', 'like', "%{$request->q}%")
                        ->orderBy('name')
                        ->limit(10)
                        ->get()->map(function ($data) {
                            return [
                                'id' => $data->id,
                                'text' => $data->name,
                            ];
                        })->toArray();
                    break;
                case 'getSize':
                    $response = Size::select('id', 'name')->where(venQuery())
                        ->whereIsActive(1)
                        ->where('name', 'like', "%{$request->q}%")
                        ->orderBy('name')
                        ->limit(10)
                        ->get()->map(function ($data) {
                            return [
                                'id' => $data->id,
                                'text' => $data->name,
                            ];
                        })->toArray();
                    break;
                    // Division, District, Upazila
                case 'getDivision':
                    $response = Division::where('name', 'like', "%{$request->q}%")
                        ->limit(10)
                        ->get()->map(function ($division) {
                            return [
                                'id' => $division->id,
                                'text' => $division->name,
                            ];
                        })->toArray();
                    break;
                case 'getDistrict':
                    $response = District::whereDivisionId($request->division_id)->where('name', 'like', "%{$request->q}%")
                        ->limit(10)
                        ->get()->map(function ($district) {
                            return [
                                'id' => $district->id,
                                'text' => $district->name,
                            ];
                        })->toArray();
                    break;
                case 'getUpazila':
                    $response = Upazila::whereDistrictId($request->district_id)->where('name', 'like', "%{$request->q}%")
                        ->limit(10)
                        ->get()->map(function ($upazila) {
                            return [
                                'id' => $upazila->id,
                                'text' => $upazila->name,
                            ];
                        })->toArray();
                    break;
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
