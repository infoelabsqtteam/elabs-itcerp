<?php

/*****************************************************
 *Web Routes File
 *Created By:Praveen-Singh
 *Created On : 15-Dec-2017
 *Modified On : 10-Oct-2018
 *Package : ITC-ERP-PKL
 ******************************************************/

//Auth Check
Auth::routes();

//Login Section
Route::get('/', 'DashboardController@index');
Route::get('dashboard', 'DashboardController@index');
Route::get('home', 'DashboardController@index');
Route::get('logout', 'Auth\LoginController@logout');
Route::get('validate-auth-session', 'Auth\SessionValidatorController@validateAuthSession');
Route::get('profile/my-account', 'AccountSettingController@index');
Route::get('multi-session-error', 'DashboardController@multiSessionError');
Route::post('profile/get-account-details', 'AccountSettingController@getAccountDetails');
Route::post('profile/update-account', 'AccountSettingController@updateAccount');
Route::get('testing', 'TestingController@testing');
Route::post('get-product-category-tree-view', 'ProductCategoryController@getProductCategoryTree');
Route::post('get-parameter-category-tree-view', 'TestParameterCategoryController@getParameterCategoryTree');
Route::get('standard-wise-product/auto-get-parameter-list/', 'DropdownController@getAllParameterListByParameterName');  //update standard-wise-product list
Route::post('roles/get-users-roles', 'DropdownController@getAllUserRolesList');
Route::post('profile/switch-user-role', 'AccountSettingController@switchUserAssignedRole');


//Route::get('send-test-mail', 'DashboardController@sendTestMail');

//Change-Expiry Password
Route::get('profile/change-password', 'AccountSettingController@index');
Route::post('profile/update_password', 'AccountSettingController@updatePassword');
Route::get('change-password', 'AccountSettingController@changePassword');
Route::get('expiry-password', 'AccountSettingController@expiryPassword');
Route::post('change-expiry-password', 'AccountSettingController@processResetPassword');

//dropdowns
Route::post('cities', 'DropdownController@getCitiesCodeList');
Route::post('statesList', 'DropdownController@getStatesList');
Route::post('countries', 'DropdownController@getCountriesList');
Route::post('departments', 'DropdownController@getdepartmentList');
Route::post('company_types', 'DropdownController@getCompanyTypesList');
Route::post('ownership_types', 'DropdownController@getOwnershipTypesList');
Route::post('itemsList', 'DropdownController@getInventoryItemsList');
Route::post('customer-billing-types-list', 'DropdownController@customerbillingTypes');
Route::post('customer-invoicing-types-list', 'DropdownController@customerInvoicingTypes');
Route::post('customer-types-list', 'DropdownController@customerTypes');
Route::post('discount-types-list', 'DropdownController@discountTypes');
Route::post('test-parameters-acc-dept/{dept_id}', 'DropdownController@testParameterList');
Route::post('methods-acc-product-category/{product_category_id}/{equipment_type_id}', 'DropdownController@methodList');
Route::post('equipment-acc-parameter-category/{product_category_id}/{test_parameter_id}', 'DropdownController@getParameterEquipmentList');
Route::post('equipment-types-list', 'DropdownController@getEquipmentTypesList');
Route::get('master/products-list/{productCatId}', 'DropdownController@getAllProductsList');
Route::post('get-state-city-tree-view', 'DropdownController@getStateCityLocationTree');
Route::get('product-master-alias-list/{productCatId}', 'DropdownController@getProductMasterAliasList');
Route::post('customer-priority-list', 'DropdownController@getSamplePriorityList');
Route::post('detectors-acc-product-category/{product_category_id}/{equipment_type_id}', 'DropdownController@detectorsList');
Route::post('detectors-acc-parameter-category/{product_category_id}/{test_parameter_id}', 'DropdownController@getParameterEquipmentList');
Route::post('get-country-state-tree-view', 'DropdownController@getCountryStateLocationTree');
Route::post('report-header-defaults-list', 'DropdownController@reportHdrDefaultTypes');

//employee section start
Route::get('master/employees', 'EmployeeController@index');
Route::post('user/add-employee', 'EmployeeController@createEmployee');
Route::get('user/get-employees/{divisionId}', 'EmployeeController@getEmployeeList');                     // get list of employees
Route::post('user/get-employees-multisearch', 'EmployeeController@getEmployeeListMultiSearch');             // get employees data with multisearch
Route::post('user/delete-employee', 'EmployeeController@deleteEmployeeData');                         // get list of employees
Route::post('user/edit-employee', 'EmployeeController@editEmployeeData');                         // edit employees data
Route::post('user/update-employee', 'EmployeeController@updateEmployeeData');                         // edit employees data
Route::post('employeeList', 'DropdownController@getEmployeeList');                               // edit employees data
Route::get('employee/branch-wise-list/{division_id}', 'DropdownController@getBranchWiseEmployeeList');                     // edit employees data
Route::get('employees/upload', 'EmployeeController@uploadEmployee');                             // view upload employees csv data form
Route::post('employees/upload-preview', 'EmployeeController@uploadEmployeePreview');                     // upload employees csv data
Route::post('employees/save-uploaded-employees', 'EmployeeController@saveUploadedEmployee');                 // save upload employees csv data
Route::post('master/employee/get-role-list', 'DropdownController@getRoleList');                     // getting all role list
Route::post('master/employees/get-branch-wise-employees', 'EmployeeController@getBranchWiseEmployeeList');         //get list of employees
Route::post('master/employee/upload-signature-image', 'FileUploadController@uploadEmployeeSignatureImage');         //get list of employees
Route::get('master/employee/delete-signature-image/{id}', 'FileUploadController@deleteEmployeeSignatureImage');         //get list of employees

//Customer section start
Route::get('master/customers', 'CustomerController@index');
Route::post('customer/add-customer', 'CustomerController@createCustomer');                             // add customer data
Route::post('customer/get-customers', 'CustomerController@getCustomerList');             // get customer data list
Route::post('customer/get-customers-multisearch', 'CustomerController@getCustomerList');                     // get customer data list
Route::post('customer/delete-customer', 'CustomerController@deleteCustomerData');                    // delete customer data
Route::post('customer/edit-customer', 'CustomerController@editCustomerData');                         // edit customer data
Route::post('customer/update-customer', 'CustomerController@updateCustomerData');                     // update customer data
Route::get('get_cities_list/{stateId}', 'DropdownController@stateCitiesList');                         // update customer data
Route::get('customers/upload', 'CustomerController@uploadCustomer');                                 // view upload customer csv data form
Route::post('customers/upload-preview', 'CustomerController@uploadCustomerPreview');                         // upload customer csv data
Route::post('customer/save-uploaded-customers', 'CustomerController@saveUploadedCustomer');                         // save upload customer csv data
Route::get('customer/generate-customer-number', 'CustomerController@getAutoGeneratedCode');                             //getting sales executive List
Route::post('master/customer/save-email-addresses', 'CustomerController@saveEmailAddresses');                         // save upload customer csv data
Route::post('master/customer/get-email-addresses/{customer_id}', 'CustomerController@getCustomerEmailAddresses');         // save upload customer csv data
Route::post('master/customer/edit-email-addresses/{customer_id}/{customer_email_id}', 'CustomerController@editCustomerEmailAddresses');         // save upload customer csv data
Route::post('master/customer/update-email-address', 'CustomerController@updateCustomerEmailAddress');                         // save upload customer csv data
Route::post('master/customer/delete-email-address', 'CustomerController@deleteCustomerEmailAddress');                             //getting sales executive List
Route::post('allcountries', 'DropdownController@getAllCountriesList');                                                           //getting All countries
Route::post('get_states_list/{countryId}', 'DropdownController@countryStatesList');                                               //getting states according to countries
Route::get('customer-locations', 'CustomerLocationController@index');
Route::post('customers-list', 'DropdownController@customersList');
Route::get('customer/get_divisions_list', 'DivisionController@getDivisionsList');                                       //getting division List
Route::get('customer/get_sales_executive_list/{divisionId}', 'DropdownController@getSalesExeListBasedOnDivision');      //getting sales executive List
Route::post('customer/get-customer-gst-categories', 'DropdownController@getCustomerGstCategories');                     //Getting Customer GST Categories
Route::post('customer/get-customer-gst-types', 'DropdownController@getCustomerGstTypes');                               //Getting Customer GST Types
Route::post('customer/get-customer-gst-tax-slab-types', 'DropdownController@getCustomerGstTaxSlabTypes');               //Getting Customer GST Tax Slab Types
Route::post('master/customer/hold-customer', 'CustomerController@holdCustomer');                                        // Holding of customer
Route::post('master/customer/unhold-customer', 'CustomerController@unholdCustomer');                                    // Unholding of customer
Route::post('master/customer/get-hold-customer-dtl', 'CustomerController@getHoldCustomerDtl');                          // Getting Hold customer detail
Route::post('customers/upload-sales-excutives-csv', 'FileUploadController@uploadSalesExecutivesCsv');                    //Upload Sales Executive CSV

//Company section start
Route::get('master/company', 'CompanyController@index');
Route::post('company/add-company', 'CompanyController@createCompany');                 // add company data
Route::post('company/get-companies', 'CompanyController@getCompaniesList');         // get company data
Route::post('company/delete-company', 'CompanyController@deleteCompanyData');       // delete company data list
Route::post('company/edit-company', 'CompanyController@editCompanyData');           // edit company data list
Route::post('company/update-company', 'CompanyController@updateCompanyData');       // update-company data list
Route::post('company/get-companycodes', 'DropdownController@getCompaniesCodeList');  // get-companycodes data list

//department section start
Route::get('master/departments', 'DepartmentController@index');
Route::post('departments/add-department', 'DepartmentController@createDepartment');         // add department data
Route::post('departments/get-departments', 'DepartmentController@getDepartmentsList');         // get department data
Route::post('departments/get-departments-multisearch', 'DepartmentController@getDeptListMultiSearch');         // get department data
Route::post('departments/delete-department', 'DepartmentController@deleteDepartment');       // delete department data list
Route::post('departments/edit-department', 'DepartmentController@editDepartmentData');      // edit department data list
Route::post('departments/update-department', 'DepartmentController@updateDepartmentData');  // update department data list
Route::post('departments-list', 'DropdownController@getdepartmentList');
Route::get('departments/upload', 'DepartmentController@uploadDepartment');                                         // view upload employees csv data form
Route::post('departments/upload-preview', 'DepartmentController@uploadDepartmentPreview');                         // upload employees csv data
Route::post('departments/save-uploaded-departments', 'DepartmentController@saveUploadedDepartment');                 // save upload employees csv data
Route::get('department/generate-department-number', 'DepartmentController@getAutoGeneratedCode');                 // save upload employees csv data
Route::get('department/generate-department-types', 'DropdownController@getDepartmentTypeList');                 // save upload employees csv data
Route::post('master/department/get-department-product-category-detail', 'DepartmentController@getDepartmentProductCategoryDetail'); //get Department Product Category Detail
Route::post('master/department/update-department-product-category-detail', 'DepartmentController@updateDepartmentProductCategoryDetail'); //get Department Product Category Detail

//division section start
Route::get('master/divisions', 'DivisionController@index');
Route::post('division/add-division', 'DivisionController@createDivision');             // add division data
Route::post('division/get-divisions', 'DivisionController@getDivisionsList');         // get division data
Route::post('division/get-divisions-multisearch', 'DivisionController@getDivisionsListMultisearch');          // get division-parameters data
Route::post('division/delete-division', 'DivisionController@deleteDivision');       // delete division data list
Route::post('division/edit-division', 'DivisionController@editDivisionData');         // edit division data list
Route::post('division/update-division', 'DivisionController@updateDivisionData');     // edit division data list
Route::post('division/get-divisioncodes', 'DropdownController@getDivisionsCodeList');          // edit company data list

//division parameters section start
Route::get('division-parameters', 'DivisionParametersController@index');
Route::post('division/add-division-parameters', 'DivisionParametersController@createDivisionParameters');          // add division-parameters data
Route::post('division/get-division-parameters', 'DivisionParametersController@getDivisionParametersList');          // get division-parameters data
Route::post('division/delete-division-parameters', 'DivisionParametersController@deleteDivisionParameters');        // delete division-parameters data list
Route::post('division/edit-division-parameters', 'DivisionParametersController@editDivisionParameters');         // edit division-parameters data list
Route::post('division/update-division-parameters', 'DivisionParametersController@updateDivisionParameters');     // update division-parameters data list
Route::post('division/cities', 'DropdownController@getCitiesCodeList');                                  // get get cities code list data list
Route::get('divisions/generate-division-number', 'DivisionController@getAutoGeneratedCode');                                  // get get cities code list data list

//division units section start
Route::get('inventory/units', 'UnitController@index');
Route::post('units/add-unit', 'UnitController@createUnit');             // add Unit data
Route::post('units/get-units', 'UnitController@getUnitsList');             // get Unit data
Route::post('units/delete-unit', 'UnitController@deleteUnit');           // delete Unit data list
Route::post('units/edit-unit', 'UnitController@editUnitData');          // edit Unit data list
Route::post('units/update-unit', 'UnitController@updateUnitData');      // edit Unit data list
Route::get('inventory/units/generate-unit-number', 'UnitController@getAutoGeneratedCode');

//division units section start
Route::get('unit-conversions', 'UnitConversionsController@index');
Route::post('unit/add-conversions', 'UnitConversionsController@createUnitConversions');             // add Unit conversions data
Route::post('unit/get-conversions', 'UnitConversionsController@getUnitsConversionsList');             // get Unit data
Route::post('unit/delete-conversions', 'UnitConversionsController@deleteUnitConversions');           // delete Unit data list
Route::post('unit/edit-conversions', 'UnitConversionsController@editUnitConversionsData');          // edit Unit data list
Route::post('unit/update-conversions', 'UnitConversionsController@updateUnitConversionsData');      // edit Unit data list

//Test Standards section start
Route::get('master/test-standards', 'TestStandardsController@index');
Route::post('test-standards/add-test-standards', 'TestStandardsController@createTestStandard');                     // add test-standards data
Route::post('test-standards/get-test-standards/{product_category_id}', 'TestStandardsController@getTestStandardsList');                     // get test-standards data
Route::post('test-standards/get-test-standards-multistart', 'TestStandardsController@getStandardListMultiSearch');     // get test-standards data
Route::post('test-standards/delete-test-standards', 'TestStandardsController@deleteTestStandardData');               // delete test-standards data list
Route::post('test-standards/edit-test-standards', 'TestStandardsController@editTestStandardData');                  // edit test-standards data list
Route::post('test-standards/update-test-standards', 'TestStandardsController@updateTestStandardData');              // edit test-standards data list
Route::get('test-standards/generate-test-standard-number', 'TestStandardsController@getAutoGeneratedCode');            // update-product-categories data list
Route::post('master/get-parent-product-category', 'DropdownController@getParentProductCategory');                     // add test-standards data
Route::post('master/test-standards/upload-test-standards-csv', 'TestStandardsController@uploadTestStandardsCSV');        //upload-product-categories data list

//products category section start
Route::get('master/product-categories', 'ProductCategoryController@index');                                         // update-product-categories data list
Route::post('product-category/add-category', 'ProductCategoryController@createProductCategory');                     // add product-categories data
Route::post('product-category/get-category/{parent_id}', 'ProductCategoryController@getProductCategoryList');                     // get product-categories data
Route::post('product-category/get-category-multisearch', 'ProductCategoryController@getProductCategoryListMultisearch');                     // get product-categories data
Route::post('product-category/delete-category', 'ProductCategoryController@deleteProductCategoryData');               // delete product-categories data list
Route::post('product-category/edit-category', 'ProductCategoryController@editProductCategoryData');                 // edit product-categories data list
Route::post('product-category/update-category', 'ProductCategoryController@updateProductCategoryData');             // update-product-categories data list
Route::post('product-category/get-categorycode-list', 'DropdownController@getProductCategoryCodeList');        // update-product-categories data list
Route::get('product-categories/generate-product-category-number', 'ProductCategoryController@getAutoGeneratedCode');        // update-product-categories data list
Route::get('product-category/get-category-list/{level}', 'DropdownController@getProductCategoryListByLevel');        // update-product-categories data list
Route::get('master/product-categories/tree-view', 'ProductCategoryController@productCategoriesTreeView');    //products category tree view section start
Route::post('master/product-categories/upload-product-categories-csv', 'ProductCategoryController@uploadProductCategoryCSV');        //upload-product-categories data list

//products section start
Route::get('master/products', 'ProductController@index');
Route::post('products/add-product', 'ProductController@createProduct');                 // add products data
Route::get('products/get-products/{productCatId}', 'ProductController@getProductListByCatId');                 // get products data
Route::post('products/get-products/{productCatId}', 'ProductController@getProductListByCatId');                 // get products data
Route::post('products/get-products-multisearch', 'ProductController@getProductListMultisearch');                 // get products data
Route::post('products/delete-product', 'ProductController@deleteProductData');           // delete products data list
Route::post('products/edit-product', 'ProductController@editProductData');                 // edit products data list
Route::post('products/update-product', 'ProductController@updateProductData');             // update-products data list
Route::get('products/get-products-dropdown/{productCatId}', 'DropdownController@getProductsByCatId');                 // get products data
Route::get('products/generate-product-number', 'ProductController@getAutoGeneratedCode');                         // get products data
Route::post('master/products/upload-products-csv', 'ProductController@uploadProductCSV');                                       // add products data

//test-parameter-categories section start
Route::get('master/test-parameter-categories', 'TestParameterCategoryController@index');
Route::get('master/test-parameter-categories/tree-view', 'TestParameterCategoryController@parameterCategoryTreeView');               // update equipment data list
Route::post('test-parameter-categories/add-category', 'TestParameterCategoryController@createTestParameterCat');             //add test-parameter-categories
Route::get('test-parameter-categories/get-category/{parent_id}', 'TestParameterCategoryController@getTestParameterCatList');             //get test-parameter-categories
Route::post('test-parameter-categories/multisearch', 'TestParameterCategoryController@getTestParameterCatListMultiSearch'); //get test-parameter-categories
Route::post('test-parameter-categories/delete-category', 'TestParameterCategoryController@deleteTestParameterCatData');  //delete test-parameter-categories list
Route::post('test-parameter-categories/edit-category', 'TestParameterCategoryController@editTestParameterCatData');        //edit test-parameter-categories list
Route::post('test-parameter-categories/update-category', 'TestParameterCategoryController@updateTestParameterCatData'); //update-test-parameter-categories list
Route::post('test-parameter-categories/get-categorycode-list', 'DropdownController@getCategoriesCodeList');                //update-test-parameter-categories list
Route::get('test-parameter-categories/generate-parameter-number', 'TestParameterCategoryController@getAutoGeneratedCode');               // update equipment data list
Route::get('test-parameter-categories/get-category-tree-popup', 'TestParameterCategoryController@getCategoryTreePopup');               // update equipment data list
Route::post('master/test-parameter-categories/upload-parameters-categories-csv', 'TestParameterCategoryController@uploadParameterCategoryCSV');        //upload-product-categories data list

//test-parameters section start
Route::get('master/test-parameters', 'TestParametersController@index');
Route::post('test-parameters/add-parameters', 'TestParametersController@createTestParameters');                 // add test-parameters data
Route::post('test-parameters/get-parameters', 'TestParametersController@getTestParameters');                                    // get test-parameters data
//Route::post('test-parameters/get-parameters-multisearch', 'TestParametersController@getTestParametersMultiSearch');	        // get test-parameters data
Route::post('test-parameters/delete-parameters', 'TestParametersController@deleteTestParameters');                // delete test-parameters data list
Route::post('test-parameters/edit-parameters', 'TestParametersController@editTestParameters');                     // edit test-parameters data list
Route::post('test-parameters/update-parameters', 'TestParametersController@updateTestParameters');                      // update test-parameters data list
Route::post('test-parameter/get-categorycode-list', 'DropdownController@getCategoriesCodeList');                  // update test-parameters data list
Route::get('test-parameter/generate-parameter-number', 'TestParametersController@getAutoGeneratedCode');              // update equipment data list
Route::post('master/test-parameter/upload-parameters-csv', 'TestParametersController@uploadParametersCSV');              // add products data

//standard-wise-product section start
Route::get('master/standard-wise-product', 'StandardWiseProductTestController@index');
Route::post('standard-wise-product/add-product', 'StandardWiseProductTestController@createProductTest');                         // add standard-wise-product data
Route::post('standard-wise-product/get-products-tests/{p_category_id}', 'StandardWiseProductTestController@getProductTestList');        // get standard-wise-product data
Route::post('standard-wise-product/get-std-test-multisearch', 'StandardWiseProductTestController@getProductTestListMultisearch');       // get standard-wise-product
Route::post('standard-wise-product/delete-product', 'StandardWiseProductTestController@deleteProductTest');                     // delete standard-wise-product list
Route::post('standard-wise-product/edit-product', 'StandardWiseProductTestController@editProductTest');                          // edit standard-wise-product data list
Route::post('standard-wise-product/getproduct', 'StandardWiseProductTestController@getProTestData');                          // edit standard-wise-product data list
Route::post('standard-wise-product/update-product', 'StandardWiseProductTestController@updateProductTest');                      // update standard-wise-product list
Route::get('standard-wise-product/get-teststandars-list/{product_cat_parent_id}', 'DropdownController@getTestStandardsList');           // get test standard list
Route::post('standard-wise-product/get-product-list', 'DropdownController@getProductsCodeList');                                // get test standard list
Route::post('get-equipment-list', 'DropdownController@getEquipmentList');                                        // get test standard list
Route::post('get-method-list', 'DropdownController@getMethodList');                                        // get test standard list
Route::get('standard-wise-product/add', 'StandardWiseProductTestController@addTest');                                   // update standard-wise-product list
Route::get('standard-wise-product/generate-test-number', 'StandardWiseProductTestController@getAutoGeneratedCode');                        // update-product-categories data list
Route::get('master/standard-wise-product/get-product-test-parameters-list-with-child-view/{test_id}', 'StandardWiseProductTestController@getProductTestAllParametersListChildView');
Route::post('master/standard-wise-product/save-product-test-parameters-list-with-child-view', 'StandardWiseProductTestController@saveTestParametersOrdering');
Route::post('master/standard-wise-product/upload-product-test-header', 'StandardWiseProductTestController@uploadProductTestHeader');
Route::post('master/standard-wise-product/upload-product-test-details', 'StandardWiseProductTestController@uploadProductTestDetails');
Route::post('standard-wise-product/add-more-product-test', 'StandardWiseProductTestController@saveMoreProductTest');
Route::post('standard-wise-product/get-parent-category/{id}', 'StandardWiseProductTestController@getProductParentDetail');

//parameters-details section start
Route::post('standard-wise-product/add-parameters-details', 'ProductTestParameterController@createProductTestParameters');     //add standard-wise-product
Route::post('standard-wise-product/get-parameters-details', 'ProductTestParameterController@getProductTestParametersList'); //get standard-wise-product data
Route::post('standard-wise-product/get-parameters-details-multisearch', 'ProductTestParameterController@getProductTestParametersListMultisearch'); //get standard-wise-product data
Route::post('standard-wise-product/delete-parameters-details', 'ProductTestParameterController@deleteProductTestParameters'); //delete standard-wise-product
Route::post('standard-wise-product/edit-parameters-details', 'ProductTestParameterController@editProductTestParameters');   //edit standard-wise-product list
Route::post('standard-wise-product/update-parameters-details', 'ProductTestParameterController@updateProductTestParameters'); //update standard-wise-product
Route::post('standard-wise-product/get-producttest-list', 'DropdownController@getProductTestCodeList');                   //update standard-wise-product list
//Route::post('standard-wise-product/get-parameter-list/{product_cat_id}/{parameter_cat_id}', 'DropdownController@getParameterByParaCat');  			 //update standard-wise-product list
Route::get('standard-wise-product/auto-get-parameter-list/{parameter_name}/{product_cat_id}/{parameter_cat_id}', 'DropdownController@getParameterListByParameterName');               //update standard-wise-product list
Route::post('standard-wise-product/get-selected-parameter-price/{parameter_id}', 'ProductTestParameterController@getParameterPrice');                   //update standard-wise-product list

//parameters-details-alternate-methods section start
Route::post('product-test-details/get-product-test-details', 'ProductTestParaAlternativeMethodController@getTestParameterDetails'); //update standard-wise-product
Route::post('product-test-details/add-alternative-method', 'ProductTestParaAlternativeMethodController@createTestAlternativeMethod'); //update standard-wise-product
Route::post('product-test-details/get-alt-methods-list', 'ProductTestParaAlternativeMethodController@getTestAlternativeMethodList'); //update standard-wise-product
Route::post('product-test-details/get-alt-methods-list-multisearch', 'ProductTestParaAlternativeMethodController@getTestAlternativeMethodListMultisearch'); // update standard-wise-product
Route::post('product-test-details/delete-alt-method', 'ProductTestParaAlternativeMethodController@deleteTestAlternativeMethod'); //update standard-wise-product
Route::post('product-test-details/edit-alt-method', 'ProductTestParaAlternativeMethodController@editTestAlternativeMethod');  //update standard-wise-product
Route::post('product-test-details/update-alt-method', 'ProductTestParaAlternativeMethodController@updateTestAlternativeMethod'); //update standard-wise-product

//test-parameter-BOM section start
Route::get('test-parameter-BOM', 'TestParameterBOMController@index');
Route::post('test-parameter-BOM/add-BOM', 'TestParameterBOMController@createTestParametersBOM');                  // add standard-wise-product data
Route::post('test-parameter-BOM/get-BOM', 'TestParameterBOMController@getTestParametersBOMList');                   // get standard-wise-product data
Route::post('test-parameter-BOM/delete-BOM', 'TestParameterBOMController@deleteTestParametersBOM');              // delete standard-wise-product data list
Route::post('test-parameter-BOM/edit-BOM', 'TestParameterBOMController@editTestParametersBOM');                   // edit standard-wise-product data list
Route::post('test-parameter-BOM/update-BOM', 'TestParameterBOMController@updateTestParametersBOM');               // update standard-wise-product data list
Route::post('get-product-test-hdr-list', 'DropdownController@getTestProductCodeList');               // update standard-wise-product data list
Route::post('get-product-test-dtl-list', 'DropdownController@getTestProductParameterCodeList');               // update standard-wise-product data list
Route::post('get-item-master-list', 'DropdownController@getItemMasterList');               // update standard-wise-product data list

//equipment section start
Route::get('master/equipment-master', 'EquipmentController@index');
Route::post('equipment/add-equipment', 'EquipmentController@createEquipment');                     // add equipment data
Route::post('equipment/get-equipments', 'EquipmentController@getEquipmentsList');                   // get equipment data
Route::post('equipment/get-equipments-multisearch', 'EquipmentController@getEquipmentListMultiSearch');                   // get method data
Route::post('equipment/delete-equipment', 'EquipmentController@deleteEquipmentData');              // delete equipment data list
Route::post('equipment/edit-equipment', 'EquipmentController@editEquipmentData');                   // edit equipment data list
Route::post('equipment/update-equipment', 'EquipmentController@updateEquipmentData');               // update equipment data list
Route::get('equipment/generate-equipment-number', 'EquipmentController@getAutoGeneratedCode');               // update equipment data list
Route::post('master/equipment/upload-equipment-types-csv', 'EquipmentController@uploadEquipmentTypesCSV');                 // add products data
Route::post('master/equipment/get-selected-sorting-equipments', 'EquipmentController@getSelectedSortingEquipments');                     // get equipment data
Route::post('master/equipment/save-update-select-sorting-equipment', 'EquipmentController@saveUpdateSelectSortingEquipment');   // save Update Select Sorting Equipment
Route::post('master/equipment/save-update-selected-sorting-equipment', 'EquipmentController@funSaveUpdateSelectedSortingEquipment');   // save Update Select Sorting Equipment

//Method section start
Route::get('master/method-master', 'MethodController@index');
Route::post('method/add-method', 'MethodController@createMethod');                                         // add method data
Route::post('method/get-methods/{equipment_type_id}', 'MethodController@getMethodsList');                  // get method data
Route::post('method/get-methods-multisearch', 'MethodController@getMethodListMultiSearch');              // get method data
Route::post('method/delete-method', 'MethodController@deleteMethodData');                                 // delete method data list
Route::post('method/edit-method', 'MethodController@editMethodData');                                      // edit method data list
Route::post('method/update-method', 'MethodController@updateMethodData');                                  // update method data list
Route::get('method/generate-method-number', 'MethodController@getAutoGeneratedCode');                      // update equipment data list
Route::post('master/method/upload-methods-csv', 'MethodController@uploadMethodCSV');                 // add products data

//State section start
Route::get('master/states', 'StateController@index');                                          // add states data
Route::post('states/add-state', 'StateController@createState');                      // add states data
Route::post('states/get-states', 'StateController@getStatesList');                       // get states data
Route::post('states/delete-state', 'StateController@deleteStateData');                   // delete states data list
Route::post('states/edit-state', 'StateController@editStateData');                   // edit states data list
Route::post('states/update-state', 'StateController@updateStateData');               // update states data list

//holidays section start
Route::get('master/holidays', 'HolidaysController@index');                                          // add holiday data
Route::post('holidays/add-holiday', 'HolidaysController@createHoliday');                      // add states data
Route::post('holidays/get-holidays', 'HolidaysController@getHolidaysList');                       // get states data
Route::post('holidays/delete-holiday', 'HolidaysController@deleteHolidayData');                   // delete states data list
Route::post('holidays/edit-holiday', 'HolidaysController@editHolidayData');                   // edit states data list
Route::post('holidays/update-holiday', 'HolidaysController@updateHolidayData');               // update states data list

//cities section start
Route::get('master/cities', 'CityController@index');                              // add city data
Route::post('cities/add-city', 'CityController@createCity');                      // add city data
Route::post('cities/get-city', 'CityController@getCitiesList');                   // get city data
Route::post('cities/delete-city', 'CityController@deleteCityData');              // delete city data list
Route::post('cities/edit-city', 'CityController@editCityData');                   // edit city data list
Route::post('cities/update-city', 'CityController@updateCityData');               // update city data list

//customers/ownership section start
Route::get('master/customers/ownership-types', 'OwnershipController@index');                                  //add ownership data
Route::post('customers/add-ownership-type', 'OwnershipController@createOwnership');             //add ownership data
Route::post('customers/get-ownership', 'OwnershipController@getOwnershipList');                  //get ownership data
Route::post('customers/delete-ownership-type', 'OwnershipController@deleteOwnershipData');         //delete ownership data list
Route::post('customers/edit-ownership-type', 'OwnershipController@editOwnershipData');          //edit ownership data list
Route::post('customers/update-ownership-type', 'OwnershipController@updateOwnershipData');      //update ownership data list
Route::get('customers/generate-ownership-type-number', 'OwnershipController@getAutoGeneratedCode');        // update-product-categories data list

//customers/company-type section start
Route::get('master/customers/company-types', 'CompanyTypeController@index');                                  //add company-type data
Route::post('customers/add-company-type', 'CompanyTypeController@createCompanyType');                 //add company-type data
Route::post('customers/get-company-type', 'CompanyTypeController@getCompanyType');                  //get company-type data
Route::post('customers/delete-company-type', 'CompanyTypeController@deleteCompanyType');         //delete company-type data list
Route::post('customers/edit-company-type', 'CompanyTypeController@editCompanyType');              //edit company-type data list
Route::post('customers/update-company-type', 'CompanyTypeController@updateCompanyType');          //update company-type data list
Route::get('customers/generate-company-type-number', 'CompanyTypeController@getAutoGeneratedCode');        // update-product-categories data list

//Inquiry section start
Route::get('inquiry/inquiries', 'InquiryController@index');
Route::post('inquiry/get-inquiry', 'InquiryController@getInquiries');
Route::post('inquiry/add-inquiry', 'InquiryController@createInquiry');
Route::post('inquiry/edit-inquiry', 'InquiryController@editInquiry');
Route::post('inquiry/edit-followup', 'InquiryController@editFollowup');
Route::post('inquiry/update-inquiry', 'InquiryController@updateInquiry');
Route::post('inquiry/update-inquiry-followup', 'InquiryController@updateFollowInquiry');
Route::post('inquiry/add-followup', 'InquiryController@createInquiryFolloup');
Route::post('inquiry/get-inquiry-followup', 'InquiryController@getFolloupsByInquiryId');
Route::post('inquiry/delete-inquiry-followup', 'InquiryController@deleteFolloup');
Route::post('inquiry/get-customers', 'InquiryController@getCustomerList');
Route::post('inquiry/delete-inquiry', 'InquiryController@deleteInquiry');
Route::get('inquiry/reports', 'InquiryController@inquiryReport');
Route::post('inquiry/get-inquiry-report', 'InquiryController@getinquiriesReport');
Route::post('inquiry/get-previous-inquiry', 'InquiryController@getpreviousInquiriesReport');

/**********************************************************
Order Management Section
 ***********************************************************/
Route::get('sales/orders', 'OrdersController@index');                                                               //Listing and adding of orders
Route::get('orders/get_test_product_category/{level}', 'DropdownController@getTestProductCategory');                //Getting Test Product Category List
Route::get('orders/get_test_products/{id}', 'DropdownController@getProductAccToCategory');                          //Getting Test Product List
Route::get('orders/get_test_product_acc_sample_name/{sampleid}', 'DropdownController@getProductAccToSampleName'); //Getting Test Product List
Route::get('orders/get-product-tests/{id}', 'DropdownController@getProductTests');                                  //Getting Test Standard List
Route::get('orders/get-product-test-parameters-list/{id}', 'OrdersController@getProductTestParametersList');        //Getting Test Standard Parameter List
Route::post('orders/get-product-test-parameters', 'OrdersController@getProductTestParameters');                     //Getting Test Standard Parameter List
Route::get('orders/edit-get-product-test-parameters-list/{test_id}/{order_id}', 'OrdersController@getEditProductTestParametersList');        //Getting Test Standard Parameter List
Route::post('orders/edit-get-product-test-parameters', 'OrdersController@getEditProductTestParameters');                            //Getting Test Standard Parameter List
Route::get('orders/get_customer_list', 'DropdownController@getCustomerList');                                                       //Getting Customer List
Route::get('orders/get_sales_executive_list', 'DropdownController@getSalesExecutiveList');                                          //Getting Customer List
Route::get('orders/get_sample_priority_list', 'DropdownController@getSamplePriorityList');                                          //Getting get sample priority List
Route::post('orders/addOrder', 'OrdersController@createOrder');                                                                     //Adding of Orders
Route::get('orders/get_orders_list/{divisionId}', 'OrdersController@getOrders');                                                    //Getting all Orders
Route::post('orders/get_orders_list', 'OrdersController@getOrders');                                                                //Getting all Orders
Route::post('orders/check-customer-wise-product-rate', 'OrdersController@getCustomerStateAndProductWiseRate');                                                                     //Adding of Orders
Route::get('orders/view_order/{order_id}', 'OrdersController@viewOrder');                                                           //Viewing of Order
Route::get('orders/get-alter-product-test-parameters/{id}', 'OrdersController@getAlterProductTestParameters');                      //Getting Test Standard Parameter List
Route::get('orders/reselect_test_standard_parameters/{id1}', 'OrdersController@reSelectTestStandardParameters');                    //Getting alternative Parameter List
Route::get('orders/delete_order/{id}', 'OrdersController@deleteOrder');                                                             //deleting an order
Route::get('orders/get_customer_attached_detail/{cid}/{sid}', 'OrdersController@getCustomerAttachedDetail');                        //Getting Customer Location and Lic Number
Route::get('orders/edit_order/{order_id}', 'OrdersController@viewOrder');                                                           //Viewing of Order
Route::get('orders/save_order/{order_id}', 'OrdersController@viewOrder');                                                           //Viewing of Order
Route::post('orders/updateOrder', 'OrdersController@updateOrder');                                                                  //Adding of Orders
Route::get('sales/orders/get-test-sample-received', 'DropdownController@getSamplesReceived');                                       //Getting all Samples
Route::get('sales/orders/get-customer-attached-sample-detail/{sampleid}', 'OrdersController@getCustomerAttachedSampleDetail');      //Getting all Samples
Route::get('sales/orders/get-edit-customer-attached-sample-detail/{orderid}/{sampleid}', 'OrdersController@getEditCustomerAttachedSampleDetail');    //Getting all Samples
Route::get('sales/orders/view_order_log/{orderId}', 'OrdersController@getOrderLog');                                                 //Getting all Samples
Route::get('sales/orders/get-sample-name-list/{id}/{text}', 'OrdersController@getAutoCompleteSampleNames');                          //Generating upload_order_pdf
Route::get('sales/orders/get-header-note-list/{text}', 'DropdownController@getAutoSearchHeaderNoteMatches');                         //Generating upload_order_pdf
Route::get('sales/orders/get-stability-note-list/{text}', 'DropdownController@getAutoSearchStabilityNoteMatches');                   //Generating upload_order_pdf
Route::get('orders/delete_order_parameter/{order_id}/{analysis_id}', 'OrdersController@deleteOrderParameter');                       //Viewing of Order
Route::post('sales/orders/search-parameters', 'OrdersController@parametersSearch');                                                  //Adding of Orders
Route::post('sales/edit-order/search-parameters', 'OrdersController@EditParametersSearch');                                          //Adding of Orders
Route::post('sales/un-hold-order/{order_id}', 'OrdersController@unHoldOrder');                                                       //Adding of Orders
Route::post('sales/orders/refresh-invoicing-structure', 'DropdownController@refreshSRInvoicingStructure');                           //Adding of Orders
Route::post('sales/orders/customer_billing_type_list', 'DropdownController@getCustomerbillingTypes');                                //Getting billing Types
Route::post('sales/orders/customer-invoicing-types-list', 'DropdownController@getCustomerInvoicingTypes');                           //Getting Invoicing Types
Route::post('sales/orders/discount-types-list', 'DropdownController@getCustomerDiscountTypes');                                      //Getting Discount Types
Route::post('sales/orders/upload-trf-order-file', 'FileUploadController@processOrderTrfFileUpload');                                 //Uploading Order TRF Files
Route::post('sales/orders/get-trf-order-file-dtl', 'OrdersController@getOrderTrfFileUploadDtl');                                     //Getting Order TRF File Detail
Route::post('sales/orders/get-customer-trf-accto-sample-name', 'OrdersController@getOrderSTPNoAccToSampleName');                     //Getting Order STP File Detail
Route::post('sales/orders/saving-customer-stp-dtl', 'OrdersController@saveOrderStpDetail');                                          //Saving of Order STP Detail
Route::get('sales/orders/remove-dynamic-field/{id}', 'OrdersController@removeDynamicFieldRowData');                                  //Removing Dynamic Field Name/Value Row
Route::get('orders/get_defined_test_std/{b_id}/{dept_id}', 'DropdownController@getdefinedTestStandardList');                         //Getting Customer List
Route::post('orders/upload-purchase-order-csv', 'FileUploadController@uploadOrderPurchaseOrderCsv');                                 //Upload Sales Executive CSV
Route::get('sales/orders/get-sampler-dropdown-list/{divId}', 'DropdownController@getSamplerDropdownList');                           //Get Sampler Dropdown List

/**********************************************************
Sample Management Section
 ***********************************************************/
Route::get('sales/samples', 'SamplesController@index');                                                                             //Samples
Route::get('sales/samples/get-division-wise-samples/{divisionId}/{status}', 'SamplesController@getDivisionWiseSamples');            //Getting all Samples
Route::post('sales/samples/get-sample-modes', 'DropdownController@getSampleModes');                                                 //Sample Mode
Route::post('sales/samples/add-division-sample', 'SamplesController@createDivisionSample');                                         //Adding of Sample
Route::get('sales/samples/view-division-sample/{sampleId}', 'SamplesController@viewSample');                                        //Getting all Sample
Route::post('sales/samples/update-division-sample', 'SamplesController@updateDivisionSample');                                      //Adding of Sample
Route::get('sales/samples/delete-division-sample/{id}', 'SamplesController@deleteDivisionSample');                                  //deleting an Sample
Route::post('sales/samples/add-internal-transfer-sample', 'SamplesController@createInternalTransferSample');                        //Adding of Internal Transfer Sample
Route::post('sales/samples/get-sample-status', 'DropdownController@getSampleStatus');                                               //Listing of Sample Action
Route::get('sales/samples/close-division-sample/{id}', 'SamplesController@closeDivisionSample');                                    //Closing an Sample
Route::post('sales/samples/get-trf-number-list', 'DropdownController@getTrfNumberList');                                            //Listing of TRF No. Dropdown
Route::get('sales/samples/get-trf-involved-customer/{id}', 'SamplesController@getTrfInvolvedCustomer');                             //Getting TRF Involved Customer Name

/**********************************************************
Reports Management Section
 ***********************************************************/
Route::get('sales/reports', 'ReportsController@index');
Route::get('reports/get_branch_wise_reports/{id}', 'ReportsController@getBranchWiseReports');                                         //Viewing branch wise orders list
Route::post('reports/get_branch_wise_reports', 'ReportsController@getBranchWiseReports');                                         //Viewing branch wise orders list
//Route::post('reports/get_reports_multisearch', 'ReportsController@getReportsMultisearch');                    		        //Viewing branch wise orders list
Route::get('reports/get_branch_wise_add_orders/{id}', 'DropdownController@getBranchWiseAddOrders');                                   //Viewing branch wise orders list
Route::get('reports/get_branch_wise_view_orders/{id}', 'DropdownController@getBranchWiseViewOrders');                                 //Viewing branch wise orders list
Route::get('reports/view_order/{id}', 'ReportsController@viewOrder');                                                                 //Viewing of Order Parameter
Route::post('reports/save_order_test_param_result_by_tester', 'ReportsController@saveOrderTestParametersResult');                       //Adding of Orders Test parameter Result
Route::get('reports/save_order_invoice/{id}', 'ReportsController@saveOrderForInvoice');                                                //Adding of Orders for Invoice
Route::get('reports/get_status_list', 'DropdownController@getReportStatusList');                                                      //Viewing report status list
Route::get('reports/get_filter_wise_reports/{division_id}', 'ReportsController@getBranchWiseReports');                                //Viewing branch wise orders list
Route::post('reports/get_filter_wise_reports/{division_id}', 'ReportsController@getBranchWiseReports');                               //Viewing branch wise orders list
Route::get('reports/delete-report/{id}', 'ReportsController@deleteReport');                                                         //deleting an report
Route::post('reports/save_final_report', 'ReportsController@saveFinalReport');                                                       //deleting an report
Route::get('reports/view_order_by_reporter/{id}', 'ReportsController@viewOrder');                                                       //Viewing of Order Parameter
Route::get('reports/view_order_by_tester/{id}', 'ReportsController@viewOrderParameterByTester');                                        //Viewing of Order Parameter
Route::post('reports/save_final_report_by_reviewer/{formtype}', 'ReportsController@saveFinalReportByReviewer');                         //deleting an report
Route::post('sales/reports/move_order_to_next_stage', 'ReportsController@moveOrderToNextStage');                                        //deleting an report
Route::post('sales/reports/need_report_modification', 'ReportsController@needReportModification');                                      //deleting an report
Route::post('sales/reports/need_report_modification_by_reviewer', 'ReportsController@needReportModificationByReviewer');                //deleting an report
Route::post('sales/reports/get-order-report-options', 'DropdownController@getOrderReportOptions');                                      //deleting an report
Route::post('sales/reports/get-note-remark-report-options', 'ReportsController@getNoteRemarkReportOptions');                            //Viewing of Order Parameter
Route::get('sales/reports/get-test-Standards/{id}', 'ReportsController@getDepartmentTestStd');                                          //Viewing of Order Parameter
Route::get('reports/amend-report/{id}', 'ReportsController@amendReport');                                                           //deleting an report
Route::get('sales/reports/get-edit-report-detail/{id}', 'ReportsController@editReport');                                                //deleting an report
Route::post('sales/reports/dispatch_report', 'ReportsController@dispatchReport');                                                   //deleting an report
Route::post('reports/order_dispatched_detail', 'ReportsController@getDispatchDetail');                                                  //get dispatch details
Route::get('reports/view_order_by_section_incharge/{id}', 'ReportsController@viewOrderBySectionIncharge');                              //Viewing of Order Parameter
Route::post('sales/reports/need_report_modification_by_section_incharge', 'ReportsController@needReportModificationBySectionIncharge'); //Section Incharge                                   //deleting an report
Route::post('reports/save_final_report_by_section_incharge/{formtype}', 'ReportsController@saveFinalReportBySectionIncharge');          //Section Incharge                       //deleting an report
Route::post('reports/get-order-section-incharge-detail', 'ReportsController@getOrderSectionInchargeDetail');                            //View Order Section Incharge Detail
Route::post('reports/get-refreshed-order-section-incharge-detail', 'ReportsController@getRefreshedOrderSectionInchargeDetail');         //Refresh Order Section Incharge Detail

/**********************************************************
Reports Setting Management Section
 ***********************************************************/
Route::get('sales/report-setting', 'ReportSettingController@index');
Route::post('sales/reports/get-order-master-columns', 'ReportSettingController@getOrderMasterColumnDtl');                           //Getting Order Master Column names list
Route::post('sales/reports/save-update-report-column-settings', 'ReportSettingController@saveUpdateReportColumnSettings');          //Save-Update Report Column Settings
Route::post('sales/reports/get-report-column-list', 'ReportSettingController@getBranchWiseReportColumns');                          //Getting Order Master Column names list

/**********************************************************
Invoices Management Section
 ***********************************************************/
Route::get('sales/invoices', 'InvoicesController@index');
Route::get('invoices/customer_billing_type_list', 'DropdownController@customerbillingTypes');                               //Viewing Customer billing Type list
Route::get('invoices/get_btype_customers_list/{bilingType}', 'InvoicesController@getBillingTypeCustomerList');              //Getting Billing Type Customer list
Route::post('invoices/get_btype_customers_list', 'InvoicesController@getBillingTypeCustomerList');                          //Getting Billing Type Customer list
Route::post('invoices/list_orders_acct_billing_type', 'InvoicesController@getCustomerBillingTypeOrders');                   //Listing all orders acco to billing Type
//Route::post('invoices/generate_invoices/{bilingType}/{cusid}', 'InvoicesController@generateInvoices');                    //Invoice generation according to billing Type
Route::post('invoices/generate_invoices', 'InvoicesController@generateInvoices');                                           //Invoice generation according to billing Type
Route::post('invoices/list_invoices_acct_billing_type', 'InvoicesController@getCustomerBillingTypeInvoices');               //Listing all orders acco to billing Type
Route::get('invoices/view_invoice_detail/{invoiceId}', 'InvoicesController@getInvoiceDetail');                              //Listing inovice detail
Route::post('invoices/get_all_invoice_list/{division_id}', 'InvoicesController@getBranchWiseInvoices');                     //Listing of all invoices
Route::post('invoices/get_all_invoice_list', 'InvoicesController@getBranchWiseInvoices');                                   //Listing of all invoices
Route::get('invoices/delete-invoice/{id}', 'InvoicesController@deleteInvoice');                                             //deleting an invoice
Route::post('invoices/dispatch-invoice-reports', 'InvoicesController@dispatchInvoiceWithReports');                          //Dispatch Invoice with Reports
//Route::post('invoices/dispatch_order', 'InvoicesController@dispatchOrder');                                               //upload an invoice pdf
Route::post('sales/invoices/view-invoice-orders-listing', 'InvoicesController@viewInvoiceOrderDetail');                     //View Invoice Order Detail
Route::get('sales/invoices/get-purchase-orders-listing/{customer_id}', 'InvoicesController@viewPurchaseOrderDetail');       //view Purchase Order Detail
Route::post('sales/invoices/get-purchase-orders-listing', 'InvoicesController@getPurchaseOrderDetail');                     //Get Purchase Order Detail
Route::get('sales/invoice/view_order/{order_id}', 'InvoicesController@viewInvoicingReport');                                //View Invoicing Report
Route::post('sales/invoices/process-invoice-cancellation', 'InvoicesController@processInvoiceCancellation');                //Process Invoice Cancellation
Route::post('sales/invoices/get-cancelled-invoice-detail', 'InvoicesController@getCancelledInvoiceDetail');                 //Get Cancelled Invoice Detail
Route::post('sales/invoices/invoice_dispatched_detail', 'InvoicesController@getInvoiceDispatchDetail');                     //Get Invoice dispatch details
Route::get('invoices/edit-invoice-detail/{invoiceId}', 'InvoicesController@editInvoiceDetail');                             //Editing inovice detail
Route::post('invoices/update-invoice-detail', 'InvoicesController@updateInvoiceDetail');                                    //Updating inovice detail

//Inventory item categories
Route::get('inventory/item-categories', 'ItemCategoryController@index');
Route::post('inventory/add-item-category', 'ItemCategoryController@createItemCategory');
Route::post('inventory/get-item-category', 'ItemCategoryController@getItemCategoryList');
Route::post('inventory/get-item-category-multisearch', 'ItemCategoryController@getItemCategoryListMultisearch');
Route::post('inventory/delete-item-category', 'ItemCategoryController@deleteItemCategoryData');
Route::post('inventory/edit-item-category', 'ItemCategoryController@editItemCategoryData');
Route::post('inventory/update-item-category', 'ItemCategoryController@updateItemCategoryData');
Route::post('inventory/get-item-category-list', 'DropdownController@getInventoryItemCategoriesList');
Route::get('inventory/item-categories/get-item-number', 'ItemCategoryController@getAutoGeneratedCode');

//Inventory item master
Route::get('inventory/items', 'ItemController@index');
Route::post('items/add-item', 'ItemController@createItem');
Route::post('items/get-items', 'ItemController@getItemsList');
Route::post('items/get-items-multisearch', 'ItemController@getItemsListMultisearch');
Route::get('items/view-item/{item_id}', 'ItemController@viewItem');
Route::post('items/update-item', 'ItemController@updateItemData');
Route::get('items/delete-item/{item_id}', 'ItemController@deleteItemData');
Route::post('items/upload-item-image', 'ItemController@uploadItemImage');
Route::get('items/delete-item-image/{item_id}', 'ItemController@deleteItemImage');
Route::get('inventory/items/get-item-number', 'ItemController@getAutoGeneratedCode');

//Inventory Vendor master
Route::get('inventory/vendors', 'VendorsController@index');                                                                   //Displaying vendors
Route::post('vendors/create_new_vendor', 'VendorsController@createVendor');                                         //Adding of vendors
Route::get('vendors/list_all_vendors/{divisionId}', 'VendorsController@getVendors');                                //Adding of vendors
Route::get('vendors/delete_vendor/{vendorId}', 'VendorsController@deleteVendor');                                   //deleting vendor
Route::get('vendors/view_vendor/{vendorId}', 'VendorsController@viewVendor');                                       //deleting vendor
Route::post('vendors/edit_vendor', 'VendorsController@editVendor');                                                 //Editing of vendors
Route::get('inventory/vendors/generate-vendor-number', 'VendorsController@getAutoGeneratedCode');

//Setting
Route::get('settings', 'SettingsController@index');

//Requistion section start
Route::get('inventory/requisition-slips', 'RequisitionController@index');
Route::post('requisition/add-requisition', 'RequisitionController@createRequisition');                 //generate requisition slip
Route::post('requisition/get-requisitions/{division_id}', 'RequisitionController@getRequisitionsList');             //get requisition-slips data
Route::post('requisition/delete-requisition', 'RequisitionController@deleteRequisitionData');       //delete requisition slip data
Route::post('requisition/delete-requisition-detail', 'RequisitionController@deleteRequisitionDetail');       //delete requisition slip data
Route::post('requisition/edit-requisition', 'RequisitionController@editRequisitionData');           //edit requisition slip data
Route::post('requisition/update-requisition', 'RequisitionController@updateRequisitionData');           //update-requisition slip data
Route::get('get_item_desc/{item_id}', 'RequisitionController@getItemDesc');                         //update-requisition slip data
Route::get('inventory/get_MRS_inputs', 'RequisitionController@get_MRS_inputs');                               //update-requisition slip data
Route::post('requisition/departments-list', 'RequisitionController@getdepartmentList');
Route::get('requisition/get-items-list/{slug}', 'DropdownController@getSearchItemsList');
Route::get('requisition/get-requisition-number/{sectionName}', 'RequisitionController@getAutoGenCode');

//Requistion section start
Route::get('inventory/indents', 'IndentController@index');
Route::post('indent/add-indent', 'IndentController@createIndent');                 //generate indent slip
Route::post('indent/get-indents/{division_id}', 'IndentController@getIndentsList');             //get indent-slips data
Route::post('indent/delete-indent', 'IndentController@deleteIndentData');       //delete indent slip data
Route::post('indent/edit-indent', 'IndentController@editIndentData');           //edit indent slip data
Route::post('indent/update-indent', 'IndentController@updateIndentData');       //update-indent slip data
Route::get('indent/get-items-list/{slug}', 'DropdownController@getSearchItemsList');
Route::get('inventory/get_indent_inputs', 'IndentController@get_indent_inputs');
Route::post('indent/delete-indent-detail', 'IndentController@deleteIndentDetailsData');
Route::get('indent/get-indent-number/{sectionName}', 'IndentController@getAutoGenCode');

//Inventory Branch Wise Items
Route::get('inventory/branch-items', 'DivisionWiseItemsController@index');                                                //Listing of branch wise Items
Route::get('branch-items/get-division-items/{division_id}', 'DivisionWiseItemsController@getDivisionItems');    //getting of branch wise Items
Route::post('branch-items/add-division-items', 'DivisionWiseItemsController@addDivisionItem');                  //Adding of branch wise Item
Route::get('branch-items/refresh', 'DivisionWiseItemsController@refreshDivisionItem');                          //Refreshing of branch wise Item
Route::post('branch-items/add-division-all-items', 'DivisionWiseItemsController@addDivisionAllItem');           //Adding of branch wise all Items

//Inventory Branch Wise Stores Section
Route::get('inventory/branch-stores', 'DivisionWiseStoresController@index');                                                       //Listing of branch wise stores
Route::get('branch-stores/get_division_stores_list/{divisionId}', 'DivisionWiseStoresController@getDivisionStores');     //Listing of divisions
Route::post('branch-stores/add-division-store', 'DivisionWiseStoresController@createDivisionStore');                     //Adding of branch wise store
Route::get('branch-stores/view-division-store/{storeId}', 'DivisionWiseStoresController@viewDivisionStore');             //Viewing of branch wise store
Route::post('branch-stores/update-division-store', 'DivisionWiseStoresController@updateDivisionStore');                  //Updating of branch wise store
Route::get('branch-stores/delete-division-store/{storeId}', 'DivisionWiseStoresController@deleteDivisionStore');         //Deleting of branch wise store
Route::get('inventory/branch-stores/generate-store-number', 'DivisionWiseStoresController@getAutoGeneratedCode');

//Inventory Branch Wise Item Stock Section
Route::get('inventory/branch-item-stock', 'DivisionWiseItemStocksController@index');                                                     //Viewing
Route::get('branch-item-stock/get-division-item-stock/{divisionId}', 'DivisionWiseItemStocksController@getDivisionStockItem'); //Listing branch wise Item Stock
Route::get('branch-item-stock/get-division-stores/{divisionId}', 'DropdownController@getDivisionStores');                      //Listing of store division wise
Route::get('branch-item-stock/get-items', 'DropdownController@getItemsList');                                                  //Listing of Items
Route::post('branch-item-stock/add-division-item-stock', 'DivisionWiseItemStocksController@createDivisionStockItem');         //Adding of branch wise stock item
Route::get('branch-item-stock/view-division-item-stock/{stockId}', 'DivisionWiseItemStocksController@viewDivisionStockItem'); //Viewing of branch wise store
Route::post('branch-item-stock/update-division-item-stock', 'DivisionWiseItemStocksController@updateDivisionStockItem');      //Updating of branch wise store
Route::get('branch-item-stock/delete-division-item-stock/{stockId}', 'DivisionWiseItemStocksController@deleteDivisionStockItem'); //Deleting of branch wise store

//Purchase Orders(PO) Section
Route::get('inventory/purchase-orders', 'PurchaseOrdersController@index');                                                                //Viewing
Route::get('inventory/purchase-orders/add-po-inputs', 'PurchaseOrdersController@add_po_inputs');                                          //update-requisition slip data
Route::get('purchase-orders/edit-po-inputs', 'PurchaseOrdersController@edit_po_inputs');                                        //update-requisition slip data
Route::get('purchase-orders/get_item_desc/{item_id}', 'RequisitionController@getItemDesc');                                     //update-requisition slip data
Route::get('purchase-orders/get-items-list/{slug}', 'DropdownController@getSearchItemsList');
Route::post('purchase-orders/create-draft-purchase-order', 'PurchaseOrdersController@createDraftPurchaseOrder');                //create Draft Purchase Order
Route::post('purchase-orders/create-purchase-order', 'PurchaseOrdersController@createPurchaseOrder');                           //Create Purchase Order
Route::get('purchase-orders/division-wise-purchase-orders/{division_id}/{po_type}', 'PurchaseOrdersController@getDivisionWiseDraftPOOrPOList'); //List all POs
Route::get('purchase-orders/view-purchase-orders/{po_hr_id}', 'PurchaseOrdersController@viewDraftOrPurchaseOrder');             //Viewing of DPO/PO
Route::get('purchase-orders/delete-purchase-orders/{po_hr_id}', 'PurchaseOrdersController@deleteDraftOrPurchaseOrder');         //Deleting of DPO/PO
Route::get('purchase-orders/generate-purchase-order-number', 'PurchaseOrdersController@generatePurchaseOrderNumber');           //generating purchase order no
Route::post('purchase-orders/update-purchase-order', 'PurchaseOrdersController@updatePurchaseOrder');                           //Update Purchase Order
Route::get('purchase-orders/delete-purchase-order-detail/{po_dtl_id}', 'PurchaseOrdersController@deleteDraftOrPurchaseOrderDetail');  //Deleting of DPO/PO
Route::get('purchase-orders/convert-dpo-to-purchase-orders/{po_hdr_id}', 'PurchaseOrdersController@convertDraftPOToPurchaseOrder');  //convert DPO to PO
Route::post('purchase-orders/amend-purchase-order', 'PurchaseOrdersController@amendPurchaseOrder');                                  //Amend Purchase Order
Route::get('purchase-orders/finalize-purchase-order/{po_hr_id}', 'PurchaseOrdersController@finalizePurchaseOrder');                  //Finalize Purchase Order

//IGN Section
Route::get('inventory/igns', 'IgnsController@index');                                                                                     //Viewing
Route::get('igns/generate-ign-number', 'IgnsController@generateIgnNumber');                                                     //generating IGN no
Route::get('igns/get_employee_list', 'DropdownController@getEmployeeExecutiveList');                                           //Getting Customer List
Route::get('igns/division-wise-ign-list/{division_id}', 'IgnsController@getDivisionWiseIGNList');                             //List all IGNs
Route::post('igns/save-ign-data', 'IgnsController@saveIGNRecord');                                                             //Create Purchase Order
Route::get('igns/delete-ign-record/{ign_hdr_id}', 'IgnsController@deleteIGNRecord');                                           //Deleting of IGN
Route::get('inventory/igns/add-ign-inputs', 'IgnsController@add_ign_inputs');                                                         //add more ign data
Route::get('igns/get-items-list/{slug}', 'DropdownController@getSearchItemsList');                                          //get item list for ign data
Route::get('igns/get-purchase-order-po-nos/{item_id}', 'DropdownController@getPurchaseOrderPONos');                         //update-requisition slip data
Route::get('igns/view-ign-detail/{ign_hdr_id}', 'IgnsController@viewIGNDetail');                                           //View IGN Detail
Route::post('igns/update-ign-data', 'IgnsController@updateIGNRecord');                                                     //Update IGN Detail
Route::get('igns/delete-ign-dtl-row/{ign_hdr_dtl_id}', 'IgnsController@deleteIGNHdrDtlData');                              //Deleting of IGN Dtl Data

//Theme settings
Route::get('theme/button_settings', 'ThemeController@buttonParametersSettings');                                            //Viewing
Route::post('theme/get-btn-parameters', 'ThemeController@getBtnParameters');                                                //Viewing

//Modules Managements
Route::get('roles/modules', 'ModulesController@index');                                                                    //Viewing
Route::get('roles/navigation', 'ModulesController@navigation');                                                            //Viewing
Route::get('roles/permissions', 'PermissionsController@index');                                                            //Viewing
Route::post('roles/get-module-category', 'ModulesController@getModuleCategory');                                           //Getting module Category
Route::post('roles/add-new-module', 'ModulesController@addNewModule');                                                     //Adding of module
Route::get('roles/get-module-list/{module_id}', 'ModulesController@getModuleList');                                        //Listing of module
Route::get('roles/view-module/{module_id}', 'ModulesController@viewModule');                                               //Viewing of module
Route::get('roles/delete-module/{module_id}', 'ModulesController@deleteModule');                                           //Deleting of Module
Route::post('roles/update-module', 'ModulesController@updateModule');                                                      //Updating of Module
Route::get('roles/get-menu-submenu-list/{module_id}/{role_id}', 'ModulesController@getMenuSubmenuList');                   //Viewing of module
Route::post('roles/get-all-modules-list', 'ModulesController@getNavigationAllModulesList');                                //Viewing of module
Route::post('roles/save-all-modules-sorted-list', 'ModulesController@saveNavigationOrdering');                             //Viewing of module
Route::post('roles/add-navigation-module', 'ModulesController@addNavigationModule');                                       //Adding of module
Route::post('roles/save-menu-level-two-sorted-list', 'ModulesController@saveNavigationMenuOrdering');                      //Viewing of module
Route::post('roles/modules/get-modules-multisearch', 'ModulesController@getModulesMultisearch');                           //Viewing of module

//Modules Permissions Managements
Route::get('roles/module-permissions', 'ModulePermissionsController@index');
Route::post('roles/get-all-modules/{level}', 'DropdownController@getAllModulesList');
Route::post('roles/get-roles-category', 'DropdownController@getAllRolesList');
Route::post('roles/get-sub-modules/{module_id}', 'DropdownController@getSubModulesList');
Route::get('roles/get-sub-modules-menue/{role_id}/{module_id}/{sub_module_id}', 'ModulePermissionsController@getSubModuleMenuList');
Route::post('roles/save-module-permissions', 'ModulePermissionsController@saveModulePermissions');

//payment module Managements
Route::get('master/sources', 'PaymentSourcesController@index');
Route::post('payment-sources/save-payment-sources', 'PaymentSourcesController@createPaymentSources');
Route::post('payment-sources/get-payment-sources', 'PaymentSourcesController@getPaymentSources');
Route::post('payment-sources/get-payment-sources-multisearch', 'PaymentSourcesController@getPaymentSourcesMultisearch');
Route::post('payment-sources/edit-payment-sources', 'PaymentSourcesController@editPaymentSources');
Route::post('payment-sources/update-payment-sources', 'PaymentSourcesController@updatePaymentSources');
Route::post('payment-sources/delete-payment-sources', 'PaymentSourcesController@deletePaymentSources');

//Payments Received Managements
Route::get('payments/payment-received', 'PaymentReceivedHdrsController@index');                                                             //Viewing
Route::post('payments/customers-list', 'DropdownController@customersList');                                                                 //getting customer list
Route::post('payments/deposited-with-list', 'DropdownController@getPaymentSources');                                                        //getting deposited with list
Route::get('payments/get-dw-payments-received/{division_id}', 'PaymentReceivedHdrsController@getDivisionWisePaymentsReceived');             //Viewing
Route::post('payments/add-dw-payments-received', 'PaymentReceivedHdrsController@addPaymentsReceived');                                      //adding payment received
Route::get('payments/view-dw-payments-received/{id}', 'PaymentReceivedHdrsController@viewPaymentsReceived');                                //Viewing
Route::post('payments/update-dw-payments-received', 'PaymentReceivedHdrsController@updatePaymentsReceived');                                //adding payment received
Route::get('payments/delete-dw-payments-received/{id}', 'PaymentReceivedHdrsController@deletePaymentReceived');                             //Viewing
Route::get('payments/generate-payment-received-number', 'PaymentReceivedHdrsController@paymentReceivedNumber');                             //generate payment Received No.
Route::post('payment/payment-received/get-payment-received-multisearch', 'PaymentReceivedHdrsController@getPaymentReceivedMultisearch');    //generate payment Received No.

//Payments Made Managements
Route::get('payments/payment-made', 'PaymentMadeHdrsController@index');                                                         //Viewing
Route::post('payments/vendors-list', 'DropdownController@getVendorDataList');                                                   //getting vendor list
Route::post('payments/paid-from-list', 'DropdownController@getPaymentSources');                                                 //getting paid from list
Route::get('payments/get-dw-payments-made/{division_id}', 'PaymentMadeHdrsController@getDivisionWisePaymentsMade');             //Viewing
Route::post('payments/add-dw-payments-made', 'PaymentMadeHdrsController@addPaymentsMade');                                      //adding payment received
Route::get('payments/view-dw-payments-made/{id}', 'PaymentMadeHdrsController@viewPaymentsMade');                                //Viewing
Route::post('payments/update-dw-payments-made', 'PaymentMadeHdrsController@updatePaymentsMade');                                //Updating payment received
Route::get('payments/delete-dw-payments-made/{id}', 'PaymentMadeHdrsController@deletePaymentMade');                             //Deleting
Route::get('payments/generate-payment-made-number', 'PaymentMadeHdrsController@paymentMadeNumber');                             //generate payment Received No.
Route::post('payment/payment-made/get-payment-made-multisearch', 'PaymentMadeHdrsController@getPaymentMadeMultisearch');        //generate payment Received No.

//Payments Debit Notes Managements
Route::get('payments/debit-notes', 'DebitNotesController@index');                                                               //Viewing
Route::get('payments/generate-debit-note-number', 'DebitNotesController@debitNoteNumber');                                      //generate debit note No.
Route::post('payments/add-dw-debit-note', 'DebitNotesController@addDebitNote');                                                 //adding of Debit Note
//Route::get('payments/get-dw-debit-note/{division_id}', 'DebitNotesController@getDivisionWiseDebitNotes');                     //Viewing
Route::get('payments/view-dw-debit-note/{id}', 'DebitNotesController@viewDebitNote');                                           //Viewing
Route::post('payments/update-dw-debit-note', 'DebitNotesController@updateDebitNote');                                           //Updating Debit Note
Route::get('payments/delete-dw-debit-note/{id}', 'DebitNotesController@deleteDebitNote');                                       //Deleting
Route::post('payment/debit-notes/get-payment-debits-multisearch', 'DebitNotesController@getDebitNotesMultisearch');             //Deleting
Route::post('payments/debit-notes/get-invoice-numbers/{customer_id}', 'DebitNotesController@getCustomerInvoiceNumbers');
Route::post('payments/debit-notes/download-excel', 'DebitNotesController@generateDebitNotesMasterDocument');                    // customers excel
Route::post('payments/view-debit-note', 'DebitNotesController@viewDebitNoteDetail');
Route::post('payments/get-dw-debit-note', 'DebitNotesController@getDivisionWiseDebitNotes');                                    //Viewing debit note search criteria 
Route::get('payments/debit-notes/get-invoice-department-detail/{id}', 'InvoicesController@viewInvoice');                        //Viewing of Invoice detail
Route::get('payments/debit-notes/get_customer_list/{state_id}', 'DropdownController@getStateWiseCustomers');         // update customer data

//Payments Credit Notes Managements
Route::get('payments/credit-notes', 'CreditNotesController@index');                                                             //Viewing
Route::get('payments/generate-credit-note-number', 'CreditNotesController@creditNoteNumber');                                   //generate credit note No.
Route::post('payments/add-dw-credit-note', 'CreditNotesController@addCreditNote');                                              //adding of Credit Note
//Route::get('payments/get-dw-credit-note/{division_id}', 'CreditNotesController@getDivisionWiseCreditNotes');                  //Viewing
Route::get('payments/view-dw-credit-note/{id}', 'CreditNotesController@viewCreditNote');                                        //Viewing
Route::post('payments/update-dw-credit-note', 'CreditNotesController@updateCreditNote');                                        //Updating Credit Note
Route::get('payments/delete-dw-credit-note/{id}', 'CreditNotesController@deleteCreditNote');                                    //Deleting
Route::post('payment/credit-notes/get-payment-credit-multisearch', 'CreditNotesController@getCreditNotesMultisearch');          //Deleting
Route::post('payments/credit-notes/get-invoice-numbers/{customer_id}', 'CreditNotesController@getCustomerInvoiceNumbers');
Route::post('payments/credit-notes/download-excel', 'CreditNotesController@generateCreditNotesMasterDocument');                 //customers excel
Route::post('payments/view-credit-note', 'CreditNotesController@viewCreditNoteDetail');                                         //Viewing
Route::post('payments/get-dw-credit-note', 'CreditNotesController@getDivisionWiseCreditNotes');                                 //Viewing credit note search criteria 
Route::get('payments/credit-notes/get-invoice-department-detail/{id}', 'InvoicesController@viewInvoice');                       //Viewing of Invoice detail

//scheduling Managements
Route::get('scheduling/assign-job', 'SchedulingsController@index');                                                                 //Viewing
Route::get('scheduling/get-pending-jobs/{divisionId}', 'SchedulingsController@getPendingJobs');                                     //Getting all Orders
Route::post('scheduling/get-filter-scheduling-jobs', 'SchedulingsController@getPendingJobs');                                       //Getting all Orders
Route::get('scheduling/get-employee-division/{divisionId}', 'DropdownController@getEmployeeBasedOnDivision');                         //Getting sales executive List
Route::post('scheduling/update-scheduling-jobs', 'SchedulingsController@updateSchedulingJobs');                                     //Updating all Scheduling Jobs
Route::get('scheduling/jobs', 'SchedulingsController@jobs');                                                                        //Viewing
Route::post('scheduling/get-assigned-jobs', 'SchedulingsController@getAssignedJobs');                                                //Getting all Orders
//Route::post('scheduling/get-filter-scheduled-assigned-jobs', 'SchedulingsController@getAssignedJobs');                              //Getting all Orders
Route::post('scheduling/update-scheduled-assigned-jobs', 'SchedulingsController@updateScheduledAssignedJobs');                      //Updating all Scheduling Jobs
Route::get('scheduling/job-sheet-print', 'SchedulingsController@jobSheetPrint');                                                    //Job Sheet Print
Route::post('scheduling/get-job-sheet-print-order-number', 'SchedulingsController@getJobSheetPrintOrderNumbers');                    //JobSheetPrint
Route::post('scheduling/get-job-sheet-print-order-list', 'SchedulingsController@getJobSheetPrintOrders');                           //Job Sheet Print
Route::post('scheduling/get-user-parent-product-category', 'DropdownController@getUserParentProductCategory');                 //add test-standards data
Route::get('scheduling/view_order/{order_id}', 'SchedulingsController@viewOrder');                                                   //Viewing of Order
Route::post('scheduling/get-edit-form-dropdowns/{product_category_id}/{parameter_id}', 'SchedulingsController@getEditFormData');     //Viewing of Order
Route::post('scheduling/update-parameters-details', 'SchedulingsController@updateParameterDetails');                                //Viewing of Order
Route::post('scheduling/check-equipment', 'SchedulingsController@checkEquipment');                                                  //Viewing of Order
Route::post('scheduling/generate-analyst-sheet-documents', 'SchedulingsController@generateAnalystSheetDocuments');                  //Download Analyst Sheet Document
Route::post('scheduling/delete-order-parameter', 'SchedulingsController@deleteOrderParameter');                                      //delete Order Parameter from scheduling
Route::post('scheduling/get-add-form-dropdowns/{order_id}', 'SchedulingsController@getAddFormData');                                 //Viewing of Order
Route::post('scheduling/save-parameters-details', 'SchedulingsController@saveParameterDetails');                                    //Viewing of Order
Route::post('scheduling/get-parameters-details', 'SchedulingsController@getParameterDetails');                                      //Viewing of Order
Route::post('scheduling/get-all-form-data/{order_id}', 'SchedulingsController@getAllFormData');                                      //Viewing of Order
Route::post('scheduling/edit-get-product-test-parameters-list/{test_id}/{order_id}', 'SchedulingsController@getEditProductTestParametersList');        //Getting Test Standard Parameter List
Route::post('scheduling/edit-get-product-test-parameters', 'SchedulingsController@getEditProductTestParameters');                    //Viewing of Order
Route::post('scheduling/update-expected-due-date', 'SchedulingsController@updateOrderExpectedDueDate');
Route::post('scheduling/export-assign-job', 'SchedulingsController@generateAssignedJobDocuments');                                   //Export Assign Jobs
Route::post('scheduling/generate-assign-jobs-documents', 'SchedulingsController@generateAssignedJobDocuments');                      //Export Assign Jobs
Route::post('scheduling/get-scheduling-unhold-jobs', 'SchedulingsController@getSchedulingUnholdJobs');                               //Export Assign Jobs

//Customer Product Master Module
Route::get('master/invoicing/customer-product-master', 'ProductMasterAliasController@index');                                             //display list of customer product master
Route::get('master/invoicing/customer-product-master/get-customer-products/{product_id}', 'ProductMasterAliasController@getCustomerProductList');     //get customer product master list
Route::post('master/invoicing/customer-product-master/add-customer-product', 'ProductMasterAliasController@addCustomerProduct');         //add customer product master
Route::get('master/invoicing/customer-product-master/edit-customer-product/{c_product_id}', 'ProductMasterAliasController@viewCustomerProduct');       //view/edit customer product master
Route::post('master/invoicing/customer-product-master/update-customer-product', 'ProductMasterAliasController@updateCustomerProduct');   //update customer product master
Route::get('master/invoicing/customer-product-master/delete-customer-product/{c_product_id}', 'ProductMasterAliasController@deleteCustomerProduct');     //delete customer product master
Route::post('master/invoicing/customer-product-master/get-products-list/{product_id}', 'ProductMasterAliasController@getCustomerProductMasterAliasList');     //delete customer product master
Route::post('master/invoicing/customer-product-master/get-products-list', 'ProductMasterAliasController@getProductMasterAliasList');     //delete customer product master
Route::post('master/invoicing/customer-product-master/edit-all-customer-product-aliases', 'ProductMasterAliasController@viewCustomerAllProductAliases');

//Customer Invoicing Section : state-wise-product-rates
Route::get('master/invoicing/state-wise-product-rates', 'InvoicingTypeStateWiseProductsController@index');            //state wise product rates
Route::post('master/invoicing/state-wise-product-rates/add-state-wise-product-rates', 'InvoicingTypeStateWiseProductsController@createStateWiseProductRates');
Route::post('master/invoicing/state-wise-product-rates/get-state-wise-product-rates', 'InvoicingTypeStateWiseProductsController@getStateWiseProductRates');
Route::get('master/invoicing/state-wise-product-rates/view-state-wise-product-rates/{id}', 'InvoicingTypeStateWiseProductsController@viewStateWiseProductRate');
Route::post('master/invoicing/state-wise-product-rates/update-state-wise-product-rates', 'InvoicingTypeStateWiseProductsController@updateStateWiseProductRate');
Route::get('master/invoicing/state-wise-product-rates/delete-state-wise-product-rates/{id}', 'InvoicingTypeStateWiseProductsController@deleteInvoicingTypeRate');
Route::get('master/invoicing/state-wise-product-rates/get-selected-state-product-rates/{state_id}/{cirId}/{divId}', 'InvoicingTypeStateWiseProductsController@getSelectedStateProductRate');
Route::post('master/invoicing/state-wise-product-rates/product-alias-states-list', 'DropdownController@getAliasProductStatesList');
Route::get('master/invoicing/state-wise-product-rates/get-states-acc-dept/{deptId}', 'InvoicingTypeStateWiseProductsController@getStatesAccToDept');

//Customer Invoicing Section : customer-wise-product-rates
Route::get('master/invoicing/customer-wise-product-rates', 'InvoicingTypeCustomerWiseProductsController@index');          //customer wise product rates
Route::post('master/invoicing/customer-wise-product-rates/product-list', 'DropdownController@getCustomerProductList');
Route::post('master/invoicing/customer-wise-product-rates/add-customer-wise-product-rates', 'InvoicingTypeCustomerWiseProductsController@createCustomerWiseProductRate');
Route::post('master/invoicing/customer-wise-product-rates/get-customer-wise-product-rates', 'InvoicingTypeCustomerWiseProductsController@getCustomerWiseProductRates');
Route::get('master/invoicing/customer-wise-product-rates/get_cities_list/{stateId}', 'DropdownController@getStateWiseCities');                    // update customer data
Route::get('master/invoicing/customer-wise-product-rates/get_customer_list/{state_id}', 'DropdownController@getStateWiseCustomers');         // update customer data
Route::get('master/invoicing/customer-wise-product-rates/view-customer-wise-product-rates/{cir_id}', 'InvoicingTypeCustomerWiseProductsController@viewCustomerWiseProductRate');         //edit customer wise product
Route::post('master/invoicing/customer-wise-product-rates/update-customer-wise-product-rates', 'InvoicingTypeCustomerWiseProductsController@updateCustomerWiseAllProductRate');         //edit all customer wise product
Route::post('master/invoicing/customer-wise-product-rates/update-customer-wise-fixed-product-rates', 'InvoicingTypeCustomerWiseProductsController@updateCustomerWiseFixedProductRate');         //edit fixed price customer wise product
Route::get('master/invoicing/customer-wise-product-rates/delete-customer-wise-product-rates/{cir_id}', 'InvoicingTypeCustomerWiseProductsController@deleteInvoicingTypeRate');         //edit customer wise product
Route::post('master/invoicing/customer-wise-product-rates/get-customer-city/{customer_id}', 'InvoicingTypeCustomerWiseProductsController@getCustomerCity');         //edit customer wise product
Route::get('master/invoicing/customer-wise-product-rates/get-selected-customer-product-rates/{customer_id}/{cirId}/{divID}', 'InvoicingTypeCustomerWiseProductsController@getSelectedCustomerProductRate');
Route::post('master/invoicing/customer-wise-product-rates/product-alias-customers-list', 'DropdownController@getAliasProductCustomerList');

//Customer Invoicing Section : customer-wise-parameter-rates
Route::get('master/invoicing/customer-wise-parameter-rates', 'InvoicingTypeCustomerWiseParametersController@index');
Route::post('master/invoicing/customer-wise-parameter-rates/parameters-customers-list', 'InvoicingTypeCustomerWiseParametersController@getParameterCustomerList');
Route::get('master/invoicing/customer-wise-parameter-rates/get-customer-wise-parameter-rates/{customer_id}', 'InvoicingTypeCustomerWiseParametersController@getCustomerWiseParametersRates');
Route::post('master/invoicing/customer-wise-parameter-rates/get-customer-wise-parameter-rates', 'InvoicingTypeCustomerWiseParametersController@getCustomerWiseParametersRates');
Route::post('master/invoicing/customer-wise-parameter-rates/parameter-list/{product_cat_id}/{parameter_cat_id}', 'DropdownController@getCustomerWiseParametersList');
Route::post('master/invoicing/customer-wise-parameter-rates/parameter-category-list/{product_cat_id}', 'DropdownController@getCustomerWiseParametersCategoryList');
Route::post('master/invoicing/customer-wise-parameter-rates/add-customer-wise-parameter-rates', 'InvoicingTypeCustomerWiseParametersController@createCustomerWiseParametersRate');
Route::get('master/invoicing/customer-wise-parameter-rates/delete-customer-wise-parameter-rates/{cir_id}', 'InvoicingTypeCustomerWiseParametersController@deleteCustomerWiseParameterRate');         //edit customer wise product
Route::post('master/invoicing/customer-wise-parameter-rates/get-selected-customer-parameter-rates', 'InvoicingTypeCustomerWiseParametersController@getSelectedCustomerParameterRate');                  //edit customer wise product
Route::post('master/invoicing/customer-wise-parameter-rates/edit-selected-customer-parameter-rates', 'InvoicingTypeCustomerWiseParametersController@editSelectedCustomerParameterRate');         //edit customer wise product
Route::post('master/get-product-test-std/{product_id}', 'InvoicingTypeCustomerWiseParametersController@getProductTestStd');
Route::post('master/invoicing/customer-wise-parameter-rates/update-customer-wise-parameter-rates', 'InvoicingTypeCustomerWiseParametersController@UpdateCustomerWiseParametersRate');

//Customer Invoicing Section : customer-wise-aasay-parameter-rates
Route::get('master/invoicing/customer-wise-assay-parameter-rates', 'InvoicingTypeCustomerWiseAssayParametersController@index');                                                 //Listing Page
Route::post('master/invoicing/customer-wise-assay-parameter-rates/get-product-category-level-one/{id}', 'DropdownController@getProductCategoryLevelOne');                       //Get Product category Level One
Route::post('master/invoicing/customer-wise-assay-parameter-rates/get-product-category-level-two/{id}', 'DropdownController@getProductCategoryLevelOne');                       //Get Product category Level One
Route::post('master/invoicing/customer-wise-assay-parameter-rates/parameter-category-list/{id}', 'DropdownController@getCustomerWiseParametersCategoryList');                   //Get Product category Level One
Route::post('master/invoicing/customer-wise-assay-parameter-rates/get-test-parameter-list/{name}/{id}', 'DropdownController@getTestParametersAccToCategory');                   //edit customer wise product
Route::post('master/invoicing/customer-wise-assay-parameter-rates/get-equipment-accto-parameter-list/{id}', 'DropdownController@getEquipmentAccToCustomerWAParameterList');
Route::post('master/invoicing/customer-wise-assay-parameter-rates/get-detector-accto-equipment-list/{eId}/{pId}', 'DropdownController@getDetectorAccToEquipment');
Route::post('master/invoicing/customer-wise-assay-parameter-rates/get-running-time-list', 'DropdownController@getRunningTimeList');
Route::post('master/invoicing/customer-wise-assay-parameter-rates/add-customer-wise-assay-parameter-rates', 'InvoicingTypeCustomerWiseAssayParametersController@createCustomerWiseAssayParametersRate');
Route::post('master/invoicing/customer-wise-assay-parameter-rates/get-customer-wise-assay-parameter-rates', 'InvoicingTypeCustomerWiseAssayParametersController@getCustomerWiseAssayParametersRates');
Route::post('master/invoicing/customer-wise-assay-parameter-rates/edit-customer-wise-assay-parameter-rates', 'InvoicingTypeCustomerWiseAssayParametersController@editCustomerWiseAssayParameterRates');         //edit customer wise product
Route::post('master/invoicing/customer-wise-assay-parameter-rates/update-customer-wise-assay-parameter-rates', 'InvoicingTypeCustomerWiseAssayParametersController@updateCustomerWiseAssayParametersRates');
Route::post('master/invoicing/customer-wise-assay-parameter-rates/delete-customer-wise-assay-parameter-rates/{cir_id}', 'InvoicingTypeCustomerWiseAssayParametersController@deleteCustomerWiseAssayParameterRate');         //edit customer wise product

//date setting (04-08-2017)
Route::get('settings/default-setting', 'SettingsController@defaultSetting');
Route::post('settings/get-default-setting-list', 'SettingsController@getdefaultSettingList');                      // get  default setting  list
Route::post('settings/get-setting-group', 'DropdownController@getSettingGroup');                                   // get  setting groups detail
Route::post('settings/save-default-setting', 'SettingsController@addDefaultSettings');                             // get  setting groups detail
Route::post('settings/delete-default-setting', 'SettingsController@deleteDefaultSettings');                        // get  setting groups detail
Route::post('settings/get-group-wise-setting-list', 'SettingsController@getGroupWiseSettingList');                 // get  setting groups detail
Route::post('settings/edit-default-setting', 'SettingsController@editDefaultSettings');                            // get  setting groups detail
Route::post('settings/update-default-setting', 'SettingsController@UpdateDefaultSettings');                        // get  setting groups detail

//MIS Reports Section
Route::get('MIS/reports/all', 'MISReportsController@index');                                                           //MIS REPORT ALL FORM
Route::get('MIS/reports/DBD001', 'MISReportsController@index');                                                        //Daily Booking Detail
Route::get('MIS/reports/PWSCDW002', 'MISReportsController@index');                                                     //PARTY WISE SAMPLE COUNT-DATE WISE FORM
Route::get('MIS/reports/PWSCMW003', 'MISReportsController@index');                                                     //PARTY WISE SAMPLE COUNT-MONTH WISE FORM
Route::get('MIS/reports/MEWBEN004', 'MISReportsController@index');                                                     //MARKETING EXECUTIVE WISE-BY EXECUTIVE NAME
Route::get('MIS/reports/MEWBPWSC005', 'MISReportsController@index');                                                   //MARKETING EXECUTIVE WISE-BY PLACE WISE SAMPLE COUNT FORM
Route::get('MIS/reports/TAT006', 'MISReportsController@index');                                                        //TAT REPORT FORM
Route::get('MIS/reports/UWPD007', 'MISReportsController@index');                                                       //USER WISE PERFORMANCE DETAIL FORM
Route::get('MIS/reports/SLS008', 'MISReportsController@index');                                                        //SAMPLE LOG STATUS FORM
Route::get('MIS/reports/PWS009', 'MISReportsController@index');                                                        //PARAMETER WISE SCHEDULINGFORM
Route::get('MIS/reports/SRD010', 'MISReportsController@index');                                                        //SALES REPORT DETAIL FORM
Route::get('MIS/reports/DID011', 'MISReportsController@index');                                                        //DAILY INVOICE DETAIL FORM
Route::get('MIS/reports/IWPD012', 'MISReportsController@index');                                                       //INSTRUMENT WISE PERFORMANCE DETAIL FORM
Route::get('MIS/reports/AWPS013', 'MISReportsController@index');                                                       //ANALYST WISE PERFORMANCE SUMMARY FORM
Route::get('MIS/reports/BCD014', 'MISReportsController@index');                                                        //BOOKING CANCELLATION DETAIL
Route::get('MIS/reports/BAD015', 'MISReportsController@index');                                                        //BOOKING AMENDMENT DETAIL
Route::get('MIS/reports/DSD016', 'MISReportsController@index');                                                        //DAILY SALES DETAIL
Route::get('MIS/reports/DESD017', 'MISReportsController@index');                                                       //DELAY STATUS DETAIL
Route::get('MIS/reports/ASR018', 'MISReportsController@index');                                                        //ACCOUNT SALES DETAIL
Route::get('MIS/reports/ESTR19', 'MISReportsController@index');                                                        //Employee Sales Target Report
Route::get('MIS/get-mis-report-types', 'MISReportsController@getMISReportTypes');                                       //Generate MIS Report Types
Route::get('MIS/get-mis-report-types-dtl', 'DropdownController@getMISReportTypesDtl');                                  //Generate MIS Report Types
Route::post('MIS/generate-mis-report', 'MISReportsController@generateMISReport');                                       //Generate MIS Report
Route::get('MIS/get-default-roles', 'DropdownController@getDefaultUserRoles');                                          //Get dafault user roles
Route::post('MIS/get-user-by-user-roles/{id}', 'DropdownController@getUserByUserRoles');                                //get user by user roles
Route::post('MIS/get-user-by-user-role-names', 'DropdownController@getUserByUserRoleNames');                            //get user by user roles
Route::post('MIS/generate-mis-report-doc', 'MISReportsController@generateMISReportDocument');                           //Generate MIS Report
Route::post('MIS/default-order-stage-phases', 'DropdownController@defaultOrderStagePhases');                            //Generate MIS Report
Route::post('MIS/view-mis-status-log-detail', 'MISReportsController@viewSampleLogStatusDetail');                        //Generate MIS Report
Route::get('MIS/get-customer-list-from-sales-excecutive/{id}', 'DropdownController@getCustomerListBasedOnSalesExecutive');         //Getting Employee Name based on Branch

//Scheduled MIS Report Section
Route::get('MIS/scheduled-reports', 'ScheduledMisReportDtlController@index');                                           //MIS Scheduled Report Window
Route::post('MIS/scheduled-reports/add', 'ScheduledMisReportDtlController@saveScheduledMisReport');                     //Saving of MIS Scheduled Report
Route::post('MIS/scheduled-reports/list', 'ScheduledMisReportDtlController@listScheduledMisReport');                    //Listing of MIS Scheduled Report
Route::post('MIS/scheduled-reports/edit', 'ScheduledMisReportDtlController@editScheduledMisReport');                    //Editing of MIS Scheduled Report
Route::post('MIS/scheduled-reports/update', 'ScheduledMisReportDtlController@updateScheduledMisReport');                //Updating of MIS Scheduled Report
Route::post('MIS/scheduled-reports/delete', 'ScheduledMisReportDtlController@deleteScheduledMisReport');                //Deleting of MIS Scheduled Report

//download section
Route::post('master/customer/download-excel', 'DownloadController@generateCustomerMasterDocuments');                         // customers excel
Route::post('master/test-parameter/download-excel', 'TestParametersController@getTestParameterDownload');                       //parameter excel
Route::post('standard-wise-product/product-test/download-excel', 'DownloadController@generateTestMasterDocuments');                // product test excel
Route::post('standard-wise-product/product-test/generate-test-master-documents', 'DownloadController@generateTestMasterDocuments');         // product test excel
Route::post('sales/orders/generate-branch-wise-order-pdf', 'OrdersController@generateBranchWiseOrderPdf');                                                       //Adding of Orders
Route::post('sales/reports/generate-branch-wise-report-pdf', 'ReportsController@generateBranchWiseReportPdf');                              //generate report sheet pdf
Route::post('sales/invoices/generate-branch-wise-invoices-pdf', 'InvoicesController@generateBranchWiseInvoicesPdf');                        //Listing of all invoices
Route::post('sales/samples/generate-branch-wise-sample-pdf', 'SamplesController@getBranchWiseSamplesPdf');                                  //Listing of Sample Action
Route::post('master/standard-wise-product/generate-test-parameter-list-pdf', 'StandardWiseProductTestController@generateProductTestPdf');
Route::post('sales/orders/generate-job-order-pdf', 'OrdersController@generateJobOrderPdf');                                                 //Genereting Job Order PDF
Route::get('sales/orders/generate-job-order-pdf/{orderId}', 'OrdersController@testing');
Route::post('sales/orders/upload_order_pdf', 'OrdersController@uploadOrderPdf');                                                            //Generating upload_order_pdf
Route::post('reports/upload_report_pdf', 'ReportsController@uploadReportPdf');                                                              //deleting an report
Route::post('invoices/upload_invoice_pdf', 'InvoicesController@uploadInvoicePdf');                                                          //upload an invoice pdf
Route::post('scheduling/generate-analytical-sheet-pdf', 'SchedulingsController@generateAnalyticalSheetPdf');                                //Generate Analytical Sheet
Route::post('scheduling/generate-analyst-sheet-pdf', 'SchedulingsController@generateAnalystSheetPdf');                                      //Generate Analyst Sheet
Route::post('scheduling/generate-job-sheet-pdf', 'SchedulingsController@generateJobSheetPdf');                                              //Viewing of Order
Route::post('sales/invoices/generate-invoice-related-reports-pdf', 'DownloadController@generateInvoiceRelatedReportPdf');                   //generate Invoice Reports Pdf
Route::post('master/detector/download', 'DownloadController@generateDetectorsDocuments');                                // detector downloads
Route::post('master/equipments/download', 'DownloadController@generateEquipmentsDocuments');                                       // equipments downloads
Route::post('master/methods/download', 'DownloadController@generateMethodsDocuments');                                     //methods list  
Route::post('master/test-standard/download', 'DownloadController@generateTestStandardDocuments');                             //test standard list
Route::post('master/test-parameter-categories/download', 'DownloadController@generateTestParameterCategoriesDocuments');                    //test parameter categories list
Route::post('master/test-product-categories/download', 'DownloadController@generateTestProductCategoriesDocuments');                        //test product categories list
Route::post('master/test-products/download', 'DownloadController@generateTestProductsDocuments');                                           //test products list
Route::post('master/departments/download-excel', 'DownloadController@generateDepartmentMasterDocuments');                         // department excel
Route::post('master/states/download-excel', 'DownloadController@generateStateMasterDocuments');                                             // state master excel
Route::post('master/cities/download-excel', 'DownloadController@generateCityMasterDocuments');                                 // city master excel
Route::post('master/employees/download-excel', 'DownloadController@generateEmployeesMasterDocuments');                             // employees excel
Route::post('master/product-alias/download-excel', 'DownloadController@generateProductAliasMasterDocuments');                         // product alias excel
Route::post('master/state-wise-products/download-excel', 'DownloadController@generateStateWiseProductRatesDocuments');                     // state wise products excel
Route::post('master/customer-wise-products/download-excel', 'DownloadController@generateCustomerWiseProductRatesDocuments');                // customer wise products excel
Route::post('master/customer-wise-parameters/download-excel', 'DownloadController@generateCustomerWiseParametersDocument');                 // customer wise products excel
Route::post('master/customer-wise-assay-parameters/download-excel', 'DownloadController@generateCustomerWiseAssayParametersDocument');      // customer wise products excel
Route::post('master/country/download-excel', 'DownloadController@generateCountryMasterDocuments');                                          // state master excel
Route::post('sales/invoices/get-related-reports-detail', 'InvoicesController@getRelatedReports');                                                  // delete hold data list
Route::post('sales/invoices/download-related-invoice-reports-document', 'DownloadController@getRelatedInvoiceReportDocument');

//***************PDF generation**********************//
Route::post('sales/orders/generate-analytical-sheet-I-pdf', 'DownloadController@generateAnalyticalSheetIPdf');                      //Generate Analytical Sheet-I Pdf
Route::post('scheduling/generate-analytical-sheet-II-pdf', 'DownloadController@generateAnalyticalSheetIIPdf');                      //Generate Analytical Sheet-II Pdf
Route::post('sales/reports/generate-report-pdf', 'DownloadController@generateReportPdf');                                           //Generate Report PDF
Route::post('sales/invoices/generate-invoice-pdf', 'DownloadController@generateInvoicePdf');                                        //Generate Invoice PDF
Route::post('payments/credit-notes/generate-credit-note-pdf', 'DownloadController@generateCreditNotePdf');                                        //Generate Invoice PDF
Route::post('payments/debit-notes/generate-debit-note-pdf', 'DownloadController@generateDebitNotePdf');                                        //Generate Invoice PDF

//detector section start
Route::get('master/detectors-master', 'DetectorController@index');
Route::post('master/detector/add-detector', 'DetectorController@createDetector');                                     // add method data
Route::post('master/get-detector/{equipment_type_id}', 'DetectorController@getDetectorsList');                              // get method data
Route::post('master/detector/get-detectors-multisearch', 'DetectorController@getDetectorListMultiSearch');                      // get method data
Route::post('master/detector/delete-detector', 'DetectorController@deleteDetectorData');                                 // delete method data list
Route::post('master/detector/edit-detector', 'DetectorController@editDetectorData');                                      // edit method data list
Route::post('master/detector/update-detector', 'DetectorController@updateDetectorData');                                  // update method data list
Route::get('master/detector/generate-detector-number', 'DetectorController@getAutoGeneratedCode');                          // update equipment data list
Route::post('master/detectors/upload-detectors-csv', 'DetectorController@uploadDetectorsCSV');                                 // add products data

//customer-defined-invoicing-type section start
Route::get('master/customer-defined-structures', 'CustomerDefinedStructureController@index');
Route::post('customer/get-all-customer-list', 'CustomerDefinedStructureController@getCustomerList');                         // get customer data list
Route::post('customer/add-customer-defined-structure', 'CustomerDefinedStructureController@createCustomer');                             // add customer data
Route::post('customer/get-customers-defined-structure-list', 'CustomerDefinedStructureController@getAllCustomerDataList');                         // get customer data list
Route::post('customer/customer-defined-structure/delete', 'CustomerDefinedStructureController@deleteCustomerData');                    // delete customer data
Route::post('customer/customer-defined-structure/edit', 'CustomerDefinedStructureController@editCustomerData');                         // edit customer data
Route::post('customer/customer-defined-structure/update', 'CustomerDefinedStructureController@updateCustomerData');                 // update customer data
Route::post('customer/get-customers-defined-structure-multisearch', 'CustomerDefinedStructureController@getAllCustomerDataList');                     // update customer data

//Test Parameter Invoicing Parents Section
Route::post('master/test-parameter/test-parameter-invoicing-parents', 'DropdownController@getTestParameterInvoicingParents');             // update customer data

//Default Remarks Nots Section
Route::get('master/default-remarks', 'OrderReportNoteRemarkDefaultController@index');
Route::post('master/get-default-remarks', 'OrderReportNoteRemarkDefaultController@getBranchWiseDefaultRemarks');                                // get branch and deprtmentwise remark data// get remark data
Route::post('master/add-default-remarks', 'OrderReportNoteRemarkDefaultController@createDefaultRemarks');                                                           // adding remark data
Route::post('master/edit-default-remarks', 'OrderReportNoteRemarkDefaultController@editDefaultRemarkData');                                                         // editing remark data
Route::post('master/update-default-remarks', 'OrderReportNoteRemarkDefaultController@updateDefaultRemarksData');                                                    // update remark data
Route::post('master/delete-default-remarks', 'OrderReportNoteRemarkDefaultController@deleteDefaultRemarksData');

//Custom Defined Fields
Route::get('master/custom_defined_fields', 'CustomDefinedFieldsController@index');
Route::post('master/get-custom-defined-list', 'CustomDefinedFieldsController@getCustomDefinedFields');                                                                // get branch and deprtmentwise custom label
Route::post('master/add-custom-defined-fields', 'CustomDefinedFieldsController@createCustomDefinedFields');                                                           // adding custom label data
Route::post('master/edit-custom-defined-fields', 'CustomDefinedFieldsController@editCustomDefinedFields');                                                            //editing custom label data
Route::post('master/update-custom-defined-fields', 'CustomDefinedFieldsController@updateCustomDefinedFields');                                                        // update custom label data
Route::post('master/delete-custom-defined-fields', 'CustomDefinedFieldsController@deleteCustomDefinedFields');

//reports template section start 14-05-2018
Route::get('master/templates', 'TemplateController@index');
Route::post('master/get-templates-list', 'TemplateController@getTemplateList');
Route::post('master/templates/add-report-template', 'TemplateController@createReportTemplate');                                     // add template header footer data
Route::post('master/templates/edit-report-template', 'TemplateController@editReportTemplate');                                      // edit template header footer data                                  
Route::post('master/templates/update-report-template', 'TemplateController@updateReportTemplate');                                  // edit template header footer data                                  
Route::post('master/templates/delete-report-template', 'TemplateController@deleteReportTemplate');                                  // delete template header footer data                                  
Route::post('master/templates/get-report-template-multisearch', 'TemplateController@getTemplateList');
Route::post('master/template/get-template-type-list', 'DropdownController@getTemplateTypeList');

//Order Cancellation Modules
Route::get('sales/orders/get-cancellation-type-list', 'DropdownController@getCancellationTypeList');                                //Geting Cancellation Type List dropdown
Route::post('orders/cancel-order-booking', 'OrderCancellationsController@cancelOrderBooking');                                      //Cancellation of Orders
Route::post('orders/get-cancel-order-booking-detail', 'OrderCancellationsController@getCancelledOrderDetail');                      //Cancelled Orders Detail

//Dashboard Cancellation Modules
Route::get('dashboard/get-content-acto-roles', 'DashboardController@getDashboardContent');                                          //get branch and deprtmentwise custom label

//Auto Printing Section
Route::get('auto-print', 'WebClientPrintsController@sendMultipleFileToPrinter');                                                    //get branch and deprtmentwise custom label

//accreditation certificate master section start
Route::get('master/accreditation-certificate-master', 'orderAccreditationCertificateMasterController@index');
Route::post('master/add-accreditation-certificate-master', 'orderAccreditationCertificateMasterController@createAccreditationCertificate');         //add division data
Route::post('master/list-accreditation-certificate-master', 'orderAccreditationCertificateMasterController@getCertificatesList');                 //get division data
Route::post('master/delete-accreditation-certificate-master', 'orderAccreditationCertificateMasterController@deleteAccreditationCertificate');       //delete division data list
Route::post('master/edit-accreditation-certificate-master', 'orderAccreditationCertificateMasterController@editAccreditationCertificate');              //edit division data list
Route::post('master/update-accreditation-certificate-master', 'orderAccreditationCertificateMasterController@updateAccreditationCertificate');          //edit division data list
Route::post('master/search-accreditation-certificate-master', 'orderAccreditationCertificateMasterController@multiSearchAccreditationCertificate');     //edit division data list

/**********************************************************
Stability Orders Prototype Management Section
 ***********************************************************/
Route::get('sales/stability-orders', 'StabilityOrderPrototypesController@index');                                                                                   //Stability Orders Listing
Route::get('sales/stability-orders/get-customer-attached-sample-detail/{sampleid}', 'StabilityOrderPrototypesController@getCustomerAttachedSampleDetail');          //Getting all Samples
Route::get('sales/stability-orders/get-test-sample-received', 'DropdownController@getSamplesReceived');                                                             //Getting all Samples
Route::get('sales/stability-orders/get_sample_priority_list', 'DropdownController@getSamplePriorityList');                                                          //Getting get sample priority List
Route::get('sales/stability-orders/get_customer_list/{state_id}', 'DropdownController@getStateWiseCustomers');                                                     //update customer data
Route::post('sales/stability-orders/create-stability-order-cs', 'StabilityOrderPrototypesController@createCustomerSampleStabilityOrder');                           //Saving Customer & Sample of StabilityOrder
Route::get('sales/stability-orders/get-sample-name-list/{id}/{text}', 'StabilityOrderPrototypesController@getAutoCompleteStabilityOrderSampleNames');               //Getting Auto Complete Sample Names
Route::get('sales/stability-orders/get_sealed_unsealed_list', 'DropdownController@getSealedUnsealedList');                                                          //Getting Sealed/Unsealed List
Route::get('sales/stability-orders/get_signed_unsigned_list', 'DropdownController@getSignedUnsignedList');                                                          //Getting Signed/Unsigned List
Route::post('sales/stability-orders/get-sample-modes', 'DropdownController@getSampleModes');                                                                        //Sample Mode
Route::post('sales/stability-orders/get_list', 'StabilityOrderPrototypesController@getStabilityOrdersList');                                                        //Sample Mode
Route::post('sales/stability-orders/generate-branch-wise-order-pdf', 'StabilityOrderPrototypesController@generateBranchWiseStabilityOrderPdf');                     //Adding of Stability Orders
Route::get('sales/stability-orders/edit-stability-order/{stb_order_hdr_id}', 'StabilityOrderPrototypesController@viewStabilityOrder');                              //Viewing of Stability Order
Route::get('sales/stability-orders/get-edit-customer-attached-sample-detail/{stb_order_hdr_id}/{sampleid}', 'StabilityOrderPrototypesController@getEditCustomerAttachedSampleDetail');    //Getting all Samples
Route::post('sales/stability-orders/customer_billing_type_list', 'DropdownController@getGlobalCustomerbillingTypes');                                               //Getting billing Types
Route::post('sales/stability-orders/customer-invoicing-types-list', 'DropdownController@getGlobalCustomerInvoicingTypes');                                          //Getting Invoicing Types
Route::post('sales/stability-orders/discount-types-list', 'DropdownController@getGlobalCustomerDiscountTypes');                                                     //Getting Discount Types
Route::post('sales/stability-orders/update-stability-order-cs', 'StabilityOrderPrototypesController@updateCustomerSampleStabilityOrder');                           //Updating Customer & Sample of StabilityOrder
Route::get('sales/stability-orders/get-stability-condition-master', 'DropdownController@getStabilityConditionMaster');                                              //Getting Stability Condition Master
Route::get('sales/stability-orders/get-product-tests/{product_id}', 'DropdownController@getProductTests');                                                          //Getting Test Standard List
Route::post('sales/stability-orders/get-product-test-master-tabular-list', 'StabilityOrderPrototypesController@getProductTestMasterTabularList');                   //Getting Product Test Master Tabular List
Route::post('sales/stability-orders/save-prototype-stability-order', 'StabilityOrderPrototypesController@savePrototypeOfStabilityOrder');                           //Getting Product Test Master Tabular List
Route::post('sales/stability-orders/get-added-stability-order-prototype-list', 'StabilityOrderPrototypesController@getAddedStabilityOrderPrototypes');              //Getting Added Stability Order Prototypes List
Route::post('sales/stability-orders/edit-added-stability-order-prototype-list', 'StabilityOrderPrototypesController@editAddedStabilityOrderPrototypes');            //Editing Added Stability Order Prototypes List
Route::post('sales/stability-orders/update-prototype-stability-order', 'StabilityOrderPrototypesController@updatePrototypeOfStabilityOrder');                       //Updating Product Test Master Tabular List
Route::get('sales/stability-orders/delete-prototype-stability-order/{id}', 'StabilityOrderPrototypesController@deletePrototypeStabilityOrder');                      //Deleting the Stability Order Prototype
Route::post('sales/stability-orders/create-order-from-stability-order-prototypes', 'StabilityOrderPrototypesController@createOrderFromStabilityOrderPrototypes');    //Creating Order From Stability Order Prototypes
Route::get('sales/stability-orders/send-stability-order-mail/{id}', 'StabilityOrderPrototypesController@sendPrototypeStabilityOrderMail');                           //Deleting the Stability Order Prototype
Route::get('sales/stability-orders/delete-stability-order/{id}', 'StabilityOrderPrototypesController@deleteStabilityOrder');                                         //Deleting the Stability Order Prototype
Route::post('sales/stability-orders/get-selected-testparameters-check-all', 'StabilityOrderPrototypesController@getSelectedTestParametersCheckAll');                 //Get Selected Test Parameters Check All
Route::post('sales/stability-orders/download-bw-stability-test-format-report', 'DownloadController@downloadBWStabilityTestFormatReport');                                //Get Selected Test Parameters Check All

/**********************************************************
Stability Orders Notofication Management Section
 ***********************************************************/
Route::get('sales/stability-notifications', 'StabilityOrdersController@index');                                                                                     //Stability Notification Listing
Route::post('sales/stability-orders/get-stability-order-notifications', 'StabilityOrdersController@getStabilityOrderNotificationList');                             //Getting Stability Order Notification List
Route::post('sales/stability-orders/get-stability-order-prototypes-detail', 'StabilityOrdersController@getStabilityOrderPrototypesDetail');                         //Creating Order From Stability Order Prototypes
Route::post('sales/stability-orders/get-final-preview-of-each-stability-prototypes', 'StabilityOrdersController@getFinalPreviewStabilityOrder');                    //Viewing of Order
Route::post('sales/stability-orders/add-order', 'StabilityOrdersController@createOrder');                                                                           //Adding of Orders
Route::post('sales/stability-orders/send-stability-order-notification-mail', 'StabilityOrdersController@sendMailNotificationOfStabilityOrder');                     //Send Mail Notification Of Stability Order

/**********************************************************
Amendment Master Management Section (27-12-2018)
 ***********************************************************/
Route::get('master/amendment-list', 'AmendmentController@index');                                      // list amendment data
Route::post('master/get-amendment', 'AmendmentController@getAmendmentsList');                              // get amendment data
Route::get('master/generate-amendment-number', 'AmendmentController@getAutoGeneratedCode');                             //generate auto code
Route::post('master/add-amendment', 'AmendmentController@createAmendment');                                 // add amendment data
Route::post('master/edit-amendment', 'AmendmentController@editAmendment');                                  // edit amendment data list
Route::post('master/update-amendment', 'AmendmentController@updateAmendment');                              // update amendment data list
Route::post('master/delete-amendment', 'AmendmentController@deleteAmendment');                                  // delete amendment data list

/**********************************************************
Hold Master Management Section (27-12-2018)
 ***********************************************************/
Route::get('master/hold-list', 'HoldController@index');                                              // list hold data
Route::post('master/get-hold-list', 'HoldController@getHoldList');                                      // get hold data
Route::get('master/generate-hold-number', 'HoldController@getAutoGeneratedCode');                                     //generate auto code
Route::post('master/add-hold', 'HoldController@create');                                                 // add hold data
Route::post('master/edit-hold', 'HoldController@edit');                                                          // edit hold data list
Route::post('master/update-hold', 'HoldController@update');                                                      // update hold data list
Route::post('master/delete-hold', 'HoldController@delete');                                                  // delete hold data list

/**********************************************************
Header notes Master Management Section (27-12-2018)
 ***********************************************************/
Route::get('master/header-notes-list', 'HeaderNoteController@index');                                         // list hold data
Route::post('master/get-header-notes-list', 'HeaderNoteController@getHeaderNoteList');                        // get hold data
Route::post('master/add-header-note', 'HeaderNoteController@create');                                         // add hold data
Route::post('master/edit-header-note', 'HeaderNoteController@edit');                                          // edit hold data list
Route::post('master/update-header-note', 'HeaderNoteController@update');                                      // update hold data list
Route::post('master/delete-header-note', 'HeaderNoteController@delete');                                      // delete hold data list

/**********************************************************
Countries Master Management Section (25-01-2019)
 ***********************************************************/
Route::get('master/countries', 'CountryController@index');                                                     // add Countries data
Route::post('master/get-countries', 'CountryController@getCountriesList');                                     // get Countries data
Route::post('master/country/add-country', 'CountryController@create');                                         // add Countries data
Route::post('master/country/edit-country', 'CountryController@edit');                                          // edit Countries data list
Route::post('master/country/update-country', 'CountryController@update');                                      // update Countries data list
Route::post('master/country/delete-country', 'CountryController@delete');                                      // delete Countries data list

/**********************************************************
Stability Type Master Management Section (25-02-2019)
 ***********************************************************/
Route::get('master/stability-type-master', 'StabilityTypeController@index');                                        // list hold data
Route::post('master/get-stability-type-list', 'StabilityTypeController@getList');                                   // get hold data
Route::post('master/add-stability-type', 'StabilityTypeController@create');                                         // add hold data
Route::post('master/edit-stability-type-master', 'StabilityTypeController@edit');                                   // edit hold data list
Route::post('master/update-stability-type-master', 'StabilityTypeController@update');                               // update hold data list
Route::post('master/delete-stability-type-master', 'StabilityTypeController@delete');                               // delete hold data list

/**********************************************************
Customer GST Categories (03-04-2019)
 ***********************************************************/
Route::get('master/customers/customer-gst-categories', 'CustomerGstCategoryController@index');                          //Listing of Customer GST Categories
Route::post('master/customers/customer-gst-categories/list', 'CustomerGstCategoryController@listCgCategory');           //Creation of Customer GST Category
Route::post('master/customers/customer-gst-categories/add', 'CustomerGstCategoryController@createCgCategory');          //Creation of Customer GST Category
Route::post('master/customers/customer-gst-categories/edit', 'CustomerGstCategoryController@editCgCategory');           //Editing of Customer GST Category
Route::post('master/customers/customer-gst-categories/update', 'CustomerGstCategoryController@updateCgCategory');       //Updation of Customer GST Category
Route::post('master/customers/customer-gst-categories/delete', 'CustomerGstCategoryController@deleteCgCategory');       //Updation of Customer GST Category

//TRF Modules   (21-May-2019)
Route::get('sales/trfs', 'TrfHdrController@index');                                                                    //Listing of TRFs
Route::post('sales/trfs/get-trf-list', 'TrfHdrController@getBranchWiseTrfs');                                          //Listing of TRFs
Route::post('sales/trfs/generate-branch-wise-trf-pdf', 'TrfHdrController@getBranchWiseTrfsPdf');                       //Generating of TRF PDF
Route::post('sales/trfs/view-trf-detail-list', 'TrfHdrController@viewTrf');                                            //Viewing of TRF
Route::post('sales/trfs/trf-generate-pdf', 'DownloadController@generateTrfSheetPdf');                                  //Viewing of TRF

//Cental Location Master : Cental POs Master
Route::get('master/central-pos', 'CentralPoDtlController@index');                                                       //Listing of Cental POs
Route::post('master/pos/central-po-listing', 'CentralPoDtlController@listing');                                         //Listing of Cental POs
Route::post('master/pos/add-central-po', 'CentralPoDtlController@createCentralPo');                                     //Adding of Cental POs
Route::post('master/pos/customers-list', 'DropdownController@customersList');                                           //Getting customer list
Route::get('master/pos/get-customer-citiy/{id}', 'DropdownController@getCustomerLocationLicNo');                        //Getting Customer City
Route::post('master/pos/delete-po-dtl', 'CentralPoDtlController@destroyPoDtl');                                         //Deleting Cental POs

//Cental Location Master : Cental STPs Master
Route::get('master/central-stps', 'CentralStpDtlController@index');                                                      //Listing of Cental STPs
Route::post('master/stps/central-stp-listing', 'CentralStpDtlController@listing');                                       //Listing of Cental STPs
Route::post('master/stps/add-central-stp', 'CentralStpDtlController@createCentralStp');                                  //Adding of Cental STPs
Route::post('master/stps/customers-list', 'DropdownController@customersList');                                           //Getting customer list
Route::get('master/stps/get-customer-citiy/{id}', 'DropdownController@getCustomerLocationLicNo');                        //Getting Customer City
Route::post('master/stps/delete-stp-dtl', 'CentralStpDtlController@destroyStpDtl');                                      //Deleting Cental STPs

//User Sales Master Master
Route::get('master/employee/sales-target', 'UserSalesTargetDetailController@index');                                      //Listing of Landing Page
Route::post('master/employee/add-sales-target', 'UserSalesTargetDetailController@createUserSalesTarget');                 //Adding of User Sales Target
Route::get('master/employee/get-employee-list/{id}', 'DropdownController@getEmployeeListBasedOnBranch');                  //Getting Employee Name based on Branch
Route::post('master/employee/sales-target-listing', 'UserSalesTargetDetailController@userSalesTargetlisting');            //Listing of User Sales Target
Route::get('master/employee/sales-target-view/{id}', 'UserSalesTargetDetailController@viewUserSalesTarget');              //Viewing of User Sales Target
Route::post('master/employee/sales-target-update', 'UserSalesTargetDetailController@updateUserSalesTarget');              //Updating of User Sales Target
Route::post('master/employee/delete-sales-target-dtl', 'UserSalesTargetDetailController@destroyUserSalesTarget');         //Deleting User Sales Target
Route::get('master/employee/get-customer-list/{id}', 'DropdownController@getCustomerListBasedOnSalesExecutive');          //Getting Employee Name based on Branch
Route::get('master/employee/sales-target/get-sales-types', 'DropdownController@getUserSalesTargetTypes');                 //Getting User Sales Target Types
Route::post('master/employee/sales-target/upload-csv-file', 'UserSalesTargetDetailController@uploadSalesTargetCSV');      //Uploading of Sales Target CSV Files

//Report Discipline Master : 09-Nov-2019
Route::get('master/disciplines', 'OrderReportDisciplineController@index');                                      //Listing of Master
Route::post('master/disciplines/list', 'OrderReportDisciplineController@listMasters');                          //Listing of Master
Route::get('master/disciplines/generate-auto-code', 'OrderReportDisciplineController@getAutoGeneratedCode');    //Generating Auto Code
Route::post('master/disciplines/add', 'OrderReportDisciplineController@createMaster');                          //Adding of Master
Route::get('master/disciplines/view/{id}', 'OrderReportDisciplineController@viewMaster');                       //Viewing of Master
Route::post('master/disciplines/update', 'OrderReportDisciplineController@updateMaster');                       //Update of Master
Route::get('master/disciplines/delete/{id}', 'OrderReportDisciplineController@destroyMaster');                  //Deleting of Master

//Report Group Master : 09-Nov-2019
Route::get('master/groups', 'OrderReportGroupController@index');                                             //Listing of Master
Route::post('master/groups/list', 'OrderReportGroupController@listMasters');                                 //Listing of Master
Route::get('master/groups/generate-auto-code', 'OrderReportGroupController@getAutoGeneratedCode');           //Generating Auto Code
Route::post('master/groups/add', 'OrderReportGroupController@createMaster');                                 //Adding of Master
Route::get('master/groups/view/{id}', 'OrderReportGroupController@viewMaster');                              //Viewing of Master
Route::post('master/groups/update', 'OrderReportGroupController@updateMaster');                              //Update of Master
Route::get('master/groups/delete/{id}', 'OrderReportGroupController@destroyMaster');                         //Deleting of Master

//Report Discipline Parameter Category Settings : 09-Nov-2019
Route::get('master/discipline-parameter-category', 'OrderReportDisciplineParameterDtlController@index');                                                     //Listing of Master
Route::post('master/discipline-parameter-category/list', 'OrderReportDisciplineParameterDtlController@listMasters');                                         //Listing of Master
Route::get('master/discipline-parameter-category/generate-auto-code', 'OrderReportDisciplineParameterDtlController@getAutoGeneratedCode');                   //Generating Auto Code
Route::post('master/discipline-parameter-category/add', 'OrderReportDisciplineParameterDtlController@createMaster');                                         //Adding of Master
Route::get('master/discipline-parameter-category/view/{id}', 'OrderReportDisciplineParameterDtlController@viewMaster');                                      //Viewing of Master
Route::post('master/discipline-parameter-category/update', 'OrderReportDisciplineParameterDtlController@updateMaster');                                      //Update of Master
Route::get('master/discipline-parameter-category/delete/{id}', 'OrderReportDisciplineParameterDtlController@destroyMaster');                                 //Deleting of Master
Route::post('master/discipline-parameter-category/get-discipline-list', 'DropdownController@getDisciplineDropdownList');                                     //Update of Master
Route::get('master/discipline-parameter-category/get-parameter-category-list/{test_cat_id}', 'DropdownController@getCustomerWiseParametersCategoryList');    //Update of Master

//Order defined test standard test detail Master : 06-April-2020
Route::get('master/defined-test-standard', 'OrderDefinedTestStandardController@index');                                          //Listing of Master
Route::post('master/defined-test-standard/list', 'OrderDefinedTestStandardController@listMasters');                              //Listing of Master
Route::post('master/defined-test-standard/add', 'OrderDefinedTestStandardController@createMaster');                              //Adding of Master
Route::get('master/defined-test-standard/view/{id}', 'OrderDefinedTestStandardController@viewMaster');                           //Viewing of Master
Route::post('master/defined-test-standard/update', 'OrderDefinedTestStandardController@updateMaster');                           //Update of Master
Route::get('master/defined-test-standard/delete/{id}', 'OrderDefinedTestStandardController@destroyMaster');
Route::get('master/get-test-standard-list/{id}', 'DropdownController@getTestStandardList');                                      //Listing of Master

//Dynamic Fields Master
Route::get('master/dynamic-fields', 'DynamicFieldController@index');
Route::post('master/get-dynamic-fields', 'DynamicFieldController@listing');
Route::post('master/add-dynamic-field', 'DynamicFieldController@create');
Route::post('master/edit-dynamic-field', 'DynamicFieldController@editDynamicFieldData');
Route::post('master/update-dynamic-field', 'DynamicFieldController@updateDynamicFieldData');
Route::post('master/delete-dynamic-field', 'DynamicFieldController@deleteDynamicFieldData');
Route::post('get-dynamic-fields-list', 'DropdownController@getDynamicFields');

//Hold-Unhold customers master (08-04-2021)
Route::get('master/hold-unhold-customers', 'OrderHoldUnholdController@index');
Route::post('master/hold-unhold-customers/get-hold-customers', 'OrderHoldUnholdController@getHoldCustomerList');                //Get hold customer data list
Route::post('master/hold-unhold-customers/get-hold-customer-dtl', 'OrderHoldUnholdController@getHoldCustomerDtl');              //Getting Hold customer detail
Route::post('master/hold-unhold-customers/unhold-customer', 'OrderHoldUnholdController@unholdCustomer');                        //Unholding of customer
Route::post('master/hold-unhold-customers/send-mail', 'OrderHoldUnholdController@sendMailToCustomers');                         //Send mail to hold customer

//Report CRM Master : 20-May-2021
Route::get('master/com-crms', 'CustomerComCrmEmailAddressController@index');                                                    //Listing of Master
Route::post('master/com-crms/list', 'CustomerComCrmEmailAddressController@list');                                               //Listing of Master
Route::post('master/com-crms/add', 'CustomerComCrmEmailAddressController@create');                                              //Adding of Master
Route::get('master/com-crms/edit/{id}', 'CustomerComCrmEmailAddressController@edit');                                           //Viewing of Master
Route::post('master/com-crms/update', 'CustomerComCrmEmailAddressController@update');                                           //Update of Master
Route::get('master/com-crms/delete/{id}', 'CustomerComCrmEmailAddressController@delete');                                       //Deleting of Master

//Customer Exist Account Hold Upload Dtl
Route::get('master/customer/cust-exist-acct-hold-upload', 'CustomerExistAccountHoldUploadDtlController@index');                  //Listing of Master
Route::post('master/customer/cust-exist-acct-hold-list', 'CustomerExistAccountHoldUploadDtlController@list');                   //Listing of Master
Route::post('master/customer/upload-cust-exist-account', 'CustomerExistAccountHoldUploadDtlController@upload');                 //Uploading of Master
Route::post('master/customer/download-excel-cust-exist-account', 'DownloadController@generateCustomerExistAccountDocuments');   //Download excel

//Order report header type route section
Route::get('master/templates/order-report-header-types', 'OrderReportHeaderTypeController@index');
Route::post('master/templates/order-report-header-types', 'OrderReportHeaderTypeController@getOrderReportHdrList');
Route::post('master/templates/add-order-report-header-types', 'OrderReportHeaderTypeController@createOrderReportHdrType');      // add template header footer data
Route::post('master/templates/delete-order-report-header-types', 'OrderReportHeaderTypeController@delete');                     // delete template header footer data                                  
Route::post('master/templates/edit-order-report-header-types', 'OrderReportHeaderTypeController@edit');                         // edit template header footer data                                  
Route::post('master/templates/update-order-report-header-types', 'OrderReportHeaderTypeController@update');                     // edit template header footer data                                  

//Order Report Signature Master : 18-Oct-2020
Route::get('master/order-report-signatures', 'OrderReportSignDtlController@index');                                              //Listing of Master
Route::post('master/order-report-signatures/list', 'OrderReportSignDtlController@listMasters');                                  //Listing of Master
Route::post('master/order-report-signatures/add', 'OrderReportSignDtlController@createMaster');                                  //Adding of Master
Route::get('master/order-report-signatures/view/{id}', 'OrderReportSignDtlController@viewMaster');                               //Viewing of Master
Route::post('master/order-report-signatures/update', 'OrderReportSignDtlController@updateMaster');                               //Update of Master
Route::get('master/order-report-signatures/delete/{id}', 'OrderReportSignDtlController@destroyMaster');                          //Deleting of Master
Route::get('master/order-report-signatures/get-equipment-list/{id}', 'DropdownController@getTestStandardList');                  //Listing of Master

//Order Column Master : 29-Nov-2021
Route::get('master/column-master', 'ColumnController@index');                                             //Listing of Master
Route::post('master/column-master/list', 'ColumnController@listMasters');                                 //Listing of Master
Route::get('master/column-master/view/{id}', 'ColumnController@editMaster');                              //Editing of Master
Route::post('master/column-master/add', 'ColumnController@createMaster');                                 //Adding of Master
Route::post('master/column-master/update', 'ColumnController@updateMaster');                              //Update of Master
Route::get('master/column-master/delete/{id}', 'ColumnController@destroyMaster');                         //Deleting of Master
Route::get('master/column-master/generate-column-code', 'ColumnController@getAutoGeneratedCode');         //generate Column Code

//Order Instance Master : 29-Nov-2021
Route::get('master/instance-master', 'InstanceController@index');                                         //Listing of Master
Route::post('master/instance-master/list', 'InstanceController@listMasters');                             //Listing of Master
Route::get('master/instance-master/view/{id}', 'InstanceController@editMaster');                          //Editing of Master
Route::post('master/instance-master/add', 'InstanceController@createMaster');                             //Adding of Master
Route::post('master/instance-master/update', 'InstanceController@updateMaster');                          //Update of Master
Route::get('master/instance-master/delete/{id}', 'InstanceController@destroyMaster');                     //Deleting of Master
Route::get('master/instance-master/generate-instance-code', 'InstanceController@getAutoGeneratedCode');   //generate Column Code

//Order Report Signature Master : 18-Oct-2020
Route::get('master/order-analyst-window-settings', 'OrderAnalystWindowSettingController@index');                                              //Listing of Master
Route::post('master/order-analyst-window-settings/list', 'OrderAnalystWindowSettingController@listMasters');                                  //Listing of Master
Route::post('master/order-analyst-window-settings/add', 'OrderAnalystWindowSettingController@createMaster');                                  //Adding of Master
Route::get('master/order-analyst-window-settings/view/{id}', 'OrderAnalystWindowSettingController@viewMaster');                               //Viewing of Master
Route::post('master/order-analyst-window-settings/update', 'OrderAnalystWindowSettingController@updateMaster');                               //Update of Master
Route::get('master/order-analyst-window-settings/delete/{id}', 'OrderAnalystWindowSettingController@destroyMaster');                          //Deleting of Master
Route::get('master/order-analyst-window-settings/get-equipment-list/{id}', 'DropdownController@getTestStandardList');                         //Listing of Master

//Common Routes
Route::get('get_customer_list_by_state/{state_id}', 'DropdownController@getStateWiseCustomerWithCodeWithCity');                              //Update customer data