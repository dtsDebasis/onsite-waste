<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;

use App\Models\SiteContent;
use Auth;

class SiteContentController extends Controller
{
    protected $_user;
    protected $_siteSettings;
    public $successStatus = 200;
    public $message = "Success!!";
	public function __construct($parameters = array()) {
		parent::__construct($parameters);
        $this->middleware(function (Request $request, $next) {
            $this->_user = Auth::user();
            return $next($request);
        });
        $this->_siteSettings = \App\Helpers\Helper::SiteSettingDetails();
		$this->_routePrefix = 'sitecontent';
	}


    public function getContent(Request $request) {
        try {
            $input = $request->all();
            $modelObj = new SiteContent();
            $data =$modelObj->getListing($input);
            return Helper::rj($this->message, $this->successStatus, $data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }
    

}
