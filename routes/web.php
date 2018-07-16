<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//category
Route::resource('category','categoryController');

//sub category
Route::resource('subCategory','subCategoryController');

//item
Route::resource('item','itemController');
Route::get('/fetch_sub_category', 'itemController@fetch_sub_category');

//supplier
Route::resource('supplier','supplierController');
Route::get('supplier/add_supply_item/{supplier_id}','supplierController@add_supply_item');
Route::post('supplier/add_supply_items','supplierController@add_supply_items');
Route::get('supplier/add_supply_items/{id}/{sup_id}','supplierController@remove_supply_items');

////transaction ////

//purchase order
Route::resource('purchase_order','purchaseOrderController');
Route::get('/fetch_supplier_items', 'purchaseOrderController@fetch_supplier_items');
Route::get('print_purchase_order/{print_id}','purchaseOrderController@print_purchase_order');


//grn
Route::resource('grn','grnController');
Route::get('/fetch_purchase_order_entries', 'grnController@fetch_purchase_order_entries');

// stock 
Route::resource('stock','stockController');

//sales invoice 
Route::resource('sales_invoice','saleInvoiceController');
Route::get('print_sales_invoice/{print_id}','saleInvoiceController@print_sales_invoice');

//purchase return 
Route::resource('purchase_return','purchaseReturnController');

//sales return
Route::resource('sales_return','salesReturnController');
Route::get('/fetch_sales_invoice_item', 'salesReturnController@fetch_sales_invoice_item');

///////////////

//////////Report///
Route::resource('stock_report','stockReportController');
Route::resource('purchase_order_report','purchaseOrderReport');
Route::resource('purchase_return_report','purchaseReturnReport');
Route::resource('grn_report','grnReportController');
Route::resource('sales_invoice_report','salesInvoiceReport');
Route::resource('sales_return_report','salesReturnReport');
////////

Route::get('/get_suggestions_for_select2','Select2Controller@GetSuggestionsForSelect2');