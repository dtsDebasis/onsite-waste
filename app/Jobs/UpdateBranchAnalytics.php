<?php

namespace App\Jobs;

use App\Models\Analytics;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UpdateBranchAnalytics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $start_date;
    protected $end_date;
    protected $branch_id;
    protected $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($start_date,$end_date,$branch_id,$type)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->branch_id = $branch_id;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Analytics::processAnalytics($this->start_date,$this->end_date,$this->branch_id,$this->type);
        // switch ($this->type) {
        //     case 'trips':
        //         Analytics::trips($this->start_date,$this->end_date,$this->branch_id);
        //         break;
        //     case 'boxes':
        //         Analytics::boxes($this->start_date,$this->end_date,$this->branch_id);
        //         break;
        //     case 'weight':
        //         Analytics::weight($this->start_date,$this->end_date,$this->branch_id);
        //         break;
        //     case 'spend':
        //         Analytics::spend($this->start_date,$this->end_date,$this->branch_id);
        //         break;
        //     case 'cycles':
        //         Analytics::cycles($this->start_date,$this->end_date,$this->branch_id);
        //         break;
        //     case 'all':
        //         Analytics::trips($this->start_date,$this->end_date,$this->branch_id);
        //         Analytics::boxes($this->start_date,$this->end_date,$this->branch_id);
        //         Analytics::weight($this->start_date,$this->end_date,$this->branch_id);
        //         Analytics::spend($this->start_date,$this->end_date,$this->branch_id);
        //         Analytics::cycles($this->start_date,$this->end_date,$this->branch_id);
        //         break;
        // }
    }
}
