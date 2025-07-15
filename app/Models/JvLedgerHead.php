<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JvLedgerHead extends Model
{
    protected $table = 'jv_ledger_head';

    protected $fillable = [
        'voucher_id',
        'ledger_head',
        'amount',
        'crdr', // 1 = Credit , 2 = Debit
    ];

    public function ledgerHead() {
        return $this->belongsTo(DetailedHead::class, 'ledger_head', 'ledgers_head_code');
    }
}
