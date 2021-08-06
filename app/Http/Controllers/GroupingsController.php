<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LocationGroup;
use App\Models\GroupLocations;
use App\Models\CompanyBranch;
use App\Helpers\Helper;
use Aws;
// date_default_timezone_set("Asia/Kolkata");

class GroupingsController extends Controller
{
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);       
        $this->_module      = 'Location Grouping';
        $this->_routePrefix = 'groupings';
        $this->_model       = new LocationGroup();
        $this->_offset = 15;
        $this->_successStatus = 200;
        $this->_message = "Success!!";
    }
    public function index(Request $request)
    {
        $this->initIndex();
        $srch_params                        = $request->all();
        $srch_params['with'] = ['customer_details','normalization_details'];
        $srch_params['withCount'] = ['grouplocationmap'];
        $this->_data['data']                = $this->_model->getListing($srch_params, $this->_offset)->appends($request->input());
        $this->_data['orderBy']             = $this->_model->orderBy;
        $this->_data['pageHeading']             = $this->_module;
        $this->_data['filters']             = $this->_model->getFilters();
        $this->_data['search']              = isset($srch_params['name'])?$srch_params['name']:null;
        return view('admin.' . $this->_routePrefix . '.index', $this->_data)
            ->with('i', ($request->input('page', 1) - 1) * $this->_offset);
    }


    public function add_locations(Request $request, $group_id = '') {
        $offset = 5;
        $srch_params                        = $request->all();
        $this->_data['group_det']           =  $this->_model->getListing(['id'=>$group_id,'with'=>['customer_details','normalization_details'],'withCount'=>['grouplocationmap']]);
        $this->_data['breadcrumb']      =  array(
            route($this->_routePrefix . '.index') => 'Group List',
            '#' => 'Add/Edit Locations'
        );
        $srch_params['group_id']         = $group_id;
        $query_params = $srch_params;
        $query_params['company_id'] = $this->_data['group_det']->customer_details->id;
        $query_params['with'] = ['addressdata','branchspecialty.speciality_details','group'];
        $this->_data['pageHeading']         =  "Add/Remove locations to group";
        unset($srch_params['orderBy']);
        $this->_data['searchParams']         =  $srch_params;
        
        $this->_data['routePrefix']         =  $this->_routePrefix;
        $companyBrancObject = new CompanyBranch();
        
        $this->_data["companybranch_list"] = $companyBrancObject->getListing($query_params, $offset)->appends($request->input());
        $this->_data['orderBy']             = $companyBrancObject->orderBy;
        return view('admin.' . $this->_routePrefix . '.add_locations', $this->_data)->with('i', ($request->input('page', 1) - 1) * $this->_offset);
    }

    public function save_locations(Request $request, $group_id = '') {
        $input = $request->all();
        if ($input['type'] == 'remove') {
            $res=GroupLocations::where('location_id',$input['location_id'])->delete();
        } else {
            GroupLocations::where('location_id',$input['location_id'])->delete();
            $res = GroupLocations::create(['location_id'=>$input['location_id'], 'group_id' =>$group_id]);
        }
        return response()->json(['success'=>true,'msg'=>$res]);
    }

    public function save_normalization(Request $request) {
        $input = $request->all();
        $location_id = $input['location_id'];
        $normalization_fact = NULL;
        $companyBrancObject = new CompanyBranch();
        $location = $companyBrancObject->getListing(['id'=>$location_id]);
        if ($input['normalization_fact']) {
            $normalization_fact = $input['normalization_fact'];   
        }
        
        $location->normalization_fact = $normalization_fact;
        $res = $location->save();
        return response()->json(['success'=>true,'msg'=>$res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $this->__formUiGeneration($request);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->_model->getListing(['id' => $id]);
        return view('admin.' . $this->_routePrefix . '.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        return $this->__formUiGeneration($request, $id);
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
    public function destroy($id)
    {
        $group_det =  $this->_model->getListing(['id'=>$id,'withCount'=>['grouplocationmap']]);
        if (count($group_det->grouplocationmap) > 0) {
            return redirect()
                ->route($this->_routePrefix . '.index')
                ->with('error', "This group has locations attached to it. Please remove those before deleting.");
        }
        $response = $this->_model->remove($id);

        if($response['status'] == 200) {
            return redirect()
                ->route($this->_routePrefix . '.index')
                ->with('success', $response['message']);
        } else {
            return redirect()
                    ->route($this->_routePrefix . '.index')
                    ->with('error', $response['message']);
        }
    }

    /**
     * ui parameters for form add and edit
     *
     * @param  string $id [description]
     * @return [type]     [description]
     */
    protected function __formUiGeneration(Request $request, $id = '')
    {
        $response = $this->initUIGeneration($id);
        if($response) {
            return $response;
        }
        $pageHeading     = $this->_module;
        extract($this->_data);
        $customerModel   = new \App\Models\Company;
        $companies      = $customerModel->getListing()->pluck('company_name', 'id');
        $normalisationModel   = new \App\Models\NormalisationType;
        $normalizations      = $normalisationModel->getListing()->pluck('name', 'id');
        $status = \App\Helpers\Helper::makeSimpleArray($this->_model->statuses, 'id,name');
        $form = [
            'route'      => $this->_routePrefix . ($id ? '.update' : '.store'),
            'back_route' => route($this->_routePrefix . '.index'),
            'fields'     => [
                'company_id'      => [
                    'row_width'  => 'col-md-4',
                    'type'          => 'select',
                    'options'       => $companies,
                    'label'         => 'Customer',
                    'attributes'    => [
                        'max'       => 255,
                        'required'  => true
                    ]
                ],
                'name'      => [
                    'row_width'  => 'col-md-4',
                    'type'          => 'text',
                    'label'         => 'Name',
                    'attributes'    => [
                        'max'       => 255,
                        'required'  => true
                    ]
                ],
                'normalization_id'      => [
                    'row_width'  => 'col-md-4',
                    'type'          => 'select',
                    'options'       => $normalizations,
                    'label'         => 'Normalization Type',
                    'attributes'    => [
                        'max'       => 255,
                        'required'  => true
                    ]
                ],
                'colorcode'      => [
                    'row_width'  => 'col-md-4',
                    'type'          => 'color',
                    'label'         => 'Color Code',
                    'attributes'    => [
                        'max'       => 255,
                        'required'  => true
                    ]
                ],
                'status'            => [
                    'row_width'  => 'col-md-4',
                    'type'          => 'radio',
                    'label'         => 'Status',
                    'options'       => $status,
                    'value'         => isset($data->status) ? $data->status : 1,
                ],
            ],
        ];

        return view('admin.components.admin-form', compact('data', 'id', 'form', 'breadcrumb', 'module','pageHeading'));
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
        $validationRules = [
            'name'          => 'required|max:255',
            'status'          => 'required'
        ];

        $this->validate($request, $validationRules);

        $input      = $request->all();
        $company_id = $input['company_id'];
        $wordCount = LocationGroup::where('company_id', '=', $company_id )->count();
        if (!$id && $wordCount >= 5) {
            return redirect()
                ->route($this->_routePrefix . '.index')
                ->with('error', "Cannot create more than 5 groups for a Customer"); 
        }
        $response   = $this->_model->store($input, $id, $request);
        
        if($response['status'] == 200){
            return redirect()
                ->route($this->_routePrefix . '.index')
                ->with('success',  $response['message']);
        } else {
            return redirect()
                    ->route($this->_routePrefix . '.index')
                    ->with('error', $response['message']);
        }
    }

}