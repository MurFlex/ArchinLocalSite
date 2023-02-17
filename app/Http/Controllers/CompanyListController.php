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
        $dir = '../app/Logs/';
        $files = scandir($dir);

        $filtered_files = array();
        foreach(array_slice($files, 2) as $file) {
            $exploded_array = explode(" ", $file);
            if(end($exploded_array) !== "Started.json" and end($exploded_array) !== 'Ended.json') {
                $filtered_files[] = $file;
            }
        }

        $data = array();

        foreach ($filtered_files as $file) {
            $data = array_merge($data, json_decode(file_get_contents($dir . $file), 1));
        }

        $name = trim($name);
        $devices = [];

        foreach($data as $id => $element) {
            if (isset($element['vriInfo']['miOwner'])) {
                $miOwner = $element['vriInfo']['miOwner'];
                if (mb_strtolower($miOwner) == mb_strtolower($name)) {
                    if(isset($element['miInfo']['singleMI']))
                        $devices[$id] = $element['miInfo']['singleMI']['mitypeTitle'];
                    elseif(isset($element['miInfo']['partyMI']))
                        $devices[$id] = $element['miInfo']['partyMI']['mitypeTitle'];
                    else
                        $devices[$id] = $element['miInfo']['etaMI']['mitypeTitle'];
                }
            }
        }

        return view('company', [
            'devices' => $devices,
            'name' => $name,
        ]);
    }
}
