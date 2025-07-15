<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContraVoucher extends Model
{
    protected $table = 'contra_vouchers';

    protected $fillable = [
        'voucher_id',
        'bill_no',
        'date',
        'from_head',
        'from_bank',
        'to_head',
        'to_bank',
        'cash_amount',
        'cheque_no',
        'cheque_date',
        'cheque_amount',
        'online_amount',
        'online_remarks',
        'edit_status'
    ];

    public function fromBank() {
        return $this->belongsTo(DetailedHead::class, 'from_bank', 'ledgers_head_code');
    }

    public function toBank() {
        return $this->belongsTo(DetailedHead::class, 'to_bank', 'ledgers_head_code');
    }
}
