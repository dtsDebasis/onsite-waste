<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Helper
{

	/**
	 * Show date with date format.
	 * @param date / datetime $date
	 * @param boolean $showTime [show time also]
	 * @param string $dateFormat custom date format [any custom date format, which is not default]
	 * @param string $timezone [User timezone]
	 * @version:    1.0.0.5
	 * @author:     Somnath Mukherjee
	 */
	public static function showdate($date, $showTime = true, $dateFormat = '', $timezone = '')
	{
		if (self::check_valid_date($date)) {
			$customFormat = true;
			if (!$dateFormat) {
				$customFormat = false;
				$dateFormat   = \Config::get('settings.date_format');
			}
			$gdt = explode(" ", $date);
			if ($showTime && isset($gdt[1]) && self::checktime($gdt[1])) {
				if (!$customFormat) {
					$dateFormat .= " " . \Config::get('settings.time_format');
				}
				$date = (string) date($dateFormat, strtotime($date));

				return $date;
			} else {
				$date = (string) date($dateFormat, strtotime($date));
				return $date;
			}
		} else {
			return false;
		}
	}
	public static function SiteSettingDetails(){
        return \Config::get('settings');
    }

	/**
	 * Search text with html tag
	 * @param:      html string [string]
	 * @param:      tag name [string]
	 * @return:     tag value if found else boolean
	 * @version:    1.0.0.1
	 * @author:     Somnath Mukherjee
	 */

	public static function text_within_tag($string, $tagname)
	{
		$pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
		preg_match($pattern, $string, $matches);
		return isset($matches[1]) ? $matches[1] : false;
	}

	/**
	 * Check valid time
	 * @param:      time as string
	 * @author:     Somnath Mukherjee
	 * @version:    1.0.0.1
	 */
	public static function checktime($time = '00:00:00')
	{
		list($hour, $min, $sec) = explode(':', $time);

		if ($hour == 0 && $min == 0 && $sec == 0) {
			return false;
		}

		if ($hour < 0 || $hour > 23 || !is_numeric($hour)) {
			return false;
		}
		if ($min < 0 || $min > 59 || !is_numeric($min)) {
			return false;
		}
		if ($sec < 0 || $sec > 59 || !is_numeric($sec)) {
			return false;
		}
		return true;
	}

	/**
	 * Check valid date
	 * @param:      date as string
	 * @author:     Somnath Mukherjee
	 * @version:    1.0.0.2
	 */
	public static function check_valid_date($date = '')
	{
		if ($date != "") {
			$date = date("Y-m-d", strtotime($date));
			$dt   = explode("-", $date);
			return checkdate((int) $dt[1], (int) $dt[2], (int) $dt[0]);
		}

		return false;
	}

	/**
	 * Get clean url.
	 * @params:         url string
	 * @version:        1.0.0.1
	 * @author:         Somnath Mukherjee
	 */

	public static function getcleanurl($name)
	{
		/*$name = trim($name);
			        $url  = preg_replace('/[^A-Za-z0-9\-]/', '-', $name);
			        $url  = strtolower(str_replace("-----", "-", $url));
			        $url  = strtolower(str_replace("----", "-", $url));
			        $url  = strtolower(str_replace("---", "-", $url));
			        $url  = strtolower(str_replace("--", "-", $url));

		*/

		return str_slug($name);
	}

	public static function getUniqueSlug($title = '', $table = '', $column = 'slug', $stringFormat = true)
	{
		$title              = self::getcleanurl($title);
		$condition[$column] = $title;

		$nor = \DB::table($table)
			->where($condition)
			->count();

		if (!$nor) {
			return $title;
		}

		if ($stringFormat) {
			$title .= '-' . chr(rand(65, 90));
		} else {
			$title .= rand(0, 9);
		}

		return self::getUniqueSlug($title, $table, $column);
	}

	/**
	 * Check for directory name
	 * @param:      directory name (mandatory).
	 * @author:     Somnath Mukherjee.
	 * @version:    1.0.0.1
	 *
	 * NOTE:        It checks only into user panels assets folder
	 *              if specified directory not exist then it will create it
	 */
	public static function check_directory($dir_name = '')
	{
		if ($dir_name == "") {
			return false;
		}

		$filePath = public_path($dir_name);
		if (!file_exists($filePath)) {
			$oldmask = umask(0);
			mkdir($filePath, 0755);

			// copying index file
			self::cp_index($filePath);
			umask($oldmask);
		}
		return true;
	}

	/**
	 * Copy index.html file to the destination folder.
	 *
	 * @param [string] $dest Destination folder
	 */
	public static function cp_index($dest = '', $source = '')
	{
		$dest   = rtrim($dest, "/") . '/index.html';
		$source = !$source ? 'index.html' : $source;
		@copy(public_path($source), $dest);
	}

	/**
	 * if this is not a valid data then it will
	 * redirct to the listing page
	 *
	 * @param  array  $data
	 * @param  string $redirectRoute redirect route
	 * @param  mixed $param extra param
	 * @return boolean
	 */
	public static function notValidData($data = [], $redirectRoute = '', $param = [])
	{
		if (!$data) {
			if ($redirectRoute) {
				return redirect()
					->route($redirectRoute, $param)
					->with('error', 'Not a valid data.');
			}

			return self::rj('No record found', 204);
		}

		return false;
	}

	public static function price($price = 0)
	{
		return '$' . number_format($price, 0);
	}

	/**
	 * Make associative array from 2d array.
	 *
	 * @param:      2d array (mandatory)
	 *
	 * @param:      fields name (mandatory).  First field for associative array's Key and
	 *              Second field for associative array's value.
	 *
	 *              NOTE: if you want to put only one field, then it will create
	 *              key and value with the same name or value.
	 *
	 * @param:      If you want to create a simple array then pass TRUE. It will not
	 *              create any associative array.
	 *
	 * @author:     Somnath Mukherjee.
	 *
	 * @version:    1.0.0.2
	 */

	public static function makeSimpleArray($marray = array(), $fields = '', $make_single = false)
	{
		if ($fields == "") {
			return false;
		}

		$fields = explode(",", $fields);
		$key    = null;
		$val    = null;
		if (count($fields) > 1) {
			$key = trim($fields[0]);
			$val = trim($fields[1]);
		} else {
			$key = $val = trim($fields[0]);
		}

		$sarray = array();
		if (is_array($marray) and !empty($marray)) {
			if (!$make_single) {
				foreach ($marray as $k => $v) {
					$v                = (array) $v;
					$sarray[$v[$key]] = trim($v[$val]);
				}
			} else {
				foreach ($marray as $k) {
					$k        = (array) $k;
					$sarray[] = $k[$key];
				}
			}
		}

		return $sarray;
	}

	public static function getMethod()
	{
		$method        = explode("@", \Route::currentRouteAction());
		return $method = end($method);
	}

	public static function getController()
	{
		$controller        = explode("@", \Route::currentRouteAction());
		$controller        = explode('\\', $controller[0]);
		return $controller = end($controller);
	}

	public static function randomNumber()
	{
		return mt_rand(10000000, 99999999);
	}
	public static function randomString($size = 15)
	{
		return Str::random($size);

		/*
			        $alpha_key = '';
			        $keys      = range('A', 'Z');

			        for ($i = 0; $i < 2; $i++) {
			            $alpha_key .= $keys[array_rand($keys)];
			        }

			        $length = $size - 2;

			        $key  = '';
			        $keys = range(0, 9);

			        for ($i = 0; $i < $length; $i++) {
			            $key .= $keys[array_rand($keys)];
			        }

		*/
	}

	public static function checkFolder($location = '')
	{
		if (!\File::exists($location)) {
			return \File::makeDirectory($location);
		}
		return true;
	}

	public static function getAttr($attributes = [], $return = FALSE)
	{
		$param = '';
		if (!empty($attributes)) {
			foreach ($attributes as $k => $v) {
				if (!$return) {
					echo ' ' . $k . '="' . $v . '"';
				} else {
					$param .= ' ' . $k . '="' . $v . '"';
				}
			}
		}

		return $param;
	}

	/**
	 * Response Json
	 * 200 OK
	 * 201 Created
	 * 202 Accepted
	 * 203 Non-Authoritative Information
	 * 204 No Content
	 * 205 Reset Content
	 * 206 Partial Content
	 * 207 Multi-Status
	 * 208 Already Reported
	 * 226 IM Used
	 *
	 * 400 Bad Request
	 * 401 Unauthorized
	 * 402 Payment Required
	 * 403 Forbidden
	 * 404 Not Found
	 * 405 Method Not Allowed
	 * 406 Not Acceptable
	 * 407 Proxy Authentication Required
	 * 408 Request Timeout
	 *
	 * 500 Internal Server Error
	 * 501 Not Implemented
	 * 502 Bad Gateway
	 */
	public static function rj($message = '', $headerStatus = 200, $data = [])
	{
		// if(empty($data)){
		//     $data = [
		//         'message'   => 'No record found!',
		//         'data'      => []
		//     ];
		//     $headerStatus   = 204;
		// }

		// $data['message']    = !isset($data['message']) ? 'No record found' : $data['message'];
		// $data['data']       = !isset($data['data']) ? (object)[] : (array)$data['data'];

		$data = self::resp($message, $headerStatus, $data);

		return response()->json($data, $headerStatus);
	}

	public static function resp($message = '', $status = 200, $data = [])
	{
		return [
			'status'  => $status,
			'message' => $message,
			'data'    => $data,
		];
	}

	public static function Log($type = '', $message = '', $requestedParam = [])
	{
		$requestedParam = json_encode($requestedParam);

		\App\ErrorLog::create([
			'error_type'     => $type,
			'error_message'  => $message,
			'request_params' => $requestedParam,
		]);
	}

	/**
	 * Format sort by clause from url.
	 *
	 *
	 */
	public static function manageOrderBy($orderBy = '')
	{
		if ($orderBy) {
			$orderBy    = explode(",", $orderBy);
			$orderByArr = [];
			foreach ($orderBy as $key => $value) {
				if ($value) {
					$value      = str_replace("__", ".", $value);
					$orderValue = substr($value, 0, 1);
					if ($orderValue == '-') {
						$orderByArr[substr($value, 1)] = 'DESC';
					} else {
						$orderByArr[$value] = 'ASC';
					}
				}
			}

			return $orderByArr;
		}

		return False;
	}

	public static function manageGroupBy($groupBy = '')
	{
		if ($groupBy) {
			$groupBy    = explode(",", $groupBy);
			$groupByArr = [];
			foreach ($groupBy as $key => $value) {
				if ($value) {
					$groupByArr[] = str_replace("__", ".", $value);
				}
			}

			return $groupByArr;
		}

		return False;
	}

	public static function getUserLatLong($data = array())
	{
		$lat = '';
		$lng = '';

		if (isset($data['zip']) and $data['zip']) {
			$location = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($data['zip']) . "&sensor=false&key=" . env('GOOGLE_API_KEY'));
			$location = json_decode($location);
			$location = (array) $location;

			if (!empty($location['results'])) {
				$location = (array) $location['results'][0]->geometry;

				$lat = $location["location"]->lat;
				$lng = $location["location"]->lng;
			}
		} elseif ((!isset($data['zip']) or (isset($data['zip']) and !$data['zip'])) and ((isset($data['lat']) and isset($data['lng'])) and ($data['lat'] != "" and $data['lng'] != ""))) {
			$lat = $data['lat'];
			$lng = $data['lng'];
		}

		if ($lat == '' and $lng == '') {
			$location = file_get_contents('http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR']);

			$location = json_decode($location);
			$lat      = $location->lat;
			$lng      = $location->lon;
		}

		return ['lat' => $lat, 'lng' => $lng];
	}

	public static function callAPI($method, $url, $data)
	{
		$curl = curl_init();
		if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
			$url = 'https://vd3r1augje.execute-api.us-west-2.amazonaws.com/Prod/'.$url;
		}

		switch ($method) {
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);
				if ($data) {
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				}

				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if ($data) {
					curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
				}

				break;
			default:
				if ($data) {
					$url = sprintf("%s?%s", $url, http_build_query($data));
				}
		}
		// OPTIONS:
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'x-api-key: xHReajboin7PY4M2G4Gku2YD9kdgqvb95qhhtO5r',
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// EXECUTE:

		$result = curl_exec($curl);
		if (!$result) {
			die("Connection Failure");
		}
		curl_close($curl);
		return $result;
	}

	public static function sort($route = '', $fieldName = '', $orderBy = [], $routeParam = [])
	{
		$srchParams = \Request::all();
		$sortBy     = explode(",", \Request::input('orderBy'));
		$sortIcon   = \Config::get('settings.icon_sort_none');
		$matchFound = false;

		if (!empty($orderBy)) {
			foreach ($orderBy as $key => $value) {
				$key = str_replace(".", "__", $key);
				if ($key == $fieldName) {
					$matchFound = true;
					if ($value == 'ASC') {
						$sortBy   = array_diff($sortBy, [$key]);
						$sortBy[] = '-' . $key;
						$sortIcon = \Config::get('settings.icon_sort_desc');
					} else {
						$sortBy = array_diff($sortBy, ['-' . $key]);
						// $sortBy[]   = $key;
						$sortIcon = \Config::get('settings.icon_sort_asc');
					}
				}
			}
		}

		if (!$matchFound) {
			$sortBy[] = $fieldName;
		}
		$srchParams['orderBy'] = implode(",", $sortBy);

		if ($routeParam) {
			$srchParams = array_merge($srchParams, $routeParam);
		}

		return '<a href="' . route($route, $srchParams) . '" class="icon-sorting">' . $sortIcon . '</a>';
	}

	public static function getUrlSegment($request = null, $segment = 0)
	{
		if ($request->is('api/*')) {
			$segment++;
		}

		return $segment;
	}

	public static function getSql($data = [], $print = true)
	{
		if (isset($data[0]) && isset($data[1])) {
			$sql = \Str::replaceArray('?', $data[1], $data[0]);
			if ($print) {
				dd($sql);
			}

			return $sql;
		}

		return false;
	}
	public static function generateMasterCode($model='',$field='',$length=8,$prefix='',$random_str = ''){
        //$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $permitted_chars = '0123456789';
        if($random_str == ''){
            $random_str = substr(str_shuffle($permitted_chars), 0, $length);
        }
        else{
            $random_str = substr(str_shuffle($random_str), 0, $length);
        }
        if($model){
            $random_str = $prefix.$random_str;
            $nor = $model::where($field, $random_str)->count();
            if (!$nor) {
                return $random_str;
            }
            return self::generateMasterCode($model,$field,$length,$prefix,$random_str);
        }
        else{
            return $random_str;
        }
    }
	public static function getCompanyOwners(){
		return $data = \App\Models\CompanyOwner::whereNull('deleted_at')->pluck('name','id')->toArray();
	}

	public static function getLeadSources($type= 0){
		if ($type !== 0 ){
			return $data = \App\Models\LeadSource::whereNull('deleted_at')->where('lead_type',$type )->pluck('name','id')->toArray();
		}
		return $data = \App\Models\LeadSource::whereNull('deleted_at')->pluck('name','id')->toArray();

	}

	public static function getRelationshiRoles(){
		return $data = \App\Models\RelationshipRole::whereNull('deleted_at')->where('status',1)->pluck('name','id')->toArray();
	}
	public static function getOnsitePartners($company_id = null, $branch_id = null){
		$onsite_role_id = \App\Models\RelationshipRole::select('id')->whereRaw('LOWER(`name`) LIKE ?', strtolower('OnSite Partner'))->first();
		$query = \App\Models\BranchUser::select('branch_users.company_id','branch_users.companybranch_id','branch_users.user_id',\DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS name"))
					->join('users','users.id','=','branch_users.user_id');
					if($company_id && !$branch_id){
						$query->where('branch_users.company_id',$company_id)->whereNull('companybranch_id');
					}
					if($branch_id){
						$query->where('branch_users.companybranch_id',$branch_id);
					}
		return $data = $query->where('users.company_relationship',$onsite_role_id->id)->pluck('name')->toArray();

	}
	public static function getSpecialities(){
		return $data = \App\Models\Speciality::where('status',1)->pluck('name','id')->toArray();
	}
	public static function getCompanySpecialities($company_id){
		return $data = \App\Models\CompanySpeciality::select('speciality.name','speciality.id')
				->join('speciality','speciality.id','=','company_speciality.specality_id')
				->where('company_speciality.company_id',$company_id)
				->where('speciality.status',1)
				->pluck('name')->toArray();
	}

	public static function getCompanySpecialitiesDropdown($company_id){
		return $data = \App\Models\CompanySpeciality::select('speciality.name','speciality.id')
			->join('speciality','speciality.id','=','company_speciality.specality_id')
			->where('company_speciality.company_id',$company_id)
			->where('speciality.status',1)
			->pluck('name','id')->toArray();

	}
	public static function getCompnyBranchSpeciality($company_id='',$branch_id=''){
		if($company_id && $branch_id){
			return $data = \App\Models\BranchSpecialty::where(['company_branch_id' =>$branch_id])->pluck('specality_id','id')->toArray();

		}
		else{
			return [];
		}
	}
	public static function getCompanyContactPersonIDName($company_id,$not_id = null){
		if($company_id){
			$query = \App\Models\User::select('id',\DB::raw("CONCAT(first_name, ' ', last_name) AS name"))->whereNull('deleted_at')->where(['company_id'=>$company_id]);
			if($not_id){
				$query->whereNotIn('id',$not_id);
			}
			return $data = $query->pluck('name','id')->toArray();
		}
		else{
			return [];
		}
	}
	public static function companies(){
		$data = [];

		$datas = app('App\Models\Company')->getListing([]);
		if(count($datas)){
			$data = $datas->pluck('company_name','id')->toArray();

		}
		return $data;
	}
	public static function companyBranches($company_id = null){
		$data = [];
		$params = [];
		if($company_id){
			$params['company_id'] = $company_id;
		}
		$datas = app('App\Models\CompanyBranch')->getListing($params);
		if(count($datas)){
			$data = $datas->pluck('name','id')->toArray();
		}

		return $data;
	}

	public static function dateConvertForDB($date,$explode_by='-'){
        if($date){
            $date1 = explode($explode_by,$date);
            if(count($date1)>2){
                return date('Y-m-d',strtotime($date1[1].'-'.$date1[0].'-'.$date1[2]));
            }
            return $date;
        }
        return $date;
    }
    public static function dateConvert($date,$format='m-d-Y'){
        if($date){
            $date = date($format,strtotime($date));
            return $date;
        }
        return $date;
    }
	public static function getOnlyIntegerValue($string,$length=12){
        return $int = preg_replace('/[^0-9]+/', '', $string);
    }
	public static function CSVToArray($filename = '', $delimiter = ',',$withKey = false)
    {
        if (!file_exists($filename) || !is_readable($filename)){
            return false;
        }
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {

                if (!$header){
                    $header = $row;
                }
                else{
                    if ($withKey) {

                        $modifiedHeader = [];
                        foreach ($header as $key => $value) {
                            $modifiedHeader[] = str_slug($value,'_');
                        }

                        $data[] = array_combine($modifiedHeader, $row);
                    } else {
                        $data[] = $row;
                    }

                }

            }
            fclose($handle);
        }
        return $data;
    }


	public static function getInventoryDetails(){

		return $data = [
			0 => [
				'company_id' => 5962741299,
				'waste_type' => 1,
				'container_type' => 1,
				'reorder_point' => 3,
				'containers_at_hand' =>33
			],
			1 => [
				'company_id' => 5949895194,
				'waste_type' => 1,
				'container_type' => 1,
				'reorder_point' => 3,
				'containers_at_hand' =>33
			],
			2 => [
				'company_id' => 5949895194,
				'waste_type' => 1,
				'container_type' => 1,
				'reorder_point' => 3,
				'containers_at_hand' =>33
			]
			];
	}
	public static function branchDetails($branch_id){
		if($branch_id){
			return $data = app('App\Models\CompanyBranch')->getListing(['uniq_id' => $branch_id,'with' => ['addressdata']]);

		}
		return false;
	}

	public static function getPackageNameDropdown() {
		$packagename = [];
		$data = app('App\Models\PackageName')->getListing(['status' => 1]);
		foreach($data as $key=>$val){
			$packagename[$val->name] = 	$val->name;
		}
		return $packagename;
	}

	public static function getCyclingDetails($branch_id){
		if($branch_id){
			// $last_run_info = [
			// 	'waste_type' => 'Liquid Waste',
			// 	'cycle_run_estimation' => '12',
			// 	'status' => 'Not Run'
			// ];
			// \App\Models\BranchCyclingInformation::create([
			// 	'branch_id' => $branch_id,
			// 	'last_run_information' => json_encode($last_run_info)
			// ]);
			return $data = app('App\Models\BranchCyclingInformation')->getListing(['branch_id' => $branch_id,'single_record' => true]);

		}
		return false;
	}

	public static function messageSendToSlack($data = []){
		if(isset($data['text']) && $data['text']){
			$url = 'https://hooks.slack.com/services/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';
			$res = self::callAPI('POST',$url,$data);
			return $res;
		}
		return false;
	}

	public static function getUserAllBranchId($user){
		$data = [];
		if($user){
			if($user->permission_level == 2){
				$data = app('App\Models\BranchUser')->getListing(['pluck'=>['companybranch_id'],'user_id' => $user->id,'branch_status' => 1]);
			}
			else{
				$data = app('App\Models\CompanyBranch')->getListing(['pluck'=>['id'],'company_id' => $user->company_id,'status' => 1]);
			}
		}
		return $data;
	}

	public static function getUserHomeSectionSettings($user){
		$data = [];
		$sections = app('App\Models\HomeSection')->getListing([]);
		foreach($sections as $key => $val){
			$status = 0;
			$setings = app('App\Models\HomeSectionSetting')->getListing(['section_id'=>$val->id,'customer_type' => $user->customer_type,'single_record' => true]);
			if($setings){
				if(($setings->customer_type == 1 || ($setings->customer_type == 0 && $user->customer_type == 1)) && $setings->locations){
					$all_branch_id = self::getUserAllBranchId($user);
					$locations = explode(',',$setings->locations);
					if(array_intersect($all_branch_id, $locations)){
						$status = 1;
					}
					else{
						$status = 0;
					}
				}
				else{
					$status = 1;
				}
			}
			$data[$val->slug] = $status;
		}
		return $data;
	}


}
