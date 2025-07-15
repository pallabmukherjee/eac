<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentVoucher extends Model
{

    protected $table = 'payment_vouchers';

    protected $fillable = [
        'p_voucher_id',
        'bill_no',
        'date',
        'payee',
        'scheme_fund',
        'payment_mode',
        'bank',
        'reference_number',
        'reference_date',
        'narration',
        'edit_status',
    ];

    public function beneficiary() {
        return $this->belongsTo(Beneficiary::class, 'payee');
    }

    public function schemeFund() {
        return $this->belongsTo(DetailedHead::class, 'scheme_fund', 'ledgers_head_code');
    }

    public function voucherBank() {
        return $this->hasOne(PVoucharBank::class, 'voucher_id', 'p_voucher_id');
    }

    public function ledgerReports() {
        return $this->hasMany(LedgerReport::class, 'voucher_id', 'p_voucher_id');
    }

}
