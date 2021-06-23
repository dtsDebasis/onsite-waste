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

class PickupController extends Controller {
	public function __construct($parameters = array()) {
		parent::__construct($parameters);
		$this->_module      = 'Pickup';
        $this->_routePrefix = 'pickup';
	}

	public function index(Request $request){
		$this->data['pageHeading'] = $this->_module;
		return view('admin.' . $this->_routePrefix . '.index',$this->data);
	}

	public function create(Request $request, $pickupid) {

		
		$this->data['pageHeading'] = 'Add '.$this->_module.' Schedule' ;
		$this->data['pickupid'] = $pickupid;
		return view('admin.' . $this->_routePrefix . '.create', $this->data);
	}

	
}
