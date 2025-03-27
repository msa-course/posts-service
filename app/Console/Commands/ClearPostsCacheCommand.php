<?php

namespace App\Console\Commands;

use App\Mail\PostCreated;
use App\Models\Post;
use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ClearPostsCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-posts-cache {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear post cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');

        if ($id) {
            Cache::forget('posts' . $id);
        } else {
            Cache::flush();
        }
    }
}
