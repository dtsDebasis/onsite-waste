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
use App\Models\File;
use File as CoreFile;
class GuestController extends Controller {
	public function __construct($parameters = array()) {
		parent::__construct($parameters);
		$this->_module      = 'Guest';
        $this->_routePrefix = 'guests';
		$this->_model       = new user();
	}

	public function index(Request $request){
		$this->initIndex();
		$requestModelObj = new \App\Models\GuestRequestInfo();
		$srch_params = $request->all();
		$this->_data['newRequestCount'] = $requestModelObj->getListing(['not_reply'=>true,'count'=>true]);
		if(!isset($srch_params['tab']) || (isset($srch_params['tab']) && empty($srch_params['tab']))){
			$srch_params['tab'] = "guest_info";
		}
		if($srch_params['tab'] == "guest_info"){
			$srch_params['role_slug'] = 'guest-user';
			$srch_params['with'] = ['company','guest_company'];
			$this->_data['data']	= $this->_model->getListing($srch_params,$this->_offset);
		}
		else if($srch_params['tab'] == "request_info"){
			$srch_params['with'] = ['user'];
			$this->_data['data']	= $requestModelObj->getListing($srch_params,$this->_offset);
			// dd($this->_data['data']);
		}
		return view('admin.' . $this->_routePrefix . '.index',$this->_data);
	}

	public function create(Request $request) {
		$this->data['pageHeading'] = 'Add '.$this->_module ;
		return view('admin.' . $this->_routePrefix . '.create',$this->data);
	}

	public function requestInfoReply(Request $request){
		$input = $request->all();
		$siteSettings = \App\Helpers\Helper::SiteSettingDetails();
		$rules = array(
			'request_id' => 'required|integer',
			'description' => 'required',
			'upload_file.*' =>'required|mimes:jpeg,png,jpg,doc,docx,pdf|max:' . (int) $siteSettings['file_size'] * 1024,
		);
		$messages = [
			'upload_file.*.max' => 'File size cannot exceed ' . $siteSettings['file_size'] . ' MB'
		];
		$validator = \Validator::make($input, $rules,$messages);
		if ($validator->fails()) {
			throw new Exception($validator->errors()->first(), 401);
		}
		else{
			$requestDetails = app('App\Models\GuestRequestInfo')->getListing(['id' => $input['request_id'],'with' => ['user']]);
			//dd($requestDetails);
            if($requestDetails && $requestDetails->user){
				$data = \App\Models\RequestReplyInfo::create([
					'request_id' => $input['request_id'],
					'to_user_id' => $requestDetails->user_id,
					'from_user_id' => \Auth::user()->id,
					'description' => $input['description']
				]);
				if($data){
					$file = app('App\Models\RequestReplyInfo')->uploadReplyDoc($data,$request);
					$mailData = [
                        'name'       => $requestDetails->user->full_name,
                        'description' => $input['description']
                    ];
					$attach = [];
					if($file['status'] != 400 && $file['data']){
						$fileObj = new File();
						$file = $fileObj->getListing(['id' =>$file['data']->id,'with'=>['cdn']]);
						if($file){
							$fileDetails = File::file($file);
                            //dd($fileDetails);
							if(count($fileDetails)){
								$attach = [
									'path' => $fileDetails['original'],
									'file_name' => $file->file_name_original,
									'file_mime' => $fileDetails['file_mime']
								];
							}
						}
					}
					$fullName = $mailData['name'];
						
                    \App\Models\SiteTemplate::sendMail($requestDetails->user->email, $fullName, $mailData, 'request_reply_mail_from_admin',$attach); //register_provider from db site_template table template_name field  
					return redirect()->back()->with('success','Reply send successfully');
				}
			}
			else{
				return redirect()->back()->with('error','Details not found');
			}
		}

	}

	public function downloadRequestFile(Request $request,$type,$id){
		$details = app('App\Models\GuestRequestInfo')->getListing(['id' => $id]);
		if($details && count($details->request_info_doc)){
			$fileName = $details->company_name.'-'.$type.'.zip';
			$cdn = \App\Models\Cdn::where("status", 1)->first();
			if ($type == 'manifest') {
				$path2 = \Storage::disk($cdn->location_type)->path($cdn->cdn_root . File::$fileType['request_manifest_document']['location'].'/'.$id);//. '\\' . $fileName;
			} else {
				$path2 = \Storage::disk($cdn->location_type)->path($cdn->cdn_root . File::$fileType['request_info_document']['location'].'/'.$id);//. '\\' . $fileName;
			}

			$path2 = str_replace('\\','/',$path2);
			$path = public_path().'/downloads';
			$files = CoreFile::files($path);
            foreach ($files as $key => $value){
                $pathname =$value->getPathname();
                unlink($pathname);
            }
			$zip = new \ZipArchive();
			if (!file_exists($path2)) {
				return redirect()->back()->with('error','Details not found');
			}
			$uploadesFiles = CoreFile::files($path2);
			if($zip->open($path.'/'.$fileName, \ZipArchive::CREATE)== TRUE){
				foreach($uploadesFiles as $value){
					$relativeName = basename($value);
					$zip->addFile($value, $relativeName);

				}
				$zip->close();
				return response()->download($path.'/'.$fileName);
			}
			else{
				return redirect()->back()->with('error','Something went to wrong');
			}

		}
		else{
			return redirect()->back()->with('error','Details not found');
		}
	}

}
