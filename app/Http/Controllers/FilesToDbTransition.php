<?php

namespace App\Http\Controllers;

use App\Models\singleMIDevice;
use App\Models\PartymiDevice;
use App\Models\EtamiDevice;
use App\Models\Company;
use Illuminate\Http\Request;

class FilesToDbTransition extends Controller
{
    public function index()
    {
        $dir = '../app/Logs/';
        $files = scandir($dir);

        $filtered_files = array();
        foreach(array_slice($files, 2) as $file) {
            $exploded_array = explode(" ", $file);
            if(end($exploded_array) !== "Started.json" && end($exploded_array) !== 'Ended.json') {
                $filtered_files[] = $file;
            }
        }

        $data = [];

        foreach ($filtered_files as $file) {
            $data += json_decode(file_get_contents($dir . $file), 1);
        }

//        foreach($data as $id => $element) {
//            if(isset($element['vriInfo']['miOwner']))
//                $miOwner = $element['vriInfo']['miOwner'];
//
//            $company = new Company();
//
//            if(!count(Company::where('company_name', '=', $miOwner)->get())){
//                $company->company_name = $miOwner;
//                $company->save();
//            }
//        }

        foreach($data as $id => $element) {
            foreach($element as $item) {
                var_dump(key($element));
                continue;
                $classname = key($item) . 'Device';
                if ($classname == 'singleMIDevice' && count(SinglemiDevice::where('device_id', '=', $id)->get()) == 0) {
                    $singleMiDevice = new SinglemiDevice();
                    $singleMiDevice->device_id = $id;
                    $singleMiDevice->mitypeNumber = isset($item['singleMI']['mitypeNumber']) ? $item['singleMI']['mitypeNumber'] : null;
                    $singleMiDevice->mitypeURL = isset($item['singleMI']['mitypeURL']) ? $item['singleMI']['mitypeURL'] : null;
                    $singleMiDevice->mitypeType = isset($item['singleMI']['mitypeType']) ? $item['singleMI']['mitypeType'] : null;
                    $singleMiDevice->mitypeTitle = isset($item['singleMI']['mitypeTitle']) ? $item['singleMI']['mitypeTitle'] : null;
                    $singleMiDevice->manufactureNum = isset($item['singleMI']['manufactureNum']) ? $item['singleMI']['manufactureNum'] : null;
                    $singleMiDevice->inventoryNum = isset($item['singleMI']['inventoryNum']) ? $item['singleMI']['inventoryNum'] : null;
                    $singleMiDevice->manufactureYear = isset($item['singleMI']['manufactureYear']) ? $item['singleMI']['manufactureYear'] : null;
                    $singleMiDevice->modification = isset($item['singleMI']['modification']) ? $item['singleMI']['modification'] : null;
                    $singleMiDevice->save();
                } elseif ($classname == 'partyMIDevice' && count(partyMIDevice::where('device_id', '=', $id)->get()) == 0) {
                    $partyMiDevice = new partyMIDevice();
                    $partyMiDevice->device_id = $id;
                    $partyMiDevice->mitypeNumber = isset($item['partyMI']['mitypeNumber']) ? $item['partyMI']['mitypeNumber'] : null;
                    $partyMiDevice->mitypeURL = isset($item['partyMI']['mitypeURL']) ? $item['partyMI']['mitypeURL'] : null;
                    $partyMiDevice->mitypeType = isset($item['partyMI']['mitypeType']) ? $item['partyMI']['mitypeType'] : null;
                    $partyMiDevice->modification = isset($item['partyMI']['modification']) ? $item['partyMI']['modification'] : null;
                    $partyMiDevice->quantity = isset($item['partyMI']['quantity']) ? $item['partyMI']['quantity'] : null;
                    $partyMiDevice->mitypeTitle = isset($item['partyMI']['mitypeTitle']) ? $item['partyMI']['mitypeTitle'] : null;
                    $partyMiDevice->save();
                } elseif ($classname == 'etaMIDevice' && count(etaMIDevice::where('device_id', '=', $id)->get()) == 0) {
                    $etaMiDevice = new etaMIDevice();
                    $etaMiDevice->device_id = $id;
                    $etaMiDevice->regNumber = isset($item['etaMI']['regNumber']) ? $item['etaMI']['regNumber'] : null;
                    $etaMiDevice->mitypeNumber = isset($item['etaMI']['mitypeNumber']) ? $item['etaMI']['mitypeNumber'] : null;
                    $etaMiDevice->mitypeURL = isset($item['etaMI']['mitypeURL']) ? $item['etaMI']['mitypeURL'] : null;
                    $etaMiDevice->mitypeType = isset($item['etaMI']['mitypeType']) ? $item['etaMI']['mitypeType'] : null;
                    $etaMiDevice->mitypeTitle = isset($item['etaMI']['mitypeTitle']) ? $item['etaMI']['mitypeTitle'] : null;
                    $etaMiDevice->manufactureNum = isset($item['etaMI']['manufactureNum']) ? $item['etaMI']['manufactureNum'] : null;
                    $etaMiDevice->manufactureYear = isset($item['etaMI']['manufactureYear']) ? $item['etaMI']['manufactureYear'] : null;
                    $etaMiDevice->modification = isset($item['etaMI']['modification']) ? $item['etaMI']['modification'] : null;
                    $etaMiDevice->rankCode = isset($item['etaMI']['rankCode']) ? $item['etaMI']['rankCode'] : null;
                    $etaMiDevice->rankTitle = isset($item['etaMI']['rankTitle']) ? $item['etaMI']['rankTitle'] : null;
                    $etaMiDevice->schemaTitle = isset($item['etaMI']['schemaTitle']) ? $item['etaMI']['schemaTitle'] : null;
                    $etaMiDevice->save();
                }
            }

            break;
        }
    }
}
