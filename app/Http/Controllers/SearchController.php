<?php

namespace App\Http\Controllers;

use App\Models\Devices;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        if(!empty($request->all())){
            $result = array();
            $devices = Devices::all()->where('name', '=', $request->all()['company_name']);
            foreach($devices as $device) {
                array_push($result, $device->getAttribute('category_id'));
            }

            if(empty($result)) {
                return view('dev',
                    [
                        'request' => $request->all(),
                    ]);
            } else {
                return view('dev',
                    [
                        'results' => $result,
                        'request' => $request->all(),
                    ]);
            }

        } else {
            return view('dev');
        }
    }
}
