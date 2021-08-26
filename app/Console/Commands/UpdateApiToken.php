<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UpdateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Api Token';

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
        $users = User::where('customer_type', 0)->where('api_token', null)->get();
        foreach ($users as $key => $user) {
            $user->api_token = Hash::make($user->email);
            $user->save();
        }
        return 1;
    }
}
