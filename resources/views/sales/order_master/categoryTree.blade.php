<div id="orderTestingProductCategory" class="modal fade" role="dialog">
	  <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Test Product Categories : [[selectedProductCategoryName]]</h4>
            </div>
            <div class="modal-body">
                  <div class="panel-body">
                      <div class="row">
                          <div class="col-md-12">
                              <ul id="OrderCategoryTree">
                                  @foreach($categories as $category)
                                      <li>
                                          @if(!empty(count($category->childs)))
                                              <i class='indicator glyphicon glyphicon-plus-sign'></i>
                                          @endif
                                          
                                          {{ $category->p_category_name }}
                                          
                                          @if(count($category->childs))
                                              @include('sales.order_master.categoryChildTree',['childs' => $category->childs])
                                          @endif
                                      </li>
                                  @endforeach
                              </ul>
                          </div>
                      </div>
                  </div>
            </div>
        </div>
	</div>
    <!--treeview js-->
	<script type="text/javascript" src="{!! asset('public/js/treeview.js') !!}"></script>
	<link href="{!! asset('public/css/treeview.css') !!}" rel="stylesheet" type="text/css"/>
	<!--/treeview js-->
</div>
