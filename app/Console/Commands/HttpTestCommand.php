<?php

namespace App\Console\Commands;

use App\Mail\PostCreated;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class HttpTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:http-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test HTTP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::get('https://httpbin.org/get', [
            'test_key' => 'test_value',
        ]);

        $this->info('GET:');
        $this->info($response->body());

        $response = Http::post('https://httpbin.org/post', [
            'test_key' => 'test_value',
        ]);

        $this->info('POST:');
        $this->info($response->body());

        $response = Http::withHeaders([
            'X-First' => 'foo',
            'X-Second' => 'bar'
        ])->post('https://httpbin.org/post', [
            'test_key' => 'test_value',
        ]);

        $this->info('POST with headers:');
        $this->info($response->body());
    }
}
