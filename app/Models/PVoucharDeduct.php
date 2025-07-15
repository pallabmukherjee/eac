<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PVoucharDeduct extends Model
{
    protected $table = 'p_vouchar_deduct';
    protected $primaryKey = 'id';

    protected $fillable = [
        'voucher_id',
        'bill_no',
        'ledger_head',
        'amount',
    ];
}
