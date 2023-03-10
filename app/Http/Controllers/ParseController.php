<?php

namespace App\Http\Controllers;

use App\Models\ApplicableDevice;
use App\Models\Category;
use App\Models\Company;
use App\Models\Device;
use App\Models\EtamiDevice;
use App\Models\InapplicableDevice;
use App\Models\PartymiDevice;
use App\Models\SinglemiDevice;
use App\Models\Storage;
use App\Models\VriInfo;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\DB;

class ParseController extends Controller
    {
        /**
         * @param $id
         * @return string
         */
        private function getData ($id)
        {
            $service_url = 'https://fgis.gost.ru/fundmetrology/eapi/vri/1-';

            $curl = curl_init($service_url . $id);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, 1);

            $curl_response = curl_exec($curl);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if($statusCode !== 200) {
                return $statusCode;
            }

            return isset(
                json_decode(stristr($curl_response, '{'),
                    1)['result']) ? json_decode(stristr(
                        $curl_response, '{'),
                1)['result'] : 'Bad response!';
        }

        private function printData (array $array)
        {
            foreach($array as $index => $element) {
                echo $index . ':';
                if(gettype($element) == 'array') {
                    echo PHP_EOL . PHP_EOL;
                    $this->printData($element);
                } else {
                    print($element);
                    echo PHP_EOL;
                }
                echo PHP_EOL;
            }
        }

        public function updateStorage () {
            $companies = Company::all();
            foreach($companies as $company) {
//                dd(Device::where('company_id', '=', $company->company_id)->get());
                $companyDevices = (Device::where('company_id', '=', $company->company_id)->get());
                foreach($companyDevices as $device) {
//                    dd($devices);
                    if(!count(Storage::where([
                        ['type', '=', $device->mitypeType],
                        ['company_id', '=', $device->company_id],
                        ['modification', '=', $device->modification],
                    ])->get())) {
                        $storage = new Storage();
                        $storage->company_id = $device->company_id;
                        $storage->type = $device->mitypeType;
                        $storage->modification = $device->modification;
                        $storage->count = 1;
                        if($device->applicable == 'N') $storage->inapplicable = 1;
                        else $storage->inapplicable = 0;
                        $storage->save();
                    } else {
                        if($device->applicable == 'N') {
                            Storage::where([
                                ['type', '=', $device->mitypeType],
                                ['company_id', '=', $device->company_id],
                                ['modification', '=', $device->modification],
                            ])->increment('inapplicable', 1);
                        }

                        Storage::where([
                            ['type', '=', $device->mitypeType],
                            ['company_id', '=', $device->company_id],
                            ['modification', '=', $device->modification],
                        ])->increment('count', 1);
                    }
                }
//                return response()->json(['response' => 'updating has been done']);
            }
            return response()->json(['response' => 'updating has been done']);
        }

        public function insertDevice ($id) {
            $data = $this->getData($id);

            $category_id = '';

            foreach($data['miInfo'] as $element) {
                $category_id = $element['mitypeNumber'];
            }

            if($data == 'Bad response!') {
                return response()->json(['response' => $data]);
            }

            if (is_array($data) && !count(Category::where('category_id', '=', $category_id)->get()) == 0) {
                if (isset($data['vriInfo']['miOwner']) && !count(Company::where('company_name', '=', (new FilesToDbTransition)->transformate($data))->get())) {
                    $company = new Company();
                    $company->company_name = trim((new FilesToDbTransition)->transformate($data));
                    $company->save();
                }

                foreach ($data as $index => $item) {
                    if ($index == 'miInfo') {
                        if (!count(Device::where('device_id', '=', $id)->get())) {
                            $classname = key($item) . 'Device';
                            $device = new Device();
                            $device->device_id = $id;
                            if (isset($data['vriInfo']['miOwner'])) {
                                $company_id = isset(Company::where('company_name', '=', (new FilesToDbTransition)->transformate($data))->first()->company_id) ?
                                    Company::where('company_name', '=', (new FilesToDbTransition)->transformate($data))->first()->company_id : null;
                                $device->company_id = $company_id;
                            } else {
                                $company_id = null;
                            }

                            $device->miInfoType = $classname;
                            if (isset($data['vriInfo']['applicable']) && !count(ApplicableDevice::where('device_id', '=', $id)->get())) {
                                $applicable = new ApplicableDevice();
                                $applicable->device_id = $id;
                                $applicable->company_id = $company_id;
                                $applicable->certNum = $data['vriInfo']['applicable']['certNum'];
                                $applicable->signPass = $data['vriInfo']['applicable']['signPass'];
                                $applicable->signMi = $data['vriInfo']['applicable']['signMi'];
                                $applicable->save();

                                $device->applicable = 'Y';
                            } elseif (isset($data['vriInfo']['inapplicable']) && !count(InapplicableDevice::where('device_id', '=', $id)->get())) {
                                $inApplicable = new InapplicableDevice();
                                $inApplicable->device_id = $id;
                                $inApplicable->company_id = $company_id;
                                $inApplicable->noticeNum = $data['vriInfo']['inapplicable']['noticeNum'];
                                $inApplicable->save();

                                $device->applicable = 'N';
                            }

                            if ($classname == 'singleMIDevice' && !count(SinglemiDevice::where('device_id', '=', $id)->get())) {
                                $singleMiDevice = new SinglemiDevice();
//                                dd($item['singleMI']['mitypeNumber']);
                                $device->category_id = isset($item['singleMI']['mitypeNumber']) ? $item['singleMI']['mitypeNumber'] : null;
                                $device->mitypeType = isset($item['singleMI']['mitypeType']) ? $item['singleMI']['mitypeType'] : null;
                                $device->modification = isset($item['singleMI']['modification']) ? $item['singleMI']['modification'] : null;
                                $device->save();
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
                            } elseif ($classname == 'partyMIDevice' && !count(partyMIDevice::where('device_id', '=', $id)->get())) {
                                $partyMiDevice = new partyMIDevice();
                                $device->category_id = isset($item['partyMI']['mitypeNumber']) ? $item['partyMI']['mitypeNumber'] : null;
                                $device->mitypeType = isset($item['partyMI']['mitypeType']) ? $item['partyMI']['mitypeType'] : null;
                                $device->modification = isset($item['partyMI']['modification']) ? $item['partyMI']['modification'] : null;
                                $device->save();
                                $partyMiDevice->device_id = $id;
                                $partyMiDevice->mitypeNumber = isset($item['partyMI']['mitypeNumber']) ? $item['partyMI']['mitypeNumber'] : null;
                                $partyMiDevice->mitypeURL = isset($item['partyMI']['mitypeURL']) ? $item['partyMI']['mitypeURL'] : null;
                                $partyMiDevice->mitypeType = isset($item['partyMI']['mitypeType']) ? $item['partyMI']['mitypeType'] : null;
                                $partyMiDevice->modification = isset($item['partyMI']['modification']) ? $item['partyMI']['modification'] : null;
                                $partyMiDevice->quantity = isset($item['partyMI']['quantity']) ? $item['partyMI']['quantity'] : null;
                                $partyMiDevice->mitypeTitle = isset($item['partyMI']['mitypeTitle']) ? $item['partyMI']['mitypeTitle'] : null;
                                $partyMiDevice->save();
                            } elseif ($classname == 'etaMIDevice' && !count(etaMIDevice::where('device_id', '=', $id)->get())) {
                                $etaMiDevice = new etaMIDevice();
                                $device->category_id = isset($item['etaMI']['mitypeNumber']) ? $item['etaMI']['mitypeNumber'] : null;
                                $device->mitypeType = isset($item['etaMI']['mitypeType']) ? $item['etaMI']['mitypeType'] : null;
                                $device->modification = isset($item['etaMI']['modification']) ? $item['etaMI']['modification'] : null;
                                $device->save();
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
                        } else {
                            return response()->json(['response' => 'device already exists']);
                        }
                    } elseif ($index == 'vriInfo') {
                        $vriInfo = new VriInfo();
                        $vriInfo->device_id = $id;
                        $vriInfo->organization = isset($item['organization']) ? $item['organization'] : null;
                        $vriInfo->signCipher = isset($item['signCipher']) ? $item['signCipher'] : null;
                        $vriInfo->miOwner = isset($item['miOwner']) ? $item['miOwner'] : null;
                        $vriInfo->vrfDate = isset($item['vrfDate']) ? $item['vrfDate'] : null;
                        $vriInfo->validDate = isset($item['validDate']) ? $item['validDate'] : null;
                        $vriInfo->vriType = isset($item['vriType']) && $item['vriType'] == 1 ? 'Первичная' : 'Периодическая';
                        $vriInfo->applicable = isset($item['applicable']) ? 'Y' : 'N';
                        $vriInfo->save();
                    } elseif ($index == 'means') {
                        break;
                    }
                }
            } else {
                return response()->json(['response' => 'device data is empty or category doesn`t exist.']);
            }
        }

        /**
         * @param Request $request
         * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
         */
        public function index(Request $request)
        {
            $request = $request->all();
            $current_time = time();
            $delay = 30 * 60;
            $data = json_decode(file_get_contents('../app/Helpers/new_result.json'));

            if(!empty($request)) {
                file_put_contents(
                    '../app/Logs/result-' . date(
                        "Y-m-d H-i-s") . ' Started' . '.json', '');
                if(isset($request['from'])
                    && isset($request['until']) && !isset($request['id'])) {
                    $start = (int) $request['from'];
                    $end = (int) $request['until'];
                    $n = 0;
                    $result = array();

                    foreach($data as $category_id => $item) {
                        if($n < $start) {
                            $n++;
                            continue;
                        } elseif ($n > $end) {
                            break;
                        } else {
                            foreach($item->id as $id) {
                                $device_data = $this->getData($id);
                                if($data !== 'Bad response!') {
                                    $result[$id] = $device_data;
                                } else {
                                    print('Bad Response!');
                                }

                                if((time() - $current_time) > $delay) {
                                    $current_time = time();
                                    $fp = fopen('../app/Logs/result-' . date("Y-m-d H-i-s") . '.json', 'w');
                                    fwrite($fp, json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ));
                                    fclose($fp);
                                }
                                sleep(1);
                            }
                        }
                        $fp = fopen('../app/Logs/' . $category_id . '.json', 'w');
                        fwrite($fp, json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ));
                        fclose($fp);
                        $result = [];
                    }

                    $fp = fopen('../app/Logs/' . date("Y-m-d H-i-s") . '.json', 'w');
                    fwrite($fp, json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                    fclose($fp);

                } elseif(isset($request['id'])
                    && !isset($request['from']) && !isset($request['until'])) {
                    $data = $this->getData($request['id']);
                    if($data !== 'Bad response!') {
                        $fp = fopen('../app/Logs/result-' . date("Y-m-d H-i-s") . '.json', 'w');
                        fwrite($fp, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                        fclose($fp);
                    } else {
                        print('Bad Response!');
                    }
                } else {
                    print('Wrong request!');
                }
                file_put_contents(
                    '../app/Logs/result-' .
                    date("Y-m-d H-i-s") .
                    ' Ended' .
                    '.json', '');
                return redirect('/parse');
            } else {
                $dir = '../app/Logs/';
                $files = scandir($dir);

                $filtered_files = array();
                foreach(array_slice($files, 2) as $file) {
                    $exploded_array = explode(" ", $file);
                    if(end(
                        $exploded_array) !== "Started.json"
                        and end($exploded_array) !== 'Ended.json') {
                        $filtered_files[] = $file;
                    }
                }

                return view('parse', [
                    'files' => $filtered_files]
                );
            }
        }
}
