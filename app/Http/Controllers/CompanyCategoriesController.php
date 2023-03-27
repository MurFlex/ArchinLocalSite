<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyCategoriesController extends Controller
{
    public function index($id, $category_id)
    {
        $category_info = Category::where(
            'category_id', '=', $category_id)->get()->toArray();

        $category_name = $category_info[0]['category_title'];

        $singleMiDevices = Device::where([
            ['category_id', '=', $category_id],
            ['company_id', '=', $id],
        ])->leftJoin('vri_infos', function($join) {
            $join->on('devices.device_id', '=', 'vri_infos.device_id');
        })->Join('singlemi_devices', function($join) {
            $join->on('devices.device_id', '=', 'singlemi_devices.device_id');
        })->get()->toArray();

        $company_name = Company::where('company_id', '=', $singleMiDevices[0]['company_id'])->first()->company_name;

        $etaMiDevices = Device::where([
            ['category_id', '=', $category_id],
            ['company_id', '=', $id],
        ])->leftJoin('vri_infos', function($join) {
            $join->on('devices.device_id', '=', 'vri_infos.device_id');
        })->Join('etami_devices', function($join) {
            $join->on('devices.device_id', '=', 'etami_devices.device_id');
        })->get()->toArray();

        $partyMiDevices = Device::where([
            ['category_id', '=', $category_id],
            ['company_id', '=', $id],
        ])->leftJoin('vri_infos', function($join) {
            $join->on('devices.device_id', '=', 'vri_infos.device_id');
        })->Join('partymi_devices', function($join) {
            $join->on('devices.device_id', '=', 'partymi_devices.device_id');
        })->get()->toArray();

        $devices = array_merge(
            array_merge($singleMiDevices, $etaMiDevices), $partyMiDevices);

//        dd($devices);
        array_multisort(array_column($devices, 'manufactureNum'), SORT_DESC,
            array_column($devices, 'device_id'),SORT_ASC, SORT_NUMERIC,
            $devices);

        $prevId = null;
        foreach($devices as $deviceId => $device) {
            if(isset($devices[$prevId]) && $devices[$prevId]['manufactureNum'] == $device['manufactureNum']) {
                unset($devices[$prevId]);
            }
            $prevId = $deviceId;
        }

        array_multisort(
            array_column($devices, 'device_id'),SORT_DESC, SORT_NUMERIC,
            $devices);

        return view('pages.company', [
            'category_id'       => $category_info[0]['category_id'],
            'company_id'        => $id,
            'category'          => $category_info[0],
            'devices'           => $devices,
            'name'              => $company_name,
            'category_name'     => $category_name,
        ]);
    }
}
