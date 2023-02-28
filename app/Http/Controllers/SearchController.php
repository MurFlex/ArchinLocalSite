<?php

namespace App\Http\Controllers;

use App\Models\Company;
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

        $page = isset(
            $request['page']) && $request['page'] !== '<<'? $request['page'] : 1;

        if ($request['company_name'] == '' && $request['device_name'] == '') {
            $companies = array();

//            $data = json_decode(Company::all(), 1);
//
//            foreach($data as $item) {
//                $companies[$item['company_id']] = $item['company_name'];
//            }
//
//            $companies = array_chunk($companies, 15);

            return view('dev', [
                'max_page' => count($companies),
                'page' => $page,
                'companies' => isset(
                    $companies[$page - 1]) ? $companies[$page - 1] : [],
            ]);
        } else {
            $companies = array();

            $data = DB::select(
                DB::raw(
                    'SELECT
                                *
                            FROM
                                 companies
                            WHERE
                                  LOWER(company_name)
                            LIKE
                                  \'%' . mb_strtolower(
                                      $request['company_name'] . '%\'')
                ));

            if(empty($data)) $data = DB::select(
                DB::raw(
                    'SELECT
                                *
                            FROM
                                 companies
                            WHERE
                                  LOWER(company_name)
                            LIKE
                                  \'%' . mb_strtolower(
                                      $this->switcher_ru(
                                          $request['company_name']) . '%\'')
                ));

            foreach($data as $item) {
                $companies[$item->company_id] = $item->company_name;
            }

            return view('dev',
                [
                    'companies' => isset($companies) ? $companies : [],
                    'request' => $request,
                ]);

        }
    }
}
