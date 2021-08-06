<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NormalisationType;
use App\Helpers\Helper;
use Aws;
// date_default_timezone_set("Asia/Kolkata");

class NormalisationController extends Controller
{
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);       
        $this->_module      = 'Normalization Type';
        $this->_routePrefix = 'master.normalizationtype';
        $this->_model       = new NormalisationType();
        $this->_offset = 10;
        $this->_successStatus = 200;
        $this->_message = "Success!!";
    }
    public function index(Request $request)
    {
        $this->initIndex();
        $srch_params                        = $request->all();
        $this->_data['data']                = $this->_model->getListing($srch_params, $this->_offset)->appends($request->input());
        $this->_data['orderBy']             = $this->_model->orderBy;
        $this->_data['pageHeading']             = $this->_module;
        $this->_data['filters']             = $this->_model->getFilters();
        $this->_data['search']              = isset($srch_params['name'])?$srch_params['name']:null;
        return view('admin.' . $this->_routePrefix . '.index', $this->_data)
            ->with('i', ($request->input('page', 1) - 1) * $this->_offset);
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
        $status = \App\Helpers\Helper::makeSimpleArray($this->_model->statuses, 'id,name');
        $form = [
            'route'      => $this->_routePrefix . ($id ? '.update' : '.store'),
            'back_route' => route($this->_routePrefix . '.index'),
            'fields'     => [
                'name'      => [
                    'row_width'  => 'col-md-6',
                    'type'          => 'text',
                    'label'         => 'Name',
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