<?php
namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Auth;
use DB;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Exception;

class InventoryController extends Controller {
	public function __construct($parameters = array()) {
		parent::__construct($parameters);
		$this->_module      = 'Inventory Management';
        $this->_routePrefix = 'inventories';
		$this->_offset = 15;
	}

	public function index(Request $request){
		$this->initIndex([], false);
        $srch_params                    = $request->all();
        $this->_data['srch_params'] = $srch_params;
		$this->_data['pageHeading'] = 'INVENTORY MANAGEMENT';
		$data = app('App\Models\CompanyBranch')->getListing($srch_params,$this->_offset);//$this->_offset
		foreach($data as $key=>$val){
			$url = 'locations/'.$val['uniq_id'].'/inventory' ;
			$containerInventory = \App\Helpers\Helper::callAPI('GET',$url,[]);
			$containerInventory = json_decode($containerInventory, true);
			$val->inventory_details = $containerInventory;
		}
		
		$this->_data['inventories'] = $data;
		// dd($data);
		return view('admin.' . $this->_routePrefix . '.index',$this->_data);
	}

	public function create(Request $request) {
		$this->data['pageHeading'] = 'Add '.$this->_module ;
		return view('admin.' . $this->_routePrefix . '.create',$this->data);
	}

	public function getCyclingDetails(Request $request){
		$input = $request->all();
		try{
			$validationRules = [
				'branch_id' => 'required|integer'
			];
			$validator = \Validator::make($request->all(), $validationRules);
			if ($validator->fails()) {
				throw new Exception($validator->errors()->first(),200);
			}
			else{ 
				$data = \App\Helpers\Helper::getCyclingDetails($input['branch_id']);
				if($data){
					$view = view("admin.inventories.cycling_information",['data'=>$data])->render();
					return Response::json(['success'=>true,'msg'=>'List generate success fully','html'=>$view]);
				}
				else{
					throw new Exception('Details not found',200);
				}
			}

		} catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }
	}
	public function updateCyclingDetails(Request $request){
		try{
			$input = $request->all();
			$validationRules = [
				'branch_id' => 'required|integer',
				'info_id' => 'required|integer'
			];
			$validator = \Validator::make($request->all(), $validationRules);
			if ($validator->fails()) {
				throw new Exception($validator->errors()->first(),200);
			}
			else{ 				
				return Response::json(['success'=>true,'msg'=>'Ping successfully','data'=>[]]);

			}
		} catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }
	}

	public function branchInventoryUpdate(Request $request){
		try	{
			$input = $request->all();
			$url = 'locations/'.$input['locationId'].'/inventory' ;
			$containerInventory = \App\Helpers\Helper::callAPI('PUT',$url,$input);
			//\App\Helpers\Helper::messageSendToSlack('text');
			return Response::json(['success'=>true,'msg'=>'Changes has been saved successfully']);

		} catch (Exception $e) {
			return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
		}
	}
	
}
