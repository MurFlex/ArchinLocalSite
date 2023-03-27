<?php

namespace App\Http\Controllers;

use App\Models\ApplicableDevice;
use App\Models\Changes;
use App\Models\Company;
use App\Models\Device;
use App\Models\InapplicableDevice;
use App\Models\PartymiDevice;
use App\Models\Storage;
use App\Models\VriInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Changing keyboard layout
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
     * API for adding company to ban list
     *
     * {
     *      'id' : company id
     * }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function banCompany(Request $request) {
        if(file_exists('../app/Helpers/black_list_of_companies.txt'))
            $data = explode(' ',trim(preg_replace('/\r\n|\n\r|\n|\r/', ' ', file_get_contents('../app/Helpers/black_list_of_companies.txt'))));
        else $data = [];
        if(!in_array($request['id'], $data))
            file_put_contents('../app/Helpers/black_list_of_companies.txt', $request['id'] . PHP_EOL, FILE_APPEND);
        return response()->json(['response' => 'done']);
    }

    /**
     * API for removing company from ban list
     *
     * {
     *      'id' : company id
     * }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unbanCompany(Request $request) {
        $data = explode(' ',trim(preg_replace('/\r\n|\n\r|\n|\r/', ' ', file_get_contents('../app/Helpers/black_list_of_companies.txt'))));
        unlink('../app/Helpers/black_list_of_companies.txt');
        if (($key = array_search($request['id'], $data)) !== False) {
            unset($data[$key]);
        }

        foreach($data as $item) {
            file_put_contents('../app/Helpers/black_list_of_companies.txt', $item . PHP_EOL, FILE_APPEND);
        }
        return response()->json(['response' => 'done']);
    }

    /**
     * API for renaming company name
     *
     * request form:
     *  {
     *      'id' : company id
     *      'rename_from' : company name from
     *      'rename_to' : company name to
     *      'apply' : true/false if there is a company with the rename_to name
     *  }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function renameCompany(Request $request) {
        $request['apply'] = $request['apply'] == 'true' ? 1 : 0;
        $request['rename_to'] = mb_strtoupper($request['rename_to']);
        if($company = Company::where('company_name', '=', $request['rename_from'])->first()) {
            if($request['apply'] && count(Company::where('company_name', '=', $request['rename_to'])->get())) {
                $renametoId = Company::where('company_name', '=', $request['rename_to'])->first()->company_id;
                Device::where('company_id', '=', $request['id'])->update(['company_id' => $renametoId]);
                Storage::where('company_id', '=', $request['id'])->update(['company_id' => $renametoId]);
                ApplicableDevice::where('company_id', '=', $request['id'])->update(['company_id' => $renametoId]);
                InapplicableDevice::where('company_id', '=', $request['id'])->update(['company_id' => $renametoId]);
                if(!count(Device::where('company_id', '=', $request['id'])->get())) {
                    Company::where('company_id', '=', $request['id'])->delete();
                    $change = new Changes;
                    $change->ip = request()->ip();
                    $change->renamed_from = $request['rename_from'];
                    $change->renamed_to = $request['rename_to'];
                    $change->renamed_id = $request['id'];
                    $change->new_id = $renametoId;
                    $change->save();
                    return response()->json(['response' => 'success']);
                } else {
                    return response()->json(['response' => 'wrong']);
                }
            } else {
                if (count(Company::where('company_name', '=', $request['rename_to'])->get())) {
                    return response()->json(['response' => 'found']);
                } else {
                    $company = Company::where('company_name', '=', $request['rename_from'])->first();
                    $change = new Changes;
                    $company->company_name = $request['rename_to'];
                    $change->ip = request()->ip();
                    $change->renamed_from = $request['rename_from'];
                    $change->renamed_to = $request['rename_to'];
                    $change->renamed_id = $request['id'];
                    $change->save();
                    $company->save();
                    return response()->json(['response' => 'success']);
                }
            }
        } else {
            return response()->json(['response' => 'not found']);
        }
    }

    /**
     * Main search controller
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if(file_exists('../app/Helpers/black_list_of_companies.txt'))
            $ban = explode(' ',trim(preg_replace('/\r\n|\n\r|\n|\r/', ' ', file_get_contents('../app/Helpers/black_list_of_companies.txt'))));
        else
            $ban = [];
        $bannedCompanies = array();

        if ($request['company_name'] == null && $request['device_name'] == null) {
            return view('pages.dev', [
                'bannedCompanies' => null,
                'request'   => $request,
                'year'      => date('Y'),
                'companies' => [],
            ]);

        } elseif($request['company_name'] !== null && $request['device_name'] == null) {
            $data = Storage::leftJoin('companies', function($join){
                $join->on('storages.company_id', '=', 'companies.company_id');
            })->where([
                ['year', 'LIKE', '%'.$request['years'].'%'],
                ['company_name', 'LIKE', '%' . $request['company_name'] . '%'],
            ])->get();

        } elseif ($request['device_name'] !== null) {
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

            $data = Storage::where([
                ['type', 'LIKE', '%' . $request['device_name'] . '%'],
                ['year', 'LIKE', $request['years']]
            ])->leftJoin('companies', function($join) {
                $join->on('storages.company_id', '=', 'companies.company_id');
            })->where('company_name', 'LIKE', '%'. $request['company_name'] .'%')->get();

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

            $addtionalData = Storage::where([
                ['type', 'LIKE', '%' . $request['device_name'] . '%'],
                ['year', 'LIKE', $request['years']]
            ])->leftJoin('companies', function($join) {
                $join->on('storages.company_id', '=', 'companies.company_id');
            })->where('company_name', 'LIKE', '%'. $request['company_name'] .'%')->get();

            if(count($data) == 0 && count($addtionalData) == 0) {
                $data = Company::where('company_name', 'LIKE', '%' . mb_strtoupper($request['company_name']) . '%')->leftJoin('devices', function ($join){
                    $join->on('companies.company_id', '=', 'devices.company_id');
                })->leftJoin('categories', function ($join) {
                    $join->on('categories.category_id', '=', 'devices.category_id');
                })->where('category_type', 'LIKE', '%' . $request['device_name'] . '%')->get();
            }
        }

        if(isset($addtionalData)) {
            $addtionalData = $addtionalData->diff($data);
            $data = $data->merge($addtionalData);
        }

        $companies = array();

        foreach($data as $storage) {
            if($storage->count !== 0) {
                if (!in_array($storage->company_id, $ban)) {
                    if (!isset($companies[$storage->company_id])) {
                        $companies[$storage->company_id] = [
                            'name' => $storage->company_name,
                            'count' => $storage->count,
                            'inapplicable' => $storage->inapplicable,
                        ];
                    } else {
                        $companies[$storage->company_id]['count'] += $storage->count;
                        $companies[$storage->company_id]['inapplicable'] += $storage->inapplicable;
                    }
                } else {
                    $bannedCompanies[$storage->company_id] = $storage->company_name;
                }
            }
        }

        $keys = array_keys($companies);
        array_multisort(array_column($companies, 'count'), SORT_DESC, SORT_NUMERIC ,$companies, $keys);
        $companies = array_combine($keys, $companies);

        return view('pages.dev',
            [
                'bannedCompanies' => !empty($bannedCompanies) ? $bannedCompanies : null,
                'year'      => date('Y'),
                'companies' => isset($companies) ? $companies : [],
                'request'   => $request,
            ]);
    }
}
