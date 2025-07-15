<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RvChequeList extends Model
{
    protected $table = 'rv_cheque_list';

    protected $fillable = [
        'voucher_id',
        'bill_no',
        'depositor_name',
        'bank_name',
        'cheque_no',
        'amount',
        'cheque_submit_bank',
        'status',
    ];
}
