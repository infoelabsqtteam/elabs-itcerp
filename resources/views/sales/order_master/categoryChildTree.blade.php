<ul>
    @foreach($childs as $child)
        <li>
            @if(!empty(count($child->childs)))
                <i class='indicator glyphicon glyphicon-plus-sign'></i>
            @endif
            
            {{ $child->p_category_name }}
            
            <span ng-if="{{$child->level}}==2" ng-click="funGetTestingProducts({{$child->p_category_id}})" class="selectCatPlus">+</span>
            
            @if(count($child->childs))
                @include('sales.order_master.categoryChildTree',['childs' => $child->childs])
            @endif
        </li>
    @endforeach
</ul>