<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePassword;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller {
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
		$this->_model       = new User();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		$srch_params             = $request->all();
		$srch_params['role_gte'] = $this->_model->myRoleMinLevel(\Auth::user()->id);
		$data['list']            = $this->_model->getListing($srch_params, $this->_offset);

		return Helper::rj('Record found', 200, $data);
	}
	public function profileDetails(Request $request){
		try{
			$user_id   = \Auth::user()->id;
			$data = $this->_model->getListing(['id' => $user_id,'with' => ['roles','company','guest_company']]);
			if($data){
				$data->section_settings = \App\Helpers\Helper::getUserHomeSectionSettings($data);
			}
			return Helper::rj('Details fetch successfully .', $this->successStatus, $data);
		} catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $id) {
		$data['details'] = $this->_model->getListing(['id' => $id]);

		return Helper::rj('Record found', 200, $data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(UserRequest $request) {
		return $this->__formPost($request);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function profileUpdate(Request $request) {
		try{
			$user_id = $this->_user->id;
			$input  = $request->all();
			$validationRules = [
				'first_name' => 'required|max:100',
				'last_name' => 'required|max:100',
				'email'     => 'email|max:255|unique:users,email,'.$user_id.',id,deleted_at,NULL',
			];
			if(isset($input['password'])){
				$validationRules['password'] = 'required|min:6|max:25';
			}
			$validator = \Validator::make($request->all(),$validationRules);
			if ($validator->fails()) {
				throw new Exception($validator->errors()->first(), 200);
			}
			else{
				$original_password = null;
				if(isset($input['password']) && !empty($input['password'])){
					$original_password = $input['password'];
					$input['password'] = \Hash::make($original_password);
				}
				$res = User::where('id', $user_id)->update($input);
				if($res){
					return Helper::rj('Updated successfully', 200);
				}
				else{
					throw new Exception('Not update', 400);
				}
			}

		} catch (Exception $e) {
			return Helper::rj($e->getMessage(), 500);
		}
	}

	public function uploadAvatar(Request $request) {
		$fileValidations = \App\File::$fileValidations['image'];
		$validationRules = [
			'user_id' => 'nullable|exists:users,id',
			'avatar'  => 'required|mimes:' . $fileValidations['mime'] . '|max:' . $fileValidations['max'],
		];

		$validationMessages = [
			'user_id.required' => 'The user is required',
			'user_id.exists'   => 'The selected user is invalid.',
		];

		$validator = \Validator::make($request->all(), $validationRules, $validationMessages);
		if ($validator->fails()) {
			return Helper::rj('Error.', 400, [
				'errors' => $validator->errors(),
			]);
		}

		$userId = $request->get('user_id');
		$userId = $userId ? $userId : \Auth::user()->id;
		$data   = $this->_model->getListing([
			'id' => $userId,
		]);
		$response = $this->_model->uploadAvatar($data, $userId, $request);
		$status   = $response['status'];
		if ($status == 200) {
			$data = $this->_model->getListing([
				'id' => $userId,
			]);
			$data = $this->_model->userInit($data, false);
		}
		return Helper::rj($response['message'], $status, [
			'details' => $data,
		]);
	}

	public function updatePassword(UpdatePassword $request) {
		try {
			$data = \Auth::user();
			// dd($data);
			$input             = $request->all();
			$input['password'] = \Hash::make($input['password']);
			$data->update($input);
			return Helper::rj('Password has been successfully updated.', 200);
		} catch (Exception $e) {
			return Helper::rj($e->getMessage(), 500);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$response = $this->_model->delete($id);
		return Helper::rj($response['message'], $response['status'], [
			'details' => $response['data'],
		]);
	}

	/**
	 * Form post action
	 *
	 * @param  Request $request [description]
	 * @param  string  $id      [description]
	 * @return [type]           [description]
	 */
	private function __formPost(UserRequest $request, $id = 0) {
		$isOwnAcc = true;
		//
		// if this is not own account, it will
		// require role.
		//
		if (Auth::user()->id != $id) {
			$isOwnAcc = false;
		}
		$input    = $request->all();
		$response = $this->_model->store($input, $id, $request);
		return Helper::rj($response['message'], $response['status'], [
			'details' => $response['data'],
		]);
	}
}
