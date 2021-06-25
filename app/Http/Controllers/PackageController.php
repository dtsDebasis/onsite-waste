<?php
namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Auth;
// use DB;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;


use App\Models\TransactionalPackage;
use App\Models\Package;


class PackageController extends Controller {
	public function __construct($parameters = array()) {
		parent::__construct($parameters);
		$this->title      = 'Product Management';
		$this->_module      = 'Product Management';
        $this->_routePrefix = 'packages';
	}

	public function index(Request $request){
		$this->initIndex();
		$this->_data['pageHeading'] = $this->title;
        $this->_data['breadcrumb'] = [
            '#'        => $this->title,
        ];
		$this->_data["transactionalpackages"] =  TransactionalPackage::where('company_id',0)->first();
		$this->_data["packages"] = Package::where(['is_active' => 1,'company_id' => 0,'branch_id' => 0 ])->get();
		return view('admin.' . $this->_routePrefix . '.index',$this->_data);
	}
	public function transactionaPackage(Request $request){
		if($request->isMethod('post') || $request->isMethod('patch')){
			$validator = Validator::make($request->all(), [
				'te_5000_rental_cost' => 'required',
				'te_5000_purchase_cost' => 'required',
				'containers_cost' => 'required',
				'shipping_cost' => 'required',
				'setup_initial_cost' => 'required',
				'setup_additional_cost' => 'required',
				'compliance_training_cost' => 'required',
				'quarterly_review_cost' => 'required'
			]);
			if ($validator->fails()) {
				return redirect()->back()
							->withErrors($validator)
							->withInput();
			}
			$input = $request->all();

			$transactionalpackage = null;
			if($input['id']){
				$transactionalpackage = TransactionalPackage::where('id', '=', $input['id'] )->first();
				if($transactionalpackage){
					$transactionalpackage->update($input);
				}
				else{
					return redirect()->back()
							->withErrors('Details not found')
							->withInput();
				}
			}
			else{
				$transactionalpackage = TransactionalPackage::create($input);
			}
			return redirect()->back()->with('success','Updated Successfully');
		}
		return redirect()->back()->with('error','Invalid method');
	}

	public function store(Request $request) {
		return $this->__formPost($request);
	}
	public function update(Request $request, $id)
    {
        return $this->__formPost($request, $id);
    }
	protected function __formPost(Request $request, $id = 0)
    {
        $input = $request->all();

		$validator = Validator::make($request->all(), [
			'name' => 'required|max:250',
			'monthly_rate' => 'required',
			'boxes_included' => 'required',
			'te_500' => 'required',
			'compliance' => 'required',
			'frequency_type' => 'required',
			'frequency_number' => 'required',
			'duration_type' => 'required',
			'duration_number' => 'required'
		]);
		if ($validator->fails()) {
			return redirect()->back()
						->withErrors($validator)
						->withInput();
		}

		$input = $request->all();
		$package = null;
		$msg = 'Created successfully';
		if($id){
			$package = Package::where('id', '=', $input['id'] )->first();
			if($package){
				$msg = 'Changes has been saved successfully';
				$package->update($input);
			}
			else{
				return redirect()->back()
						->withErrors('Details not found')
						->withInput();
			}
		}
		else{
			$package = Package::create($input);
		}
		return redirect()->route( $this->_routePrefix.'.index' )->with('success',$msg);

	}

	public function edit(Request $request, $id){
		$this->initIndex();
		$package =  Package::where('id', '=', $id )->first();
		if( !$package ){
			return redirect()->route('package.index')->with('error','Package Details not found');
		}
		$this->_data['package'] = $package;
		$this->_data['pageHeading'] = $this->title;
		$this->_data["transactionalpackages"] =  TransactionalPackage::first();
		$this->_data["packages"] =  Package::where('is_active', '=', '1' )->get();
		return view('admin.' . $this->_routePrefix . '.index',$this->_data);
	}
	public function destroy($id)
    {
		$package =  Package::where('id', '=', $id )->first();
		if($package){
			$package->delete();
			return redirect()->route( $this->_routePrefix.'.index')->with('success','Record deleted successfully');
		}
		else{
			return redirect()->back()->with('error','Package Details not found');
		}
	}

}
