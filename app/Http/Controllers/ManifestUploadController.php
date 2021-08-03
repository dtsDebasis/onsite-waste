<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ManifestUpload;
use App\Models\AwsStorage;
use App\Helpers\Helper;
use App\Jobs\ImportManifest;
use App\Models\CompanyBranch;
use App\Models\CompanyHauling;
use App\Models\Manifest;
use DateTime;


class ManifestUploadController extends Controller
{
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);       
        $this->_module      = 'Manifest Upload';
        $this->_routePrefix = 'manifests';
        $this->_model       = new ManifestUpload();
        $this->_offset = 10;
        $this->_successStatus = 200;
        $this->_message = "Success!!";
    }
    public function index(Request $request)
    {
        $this->initIndex();
        
        return view('admin.' . $this->_routePrefix . '.index', $this->_data);
    }
    public function processcsv(Request $request)
    {
        if(!empty($_FILES['manifestdata']['name']))
        {
            $row_data=[];
            if ($_POST['start-date']) {
                $start_date = strtotime($_POST['start-date']);
                $start_date = date('Y-m-d',$start_date);
            } else {
                $day = date('w');
                $start_date = date('Y-m-d', strtotime('-'.$day.' days'));
            }
            $awskey = 'manifest-csv-uploads/org-'.$_FILES['manifestdata']['name'];
            $awsmodelObj = new AwsStorage();
            $awsupload = $awsmodelObj->uploadFile($_FILES['manifestdata']['tmp_name'], $awskey);
            $file_data = fopen($_FILES['manifestdata']['tmp_name'], 'r');
            $column = fgetcsv($file_data);
            $def_columns = array("Date", "Delivery #", "Disposal Date", "Driver", "Customer Company ID", "Customer Internal Account #", "Customer Name", "Customer's Service - Full Address", "Manifest #", "Manifest Note", "Schedule Frequency", "Total Number of Items/Services", "Total Weight of Items/Services", "Truck - Pickup", "Waste Type", "Waste Subtype");
            if ($column != $def_columns) {
                return Helper::rj("File mismatch. Please enter data in proper file format", $this->_successStatus, []); 
            }
            array_unshift($column, 'Wastetech Internal #');
            array_unshift($column, 'Location ID');   
            $column_data = [];
            $excludes = ['Disposal Date','Customer Internal Account #','Delivery #' ];
            foreach ($column as $value) {
                if (in_array($value, $excludes)) {
                    continue;
                }
                $column_data[] = $value;
            }
            $location_keys = [];
            while($row = fgetcsv($file_data))
            {
                $row[0] = str_replace('-', '/', $row[0]);
                // dd(new DateTime($row[0]));
                $location_det='';
                $manifest_date = strtotime($row[0]);
                $manifest_date = date('Y-m-d',$manifest_date);
                if($manifest_date < $start_date) {
                    continue;
                }
                $address = explode(' ', $row[7]); 
                $combined_key = $address[0].$address[count($address)-1];
                if (! array_key_exists($combined_key,$location_keys)) {
                    $key = ['street_no'=>$address[0], 'pin'=> $address[count($address)-1]];
                    $data = app('App\Models\CompanyBranch')->getListing(['with'=>'addressdata', 'wherehas'=>['addressdata', function($q) use($key) { $q->where('postcode',$key['pin'])->where('addressline1','like',$key['street_no']."%");}]])->first();
                    if ($data) {
                        $location_keys[$combined_key] = [
                            'location' => $data,
                            'street_no'=>$address[0], 
                            'pin'=> $address[count($address)-1]
                        ]; 
                        $location_det = $data;
                    }
                } else {
                    $location_det = $location_keys[$combined_key]['location'];
                }
                
                $row_data[] = array(
                    'locationid'  => $location_det? $location_det->uniq_id : '',
                    'internalid'  => $location_det? $location_det->company_id.'#'.$location_det->id : '',
                    'date'  => $row[0],
                    // 'deliveryid'  => $row[1],
                    // 'disposaldate'  => $row[2],
                    'driver'  => $row[3],
                    'customer_company_id'  => $row[4],
                    // 'customer_internal_acc'  => $row[5],
                    'customer_name'  => $location_det? $location_det->name : $row[6],
                    'customer_address'  => $location_det? $location_det->addressdata->addressline1 : $row[7],
                    'manifest_id'  => $row[8],
                    'manifest_note'  => $row[9],
                    'schedule_freq'  => $row[10],
                    'no_of_items'  => $row[11],
                    'weight_of_items'  => $row[12],
                    'truck_pickup'  => $row[13],
                    'waste_type'  => $row[14],
                    'waste_subtype'  => $row[15],
                );
            }
            $output = array(
                'column'  => $column_data,
                'row_data'  => $row_data
            );
            return Helper::rj($this->_message, $this->_successStatus, $output);
        }

    }

    public function savecsv(Request $request)
    {
        $data = $request->all();
        $pickup_data = [];
        $fileName = 'manifest'.date('Y-m-d').'.csv';
        // $columns = array("Location ID", "Date", "Delivery #", "Disposal Date", "Driver", "Customer Company ID", "Customer Internal Account #", "Customer Name", "Customer's Service - Full Address", "Manifest #", "Manifest Note", "Schedule Frequency", "Total Number of Items/Services", "Total Weight of Items/Services", "Truck - Pickup", "Waste Type");
        $columns = array("Location ID",'Wastetech Internal #',  "Date", "Driver", "Customer Company ID", "Customer Name", "Customer's Service - Full Address", "Manifest #", "Manifest Note", "Schedule Frequency", "Total Number of Items/Services", "Total Weight of Items/Services", "Truck - Pickup", "Waste Type");
        $path =  \Storage::disk('public')->path('manifest_uploads' . '/' . $fileName);
        $file = fopen($path, 'w');
	    fputcsv($file, $columns);
        $i = 1;
        foreach ($data as $manifest) {
            fputcsv($file, $manifest);
            if($manifest['locationid']!='') {  
                if ($manifest['internalid']=='' || $manifest['internalid']=='null') {
                    $branch_det = app('App\Models\CompanyBranch')->getListing(['uniq_id'=>$manifest['locationid']])->first();
                    $company_id = $branch_det->company_id;
                    $branch_id = $branch_det->id;
                } else {
                    $company_id = explode('#', $manifest['internalid'])[0];
                    $branch_id = explode('#', $manifest['internalid'])[1];
                }
                $pickup_data = array(
                    'company_id'=> $company_id,
                    'branch_id'=> $branch_id,
                    'package_id'=> '',
                    'number_of_boxes'=> $manifest['no_of_items'],
                    'driver_name'=> $manifest['driver'],
                    'supplies_requested'=> 0,
                    'request_type'=> 1,
                    'date'=> date('Y-m-d H:i:s', strtotime($manifest['date'])),
                    'description'=> $manifest['manifest_note'],
                    'is_additional'=> 0,
                    'status'=> 3,
                    'manifest_data'=> [
                        'uniq_id' => $manifest['manifest_id'],
                        'hauling_id' => '',
                        'person_name' => $manifest['driver'],
                        'date' => date('Y-m-d H:i:s', strtotime($manifest['date'])),
                        'number_of_container' => $manifest['no_of_items'],
                        'branch_address' => $manifest['customer_address'],
                        'status' => 1
                    ]
                );
                ImportManifest::dispatch($pickup_data);
                $i++; 
            }   
        }
        
        $awskey= 'manifest-csv-uploads/'.$fileName;
        $awsmodelObj = new AwsStorage();
        $res = $awsmodelObj->uploadFile($path, $awskey);
        $restata = [
            'fileupload' => $res,
            'rowcount' => $i
        ];
        return Helper::rj($this->_message, $this->_successStatus, $restata);
    }

}