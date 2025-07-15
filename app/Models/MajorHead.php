<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MajorHead extends Model
{

    protected $table = 'major_heads';

    protected $fillable = [
        'code',
        'name',
        'schedule_reference_no',
    ];
}
