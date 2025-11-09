<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationDeleteUser extends Model
{
    use HasFactory;

    protected $table = 'notificationdeleteuser'; // Define the table name if it's not the plural of the model name

    protected $fillable = [
        'id_notification',
        'userId',
        'delete',
    ];

    // Relationships (if needed)
    public function notification()
    {
        return $this->belongsTo(Notification::class, 'id_notification');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
    
}
