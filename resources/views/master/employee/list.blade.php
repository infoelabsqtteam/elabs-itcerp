<div class="row" ng-hide="isEmployeeListDiv">
    <form class="form-inline" method="POST" target="blank" action="{{url('master/employees/download-excel')}}" role="form" id="erpEmployeesFilterForm" name="erpEmployeesFilterForm" novalidate>
        <label for="submit">{{ csrf_field() }}</label>
        <!--header-->
        <div class="header">
            <div class="navbar-form navbar-left" role="search"><strong ng-click="refreshMultisearch()" title="Refresh">Employees <span ng-if="empdata.length">([[empdata.length]]) </span></strong></div>
            <div class="navbar-form navbar-right" role="search">
                <div class="nav-custom custom-display">
                    <input type="text" class="form-control ng-pristine ng-untouched ng-valid" placeholder="Search" name="search_keyword" ng-change="getEmployeeKeywordSearch(searchEmployee.search_keyword)" ng-model="searchEmployee.search_keyword">
                    <a ng-if="{{defined('ADD') && ADD}}" title="Add Employee" class="btn btn-primary" ng-click="navigatePage()">Add New</a>
                    <a ng-if="{{defined('ADD') && ADD}}" title="Upload CSV Records" class="btn btn-primary" href="{{ url('/employees/upload') }}">Upload</a>
                    <button type="button" ng-disabled="!empdata.length" class="form-control btn btn-default dropdown dropdown-toggle " data-toggle="dropdown" title="Download">
                        Download</button>
                    <div class="dropdown-menu">
                        <input type="submit" name="generate_employee_documents" class="dropdown-item" value="Excel">
                    </div>
                </div>
            </div>
        </div>
        <!--/header-->

        <div id="no-more-tables">
            <table class="col-sm-12 table-striped table-condensed cf">
                <thead class="cf">
                    <tr>
                        <th>
                            <label class="sortlabel" ng-click="sortBy('user_code')">Employee Code</label>
                            <span class="sortorder" ng-show="predicate === 'user_code'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th>
                            <label class="sortlabel" ng-click="sortBy('name')">Name </label>
                            <span class="sortorder" ng-show="predicate === 'name'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th>
                            <label class="sortlabel" ng-click="sortBy('email')">Email </label>
                            <span class="sortorder" ng-show="predicate === 'email'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th>
                            <label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
                            <span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th>
                            <label class="sortlabel">Departments</label>
                        </th>
                        <th>
                            <label class="sortlabel">Roles</label>
                        </th>
                        <th>
                            <label class="sortlabel">Equipment Type</label>
                        </th>
                        <th class="width5">
                            <label class="sortlabel" ng-click="sortBy('is_sales_person')">Is Sales Person</label>
                            <span class="sortorder" ng-show="predicate === 'is_sales_person'" ng-class="{reverse:reverse}"></span>
                        </th>
						<th class="width5">
                            <label class="sortlabel" ng-click="sortBy('is_sampler_person')">Is Sampler</label>
                            <span class="sortorder" ng-show="predicate === 'is_sampler_person'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th>
                            <label class="sortlabel">Signature</label>
                            <span class="sortorder" ng-show="predicate === 'user_signature'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th>
                            <label class="sortlabel">Status</label>
                            <span class="sortorder" ng-show="predicate === 'status'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th class="width8">
                            <label class="sortlabel" ng-click="sortBy('created_by')">Created By</label>
                            <span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th class="width8">
                            <label class="sortlabel" ng-click="sortBy('created_at')">Created On</label>
                            <span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th class="width8">
                            <label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
                            <span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th class="width10">Action
                            <button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-hide="multiSearchTr">
                        <td><input type="text" ng-change="getEmployeeMultiSearch()" name="search_user_code" ng-model="searchEmployee.search_user_code" class="multiSearch form-control" placeholder="User Code"></td>
                        <td><input type="text" ng-change="getEmployeeMultiSearch()" name="search_name" ng-model="searchEmployee.search_name" class="multiSearch form-control" placeholder="Name"></td>
                        <td><input type="text" ng-change="getEmployeeMultiSearch()" name="search_email" ng-model="searchEmployee.search_email" class="multiSearch form-control" placeholder="Email"></td>
                        <td><select ng-change="getEmployeeMultiSearch()" name="search_division" class="form-control multiSearch" ng-model="searchEmployee.search_division" ng-options="division.name for division in DivisionsList track by division.id"><option value="">All Branch</option></select></td>
                        <td><select ng-change="getEmployeeMultiSearch()" name="search_department" class="form-control multiSearch" ng-model="searchEmployee.search_department" ng-options="departments.name for departments in departmentList track by departments.id"><option value="">All Department</option></select></td>
                        <td><select ng-change="getEmployeeMultiSearch()" name="search_role" class="form-control multiSearch" ng-model="searchEmployee.search_role" ng-options="roleData.name for roleData in roleDataList track by roleData.id"><option value="">All Role</option></select></td>
                        <td><select ng-change="getEmployeeMultiSearch()" name="search_equipment" class="form-control multiSearch" ng-model="searchEmployee.search_equipment" ng-options="equipmentTypes.name for equipmentTypes in equipmentTypesList track by equipmentTypes.id"><option value="">All Equipment</option></select></td>
                        <td><input type="text" ng-change="getEmployeeMultiSearch()" name="search_is_sales_person" ng-model="searchEmployee.search_is_sales_person" class="multiSearch form-control" placeholder="Is Sales Person"></td>
						<td><input type="text" ng-change="getEmployeeMultiSearch()" name="search_is_sampler_person" ng-model="searchEmployee.search_is_sampler_person" class="multiSearch form-control" placeholder="Is Sampler"></td>
                        <td></td>
                        <td><select ng-change="getEmployeeMultiSearch()" name="search_status" class="form-control multiSearch" ng-model="searchEmployee.search_status" ng-options="employeeStatus.name for employeeStatus in employeeStatusList track by employeeStatus.id"><option value="">All Status</option></select></td>
                        <td><input type="text" ng-change="getEmployeeMultiSearch()" name="search_created_by" ng-model="searchEmployee.search_created_by" class="multiSearch form-control" placeholder="Created By"></td>
                        <td><input type="text" ng-change="getEmployeeMultiSearch()" name="search_created_at" ng-model="searchEmployee.search_created_at" class="multiSearch form-control visibility" placeholder="Created On"></td>
                        <td><input type="text" ng-change="getEmployeeMultiSearch()" name="search_updated_at" ng-model="searchEmployee.search_updated_at" class="multiSearch form-control visibility" placeholder="Updated On"></td>
                        <td class="width10">
                            <button ng-click="getEmployeeMultiSearch()" type="button" class="btn btn-primary btn-sm" title="Refresh"><i aria-hidden="true" class="fa fa-search"></i></button>
                            <button ng-click="refreshMultisearch()" type="button" class="btn btn-primary btn-sm" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                            <button ng-click="closeMultisearch()" type="button" class="btn btn-default btn-sm" title="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                    <tr dir-paginate="obj in empdata | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                        <td data-title="Employee Code">[[obj.userData.user_code]]</td>
                        <td data-title="Name">[[obj.userData.name]]</td>
                        <td data-title="Email">[[obj.userData.email]]</td>
                        <td data-title="Division Name">[[obj.userData.division_name]]</td>
                        <td data-title="Departments">
                            <span ng-if="obj.departments.length">
                                <a href="javascript:;" title="[[employeeTitle]]" ng-mouseover="funArrayToString(obj.departments,'department_name')" class="text-center" data-toggle="modal" data-target="#myModalDepartment_[[obj.userData.id]]"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <div class="modal fade" id="myModalDepartment_[[obj.userData.id]]" role="dialog">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header text-left">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"><span class="poptitle">Departments </span></h4>
                                            </div>
                                            <div class="modal-body">
                                                <ul><li id="[[deparmentObj.department_id]]" ng-repeat="deparmentObj in obj.departments">[[deparmentObj.department_name]]</li></ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </span>
                            <span ng-if="!obj.departments.length">-</span>
                        </td>
                        <td data-title="Roles">
                            <span ng-if="obj.roles.length">
                                <a href="javascript:;" title="[[employeeTitle]]" ng-mouseover="funArrayToString(obj.roles,'name')" class="text-center" data-toggle="modal" data-target="#myModalRole_[[obj.userData.id]]"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <div class="modal fade" id="myModalRole_[[obj.userData.id]]" role="dialog">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header text-left">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"><span class="poptitle">Roles</span></h4>
                                            </div>
                                            <div class="modal-body">
                                                <ul><li id="[[roleObj.role_id]]" ng-repeat="roleObj in obj.roles">[[roleObj.name]]</li></ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </span>
                            <span ng-if="!obj.roles.length">-</span>
                        </td>
                        <td data-title="Equipment Type">
                            <span ng-if="obj.equipmentType.length">
                                <a href="javascript:;" title="[[employeeTitle]]" ng-mouseover="funArrayToString(obj.equipmentType,'equipment_name')" class="text-center" data-toggle="modal" data-target="#myModalEquipmentType_[[obj.userData.id]]"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <div class="modal fade" id="myModalEquipmentType_[[obj.userData.id]]" role="dialog">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header text-left">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"><span class="poptitle">Equipment Types </span></h4>
                                            </div>
                                            <div class="modal-body custom-scroll">
                                                <ul><li id="[[equip.equipment_type_id]]" ng-repeat="equip in obj.equipmentType">[[equip.equipment_name]]</li></ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </span>
                            <span ng-if="!obj.equipmentType.length">-</span>
                        </td>
                        <td data-title="Sales Person"> [[obj.userData.is_sales_person | yesOrNo]]</td>
						<td data-title="Sales Person"> [[obj.userData.is_sampler_person | yesOrNo]]</td>
                        <td>
                            <span ng-if="obj.userData.user_signature" id="removeItemImage-[[obj.userData.id]]" ng-click="funRemoveItemImage([[obj.userData.id]])" title="Remove Image"><i class="fa fa-times-rectangle-o imgDelIcon" aria-hidden="true"></i></span>
                            <span ng-if="obj.userData.user_signature" style="width:100%;"><a href="{{SITE_URL.SIGN_PATH}}[[obj.userData.user_signature]]" target="_blank"><img id="user_signature_[[obj.userData.id]]" height="50%" width="50%" class="item_images img-thumbnail" title="[[obj.userData.user_signature]]" alt="[[obj.userData.user_signature]]" ng-src="{{SITE_URL.SIGN_PATH}}[[obj.userData.user_signature]]"></a></span>
                            <span ng-if="!obj.userData.user_signature" class="btn btn-default btn-file ng-scope">Upload <input type="file" id="[[obj.userData.id]]" name="user_signature" class="uploadSignImage width10"></span>
                        </td>
                        <td data-title="Status">[[obj.userData.status | activeOrInactiveUsers]]</td>
                        <td data-title="Created By">[[obj.userData.createdBy]]</td>
                        <td data-title="Created at">[[obj.userData.created_at]]</td>
                        <td data-title="Updated at">[[obj.userData.updated_at]]</td>
                        <td class="width10" ng-if="{{(defined('EDIT') && EDIT) || (defined('DELETE') && DELETE)}}">
                            <a ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" title="Update" class="btn btn-primary btn-sm" ng-click='funEditEmployee(obj.userData.id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a ng-if="{{defined('DELETE') && DELETE}}" href="javascript:;" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.userData.id,divisionID)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                    <tr ng-if="!empdata.length" class="noRecord">
                        <td colspan="13">No Record Found!</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="[[empdata.length]]">
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
