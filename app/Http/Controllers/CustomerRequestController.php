<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\CustomerRequest;
use Response;
use Excel;
use Exception;

class CustomerRequestController extends Controller
{
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);

        $this->_module      = 'Customer Requests';
        $this->_routePrefix = 'customer-requests';
        $this->_model       = new CustomerRequest;
        $this->_offset = 20;
    }

    
    protected function __routeParams($category = 0, $sub_category = 0) {
        $this->_data['routeParams'] = [
        ];
    }

    public function index(Request $request)
    {
        $this->initIndex([], false);
        $this->__routeParams();
        $srch_params                    = $request->all();
        $this->_data['srch_params'] = $srch_params;
        
        $srch_params['with'] = ['branch_details.addressdata','user_details' => function($q){return $q->select('id','email','phone','first_name','last_name');},'company_details' => function($q){return $q->select('company_number','company_name');}];
        $this->_data['pageHeading'] = $this->_module;
        $this->_data['data']            = $this->_model->getListing($srch_params, 20);
        //dd($this->_data['data']);
        $this->_data['orderBy']         = $this->_model->orderBy;
        $this->_data['filters']         = null;

        $this->_data['breadcrumb'] = [
            '#'        => $this->_module,
        ];
        return view('admin.' . $this->_routePrefix . '.index', $this->_data);
    }
}