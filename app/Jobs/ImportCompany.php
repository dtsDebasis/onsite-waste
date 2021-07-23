<?php

namespace App\Jobs;

use Exception;
use App\Helpers\Helper;
use App\Models\Company;
use App\Models\LeadSource;
use App\Models\Speciality;
use App\Models\AddressData;
use App\Models\CompanyOwner;
use App\Models\CompanyBranch;
use Illuminate\Bus\Queueable;
use App\Models\BranchSpecialty;
use App\Models\CompanySpeciality;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ImportCompany implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
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
            $val = $this->data;
            // check Company or not
            $company_status = false;
            //$associate_id = \App\Helpers\Helper::getOnlyIntegerValue($val[16]);
            $associate_id = $val[16];
            $branch = true;
            if(empty($val[16])){
                $company_status = true;
            }
            else{
                $asociates = explode(',',$val[16]);
                $company_status = (count($asociates) > 1)?true:false;
                $branch = ($company_status)?false:$branch;
            }

            $phone = \App\Helpers\Helper::getOnlyIntegerValue($val[5]);
            $company = null;
            //CompanyOwner
            $ownerId = 0;
            $findCompanyOwner = CompanyOwner::where('name',$val[2])->first();
            if (!$findCompanyOwner) {
                $newCompanyOwner = new CompanyOwner;
                $newCompanyOwner->name = $val[2];
                $newCompanyOwner->save();
                $ownerId = $newCompanyOwner->id;
            }else {
                $ownerId = $findCompanyOwner->id;
            }

            $findLeadSource1 = LeadSource::where('name',$val[3])->first();
            if (!$findLeadSource1) {
                $newLeadSource1 = new LeadSource;
                $newLeadSource1->name = $val[3];
                $newLeadSource1->lead_type = 1;
                $newLeadSource1->save();
                $leadSource1 = $newLeadSource1->id;
            }else {
                $leadSource1 = $findLeadSource1->id;
            }

            $findLeadSource2 = LeadSource::where('name',$val[4])->first();
            if (!$findLeadSource2) {
                $newLeadSource2 = new LeadSource;
                $newLeadSource2->name = $val[4];
                $newLeadSource2->lead_type = 2;
                $newLeadSource2->save();
                $leadSource2 = $newLeadSource2->id;
            }else {
                $leadSource2 = $findLeadSource2->id;
            }
            if($company_status){
                $company = Company::where(['company_number' => $val[0]])->first();

                if(!$company){
                    $addressData = [
                        'addressline1' => $val[7].', '.$val[6],
                        'locality' => $val[7],
                        'state' => $val[6],
                        'country' => $val[8]
                    ];

                    $address = AddressData::create($addressData);

                    $address_id = ($address)?$address->id:null;

                    $company_data = [
                        //'company_number' => \App\Helpers\Helper::getOnlyIntegerValue($val[0]),
                        'company_number' => $val[0],
                        'company_name' => $val[1],
                        'phone' => $phone,
                        'email' => null,
                        'addressdata_id' => $address_id,
                        'lead_source' =>  $leadSource1,
                        'leadsource_2' => $leadSource2,
                        'owner' => $ownerId
                    ];

                    $company = Company::create($company_data);
                    CompanyBranch::where('company_number',$val[0])->update(['company_id' => $company->id]);

                    $associate_id = $val[0];
                    $specialities = explode(',',trim($val[9]));

                    foreach($specialities as $key=>$spval){
                        if ($spval) {
                            $existing = \App\Models\Speciality::where(['name' => $spval])->first();
                            if(!$existing){
                                $existing  = \App\Models\Speciality::create(['name' => $spval,'status' => 1]);
                            }
                            if($existing && $company){
                                \App\Models\CompanySpeciality::create([
                                    'company_id' => $company->id,
                                    'specality_id' => $existing->id
                                ]);
                            }
                        }
                    }
                }
            }
            if($branch){

                $addressData = [
                    'addressline1' => $val[7].', '.$val[6].', '.$val[8],
                    'locality' => $val[7],
                    'state' => $val[6],
                    'country' => $val[8]
                ];
                $address = AddressData::create($addressData);
                $address_id = ($address)?$address->id:null;

                $branchHubId = \App\Helpers\Helper::getOnlyIntegerValue($val[0]);
                $company_data = [
                    'uniq_id' => $branchHubId,
                    'company_number' => $associate_id,
                    'name' => $val[1],
                    'phone' => $phone,
                    'addressdata_id' => $address_id,
                    'lead_source' =>  $leadSource1,
                    'leadsource_2' => $leadSource2,
                    'owner' => $ownerId
                ];


                $findBranch = CompanyBranch::where('uniq_id',$branchHubId)->first();
                if ($findBranch) {
                    $findBranch->delete();
                }
                $companyBranch = CompanyBranch::create($company_data);
                $companyBranch->sh_container_type = $val[15];
                $companyBranch->sh_rop = $val[14];
                $companyBranch->rb_container_type = $val[12];
                $companyBranch->rb_rop = $val[11];
                $companyBranch->save();

                Log:info($companyBranch->id);

                $specialities = explode(',',trim($val[9]));

                foreach($specialities as $key=>$spval){
                    if ($spval) {
                        $existing = \App\Models\Speciality::where(['name' => $spval])->first();
                        if(!$existing){
                            $existing  = \App\Models\Speciality::create(['name' => $spval,'status' => 1]);
                        }
                        if($existing && $companyBranch){
                            \App\Models\BranchSpecialty::create([
                                'specality_id' => $existing->id,
                                'company_branch_id' => $companyBranch->id
                            ]);
                        }
                    }
                }
            }

            $findComp = Company::where('company_number', $associate_id)->first();

            if ($findComp) {
                CompanyBranch::where('company_number',$associate_id)->update(['company_id' => $findComp->id]);
            }
            // $companies = Company::select(['id','company_number'])->get();
            // foreach($companies as $key=>$val){
            //     CompanyBranch::where('company_number',$val->company_number)->update(['company_id' => $val->id]);
            // }
        } catch (Exception $e) {
            Log::info($this->data);
        }
    }
}
