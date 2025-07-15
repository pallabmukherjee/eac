<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalVoucher extends Model
{
    protected $table = 'journal_vouchers';

    protected $fillable = [
        'date',
        'voucher_id',
        'bill_no',
        'narration',
        'remarks',
        'edit_status'
    ];

    public function ledgerReports() {
        return $this->hasMany(JvLedgerHead::class, 'voucher_id', 'voucher_id');
    }
}
