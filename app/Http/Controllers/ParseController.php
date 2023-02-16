<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParseController extends Controller
    {
        /**
         * @param string $url
         * @return string
         */
        private function getData ($url)
        {
            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, 1);

            $curl_response = curl_exec($curl);
            if(strpos($curl_response, 'HTTP/1.1 200') !== 0) {
                return ('Bad response!');
            }

            return json_decode(stristr($curl_response, '{'), 1)['result'];
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index(Request $request)
    {
        $service_url = 'https://fgis.gost.ru/fundmetrology/eapi/vri/1-';
        $request = $request->all();
        $current_time = time();
        $delay = 30 * 60;
        $data = json_decode(file_get_contents('../app/Helpers/new_result.json'));


        if(!empty($request)) {
            file_put_contents('../app/Logs/result-' . date("Y-m-d H-i-s") . ' Started' . '.json', '');
            if(isset($request['from']) && isset($request['until']) && !isset($request['id'])) {
                $start = (int) $request['from'];
                $end = (int) $request['until'];
                $n = 0;
//                file_put_contents('../app/Logs/result-' . date("Y-m-d H-i-s") . ' Started' . '.json', '');
                $result = array();

                foreach($data as $category_id => $item) {
                    if($n < $start) {
                        $n++;
                        continue;
                    } elseif ($n > $end) {
                        break;
                    } else {
                        foreach($item->id as $id) {
                            $current_url = $service_url . $id;
                            $device_data = $this->getData($current_url);
//                            dd($device_data);
                            if($data !== 'Bad response!') {
//                                $result[] = $device_data;
//                                dd($id);
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

//                while($start <= $end) {
//                    $current_url = $service_url . $start;
//                    $data = $this->getData($current_url);
//
//                    if($data !== 'Bad response!') {
//                        $result[] = $data;
//                    } else {
//                        print('Bad Response!');
//                    }
//
//                    $start = $start + 1;
//
//                    if((time() - $current_time) > $delay) {
//                        $current_time = time();
//                        $fp = fopen('../app/Logs/result-' . date("Y-m-d H-i-s") . '.json', 'w');
//                        fwrite($fp, json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ));
//                        fclose($fp);
//                    }
//
//                    sleep(1);
//                }

                $fp = fopen('../app/Logs/' . date("Y-m-d H-i-s") . '.json', 'w');
                fwrite($fp, json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                fclose($fp);

            } elseif(isset($request['id']) && !isset($request['from']) && !isset($request['until'])) {
                $current_url = $service_url . $request['id'];
                $data = $this->getData($current_url);
                if($data !== 'Bad response!') {
//                    $this->printData((array)$data);
                    $fp = fopen('../app/Logs/result-' . date("Y-m-d H-i-s") . '.json', 'w');
                    fwrite($fp, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                    fclose($fp);
                } else {
                    print('Bad Response!');
                }
            } else {
                print('Wrong request!');
            }
            file_put_contents('../app/Logs/result-' . date("Y-m-d H-i-s") . ' Ended' . '.json', '');
            return redirect('/parse');
        } else {
            $dir = '../app/Logs/';
            $files = scandir($dir);

            $filtered_files = array();
            foreach(array_slice($files, 2) as $file) {
                $exploded_array = explode(" ", $file);
//                dd(end($exploded_array));
                if(end($exploded_array) !== "Started.json" and end($exploded_array) !== 'Ended.json') {
                    $filtered_files[] = $file;
                }
            }

            return view('parse', ['files' => $filtered_files]);
        }
    }
}
