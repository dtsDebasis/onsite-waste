<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CompanyBranch;
use App\Models\CompanyHauling;
use App\Models\Manifest;
use Illuminate\Support\Facades\Log;

class ImportManifest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    /**
    * array(
    *    'company_id'=> $company_id,
    *    'branch_id'=> $branch_id,
    *    'package_id'=> '',
    *    'number_of_boxes'=> $manifest['no_of_items'],
    *    'driver_name'=> $manifest['driver'],
    *    'supplies_requested'=> 0,
    *    'request_type'=> 1,
    *    'date'=> date('Y-m-d H:i:s', strtotime($manifest['date'])),
    *    'description'=> $manifest['manifest_note'],
    *    'is_additional'=> 0,
    *    'status'=> 3,
    *    'manifest_data'=> [
    *        'uniq_id' => $manifest['manifest_id'],
    *        'hauling_id' => '',
    *        'person_name' => $manifest['driver'],
    *        'date' => date('Y-m-d H:i:s', strtotime($manifest['date'])),
    *        'number_of_container' => $manifest['no_of_items'],
    *        'branch_address' => $manifest['customer_address'],
    *        'status' => 1
    *    ]
    * );
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $pickupdata = $this->data;
            $pickupmodel = new CompanyHauling();
            $manifestmodel = new Manifest();
            $locationmodel = new CompanyBranch();
            $exists = $manifestmodel->getListing(['uniq_id'=>$pickupdata['manifest_data']['uniq_id']]);
            if (count($exists) == 0 ) {
                $location_det = $locationmodel->getListing(['id'=>$pickupdata['branch_id'],'with'=>['package_details']]);
                $res = $pickupmodel->create($pickupdata);
                if ($res) {
                    $pickup_id = $res->id;
                    $manifestdata = $pickupdata['manifest_data'];
                    $manifestdata['hauling_id'] = $pickup_id;
                    $manifestres = $manifestmodel->create($manifestdata);
                }
                Log::info(date('Y-m-d H:i:s')."import Manifest #".$pickupdata['manifest_data']['uniq_id']." imported successfully");
                Log::info($manifestdata);
            } else {
                Log::info(date('Y-m-d H:i:s')."import Manifest #".$pickupdata['manifest_data']['uniq_id']." already exists");
            }
        } catch (Exception $e) {
            Log::info($e);
        }
    }
}
