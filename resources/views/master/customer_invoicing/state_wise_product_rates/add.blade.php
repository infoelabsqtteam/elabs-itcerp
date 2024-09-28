<div class="row" ng-hide="addStateWiseProductRateDiv">
   <div class="panel panel-default">
      <div class="panel-body">
         <div class="row header-form">
            <span class="pull-left headerText"><strong ng-model="formTitle" ng-bind="formTitle"></strong></span>
            <span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="navigateFormPage(cirStateID,'0','add')">Back</button></span>
         </div>
         <form method="POST" role="form" id="erpAddStateWiseProductRateForm" name="erpAddStateWiseProductRateForm" novalidate>
            <div class="row" ng-if="addForm">
               <div class="col-xs-3">
                 <label for="country_id">Select Country<em class="asteriskRed">*</em></label>
                 <select
                     class="form-control"
                     name="cir_country_id"
                     ng-model="selectedCountry"
                     ng-change="funGetStateOnCountryChange(selectedCountry.id)"
                     id="country_id"
                     ng-required='true'
                     ng-options="item.name for item in countryCodeList track by item.id ">
                     <option value="">Select Country</option>
                 </select>
                 <span ng-messages="customerForm.country_id.$error" ng-if='customerForm.country_id.$dirty  || customerForm.$submitted' role="alert">
                     <span ng-message="required" class="error">State country is required</span>
                 </span>
                  </div>
               <!-- States-->
              <!-- <div class="col-xs-1 form-group"><label for="cir_state_id">Select State:<em class="asteriskRed">*</em></label></div>-->
               <div class="col-xs-3 form-group">
                  <label for="cir_state_id">Select State:<em class="asteriskRed">*</em></label>
                  <select class="form-control" ng-disabled ="disableStateOnEdit"
                     name="cir_state_id"
                     ng-model="addStateWiseProductRate.cir_state_id.selectedOption"
                     id="cir_state_id"
                     ng-change = "enableDepartmentField(addStateWiseProductRate.cir_state_id.selectedOption)"
                     ng-required='true'
                     ng-options="item.name for item in statesCodeList track by item.id">
                     <option value="">Select State</option>
                  </select>
                  <span ng-messages="erpAddStateWiseProductRateForm.cir_state_id.$error" ng-if='erpAddStateWiseProductRateForm.cir_state_id.$dirty  || erpAddStateWiseProductRateForm.$submitted' role="alert">
                  <span ng-message="required" class="error">State is required</span>
                  </span>
               </div>
               <!-- /States--->
               <!-- Branch -->
              <!-- <div class="col-xs-1 form-group"><label for="cir_state_id">Branch:<em class="asteriskRed">*</em></label></div>-->
               <div class="col-xs-3 form-group">
               <label for="cir_state_id">Branch:<em class="asteriskRed">*</em></label>
                  <select class="form-control" ng-disabled="disableBranchOnEdit"
                     name="cir_division_id"
                     ng-model="addStateWiseProductRate.cir_division_id.selectedOption"
                     id="cir_division_id"
                     ng-required='true'
                     ng-options="item.name for item in divisionsCodeList track by item.id">
                     <option value="">Select Branch</option>
                  </select>
                  <span ng-messages="erpAddStateWiseProductRateForm.cir_division_id.$error" ng-if='erpAddStateWiseProductRateForm.cir_division_id.$dirty  || erpAddStateWiseProductRateForm.$submitted' role="alert">
                  <span ng-message="required" class="error">Branch is required</span>
                  </span>
               </div>
               <!-- /Branch--->
               <!-- Product department-->
              <!-- <div class="col-xs-1 form-group"><label for="cir_state_id">Department:<em class="asteriskRed">*</em></label></div>-->
               <div class="col-xs-3 form-group">
               <label for="cir_state_id">Department:<em class="asteriskRed">*</em></label>
                  <select class="form-control" ng-disabled="disableDeptOnEdit"
                     name="cir_product_category_id"
                     ng-model="addStateWiseProductRate.cir_product_category_id.selectedOption"
                     id="cir_product_category_id"
                     ng-required='true'
                     ng-change = "getProductAliasRateList(addStateWiseProductRate.cir_product_category_id.selectedOption.id)"
                     ng-options="item.name for item in parentCategoryList track by item.id"
                    >
                     <option value="">Select Department</option>
                  </select>
                  <span ng-messages="erpAddStateWiseProductRateForm.cir_product_category_id.$error" ng-if='erpAddStateWiseProductRateForm.cir_product_category_id.$dirty  || erpAddStateWiseProductRateForm.$submitted' role="alert">
                  <span ng-message="required" class="error">Department is required</span>
                  </span>
               </div>
               <!-- /States--->
            </div>
            <hr>
            <!-- /Products--->
            <div class="row">
               <div class="col-xs-3 form-group"><label>Product Name:<em class="asteriskRed">*</em></label></div>
               <div class="col-xs-4 form-group"><label>Product Alias Name</label></div>
               <div class="col-xs-2 form-group"><label>Enter Rate</label></div>
               <div class="col-xs-3 form-group"><input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search Product" ng-model="filterProductRateList">		</div>
            </div>
            <div class="row col-xs-12" dir-paginate="productObj in productAliasRateList | filter : filterProductRateList | itemsPerPage : {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:'-productObj.rate'">
               <div class="col-xs-12">

                  <div class="col-xs-3 form-group">[[productObj.product_name]]</div>
                  <div class="col-xs-4 form-group">
                     [[productObj.name]]
                     <input type="hidden" name="cir_id[]" ng-model="productObj.cir_id" ng-value="productObj.cir_id">
   
                     <input type="hidden" name="cir_c_product_id[]" ng-model="productObj.id" ng-value="productObj.id">
                  </div>
                  <div class="col-xs-5 form-group">
                     <input type="number" min="0" ng-if="!productObj.rate"
                        class="form-control invoicing_rate"
                        ng-model="addSWiseProductRate.invoicing_rate_[[productObj.id]]"
                        name="invoicing_rate[]"
                        value=""
                        class="form-control"
                        placeholder="Rate">
                     <input type="number" min="0" ng-if="productObj.rate"
                        class="form-control invoicing_rate"
                        ng-model="addSProductRate.invoicing_rate_[[productObj.id]]"
                        name="invoicing_rate[]"
                        value="[[productObj.rate]]"
                        class="form-control"
                        placeholder="Rate">
                  </div>
               </div>                  
            </div>
            <div ng-if="productAliasRateList.length">
               <div class="box-footer clearfix">
                  <dir-pagination-controls></dir-pagination-controls>
               </div>
            </div>
            <div ng-if="!productAliasRateList.length">No record found.</div>
            <!-- /Products--->
            <hr>
            <div class="row">
               <!--Save Button-->
               <div class="col-xs-12 form-group text-right mT10">
                  <label for="submit">{{ csrf_field() }}</label>
                  <span ng-if="hideSaveBtn">

                  <button type="submit" ng-disabled="erpAddStateWiseProductRateForm.$invalid" class="btn btn-primary" ng-click="funAddStateWiseProductRate(cirStateID,addStateWiseProductRate.cir_product_category_id.selectedOption,'add')">Save</button>
                  </span>
                  <span ng-if="hideUpdateBtn">
                  <button type="submit" ng-disabled="erpAddStateWiseProductRateForm.$invalid" class="btn btn-primary" ng-click="funAddStateWiseProductRate(cirStateID,addStateWiseProductRate.cir_product_category_id.selectedOption,'modify')">Update</button>
                  </span>
                  <a ng-if="hideResetBtn" type="button" class="btn btn-default" ng-click="resetButton()">Reset</a>
                  <a ng-if="hideCancelBtn" type="button" class="btn btn-default" ng-click="navigateFormPage(cirStateID,'add')">Cancel</a>
               </div>
               <!--Save Button-->
            </div>
         </form>
      </div>
   </div>
</div>
