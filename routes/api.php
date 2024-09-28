<?php

use Illuminate\Http\Request;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization, Client-Security-Token, Accept-Encoding');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/common/single-row-list', 'ApiInterfaceController@getWebRow');
Route::post('/common/multiple-rows-list', 'ApiInterfaceController@getWebRows');
Route::get('customers/get-customers-list', 'ApiInterfaceController@getWebCustomersList');
Route::get('customers/get-customer-row', 'ApiInterfaceController@getWebCustomerDetail');
Route::get('customers/get-divisioncodes', 'ApiInterfaceController@getWebDivisionsCodeList');
Route::get('customers/get-parent-product-category','ApiInterfaceController@getWebParentProductCategory');
Route::post('customers/get-standard-product-category', 'ApiInterfaceController@getWebStandardProductCategory');
Route::post('customers/trf-sub-product-category', 'ApiInterfaceController@getWebSubProductCategory');
Route::post('customer/trf-product-data-list', 'ApiInterfaceController@getWebProductNameList');
Route::post('customers/get-related-Customer-list', 'ApiInterfaceController@getWebRelatedCustomersList');
Route::post('customers/get-trf-storage-condition-list', 'ApiInterfaceController@getWebTrfStorageConditionList');
Route::post('customer/trf-product-test-master-data-list', 'ApiInterfaceController@getWebProductTestMasterList');
Route::post('customer/trf-save-customer-trf-erp', 'ApiInterfaceController@createTrfInErp');
Route::post('customer/trf-update-customer-trf-erp', 'ApiInterfaceController@updateTrfInErp');
Route::post('customer/trf-update-trf-status-erp', 'ApiInterfaceController@updateTrfStatusInErp');
Route::get('customers/get-brand-type-list', 'ApiInterfaceController@getBrandTypeListInErp');
