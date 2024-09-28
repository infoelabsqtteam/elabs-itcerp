<div class="row" ng-hide="listCreditNoteFormBladeDiv">
    <form class="form-inline" method="POST" role="form" name="erpCrebditNoteForm" action="{{url('payments/credit-notes/download-excel')}}" target="blank" novalidate>
        <label for="submit">{{ csrf_field() }}</label>
        
		<!--search-->
        <div class="row header">
            <div role="new" class="navbar-form navbar-left">
                <div><strong id="form_title"><span ng-click="funGetBranchWiseCreditNotes({{$division_id}})">Credit Note Listing<span ng-if="creditNotesList.length"></span>([[creditNotesList.length]])</strong></div>
            </div>
            <div role="new" class="navbar-form navbar-right">
                <div class="nav-custom custom-display">
                    <input type="text" placeholder="Search" ng-change="funSearchCreditNote(filterCreditNote.keyword)" ng-keypress="funEnterPressHandler($event)" name="" ng-model="filterCreditNote.keyword" class="form-control ng-pristine ng-untouched ng-valid">
                    <select ng-if="{{$division_id}} == 0" class="form-control" ng-model="filterCreditNote.divisions" name="division_id" ng-options="division.name for division in divisionsCodeList track by division.id" ng-change="funGetBranchWiseCreditNotes(filterCreditNote.divisions.id)">
                        <option value="">All Branch</option>
                    </select>
                    <button type="button" ng-disabled="!creditNotesList.length" class="form-control btn btn-default dropdown dropdown-toggle" data-toggle="dropdown" title="Download">Download</button>
                    <div class="dropdown-menu">
                        <input type="submit" formtarget="_blank" name="generate_credit_notes_documents" value="Excel" class="dropdown-item">
                    </div>
                    <span ng-if="{{defined('ADD') && ADD}}">
                        <button type="button" ng-click="navigateCreditNotePage()" class="btn btn-primary">Add New</button>
                    </span>
                </div>
            </div>
        </div>
        <!--/search-->

        <!--display record-->
        <div class="row" id="no-more-tables">
            <table class="col-sm-12 table-striped table-condensed cf">
                <thead class="cf">
                    <tr>
                        <th>
                            <label ng-click="sortBy('credit_note_no')" class="sortlabel">Credit Note No.</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'credit_note_no'" class="sortorder reverse"></span>
                        </th>
                        <th ng-if="{{$division_id}} == 0">
                            <label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
                            <span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th>
                            <label class="sortlabel" ng-click="sortBy('department_name')">Department</label>
                            <span class="sortorder" ng-show="predicate === 'department_name'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('customer_name')" class="sortlabel">Customer Name</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'customer_name'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('invoice_no')" class="sortlabel">Invoice Number</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'invoice_no'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('credit_reference_no')" class="sortlabel">Reference Number</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'credit_reference_no'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('credit_note_date')" class="sortlabel">Credit Note Date </label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'credit_note_date'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th class="width8">
                            <label ng-click="sortBy('credit_note_amount')" class="sortlabel">Amount(Rs.)</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'credit_note_amount'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th class="width8">
                            <label ng-click="sortBy('credit_note_remark')" class="sortlabel">Remark</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'credit_note_remark'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th class="width8">
                            <label ng-click="sortBy('createdByName')" class="sortlabel">Created By</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'createdByName'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th class="width10">Action
                            <button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-hide="multiSearchTr">
                        <td><input type="text" ng-change="getMultiSearch(searchCredit.search_credit_note_no)" ng-keypress="funEnterPressHandler($event)" name="search_credit_note_no" ng-model="searchCredit.search_credit_note_no" class="multiSearch form-control width80" placeholder="Credit Note No."></td>
                        <td ng-if="{{$division_id}} == 0" class="width10"></td>
                        <td class="width10"><input type="text" ng-change="getMultiSearch(searchCredit.search_department_name)" ng-keypress="funEnterPressHandler($event)" name="search_department_name" ng-model="searchCredit.search_department_name" class="multiSearch form-control width80" placeholder="Department Name"></td>
                        <td><input type="text" ng-change="getMultiSearch(searchCredit.search_customer_name)" ng-keypress="funEnterPressHandler($event)" name="search_customer_name" ng-model="searchCredit.search_customer_name" class="multiSearch form-control width80" placeholder="Customer Name"></td>
                        <td></td>
                        <td></td>
                        <td><input type="text" ng-change="getMultiSearch(searchCredit.search_credit_note_date)" ng-keypress="funEnterPressHandler($event)" name="search_credit_note_date" ng-model="searchCredit.search_credit_note_date" class="multiSearch form-control width80" placeholder="Credit Note Date"></td>
                        <td class="width10"><input type="text" ng-change="getMultiSearch(searchCredit.search_credit_note_amount)" ng-keypress="funEnterPressHandler($event)" name="search_credit_note_amount" ng-model="searchCredit.search_credit_note_amount" class="multiSearch form-control width80" placeholder="Amount(Rs.)"></td>
                        <td class="width10"><input type="text" ng-change="getMultiSearch(searchCredit.search_credit_note_remark)" ng-keypress="funEnterPressHandler($event)" name="search_credit_note_remark" ng-model="searchCredit.search_credit_note_remark" class="multiSearch form-control width80" placeholder="Remark"></td>
                        <td class="width10"><input type="text" ng-change="getMultiSearch(searchCredit.search_created_by)" ng-keypress="funEnterPressHandler($event)" name="search_created_by" ng-model="searchCredit.search_created_by" class="multiSearch form-control width80" placeholder="Created By"></td>
                        <td class="width10">
                            <button ng-click="refreshMultisearch(divisionID)" type="button" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                            <button ng-click="closeMultisearch(divisionID)" type="button" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                    <tr dir-paginate="creditNotesObj in creditNotesList | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                        <td data-title="credit_note_no" class="ng-binding">[[creditNotesObj.credit_note_no]]</span></td>
                        <td ng-if="{{$division_id}} == 0" data-title="Division Name ">[[creditNotesObj.division_name]]</td>
                        <td data-title="Department Name ">[[creditNotesObj.department_name]]</td>
                        <td data-title="Customer Name" class="ng-binding">[[creditNotesObj.customer_name]]</td>
                        <td data-title="Invoice Number" class="ng-binding">[[creditNotesObj.invoice_no]]</td>
                        <td data-title="Credit Reference Number" class="ng-binding">[[creditNotesObj.credit_reference_no]]</td>
                        <td data-title="Payment made date" class="ng-binding">[[creditNotesObj.credit_note_date | date : 'dd-MM-yyyy']]</td>
                        <td data-title="Payment made amount" class="ng-binding">[[creditNotesObj.credit_note_amount]]</td>
                        <td data-title="Payment made amount" class="ng-binding">[[creditNotesObj.credit_note_remark]]</td>
                        <td data-title="Created By" class="ng-binding">[[creditNotesObj.createdByName]]</td>
                        <td class="width10">
                            <span class="hidden" style="display:none;" ng-if="{{defined('EDIT') && EDIT}}">
                                <button ng-click="funEditCreditNote(creditNotesObj.credit_note_id)" title="Edit Credit Note" class="btn btn-primary btn-sm"><i aria-hidden="true" class="fa fa-pencil-square-o"></i></button>
                            </span>
                            <span ng-if="{{defined('VIEW') && VIEW}}">
                                <button type="button" ng-click="funViewCreditNoteDetails(creditNotesObj.credit_note_id)" title="View Credit Note" class="btn btn-primary btn-sm"> <i class="fa fa-eye" aria-hidden="true"></i></button>
                            </span>
                            <span ng-if="{{defined('DELETE') && DELETE}}">
                                <button type="button" ng-click="funConfirmDeleteMessage(creditNotesObj.credit_note_id,divisionID)" title="Delete Credit Note" class="btn btn-danger btn-sm"> <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            </span>
                        </td>
                    </tr>
                    <tr ng-if="!creditNotesList.length">
                        <td colspan="8">No record found.</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">
                            <div class="box-footer clearfix">
                                <dir-pagination-controls></dir-pagination-controls>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </form>
</div>
