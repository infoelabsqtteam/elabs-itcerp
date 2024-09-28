@extends('layouts.app')
@section('content')
<script type="text/javascript" src="{!! asset('public/ang/controller/themeController.js') !!}"></script>	
<div class="container" ng-controller="themeController" ng-init="getAllParameters()">
	<!--display Messge Div--
	@include('includes.alertMessage')
	!--/display Messge Div-->
		
	<div class="row header">
			<strong class="pull-left headerText">Button Parameters</strong>			
	</div>
    <div class="row">
        <div id="no-more-tables">
            <table class="col-sm-12 table-striped table-condensed cf">      		
        		<thead class="cf">
					<th>
						<label class="sortlabel" ng-click="sortBy('btn_code')">Button Code  </label>
						<span class="sortorder" ng-show="predicate === 'btn_code'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('btn_name')">Name  </label>
						<span class="sortorder" ng-show="predicate === 'btn_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('btn_bg_color')">Background Color  </label>
						<span class="sortorder" ng-show="predicate === 'btn_bg_color'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('btn_text')"> Text  </label>
						<span class="sortorder" ng-show="predicate === 'btn_text'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('color')">Text Color </label>
						<span class="sortorder" ng-show="predicate === 'color'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('btn_border_color')">Border Color </label>
						<span class="sortorder" ng-show="predicate === 'btn_border_color'" ng-class="{reverse:reverse}"></span>						
					</th>
				</thead>
				<tbody> 
					<tr dir-paginate="obj in btnsParameterList | filter:searchDepartments | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="Company Code">[[obj.btn_code]]</td>
						<td data-title="Company Code">[[obj.btn_name]]</td>
						<td data-title="Company Code"><button class="btn btnPrev" style="background-color:[[obj.btn_bg_color]];"></button></td>
						<td data-title="Company Code">[[obj.btn_text]]</td>
						<td data-title="Company Code">[[obj.color]]</td>
						<td data-title="Company Code">[[obj.btn_border_color]]</td>
						<td class="width10">
							<button title="Update" <?php if(!empty($btn['EDIT'])){ echo "style='background-color:".$btn['EDIT']->btn_bg_color." !important;border-color:".$btn['EDIT']->btn_border_color." !important;color:".$btn['EDIT']->color." !important;'";  }  ?> class="btn btn-primary btn-sm"  ng-click='editDepartment(obj.id)'><?php if(!empty($btn['EDIT']->btn_text)){ print($btn['EDIT']->btn_text); }else{ echo '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'; } ?></button>						
							<button title="Delete" <?php if(!empty($btn['DELETE'])){ echo "style='background-color:".$btn['DELETE']->btn_bg_color." !important;border-color:".$btn['DELETE']->btn_border_color." !important;color:".$btn['DELETE']->color." !important;'";  }  ?> class="btn btn-danger btn-sm" ng-click='deleteDepartment(obj.id)'><?php if(!empty($btn['DELETE']->btn_text)){ print($btn['DELETE']->btn_text); }else{ echo '<i class="fa fa-trash-o" aria-hidden="true"></i>'; } ?></button>
						</td>
        			</tr>
        		</tbody>
        	</table>	  
		</div>
	</div>
</div>
@endsection