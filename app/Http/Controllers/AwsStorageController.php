<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AwsStorage;
use App\Helpers\Helper;
use Aws;
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
        $credentials = new Aws\Credentials\Credentials(env("AWS_ACCESS_KEY_ID"), env("AWS_SECRET_ACCESS_KEY"));

        $s3 = new Aws\S3\S3Client([
            'version'     => 'latest',
            'region'      => 'us-west-2',
            'credentials' => $credentials
        ]);
        
        $listoptions = [
            'Bucket' => env("AWS_BUCKET"),
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

        $credentials = new Aws\Credentials\Credentials(env("AWS_ACCESS_KEY_ID"), env("AWS_SECRET_ACCESS_KEY"));
        $s3 = new Aws\S3\S3Client([
            'version'     => 'latest',
            'region'      => 'us-west-2',
            'credentials' => $credentials
        ]);

        // Get the object.
        $result = $s3->getObject([
            'Bucket' => env("AWS_BUCKET"),
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
        $credentials = new Aws\Credentials\Credentials(env("AWS_ACCESS_KEY_ID"), env("AWS_SECRET_ACCESS_KEY"));
        $s3 = new Aws\S3\S3Client([
            'version'     => 'latest',
            'region'      => 'us-west-2',
            'credentials' => $credentials
        ]);
        
        $listoptions = [
            'Bucket' => env("AWS_BUCKET"),
            "Prefix" => $fullpath
        ];
        $results = $s3->listObjects($listoptions);
        foreach($results['Contents'] as $content) {
            $res[] = $this->_model->deleteobj($content['Key']);
        }
        return redirect()
                ->route($this->_routePrefix . '.index',['browse'=>($location!='-')?$location:''])
                ->with('success', $this->_message. ' Deleted object - '. $file );
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