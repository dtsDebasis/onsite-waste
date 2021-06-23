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
use App\Models\BranchTe5000Information;

class BranchTe500Controller extends Controller {
	public function __construct($parameters = array()) {
		parent::__construct($parameters);
		$this->_module      = 'TE-5000 Management';
        $this->_routePrefix = 'te-5000-informations';
        $this->_model       = new BranchTe5000Information;
	}

	public function index(Request $request){
		$this->initIndex([], false);
        $srch_params                    = $request->all();
        $this->_data['srch_params'] = $srch_params;
		$this->_data['pageHeading'] = $this->_module;
        $srch_params['with'] = ['branch_details','package_details'];
		$this->_data['data'] = $this->_model->getListing($srch_params, $this->_offset);
		return view('admin.' . $this->_routePrefix . '.index',$this->_data);
	}

	public function create(Request $request) {
		$this->data['pageHeading'] = 'Add '.$this->_module ;
		return view('admin.' . $this->_routePrefix . '.create',$this->data);
	}

    public function infoDetails(Request $request){
        $input = $request->all();
        try{
			$validationRules = [
				'id' => 'required|integer'
			];
			$validator = \Validator::make($request->all(), $validationRules);
			if ($validator->fails()) {
				throw new Exception($validator->errors()->first(),200);
			}
			else{ 
				$data = $this->_model->getListing(['id'=>$input['id']]);
				if($data && $data->te_5000_info){
					$view = view("admin.te-5000-informations.te_5000_info",['data'=>$data])->render();
					return Response::json(['success'=>true,'msg'=>'Details fetch successfully','html'=>$view]);
				}
				else{
					throw new Exception('Details not found',200);
				}
			}

		} catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }
    }

	
}
