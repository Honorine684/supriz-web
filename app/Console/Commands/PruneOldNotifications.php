<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\NotificationDeleteUser;
use App\Models\NotificationMakereadUser;
use Illuminate\Support\Facades\Log;

class PruneOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:prune-old {--days=7}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprime les notifications ADMIN (dashboard, globales) de plus de N jours (7 par défaut)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        if ($days < 1) {
            $days = 7;
        }

        $cutoff = now()->subDays($days);

        // Uniquement les notifications ADMIN (envoyées depuis le dashboard) :
        // elles sont globales (user_id null). Les notifs personnelles/anniversaire
        // (user_id renseigné) sont préservées.
        $ids = Notification::whereNull('user_id')
            ->where('created_at', '<', $cutoff)
            ->pluck('id');

        if ($ids->isEmpty()) {
            $this->info('Aucune notification à supprimer.');
            return Command::SUCCESS;
        }

        // Nettoie d'abord les tables de suivi (lu / supprimé par utilisateur)
        // pour ne pas laisser de lignes orphelines.
        NotificationMakereadUser::whereIn('id_notification', $ids)->delete();
        NotificationDeleteUser::whereIn('id_notification', $ids)->delete();

        $deleted = Notification::whereIn('id', $ids)->delete();

        $this->info("Supprimé {$deleted} notification(s) de plus de {$days} jours.");
        Log::info('Notifications purgées', ['days' => $days, 'count' => $deleted]);

        return Command::SUCCESS;
    }
}
