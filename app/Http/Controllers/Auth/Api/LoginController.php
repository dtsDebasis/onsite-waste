<?php

namespace App\Http\Controllers\Auth\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Exception;

class LoginController extends Controller {

	use AuthenticatesUsers;
	public $successStatus = 200;
	protected $_model     = null;

	//protected $decayMinutes=5;

	public function __construct() {
		$this->_model = new User;
		$this->middleware('throttle:6,1')->only('login');

		//	return Helper::rj('Your account has been locked due to 3 consecutive login failure. Please reset your password to access this platform', 429);
	}

	/**
	 * login api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function login(LoginRequest $request) {

		try {
			$user  = Auth::user();
			$query = User::with(['guest_company','company'])->where(function ($q) use ($request) {
				$q->where('email', strtolower($request->email));
					//->orWhere('phone',$request->email);
			})
			->where(['deleted_at'=>NULL]);
			$user = $query->first();
			if(!$user) {
				throw new Exception('Sorry! You have entered either wrong email address.', 400);
			}
			elseif($user && !\Hash::check($request->password, $user->password)){
				throw new Exception('Sorry! You have entered wrong password.',400);
			}
			if ($user->status == 2) {
				throw new Exception('Sorry! Your account is currently blocked. Please contact with us for the details.', 400);
			}
			if (!$user->verified) {
				throw new Exception('Sorry! Your account is not verified yet.', 400);
			}
			if ($user->user_type !="user") {
				throw new Exception('Sorry! Your are unauthorised. Please contact with us for the details.', 400);
			}
			$data['details'] = $user;
			$data['token'] = $user->createToken($user->email)->accessToken;
			$section_settings = \App\Helpers\Helper::getUserHomeSectionSettings($user);
			$data['section_settings'] = $section_settings;
			$data['details']->section_settings = $section_settings;

			return Helper::rj('Login Successful', $this->successStatus, $data);
		} catch (\Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
	}

	public function logout(Request $request) {
		if (Auth::check()) {
			// $headerToken = $request->bearerToken();
			$result = $request->user()->token()->delete();
			if ($result) {
				return Helper::rj('Logout Successful', $this->successStatus);
			} else {
				return Helper::rj('Something went wrong.', 400);
			}
			// return $response;
			// Auth::user()->AauthAcessToken()->delete();

		}

		return Helper::rj('Not a valid credential.', 400);
	}

	/**
	 * Forgot Password api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function forgotPassword(Request $request) {
		try {
			$validator = Validator::make($request->all(), [
				'email' => 'required|email',
			]);
			if ($validator->fails()) {
				return Helper::rj('Bad Request', 400, [
					'errors' => $validator->errors(),
				]);
			}
			$input = $request->all();
			$user  = User::where('email', $input['email'])->first();

			// $user_roles  = $user->roles->pluck('slug')->toArray();
			// if (in_array('customer', $user_roles)) {
			// 	return \App\Helpers\Helper::resp('Not a valid user.', 400);
			// }

			if (!$user) {
				// return \App\Helpers\Helper::resp('Not a valid data', 400);
				return Helper::rj('An email has been sent to your registered email id to recover your password.', 200, []);
			}

            //User::sendPasswordChangeMail($input['email']);

			$user->remember_token = Helper::randomString(25);

			if ($user->save()) {
                $url = \Config::get('services.frontend_url') ? \Config::get('services.frontend_url') : 'https://onsite-customer.glohtesting.com/';

				$mailData = [
					'first_name'      => $user->first_name,
					'activation_link' => $url . 'reset-password/' . $user->remember_token,
				];
				$fullName = $user->first_name . ' ' . $user->last_name;
				\App\Models\SiteTemplate::sendMail($user->email, $fullName, $mailData, 'forgot_password');
				return Helper::rj('Password recovery email has been sent to you.', 200);
			} else {
				return Helper::rj('An email has been sent to your registered email id to recover your password.', 200, [
					'errors' => 'Sorry! This email is not registered with us.',
				]);
			}
			return Helper::rj('Password recovery email has been sent to you.', 200);
		} catch (Exception $e) {
			return Helper::rj($e->getMessage(), 500);
		}
	}

	/**
	 * Set Password api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function setPassword(Request $request) {
		try {
			$passwordValidators = \App\Models\User::$passwordValidator;
			$validator          = Validator::make($request->all(), [
				'token'      => 'required',
				'password'   => $passwordValidators,
				'c_password' => 'required|same:password',
			]);
			if ($validator->fails()) {
				return Helper::rj('Bad Request', 400, [
					'errors' => $validator->errors(),
				]);
			}
			$input  = $request->all();
			$user   = User::where('remember_token', $input['token'])->first();
            if (!$user) {
				// return \App\Helpers\Helper::resp('Not a valid data', 400);
				return Helper::rj('Not a valid token', 400, []);
			}
			$return = \App\Helpers\Helper::notValidData($user);
			if ($return) {
				return $return;
			}

			$user->status         = 1;
			$user->password       = bcrypt($input['password']);
			$user->remember_token = null;

			if ($user->save()) {
				// $success['token'] = $user->createToken('MyApp')->accessToken;
				$success = [];
				if (isset($input['verify']) && $input['verify']) {
					$success = $this->_model->userInit($user);
				}

				return Helper::rj('New password set for your account', $this->successStatus, $success);
			} else {
				return Helper::rj('Failed!', 400);
			}

		} catch (Exception $e) {
			return Helper::rj($e->getMessage(), 500);
		}
	}


	public function verifyToken(Request $request) {
		try {
			$validator          = Validator::make($request->all(), [
				'token'      => 'required',
			]);
			if ($validator->fails()) {
				return Helper::rj('Bad Request', 400, [
					'errors' => $validator->errors(),
				]);
			}
			$input  = $request->all();
			$user   = User::where('remember_token', $input['token'])->first();
            if (!$user) {
				// return \App\Helpers\Helper::resp('Not a valid data', 400);
				return Helper::rj('Not a valid token', 400, []);
			}

			return Helper::rj('Valid user', 200, $user);

		} catch (Exception $e) {
			return Helper::rj($e->getMessage(), 500);
		}
	}

	/**
	 * Email Verification
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function verifyEmail(Request $request) {
		try {
			$input                  = $request->all();
			$validator = Validator::make($request->all(), [
				'token' => 'required',
			]);
			if ($validator->fails()) {
				return Helper::rj('Bad Request', 400, [
					'errors' => $validator->errors(),
				]);
			}
			$user =  User::where('remember_token', $input['token'])->first();
			if($user){
				$data['verified']       = 1;
				$data['remember_token'] = null;
				$data['status']         = 1;
				$data['email_verified_at'] = now();
				$user                      = User::where('remember_token', $input['token'])->update($data);
				if ($user) {
					return Helper::rj('Email verified successfully.', 200);
				} else {
					return Helper::rj('Failed!', 400);
				}
			}
			else{
				return Helper::rj('Invalid token', 400);
			}

		} catch (Exception $e) {
			return Helper::rj($e->getMessage(), 500);
		}
	}

	public function resendVerificationMail() {
		try {

			$user = Auth::user();

			$mailData = [
				'name'            => $user->first_name,
				'activation_link' => \Config::get('settings.frontend_url') . 'email/verify/' . $user->username,
			];

			$fullName = $user->first_name . ' ' . $user->last_name;
			\App\Models\SiteTemplate::sendMail($user->email, $fullName, $mailData, 'resend_verify_email');

			return Helper::rj('Verification email sent.', 200);

		} catch (Exception $e) {
			return Helper::rj($e->getMessage(), 500);
		}
	}

	public function isEmailUnique(Request $request) {
		try {
			$validator = Validator::make($request->all(), [
				'email' => 'required|email:rfc,dns|max:255|unique:users,email,0,id,deleted_at,NULL',
			],
				[
					'email.unique' => 'This email has already been taken. Try another',
				]);
			if ($validator->fails()) {
				return Helper::rj('Bad Request', 400, [
					'errors' => $validator->errors(),
				]);
			}

			return Helper::rj('Email is unique.', 200);

		} catch (Exception $e) {
			return Helper::rj($e->getMessage(), 500);
		}
	}
}
