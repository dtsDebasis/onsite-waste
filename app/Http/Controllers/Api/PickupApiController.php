<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\File;
use App\Models\CompanyHauling;
use Auth;
use Illuminate\Http\Request;
use Exception;
use Response;
use App\Helpers\Helper;
use App\Exports\ManifestExport;
use App\Exports\InvoiceExport;
use Excel;

class PickupApiController extends Controller {
    protected $_user;
    protected $_siteSettings;
    protected $_offset;
    public $successStatus = 200;
	public function __construct($parameters = array()) {
		parent::__construct($parameters);
        $this->middleware(function (Request $request, $next) {
            $this->_user = Auth::user();
            return $next($request);
        });
        $this->_siteSettings = \App\Helpers\Helper::SiteSettingDetails();
		$this->_module      = 'User';
		$this->_routePrefix = 'users';
		$this->_model       = new CompanyHauling();
        $this->_offset      = \Config::get('settings.per_page_record');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function manifestList(Request $request) {
        try{
            $srch_params = $request->all();
            $branch_ids = \App\Helpers\Helper::getUserAllBranchId($this->_user);
            $srch_params['with'] = ['hauling_details'];
            $srch_params['branch_id'] = $branch_ids;
            $data = app('App\Models\Manifest')->getListing($srch_params,$this->_offset);
            return Helper::rj('List fetch successfully .', $this->successStatus,$data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }
    public function manifestListExport(Request $request) {
        try{
            $srch_params = $request->all();
            $rules = array(
                'ids' => 'required|array'
            );
            $messages = [
               
            ];
            $validator = \Validator::make($srch_params, $rules,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 400);
            }
            else{
                //$branch_ids = \App\Helpers\Helper::getUserAllBranchId($this->_user);
                $srch_params['with'] = ['hauling_details'];
                $data = [];
                $cdn = \App\Models\Cdn::where("status", 1)->first();
                $file_name = 'manifest_'.$this->_user->id;
                $this->__manifestExceltExport($srch_params, $file_name);
                $data['link'] = url('uploads/manifest-excel/' . $file_name . '.xlsx');
                return Helper::rj('List fetch successfully .', $this->successStatus,$data);
            }
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
        
    }
    private function __manifestExceltExport($srch_param, $filename) {  
        return Excel::store(new ManifestExport($srch_param), $filename . '.xlsx', 'excel');
    }
    public function haulingList(Request $request) {
        try{
            $srch_params = $request->all();
            $branch_ids = \App\Helpers\Helper::getUserAllBranchId($this->_user);
            $srch_params['with'] = ['branch_details','branch_details.addressdata','package_details' => function($q){return $q->select('id','name');}];
            $srch_params['branch_id'] = $branch_ids;
            $date = date('Y-m-d');
            $srch_params['start_date'] = date('Y-m-d', strtotime($date . ' +1 day'));
            $srch_params['end_date'] = date('Y-m-d', strtotime($srch_params['start_date'] . ' +30 day'));
            $data = app('App\Models\CompanyHauling')->getListing($srch_params,$this->_offset);
            return Helper::rj('List fetch successfully .', $this->successStatus,$data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }

    public function haulingCancellation(Request $request){
        try{
            $input = $request->all();
            //$siteSettings = \App\Helpers\Helper::SiteSettingDetails();
            $rules = array(
                'company_hauling_id' => 'required|integer',
                'description' => 'required',
                //'info_documents.*' =>'required|mimes:jpeg,png,jpg,doc,docx,pdf|max:' . (int) $siteSettings['file_size'] * 1024,
            );
            $messages = [
                //'info_documents.*.max' => 'File size cannot exceed ' . $siteSettings['file_size'] . ' MB'
            ];
            $validator = \Validator::make($input, $rules,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 400);
            }
            else{
                $branch_ids = \App\Helpers\Helper::getUserAllBranchId($this->_user);
                $input['user_id'] = $this->_user->id;
                $data = \App\Models\CompanyHaulingCancellationRequest::create($input);
                return Helper::rj('Cancellation request send successfully .', $this->successStatus,$data);
            }
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }

    public function requestAdditionalHauling(Request $request){
        try{
            $input = $request->all();
            $rules = array(
                'branch_id' => 'required|integer',
                'number_of_boxes' => 'required|integer',
                'date' => 'required'
            );
            $messages = [
            ];
            $validator = \Validator::make($input, $rules,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 400);
            }
            else{
                $input['company_id'] = $this->_user->company_id;
                $input['status'] = 5;
                $input['user_id'] = $this->_user->id;
                $data = \App\Models\CompanyHauling::create($input);
                return Helper::rj('Additional pickup request successfully .', $this->successStatus,$data);
            }
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }  
    }

    public function requestPackageChange(Request $request){
        try{
            $input = $request->all();
            $rules = array(
                'branch_id'  => 'required|integer',
                'name' => 'required|max:200',
                'monthly_rate' => 'required',
                'boxes_included' => 'required|integer',
                'frequency_type' => 'required',
                'frequency_number' => 'required',
                'duration_type' => 'required',
                'duration_number' => 'required'

            );
            $messages = [
            ];
            $validator = \Validator::make($input, $rules,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 400);
            }
            else{
                $input['company_id'] = $this->_user->company_id;
                $input['user_id'] = $this->_user->id;
                $data = \App\Models\PackageChangeRequest::create($input);
                return Helper::rj('Pickup change request successfully .', $this->successStatus,$data);
            }
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }  
    }

    public function customerRequestStore(Request $request){
        try{
            $input = $request->all();
            $rules = array(
                'branch_id'  => 'required|integer',
                'description' => 'required',
                'type' => 'required|integer'
            );
            $messages = [
            ];
            $validator = \Validator::make($input, $rules,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 400);
            }
            else{
                $input['company_id'] = $this->_user->company_id;
                $input['user_id'] = $this->_user->id;
                $data = \App\Models\CustomerRequest::create($input);
                return Helper::rj('Request sent successfully .', $this->successStatus,$data);
            }
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }  
    }

    public function branchList(Request $request){
        try{
            $srch_params = $request->all();
            $branch_ids = \App\Helpers\Helper::getUserAllBranchId($this->_user);
            $srch_params['with'] = ['addressdata'];
            $srch_params['ids'] = $branch_ids;
            $data = app('App\Models\CompanyBranch')->getListing($srch_params,$this->_offset);
            return Helper::rj('List fetch successfully .', $this->successStatus,$data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }

    public function inventoryList(Request $request){
        try{
            $srch_param = $request->all();
            if(isset($srch_param['location']) && $srch_param['location']){
                $branch_ids[] = $srch_param['location'];
            }
            else{
                $branch_ids = \App\Helpers\Helper::getUserAllBranchId($this->_user);
            }
            $srch_params['with'] = ['addressdata'];
            $srch_params['ids'] = $branch_ids;
            $data = app('App\Models\CompanyBranch')->getListing($srch_params,$this->_offset);
            foreach($data as $key=>$val){
                // $url = 'https://wastetech-dev.s3-us-west-2.amazonaws.com/api/mock/inventory.json';
                // $devices_url = 'https://wastetech-dev.s3-us-west-2.amazonaws.com/api/mock/devices.json';

                $url = 'locations/'.$val['uniq_id'].'/inventory' ;
                $devices_url = 'locations/'.$val['uniq_id'].'/devices';

                $containerInventory = \App\Helpers\Helper::callAPI('GET',$url,[]);
                $containerInventory = json_decode($containerInventory, true);

                $devicesInventory = \App\Helpers\Helper::callAPI('GET',$devices_url,[]);
                $devicesInventory = json_decode($devicesInventory, true);

                $val->inventory_details = $containerInventory;
                $val->devices_details = $devicesInventory;
            }
            return Helper::rj('List fetch successfully .', $this->successStatus, $data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }
    public function invoiceList(Request $request){
        try{
            $srch_params = $request->all();
            $branch_ids = [];
            if(!isset($srch_params['begin_date']) || (isset($srch_params['begin_date']) && empty($srch_params['begin_date']))){
                $srch_params['begin_date'] = date('Y-m').'-01';
            }
            if(!isset($srch_params['end_date']) || (isset($srch_params['end_date']) && empty($srch_params['end_date']))){
                $srch_params['end_date'] = date('Y-m-t');
            }
            if(isset($srch_param['location']) && $srch_param['location']){
                $branch_ids[] = $srch_param['location'];
            }
            else{
                $branch_ids = \App\Helpers\Helper::getUserAllBranchId($this->_user);
            }

            $branch_ids = [1,10];
            $srch_params['begin_date'] = '2021-01-01';
            $srch_params['end_date'] = '2021-07-01';
            $invData = [];
            $accountdetails = [];
            $api_key = \Config::get('settings.RECURLY_KEY');
            $client = new \Recurly\Client($api_key);
            $options = [
                'params' => [
                    //'limit' => 1,
                    'begin_time' => $srch_params['begin_date'],
                    'end_time' => $srch_params['end_date']
                ]
            ];
            $key = 0;
            foreach($branch_ids as $key1 => $val){
                $companybranch = \App\Models\CompanyBranch::select('name','uniq_id','recurring_id')->where('id',$val)->first();

                if($companybranch && $companybranch->recurring_id){
                    $account = $client->getAccount($companybranch->recurring_id);
                    $invoices = $client->listAccountInvoices($account->getId(),$options);	
                    
                    foreach($invoices as $invoice) {
                        // print_r($invoice);
                        $invData[$key]['branch_name'] = $companybranch->name;
                        $invData[$key]['branch_code'] = $companybranch->uniq_id;
                        $invData[$key]['code'] = $invoice->getAccount()->getCode();
                        $invData[$key]['id'] = $invoice->getId();
                        $invData[$key]['company'] = $invoice->getAddress()->getCompany();
                        $invData[$key]['total'] = $invoice->getTotal();
                        $invData[$key]['state'] = $invoice->getState();
                        $invData[$key]['created_at'] = $invoice->getCreatedAt();
                        $invData[$key]['balance'] = $invoice->getBalance();
                        $invData[$key]['subtotal'] = $invoice->getSubtotal();
                        $invData[$key]['discount'] = $invoice->getDiscount();
                        foreach($invoice->getLineItems() as $line){
                            $invData[$key]['line_items'][] = array(
                                'desc' => $line->getDescription(),
                                'amount' => $line->getAmount(),

                            );
                        }
                        $key++;
                    }
                }
            }         
            $data = $invData;
            return Helper::rj('List fetch successfully .', $this->successStatus,$data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }

    }

    public function getInvoiceExcelLink(Request $request){
        try{
            $srch_params = $request->all();
            $rules = array(
                'ids' => 'required'
            );
            $messages = [
               
            ];
            $validator = \Validator::make($srch_params, $rules,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 400);
            }
            else{
                $ids = explode(',',$srch_params['ids']);
                $invData = [];
                foreach( $ids as $key=>$id){
                    $lineitems=[];
                    $api_key = \Config::get('settings.RECURLY_KEY');
                    $client = new \Recurly\Client($api_key);
                    $invoice = $client->getInvoice($id);
                    $invData[$key]['id'] = $invoice->getId();
                    $invData[$key]['code'] = $invoice->getAccount()->getCode();
                    $invData[$key]['created_at'] = \App\Helpers\Helper::dateConvert($invoice->getCreatedAt());
                    $invData[$key]['company'] = $invoice->getAddress()->getCompany();
                    $invData[$key]['amount'] = $invoice->getTotal();
                    $invData[$key]['state'] = $invoice->getState();
                    foreach($invoice->getLineItems() as $line){
                        $lineitems[] = $line->getDescription();
                    }
                    $invData[$key]['lineItems'] = implode(',',$lineitems);
                }
                $file_name = 'invoice_'.$this->_user->id;
                Excel::store(new InvoiceExport($invData), $file_name . '.xlsx', 'excel');
                $data['link'] = url('uploads/manifest-excel/' . $file_name . '.xlsx');
                return Helper::rj('export excel generate successfully .', $this->successStatus,$data);
            }
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }

    public function packageLists(Request $request){
        try{
            $srch_params = $request->all();
            $branch_ids = [];
            if(isset($srch_params['location']) && $srch_params['location']){
                $branch_ids = explode(',',$srch_params['location']);
            }
            else{
                $branch_ids = \App\Helpers\Helper::getUserAllBranchId($this->_user);
            }

            $data = app('App\Models\Package')->getListing(['branch_id' => $branch_ids,'with' => ['companybranch' => function($q){return $q->select('id','name');}]]);
            
            return Helper::rj('Package successfully .', $this->successStatus,$data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }
    public function transactionLists(Request $request){
        try{
            $srch_params = $request->all();
            $branch_ids = [];
            if(isset($srch_param['location']) && $srch_param['location']){
                $branch_ids[] = explode(',',$srch_param['location']);
            }
            else{
                $branch_ids = \App\Helpers\Helper::getUserAllBranchId($this->_user);
            }
            $data = app('App\Models\TransactionalPackage')->getListing(['branch_id' => $branch_ids]);
            return Helper::rj('Transaction successfully .', $this->successStatus,$data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }

    public function getTransactionPackageDetails(Request $request){
        $input = $request->all();
        try{
            $srch_params = $request->all();
            $rules = array(
                'location' => 'required'
            );
            $messages = [
               
            ];
            $validator = \Validator::make($srch_params, $rules,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 400);
            }
            else{
                $company_id = $this->_user->company_id;
                $data = app('App\Models\TransactionalPackage')->getListing(['branch_id' => $input['location'],'company_id' => $company_id,'single_record' => true]);
                return Helper::rj('Transaction details successfully .', $this->successStatus,$data);
            }
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }
}