<?php

namespace App\Console\Commands;

use App\Mail\PostCreated;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails about new posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Post::query()
            ->where('created_at', '>', now()->subDays(1))
            ->chunkById(100, function ($posts) {
                foreach ($posts as $post) {
                    Mail::to('admin@example.com')->send(new PostCreated($post));
                }
            });
    }
}
