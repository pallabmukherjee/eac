<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_roles';

    protected $fillable = [
        'user_id', 'payment', 'receipt', 'contra', 'journal', 'leave', 'pension', 'gratuity',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
