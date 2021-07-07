<?php

namespace App\Http\Controllers\Auth\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Validator;
use Exception;
use DB;

class RegisterApiController extends Controller {
	public $successStatus = 200;

	/**
	 * Register api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function register(Request $request) {
		try{
			$input = $request->all();
			$validator = Validator::make($request->all(), [
				'first_name' => 'required|max:100',
				'last_name' => 'required|max:100',
				'email'     => 'required|email|max:255|unique:users,email,0,id,deleted_at,NULL',
				'password'  => 'required|min:6|max:25',
				'company_name' => 'required|max:200',
				// 'company_phone' => 'required|max:200',
				// 'company_email' => 'required|max:200',
			]);
			if ($validator->fails()) {	
				throw new Exception($validator->errors()->first(), 400);
			}
			$original_password = null;
			if(isset($input['password']) && !empty($input['password'])){
				$original_password = $input['password'];
				$input['password'] = \Hash::make($original_password);
			}	
			else{
				$original_password = Helper::randomString(10);
				$input['password'] = \Hash::make($original_password);
			}	
			$input['username']         = Helper::randomString(15);
			$input['status']           = 0;
			$input['user_type']		   = 'user';
			$input['customer_type']		   = 2;
			$input['remember_token']   = Helper::randomString(25);
			$user                      = User::create($input);
			if($user){	
				$role                      = \App\Models\Role::where('slug', 'guest-user')->first();		
				if($role){
					$input['user_id']          = $user->id;
					$input['role_id']          = $role->id;
					$role                      = UserRole::create($input);
				}
				$input['user_id'] = $user->id;
				$input['status'] = 1;
				\App\Models\GuestCompanyInfo::create($input);
                $url = \Config::get('services.frontend_url') ? \Config::get('services.frontend_url') : 'https://onsite-customer.glohtesting.com/';

				$mailData = [
					'name'       => $user->full_name,
					'activation_link' => $url . 'user/verify/' . $user->remember_token,
					'extra_text'      => '',
				];
				$fullName = $user->full_name;
				\App\Models\SiteTemplate::sendMail($user->email, $fullName, $mailData, 'register'); //register_provider from db site_template table template_name field

				return Helper::rj('Registration has been successfully completed.', $this->successStatus, $user);
				
			}
			else{
				throw new Exception('Something went to wrong.', 400);
			}
		
		} catch (\Exception $e) {
			return Helper::rj($e->getMessage(), $e->getCode());
		}
	}
}
