<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YearlyAccountBalence extends Model {

    protected $table = 'yearly_account_balence';
    protected $primaryKey = 'id';

    protected $fillable = [
        'year',
        'ledgers_head_code',
        'major_head_code',
        'minor_head_code',
        'opening_debit',
        'opening_credit',
        'closing_debit',
        'closing_credit',
    ];

}
