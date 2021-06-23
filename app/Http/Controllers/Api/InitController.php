<?php
namespace App\Http\Controllers\Api;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class InitController extends Controller {
	public function initialDetails(Request $request) {
		try {
			$user      = \Auth::user();
			$userModel = new User;
			$data      = $userModel->userInit($user, false);
			return Helper::rj('Record found', 200, $data);
		} catch (Exception $e) {
			Helper::Log('InitController-initialDetails', $e->getMessage());
			return Helper::rj($e->getMessage(), 500);
		}
	}

	public function customerInit(Request $request) {
		$validator = Validator::make($request->all(), [
			'entity_type' => 'required',
			'entity'      => 'required',
		]);
		$input     = $request->all();
		$user_id   = \Auth::user()->id;
		$userModel = new \App\User();
		$success   = $userModel->userInit(\Auth::user(), false);

		if ($input['entity_type'] == 'digital-form') {
			$userCustomerFormModel = new \App\UserCustomerForm();
			$srch_params['slug']   = $input['entity'];

			$data   = $userCustomerFormModel->getListing($srch_params);
			$return = \App\Helpers\Helper::notValidData($data);
			if ($return) {
				return $return;
			}

			$userCustomerFormConsentMapModel = new \App\UserCustomerFormConsentMap;
			$success['consents']             = $userCustomerFormConsentMapModel->getListing([
				'user_customer_form_id' => $data->id,
				'customer_id'           => $user_id,
				'status'                => 0,
				'first'                 => true,
			]);
		}

		// if($input['entity_type']=='task'){
		// 	$userContentShareModel= new \App\UserContentShare();
		// 	$srch_params['slug']= $input['entity'];
		// 	$data    = $userContentShareModel->getListing($srch_params);
		// 	$return = \App\Helpers\Helper::notValidData($data);
		// 	if ($return) {
		// 	return $return;
		// 	}
		// }
		//dd(\Auth::user());
		//dd($success);
		return Helper::rj('Record found', 200, $success);

	}
	public function siteInitialDetails(Request $request) {
		try {

			$data['site'] = \App\SiteSetting::select("key", "val", "field_label", "field_type")
				->where("is_visible", 1)
				->get();

			return Helper::rj('Record found', 200, $data);
		} catch (Exception $e) {
			Helper::Log('InitController-siteInitialDetails', $e->getMessage());
			return Helper::rj($e->getMessage(), 500);
		}
	}

	public function updateSiteSetting(Request $request, $key = '') {
		try {
			$validationRules = [
				'val' => 'required',
			];

			$validator = Validator::make($request->all(), $validationRules);
			if ($validator->fails()) {
				return Helper::rj('Error.', 400, [
					'errors' => $validator->errors(),
				]);
			}

			$input  = $request->all();
			$data   = \App\SiteSetting::where('key', $key)->first();
			$return = \App\Helpers\Helper::notValidData($data);
			if ($return) {
				return $return;
			}

			$data->update($input);

			\App\SiteSetting::makeCacheSetting();

			return Helper::rj('Record has been successfully updated.', 200);
		} catch (Exception $e) {
			return Helper::rj($e->getMessage(), 500);
		}
	}

	public function captcha(Request $request) {
		$captcha = new \App\Helpers\Captcha;
		$captcha = $captcha->create();

		$captchaResponse = \App\CaptchaToken::create([
			'token'        => Helper::randomString(200),
			'captcha_text' => md5($captcha['word-org']),
			'ip'           => $request->ip(),
		]);

		return Helper::rj('captcha created', 200, [
			'captcha' => $captcha['image'],
			'token'   => $captchaResponse->token,
		]);
	}
}
