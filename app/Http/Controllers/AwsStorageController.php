<?php

namespace App\Http\Controllers;

use Aws;
use Exception;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Manifest;
use App\Models\AwsStorage;
use App\Models\ManifestType;
use Illuminate\Http\Request;
use App\Models\CompanyBranch;
use App\Http\Controllers\Controller;
date_default_timezone_set("Asia/Kolkata");

class AwsStorageController extends Controller
{
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);
        $this->_module      = 'Aws File Storage';
        $this->_routePrefix = 'storage';
        $this->_model       = new AwsStorage();
        $this->_offset = 10;
        $this->_successStatus = 200;
        $this->_message = "Success!!";
    }
    public function index(Request $request)
    {
        $this->initIndex();
        $search = $request->all();
        $credentials = new Aws\Credentials\Credentials(\Config::get('services.ses.key'), \Config::get('services.ses.secret'));

        $s3 = new Aws\S3\S3Client([
            'version'     => 'latest',
            'region'      => 'us-west-2',
            'credentials' => $credentials
        ]);

        $listoptions = [
            'Bucket' => \Config::get('services.ses.bucket'),
            // 'Delimiter'=>'abc/',
            "Prefix" => isset($search['browse'])?$search['browse']:''
        ];
        $results = $s3->listObjects($listoptions);
        $objects = [];
        $unique_objects = [];
        $browse_length = 0;
        if(isset($search['browse']) ) {
            $browse_content = explode('/',$search['browse']);
            $browse_length = count($browse_content) - 1;
        }
        // dd($results['Contents']);
        if (!isset($results['Contents']) || count($results['Contents'])==0) {
            $browseloc = explode('/',$search['browse']);
            array_pop($browseloc);
            array_pop($browseloc);
            if (count($browseloc)) {
                $loc = implode('/',$browseloc).'/';
            } else {
                $loc = '';
            }
            return redirect()->route($this->_routePrefix . '.index',['browse'=>$loc]);
        }
        foreach($results['Contents'] as $content) {
            $elem = explode('/', $content['Key']);
            if (count($elem) > ($browse_length + 1 )) {
                $type = 'folder';
                $obj = explode('/', $content['Key'])[$browse_length].'/';
            } else {
                $obj = explode('/', $content['Key'])[$browse_length];
                $type = 'file';
            }
            if ($obj) {
                if (!in_array( $obj, $unique_objects)) {
                    $unique_objects[] = $obj;
                    $objects[] = array(
                        'object' =>$obj,
                        'type' => $type,
                        'createdate' => date('m/d/Y h:i:s a', strtotime($content['LastModified'])),
                        'size' => $content['Size'],
                    );
                }

            }

        }
        $manifesttypemodel = new ManifestType();
        $manifesttype = $manifesttypemodel->getListing(['type'=>1, 'status'=>1])->pluck('name');
        $arr= $arr2=[];
        foreach($manifesttype as $key=>$val) {
            $arr[$val] = $val;
        }
        $this->_data['manifesttype'] = $arr;
        $subtype = $manifesttypemodel->getListing(['type'=>2, 'status'=>1])->pluck('name');
        foreach($subtype as $key=>$val) {
            $arr2[$val] = $val;
        }
        $this->_data['subtypes'] = $arr2;
        $this->_data['objects'] = $objects;
        $this->_data['location'] = isset($search['browse'])?$search['browse']:'';
        $this->_data['prevlocation'] = isset($search['browse'])?$this->getPreviousFolder($search['browse']):'';
        return view('admin.' . $this->_routePrefix . '.index', $this->_data);
    }

    public function createfolder(Request $request)
    {
        $input = $request->all();
        $location = '';
        if ($input['locationpath']) {
            $location = $input['locationpath'];
        }
        $path = $location.$input['foldername']. '/';
        $res = $this->_model->createFolder($path);
        return Helper::rj($this->_message, $this->_successStatus, $res);

    }
    public function uploadfile(Request $request)
    {
        $input = $request->all();
        $location = '';
        if ($input['filelocationpath']) {
            $location = $input['filelocationpath'];
        }
        if(!empty($_FILES['inputfile']['name']))
        {
            $file_data = fopen($_FILES['inputfile']['tmp_name'], 'r');
            $res = $this->_model->uploadFile($_FILES['inputfile']['tmp_name'], $location.$_FILES['inputfile']['name']);
        }

        return Helper::rj($this->_message, $this->_successStatus, $res);
    }
    public function downloadobject(Request $request)
    {
        $input = $request->all();
        $key=$input['key'];

        $credentials = new Aws\Credentials\Credentials(\Config::get('services.ses.key'), \Config::get('services.ses.secret'));
        $s3 = new Aws\S3\S3Client([
            'version'     => 'latest',
            'region'      => 'us-west-2',
            'credentials' => $credentials
        ]);

        // Get the object.
        $result = $s3->getObject([
            'Bucket' => \Config::get('services.ses.bucket'),
            'Key'    => $key
        ]);
        $obj = explode('/', $key);
        $filename = $obj[count($obj)-1];
        // Display the object in the browser.
        header("Content-Type: {$result['ContentType']}");
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Connection: Keep-Alive');
        echo $result['Body'];
    }

    public function destroy(Request $request)
    {
        $input = $request->all();
        $location = $input['location'];
        $file = $input['file'];
        $fullpath = $file;
        if($location!='-') {
            $fullpath = $location.$file;
        }
        $credentials = new Aws\Credentials\Credentials(\Config::get('services.ses.key'), \Config::get('services.ses.secret'));
        $s3 = new Aws\S3\S3Client([
            'version'     => 'latest',
            'region'      => 'us-west-2',
            'credentials' => $credentials
        ]);

        $listoptions = [
            'Bucket' => \Config::get('services.ses.bucket'),
            "Prefix" => $fullpath
        ];
        $results = $s3->listObjects($listoptions);
        foreach($results['Contents'] as $content) {
            if (strpos($content['Key'], 'manifest-pdf-files') !== false) {
                $keyarr = explode('/', $content['Key']);
                $fileobj = $keyarr[count($keyarr)-1];
                if($fileobj!='') {
                    $manifestmodel = new Manifest();
                    $manifestid = explode('.',$fileobj)[0];
                    $exists = $manifestmodel->getListing(['uniq_id'=>$manifestid]);
                    if (count($exists) != 0 ) {
                        $manifest = $exists[0];
                        $manifest->file_path = '';
                        $manifest->save();
                    }

                    // dd($manifestid);
                }
            }
            $res[] = $this->_model->deleteobj($content['Key']);
        }
        return redirect()
                ->route($this->_routePrefix . '.index',['browse'=>($location!='-')?$location:''])
                ->with('success', $this->_message. ' Deleted object - '. $file );
    }


    public function uploadmanifest(Request $request)
    {
        $input = $request->all();
        $filelocation = 'manifest-pdf-files/';
        if(!empty($_FILES['manifestfileinput']['name']))
        {
            $type = $input['manifesttype'];
            $subtype = $input['manifestsubtype'];
            $additional_path = $type.'/'.$subtype.'/';
            $manifestid = explode('.', $_FILES['manifestfileinput']['name'])[0];
            $manifestmodel = new Manifest();
            $locationmodel = new CompanyBranch();
            $exists = $manifestmodel->getListing(['uniq_id'=>$manifestid, 'with'=>['hauling_details']]);
            $file_data = fopen($_FILES['manifestfileinput']['tmp_name'], 'r');
            if (count($exists) != 0 ) {
                $manifest = $exists[0];
                $location_det = $locationmodel->getListing(['id'=>$manifest->hauling_details->branch_id,'with'=>['company']]);
                $filelocation = $additional_path.$filelocation.$location_det->company->company_number.'/'.$location_det->uniq_id.'/';
                $filename = $location_det->uniq_id.'_'.$manifest->date.'_'.'hauling'.'_'.$_FILES['manifestfileinput']['name'];
                $res = $this->_model->uploadFile($_FILES['manifestfileinput']['tmp_name'], $filelocation.$filename, 'public-read');
                $manifest->file_path = $res;
                $manifest->save();
                return Helper::rj($this->_message, $this->_successStatus, ['uploaded'=>true]);
            } else {
                $filelocation = 'queueitems'.'/'.$additional_path;
                $res = $this->_model->uploadFile($_FILES['manifestfileinput']['tmp_name'], $filelocation.$_FILES['manifestfileinput']['name'], 'public-read');
                return Helper::rj($this->_message, $this->_successStatus, ['uploaded'=>false]);
            }

        }
    }

    public function uploadManifestApi(Request $request)
    {

        $input = $request->all();
        $rules = array(
            'manifesttype' => 'required',
            'manifestsubtype' => 'required',
            'token' => 'required',
            'manifestfileinput' => 'required',
        );
        $messages = [
        ];
        $validator = \Validator::make($input, $rules,$messages);
        if ($validator->fails()) {
            return Helper::rj($validator->errors()->first(), 400);
        }

        $token = $request->token;
        $findToken = User::where('api_token',$token)->first();
        if ($token && $findToken) {
            return $this->uploadmanifest($request);
        } else {
            return Helper::rj("Not A valid token", 400, ['uploaded'=>false]);
        }
    }

    private function getPreviousFolder($current='') {
        $prevlocation = '';
        if ($current) {
            $arr = explode ('/', $current);
            if (count($arr) >2 ) {
                for ($i=0; $i < count($arr) - 2; $i++) {
                    $prevlocation= $prevlocation.$arr[$i].'/';
                }
            }
        }
        return $prevlocation;
    }
}
