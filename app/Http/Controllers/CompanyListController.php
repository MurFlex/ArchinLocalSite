<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class CompanyListController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index($name) {
        $data = json_decode(file_get_contents('../app/Logs/result-2023-02-16 10-07-15.json'));
        $name = trim($name);
        $devices = [];

        foreach($data as $id => $element) {
            if (isset($element->vriInfo->miOwner)) {
                $miOwner = $element->vriInfo->miOwner;
                if (mb_strtolower($miOwner) == mb_strtolower($name)) {
                    if(isset($element->miInfo->singleMI))
                        $devices[$id] = $element->miInfo->singleMI->mitypeTitle;
                    elseif(isset($element->miInfo->partyMI))
                        $devices[$id] = $element->miInfo->partyMI->mitypeTitle;
                    else
                        $devices[$id] = $element->miInfo->etaMI->mitypeTitle;
                }
            }
        }

        return view('company', [
            'devices' => $devices,
            'name' => $name,
        ]);
    }
}
