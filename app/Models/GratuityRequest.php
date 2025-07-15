<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GratuityRequest extends Model {
    protected $table = 'gratuity_requests';

    protected $fillable = [
        'request_id',
        'emp_id',
        'amount',
        'prayer_no',
        'prayer_date',
        'status',
    ];

    public function empName() {
        return $this->belongsTo(Gratuity::class, 'emp_id');
    }
}
