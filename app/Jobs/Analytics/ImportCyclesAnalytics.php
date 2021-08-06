<?php

namespace App\Jobs\Analytics;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCyclesAnalytics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $hauling_id;
    protected $branch_id;
    protected $start_date;
    protected $end_date;
    protected $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($hauling_id,$branch_id,$start_date,$end_date,$type)
    {
        $this->hauling_id = $hauling_id;
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
        //
    }
}
