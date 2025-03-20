<?php

use App\Console\Commands\SendEmailsCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(SendEmailsCommand::class)->everyMinute();
