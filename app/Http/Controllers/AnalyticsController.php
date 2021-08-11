<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use App\Models\Company;
use Illuminate\Http\Request;
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
		$srch_params['with'] = ['addressdata','speciality.speciality'];
        $companyModel = new Company();
		$this->_data["companies"] = $companyModel->getListing($srch_params, $this->_offset)->appends($request->input());
		$this->_data['pageHeading'] = 'CUSTOMER LISTING';
        return view('admin.analytics.companylist',$this->_data);
    }

    public function companydata(Request $request, $company_id){
        $srch_params             = $request->all();
        $this->_data["srch_params"] = $srch_params;
		return view('admin.analytics.companydata',$this->_data);
    }


}
