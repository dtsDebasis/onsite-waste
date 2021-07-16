<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KnowledgeContent;
use Response;

class KnowledgeContentController extends Controller
{
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);

        $this->_module      = 'Knowledge Content';
        $this->_routePrefix = 'knowledgecontent';
        $this->_model       = new KnowledgeContent();
        $this->_offset = 10;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->initIndex([], false);
        $this->__routeParams();
        $srch_params                    = $request->all();
        $this->_data['pageHeading'] = $this->_module;
        $this->_data['data']            = $this->_model->getListing($srch_params, $this->_offset)->appends($request->input());
        $this->_data['search']              = isset($srch_params['title'])?$srch_params['title']:null;
        $this->_data['orderBy']         = $this->_model->orderBy;
        $this->_data['filters']         = $this->_model->getFilters();

        $this->_data['breadcrumb'] = [
            '#'                                                                 => $this->_module,
        ];
        return view('admin.' . $this->_routePrefix . '.index', $this->_data)
            ->with('i', ($request->input('page', 1) - 1) * $this->_offset);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $category = 0, $sub_category = 0)
    {
        return $this->__formUiGeneration($request, $category, $sub_category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $category = 0, $sub_category = 0)
    {
        return $this->__formPost($request, $category, $sub_category);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
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
        $response = $this->_model->remove($id);
        $this->__routeParams();

        if($response['status'] == 200) {
            \App\Models\KnowledgeContentTag::where([
                'knowledge_content_id' => $id
            ])->delete();
            return redirect()
                ->route($this->_routePrefix . '.index', $this->_data['routeParams'])
                ->with('success', $response['message']);
        } else {
            return redirect()
                    ->route($this->_routePrefix . '.index', $this->_data['routeParams'])
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
        $this->__routeParams();
        $response = $this->initUIGeneration($id, true, $this->_data['routeParams']);
        if($response) {
            return $response;
        }
        $tags = null;
        $existingSpeciality = null;
        $existingState = null;
        $existingLocation = null;
        if($id){
            $tags = implode(",",\App\Models\KnowledgeContentTag::where('knowledge_content_id',$id)->pluck('tag')->toArray());
            $existingSpeciality = \App\Models\KnowledgeContentSpeciality::where('knowledge_content_id',$id)->pluck('speciality_id')->toArray();
            $existingState = \App\Models\KnowledgeContentState::where('knowledge_content_id',$id)->pluck('state_id')->toArray();
            //$existingLocation = \App\Models\KnowledgeContentLocation::where('knowledge_content_id',$id)->pluck('branch_id')->toArray();

        }
        extract($this->_data);
        $this->_data['breadcrumb'] = [
            route('knowledgecontent.index', $this->_data['routeParams'])   => $this->_module,
            '#'                                                                 => $moduleName,
        ];

        $categoryModel   = new \App\Models\KnowledgeCategory;
        $categories      = $categoryModel->getListing(['kw_category_id' => 0])->pluck('title', 'id');

        $stateModel   = new \App\Models\LocationState;
        $states      = $stateModel->getListing(['status' => 1])->pluck('state_name', 'id');

        $locationsModel   = new \App\Models\CompanyBranch;
        //$locations      = $locationsModel->getListing(['status' => 1])->pluck('name', 'id');
        $locations = ['Single Location' => 'Single Location','Multiple Locations' => 'Multiple Locations'];

        $specialityModel   = new \App\Models\Speciality;
        $specialities      = $specialityModel->getListing(['status' => 1])->pluck('name', 'id');


        $status = \App\Helpers\Helper::makeSimpleArray($this->_model->statuses, 'id,name');
        $types = $this->_model->types;
        //$waste_types = $this->_model->waste_types;
        $service_types = ['TE-Only' => 'TE-Only', 'Hauling Only' => 'Hauling Only', 'TE and Hauling' => 'TE and Hauling'];
        $this->_data['pageHeading'] = $moduleName;
        $this->_data['form'] = [
            'route'         => $this->_routePrefix . ($id ? '.update' : '.store'),
            'back_route'    => route($this->_routePrefix . '.index', $this->_data['routeParams']),
            'route_param'   => $this->_data['routeParams'],
            //'include_scripts' => '<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuD_MmkCBeWz1oLMHGQB16cUJOxPG0soQ&libraries=places&callback=initAutocomplete"></script><script src="'. asset('administrator/admin-form-plugins/address_autocomplete.js'). '"></script>',
            'fields'     => [
                'category_id'      => [
                    'row_width'  => 'col-md-4',
                    'type'          => 'select',
                    'label'         => 'category',
                    'options'       => $categories,
                    'value'         => isset($data->category_id) ? $data->category_id : '',
                    'attributes'    => [
                        'required'  => true,
                        'class' => 'form-control select2',
                        'placeholder' => 'Choose ...'
                    ]
                ],
                'title'      => [
                    'row_width'  => 'col-md-4',
                    'type'          => 'text',
                    'label'         => 'Title',
                    'help'          => 'Maximum 250 characters',
                    'attributes'    => [
                        'required'  => true
                    ]
                ],
                'rank'             => [
                    'type'       => 'number',
                    'row_width'  => 'col-md-4',
                    'label'      => 'Rank',
                    'value'      => $data->rank ? $data->rank : '',
                    'attributes'    => [
                        'required'  => true,
                        'min'  => 0
                    ]
                ],
                'knowledge_states[]' => [
                    'row_width'     => 'col-md-6',
                    'type'          => 'select',
                    'options'       => $states,
                    'help'          => 'Leave blank for All',
                    'attributes'    => [
                        'class' => 'form-control select2',
                        'multiple' =>  true,
                    ],
                    'value'         => $existingState,
                    'label'         => 'States'
                ],
                'knowledge_specialities[]' => [
                    'row_width'     => 'col-md-6',
                    'type'          => 'select',
                    'options'       => $specialities,
                    'help'          => 'Leave blank for All',
                    'attributes'    => [
                        'class' => 'form-control select2',
                        'multiple' =>  true,
                    ],
                    'value'         => $existingSpeciality,
                    'label'         => 'Specialities'
                ],

                //knowledge_locations
                'location'      => [
                    'row_width'     => 'col-md-6',
                    'type'          => 'select',
                    'options'       => $locations,
                    'help'          => 'Leave blank for All',
                    'attributes'    => [
                        'class' => 'form-control select2'
                        //'multiple' =>  true,
                    ],
                    'value'         => ($data->location) ? $data->location:null,
                    'label'         => 'Location'
                ],
                'knowledge_tags'      => [
                    'row_width'  => 'col-md-6',
                    'type'          => 'text',
                    'label'         => 'Keywords',
                    'value'         => $tags,
                    'attributes'    => [
                        'data-role' => 'tagsinput',
                        'class' => 'form-control col-md-12',
                        'placeholder' => 'Enter tags'
                    ]
                ],
                'dsp_img' => [
                    'row_width'     => 'col-md-6',
                    'type'          => 'file',
                    'label'         => 'Display Image',
                    'help'          => 'For better viewing upload image of 2000 x 500 size',
                    'value'         =>  isset ($data->dispimage) ? $data->dispimage : '',
                ],
                'short_desc'        => [
                    'row_width'  => 'col-md-6',
                    'type'          => 'text',
                    'help'          => 'Maximum 250 characters',
                    'label'         => 'Short Desc',
                    'attributes'    => [
                        'required'  => true
                    ]
                ],
                'type' => [
                    'row_width'     => 'col-md-3',
                    'type'          => 'select',
                    'options'       => $types,
                    'attributes'    => [
                        'class' => 'form-control select2'
                    ],
                    'value'         => isset($data->type) ? $data->type : '',
                    'label'         => 'Type'
                ],
                // 'waste_type' => [
                //     'row_width'     => 'col-md-3',
                //     'type'          => 'select',
                //     'options'       => $waste_types,
                //     'attributes'    => [
                //         'class' => 'form-control select2',
                //     ],
                //     'value'         => isset($data->waste_type) ? $data->waste_type : '',
                //     'label'         => 'Waste Type'
                // ],
                'service_type' => [
                    'row_width'     => 'col-md-3',
                    'type'          => 'select',
                    'options'       => $service_types,
                    'attributes'    => [
                        'class' => 'form-control select2',
                    ],
                    'value'         => isset($data->service_type) ? $data->service_type : null,
                    'label'         => 'Service Type'
                ],
                'details'  => [
                    'row_width'     => 'col-md-12',
                    'type'          => 'editor',
                    'label'         => 'Long Description'
                ],
                'status'            => [
                    'row_width'     => 'col-md-4',
                    'type'          => 'radio',
                    'label'         => 'Status',
                    'options'       => $status,
                    'value'         => isset($data->status) ? $data->status : 1,
                ],
            ],
        ];

        // dd($this->_data['form']);

        return view('admin.components.admin-form', $this->_data);
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
            'category_id'            => 'required',
            'title'             => 'required|max:255',
            'short_desc'             => 'required|max:255',
            'rank'             => 'required|numeric',
        ];

        $this->validate($request, $validationRules);

        $input      = $request->all();
        $response   = $this->_model->store($input, $id, $request);
        $this->__routeParams();
        if($response['status'] == 200){
            \App\Models\KnowledgeContentTag::where('knowledge_content_id',$response['data']->id)->delete();
            if(isset($input['knowledge_tags']) && $input['knowledge_tags']){
                $knowledge_tags = explode(',',$input['knowledge_tags']);
                foreach($knowledge_tags as $kval){
                    \App\Models\KnowledgeContentTag::create([
                        'knowledge_content_id' => $response['data']->id,
                        'tag' => $kval
                    ]);
                }
            }

            \App\Models\KnowledgeContentState::where('knowledge_content_id',$response['data']->id)->delete();
            if(isset($input['knowledge_states']) && count($input['knowledge_states'])){
                foreach($input['knowledge_states'] as $kval){
                    \App\Models\KnowledgeContentState::create([
                        'knowledge_content_id' => $response['data']->id,
                        'state_id' => $kval
                    ]);
                }
            }
            \App\Models\KnowledgeContentSpeciality::where('knowledge_content_id',$response['data']->id)->delete();
            if(isset($input['knowledge_specialities']) && count($input['knowledge_specialities'])){
                foreach($input['knowledge_specialities'] as $kval){
                    \App\Models\KnowledgeContentSpeciality::create([
                        'knowledge_content_id' => $response['data']->id,
                        'speciality_id' => $kval
                    ]);
                }
            }
            // \App\Models\KnowledgeContentLocation::where('knowledge_content_id',$response['data']->id)->delete();
            // if(isset($input['knowledge_locations']) && count($input['knowledge_locations'])){
            //     foreach($input['knowledge_locations'] as $kval){
            //         \App\Models\KnowledgeContentLocation::create([
            //             'knowledge_content_id' => $response['data']->id,
            //             'branch_id' => $kval
            //         ]);
            //     }
            // }
            return redirect()
                ->route($this->_routePrefix . '.index', $this->_data['routeParams'])
                ->with('success',  $response['message']);
        } else {
            return redirect()
                    ->route($this->_routePrefix . '.index', $this->_data['routeParams'])
                    ->with('error', $response['message']);
        }
    }

    protected function __routeParams($category = 0, $sub_category = 0) {
        $this->_data['routeParams'] = [
        ];
    }

    public function getChildCategory(Request $request,$id){
        try{
            $data = \App\Models\KnowledgeCategory::where(['kw_category_id' => $id,'status' => 1])->pluck('title','id')->toArray();

            return Response::json(['success'=>true,'msg'=>'List generate success fully','data'=>$data]);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], $e->getCode());
        }
    }
}
