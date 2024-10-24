<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyPosController;
use App\Http\Controllers\CompanySerialController;
use App\Http\Controllers\ExpenseProviderController;
use App\Http\Controllers\ExpenseServiceController;
use App\Http\Controllers\ExpenseStaffController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\MainBoxController;
use App\Http\Controllers\OtherPayController;
use App\Http\Controllers\PayBoxController;
use App\Http\Controllers\PayBoxExpenseController;
use App\Http\Controllers\PayBoxIncomeController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PosExpenseController;
use App\Http\Controllers\PosIncomeController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SalesDetailController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\SaleSplitController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TipsPercentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/categories', CategoryController::class)->names('categories');    

    Route::resource('/places', PlaceController::class)->names('places');    

    Route::resource('/tables', TableController::class)->names('tables');    

    Route::resource('/products', ProductController::class)->names('products');    

    Route::resource('/sales', SaleController::class)->names('sales');
    
    Route::get('/sale/available', [SaleController::class, 'available'])->name('sales.available');    
    Route::get('/sale/tablelist', [SaleController::class, 'tablelist'])->name('sales.tablelist');  
    Route::post('/table/clean/{tableId}', [TableController::class, 'clean'])->name('table.clean'); 
    Route::post('/sale/takeorder', [SaleController::class, 'takeorder'])->name('sales.takeorder'); 
    Route::get('/table/clear/{saleId}', [TableController::class, 'clear'])->name('table.clear');   

    Route::get('/sale/{saleId}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sale/pdf/{sale}/{discount}', [SaleController::class, 'pdf'])->name('sales.pdf');
    Route::get('/sale/print/{sale}/{discount}/{withcash}', [SaleController::class, 'print'])->name('sales.print');
    Route::get('/sale/change/{sale}/{discount}/{withcash}', [SaleController::class, 'change'])->name('sales.change');
    Route::get('/sale/order/{saleId}', [SaleController::class, 'order'])->name('sales.order');
    Route::post('/sale/changetable', [SaleController::class, 'changetable'])->name('sales.changetable');
    Route::post('/sale/sendticket', [SaleController::class, 'sendticket'])->name('sales.sendticket');
    Route::post('/sale/update', [SaleController::class, 'update'])->name('sales.update');
    Route::post('/sale/sendboleta', [SaleController::class, 'sendboleta'])->name('sales.sendboleta');
    Route::post('/sale/sendfactura', [SaleController::class, 'sendfactura'])->name('sales.sendfactura');
    Route::post('/sale/addtips', [SaleController::class, 'addtips'])->name('sales.addtips');
    Route::post('/sale/reprint', [SaleController::class, 'reprint'])->name('sales.reprint');

    Route::post('/salesdetail/add', [SalesDetailController::class, 'add'])->name('salesdetail.add');
    Route::post('/salesdetail/edit', [SalesDetailController::class, 'edit'])->name('salesdetail.edit');
    Route::post('/salesdetail/remove/{saleDetailId}', [SalesDetailController::class, 'remove'])->name('salesdetail.remove');
    Route::post('/salesdetail/remove/{saleDetailId}/{saveHistory}', [SalesDetailController::class, 'remove'])->name('salesdetail.remove');
    Route::get('/salesdetail/print/{saleDetailId}', [SalesDetailController::class, 'print'])->name('salesdetail.print');
    Route::get('/salesdetail/list/{saleId}', [SalesDetailController::class, 'list'])->name('salesdetail.list');
    Route::get('/salesdetail/splitlist/{saleId}', [SalesDetailController::class, 'splitlist'])->name('salesdetail.splitlist');
    Route::post('/salesdetail/addsale', [SalesDetailController::class, 'addsale'])->name('salesdetail.addsale');
    Route::post('/salesdetail/adddetail', [SalesDetailController::class, 'adddetail'])->name('salesdetail.adddetail');
    Route::post('/salesdetail/removedetail', [SalesDetailController::class, 'removedetail'])->name('salesdetail.removedetail');
    Route::post('/salesdetail/sendticket', [SalesDetailController::class, 'sendticket'])->name('salesdetail.sendticket');
    Route::post('/salesdetail/senddocument', [SalesDetailController::class, 'senddocument'])->name('salesdetail.senddocument');

    Route::get('/salelist', [SaleController::class, 'list'])->name('salelist.list');
    
    Route::get('/detail/{saleId}', [SaleController::class, 'detail'])->name('salelist.detail');
    Route::get('/report/detail/{saleId}', [SaleController::class, 'detailorder'])->name('reports.detail');
    Route::get('/report/split/{saleId}', [SaleController::class, 'split'])->name('reports.split');
    Route::get('/report/history/{saleId}', [SaleController::class, 'history'])->name('reports.history');

    Route::get('/sale/nullify/{saleId}', [SaleController::class, 'nullify'])->name('sales.nullify');
    Route::post('/sale/cancelsale/{saleId}', [SaleController::class, 'cancelsale'])->name('sales.cancelsale');
    Route::get('/sale/removesale/{saleId}/{mainId}/', [SaleController::class, 'removesale'])->name('sales.removesale');

    Route::get('/report/sales', [ReportController::class, 'sales'])->name('report.sales');
    Route::post('/report/saleslist', [ReportController::class, 'saleslist'])->name('report.saleslist');
    Route::get('/report/lastorders', [ReportController::class, 'lastorders'])->name('report.lastorders');
    Route::post('/report/lastorderslist', [ReportController::class, 'lastorderslist'])->name('report.lastorderslist');
    Route::get('/report/payboxsales', [ReportController::class, 'payboxsales'])->name('report.payboxsales');
    Route::get('/report/tips', [ReportController::class, 'tips'])->name('report.tips');
    Route::post('/report/tipslist', [ReportController::class, 'tipslist'])->name('report.tipslist');
    Route::get('/report/receivable', [ReportController::class, 'receivable'])->name('report.receivable');
    Route::post('/report/receivablelist', [ReportController::class, 'receivablelist'])->name('report.receivablelist');
    Route::post('/report/receivableadd', [ReportController::class, 'receivableadd'])->name('report.receivableadd');
    Route::post('/report/sunat/{saleId}/{sunat}', [ReportController::class, 'sunat'])->name('report.sunat');
    Route::post('/report/isforeign/{saleId}/{isforeign}', [ReportController::class, 'isforeign'])->name('report.isforeign');
    Route::get('/report/topfood/{daterange}/{top}/{incharge}', [ReportController::class, 'topfood'])->name('report.topfood');
    Route::get('/report/salesporcobrar', [ReportController::class, 'salesporcobrar'])->name('report.salesporcobrar');
    Route::get('/report/productchart', [ReportController::class, 'productchart'])->name('report.productchart');
    Route::post('/report/productlist', [ReportController::class, 'productlist'])->name('report.productlist');
    Route::post('/report/saleschartlist', [ReportController::class, 'saleschartlist'])->name('report.saleschartlist');
    Route::get('/report/notifications', [ReportController::class, 'notifications'])->name('report.notifications');
    Route::get('/report/saleschart', [ReportController::class, 'saleschart'])->name('report.saleschart');
    Route::post('/report/salesreport', [ReportController::class, 'salesreport'])->name('report.salesreport');
    Route::post('/report/salesreport2', [ReportController::class, 'salesreport2'])->name('report.salesreport2');
    Route::post('/report/salesreport3', [ReportController::class, 'salesreport3'])->name('report.salesreport3');
    Route::get('/report/expensechart', [ReportController::class, 'expensechart'])->name('report.expensechart');
    Route::post('/report/expensereport', [ReportController::class, 'expensereport'])->name('report.expensereport');

    
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/list', [UserController::class, 'list'])->name('user.list');
    Route::post('/user/add', [UserController::class, 'add'])->name('user.add');
    Route::post('/user/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/remove/{userId}', [UserController::class, 'remove'])->name('user.remove');

    Route::get('/provider', [ProviderController::class, 'index'])->name('provider.index');
    Route::post('/provider/add', [ProviderController::class, 'add'])->name('provider.add');
    Route::post('/provider/edit', [ProviderController::class, 'edit'])->name('provider.edit');
    Route::post('/provider/remove/{providerId}', [ProviderController::class, 'remove'])->name('provider.remove');
    Route::get('/provider/list', [ProviderController::class, 'list'])->name('provider.list');
    Route::get('/provider/detail/{providerId}', [ProviderController::class, 'detail'])->name('provider.detail');
    Route::post('/provider/listpayments', [ProviderController::class, 'listpayments'])->name('provider.listpayments');

    Route::get('/paybox', [PayBoxController::class, 'index'])->name('paybox.index');
    Route::post('/paybox/add', [PayboxController::class, 'add'])->name('paybox.add');
    Route::post('/paybox/edit', [PayboxController::class, 'edit'])->name('paybox.edit');
    Route::get('/paybox/initbox', [PayboxController::class, 'initbox'])->name('paybox.initbox');
    Route::get('/paybox/list', [PayboxController::class, 'list'])->name('paybox.list');
    Route::get('/paybox/detail/{payboxId}', [PayboxController::class, 'detail'])->name('paybox.detail');
    Route::post('/paybox/close', [PayboxController::class, 'close'])->name('paybox.close');
    Route::get('/paybox/verifyopen', [PayboxController::class, 'verifyopen'])->name('paybox.verifyopen');
    Route::get('/paybox/show/{payboxId}', [PayboxController::class, 'show'])->name('paybox.show');

    Route::get('/clients', [ClientController::class, 'index'])->name('client.index');
    Route::post('/clients/add', [ClientController::class, 'add'])->name('client.add');
    Route::post('/clients/edit', [ClientController::class, 'edit'])->name('client.edit');
    Route::post('/clients/remove/{clientId}', [ClientController::class, 'remove'])->name('client.remove');
    Route::get('/clients/list', [ClientController::class, 'list'])->name('client.list');
    Route::get('/clients/detail/{clientId}', [ClientController::class, 'detail'])->name('client.detail');
    Route::post('/clients/listpayments', [ClientController::class, 'listpayments'])->name('client.listpayments');

    Route::post('/split/add/{saleId}', [SaleSplitController::class, 'add'])->name('salesplit.add');

    Route::get('/income/{payboxId}', [IncomeController::class, 'index'])->name('income.index');
    Route::post('/income/add', [IncomeController::class, 'add'])->name('income.add');
    Route::get('/income/list/{payboxId}', [IncomeController::class, 'list'])->name('income.list');
    Route::post('/income/remove/{incomeId}', [IncomeController::class, 'remove'])->name('income.remove');

    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff/add', [StaffController::class, 'add'])->name('staff.add');
    Route::post('/staff/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::post('/staff/remove/{staffId}', [StaffController::class, 'remove'])->name('staff.remove');
    Route::get('/staff/list', [StaffController::class, 'list'])->name('staff.list');
    Route::get('/staff/detail/{staffId}', [StaffController::class, 'detail'])->name('staff.detail');
    Route::post('/staff/addexpense', [StaffController::class, 'addexpense'])->name('staff.addexpense');
    Route::post('/staff/listexpense', [StaffController::class, 'listexpense'])->name('staff.listexpense');
    Route::post('/staff/removeexpense/{posExpenseId}', [StaffController::class, 'removeexpense'])->name('staff.removeexpense');
    Route::post('/staff/editexpense', [StaffController::class, 'editexpense'])->name('staff.editexpense');

    Route::post('/expenseprovider/add', [ExpenseProviderController::class, 'add'])->name('expenseprovider.add');
    Route::get('/expenseprovider/list/{payboxId}', [ExpenseProviderController::class, 'list'])->name('expenseprovider.list');
    Route::post('/expenseprovider/remove/{expenseProviderId}', [ExpenseProviderController::class, 'remove'])->name('expenseprovider.remove');
    Route::post('/expensestaff/add', [ExpenseStaffController::class, 'add'])->name('expensestaff.add');
    Route::get('/expensestaff/list/{payboxId}', [ExpenseStaffController::class, 'list'])->name('expensestaff.list');
    Route::post('/expensestaff/remove/{expenseStaffId}', [ExpenseStaffController::class, 'remove'])->name('expensestaff.remove');
    Route::post('/expenseservice/add', [ExpenseServiceController::class, 'add'])->name('expenseservice.add');
    Route::get('/expenseservice/list/{payboxId}', [ExpenseServiceController::class, 'list'])->name('expenseservice.list');
    Route::post('/expenseservice/remove/{expenseServiceId}', [ExpenseServiceController::class, 'remove'])->name('expenseservice.remove');

    Route::get('/company', [CompanyController::class, 'index'])->name('company.index');
    Route::post('/company/add', [CompanyController::class, 'add'])->name('company.add');
    Route::post('/company/store', [CompanyController::class, 'store'])->name('company.store');
    
    Route::get('/companyserial/list/{serietype}', [CompanySerialController::class, 'list'])->name('companyserial.list');
    Route::post('/companyserial/store', [CompanySerialController::class, 'store'])->name('companyserial.store');
    Route::post('/companyserial/adddebug', [CompanySerialController::class, 'adddebug'])->name('companyserial.adddebug');
    Route::get('/companyserial/verify/{serietype}/{serie}', [CompanySerialController::class, 'verify'])->name('companyserial.verify');

    Route::get('/companypos', [CompanyPosController::class, 'index'])->name('companypos.index');
    Route::post('/companypos/add', [CompanyPosController::class, 'add'])->name('companypos.add');
    Route::post('/companypos/edit', [CompanyPosController::class, 'edit'])->name('companypos.edit');
    Route::post('/companypos/remove/{companyPosId}', [CompanyPosController::class, 'remove'])->name('companypos.remove');
    Route::get('/companypos/list', [CompanyPosController::class, 'list'])->name('companypos.list');
    Route::get('/companypos/detail/{companyPosId}', [CompanyPosController::class, 'detail'])->name('companypos.detail');

    Route::post('/tipspercent/add', [TipsPercentController::class, 'add'])->name('tipspercent.add');
    Route::post('/tipspercent/edit', [TipsPercentController::class, 'edit'])->name('tipspercent.edit');
    Route::post('/tipspercent/remove/{tipsPercentId}', [TipsPercentController::class, 'remove'])->name('tipspercent.remove');
    Route::get('/tipspercent/list', [TipsPercentController::class, 'list'])->name('tipspercent.list');

    Route::post('/posincome/add', [PosIncomeController::class, 'add'])->name('posincome.add');
    Route::post('/posincome/edit', [PosIncomeController::class, 'edit'])->name('posincome.edit');
    Route::post('/posincome/remove/{posIncomeId}', [PosIncomeController::class, 'remove'])->name('posincome.remove');
    Route::post('/posincome/list', [PosIncomeController::class, 'list'])->name('posincome.list');

    Route::post('/posexpense/add', [PosExpenseController::class, 'add'])->name('posexpense.add');
    Route::post('/posexpense/edit', [PosExpenseController::class, 'edit'])->name('posexpense.edit');
    Route::post('/posexpense/remove/{posExpenseId}', [PosExpenseController::class, 'remove'])->name('posexpense.remove');
    Route::post('/posexpense/list', [PosExpenseController::class, 'list'])->name('posexpense.list');

    Route::get('/otherpay', [OtherPayController::class, 'index'])->name('otherpay.index');
    Route::post('/otherpay/add', [OtherPayController::class, 'add'])->name('otherpay.add');
    Route::post('/otherpay/edit', [OtherPayController::class, 'edit'])->name('otherpay.edit');
    Route::post('/otherpay/remove/{otherpayId}', [OtherPayController::class, 'remove'])->name('otherpay.remove');
    Route::get('/otherpay/list', [OtherPayController::class, 'list'])->name('otherpay.list');
    Route::get('/otherpay/detail/{otherpayId}', [OtherPayController::class, 'detail'])->name('otherpay.detail');
    Route::post('/otherpay/listexpense', [OtherPayController::class, 'listexpense'])->name('otherpay.listexpense');
    
    Route::post('/payboxincome/add', [PayBoxIncomeController::class, 'add'])->name('payboxincome.add');
    Route::post('/payboxincome/edit', [PayBoxIncomeController::class, 'edit'])->name('payboxincome.edit');
    Route::post('/payboxincome/remove/{payboxIncomeId}', [PayBoxIncomeController::class, 'remove'])->name('payboxincome.remove');
    Route::get('/payboxincome/list/{payboxId}', [PayBoxIncomeController::class, 'list'])->name('payboxincome.list');

    Route::post('/payboxexpense/add', [PayBoxExpenseController::class, 'add'])->name('payboxexpense.add');
    Route::post('/payboxexpense/edit', [PayBoxExpenseController::class, 'edit'])->name('payboxexpense.edit');
    Route::post('/payboxexpense/remove/{payboxExpenseId}', [PayBoxExpenseController::class, 'remove'])->name('payboxexpense.remove');
    Route::get('/payboxexpense/list/{payboxId}', [PayBoxExpenseController::class, 'list'])->name('payboxexpense.list');

    Route::get('/mainbox', [MainBoxController::class, 'index'])->name('mainbox.index');
    Route::post('/mainbox/add', [MainBoxController::class, 'add'])->name('mainbox.add');
    Route::post('/mainbox/edit', [MainBoxController::class, 'edit'])->name('mainbox.edit');
    Route::post('/mainbox/list', [MainBoxController::class, 'list'])->name('mainbox.list');
    Route::post('/mainbox/addexpense', [MainBoxController::class, 'addexpense'])->name('mainbox.addexpense');
    Route::post('/mainbox/editexpense', [MainBoxController::class, 'editexpense'])->name('mainbox.editexpense');
    Route::post('/mainbox/remove/{mainboxId}', [MainBoxController::class, 'remove'])->name('mainbox.remove');

    Route::get('/service', [ServiceController::class, 'index'])->name('service.index');
    Route::post('/service/add', [ServiceController::class, 'add'])->name('service.add');
    Route::post('/service/edit', [ServiceController::class, 'edit'])->name('service.edit');
    Route::post('/service/remove/{serviceId}', [ServiceController::class, 'remove'])->name('service.remove');
    Route::get('/service/list', [ServiceController::class, 'list'])->name('service.list');
    Route::get('/service/detail/{serviceId}', [ServiceController::class, 'detail'])->name('service.detail');
    Route::post('/service/listexpense', [ServiceController::class, 'listexpense'])->name('service.listexpense');
});

require __DIR__.'/auth.php';

Auth::routes();