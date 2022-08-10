<?php

namespace App\Console\Commands;

use App\Http\Controllers\NotificationController;
use Illuminate\Console\Command;

class SendMailNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendmailnotifications';

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
        $notify= new NotificationController();
        $notify->sendMailNotification();
        $this->info('Cron to send mail notification run successfully!');
    }
}
