<?php

namespace App\Jobs;

use App\Helpers\Helper;
use App\Models\Company;
use App\Models\Speciality;
use App\Models\AddressData;
use App\Models\CompanyBranch;
use Illuminate\Bus\Queueable;
use App\Models\BranchSpecialty;
use App\Models\CompanySpeciality;
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
        $val = $this->data;
        // check Company or not
        $company_status = false;
        $associate_id = \App\Helpers\Helper::getOnlyIntegerValue($val[16]);
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
                    'company_number' => \App\Helpers\Helper::getOnlyIntegerValue($val[0]),
                    'company_name' => $val[1],
                    'phone' => $phone,
                    'email' => null,
                    'addressdata_id' => $address_id,
                    'lead_source' =>  $val[3],
                    'leadsource_2' => $val[4],
                    'owner' => $val[2]
                ];

                $company = Company::create($company_data);
                self::createDefaultTranctionalPackage($company->id);
                $associate_id = $val[0];
                $specialities = explode(',',$val[9]);

                foreach($specialities as $key=>$spval){
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
        if($branch){
            $addressData = [
                'addressline1' => $val[7].', '.$val[6],
                'locality' => $val[7],
                'state' => $val[6],
                'country' => $val[8]
            ];
            $address = AddressData::create($addressData);
            $address_id = ($address)?$address->id:null;

            $company_data = [
                'uniq_id' => \App\Helpers\Helper::getOnlyIntegerValue($val[0]),
                'company_number' => $associate_id,
                'name' => $val[1],
                'phone' => $phone,
                'addressdata_id' => $address_id,
                'lead_source' =>  $val[3],
                'leadsource_2' => $val[4],
                'owner' => $val[2]
            ];
            $companyBranch = CompanyBranch::create($company_data);
            $specialities = explode(',',$val[9]);
            foreach($specialities as $key=>$spval){

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

        $companies = Company::select(['id','company_number'])->get();
        foreach($companies as $key=>$val){
            CompanyBranch::where('company_number',$val->company_number)->update(['company_id' => $val->id]);
        }
    }
}
