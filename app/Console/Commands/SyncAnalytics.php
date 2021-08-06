<?php

namespace App\Console\Commands;

use App\Models\Analytics;
use Illuminate\Console\Command;
use App\Jobs\UpdateBranchAnalytics;

class SyncAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:analytics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync All Analytics Data';

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
        $type='all';
        $num_of_month = 12;
        $branch_id = 0;
        $date_format = 'y-m-d';
        for ($i=1; $i <= $num_of_month; $i++) {
            $start_date = date('Y-m-01', strtotime(date($date_format, strtotime('-'.$i.' month'))));
            $end_date = date('Y-m-t', strtotime(date($date_format, strtotime('-'.$i.' month'))));
            UpdateBranchAnalytics::dispatch($start_date,$end_date,$branch_id,$type);
        }
    }
}
