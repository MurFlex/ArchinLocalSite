<?php

namespace App\Http\Controllers;

use App\Models\Devices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        if ($request['company_name'] == '' && $request['device_name'] == '') {
            $companies = array();
            $data = json_decode(file_get_contents('../app/Logs/result-2023-02-16 10-07-15.json'));

            foreach($data as $id => $element) {
                if(isset($element->vriInfo->miOwner))
                    $miOwner = $element->vriInfo->miOwner;
                if(!in_array($miOwner, $companies)) {
                    $companies[] = $miOwner;
                }
            }
            return view('dev', [
                'companies' => $companies,
            ]);
        } else {
            $request = $request->all();
            $results = array();
            $companies = array();
//            $devices = DB::select(DB::raw('SELECT * FROM devices WHERE LOWER(name) LIKE ' . "'%" . strtolower($request['company_name'] . "%'")));

            $data = json_decode(file_get_contents('../app/Logs/result-2023-02-16 10-07-15.json'));

            foreach($data as $id => $element) {
                if (isset($element->vriInfo->miOwner))
                    $miOwner = $element->vriInfo->miOwner;
                if (!in_array($miOwner, $companies)) {
                    $companies[] = $miOwner;
                }

                if (str_contains(mb_strtolower($miOwner), mb_strtolower($request['company_name']))) {
                    $results[$id] = $miOwner;
                }
            }

            if(empty($results)) {
                return view('dev',
                    [
                        'companies' => $companies,
                        'request' => $request,
                    ]);
            } else {
                return view('dev',
                    [
                        'companies' => $companies,
                        'results' => $results,
                        'request' => $request,
                    ]);
            }
        }
    }
}
