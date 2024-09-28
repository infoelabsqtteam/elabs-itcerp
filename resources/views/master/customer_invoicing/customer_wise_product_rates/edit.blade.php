<div class="row" ng-if="editAllCustomerWiseProductRateDiv">

    <div class="row" id="no-more-tables">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row header-form">
                    <span class="pull-left headerText"><strong>Edit Customer Wise Product</strong></span>
                    <span class="pull-right pull-custom">
                        <button type="button" class="form-control btn btn-primary" ng-click="navigateFormPage('list');" style="margin-top:-2px !important">Back</button>
                    </span>
                    <span class="pull-right pull-custom">
                        <input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search Product" ng-model="filterInvoicingProductRateList" style="margin-top:-2px !important"></span>
                </div>
                <form method="POST" role="form" id="erpEditCustomerWiseAllProductRateForm" name="erpEditCustomerWiseAllProductRateForm" novalidate>

                    <hr ng-if="for_fixed_rate==false">
                    <div class="row" ng-if="for_fixed_rate==false">
                        <table class="col-sm-12 table-striped table-condensed cf font15">
                            <thead class="cf">
                                <tr>
                                    <th></th>
                                    <th>
                                        <label ng-click="sortBy('p_category_name')" class="sortlabel">Product Category Name</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'p_category_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('product_name')" class="sortlabel">Product Name</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'product_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('c_product_name')" class="sortlabel">Product Alias Name</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'test_parc_product_namea_cat_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label class="sortlabel">Enter Rate</label>
                                        <span class="sortorder reverse"></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr dir-paginate="productObj in productAliasRateList | filter : filterInvoicingProductRateList | itemsPerPage : {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:'-productObj.rate'">
                                    <td></td>
                                    <td>[[productObj.p_category_name]]</td>
                                    <td>[[productObj.product_name]]
                                        <input type="hidden" name="cir_c_product_id_1[]" ng-model="productObj.cir_id" ng-value="[[productObj.cir_id]]">
                                    </td>
                                    <td>[[productObj.name]]
                                        <input type="hidden" name="cir_c_product_id[]" ng-model="productObj.id" ng-value="productObj.id">
                                    </td>
                                    <td><input type="number" min="0" ng-if="productObj.rate" class="form-control invoicing_rate width35" ng-model="editCustomerWiseProductRate.invoicing_rate_[[productObj.id]]" name="invoicing_rate[]" value="[[productObj.rate]]" class="form-control" placeholder="Rate"></td>
                                </tr>
                                <tr ng-if="productAliasRateList.length">
                                    <dir-pagination-controls></dir-pagination-controls>
                    </div>
                    </tr>
                    <tr>
                        <div ng-if="!productAliasRateList.length">No record found.</div>
                    </tr>
                    </tbody>
                    </table>
            </div>
            <hr>
            <!--Save Button-->
            <div class="row">
                <div class="col-xs-12 form-group text-right mT10">
                    <label for="submit">{{ csrf_field() }}</label>
                    <input type="hidden" ng-value="editCustomerWiseProductRate.cir_id" name="cir_id" ng-model="editCustomerWiseProductRate.cir_id">
                    <button type="submit" class="btn btn-primary" ng-click="funUpdateCustomerWiseAllProductRate()">Update</button>
                    <button type="button" class="btn btn-default" ng-click="navigateFormPage('list')">Cancel</button>
                </div>
            </div>
            <!--Save Button-->
            </form>
        </div>
    </div>
</div>
</div>
