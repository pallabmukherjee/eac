<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinorHead extends Model
{
    protected $table = 'minor_heads';

    protected $fillable = [
        'major_head_code',
        'code',
        'name',
    ];

    public function majorHead() {
        return $this->belongsTo(MajorHead::class, 'major_head_code');
    }
}
