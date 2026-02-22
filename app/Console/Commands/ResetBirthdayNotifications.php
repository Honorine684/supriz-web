<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FriendBirthday;
use Illuminate\Support\Facades\Log;

class ResetBirthdayNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthdays:reset-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Réinitialiser les flags de notification pour la nouvelle année';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = FriendBirthday::where('notification_sent_this_year', true)->count();

        FriendBirthday::query()->update(['notification_sent_this_year' => false]);

        $this->info("Réinitialisé {$count} flag(s) de notification");

        Log::info("Birthday notification flags reset", ['count' => $count]);

        return Command::SUCCESS;
    }
}
