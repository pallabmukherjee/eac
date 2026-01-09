<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SuperAdmin\WebsiteSettingController;
use App\Http\Controllers\SuperAdmin\UserRoleController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\Leave\LeaveController;
use App\Http\Controllers\Account\MajorHeadController;
use App\Http\Controllers\Account\MinorHeadController;
use App\Http\Controllers\Account\DetailedHeadController;
use App\Http\Controllers\Account\LedgerHeadController;
use App\Http\Controllers\Account\BeneficiaryController;
use App\Http\Controllers\Account\PanCardController;
use App\Http\Controllers\Account\PaymentVoucherController;
use App\Http\Controllers\Account\PendingActionController;
use App\Http\Controllers\Account\ReceiptVoucharController;
use App\Http\Controllers\Account\ContraVoucharController;
use App\Http\Controllers\Account\JournalVoucharController;
use App\Http\Controllers\Account\EditRequestController;
use App\Http\Controllers\Account\Report\BalanceSheetController;
use App\Http\Controllers\Account\Report\ChequeOnlineController;
use App\Http\Controllers\Account\Report\ContraSummaryController;
use App\Http\Controllers\Account\Report\JournalSummaryController;
use App\Http\Controllers\Account\Report\LedgerSummaryController;
use App\Http\Controllers\Account\Report\MajorHeadIncomeExpenditureController;
use App\Http\Controllers\Account\Report\MinorHeadIncomeExpenditureController;
use App\Http\Controllers\Account\Report\PaymentSummaryAccountHeadController;
use App\Http\Controllers\Account\Report\PaymentSummaryBenificiaryController;
use App\Http\Controllers\Account\Report\ReceiptSummaryController;
use App\Http\Controllers\Account\Report\ReportSettingsController;
use App\Http\Controllers\Pension\PensionDashboardController;
use App\Http\Controllers\Pension\PensionController;
use App\Http\Controllers\Pension\RopaYearController;
use App\Http\Controllers\Pension\PensionReportController;
use App\Http\Controllers\Pension\LifeCertificateController;
use App\Http\Controllers\Pension\PensionOtherBillController;
use App\Http\Controllers\Gratuity\GratuityController;
use App\Http\Controllers\Gratuity\LoanController;
use App\Http\Controllers\Gratuity\RequestController;
use App\Http\Controllers\Gratuity\FinancialYearController;
use App\Http\Controllers\Gratuity\GratuityReportController;
use App\Http\Controllers\Gratuity\GratuityBillController;
use App\Http\Controllers\Gratuity\GratuityRopaYearController;


Route::get('/setting', [WebsiteSettingController::class, 'setting'])->name('setting');
Route::post('/update-website-settings', [WebsiteSettingController::class, 'store'])->name('update.website.settings');

Route::prefix('user')->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('/user/status/update', [UserController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/user/create', [UserController::class, 'createUser'])->name('create');
});

Route::prefix('role')->name('role.')->group(function () {
    Route::get('/', [UserRoleController::class, 'index'])->name('index');
    Route::post('/user/update-field', [UserRoleController::class, 'updateField'])->name('updateField');
});

Route::prefix('leave')->name('leave.')->group(function () {
    Route::get('/dashboard', [LeaveController::class, 'dashboard'])->name('dashboard');

    Route::prefix('/type')->name('type.')->group(function () {
        Route::get('/add', [LeaveController::class, 'employeeTypeAdd'])->name('add');
        Route::get('/', [LeaveController::class, 'employeeTypeList'])->name('list');
        Route::post('/employee-types', [LeaveController::class, 'employeeTypeStore'])->name('store');
        Route::get('/edit/{id}', [LeaveController::class, 'employeeTypeEdit'])->name('edit');
        Route::post('/update/{id}', [LeaveController::class, 'employeeTypeUpdate'])->name('update');
        Route::delete('employeeTypes/{id}', [LeaveController::class, 'employeeTypeDestroy'])->name('destroy');
    });

    Route::prefix('/employee')->name('employee.')->group(function () {
        Route::get('/', [LeaveController::class, 'employeeLeaveList'])->name('index');
        Route::get('/add', [LeaveController::class, 'employeeLeaveAdd'])->name('add');
        Route::post('/store', [LeaveController::class, 'employeeLeaveStore'])->name('store');
        Route::get('/edit/{id}', [LeaveController::class, 'employeeLeaveEdit'])->name('edit');
        Route::post('/update/{id}', [LeaveController::class, 'employeeLeaveUpdate'])->name('update');
        Route::delete('employeeLeave/{id}', [LeaveController::class, 'employeeLeaveDestroy'])->name('destroy');
    });

    Route::prefix('/enjoyed')->name('enjoyed.')->group(function () {
        Route::get('/add/{id}', [LeaveController::class, 'leaveEnjoyedAdd'])->name('add');
        Route::post('/store', [LeaveController::class, 'leaveEnjoyedStore'])->name('store');
    });

    Route::prefix('/report')->name('report.')->group(function () {
        Route::get('/', [LeaveController::class, 'reportsIndex'])->name('index');
        Route::post('/report', [LeaveController::class, 'reportsStoreLeaveReport'])->name('report');
        Route::post('/download-report', [LeaveController::class, 'reportsDownloadReport'])->name('download');

        Route::get('/report-create', [LeaveController::class, 'reportsCreate'])->name('create');
        Route::get('/view/{report_month}', [LeaveController::class, 'reportsView'])->name('view');
        Route::put('/update', [LeaveController::class, 'reportsUpdate'])->name('update');
    });

    Route::prefix('/calendar')->name('calendar.')->group(function () {
        Route::get('/', [LeaveController::class, 'leaveCalendar'])->name('index');
        Route::post('/store', [LeaveController::class, 'storeLeaveCalendar'])->name('store');
    });
});

Route::prefix('account')->name('account.')->group(function () {
    Route::get('/', [SuperAdminDashboardController::class, 'account'])->name('account');

    Route::prefix('/majorhead')->name('majorhead.')->group(function () {
        Route::get('/', [MajorHeadController::class, 'index'])->name('index');
        Route::post('/create', [MajorHeadController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MajorHeadController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MajorHeadController::class, 'update'])->name('update');
        Route::delete('/{id}', [MajorHeadController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/minorhead')->name('minorhead.')->group(function () {
        Route::get('/', [MinorHeadController::class, 'index'])->name('index');
        Route::post('/create', [MinorHeadController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MinorHeadController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MinorHeadController::class, 'update'])->name('update');
        Route::delete('/{id}', [MinorHeadController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/detailedhead')->name('detailedhead.')->group(function () {
        Route::get('/', [DetailedHeadController::class, 'index'])->name('index');
        Route::post('/create', [DetailedHeadController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [DetailedHeadController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DetailedHeadController::class, 'update'])->name('update');
        Route::delete('/{id}', [DetailedHeadController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/ledgerhead')->name('ledgerhead.')->group(function () {
        Route::get('/', [LedgerHeadController::class, 'index'])->name('index');
        Route::post('/update-detailed-head', [LedgerHeadController::class, 'updateDetailedHead'])->name('update');
    });

    Route::prefix('/beneficiary')->name('beneficiary.')->group(function () {
        Route::get('/', [BeneficiaryController::class, 'index'])->name('index');
        Route::post('/create', [BeneficiaryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BeneficiaryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BeneficiaryController::class, 'update'])->name('update');
        Route::delete('/{id}', [BeneficiaryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/pan')->name('pan.')->group(function () {
        Route::get('/', [PanCardController::class, 'index'])->name('index');
        Route::post('/create', [PanCardController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PanCardController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PanCardController::class, 'update'])->name('update');
        Route::delete('/{id}', [PanCardController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/payment-voucher')->name('paymentvoucher.')->group(function () {
        Route::get('/', [PaymentVoucherController::class, 'index'])->name('index');
        Route::post('/create', [PaymentVoucherController::class, 'store'])->name('store');
        Route::get('/list', [PaymentVoucherController::class, 'list'])->name('list');
        Route::get('/show/{p_voucher_id}', [PaymentVoucherController::class, 'show'])->name('show');
        Route::get('/{p_voucher_id}/pdf', [PaymentVoucherController::class, 'generatePDF'])->name('generate_pdf');
        Route::get('/edit-request/{p_voucher_id}', [PaymentVoucherController::class, 'editRequest'])->name('edit.request');
        Route::get('/edit/{p_voucher_id}', [PaymentVoucherController::class, 'edit'])->name('edit');
        Route::put('/edit/{p_voucher_id}', [PaymentVoucherController::class, 'update'])->name('update');
    });

    Route::prefix('/edit-request')->name('edit.')->group(function () {
        Route::get('/', [EditRequestController::class, 'index'])->name('index');
        Route::post('/update-status', [EditRequestController::class, 'updateStatus'])->name('update-status');
    });

    Route::prefix('/pending-action')->name('pendingaction.')->group(function () {
        Route::get('/', [PendingActionController::class, 'index'])->name('index');
        Route::get('/show/{voucher_id}', [PendingActionController::class, 'show'])->name('show');
        Route::post('/create/{voucher_id}', [PendingActionController::class, 'store'])->name('store');

        Route::get('/uncleared-cheque', [PendingActionController::class, 'unclearedCheque'])->name('uncleared');
        Route::get('/uncleared-cheque/show/{voucher_id}', [PendingActionController::class, 'unclearedChequeShow'])->name('ucshow');
        Route::post('/uncleared-cheque', [PendingActionController::class, 'unclearedChequeStore'])->name('ucstore');
        Route::post('/update-status', [PendingActionController::class, 'updateStatus'])->name('updateStatus');
    });

    Route::prefix('/receipt-voucher')->name('receiptvoucher.')->group(function () {
        Route::get('/', [ReceiptVoucharController::class, 'index'])->name('index');
        Route::post('/create', [ReceiptVoucharController::class, 'store'])->name('store');
        Route::get('/list', [ReceiptVoucharController::class, 'list'])->name('list');
        Route::get('/show/{voucher_id}', [ReceiptVoucharController::class, 'show'])->name('show');
        Route::get('receipt-voucher/{voucher_id}/pdf', [ReceiptVoucharController::class, 'generatePDF'])->name('generate_pdf');

        Route::get('/edit-request/{voucher_id}', [ReceiptVoucharController::class, 'editRequest'])->name('edit.request');
        Route::get('/edit/{voucher_id}', [ReceiptVoucharController::class, 'edit'])->name('edit');
        Route::put('/edit/{voucher_id}', [ReceiptVoucharController::class, 'update'])->name('update');
    });

    Route::prefix('/contra-voucher')->name('contravoucher.')->group(function () {
        Route::get('/', [ContraVoucharController::class, 'index'])->name('index');
        Route::post('/create', [ContraVoucharController::class, 'store'])->name('store');
        Route::get('/list', [ContraVoucharController::class, 'list'])->name('list');
        Route::get('/show/{voucher_id}', [ContraVoucharController::class, 'show'])->name('show');
        Route::get('contra-voucher/{voucher_id}/pdf', [ContraVoucharController::class, 'generatePDF'])->name('generate_pdf');

        Route::get('/edit-request/{voucher_id}', [ContraVoucharController::class, 'editRequest'])->name('edit.request');
        Route::get('/edit/{voucher_id}', [ContraVoucharController::class, 'edit'])->name('edit');
        Route::put('/edit/{voucher_id}', [ContraVoucharController::class, 'update'])->name('update');
    });

    Route::prefix('/journal-voucher')->name('journalvoucher.')->group(function () {
        Route::get('/', [JournalVoucharController::class, 'index'])->name('index');
        Route::post('/create', [JournalVoucharController::class, 'store'])->name('store');
        Route::get('/list', [JournalVoucharController::class, 'list'])->name('list');
        Route::get('/show/{voucher_id}', [JournalVoucharController::class, 'show'])->name('show');
        Route::get('journal-voucher/{voucher_id}/pdf', [JournalVoucharController::class, 'generatePDF'])->name('generate_pdf');

        Route::get('/edit-request/{voucher_id}', [JournalVoucharController::class, 'editRequest'])->name('edit.request');
        Route::get('/edit/{voucher_id}', [JournalVoucharController::class, 'edit'])->name('edit');
        Route::put('/edit/{voucher_id}', [JournalVoucharController::class, 'update'])->name('update');
    });

    Route::prefix('/report')->name('report.')->group(function () {
        Route::get('/report-store', [ReportSettingsController::class, 'index'])->name('reportstore');

        Route::get('/balance-sheet', [BalanceSheetController::class, 'index'])->name('balanceSheet');
        Route::post('/balance-sheet', [BalanceSheetController::class, 'report'])->name('balanceSheetReport');

        Route::get('/cheque-online-summary', [ChequeOnlineController::class, 'index'])->name('chequeOnline');
        Route::post('/cheque-online-summary', [ChequeOnlineController::class, 'report'])->name('chequeOnlineReport');

        Route::get('/contra-summary', [ContraSummaryController::class, 'index'])->name('contraSummary');
        Route::post('/contra-summary', [ContraSummaryController::class, 'report'])->name('contraSummaryReport');

        Route::get('/major-head-income-expenditure', [MajorHeadIncomeExpenditureController::class, 'index'])->name('majorHeadIncomeExpenditure');
        Route::post('/major-head-income-expenditure', [MajorHeadIncomeExpenditureController::class, 'report'])->name('majorHeadIncomeExpenditureReport');

        Route::get('/minor-head-income-expenditure', [MinorHeadIncomeExpenditureController::class, 'index'])->name('minorHeadIncomeExpenditure');
        Route::post('/minor-head-income-expenditure', [MinorHeadIncomeExpenditureController::class, 'report'])->name('minorHeadIncomeExpenditureReport');

        Route::get('/journal-summary', [JournalSummaryController::class, 'index'])->name('journalSummary');
        Route::post('/journal-summary', [JournalSummaryController::class, 'report'])->name('journalSummaryReport');

        Route::get('/ledgersummary', [LedgerSummaryController::class, 'index'])->name('ledgerSummary');
        Route::post('/ledgersummary', [LedgerSummaryController::class, 'report'])->name('ledgerSummaryReport');

        Route::get('/payment-summary-account-head', [PaymentSummaryAccountHeadController::class, 'index'])->name('paymentSummaryAccountHead');
        Route::post('/payment-summary-account-head', [PaymentSummaryAccountHeadController::class, 'report'])->name('paymentSummaryAccountHeadReport');

        Route::get('/payment-summary-benificiary', [PaymentSummaryBenificiaryController::class, 'index'])->name('paymentSummaryBenificiary');
        Route::post('/payment-summary-benificiary', [PaymentSummaryBenificiaryController::class, 'report'])->name('benificiaryReport');

        Route::get('/receipt-summary', [ReceiptSummaryController::class, 'index'])->name('receiptSummary');
        Route::post('/receipt-summary', [ReceiptSummaryController::class, 'report'])->name('receiptSummaryReport');
    });
});

Route::prefix('pension')->name('pension.')->group(function () {
    Route::get('/dashboard', [PensionDashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [PensionController::class, 'index'])->name('index');
    Route::get('/create', [PensionController::class, 'create'])->name('create');
    Route::post('/create', [PensionController::class, 'store'])->name('store');
    Route::get('/export', [PensionController::class, 'export'])->name('export');
    Route::get('/export-pdf', [PensionController::class, 'exportPdf'])->name('exportPdf');
    Route::get('/edit/{id}', [PensionController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [PensionController::class, 'update'])->name('update');
    Route::post('/update-life-certificate', [PensionController::class, 'updateLifeCertificate'])->name('updateLifeCertificate');
    Route::get('/download-life-certificate/{id}', [PensionController::class, 'downloadLifeCertificate'])->name('downloadLifeCertificate');

    Route::prefix('/report')->name('report.')->group(function () {
        Route::get('/', [PensionReportController::class, 'index'])->name('index');
        Route::get('/report-create', [PensionReportController::class, 'create'])->name('create');
        Route::post('/report', [PensionReportController::class, 'store'])->name('store');
        Route::get('/report-show/{report_id}', [PensionReportController::class, 'show'])->name('show');
        Route::get('/report-edit/{report_id}', [PensionReportController::class, 'edit'])->name('edit');
        Route::post('/report-update/{report_id}', [PensionReportController::class, 'update'])->name('update');
        Route::get('/report-pdf/{report_id}', [PensionReportController::class, 'pdf'])->name('pdf');
        Route::get('/report-csv/{report_id}', [PensionReportController::class, 'csv'])->name('csv');
        Route::delete('/report-delete/{report_id}', [PensionReportController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/life')->name('life.')->group(function () {
        Route::get('/', [LifeCertificateController::class, 'index'])->name('index');
    });

    Route::prefix('/ropa')->name('ropa.')->group(function () {
        Route::get('/', [RopaYearController::class, 'index'])->name('index');
        Route::post('/create', [RopaYearController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RopaYearController::class, 'edit'])->name('edit');
        Route::post('/{id}', [RopaYearController::class, 'update'])->name('update');
    });

    Route::prefix('/other-bill')->name('other.')->group(function () {
        Route::get('/', [PensionOtherBillController::class, 'index'])->name('index');
        Route::get('/report-create', [PensionOtherBillController::class, 'create'])->name('create');
        Route::post('/report', [PensionOtherBillController::class, 'store'])->name('store');
        Route::get('/report-show/{report_id}', [PensionOtherBillController::class, 'show'])->name('show');
        Route::get('/report-edit/{report_id}', [PensionOtherBillController::class, 'edit'])->name('edit');
        Route::post('/report-update/{report_id}', [PensionOtherBillController::class, 'update'])->name('update');
        Route::get('/report-pdf/{report_id}', [PensionOtherBillController::class, 'pdf'])->name('pdf');
        Route::get('/report-csv/{report_id}', [PensionOtherBillController::class, 'csv'])->name('csv');
    });

    Route::prefix('/signs')->name('signs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Pension\SigningAuthorityController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Pension\SigningAuthorityController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Pension\SigningAuthorityController::class, 'store'])->name('store');
        Route::get('/{sign}/edit', [\App\Http\Controllers\Pension\SigningAuthorityController::class, 'edit'])->name('edit');
        Route::put('/{sign}', [\App\Http\Controllers\Pension\SigningAuthorityController::class, 'update'])->name('update');
        Route::delete('/{sign}', [\App\Http\Controllers\Pension\SigningAuthorityController::class, 'destroy'])->name('destroy');
    });
});


Route::prefix('gratuity')->name('gratuity.')->group(function () {
    Route::get('/', [GratuityReportController::class, 'index'])->name('index');
    Route::get('/add', [GratuityController::class, 'add'])->name('add');
    Route::post('/create', [GratuityController::class, 'store'])->name('store');
    Route::get('/list', [GratuityController::class, 'list'])->name('list');
    Route::get('/export', [GratuityController::class, 'export'])->name('export');
    Route::get('/export-pdf', [GratuityController::class, 'exportPdf'])->name('exportPdf');
    Route::get('/edit/{id}', [GratuityController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [GratuityController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [GratuityController::class, 'destroy'])->name('destroy');
    Route::get('/search-employees', [GratuityController::class, 'searchEmployees'])->name('search.employees');
    Route::get('/get-employee-details/{id}', [GratuityController::class, 'getEmployeeDetails'])->name('employee.details');

    Route::prefix('/loan')->name('loan.')->group(function () {
        Route::get('/', [LoanController::class, 'index'])->name('index');
        Route::post('/create', [LoanController::class, 'store'])->name('store');
        Route::get('/loan/edit/{id}', [LoanController::class, 'edit'])->name('edit');
        Route::post('/loan/update/{empCode}', [LoanController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [LoanController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/financial-year')->name('financial.')->group(function () {
        Route::get('/', [FinancialYearController::class, 'index'])->name('index');
        Route::post('/create', [FinancialYearController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [FinancialYearController::class, 'edit'])->name('edit');
        Route::post('/{id}', [FinancialYearController::class, 'update'])->name('update');
    });

    Route::prefix('/ropa-year')->name('ropa.')->group(function () {
        Route::get('/', [GratuityRopaYearController::class, 'index'])->name('index');
        Route::post('/create', [GratuityRopaYearController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [GratuityRopaYearController::class, 'edit'])->name('edit');
        Route::post('/{id}', [GratuityRopaYearController::class, 'update'])->name('update');
    });

    Route::prefix('/report')->name('report.')->group(function () {
        Route::get('/', [GratuityReportController::class, 'index'])->name('index');
        Route::get('/yearly', [GratuityReportController::class, 'yearlyPaid'])->name('yearly');
        Route::get('/monthly', [GratuityReportController::class, 'monthlyPaid'])->name('monthly');
        Route::get('/employee-wise', [GratuityReportController::class, 'employeeWise'])->name('employee.wise');
    });

    Route::prefix('/bill')->name('bill.')->group(function () {
        Route::get('/', [GratuityBillController::class, 'index'])->name('index');
        Route::get('/create', [GratuityBillController::class, 'create'])->name('create');
        Route::post('/create', [GratuityBillController::class, 'store'])->name('store');
        Route::get('/bill-show/{bill_id}', [GratuityBillController::class, 'show'])->name('show');
        Route::get('/bill-edit/{bill_id}', [GratuityBillController::class, 'edit'])->name('edit');
        Route::put('/bill-update/{bill_id}', [GratuityBillController::class, 'update'])->name('update');
        Route::get('/bill-pdf/{bill_id}', [GratuityBillController::class, 'pdf'])->name('pdf');
        Route::delete('/bill-delete/{bill_id}', [GratuityBillController::class, 'destroy'])->name('destroy');
        Route::get('/bill-approve/{bill_id}', [GratuityBillController::class, 'approve'])->name('approve');
        Route::post('/bill-approve-confirm/{bill_id}', [GratuityBillController::class, 'confirmApprove'])->name('approve.confirm');
    });
});
