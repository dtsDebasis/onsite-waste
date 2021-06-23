<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Speciality;

class SynchronizationController extends Controller
{
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);
        
        $this->_module      = 'Synchronizations';
        $this->_routePrefix = 'synchronizations';
        $this->_model       = '';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->initIndex();
        $srch_params                        = $request->all();
        $this->_data['data']                = [];
        $this->_data['orderBy']             = [];
        $this->_data['pageHeading']         = $this->_module;
        $this->_data['filters']             = [];
        return view('admin.' . $this->_routePrefix . '.index', $this->_data);
    }

    public function recurringSync(Request $request){
        $srch_params = $request->all();
        $validations = [
            'begin_date'   => 'required',
            'end_date'  => 'required',
        ];
        $messages = [
           
        ];            
        $validator = \Validator::make($request->all(), $validations,$messages);
        if ($validator->fails()) {
            return redirect()->back()->with('error',$validator->errors()->first());
        }
        else{
            $accountdetails = [];
			$api_key = \Config::get('settings.RECURLY_KEY');//env('RECURLY_KEY');
			
			$client = new \Recurly\Client($api_key);
			$options = [
				'params' => [
					'begin_time' => $srch_params['begin_date'],
					'end_time' => $srch_params['end_date']
				]
			];
			$accounts = $client->listAccounts($options);
            $data = [];
			foreach($accounts as $account) {
				//echo 'Account code: ' . $account->getCode() . PHP_EOL;
				$code = $account->getCode();
                $id = $account->getId();
                \App\Models\CompanyBranch::where(['uniq_id' => $code])->update(['recurring_id' => $id]);
				
			}
            return redirect()->route($this->_routePrefix . '.index')->with('success','Synchrozied successfully');
        }
    }
}