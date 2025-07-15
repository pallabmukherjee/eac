<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YearlyAccountStatus extends Model
{
    protected $table = 'yearly_account_status';

    protected $fillable = [
        'year',
    ];
}
