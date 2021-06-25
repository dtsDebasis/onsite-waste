<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\CompanyHauling;
use App\Exports\CompletedHaulingExport;
use Response;
use Excel;
use Exception;

class HaulingController extends Controller
{
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);

        $this->title      = 'Pickup Management';
        $this->_module      = 'Pickup Management';
        $this->_routePrefix = 'pickups';
        $this->_model       = new CompanyHauling;
    }

    public static function validationRules($id=0){
        return $rules = [
            'date'    => 'required',
            'company_id'     => 'required|integer',
            'branch_id'   => 'required|integer'
        ];
    }
    protected function __routeParams($category = 0, $sub_category = 0) {
        $this->_data['routeParams'] = [
        ];
    }

    public function index(Request $request)
    {
        $this->initIndex([], false);
        $this->__routeParams();
        $srch_params                    = $request->all();
        $this->_data['srch_params'] = $srch_params;
        if(!isset($srch_params['tab']) || (isset($srch_params['tab']) && empty($srch_params['tab']))) {
            $srch_params['tab'] = 'active';
        }
        if($srch_params['tab'] == 'active'){
            if(!isset($srch_params['status']) || (isset($srch_params['status']) && ($srch_params['status'] == ''))){
                $srch_params['status'] = [0,1,2,4,5];
            }
        }
        else{
            $srch_params['status'] = [3];
        }
        $srch_params['with'] = ['branch_details.addressdata','package_details'];
        $this->_data['pageHeading'] = $this->title;

        $this->_data['data']            = $this->_model->getListing($srch_params, $this->_offset);

        $this->_data['orderBy']         = $this->_model->orderBy;
        $this->_data['filters']         = null;

        $this->_data['breadcrumb'] = [
            '#'        => $this->title,
        ];
        return view('admin.' . $this->_routePrefix . '.index', $this->_data)
            ->with('i', ($request->input('page', 1) - 1) * $this->_offset);
    }

    public function generateExcel(Request $request){
        $srch_params = $request->toArray();
        $srch_params['status'] = [3];
        $srch_params['with'] = ['branch_details.addressdata','package_details'];
        if(count($srch_params)>0){
            return Excel::download(new CompletedHaulingExport($srch_params), 'compeleted-pickup.xlsx');
        }
        else{
            return redirect()->back()->with('error','Search data not found');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id = 0)
    {
        return $this->__formUiGeneration($request,$id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->__formPost($request);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        return $this->__formUiGeneration($request,$id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->__formPost($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($kw_category_id = 0, $id = null)
    {
        $id = $id??$kw_category_id;
        $data = $this->_model->getListing(['id'=> $id]);
        if (!$data) {
            return redirect()->back()->with('error','Not a valid data');
        }
        $res = $data->delete();
        if($res) {
            return redirect()
                ->back()
                ->with('success', 'Record deleted successfully');
        } else {
            return redirect()
                    ->back()
                    ->with('error', 'Record not delete');
        }
    }

    /**
     * ui parameters for form add and edit
     *
     * @param  string $id [description]
     * @return [type]     [description]
     */
    protected function __formUiGeneration($request, $id = '')
    {
        $response = $this->initUIGeneration($id);
        if ($response) {
            return $response;
        }
        $input = $request->all();
        $this->_data['branch_details'] = null;
        if(isset($input['branch_id']) && $input['branch_id']){
            $this->_data['branch_details'] = app('App\Models\CompanyBranch')->getListing(['id'=>$input['branch_id']]);
        }
        return view('admin.' . $this->_routePrefix . '.add_edit', $this->_data);
    }

    /**
     * Form post action
     *
     * @param  Request $request [description]
     * @param  string  $id      [description]
     * @return [type]           [description]
     */
    protected function __formPost(Request $request, $id = '')
    {
        $input = $request->all();
        $validationRules = self::validationRules($id);
        $validator = \Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        else{
            $input['is_additional'] = 1;
            $packageDetails = \App\Models\Package::where(['company_id' => $input['company_id'],'branch_id' => $input['branch_id'],'is_active' => 1, 'deleted_at' => NULL])->first();
            $input['package_id'] = ($packageDetails) ? $packageDetails->id:null;
            $results = $this->storeUpdate($input,$id);
            if(isset($results['status']) && $results['status'] == 200){
                return redirect()->route('pickups.index')->with('success',$results['message']);
            }
            else{
                return redirect()->back()
                ->withErrors($results['message'])
                ->withInput();
            }
        }
    }

    public function updateStatus(Request $request){
        $input = $request->all();
        $validationRules = [
            'id' => 'required|integer',
        ];
        $validator = \Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        else{
            $id = $input['id'];
            unset($input['id']);
            $results = $this->storeUpdate($input,$id);
            if(isset($results['status']) && $results['status'] == 200){
                return redirect()->back()->with('success',$results['message']);
            }
            else{
                return redirect()->back()
                ->withErrors($results['message'])
                ->withInput();
            }
        }
    }

    public function storeUpdate($input,$id=0){
        $data = null;
        $old_data = null;
        $msg = 'Created successfully';
        if ($id) {
            $old_data = $data;
            $data   = $this->_model->getListing(['id'=> $id]);
            if (!$data) {
                return \App\Helpers\Helper::resp('Not a valid data', 400);
            }
            $data->update($input);
            $msg = 'Changes has been saved successfully';
        } else {
            $input['status'] = 1;
            $data   = $this->_model->create($input);
        }
        if($data){
            if($data->status != 0){
                if(!isset($old_data->status) || (isset($old_data->status) && $old_data->status !=0)){
                    $this->sendMailToBranch($data);
                }
            }
            return \App\Helpers\Helper::resp($msg, 200, $data);

        }
        else{
            return \App\Helpers\Helper::resp('Something went to wrong', 400);
        }
    }

    public function sendMailToBranch($data){
        if(isset($data->branch_id)){
            $status_arr = ['0'=>'Not Confirm','1'=> 'Confirmed','2' => 'Pickup Done', '4' => 'Declined','3' => 'Completed'];
            $branchDetails = app('App\Models\BranchUser')->getListing(['companybranch_id'=>$data->branch_id,'with'=>['user']]);
            if(count($branchDetails)){
                $maildata = [
                    'status' => $status_arr[$data->status],
                    'number_of_boxes' => $data->number_of_boxes,
                    'driver_name' => $data->driver_name,
                    'description' => $data->description
                ];
                foreach($branchDetails as $val){
                    if($val->user){
                        $maildata['name'] = $val->user->full_name;
                        $fullName = $val->user->full_name;
				        \App\Models\SiteTemplate::sendMail($val->user->email, $fullName, $maildata, 'hauling_mail');
                    }
                }
                return true;
            }
            return false;
        }
    }

    public function manifestDetails(Request $request){
        try{
            $input = $request->all();
            $validationRules = [
                'branch_id' => 'required|integer',
                'hauling_id' => 'required|integer'
            ];
            $validator = \Validator::make($request->all(), $validationRules);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(),200);
            }
            else{
                $haulingDetails = app('App\Models\CompanyHauling')->getListing(['id'=>$input['hauling_id'],'with' => ['branch_details.addressdata']]);
                $manifest = app('App\Models\Manifest')->getListing(['hauling_id'=>$input['hauling_id'],'single_record' => true ]);

                $view = view("admin.pickups.manifest",['haulingDetails'=>$haulingDetails,'manifest'=>$manifest])->render();
                return Response::json(['success'=>true,'msg'=>'List generate success fully','html'=>$view]);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }
    }

    public function manifestUpdateDetails(Request $request){
        try{
            $input = $request->all();
            $siteSettings = \App\Helpers\Helper::SiteSettingDetails();
            $validationRules = [
                'hauling_id' => 'required|integer',
                'manifest_doc.*' =>'mimes:jpeg,png,jpg,doc,docx,pdf|max:' . (int) $siteSettings['file_size'] * 1024,
            ];
            $validator = \Validator::make($request->all(), $validationRules);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(),200);
            }
            else{
                $manifest = app('App\Models\Manifest')->getListing(['hauling_id'=>$input['hauling_id'],'single_record' => true ]);
                if($manifest){
                    $manifest->update($input);
                }
                else{
                    $input['uniq_id'] = \App\Helpers\Helper::generateMasterCode('\App\Models\Manifest','uniq_id');
                    $manifest = \App\Models\Manifest::create($input);
                }
                if($manifest){
                    app('App\Models\Manifest')->uploadManifestDoc($manifest,$request);
                    return Response::json(['success'=>true,'msg'=>'Updated successfully','data'=>$manifest]);
                }
                else{
                    throw new Exception('Something went to wrong',200);
                }
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }

    }
}
