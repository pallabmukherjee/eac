<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptVoucher extends Model
{
    protected $table = 'receipt_vouchers';

    protected $fillable = [
        'voucher_id',
        'bill_no',
        'edit_status',
        'date',
    ];

    public function rvLedgerHeads() {
        return $this->hasMany(RvLedgerHead::class, 'voucher_id', 'voucher_id');
    }
}
