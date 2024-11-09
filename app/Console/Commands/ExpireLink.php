<?php

namespace App\Console\Commands;

use App\Models\Url;
use Illuminate\Console\Command;

class ExpireLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:expire_date_link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = date('Y-m-d');
        $one_day_ago = date('Y-m-d', strtotime($today . ' -1 day'));
        // dd($one_day_ago);
        $url_list = Url::whereDate('expire_date','<=', $one_day_ago)->where('is_expire', 1)->delete();
        // dd($one_day_ago);
        return true;
    }
}
