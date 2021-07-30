<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\KnowledgeCategory;

class KnowledgeCategoryController extends Controller
{
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);

        $this->_module      = 'Knowledge Category';
        $this->_routePrefix = 'knowledgecategories';
        $this->_model       = new KnowledgeCategory;
        $this->_offset = 10;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $kw_category_id = 0)
    {
        $permission = \App\Models\Permission::checkModulePermissions();
        $srch_params                    = $request->all();
        $data       = $this->_model->getListing($srch_params, $this->_offset)->appends($request->input());
        $adminMenu  = $this->_model->getParentMenu($kw_category_id);
        $search              = isset($srch_params['title'])?$srch_params['title']:null;
        $breadcrumb[route($this->_routePrefix . '.index', 0)] = str_plural($this->_module);
        foreach ($adminMenu as $key => $value) {
            $breadcrumb[route($this->_routePrefix . '.index', $value['id'])] = $value['title'];
        }
        $pageHeading = $this->_module;
        $module         = "Manage "  . str_plural($this->_module);
        $routePrefix    = $this->_routePrefix;

        return view('admin.' . $this->_routePrefix . '.index', compact(
            'data',
            'breadcrumb',
            'module',
            'permission',
            'pageHeading',
            'routePrefix',
            'search',
            'kw_category_id'
        ))
            ->with('i', ($request->input('page', 1) - 1) * $this->_offset);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($kw_category_id = 0)
    {
        return $this->__formUiGeneration($kw_category_id);
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
    public function edit($kw_category_id = 0, $id)
    {
        return $this->__formUiGeneration($kw_category_id, $id);
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
    public function destroy($kw_category_id = 0, $id)
    {
        $data = KnowledgeCategory::find($id);

        if (!$data) {
            return \App\Helpers\Helper::resp('Not a valid data', 400);
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
    protected function __formUiGeneration($kw_category_id = 0, $id = '')
    {
        $response = $this->initUIGeneration($id);
        if ($response) {
            return $response;
        }

        extract($this->_data);

        $adminMenu = $this->_model->getParentMenu($kw_category_id);
        $this->_data['breadcrumb'] = [];
        $this->_data['breadcrumb'][route($this->_routePrefix . '.index', 0)] = str_plural($this->_module);
        foreach ($adminMenu as $key => $value) {
            $this->_data['breadcrumb'][route($this->_routePrefix . '.index', $value['id'])] = $value['title'];
        }

        $module     = str_plural($this->_module) . ' | ' . $moduleName;
        $pageHeading     = $moduleName;
        $this->_data['breadcrumb']['#'] = $moduleName;

        $form = [
            'route'             => $this->_routePrefix . ($id ? '.update' : '.store'),
            'back_route'        => route($this->_routePrefix . '.index', $kw_category_id), 
            'include_scripts'   => '<script src="' . asset('administrator/admin-form-plugins/form-controls.js') . '"></script>',
            'fields'     => [
                'kw_category_id' => [
                    'type'      => 'hidden',
                    'value'     => $kw_category_id
                ],
                'title'             => [
                    'row_width'  => 'col-md-4',
                    'type'       => 'text',
                    'label'      => 'Title',
                    'help'       => 'Maximum 255 characters',
                    'attributes' => ['required' => true],
                    'value'      => $data->title ? $data->title : ''
                ],
                'short_desc'             => [
                    'type'       => 'text',
                    'row_width'  => 'col-md-8',
                    'label'      => 'Short Description',
                    'help'       => 'Maximum 255 characters',
                    'value'      => $data->short_desc ? $data->short_desc : ''
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
                'status' => [
                    'row_width'  => 'col-md-4',
                    'type' => 'radio',
                    'label' => 'Status',
                    'options' => [1 => 'Enabled', 0 => 'Disabled'],
                    'value' => $data->status ? $data->status : 0
                ],
            ],
        ];

        return view('admin.components.admin-form', compact('data', 'id', 'form', 'breadcrumb', 'module', 'pageHeading'));
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
            'title'             => 'required|max:255',
            'short_desc'              => 'required|max:255',
            'rank'              => 'required|numeric'
        ];

        $input = $request->all();

        $this->validate($request, $validationRules);

        if ($id) {
            $contentCount =  \App\Models\KnowledgeContent::where('category_id',$id)
                    ->where('status',1)->count();
            if ($contentCount > 0 && $request->status == 0) {
                return redirect()
                    ->route($this->_routePrefix . '.index', $input['kw_category_id'])
                    ->with('error', 'Sorry you can`t disable this category as you have active content in this category');
            }
            $data       = $this->_model->find($id);

            if (!$data) {
                return \App\Helpers\Helper::resp('Not a valid data', 400);
            }
            $data->update($input);
        } else {
            $data   = $this->_model->create($input);
        }

        return redirect()
            ->route($this->_routePrefix . '.index', $input['kw_category_id'])
            ->with('success', 'Record has been successfully saved.');
    }
}
