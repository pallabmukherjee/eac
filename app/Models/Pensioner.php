<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pensioner extends Model {
    protected $table = 'pensioners';

    protected $fillable = [
        'pensioner_name',
        'pension_type',
        'family_name',
        'dob',
        'ppo_date',
        'retirement_date',
        'alive_status',
        'no_claimant',
        'death_date',
        'life_certificate',
        'five_year_date',
        'five_year_completed',
        'employee_code',
        'ppo_number',
        'ropa_year',
        'aadhar_number',
        'savings_account_number',
        'ifsc_code',
        'basic_pension',
        'dr_percentage',
        'medical_allowance',
        'other_allowance',
    ];

    public function ropa() {
        return $this->belongsTo(RopaYear::class, 'ropa_year');
    }
}
