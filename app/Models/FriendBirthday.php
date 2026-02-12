<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FriendBirthday extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'friend_name',
        'friend_phone',
        'birthday_date',
        'reminder_time',
        'reminder_minutes_before',
        'is_active',
        'notification_sent_this_year',
        'notes',
    ];

    protected $casts = [
        'birthday_date' => 'date',
        'is_active' => 'boolean',
        'notification_sent_this_year' => 'boolean',
    ];

    /**
     * Relation avec User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir l'âge de l'ami
     */
    public function getAgeAttribute()
    {
        return $this->birthday_date->age;
    }

    /**
     * Obtenir le prochain anniversaire
     */
    public function getNextBirthdayAttribute()
    {
        $birthday = $this->birthday_date->copy();
        $birthday->year = Carbon::now()->year;

        if ($birthday->isPast()) {
            $birthday->addYear();
        }

        return $birthday;
    }

    /**
     * Nombre de jours restants jusqu'au prochain anniversaire
     */
    public function getDaysUntilBirthdayAttribute()
    {
        return Carbon::now()->startOfDay()->diffInDays($this->next_birthday, false);
    }

    /**
     * Vérifier si c'est l'anniversaire aujourd'hui
     */
    public function isBirthdayToday()
    {
        $today = Carbon::now();
        return $this->birthday_date->month === $today->month
            && $this->birthday_date->day === $today->day;
    }

    /**
     * Scope pour les anniversaires à venir dans les X jours
     */
    public function scopeUpcoming($query, $days = 7)
    {
        $today = Carbon::now();
        $endDate = $today->copy()->addDays($days);

        return $query->where('is_active', true)
            ->whereRaw("DATE_FORMAT(birthday_date, '%m-%d') BETWEEN ? AND ?", [
                $today->format('m-d'),
                $endDate->format('m-d')
            ]);
    }

    /**
     * Scope pour les anniversaires aujourd'hui
     */
    public function scopeToday($query)
    {
        $today = Carbon::now();
        return $query->where('is_active', true)
            ->whereRaw("DATE_FORMAT(birthday_date, '%m-%d') = ?", [
                $today->format('m-d')
            ]);
    }
}
