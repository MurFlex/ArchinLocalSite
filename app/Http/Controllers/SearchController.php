<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        if ($request['company_name'] == '' && $request['device_name'] == '') {
//            $companies = array();
//            $dir = '../app/Logs/';
////            $data = json_decode(file_get_contents('../app/Logs/result-2023-02-16 10-07-15.json'));
//            $filtered_files = array();
//            $data = array();
//            $files = scandir($dir);
//
//            foreach(array_slice($files, 2) as $file) {
//                $exploded_array = explode(" ", $file);
//                if(end($exploded_array) !== "Started.json" and end($exploded_array) !== 'Ended.json') {
//                    $filtered_files[] = $file;
//                }
//            }
//
//            foreach ($filtered_files as $file) {
//
//                $data = array_merge($data, json_decode(file_get_contents($dir . $file), 1));
//            }
//
//            foreach($data as $id => $element) {
//                if(isset($element['vriInfo']['miOwner']))
//                    $miOwner = $element['vriInfo']['miOwner'];
//                if(!in_array($miOwner, $companies)) {
//                    $companies[] = $miOwner;
//                }
//            }

            $companies = array();

            $data = json_decode(Company::all(), 1);

            foreach($data as $item) {
                $companies[$item['company_id']] = $item['company_name'];
            }

//            dd($companies);

            return view('dev', [
                'companies' => $companies,
            ]);
        } else {
//            $dir = '../app/Logs/';
//            $filtered_files = array();
//            $data = array();
//            $files = scandir($dir);
//
//            foreach(array_slice($files, 2) as $file) {
//                $exploded_array = explode(" ", $file);
//                if(end($exploded_array) !== "Started.json" and end($exploded_array) !== 'Ended.json') {
//                    $filtered_files[] = $file;
//                }
//            }
//
//            foreach ($filtered_files as $file) {
//                $data = array_merge($data, json_decode(file_get_contents($dir . $file), 1));
//            }
//
//            $request = $request->all();
//            $results = array();
//            $companies = array();
//            $devices = DB::select(DB::raw('SELECT * FROM devices WHERE LOWER(name) LIKE ' . "'%" . strtolower($request['company_name'] . "%'")));


//            $data = json_decode(file_get_contents('../app/Logs/result-2023-02-16 10-07-15.json'));

//            foreach($data as $id => $element) {
//                if (isset($element['vriInfo']['miOwner']))
//                    $miOwner = $element['vriInfo']['miOwner'];
//                if (!in_array($miOwner, $companies)) {
//                    $companies[] = $miOwner;
//                }
//
//                if (str_contains(mb_strtolower($miOwner), mb_strtolower($request['company_name']))) {
//                    $results[$miOwner] = $miOwner; #todo company id instead id
//                }
//            }

            $companies = array();

            $data = DB::select(DB::raw('SELECT * FROM companies WHERE LOWER(company_name) LIKE \'%' . mb_strtolower($request['company_name'] . '%\'')));

            foreach($data as $item) {
                $companies[$item->company_id] = $item->company_name;
            }

//            dd($companies);
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
