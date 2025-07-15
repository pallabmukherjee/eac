<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailedHead extends Model
{
    protected $table = 'detailed_heads';

    protected $fillable = [
        'ledgers_head_code',
        'major_head_code',
        'minor_head_code',
        'code',
        'name',
        'opening_debit',
        'opening_credit',
        'debit_amount',
        'credit_amount',
        'closing_debit',
        'closing_credit',
    ];

    public function majorHead() {
        return $this->belongsTo(MajorHead::class, 'major_head_code');
    }

    public function minorHead() {
        return $this->belongsTo(MinorHead::class, 'minor_head_code');
    }
}
