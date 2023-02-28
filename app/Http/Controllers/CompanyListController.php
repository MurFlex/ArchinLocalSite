<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyListController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index($name, Request $request) {
        $name = trim($name);

        try {
            $company_id = DB::select(DB::raw(
                'SELECT
                            company_id
                        FROM
                             companies
                        WHERE
                              LOWER(company_name) =' . "'" . $name . "'")
            )[0]->company_id;
        } catch (\Exception $e) {
            abort(404);
        } catch (\Error $e) {
            abort(404);
        }

        $categories = array();
        $applicable = array();
        $inapplicable = array();
        $category_type = array();

        $queryResult = Device::where(
            'company_id', '=', $company_id)->rightJoin(
                'categories', function($join) {
           $join->on('devices.category_id', '=', 'categories.category_id');
        })->get()->toArray();

//        dd($queryResult);

//        dd($queryResult);
        //todo convert into an obj
        foreach($queryResult as $result) {
            if(!isset($categories[$result['category_id']])) {
                $categories[$result['category_id']] =  $result['category_title'];
                $category_type[$result['category_id']] = $result['category_type'];
                if($result['applicable'] == 'Y') {
                    $applicable[$result['category_id']] = 1;
                    $inapplicable[$result['category_id']] = 0;
                } else {
                    $applicable[$result['category_id']] = 0;
                    $inapplicable[$result['category_id']] = 1;
                }
            } else {
                if ($result['applicable'] == 'Y')
                    $applicable[$result['category_id']]++;
                else
                    $inapplicable[$result['category_id']]++;
            }
        }

//        dd($applicable);

        return view('company_categories', [
            'category_types' => $category_type,
            'applicable' => $applicable,
            'inapplicable' => $inapplicable,
            'categories' => $categories,
            'name'      => $name,
        ]);
    }
}
