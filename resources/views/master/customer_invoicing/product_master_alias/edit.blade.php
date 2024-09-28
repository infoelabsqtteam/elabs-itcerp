<style>
    .c_product_name {
        width: calc(100% - 30px);
        float: left;
    }

    .c_product_name span {
        float: left;
    }

    .deleteIcon {
        float: left;
        margin-top: 10px;
    }

</style>
<div class="row" ng-hide="editCustomerProductFormBladeDiv">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row header-form">
                <span class="pull-left headerText"><strong>Edit Product Master Alias : </strong></span>
                <span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="backButton()">Back</button></span>
            </div>
            <form method="POST" role="form" id="erpEditCustomerProductForm" name="erpEditCustomerProductForm" novalidate>

                <div class="row">
                    <div class="col-xs-12">
                        <!--Selecet Product view-record-->
                        <div class="col-xs-6 form-group ">
                            <label for="product_id">Select Product<em class="asteriskRed">*</em>
                                <a title="Refresh Product List" ng-click="funGetProductList(0)" class='generate cursor-pointer'> Refresh </a></label>
                            <a title="Select Category" data-toggle="modal" title="Filter Product" ng-click="showProductCatTreeViewPopUp(3)" class='generate cursor-pointer'> Tree View </a>
                            <select class="form-control" name="product_id" id="product_id" ng-required="true" ng-model="editProductMasterAlias.product_id.selectedOption" ng-options="item.name for item in productsListData track by item.id">
                                <option value="">Select Product</option>
                            </select>
                            <span ng-messages="erpAddCustomerProductForm.product_id.$error" ng-if="erpAddCustomerProductForm.product_id.$dirty || erpAddCustomerProductForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Product is required</span>
                            </span>
                        </div>
                        <!--/Selecet Product -->

                        <!--Customer Product Name-->
                        <div class="col-xs-6 form-group" ng-if="!editAllCustomerProductAliases.length">
                            <label for="c_product_name">Product Alias Name<em class="asteriskRed">*</em></label>
                            <input class="form-control" id="c_product_name" ng-model="editCustomerProduct.c_product_name" name="c_product_name[]" ng-required="true" placeholder="Customer Product Name">
                            <span ng-messages="erpAddCustomerProductForm.c_product_name.$error" ng-if="erpAddCustomerProductForm.c_product_name.$dirty || erpAddCustomerProductForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Customer product name is required</span>
                            </span>
                            <input type="hidden" ng-model="editCustomerProduct.c_product_id" ng-value="editCustomerProduct.c_product_id" name="c_product_id[]" placeholder="Customer Product Id">
                        </div>
                        <div class="row" ng-if="editAllCustomerProductAliases.length">
                            <label for="c_product_name">Product Alias Name<em class="asteriskRed">*</em></label>
                            <span ng-if="showAddMoreOption"><a title="Add New Row" id="#addNewRow" href="javascript:;" ng-click="addRow(1)"><i class="font15 mL5 glyphicon glyphicon-plus"></i></a></span>
                            <input style="display:none;" type="number" class="width70 " title="Add multiple rows" id="mutipleRows" max="5">
                        </div>
                        <div class="row" ng-if="editAllCustomerProductAliases.length">
                            <div class="col-xs-12" ng-repeat="editAllProductAliases in editAllCustomerProductAliases">
                                <div class="col-xs-6 form-group"></div>
                                <div class="col-xs-6 form-group" style="margin-top: -35px; margin-bottom: 45px; top:-17px;">
                                    <input class="form-control c_product_name" id="c_product_name" name="c_product_name[]" ng-required="true" placeholder="Customer Product Name" ng-model="editProductMasterAlias.c_product_name_[[editAllProductAliases.c_product_id]]" ng-value="editAllProductAliases.c_product_name">
                                    <span ng-if="editAllProductAliases.c_product_id" ng-hide="!$index">
                                        <input type="hidden" name="c_product_id[]" id="c_product_id_[[$index]]" ng-value="[[editAllProductAliases.c_product_id]]">
                                        <a class="deleteIcon" href="javascript:;" title="Delete Row" ng-click="deleteRow($index,[[editAllProductAliases.c_product_id]],[[editAllProductAliases.product_id]],'deleteEditRow');">
                                            <i class="font15 removeIcon glyphicon glyphicon-remove"></i>
                                        </a>
                                    </span> &nbsp;
                                </div>
                            </div>
                        </div>
						<!--/Customer Product Name-->

                    </div>
                </div>
                <!------ clone row ---------->
                <div class="row" ng-if="ifAddMoreClickedOnAddEdit" select-last ng-repeat="product_alias in customer_product_alias">
                    <div class="col-xs-12" style="margin-top: -51px; margin-bottom: 38px;">
                        <div class="col-xs-6 form-group"></div>
                        <div class="col-xs-6 form-group" style="margin-top: 0px; margin-bottom: 25px;">
                            <input class="form-control c_product_name" id="c_product_name_[[$index]]" name="c_product_name[]" ng-required="true" placeholder="Customer Product Name">
                            <input type="hidden" name="c_product_id[]" id="c_product_id_[[$index]]" ng-value="">

                            <span ng-if="$index >= 0">
                                <a class="deleteIcon" href="javascript:;" title="Delete Row" ng-click="deleteRow($index,0,0,'deleteNewRow');">
                                    <i class="font15 removeIcon glyphicon glyphicon-remove"></i>
                                </a>
                            </span>&nbsp;
                        </div>
                    </div>
                </div>

                <!--Save Button-->
                <div class="col-xs-12 form-group text-right mT10">
                    <label for="submit">{{ csrf_field() }}</label>
                    <button type="submit" class="btn btn-primary" ng-click="funUpdateCustomerProductMaster()">Update</button>
                    <button type="button" class="btn btn-default" ng-click="backButton()">Cancel</button>
                </div>
                <!--Save Button-- ng-disabled="erpEditCustomerProductForm.$invalid">-->

            </form>
        </div>
    </div>
</div>
