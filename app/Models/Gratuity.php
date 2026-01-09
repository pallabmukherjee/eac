<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gratuity extends Model {

    protected $table = 'gratuity';

    protected $fillable = [
        'name',
        'relation_name',
        'employee_code',
        'ppo_number',
        'ppo_receive_date',
        'retire_dead',
        'retirement_date',
        'financial_year',
        'alive_status',
        'loan_status',
        'relation_died',
        'warrant_name',
        'warrant_adhar_no',
        'ppo_amount',
        'sanctioned_amount',
        'ropa_year',
        'bank_ac_no',
        'ifsc',
    ];
    public function financialYear() {
        return $this->belongsTo(FinancialYear::class, 'financial_year');
    }

    public function ropaYear() {
        return $this->belongsTo(GratuityRopaYear::class, 'ropa_year');
    }
}
