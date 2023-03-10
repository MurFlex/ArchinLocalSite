<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Device;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * @param $value
     * @return string
     */
    private function switcher_ru($value)
    {
        $converter = array(
            'f' => 'а',	',' => 'б',	'd' => 'в',	'u' => 'г',	'l' => 'д',	't' => 'е',	'`' => 'ё',
            ';' => 'ж',	'p' => 'з',	'b' => 'и',	'q' => 'й',	'r' => 'к',	'k' => 'л',	'v' => 'м',
            'y' => 'н',	'j' => 'о',	'g' => 'п',	'h' => 'р',	'c' => 'с',	'n' => 'т',	'e' => 'у',
            'a' => 'ф',	'[' => 'х',	'w' => 'ц',	'x' => 'ч',	'i' => 'ш',	'o' => 'щ',	'm' => 'ь',
            's' => 'ы',	']' => 'ъ',	"'" => "э",	'.' => 'ю',	'z' => 'я',

            'F' => 'А',	'<' => 'Б',	'D' => 'В',	'U' => 'Г',	'L' => 'Д',	'T' => 'Е',	'~' => 'Ё',
            ':' => 'Ж',	'P' => 'З',	'B' => 'И',	'Q' => 'Й',	'R' => 'К',	'K' => 'Л',	'V' => 'М',
            'Y' => 'Н',	'J' => 'О',	'G' => 'П',	'H' => 'Р',	'C' => 'С',	'N' => 'Т',	'E' => 'У',
            'A' => 'Ф',	'{' => 'Х',	'W' => 'Ц',	'X' => 'Ч',	'I' => 'Ш',	'O' => 'Щ',	'M' => 'Ь',
            'S' => 'Ы',	'}' => 'Ъ',	'"' => 'Э',	'>' => 'Ю',	'Z' => 'Я',

            '@' => '"',	'#' => '№',	'$' => ';',	'^' => ':',	'&' => '?',	'/' => '.',	'?' => ',',
        );

        $value = strtr($value, $converter);
        return $value;
    }



    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $found = False;

        $page = isset(
            $request['page']) && $request['page'] !== '<<'? $request['page'] : 1;

        if ($request['company_name'] == null && $request['device_name'] == null) {
            $companies = array();

//            $data = json_decode(Company::all(), 1);
//
//            foreach($data as $item) {
//                $companies[$item['company_id']] = $item['company_name'];
//            }

//            $companies = array_chunk($companies, 15);

//            dd(1);
            return view('dev', [
                'found'     => null,
                'request'   => $request,
                'year'      => date('Y'),
                'storages'  => null,
                'companies' => null,
            ]);

        } elseif($request['company_name'] !== null && $request['device_name'] == null) {
            $companies = array();

            $data = Company::where('company_name', 'LIKE', '%' . mb_strtoupper($request['company_name'] . '%'))->get();

            if(count($data) == 0) {
                $found = $this->switcher_ru($request['company_name']);
                $data = Company::where('company_name', 'LIKE', '%' . mb_strtoupper($this->switcher_ru($request['company_name'] . '%')))->get();
            }
        } elseif ($request['device_name'] !== null) {
            $companies = array();

            $swapWords = [
                'С' => 'C', # Ru to eng
                'К' => 'K',
                'М' => 'M',
                'Р' => 'P',
                'Н' => 'H',
                'Е' => 'E',
                'А' => 'A',
                'Х' => 'X',
                'В' => 'B',
                'Т' => 'T',
            ];

            $request['device_name'] = strtr(mb_strtoupper($request['device_name']), $swapWords);

//            dd($request['device_name']);
            $data = Storage::where('type', 'LIKE', '%' . $request['device_name'] . '%')->leftJoin('companies', function($join) {
                $join->on('storages.company_id', '=', 'companies.company_id');
            })->get();

//            dd($data);

//             $data = Device::where('mitypeType', 'LIKE', '%' . $request['device_name'] . '%')->leftJoin('vri_Infos', function ($join) {
//                $join->on('vri_Infos.device_id', '=', 'devices.device_id');
//            })->leftJoin('companies', function ($join) {
//                $join->on('companies.company_id', '=', 'devices.company_id');
//            })->where('vrfDate', 'LIKE', '%' .  $request['years'])->leftJoin('storages', function ($join) {
//                $join->on('storages.type', '=', 'devices.mitypeType');
//            })->get();

//            dd($devices);

            // Swap to english analogue
            $swapWords = [
                'C' => 'С', # Eng to ru
                'K' => 'К',
                'M' => 'М',
                'P' => 'Р',
                'H' => 'Н',
                'E' => 'Е',
                'A' => 'А',
                'X' => 'Х',
                'B' => 'В',
                'T' => 'Т',
            ];

            $request['device_name'] = strtr(mb_strtoupper($request['device_name']), $swapWords);

//            dd($request['device_name']);

            $addtionalData = Storage::where('type', 'LIKE', '%' . $request['device_name'] . '%')->leftJoin('companies', function($join) {
                $join->on('storages.company_id', '=', 'companies.company_id');
            })->get();

//            $data += $addtionalData;
//            dd($addtionalData);

            if(count($data) == 0 && count($addtionalData) == 0) {
                $data = Company::where('company_name', 'LIKE', '%' . mb_strtoupper($request['company_name']) . '%')->leftJoin('devices', function ($join){
                    $join->on('companies.company_id', '=', 'devices.company_id');
                })->leftJoin('categories', function ($join) {
                    $join->on('categories.category_id', '=', 'devices.category_id');
                })->where('category_type', 'LIKE', '%' . $request['device_name'] . '%')->get();
            }
        }

        $storage = array();
        $inapplicable = array();

        if(isset($data)) {
            foreach ($data as $item) {
                $companies[$item->company_id] = $item->company_name;
                if(!isset($storage[$item->company_id]) && !isset($inapplicable[$item->company_id]) && $request['device_name'] !== null) {
                    $storage[$item->company_id] = $item->count;
                    $inapplicable[$item->company_id] = $item->inapplicable;
                } elseif($request['device_name'] !== null) {
                    $storage[$item->company_id] += $item->count;
                    $inapplicable[$item->company_id] += $item->inapplicable;
                }

            }
        }

        if(isset($addtionalData)) {
            foreach ($addtionalData as $item) {
                $companies[$item->company_id] = $item->company_name;
                if(!isset($storage[$item->company_id]) && !isset($inapplicable[$item->company_id]) && $request['device_name'] !== null) {
                    $storage[$item->company_id] = $item->count;
                    $inapplicable[$item->company_id] = $item->inapplicable;
                } elseif($request['device_name'] !== null) {
                    $storage[$item->company_id] += $item->count;
                    $inapplicable[$item->company_id] += $item->inapplicable;
                }
            }
        }

        arsort($storage);

        return view('dev',
            [
                'year'      => date('Y'),
                'inapplicable' => $inapplicable,
                'storages'   => !empty($storage) ? $storage : null,
                'found'     => $found,
                'companies' => isset($companies) ? $companies : [],
                'request'   => $request,
            ]);
    }
}
