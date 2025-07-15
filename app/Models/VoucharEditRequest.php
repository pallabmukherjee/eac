<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucharEditRequest extends Model
{
    protected $table = 'vouchar_edit_requests';

    protected $fillable = [
        'vouchar_id',
        'bill_no',
        'user_id',
        'edit_status',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function pvoucher() {
        return $this->belongsTo(PaymentVoucher::class, 'voucher_id', 'p_voucher_id');
    }

    public function rvoucher() {
        return $this->belongsTo(ReceiptVoucher::class, 'voucher_id', 'voucher_id');
    }

    public function jvoucher() {
        return $this->belongsTo(JournalVoucher::class, 'voucher_id', 'voucher_id');
    }

    public function cvoucher() {
        return $this->belongsTo(ContraVoucher::class, 'voucher_id', 'voucher_id');
    }
}
