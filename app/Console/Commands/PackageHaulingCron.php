<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PackageHaulingCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package_hauling:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $freQuencyDay = ['1' => 1,'2' => 7,'3' => 28,'4' => 365];
        $createUntillDate = date("Y-m-d", strtotime("+ 90 day"));//
        $data = \App\Models\Package::whereNotNull(['branch_id','company_id'])->where(['is_active'=>1,'deleted_at'=>NULL])
                ->where(function($q){
                    return $q->whereNull('last_hauling_date')
                    ->orWhereDate('last_hauling_date','<=',date('Y-m-d'));
                })->get();
        foreach($data as $key => $val){
            if(!empty($val->frequency_number)){
                $freQuencyDayVal = (int) $freQuencyDay[$val->frequency_type] * $val->frequency_number;
                $start_date = ($val->last_hauling_date)?$val->last_hauling_date:date('Y-m-d');
                $j = date('Y-m-d', strtotime($start_date. ' + '.$freQuencyDayVal.' days'));
                $lastdateModiFied = false;
                while(strtotime($j) <= strtotime($createUntillDate)){                    
                    \App\Models\CompanyHauling::create([
                        'company_id' => $val->company_id,
                        'branch_id' => $val->branch_id,
                        'package_id' => $val->id,
                        'number_of_boxes' => $val->boxes_included,
                        'date' =>  $j
                    ]);
                    $lastdateModiFied = true;
                    \App\Models\Package::where('id',$val->id)->update(['last_hauling_date' => $j]);
                    $j = date('Y-m-d', strtotime($j. ' + '.$freQuencyDayVal.' days'));
                }
                if(!$lastdateModiFied){
                    \App\Models\Package::where('id',$val->id)->update(['last_hauling_date' => $start_date]);
                }
            }
        }
    }
}
