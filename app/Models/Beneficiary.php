<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model {

    protected $table = 'beneficiaries';

    protected $fillable = [
        'name',
        'party_code',
        'mobile',
        'pan_card',
        'gst',
        'aadhaar_no',
        'pan_category',
        'address',
        'bank_name',
        'ifsc_code',
        'account_no',
    ];

    public function pancard() {
        return $this->belongsTo(PanCard::class, 'pan_category');
    }
}
