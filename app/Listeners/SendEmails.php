<?php

namespace App\Listeners;

use App\Jobs\SendEmails as SendEmailsJob;

class SendEmails
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        SendEmailsJob::dispatch($event->post);
    }
}
