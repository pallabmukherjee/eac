<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PVoucharBank extends Model
{
    protected $table = 'p_vouchar_bank';

    protected $fillable = [
        'voucher_id',
        'bank',
        'cheque_no',
        'date',
    ];

    public function paymentVoucher() {
        return $this->belongsTo(PaymentVoucher::class, 'voucher_id', 'p_voucher_id');
    }
}
