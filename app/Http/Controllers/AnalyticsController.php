<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Analytics;
use Illuminate\Http\Request;
use App\Models\LocationGroup;
use App\Jobs\UpdateBranchAnalytics;

class AnalyticsController extends Controller
{
    public function __construct($parameters = array()) {
		parent::__construct($parameters);
		$this->_module      = 'Data Analytics';
		$this->_routePrefix = 'analytics';
		$this->_offset = 15;
	}

    public function index($type='all',$num_of_month = 12, $branch_id = 0)
    {
        $date_format = 'y-m-d';
        for ($i=1; $i <= $num_of_month; $i++) {
            $start_date = date('Y-m-01', strtotime(date($date_format, strtotime('-'.$i.' month'))));
            $end_date = date('Y-m-t', strtotime(date($date_format, strtotime('-'.$i.' month'))));

            //Analytics::processAnalytics($start_date,$end_date,$branch_id,$type);
            UpdateBranchAnalytics::dispatch($start_date,$end_date,$branch_id,$type);
        }
    }

    public function companylist(Request $request){
		$srch_params             = $request->all();
		$srch_params['with'] = ['addressdata','speciality.speciality','categories'];
		$srch_params['whereHas'] = 'categories';
        $companyModel = new Company();
		$this->_data["companies"] = $companyModel->getListing($srch_params, $this->_offset)->appends($request->input());
		$this->_data['pageHeading'] = 'CUSTOMER LISTING';
        return view('admin.analytics.companylist',$this->_data);
    }

    public function companydata(Request $request, $company_id, $category_id,$group_id=null){
        $srch_params             = $request->all();
        $this->_data["srch_params"] = $srch_params;
        $this->_data["company_id"] = $company_id;
        $this->_data["category_id"] = $category_id;
        $this->_data["group_id"] = $group_id && $group_id != 0 ? $group_id : null ;
        $date_format = 'y-m-d';

        if ($request->start_date && $request->end_date) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
        } else {
            $start_date = date('Y-m', strtotime(date($date_format, strtotime('-2 month'))));
            $end_date = date('Y-m', strtotime(date($date_format, strtotime('-0 month'))));
        }

        $this->_data["start_date"] = $start_date;
        $this->_data["start_date_filter"] = date($start_date.'-01');
        $this->_data["end_date"] = $end_date;
        $this->_data["end_date_filter"] = date($end_date.'-28');

        $groups = LocationGroup::with(['normalization_details','grouplocationmap'])->where('company_id', $company_id)->where('category_id',$category_id)->get();

        $this->_data["groups"] = $groups;
        $this->_data['pageHeading'] = 'Zone wise analytics';
        $this->_data['breadcrumb'][route('analytics.companylist')] = 'Customer listings';
		$this->_data['breadcrumb']['#']      = 'analytics';


		return view('admin.analytics.companydata',$this->_data);
    }


}
