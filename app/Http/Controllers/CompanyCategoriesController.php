<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyCategoriesController extends Controller
{
    public function index($name ,$category_id, Request $request) {
        $name = trim($name);

        try {
            $company_id = DB::select(DB::raw(
                'SELECT
                            company_id
                        FROM
                             companies
                        WHERE
                              LOWER(company_name) =' . "'" . $name . "'")
            )[0]->company_id;
        } catch (\Exception $e) {
            abort(404);
        } catch (\Error $e) {
            abort(404);
        }

        $category_info = Category::where(
            'category_id', '=', $category_id)->get()->toArray();

        $singleMiDevices = Device::where([
            ['category_id', '=', $category_id],
            ['company_id', '=', $company_id],
        ])->leftJoin('vri_infos', function($join) {
            $join->on('devices.device_id', '=', 'vri_infos.device_id');
        })->Join('singlemi_devices', function($join) {
            $join->on('devices.device_id', '=', 'singlemi_devices.device_id');
        })->get()->toArray();

        $etaMiDevices = Device::where([
            ['category_id', '=', $category_id],
            ['company_id', '=', $company_id],
        ])->leftJoin('vri_infos', function($join) {
            $join->on('devices.device_id', '=', 'vri_infos.device_id');
        })->Join('etami_devices', function($join) {
            $join->on('devices.device_id', '=', 'etami_devices.device_id');
        })->get()->toArray();

        $partyMiDevices = Device::where([
            ['category_id', '=', $category_id],
            ['company_id', '=', $company_id],
        ])->leftJoin('vri_infos', function($join) {
            $join->on('devices.device_id', '=', 'vri_infos.device_id');
        })->Join('partymi_devices', function($join) {
            $join->on('devices.device_id', '=', 'partymi_devices.device_id');
        })->get()->toArray();

        $devices = array_merge(
            array_merge($singleMiDevices, $etaMiDevices), $partyMiDevices);

        return view('company', [
            'name' => $name,
            'category' => $category_info[0],
            'devices'   => $devices,
        ]);
    }
}
