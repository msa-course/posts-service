<?php

namespace App\Providers;

use App\Events\PostCreated;
use App\Listeners\PostsSubscriber;
use App\Listeners\SendEmails;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            PostCreated::class,
            SendEmails::class,
        );

        Event::subscribe(PostsSubscriber::class);
    }
}
