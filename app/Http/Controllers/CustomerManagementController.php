<?php
namespace App\Http\Controllers;

use DB;
use Auth;
use Exception;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Company;
use App\Models\Package;

use App\Models\BranchUser;
use App\Models\Speciality;
use App\Jobs\ImportCompany;
use App\Models\AddressData;
use App\Models\Designations;
use Illuminate\Http\Request;
use App\Models\CompanyBranch;
use App\Models\BranchSpecialty;
use App\Models\CompanyPackages;
use App\Models\RelationshipRole;
use App\Models\CompanySpeciality;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Helpers\HubSpot as HubspotAPI;
use App\Helpers\ZenDesk as ZendeskAPI;
use Illuminate\Support\Facades\Response;

class CustomerManagementController extends Controller {
	public function __construct($parameters = array()) {
		parent::__construct($parameters);
		$this->_module      = 'Customers';
		$this->_routePrefix = 'customer';
		$this->_model       = new Company();
		$this->_offset = 15;

	}


	public function index(Request $request){
		// $third_party = [
		// 	'branch_name' => 'Test Child 6 DZT3',
		// 	'branch_email' => '',
		// 	'branch_phone' => 2182371,
		// 	'branch_uniq_id' => '4410982356',
		// 	'branch_address' => '7888 Westminster Boulevard, Westminster, CA, USA',
		// 	'branch_state' => 'CA',
		// 	'branch_city' => 'Westminster',
		// 	'branch_postcode' => 92683,
		// 	'branch_country' => 'United States',
		// 	'lead_source_1' => 'Channel',
		// 	'lead_source_2' => 'Direct Supply',
		// 	'company_name' => 'Joseph Test',
		// 	'company_phone' => '111-222-3333',
		// 	'company_email' => '',
		// 	'company_id' => '3330166296',
		// 	'red_bag_reserve' => 4,
		// 	'sharps_reserve' => 4,
		// 	'rb_container_type' => 'Rocker',
		// 	'sh_container_type' => 'Spinner',
		// 	'branch_billing_address' => '7888 Westminster Boulevard, Westminster, CA, USA',
		// 	'branch_billing_state' => 'CA',
		// 	'branch_billing_city' => 'Westminster',
		// 	'branch_billing_postcode' => '92683',
		// 	'branch_billing_country' => 'United States',
		// 	'contact_firstname' => '',
		// 	'contact_lastname' =>'',
		// 	'contact_phone' => '',
		// 	'contact_email' => '',
		// 	'contact_role' => '',
		// 	'contact_id' => '',
		// ];
		//$hubspotRes = \App\Helpers\HubSpot::createUser($third_party);
		//$zendeskRes = \App\Helpers\ZenDesk::createUser($third_party);
		// $recurlyRes = \App\Helpers\Recurly::createUser($third_party);
		// dd();
		$this->initIndex();
		$srch_params             = $request->all();
		$srch_params['with'] = ['addressdata','speciality.speciality'];
		$this->_data["companies"] = $this->_model->getListing($srch_params, $this->_offset)->appends($request->input());
		$this->_data['pageHeading'] = 'CUSTOMER LISTING';
		return view('admin.customer.index',$this->_data);
	}
	public function destroy($id){
		$data = app('App\Models\Company')->getListing(['id' =>$id]);
		if($data){
			$data->delete();
			\App\Models\CompanyBranch::where('company_id',$data->id)->delete();
			return redirect()->back()->with('error', 'Deleted successfully');
		}
		else{
			return redirect()->back()->with('error', 'Details not found');
		}
	}
	public function importData(Request $request){
		$input  = $request->all();

		$validationRules = [
			'import_file' =>'required|mimes:csv,txt',
		];
		$validator = \Validator		::make($request->all(), $validationRules);
		if ($validator->fails()) {
			return redirect()->back()->with('error',$validator->errors()->first());
		}
		else{
			$extension = $request->file('import_file')->getClientOriginalExtension();
			// dd($extension);
			if (strtolower($extension) != 'csv') {
				return redirect()->back()->with('error','File format not supported');
			}
			$file = $request->file('import_file');
			$customerArr = \App\Helpers\Helper::CSVToArray($file);
           // dd($customerArr);
			foreach ($customerArr as $key => $val) {
                // dd($val);
                //ALTER TABLE `company_branch` CHANGE `company_number` `company_number` VARCHAR(255) NULL DEFAULT NULL;

                $newPayload = [];
                foreach ($val as $key1 => $value) {
                    $encoding = mb_detect_encoding($value, 'UTF-8, ISO-8859-1, WINDOWS-1252, WINDOWS-1251', true);
                    if ($encoding != 'UTF-8') {
                        $value = iconv($encoding, 'UTF-8//IGNORE', $value);
                    }
                    $newPayload[$key1] = $value;
                }
                if (count($newPayload) > 16) {
                    unset($newPayload[count($newPayload)-1]);
                }
                //dd($newPayload);
                $jobDispatch = ImportCompany::dispatch($newPayload);
				//dd($jobDispatch);
			}

			return redirect()->back()->with('success','Please wait import may take few minutes');
		}

	}

	#1
	public function create(Request $request,$id) {
		$this->initIndex();
		$this->_data['breadcrumb']['#'] = 'General Information';
		$input = $request->all();
		// if id = 0, company
		//$id = isset($input['id'])?$input['id']:0;
		$company = $addressdata = null;
		if( $id == 0  && !Company::where('id', '=', $id)->first() ){
			$company = new Company();
		}
		else{
			$company = Company::with(['speciality'])->where('id', '=', $id)->first();
		}
		// if id = 0, AddressData
		if(  AddressData::where('id', '=',  $company->addressdata_id )->first()  ){
			$addressdata = AddressData::where('id', '=',  $company->addressdata_id )->first();
		}
		else{
			$addressdata = new AddressData();
		}

		if($request->isMethod('post')){
			$validator = Validator::make($request->all(), [
				//'email' => 'required|email',
				'company_name' => 'required',
				'company_number' => 'required',
				//'phone' => 'required',
				'addressline1' => 'required',
				//'website' => 'required|unique:company,website,' . $id . ',id'
			]);

			if ($validator->fails()) {
				return redirect()->back()
							->withErrors($validator)
							->withInput();
			}
			$addressdata->addressline1 = request('addressline1');
			$addressdata->address1 = request('address1');
			$addressdata->address2 = request('address2');
			$addressdata->locality = request('locality');
			$addressdata->state = request('state');
			$addressdata->postcode = request('postcode');
			$addressdata->country = request('country');
			$addressdata->latitude = request('latitude');
			$addressdata->country = request('longitude');

			$addressdata->save();


			if( $addressdata->id ){
				$company->company_name = request('company_name');
				$company->company_number = request('company_number');
				$company->phone = request('phone');
				//$company->email = request('email');
				$company->owner = request('owner');
				$company->website = request('website');
				$company->addressdata_id = $addressdata->id;
				$company->lead_source = request('leadsource_1');
				$company->leadsource_2 = request('leadsource_2');
				$company->save();

				//companyspeciality save
				if($company->id){
					#delete old  for update   companyspeciality
					CompanySpeciality::where('company_id', '=',  $company->id)->delete();

					$specialitie = request('specialities');
					if( is_array($specialitie) ){
						foreach ($specialitie as $s ){
							$companyspeciality = new CompanySpeciality();
							$companyspeciality->company_id = $company->id;
							$companyspeciality->specality_id = $s;
							$companyspeciality->save();
						}
					}
					\App\Models\CompanyCorporateContacts::where('company_id',$company->id)->delete();
					if(isset($input['contact_person_ids']) && is_array($input['contact_person_ids'])){
						foreach($input['contact_person_ids'] as $key=>$val){
							\App\Models\CompanyCorporateContacts::create([
								'company_id' => $company->id,
								'user_id' => $val,
								'is_owner' => isset($input['is_owners'][$key])?$input['is_owners'][$key]:0
							]);
							\App\Models\User::where('id',$val)->update(['company_id' => $company->id]);
						}
					}
					self::createDefaultTranctionalPackage($company->id);
				}


				return redirect(route('customers.create.contact',  $company->id ));
			}

		}
		//post end
		$this->_data["id"] = $id;
		$this->_data["navlink_isactive"] = true;
		$this->_data["specilities"] =  Speciality::where('status', '=', 'y' )->get();
		$this->_data["states"] = DB::table('location_states')->get();

		$this->_data["company"] = $company;
		$this->_data['addressdata'] = $addressdata;
		$contacts_list = ($id)?\App\Models\CompanyCorporateContacts::with(['user'=>function($q){return $q->select('id','email','phone');}])->where('company_id', '=', $id )->get()->toArray():[];
		if(!$id && !count($contacts_list) && (isset($input['guest']) && $input['guest'])){
			$contacts_list = app('App\Models\User')->getListing(['id'=>$input['guest']]);
		}
		$this->_data['contacts_list'] = $contacts_list;

		return view('admin.customer.create.tab1.home-1',$this->_data);
	}

	#2
	public function contact(Request $request, $id) {
		$this->initIndex();
		$this->_data['breadcrumb']['#'] = "Contacts";
		// if id = 0, company
		$company = $addressdata = null;
		if( !Company::where('id', '=', $id)->first() ){
			return redirect()->route('customers.index');
		}
		else{
			$company = Company::where('id', '=', $id)->first();
		}
		//user
		$srch_params = $request->all();
		$query = User::where('company_id', '=', $company->id )
				->when(isset($srch_params['srch_params']), function ($q) use ($srch_params) {
					return $q->whereRaw("CONCAT(users.first_name, ' ', users.last_name) LIKE '%{$srch_params['srch_params']}%'")
								->orWhereRaw("users.phone LIKE '%{$srch_params['srch_params']}%'")
								->orWhereRaw("users.email LIKE '%{$srch_params['srch_params']}%'");
				});
		$contact_list = $query->get();
		// dd($contact_list);
		$user = new User();
		if($request->isMethod('post')){
			if( request("user_id") ){
				$user = User::where('id', '=', request("user_id") )->first();
				//dd($_REQUEST);
			}

			$user_id = ($user)?$user->id:0;
			$status = ($user && $user->status)?$user->status:1;

			$validator = Validator::make($request->all(), [
				'first_name' => 'required',
				'last_name' => 'required',
				//'phone' => 'required',
				'email' => 'required|email|unique:users,email,' . $user_id . ',id,deleted_at,NULL',
				//'designation' => 'required',
				'company_relationship' => 'required',
				'permission_level' => 'required'

			]);

			if ($validator->fails()) {
				return redirect()->back()
							->withErrors($validator)
							->withInput();
			}

			//save data
			//dd($status);
			$user->status = $status; // 0 = inactive and email unverified, 1 = active, 2 = inactive and email verified, 3 = user rejected by admin
			$user->first_name = request('first_name');
			$user->last_name = request('last_name');
			$user->phone = request('phone');
			$user->email = request('email');
			$user->designation = request('designation');
			$user->company_relationship = request('company_relationship');
			$user->permission_level = request('permission_level');
			$user->company_id = $company->id;
			$user->customer_type = 1;
			$user->verified = 1;
			$user->user_type = 'user';
			$user->remember_token = Helper::randomString(25);
			//company_relationship
			//relationship pending
			$user->save();
			if($user && !$user_id){
                $url = \Config::get('services.frontend_url') ? \Config::get('services.frontend_url') : 'https://onsite-customer.glohtesting.com/';

				$role                      = \App\Models\Role::where('slug', 'guest-user')->first();
				if($role){
					$input['user_id']          = $user->id;
					$input['role_id']          = $role->id;
					$role                      = \App\Models\UserRole::create($input);
				}
				$mailData = [
					'name'       => $user->full_name,
					'activation_link' => $url . 'user/verify/' . $user->remember_token,
					'extra_text'      => '',
				];
				$fullName = $user->full_name;
				//\App\Models\SiteTemplate::sendMail($user->email, $fullName, $mailData, 'register'); //register_provider from db site_template table template_name field

			}
			return redirect(route('customers.create.contact',  $company->id ));

		}

		if($request->isMethod('put')){
			if( request('user_id') && request('company_id') && request('companybranch_id')  ){

				#delete old  for update  branchusers
				$branchusers_old = BranchUser::select('*')
							->where([
								['companybranch_id', '=',  request('companybranch_id') ],
								['company_id', '=', request('company_id') ]
							])
							->get();
				foreach($branchusers_old as $old){
					$old->delete();
				}
				$branchusers = new BranchUser();
				$branchusers->user_id= request('user_id');
				$branchusers->company_id= request('company_id');
				$branchusers->companybranch_id= request('companybranch_id');
				$branchusers->save();
				$response = array('success' => true, 'message' =>'added!');
			}
			else{
				$response = array('success' => false, 'message' =>'not added!');
			}

			return response()->json(['data' => $response ],200);

		}

		if($request->isMethod('patch')){
			//die("test");
			dd($_REQUEST);
		}

		$this->_data["states"] = DB::table('location_states')->get();

		$this->_data["id"] = $id;
		$this->_data["navlink_isactive"] = true;
		$this->_data["company"] = $company;
		$this->_data["user"] = $user;
		$this->_data["contact_list"] =  $contact_list;
		$branchModel = new CompanyBranch();
		$this->_data["companybranch_list"] = $branchModel->getListing(['company_id' => $company->id, 'with' => ['addressdata','branchusers.user','company']]);
		$this->_data["designations"] = Designations::where('is_active', '=', 1 )->get();

		return view('admin.customer.create.tab2.contacts-1',$this->_data);
	}

	public function contactAssignToBranch(Request $request){
		$input = $request->all();
		$validator = Validator::make($request->all(), [
			'company_id' => 'required',
			'user_id' => 'required',
			'branch_ids' => 'required',
		]);
		if ($validator->fails()) {
			return redirect()->back()
						->withErrors($validator)
						->withInput();
		}
		else{
			\App\Models\BranchUser::where(['user_id' => $input['user_id'], 'company_id' => $input['company_id']])->delete();
			$brnaches = explode(",",$input['branch_ids']);
			foreach($brnaches as $key=>$val){
				User::where('id',$input['user_id'])->update(['customer_type' => 1]);
				\App\Models\BranchUser::create([
					'user_id' => $input['user_id'],
					'company_id' => $input['company_id'],
					'companybranch_id' => $val
				]);
			}
			return redirect()->route('customers.create.contact',$input['company_id'])->with('success','Branch Assign successfully');
		}
	}

	public function branchAssignToContact(Request $request){
		$input = $request->all();
		$validator = Validator::make($request->all(), [
			'company_id' => 'required',
			'user_ids' => 'required',
			'branch_id' => 'required',
		]);
		if ($validator->fails()) {
			return redirect()->back()
						->withErrors($validator)
						->withInput();
		}
		else{
			\App\Models\BranchUser::where(['companybranch_id' => $input['branch_id'], 'company_id' => $input['company_id']])->delete();
			$brnaches = explode(",",$input['user_ids']);
			foreach($brnaches as $key=>$val){
				\App\Models\BranchUser::create([
					'user_id' => $val,
					'company_id' => $input['company_id'],
					'companybranch_id' => $input['branch_id']
				]);
			}
			return redirect()->route('customers.create.location',$input['company_id'])->with('success','Branch Assign successfully');
		}
	}

	public function contactDelete($company_id,$id){
		$user =  User::where(['id' => $id,'company_id' => $company_id])->first();
		if($user){
			$res = $user->delete();
			if($res){
				\App\Models\BranchUser::where(['user_id' => $id])->delete();
			}
			return redirect()->route('customers.create.contact',[$company_id])->with('success','Contact deleted successfully');
		}
		else{
			return redirect()->back()->with('error','Details not found');
		}
	}

	#3
	public function package(Request $request, $id) {
		$this->initIndex();
		$this->_data['breadcrumb']['#'] = 'Packages';

		// if id = 0, company
		$input = $request->all();
		$company = null;
		if( !Company::where('id', '=', $id)->first() ){
			return redirect()->route('customers.index');
		}
		else{
			$company = Company::where('id', '=', $id)->first();
		}

		if($request->isMethod('post')){

			if( request("task") == 'clone-package' && request("packageid")  ){
				//dd($_REQUEST);
				/*
				"clonepackage" => "1"
				"packageid" => "2"
				"company_id" => "1"
				*/


				$companypackages_old = CompanyPackages::select('*')
				->where([
					['package_id', '=',  request("packageid") ],
					['company_id', '=',  $company->id ]
				])
				->get();
				foreach($companypackages_old as $old){
					$old->delete();
				}

				$companypackages = new CompanyPackages();
				$packages  = Package::where('id', '=', request("packageid"))->first();

				$companypackages->package_id = $packages->id;
				$companypackages->company_id = $company->id;
				//$companypackages->branch_id  ??
				$companypackages->cust_haul = $packages->haul;
				//$companypackages->cust_box = $packages->
				$companypackages->cust_price = $packages->price;
				$companypackages->save();

				$response = array('success' => true, 'message' =>'added!');
			}
			if( request("task") == 'assign-branch' && request("companypackage_id") && request("branchid")  ){



				$companypackage  = CompanyPackages::where('id', '=', request("companypackage_id") )->first();

				$companypackage->branch_id = request("branchid");
				$companypackage->save();
				$response = array('success' => true, 'message' =>'added!');
			}
			if( request("task") == 'deletecompanypackage' && request("companypackage_id")  ){

				//dd($_REQUEST); //companypackage_id
				$companypackage  = CompanyPackages::where('id', '=', request("companypackage_id") )->first();
				$companypackage->delete();

				$response = array('success' => true, 'message' =>'deleted!');
			}
			else{
				$response = array('success' => false, 'message' =>'not added!');
			}

			return response()->json(['data' => $response ],200);

		}
		$packages = \App\Helpers\Helper::getPackageNameDropdown();
		if(isset($input['package_id'])){
			$this->_data['editPackage'] = Package::where(['company_id' => $id, 'id' => $input['package_id']])->first();
		}
		if(isset($input['fnc']) && $input['fnc']=="create"){
			$this->_data['editPackage'] = null;
		}

		$this->_data["id"] = $id;
		$this->_data["packagenames"] = $packages;
		$this->_data["navlink_isactive"] = true;
		$this->_data['transactionalpackages'] = \App\Models\TransactionalPackage::where('company_id',$id)->first();
		$this->_data["packages"] =  Package::where(['is_active' => 1,'company_id' => 0,'branch_id' => 0 ])->get();
		$this->_data["companypackages"] =  Package::with(['companybranch'])->where(['company_id' => $id,'branch_id' => 0, 'deleted_at' => NULL])->get();
		$this->_data["companybranch_list"] = CompanyBranch::where('company_id', '=', $company->id )->get();
		$this->_data["company"] =  $company;
		return view('admin.customer.create.tab3.package', $this->_data);
	}
	public function StoreUpdatePackage(Request $request,$id,$package_id=0){
		$input = $request->all();
		$details = null;
		if($package_id){
			$details = Package::where(['company_id' => $id, 'id' => $package_id])->first();
			if($details){
				$details->update($input);
			}
			else{
				return redirect()->back()
						->with('error','Details not found');
			}
		}
		else{
			$details = Package::create($input);
		}
		if($details){
			return redirect()->route('customers.create.package',[$id])
						->with('success','Package updated successfully');
		}
		else{
			return redirect()->back()
						->with('error','Package not found');
		}
	}

	public function deletePackage($id){
		$details = Package::where('id', '=', $id )->first();
		if($details){
			$details->delete();
			return redirect()->back()
						->with('success','Package deleted successfully');
		}
		else{
			return redirect()->back()
						->with('error','Package not found');
		}
	}

	/**
	 * Clone package from customer
	 */
	public function clonePackage(Request $request){
		$input = $request->all();
		$validator = Validator::make($request->all(), [
			'company_id' => 'required',
			'package_id' => 'required'
		]);
		if ($validator->fails()) {
			return redirect()->back()
						->withErrors($validator)
						->withInput();
		}
		else{
			$packages = explode(",",$input['package_id']);
			if(count($packages)){
				foreach($packages as $pkey=>$pavl){
					$packageDetails = \App\Models\Package::where(['id'=>$pavl,'deleted_at' => NULL])->first();
					if($packageDetails){
						$newDta = $packageDetails->toArray();
						unset($newDta['id']);unset($newDta['updated_at']);unset($newDta['created_at']);
						$newDta['company_id'] = $input['company_id'];
						$res = \App\Models\Package::create($newDta);
					}
				}
				return redirect()->back()
								->with('success','Package cloned successfully');

			}
			else{
				return redirect()->back()
								->with('error','Packages not found');
			}
		}
	}

	/**
	 * Create default Transaction package
	 *
	 */
	public static function createDefaultTranctionalPackage($company_id, $branch_id=0){
		$transactionPackageDetails = \App\Models\TransactionalPackage::where(['company_id' => $company_id,'branch_id' => $branch_id])->first();
		if(!$transactionPackageDetails){
			$tranCompany = ($branch_id)?$company_id:0;
			$defaultDetails = \App\Models\TransactionalPackage::where(['company_id' => $tranCompany,'branch_id' => 0])->first();
			if($defaultDetails){
				$newdata = $defaultDetails->toArray();
				unset($newdata['id']);unset($newdata['created_at']);unset($newdata['updated_at']);
				$newdata['company_id'] = $company_id;
				$newdata['branch_id'] = $branch_id;
				\App\Models\TransactionalPackage::create($newdata);
			}
		}
	}
	#4
	public function location(Request $request, $id) {
		$this->initIndex();
		$this->_data['breadcrumb']['#']      = 'Locations';

		// if id = 0, company
		$this->_data["id"] = $id;
		$company = Company::where('id', '=', $id)->first();
		if($company){
			$addressdata = $companybranch = null;
			$companyBrancObject = new CompanyBranch();
			$this->_data["companybranch_list"] = $companyBrancObject->getListing(['company_id'=>$id,'with'=>['addressdata','billingaddress','branchusers.user','branchspecialty.speciality_details','package_details']]);

			$this->_data['branch_code'] = \App\Helpers\Helper::generateMasterCode('\App\Models\CompanyBranch','uniq_id',10);
			$this->_data['company'] = $company;
			$this->_data["navlink_isactive"] = true;
			$this->_data["shipping"] = true;
			if( request('edit') && request('companybranchid') ){
				$companybranch = $companyBrancObject->getListing(['id'=>request('companybranchid'),'company_id'=>$id,'with'=>['addressdata','branchusers.user']]);
				$this->_data['branch_code'] = ($companybranch)?$companybranch->uniq_id:$this->_data['branch_code'];
			}
			else{
				$companybranch = new CompanyBranch();
			}
			if(isset($companybranch->addressdata) && $companybranch->addressdata){
				$addressdata = $companybranch->addressdata;
			}
			else{
				$addressdata = new AddressData();
			}
			$this->_data['addressdata'] = $addressdata;

			if(isset($companybranch->billingaddress) && $companybranch->billingaddress){
				$billingaddress = $companybranch->billingaddress;
			}
			else{
				$billingaddress = new AddressData();
			}
			$this->_data['billingaddress'] = $billingaddress;

			$this->_data['companybranch'] = $companybranch;
			$this->_data['sh_container'] = $companybranch->sh_container_type;
			$this->_data['rb_container'] = $companybranch->rb_container_type;

			$this->_data["contact_list"] = User::where('company_id', '=', $company->id )->get();
			//dd($this->_data);
            return view('admin.customer.create.tab4.location', $this->_data);
		}
		else{
			return redirect()->route('customers.index');
		}
	}
	public function branchDetails(Request $request, $id){
		$this->initIndex();
		$this->_data['breadcrumb']['#']      = 'Locations';

		// if id = 0, company
		$this->_data["id"] = $id;
		$company = Company::where('id', '=', $id)->first();
		if($company){
			$this->_data['company'] = $company;
			$companyBrancObject = new CompanyBranch();
			$this->_data["companybranch_list"] = $companyBrancObject->getListing(['company_id'=>$id,'with'=>['addressdata','branchusers.user','branchspecialty.speciality_details','package_details']]);
			$this->_data['includePage'] = 'admin.' .$this->_routePrefix . '.branch-lists';
			$viewPage = '';
			$this->_data['pageHeading'] = 'LOCATION LISTING';
			return view('admin.components.general' . $viewPage, $this->_data);

		}
		else{
			return redirect()->back()->with('error','Details not found');
		}
	}
	public function branchInfoDetails(Request $request,$company_id,$id){
		$srch_params = $request->all();
		if(!isset($srch_params['tab']) || (isset($srch_params['tab']) && empty($srch_params['tab']))){
			$srch_params['tab'] = 'contact';
		}
		$this->initIndex();
		// if id = 0, company
		$this->_data["id"] = $id;
		$company = Company::where('id', '=', $company_id)->first();
		$companyBrancObject = new CompanyBranch();
		$companybranch = $companyBrancObject->getListing(['company_id'=>$company_id,'id'=>$id,'with'=>['addressdata','branchspecialty.speciality_details']]);
		if($company && $companybranch){
			$this->_data['company'] = $company;
			$this->_data["companybranch"] = $companybranch;
			$this->_data['breadcrumb'][route('customers.branches',$company->id)]      = 'Locations';
			$this->_data['breadcrumb']['#']      = 'Location Info';
			$this->_data['includePage'] = 'admin.' .$this->_routePrefix . '.branch-info-details';
			$viewPage = '';
			if($srch_params['tab'] == 'contact'){
				$this->_data['branchusers'] = app('App\Models\BranchUser')->getListing(['with' => ['user'],'companybranch_id'=>$id]);
			}
			if($srch_params['tab'] == 'pricing'){
				$this->_data['transactionalpackages'] = app('App\Models\TransactionalPackage')->getListing(['company_id' => $company_id,'branch_id' => $id, 'single_record' => true]);
			}
			if($srch_params['tab'] == 'package'){
				$this->_data['package'] = app('App\Models\Package')->getListing(['company_id' => $company_id,'branch_id' => $id, 'single_record' => true]);
			}
			if($srch_params['tab'] == 'hauling'){
				$this->_data["hauling_list"] = app('App\Models\CompanyHauling')->getListing(['company_id' => $company_id,'branch_id' => $id, 'with' => ['branch_details.addressdata','package_details','manifest_details']]);
			}
			if($srch_params['tab'] == 'invoices'){
				if(!isset($srch_params['begin_date']) || (isset($srch_params['begin_date']) && empty($srch_params['begin_date']))){
					$srch_params['begin_date'] = date('Y-m').'-01';
				}
				if(!isset($srch_params['end_date']) || (isset($srch_params['end_date']) && empty($srch_params['end_date']))){
					$srch_params['end_date'] = date('Y-m-t');
				}
				$invData = [];
				$accountdetails = [];
				$api_key = \Config::get('settings.RECURLY_KEY');
				$client = new \Recurly\Client($api_key);
				$options = [
					'params' => [
						//'limit' => 1,
						'begin_time' => $srch_params['begin_date'],
						'end_time' => $srch_params['end_date']
					]
				];
				// $account = $client->getAccount('code-'.$companybranch['uniq_id']);
				// $accounts = $client->listAccounts($options);
				// foreach($accounts as $account) {
				// 	echo 'Account code: ' . $account->getCode() . PHP_EOL;
				// 	$accountdetails[] = $account->getId();//
				// }
				// dd($account);



					if($companybranch->recurring_id){
						$account = $client->getAccount($companybranch->recurring_id);
						$invoices = $client->listAccountInvoices($account->getId(),$options);
						$key = 0;
						foreach($invoices as $invoice) {
							// dd($invoice);
							$invData[$key]['branch_name'] = $companybranch->name;
							$invData[$key]['branch_code'] = $companybranch->uniq_id;
							$invData[$key]['code'] = $invoice->getAccount()->getCode();
							$invData[$key]['id'] = $invoice->getId();
							$invData[$key]['company'] = $invoice->getAddress()->getCompany();
							$invData[$key]['state'] = $invoice->getState();
							$invData[$key]['created_at'] = $invoice->getCreatedAt();
							// $invData[$key]['address'] = $invoice->getAddress();
							$invData[$key]['balance'] = $invoice->getBalance();
							$invData[$key]['total'] = $invoice->getTotal();
							$invData[$key]['subtotal'] = $invoice->getSubtotal();
							$invData[$key]['discount'] = $invoice->getDiscount();
							foreach($invoice->getLineItems() as $line){
								$invData[$key]['line_items'][] = [
									'description' =>$line->getDescription(),
									'amount' =>$line->getAmount(),
								];
							}
							$key++;
						}
					}
					// dd($invData);
				//}
				$this->_data["invoices"] = $invData;
			}
			if($srch_params['tab'] == 'inventory'){
				// $url = 'https://wastetech-dev.s3-us-west-2.amazonaws.com/api/mock/inventory.json';
				$url = 'locations/'.$companybranch['uniq_id'].'/inventory' ;
				$containerInventory = \App\Helpers\Helper::callAPI('GET',$url,[]);
				$containerInventory = json_decode($containerInventory, true);
				$this->_data['containerInventory'] = $containerInventory;

				$url = 'locations/'.$companybranch['uniq_id'].'/devices';
				$te5000 = \App\Helpers\Helper::callAPI('GET',$url,[]);
				$te5000 = json_decode($te5000, true);
				$this->_data['te5000'] = $te5000;
			}
			$this->_data['srch_params'] = $srch_params;
			$this->_data['pageHeading'] = 'Location Info';

			return view('admin.components.general' . $viewPage, $this->_data);

		}
		else{
			return redirect()->back()->with('error','Details not found');
		}
	}

	public function locationInventoryDetails(Request $request) {
		$input = $request->all();
		$companyBrancObject = new CompanyBranch();
		$this->_data['location'] = $companyBrancObject->getListing(['unique_id'=> $input['unique_id']])[0];

		$this->_data['uniq_id'] = $input['unique_id'];
		$url = 'locations/'.$input['unique_id'].'/inventory' ;
		$containerInventory = \App\Helpers\Helper::callAPI('GET',$url,[]);
		$containerInventory = json_decode($containerInventory, true);
		$this->_data['containerInventory'] = $containerInventory;

		$url = 'locations/'.$input['unique_id'].'/devices';
		$te5000 = \App\Helpers\Helper::callAPI('GET',$url,[]);
		$te5000 = json_decode($te5000, true);
		$this->_data['te5000'] = $te5000;
		// echo "<pre>";
		// print_r($this->_data);
		// dd();
		return view('admin.customer.create.tab4.inventory-partial', $this->_data);
	}

	public function branchInventoryReorder(Request $request){
		try{
			$input = $request->all();
			//\App\Helpers\Helper::messageSendToSlack('text');
			return Response::json(['success'=>true,'msg'=>'Re-order successfully']);

		} catch (Exception $e) {
			return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
		}
	}

	public function branchInventoryLastRunInfo(Request $request){
		try{
			$input = $request->all();
			//\App\Helpers\Helper::messageSendToSlack('text');
			// $url = 'locations/'.$input['location_id'].'/cycles';
			$url = 'locations/'.$input['location_id'].'/devices/'.$input['imie_no'].'/cycles';
			// .$input['imie_no'].'/cycleHistory'
			$te5000 = \App\Helpers\Helper::callAPI('GET',$url,['imei'=>$input['imie_no']]);
			$te5000 = json_decode($te5000, true);
			$last_result = count($te5000['results'])-1;
			$data = isset($te5000['results']) ? $te5000['results'][$last_result] : [];
			if(isset($data['startDateTime'])){
				$data['startDateTime'] = \App\Helpers\Helper::showdate($data['startDateTime'],true,'m-d-Y h:i A');
			}
			if(isset($data['endDateTime'])){
				$data['endDateTime'] = \App\Helpers\Helper::showdate($data['endDateTime'],true,'m-d-Y h:i A');
			}
			return Response::json(['success' => true,'msg' => 'Details fetch successfully','data' => $data]);

		} catch (Exception $e) {
			return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
		}
	}
	public function inventoryMachinePing(Request $request){
		try{
			$input = $request->all();
			//\App\Helpers\Helper::messageSendToSlack('text');
			return Response::json(['success'=>true,'msg'=>'Machine ping successfully']);

		} catch (Exception $e) {
			return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
		}
	}
	public function branchStoreUpdate(Request $request,$company_id){
		$company = Company::where('id', '=', $company_id)->with(['leadsourceone', 'leadsourcetwo'])->first();

		$new_account = false;
		if($company){
			$msg = 'Branch created successfully';
			$input = $request->all();
			$id = (isset($input['id']) && $input['id']) ? $input['id'] : 0;
			$input['uniq_id'] = \App\Helpers\Helper::getOnlyIntegerValue($input['uniq_id']);

			if ($id == 0) {
				$validator = Validator::make($input, [
					'uniq_id' => 'required|numeric|unique:company_branch,uniq_id,' . $id . ',id',
				],[
					'uniq_id.required' => 'The Branch Code field is required',
					'uniq_id.numeric' => 'The Branch Code field must be numeric',
					'uniq_id.unique' => 'The Branch Code has already been taken'
				]);
			} else {
				$validator = Validator::make($input, [
					'uniq_id' => 'required|numeric|unique:company_branch,uniq_id,' . $id . ',id,company_id,'.$company_id,
				],[
					'uniq_id.required' => 'The Branch Code field is required',
					'uniq_id.numeric' => 'The Branch C		ode field must be numeric',
					'uniq_id.unique' => 'The Branch Code has already been taken'
				]);
			}
			if ($validator->fails()) {
				return redirect()->back()
							->withErrors($validator)
							->withInput();
			}
			$input['company_id'] = $company->id;
						$addressdata = null;
			$billingaddress = null;
			if($id){
				$companyBrancObject = new CompanyBranch();
				$data = $companyBrancObject->getListing(['id'=>$id,'with'=>['addressdata','billingaddress','branchusers.user']]);
				if($data){
					$addressdata = $data->addressdata;
					$billingaddress = $data->billingaddress;
					$data->update($input);
					$msg = 'Changes has been saved successfully';
				}
			}
			else{
				//$input['uniq_id'] = \App\Helpers\Helper::generateMasterCode('\App\Models\CompanyBranch','uniq_id');
				$data = CompanyBranch::create($input);
				$new_account = true;

			}
			if($data){
				// $hubspotData = [
				// 	'company_id' => $company->id,
				// 	'id' => $input['package_id']
				// ];
				/****Create Company In Zendesk */
				/****Create Company In Hubspot */
				//$hubspot = HubspotAPI::createUser();
				/****Create Company In Recurly */
				self::createDefaultTranctionalPackage($company->id,$data->id);
				$billing_data = null;
				if($input['addressline1_b']) {
					$billing_data = array(
						'addressline1' => $input['addressline1_b'],
						'address1' => $input['address1_b'],
						'address2' => $input['address2_b'],
						'locality' => $input['locality_b'],
						'state' => $input['state_b'],
						'postcode' => $input['postcode_b'],
						'country' => $input['country_b'],
						'latitude' => $input['latitude_b'],
						'longitude' => $input['longitude_b'],
					);
				}

				if($addressdata){
					$addressdata->update($input);
				}
				else{
					$addressdata = AddressData::create($input);
				}
				if($addressdata){
					$data->update(['addressdata_id' => $addressdata->id]);
				}

				if ($billing_data) {
					if($billingaddress){
						$billingaddress->update($billing_data);
					}
					else{
						$billingaddress = AddressData::create($billing_data);
					}
					if($addressdata){
						$data->update(['billingaddress_id' => $billingaddress->id]);
					}
				}


				$branchspecialty_old = BranchSpecialty::where(['company_branch_id' => $data->id])->delete();
				if( isset($input['specialities']) && is_array($input['specialities']) ){
					foreach ($input['specialities'] as $s ){
						$branchspecialty = new BranchSpecialty();
						$branchspecialty->specality_id = $s;
						$branchspecialty->company_branch_id = $data->id;
						//$branchspecialty->company_id = $company->id;
						$branchspecialty->save();
					}
				}

				// save  branch_users
				$branch_users = request('branch_users') ?  request('branch_users') : array()  ;
				#delete old  for update  branchusers
				$branchusers_old = BranchUser::where(['companybranch_id' => $data->id,'company_id' => $company->id])->delete();
				foreach ($branch_users as $user){
					$branchuser = new BranchUser();
					$branchuser->user_id= $user;
					$branchuser->company_id= $company->id;
					$branchuser->companybranch_id= $data->id;
					$branchuser->save();
				}
				if($new_account){
					$third_party['branch_name'] = $data->name;
					$third_party['branch_email'] = $data->email;
					$third_party['branch_phone'] = $data->phone;
					$third_party['branch_uniq_id'] = $data->uniq_id;
					$third_party['branch_address'] = $addressdata->addressline1;
					$third_party['branch_state'] = $addressdata->state;
					$third_party['branch_city'] = $addressdata->locality;
					$third_party['branch_postcode'] = $addressdata->postcode;
					$third_party['branch_country'] = $addressdata->country;
					$third_party['lead_source_1'] = isset($company->leadsourceone)? $company->leadsourceone->name: 'Marketing';
					$third_party['lead_source_2'] = isset($company->leadsourcetwo)? $company->leadsourcetwo->name: 'Facebook';
					$third_party['company_name'] = $company->company_name;
					$third_party['company_phone'] = $company->phone;
					$third_party['company_email'] = $company->email;
					$third_party['company_id'] = $company->company_number;
					$third_party['red_bag_reserve'] = $data->rb_rop;
					$third_party['sharps_reserve'] = $data->sh_rop;
					$third_party['rb_container_type'] = $data->rb_container_type;
					$third_party['sh_container_type'] = $data->sh_container_type;
					$third_party['branch_billing_address'] = $billingaddress?$billingaddress->addressline1:'';
					$third_party['branch_billing_state'] = $billingaddress?$billingaddress->state:'';
					$third_party['branch_billing_city'] = $billingaddress?$billingaddress->locality:'';
					$third_party['branch_billing_postcode'] = $billingaddress?$billingaddress->postcode:'';
					$third_party['branch_billing_country'] = $billingaddress?$billingaddress->country:'';
					$branchContactDetails = app('App\Models\BranchUser')->getListing(['companybranch_id' => $data->id, 'with' => ['user'],'single_record' => true]);
					$userDetails = ($branchContactDetails && $branchContactDetails->user) ? $branchContactDetails->user : null;
					if ($userDetails) {
						$role = RelationshipRole::where('id', '=', $userDetails->company_relationship)->first();
					}
					$third_party['contact_firstname'] = ($userDetails) ? $userDetails->first_name : null;
					$third_party['contact_lastname'] = ($userDetails) ? $userDetails->last_name : null;
					$third_party['contact_phone'] = ($userDetails) ? $userDetails->phone : null;
					$third_party['contact_email'] = ($userDetails) ? $userDetails->email : null;
					$third_party['contact_role'] = ($userDetails) ? $role->name : null;
					$third_party['contact_id'] = ($userDetails) ? $userDetails->id : null;

					/**
					 * Call Api
					 *
                     * */

					try {
						$hubspotRes = \App\Helpers\HubSpot::createUser($third_party);
						$zendeskRes = \App\Helpers\ZenDesk::createUser($third_party);
						$recurlyRes = \App\Helpers\Recurly::createUser($third_party);
						$companyBrancObject = new CompanyBranch();
						$branchData = $companyBrancObject->getListing(['id'=>$data->id]);
						if($branchData){
							$branchData->recurring_id = $recurlyRes['company_id'];
							$branchData->hubspot_id = $hubspotRes['company_id'];
							$branchData->zendesk_id = $zendeskRes['company_id'];
							$branchData->save();
						}
					} catch (Exception $e) {

					}


				}
				if(isset($input['package_id']) && !empty($input['package_id'])){
					$query = [
						'company_id' => $company->id,
						'id' => $input['package_id']
					];
					$packageModel = new \App\Models\Package();
					$package = $packageModel->getListing($query);
					if($package){
						$newData = $package->toArray();
						$newData['monthly_rate'] = (isset($input['package_price']) && ($input['package_price'] != '')) ? $input['package_price']:$newData['monthly_rate'];
						unset($newData['id']);unset($newData['created_at']);unset($newData['updated_at']);
						$newData['branch_id'] = $data->id;
						\App\Models\Package::create($newData);
					}
				}
				return redirect()->route('customers.create.location',  $company->id )->with('success',$msg);
			}
			else{
				return redirect()->route('customers.create.location',[$company_id])->with('error','Details not found');
			}
		}
		else{
			return redirect()->route('customers.index')->with('error','Details not found');
		}
	}
	public function branchRemove(Request $request,$company_id,$id){
		$company = Company::where('id', '=', $company_id)->first();
		if($company){
			if($id){
				$companyBrancObject = new CompanyBranch();
				$data = $companyBrancObject->getListing(['id'=>$id,'with'=>['addressdata','branchusers.user']]);
				if($data){
					$data->delete();
					$data->addressdata->delete();
					\App\Models\BranchUser::where(['company_id'=>$company->id,'companybranch_id'=>$data->id])->delete();
					\App\Models\BranchSpecialty::where(['company_branch_id'=>$data->id])->delete();
					return redirect()->route('customers.create.location',  $company->id )->with('success','Branch deleted not found');
				}
				else{
					return redirect()->back()->with('error', 'Details not found');
				}
			}
			else{
				return redirect()->back()->with('error', 'Invalid data');
			}

		}
		else{
			return redirect()->route('customers.index')->with('error','Details not found');
		}
	}

	public function branchTransactionDetails(Request $request){
		$input = $request->all();
		try{
            $validations = [
                'company_id'   => 'required|integer',
				'branch_id'  => 'required|integer',
            ];
            $messages = [
                'company_id.required' => 'Sorry! some data is missing ',
				'branch_id.required' => 'Sorry! some data is missing '
            ];
            $validator = \Validator::make($request->all(), $validations,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(),200);
            }
            else{
				$tranModel = new \App\Models\TransactionalPackage();
				$input['single_record'] = true;
				$transactionDetails = $tranModel->getListing($input);
                $view = view("admin.customer.create.tab4.transaction-details",['transactionalpackages'=>$transactionDetails,'input'=>$input])->render();
                return Response::json(['success'=>true,'msg'=>'List generate success fully','html'=>$view]);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }
	}

	public function updateBranchTransactionDetails(Request $request){
		$input = $request->all();
		try{
            $validations = [
                'company_id'   => 'required|integer',
				'branch_id'  => 'required|integer',
            ];
            $messages = [
                'company_id.required' => 'Sorry! some data is missing ',
				'branch_id.required' => 'Sorry! some data is missing '
            ];
            $validator = \Validator::make($request->all(), $validations,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(),200);
            }
            else{
				$tranModel = new \App\Models\TransactionalPackage();
				$input['single_record'] = true;
				$transactionDetails = $tranModel->getListing($input);
				if($transactionDetails){
					$res = $transactionDetails->update($input);
					if($res){
						return Response::json(['success'=>true,'msg'=>'Changes has been saved successfully','data'=>[]]);
					}
					else{
						throw new Exception('Not update',200);
					}
				}
				else{
					throw new Exception('Details not found',200);
				}
			}
		}catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }

	}

	public function branchPackageList(Request $request){
		$input = $request->all();
		try{
            $validations = [
                'company_id'   => 'required|integer',
				'page_type' => 'required'
            ];
            $messages = [
                'company_id.required' => 'Sorry! some data is missing ',
				'branch_id.required' => 'Sorry! some data is missing '
            ];
            $validator = \Validator::make($request->all(), $validations,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(),200);
            }
            else{
				$packageModel = new \App\Models\Package();
				$query = [
					'company_id' => $input['company_id'],
					'branch_id'  => 0
				];
				if(isset($input['branch_id']) && !empty($input['branch_id'])){
					$query2['company_id'] = $input['company_id'];
					$query2['branch_id'] = $input['branch_id'];
					$query2['single_record'] = true;
					$packages = $packageModel->getListing($query2);
					if($packages){
						//$packagename = \App\Helpers\Helper::getPackageNameDropdown();
						$packagename = Package::with(['companybranch'])->where(['company_id' => $input['company_id'],'branch_id' => 0, 'deleted_at' => NULL])->get();
						$view = view("admin.customer.create.tab4.package-edit",['package'=>$packages,'input' => $input, 'packagenames' => $packagename])->render();
						return Response::json(['success'=>true,'msg'=>'Details fetch successfully','html'=>$view,'mod_head_content'=>'Package Details']);
					}
					else if($input['page_type'] == "create_edit"){
						$packages = $packageModel->getListing($query);
						if(count($packages)){
							$view = view("admin.customer.create.tab4.package-listing",['packages'=>$packages,'input' => $input])->render();
							return Response::json(['success'=>true,'msg'=>'Details fetched successfully','html'=>$view,'mod_head_content'=>'Package List']);

						}
						else{
							throw new Exception('Packages not found',200);
						}
					}
				}
				else{
					$packages = $packageModel->getListing($query);
					if(count($packages)){
						$view = view("admin.customer.create.tab4.package-listing",['packages'=>$packages,'input' => $input])->render();
						return Response::json(['success'=>true,'msg'=>'Details fetched successfully','html'=>$view,'mod_head_content'=>'Package List']);

					}
					else{
						throw new Exception('Packages not found',200);
					}
				}
			}
		}catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }
	}

	public function branchPackageClone(Request $request){
		$input = $request->all();
		try{
            $validations = [
                'company_id'   => 'required|integer',
				'branch_id'  => 'required|integer',
				'package_ids' => 'required'
            ];
            $messages = [
                'company_id.required' => 'Sorry! some data is missing ',
				'branch_id.required' => 'Sorry! some data is missing '
            ];
            $validator = \Validator::make($request->all(), $validations,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(),200);
            }
            else{
				$package_ids = explode(',',$input['package_ids']);
				\App\Models\Package::where([
					'company_id' => $input['company_id'],
					'branch_id'  => $input['branch_id']
				])->delete();
				$packageModel = new \App\Models\Package();
				$package_prices = isset($input['package_price']) ? explode(',',$input['package_price']):[];
				foreach($package_ids as $key=>$val){
					$query = [
						'company_id' => $input['company_id'],
						'id' => $val
					];
					$package = $packageModel->getListing($query);
					if($package && empty($package->branch_id)){
						$newData = $package->toArray();
						unset($newData['id']);unset($newData['created_at']);unset($newData['updated_at']);
						$newData['branch_id'] = $input['branch_id'];
						$newData['monthly_rate'] = isset($package_prices[$key]) ? $package_prices[$key] : $newData['monthly_rate'];
						$package = \App\Models\Package::create($newData);
						$this->_createBranchTe5000($package);
					}
				}
				return Response::json(['success'=>true,'msg'=>'Changes has been saved successfully','data'=>[]]);
			}
		}catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }
	}
	public function branchPackageRemove(Request $request){
		$input = $request->all();
		try{
            $validations = [
                'company_id'   => 'required|integer',
				'branch_id'  => 'required|integer',
				'package_id' => 'required'
            ];
            $messages = [
                'company_id.required' => 'Sorry! some data is missing ',
				'branch_id.required' => 'Sorry! some data is missing '
            ];
            $validator = \Validator::make($request->all(), $validations,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(),200);
            }
            else{
				$packageModel = new \App\Models\Package();
				$query = [
					'company_id' => $input['company_id'],
					'branch_id'  => $input['branch_id'],
					'id' => $input['package_id']
				];
				$package = $packageModel->getListing($query);
				if($package){
					$newData = $package->delete();
					$branchDetails = app('App\Models\CompanyBranch')->getListing(['id' => $input['branch_id']]);
					if($branchDetails){
						\App\Models\BranchTe5000Information::where(['branch_id' => $branchDetails->uniq_id])->delete();
					}
					return Response::json(['success'=>true,'msg'=>'Deleted successfully','data'=>[]]);
				}
				else{
					throw new Exception('Details not found',200);
				}
			}
		}catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }
	}

	public function branchPackageDetails(Request $request){
		$input = $request->all();
		try{
            $validations = [
                'company_id'   => 'required|integer',
				'branch_id'  => 'required|integer',
				'package_id' => 'required'
            ];
            $messages = [
                'company_id.required' => 'Sorry! some data is missing ',
				'branch_id.required' => 'Sorry! some data is missing '
            ];
            $validator = \Validator::make($request->all(), $validations,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(),200);
            }
            else{
				$packageModel = new \App\Models\Package();
				$query = [
					'company_id' => $input['company_id'],
					'branch_id'  => $input['branch_id'],
					'id' => $input['package_id']
				];
				$package = $packageModel->getListing($query);
				if($package){
					$view = view("admin.customer.create.tab4.package-edit",['package'=>$package,'input' => $input])->render();
					return Response::json(['success'=>true,'msg'=>'Changes has been saved successfully','html'=>$view]);
				}
				else{
					throw new Exception('Details not found',200);
				}
			}
		}catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }
	}

	public function branchPackageUpdate(Request $request){
		$input = $request->all();
		try{
            $validations = [
                'company_id'   => 'required|integer',
				'branch_id'  => 'required|integer',
				'id' => 'required'
            ];
            $messages = [
                'company_id.required' => 'Sorry! some data is missing ',
				'branch_id.required' => 'Sorry! some data is missing '
            ];
            $validator = \Validator::make($request->all(), $validations,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(),200);
            }
            else{
				$packageModel = new \App\Models\Package();
				$query = [
					'company_id' => $input['company_id'],
					'branch_id'  => $input['branch_id'],
					'id' => $input['id']
				];
				$package = $packageModel->getListing($query);
				if($package){
					$package->update($input);
					$this->_createBranchTe5000($package);
					return Response::json(['success'=>true,'msg'=>'Changes has been saved successfully','data'=>[]]);
				}
				else{
					throw new Exception('Details not found',200);
				}
			}
		}catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }
	}

	public function _createBranchTe5000($branchPackage){
		if($branchPackage){
			$branchDetails = app('App\Models\CompanyBranch')->getListing(['id' => $branchPackage->branch_id]);
			if($branchDetails){
				if($branchPackage->te_500 == 1){
					$existing = app('App\Models\BranchTe5000Information')->getListing(['branch_id' => $branchPackage->uniq_id,'single_record' => true]);
					if($existing){
						$existing->update([
							'branch_id' => $branchDetails->uniq_id,
							'package_id' => $branchPackage->id
						]);
					}
					else{
						$existing = \App\Models\BranchTe5000Information::create([
							'branch_id' => $branchDetails->uniq_id,
							'package_id' => $branchPackage->id
						]);
					}
					\App\Helpers\Helper::messageSendToSlack(['text' => $branchPackage->name.' added TE-5000 in '.$branchDetails->name.'('.$branchDetails->uniq_id.')']);
					return $existing;
				}
				else{
					\App\Models\BranchTe5000Information::where(['branch_id' => $branchPackage->uniq_id])->delete();
				}
				return true;
			}
			return false;
		}
	}

	public function branchAssignedContactDetails(Request $request){
		$input = $request->all();
		try{
            $validations = [
                'company_id'   => 'required|integer',
				'branch_id'  => 'required|integer',
            ];
            $messages = [
                'company_id.required' => 'Sorry! some data is missing ',
				'branch_id.required' => 'Sorry! some data is missing '
            ];
            $validator = \Validator::make($request->all(), $validations,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(),200);
            }
            else{
				$branchUserMod = new \App\Models\BranchUser();
				$branchUsers = $branchUserMod->getListing(['company_id'=>$input['company_id'],'companybranch_id' => $input['branch_id'],'with'=>['user']]);

                $view = view("admin.customer.create.tab4.assigned-contact-list",['contacts'=>$branchUsers])->render();
                return Response::json(['success'=>true,'msg'=>'List generate success fully','html'=>$view]);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }
	}




	//inventory
	public function inventory(Request $request, $id) {

		$this->_data["id"] = $id;
		$this->_data["navlink_isactive"] = true;
		return view('admin.customer.create.tab5.inventory', $this->_data);
	}

	//hauling
	public function haulingList(Request $request, $id) {

		$this->initIndex();
		$this->_data['breadcrumb']['#']      = 'Pickup';
		$input = $request->all();
		// if id = 0, company
		$this->_data["id"] = $id;
		$this->_data["navlink_isactive"] = true;
		$company = Company::where('id', '=', $id)->first();
		if($company){
			$this->_data['company'] = $company;
			$hauling = null;
			if(isset($input['hid']) && !empty($input['hid'])){
				$hauling = app('App\Models\CompanyHauling')->getListing(['id' => $input['hid']]);
			}
			$this->_data["hauling"] = $hauling;
			$this->_data["hauling_list"] = app('App\Models\CompanyHauling')->getListing(['company_id' => $id,'with' => ['branch_details.addressdata','package_details','manifest_details']]);
			$this->_data['pageHeading'] = 'Pickup';
			return view('admin.customer.create.tab6.hauling', $this->_data);
		}
		else{
			return redirect()->back()->with('error','Details not found');
		}

	}

	public function haulingStoreUpdate(Request $request,$id){
		$company = Company::where('id', '=', $id)->first();
		if($company){
			$input = $request->all();

			$hauling_id = (isset($input['hauling_id'])) ? $input['hauling_id']:0;
			$branch_id = (isset($input['branch_id'])) ? $input['branch_id']:0;
            //dd($branch_id);
            $package = Package::where('branch_id',$branch_id)->first();
            if (!$package) {
                return redirect()->back()
					->withError("Please assign a package to the location.")
					->withInput();
            }
            $input['package_id'] = $package->id;
			$validationRules = \App\Http\Controllers\HaulingController::validationRules($hauling_id);
			$validator = \Validator::make($request->all(), $validationRules);
			if ($validator->fails()) {
				return redirect()->back()
					->withErrors($validator)
					->withInput();
			}
			else{
				$results = app('App\Http\Controllers\HaulingController')->storeUpdate($input,$hauling_id);
				if(isset($results['status']) && $results['status'] == 200){
					return redirect()->route('customers.create.hauling',$id)->with('success',$results['message']);
				}
				else{
					return redirect()->back()
					->withErrors($results['message'])
					->withInput();
				}
			}

		}
		else{
			return redirect()->back()->with('error','Details not found');
		}
	}

	//document
	public function document(Request $request, $id) {

		$this->_data["id"] = $id;
		$this->_data["navlink_isactive"] = true;
		return view('admin.customer.create.tab7.document', $this->_data);
	}

	//invoices
	public function invoices(Request $request, $id) {

		$this->initIndex();
		$srch_params = $request->all();
		$this->_data['breadcrumb']['#']      = 'Locations';
		$branch = app('App\Models\CompanyBranch')->getListing(['company_id'=>$id,'with'=>['addressdata']]);
		$branches = [];
		foreach($branch as $val){
			$branches[$val->uniq_id] = isset($val->addressdata->addressline1)?$val->addressdata->addressline1:'NA';
		}
		$this->_data['branches'] = $branches;
		if(!isset($srch_params['begin_date']) || (isset($srch_params['begin_date']) && empty($srch_params['begin_date']))){
			$srch_params['begin_date'] = date('Y-m').'-01';
		}
		if(!isset($srch_params['end_date']) || (isset($srch_params['end_date']) && empty($srch_params['end_date']))){
			$srch_params['end_date'] = date('Y-m-t');
		}
		$this->_data["srch_params"] = $srch_params;
		// if id = 0, company
		$this->_data["id"] = $id;
		$company = Company::where('id', '=', $id)->first();
		if($company){
			$this->_data['company'] = $company;
			$this->_data["navlink_isactive"] = true;
			$data = [];
			$accountdetails = [];
			$api_key = \Config::get('settings.RECURLY_KEY');

			$client = new \Recurly\Client($api_key);
			$options = [
				'params' => [
					//'limit' => 1,
					'begin_time' => $srch_params['begin_date'],
					'end_time' => $srch_params['end_date']
				]
			];
			$accounts = $client->listAccounts($options);
			foreach($accounts as $account) {
				//echo 'Account code: ' . $account->getCode() . PHP_EOL;

				$accountdetails[$account->getCode()] = $client->getAccount($account->getId());
				$invoices = $client->listAccountInvoices($account->getId());
				foreach($invoices as $invoice) {
					//$data[$account->getId()] = $invoice;

					$data[$account->getId()]['code'] = $account->getCode();
					$data[$account->getId()]['id'] = $invoice->getId();
					$data[$account->getId()]['company'] = $invoice->getAddress()->getCompany();
					$data[$account->getId()]['total'] = $invoice->getTotal();
					$data[$account->getId()]['state'] = $invoice->getState();
					$data[$account->getId()]['created_at'] = $invoice->getCreatedAt();
					foreach($invoice->getLineItems() as $line){
						$data[$account->getId()]['line_items'][] = $line->getDescription();
					}
				}
			}
			$this->_data['invoices'] = $data;
			return view('admin.customer.create.tab8.invoices', $this->_data);
		}
		else{
			return redirect()->route('customers.index')->with('error','Details not found');
		}
	}

	public function getAjaxContactPersonTemplate(Request $request){
		$input = $request->all();
		try{
            $validations = [
                'last_row'   => 'required|integer',
				'id'  => 'required|integer',
            ];
            $messages = [
                'last_row.required' => 'Sorry! some data is missing ',
				'id.required' => 'Sorry! some data is missing '
            ];
            $validator = \Validator::make($request->all(), $validations,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(),200);
            }
            else{
				$not_id = isset($input['selected_id'])?explode(',',$input['selected_id']):[];
                $view = view("admin.customer.create.tab1.corporate-contacts",['key'=>$input['last_row'],'id'=>$input['id'],'not_id'=>$not_id])->render();
                return Response::json(['success'=>true,'msg'=>'List generate success fully','html'=>$view]);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }

	}

	public function getAjaxContactPersonDetails(Request $request){
		$input = $request->all();
		try{
            $validations = [
                'user_id'   => 'required|integer'
            ];
            $messages = [
                'user_id.required' => 'Sorry! some data is missing '
            ];
            $validator = \Validator::make($request->all(), $validations,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(),200);
            }
            else{
                $data = \App\Models\User::select('id','email','phone')->whereNull('deleted_at')->where(['id' => $input['user_id']])->first();
				if($data){
                	return Response::json(['success'=>true,'msg'=>'List generate success fully','data'=>$data]);
				}
				else{
					throw new Exception('Details not found',200);
				}
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }
	}

	public function getAjaxBranchList(Request $request){
		$input = $request->all();
		try{
            $validations = [
                'company_id'   => 'required|integer'
            ];
            $messages = [
                'company_id.required' => 'Sorry! some data is missing '
            ];
            $validator = \Validator::make($request->all(), $validations,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(),200);
            }
            else{
				$data = \App\Helpers\Helper::companyBranches($input['company_id']);
                return Response::json(['success'=>true,'msg'=>'List generate success fully','data'=>$data]);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }
	}

    public function assign_password($id)
    {
        $user  = User::where('id', $id)->first();

        $user->remember_token = Helper::randomString(25);
        $url = \Config::get('services.frontend_url') ? \Config::get('services.frontend_url') : 'https://onsite-customer.glohtesting.com/';
        if ($user->save()) {
            $mailData = [
                'first_name'      => $user->first_name,
                'activation_link' => $url . 'reset-password/' . $user->remember_token,
            ];
            $fullName = $user->first_name . ' ' . $user->last_name;
            \App\Models\SiteTemplate::sendMail($user->email, $fullName, $mailData, 'forgot_password');
        }
        return redirect()->back()->with('success','Password Set mail send successfully');
    }

}
