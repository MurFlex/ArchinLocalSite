<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class CompanyListController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Controller for displaying categories of requested company
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index($id)
    {
        $storages = Storage::where('company_id', '=', $id)->leftJoin('categories', function($join){
            $join->on('storages.category_id', '=', 'categories.category_id');
        })->get();

        $count = 0;
        $inapplicableCount = 0;

        $company_name = Company::where('company_id', '=', $storages->first()->company_id)->first()->company_name;

        $categories = array();

        foreach($storages as $storage) {
            if(!isset($categories[$storage->category_id])) {
                $categories[$storage->category_id] = [
                    'category_title' => $storage->category_title,
                    'category_type' => $storage->type,
                    'count' => $storage->count,
                    'inapplicable' => $storage->inapplicable,
                ];
            } else {
                $categories[$storage->category_id]['count'] += $storage->count;
                $categories[$storage->category_id]['inapplicable'] += $storage->inapplicable;
            }
            $count += $storage->count;
            $inapplicableCount += $storage->inapplicable;
        }

        uasort($categories, fn($a, $b) => $b['count'] <=> $a['count']);

        return view('pages.company_categories', [
            'inapplicableCount' => $inapplicableCount,
            'count'             => $count,
            'categories'        => $categories,
            'name'              => $company_name,
            'company_id'        => $id,
        ]);
    }
}
