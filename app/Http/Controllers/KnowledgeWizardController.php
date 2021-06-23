<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KnowledgeWizard;
use Response;

class KnowledgeWizardController extends Controller
{
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);
        
        $this->_module      = 'Knowledge Wizard';
        $this->_routePrefix = 'knowledgewizard';
        $this->_model       = new KnowledgeWizard();
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
        // if($category) {
        //     $srch_params['category']  = $category;
        // }

        // if($sub_category) {
        //     $srch_params['sub_category']    = $sub_category;
        // }
        $this->_data['pageHeading'] = $this->_module;
        $this->_data['data']            = $this->_model->getListing($srch_params, $this->_offset);
        $this->_data['orderBy']         = $this->_model->orderBy;
        $this->_data['filters']         = $this->_model->getFilters();
        // $this->_data['category']         = $category;
        // $this->_data['sub_category']           = $sub_category;
        //dd($this->_data['data'][0] );

        $this->_data['breadcrumb'] = [
            // route('location.countries.index')                                   => "Countries",
            // route('location.countries.sub_categorys.index', ['category' => $category])   => "sub_categorys",
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
        
        extract($this->_data);
        $this->_data['breadcrumb'] = [
            route('knowledgewizard.index', $this->_data['routeParams'])   => $this->_module,
            '#'                                                                 => $moduleName,
        ];

        // $category        = $request->old('category_id') ? $request->old('category_id') : $category;
        // $sub_category          = $request->old('sub_category_id') ? $request->old('sub_category_id') : $sub_category;

        $categoryModel   = new \App\Models\KnowledgeCategory;
        $categories      = $categoryModel->getListing(['kw_category_id' => 0])->pluck('title', 'id');

        $sub_categorys = [];
        if($data->category_id) {
            $sub_categoryModel = new \App\Models\KnowledgeCategory;
            $sub_categorys     = $sub_categoryModel->getListing(['kw_category_id' =>$data->category_id])->pluck('title', 'id');
        }
        $status = \App\Helpers\Helper::makeSimpleArray($this->_model->statuses, 'id,name');
        $this->_data['pageHeading'] = $moduleName;
        $this->_data['form'] = [
            'route'         => $this->_routePrefix . ($id ? '.update' : '.store'),
            'back_route'    => route($this->_routePrefix . '.index', $this->_data['routeParams']),
            'route_param'   => $this->_data['routeParams'],
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
                // 'sub_category_id'      => [
                //     'row_width'  => 'col-md-4',
                //     'type'          => 'select',
                //     'label'         => 'Sub Category',
                //     'options'       => $sub_categorys,
                //     'value'         => isset($data->sub_category_id) ? $data->sub_category_id : '',
                //     'attributes'    => [
                //         'required'  => true,
                //         'class' => 'form-control select2',
                //         'placeholder' => 'Choose ...'
                //     ]
                // ],                
                'title'      => [
                    'row_width'  => 'col-md-5',
                    'type'          => 'text',
                    'label'         => 'Title',
                    'help'          => 'Maximum 250 characters',
                    'attributes'    => [
                        'required'  => true
                    ]
                ],
                'rank'             => [
                    'type'       => 'number',
                    'row_width'  => 'col-md-3',
                    'label'      => 'Rank',
                    'value'      => $data->rank ? $data->rank : '',
                    'attributes'    => [
                        'required'  => true
                    ]
                ],
                'bk_img' => [
                    'row_width'     => 'col-md-6',
                    'type'          => 'file',
                    'label'         => 'Background Image',
                    'help'          => 'For better viewing upload image of 320 x 200 size',
                    'value'         =>  isset($data->back_img) ? $data->back_img : '',
                ],
                'benifits_header' => [
                    'row_width'     => 'col-md-6',
                    'type'          => 'text',
                    'label'         => 'Highlights Title'
                ],
                'benifits' => [
                    'row_width'     => 'col-md-6',
                    'type'          => 'textarea',
                    'help'          => 'Add multiple seperated by (;)',
                    'label'         => 'Highlights'
                ],
                'short_desc'        => [
                    'row_width'  => 'col-md-6',
                    'type'          => 'textarea',
                    'help'          => 'Max 250 characters',
                    'label'         => 'Short Desc',
                    'attributes'    => [
                        'required'  => true
                    ]
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
            'title'             => 'required|max:250',
            'short_desc'             => 'required|max:250',
            'benifits'             => 'required',
            'benifits_header'             => 'required|max:250',
            'rank'             => 'required|numeric',
        ];

        $this->validate($request, $validationRules);

        $input      = $request->all();
        $response   = $this->_model->store($input, $id, $request);
        $this->__routeParams();
        if($response['status'] == 200){
           
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
