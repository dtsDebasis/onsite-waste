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

class SalesController extends Controller {
	public function __construct($parameters = array()) {
		parent::__construct($parameters);
		$this->_module      = 'Sales Collateral';
        $this->_routePrefix = 'sales';
	}

	public function index(Request $request){
		$this->data['pageHeading'] = $this->_module;
		return view('admin.' . $this->_routePrefix . '.index',$this->data);
	}

	public function create(Request $request) {
		$this->data['pageHeading'] = 'Add '.$this->_module ;
		return view('admin.' . $this->_routePrefix . '.create',$this->data);
	}
	
}
