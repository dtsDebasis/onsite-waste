<?php

namespace App\Jobs\Analytics;

use App\Models\Analytics;
use App\Models\CompanyBranch;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ImportSpendAnalytics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $branch_id;
    protected $start_date;
    protected $end_date;
    protected $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($branch_id,$start_date,$end_date,$type)
    {
        $this->branch_id = $branch_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Analytics::addSpendAnalytics($this->branch_id,$this->start_date,$this->end_date,$this->type);
    }
}
