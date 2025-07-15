<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerReport extends Model
{
    protected $table = 'ledger_reports';

    protected $fillable = [
        'voucher_id',
        'ledger',
        'amount',
        'pay_deduct',  // 1 = Pay , 2 = Deduct
    ];

    public function ledgername() {
        return $this->belongsTo(DetailedHead::class, 'ledger', 'ledgers_head_code');
    }
}
