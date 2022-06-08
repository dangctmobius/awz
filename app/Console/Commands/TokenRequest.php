<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TokenRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:reset';

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
        \DB::table('token_requests')->truncate();
        echo 'token_requests ok!';
        return 0;
    }
}
