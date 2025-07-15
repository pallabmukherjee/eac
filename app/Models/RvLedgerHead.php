<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RvLedgerHead extends Model
{
    protected $table = 'rv_ledger_head';

    protected $fillable = [
        'voucher_id',
        'ledger_head',
        'cash_head',
        'cash_amount',
        'cheque_amount',
        'online_head',
        'online_amount',
        'remarks',
    ];

    public function ledgerHead() {
        return $this->belongsTo(DetailedHead::class, 'ledger_head', 'ledgers_head_code');
    }

}
