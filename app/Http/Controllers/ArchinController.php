<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ArchinController extends Controller
{
    public function index(Request $request) {
        if(isset($request['registry']) || isset($request['name']) || isset($request['type']) || isset($request['modification']))
            return redirect()->away('https://fgis.gost.ru/fundmetrology/cm/results?filter_mi_mitnumber=' . $request['registry'] . '&filter_mi_mititle=' . $request['name'] . '&filter_mi_mitype=' . $request['type'] . '&filter_mi_modification=' . $request['modification'] . '&activeYear=Все');
        else {
            return view('pages.archin');
        }
    }
}
