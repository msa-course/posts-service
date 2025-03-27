<?php

namespace App\Console\Commands;

use App\Mail\PostCreated;
use App\Models\Post;
use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class GetCacheKeysCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-cache-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get cache keys';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keys = Redis::connection('cache')->keys('*');
        Log::debug($keys);
    }
}
