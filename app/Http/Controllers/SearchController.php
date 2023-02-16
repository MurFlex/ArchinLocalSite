<?php

namespace App\Http\Controllers;

use App\Models\Devices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        if(!empty($request->all())){
            $request = $request->all();
            $results = array();
            $devices = DB::select(DB::raw('SELECT * FROM devices WHERE LOWER(name) LIKE ' . "'%" . strtolower($request['company_name'] . "%'")));

            foreach($devices as $device) {
                $results[] = $device->category_id;
            }

            if(empty($result)) {
                return view('dev',
                    [
                        'request' => $request,
                    ]);
            } else {
                return view('dev',
                    [
                        'results' => $results,
                        'request' => $request,
                    ]);
            }

        } else {
            return view('dev');
        }
    }
}
