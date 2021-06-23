<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;

use App\Models\KnowledgeCategory;
use App\Models\KnowledgeWizard;
use App\Models\KnowledgeContent;
use App\Models\KnowledgePreference;
use App\Models\KnowledgeContentTag;
use Auth;
use Exception;
class KnowledgeCenterController extends Controller
{
    protected $_user;
    protected $_siteSettings;
    public $successStatus = 200;
    public $message = "Success!!";
	public function __construct($parameters = array()) {
		parent::__construct($parameters);
        $this->middleware(function (Request $request, $next) {
            $this->_user = Auth::user();
            return $next($request);
        });
        $this->_siteSettings = \App\Helpers\Helper::SiteSettingDetails();
		$this->_routePrefix = 'knowledgecenter';
	}

    public function getCategory(Request $request) {
        try {
            $input = $request->all();
            $modelObj = new KnowledgeCategory();
            if((!isset($input['kw_category_id']) || empty($input['kw_category_id']))){
                $input['kw_category_id'] = 0;
            }
            $input['status'] = 1;
            $data = $modelObj->getListing($input);
            return Helper::rj($this->message, $this->successStatus, $data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }

    }

    public function getKnowledgeWizard(Request $request) {
        try {
            $input = $request->all();
            $modelObj = new KnowledgeWizard();
            $input['status'] = 1;
            $data =$modelObj->getListing($input);
            return Helper::rj($this->message, $this->successStatus, $data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }

    public function addUserPreference(Request $request) {
        try {
            $input = $request->all();
            $data =[];
            $user_id   = \Auth::user()->id;
            if($input['wizard_ids']) {
                KnowledgePreference::where(['user_id'=>$user_id])->delete();
                foreach ($input['wizard_ids'] as $key => $value) {
                    $data[] = array(
                        'knowledge_wizard_id' =>$value,
                        'user_id' =>$user_id,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s')
                    );
                }
            }
            KnowledgePreference::insert($data);
            $modelObj = new KnowledgePreference();
            $return_data =$modelObj->getListing(['user_id'=>$user_id]);
            return Helper::rj($this->message, $this->successStatus, $return_data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }


    public function getUserPreference(Request $request) {
        try {
            $input = $request->all();
            $user_id   = \Auth::user()->id;
            $modelObj = new KnowledgePreference();
            $data =$modelObj->getListing(['user_id'=>$user_id,'with'=>'knowledge_wizard']);
            return Helper::rj($this->message, $this->successStatus, $data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }

    public function getKnowledgeContent(Request $request) {
        try {
            
            $input = $request->all();
            $modelObj = new KnowledgeContent();
            if(isset($input['content_id'])) {
                $data =$modelObj->getListing(['id'=>$input['content_id'],'with' => ['tags']]);
            } else {
                $kw_category_id = 0;
                $input['status'] = 1;
                $input['with'] = ['tags'];
                $data =$modelObj->getListing($input,9);
            }
            
            return Helper::rj($this->message, $this->successStatus, $data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }
    public function getTagList(Request $request) {
        try {
            
            $input = $request->all();
            $modelObj = new KnowledgeContentTag();
            $input['groupby'] = 'tag';
            $data =$modelObj->getListing($input);
            return Helper::rj($this->message, $this->successStatus, $data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }

    public function getContentByWizardCat(Request $request){
        try{
            $input = $request->all();
            $rules = array(
                'id' => 'required|integer'
            );
            $messages = [
                'id.required' => 'Details not found'
            ];
            $validator = \Validator::make($input, $rules,$messages);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 400);
            }
            else{
                $wizard = app('App\Models\KnowledgeWizard')->getListing(['id' => $input['id']]);
                if($wizard){
                    $data = app('App\Models\KnowledgeCategory')->getListing(['id' => $wizard->category_id,'with'=>['knowledge_content'=>function($q){return $q->orderBy('rank','ASC')->offset(0)->limit(5);}]]);
                    return Helper::rj('List fetch successfully', $this->successStatus, $data);
                }
                else{
                    throw new Exception('Details not found', 400);
                }
            }
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }

    public function getContentWithCat(Request $request){
        try{
            $input = $request->all();
            $data = [];
            if(isset($input['category_id']) && $input['category_id']){
                $data = app('App\Models\KnowledgeCategory')->getListing(['id' => $input['category_id'],'with'=>['knowledge_content'=>function($q){return $q->orderBy('rank','ASC');}]]);
            }
            else{
                //,'with'=>['knowledge_content'=>function($q){return $q->orderBy('rank','ASC')->limit(5);}]
                $data = app('App\Models\KnowledgeCategory')->getListing(['kw_category_id'=>0]);
                foreach($data as $key => $val){
                    $val->knowledge_content = \App\Models\KnowledgeContent::where('category_id',$val->id)->skip(0)->take(5)->orderBy('rank','ASC')->get();
                }
            }
            return Helper::rj('List fetch successfully', $this->successStatus, $data);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }
    

}
