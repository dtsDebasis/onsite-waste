<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePassword;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\File;
use App\Models\GuestRequestInfo;
use Auth;
use Illuminate\Http\Request;
use Exception;

class GuestRequestApiController extends Controller {
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
		$this->_module      = 'User';
		$this->_routePrefix = 'users';
		$this->_model       = new GuestRequestInfo();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function postRequest(Request $request) {
        try{
            $siteSettings = $this->_siteSettings;
            $input = $request->all();
            $input['company_phone'] = isset($input['company_phone'])?\App\Helpers\Helper::getOnlyIntegerValue($input['company_phone']):null;
            $userId = $this->_user->id;
            $input['user_id'] = $userId;
            $rules = array(
                'company_name' => 'required|max:200',
                'company_email' => 'required|max:200',
                'company_phone' =>'required',
                'request_type' =>'required|integer',
                'info_documents.*' =>'required|mimes:jpeg,png,jpg,doc,docx,pdf|max:' . (int) $siteSettings['file_size'] * 1024,
            );
            $messages = [
                'info_documents.*.max' => 'File size cannot exceed ' . $siteSettings['file_size'] . ' MB'
            ];
            $validator = \Validator::make($input, $rules,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 400);
            }
            else{
                /**
                $guestCompanyDetails = app('App\Models\GuestCompanyInfo')->getListing(['user_id'=>$this->_user->id,'single_record' => true]);
                if($guestCompanyDetails){
                    $compnayInfo = [];
                    if(isset($input['company_name']) && $input['company_name']){
                        $compnayInfo['company_name'] = $input['company_name'];
                    }
                    if(isset($input['company_email']) && $input['company_email']){
                        $compnayInfo['company_email'] = $input['company_email'];
                    }
                    if(isset($input['company_phone']) && $input['company_phone']){
                        $compnayInfo['company_phone'] = $input['company_phone'];
                    }
                    if(count($compnayInfo)){
                        $guestCompanyDetails->update($compnayInfo);
                    }
                }
                **/    
                $input['uniq_id'] = time();               
                $model = new \App\Models\GuestRequestInfo();
                $data = $model->create($input);
                if($data){ 
                    $uploadedFiles = $model->uploadInfoDoc($data,$request);   
                    $mailData = [
                        'name'       => 'Hello Admin',
                        'user_name' => $this->_user->full_name,
                        'user_email' => $this->_user->email,
                        'company_name' => $input['company_name'],
                        'company_email' => $input['company_email'],
                        'company_phone' => $input['company_phone'],
                        'description' => $input['description'],
                        'reply_to' => $this->_user->email
                    ];
                    $attach = [];
                    if($uploadedFiles['status'] != 400 && $uploadedFiles['data']){
						$fileObj = new File();
                        foreach($uploadedFiles['data'] as $fileVal){
                            $file = $fileObj->getListing(['id' =>$fileVal->id,'with'=>['cdn']]);
                            if($file){
                                $fileDetails = File::file($file);
                                if(count($fileDetails)){
                                    // $attach[] = [
                                    // 	'path' => $fileDetails['original'],
                                    // 	'file_name' => $file->file_name_original,
                                    // 	'file_mime' => $fileDetails['file_mime']
                                    // ];
                                }
                            }
                        }
					}
                    $fullName = $mailData['name'];
                    \App\Models\SiteTemplate::sendMail(\Config::get('settings.request_info_mail'), $fullName, $mailData, 'guest_request_info_mail_to_admin',[],$attach); //register_provider from db site_template table template_name field  
                    
                    $fullName = $this->_user->full_name;
                    \App\Models\SiteTemplate::sendMail($this->_user->email ,$fullName, ['name' => $this->_user->full_name,'reply_to' => \Config::get('settings.request_info_mail')], 'guest_request_info_mail_to_guest'); //register_provider from db site_template table template_name field
                    
                    return Helper::rj('Request send successfully .', $this->successStatus);
                }
                else{
                    throw new Exception('Request not send',400);
                }
                
            }
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }
}