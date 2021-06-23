<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePassword;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\File;
use App\Models\BranchCyclingInformation;
use Auth;
use Illuminate\Http\Request;
use Exception;

class ExternalInfoApiController extends Controller {
    protected $_user;
    protected $_siteSettings;
    public $successStatus = 200;
	public function __construct($parameters = array()) {
		parent::__construct($parameters);
        $this->middleware(function (Request $request, $next) {
            $this->_user = Auth::user();
            return $next($request);
        });
        $this->_siteSettings = \App\Helpers\Helper::SiteSettingDetails();
		$this->_module      = 'External Info';
		$this->_model       = new BranchCyclingInformation();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function storeLastCyclingInfo(Request $request) {
        try{
            $input = $request->all();
            $rules = array(
                'branch_id' => 'required|integer',
                'last_run_information' => 'required|json',
            );
            $messages = [
            ];
            $validator = \Validator::make($input, $rules,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 400);
            }
            else{
                $data = $this->_model->getListing(['branch_id' => $input['branch_id'],'single_record' => true]);
                if($data){
                    $data->update(['last_run_information' => $input['last_run_information']]);
                }
                else{
                    $data = BranchCyclingInformation::create([
                        'branch_id' => $input['branch_id'],
                        'last_run_information' => $input['last_run_information']
                    ]);
                }
                if($data){ 
                    return Helper::rj('Stored successfully .', $this->successStatus);
                }
                else{
                    throw new Exception('Request not send',400);
                }
                
            }
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }

    public function updateTE500Information(Request $request){
        try{
            $input = $request->all();
            $rules = array(
                'branch_id' => 'required|integer',
                'te_5000_info' => 'required|json',
                'status' => 'required|integer'
            );
            $messages = [
            ];
            $validator = \Validator::make($input, $rules,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 400);
            }
            else{
                $data = app('App\Models\BranchTe5000Information')->getListing(['branch_id' => $input['branch_id'],'single_record' => true]);
                if($data){
                    $inf0 = json_decode($input['te_5000_info']);
                    $te_5000_imei = isset($info['te_5000_imei'])?$info['te_5000_imei']:null;
                    $res = $data->update([
                        'te_5000_imei' => $te_5000_imei,
                        'status' => $input['status'],
                        'te_5000_info' => $input['te_5000_info']
                    ]);
                    if($res){
                        return Helper::rj('Updated successfully .', $this->successStatus);
                    }
                    else{
                        throw new Exception('not update', 400);
                    }
                }
                else{
                    throw new Exception('Details not found', 400);
                }
            }
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }

    public function createContact(Request $request){
        try{
            $input = $request->all();
            $rules = array(
                'name' => 'required|max:200',
                'email' => 'required|max:200',
                'phone' => 'required|integer',
                'description' => 'required'
            );
            $validator = \Validator::make($input, $rules);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 400);
            }
            else{
                if($this->_user){
                    $input['user_id'] = $this->_user->id;
                }
                $res = \App\Models\Contact::create($input);
                return Helper::rj('Request sent successfully .', $this->successStatus);
            }

        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }
    public function contactDetails(Request $request){
        try{
            $data = [
                'contact_phone' => \Config::get('settings.contact_phone'),
                'contact_email' => \Config::get('settings.contact_mail'),
                'contact_address' => \Config::get('settings.contact_address')
            ];
            return Helper::rj('Contact details fetch successfully .', $this->successStatus, $data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }
}