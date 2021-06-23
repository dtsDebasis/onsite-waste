<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Contact;
use Response;
use Excel;
use Exception;

class ContactController extends Controller
{
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);

        $this->_module      = 'Contacted By Website';
        $this->_routePrefix = 'contacts';
        $this->_model       = new Contact;
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
        $this->_data['pageHeading'] = $this->_module;
        $this->_data['data']            = $this->_model->getListing($srch_params, $this->_offset);
        
        $this->_data['orderBy']         = $this->_model->orderBy;
        $this->_data['filters']         = null;

        $this->_data['breadcrumb'] = [
            '#'        => $this->_module,
        ];
        return view('admin.' . $this->_routePrefix . '.index', $this->_data);
    }
}