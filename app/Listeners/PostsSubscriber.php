<?php

namespace App\Listeners;

use App\Events\PostCreatedForListener;
use App\Jobs\SendEmails as SendEmailsJob;
use Illuminate\Events\Dispatcher;

class PostsSubscriber
{
    public function sendEmails(PostCreatedForListener $event): void {
        SendEmailsJob::dispatch($event->post);
    }


    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            PostCreatedForListener::class,
            [PostsSubscriber::class, 'sendEmails']
        );
    }
}
