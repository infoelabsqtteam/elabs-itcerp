/*****************************tree view js*************************************
currentModule=1  (used on product categories module)
currentModule=2  (used on products module)
currentModule=3  (used on std test products module on product categories)
currentModule=4  (used on product categories module for filter tree view btn)
currentModule=5  (used on product module for filter tree view btn)
currentModule=6  (used on std product test module for filter tree view btn)
currentModule=7  (used on orders module)
currentModule=8  (used on parameter categories module)
currentModule=9  (used on parameters module)
currentModule=10 (used on parameter categories module for filter tree view btn)
currentModule=11 (used on parameter module for filter tree view btn)
currentModule=12 (used on std test products module on parameter categories)
currentModule=13 (used on product categories tree view module)
currentModule=14 (used on parameter categories tree view module)
currentModule=15 (used on location country,sate,city, popup view module)
currentModule=16 (used on add customer wise parameter module)
currentModule=19 (used on location country,state,city, popup view module)
currentModule=20 (used on location country,state, popup in reporting to view order module )
currentModule=21 (used on location country,state, popup reporting to view order module)

*********************************************************************************/

(function(l) {
    l.module("angularTreeview", []).directive("treeModel", function($compile){
        return {
            restrict: "A",
            link: function(a, g, c) {
			var e = c.treeModel,
				h = c.nodeLabel || "label",
				level = c.nodeLevel || "level",
				d = c.nodeChildren || "children",
				k = '<ul>'+
						'<li data-ng-if="[[currentModule]] != 15" class="treeli" data-ng-repeat="node in ' + e + '" data-filtertext="[[node.product_category_id]]">'+'<i class="collapsed glyphicon glyphicon-minus-sign" data-ng-show="node.' + d + '.length && node.expanded" data-ng-click="selectNodeHead(node, $event)"></i>'+
							'<i class="expanded glyphicon glyphicon-plus-sign" data-ng-show="node.' + d + '.length && !node.expanded" data-ng-click="selectNodeHead(node, $event)"></i>'+
							'<i class="normal glyphicon glyphicon-minus" data-ng-hide="node.' + d + '.length"></i>'+ 
							'<span data-ng-class="node.selected">[[node.' + h + ']]</span>'+
							'<span data-ng-if="[[currentModule]]==1"><i data-ng-click="setSelectedProductCategoryId(node)" class="glyphicon glyphicon-plus" data-ng-if="[[node.' + level + ']]==0 || [[node.' + level + ']]==1"></i></span>'+
							'<span data-ng-if="[[currentModule]]==17"><i data-ng-click="funGetSelectedStateId(node)" class="glyphicon glyphicon-plus"></i></span>'+'<span data-ng-if="[[currentModule]]==18"><i data-ng-click="funGetSelectedInvoicingStateId(node)" class="glyphicon glyphicon-plus"></i></span>'+
							'<span data-ng-if="[[currentModule]]==2"><i data-ng-click="setSelectedProductCategoryId(node)" class="glyphicon glyphicon-plus" data-ng-if="[[node.' + level + ']]==2"></i></span>'+
							'<span data-ng-if="[[currentModule]]==3"><i data-ng-click="getProductsAndStandards(node)" class="glyphicon glyphicon-plus" data-ng-if="[[node.' + level + ']]==2"></i></span>'+
							'<span data-ng-if="[[currentModule]]==4"><i data-ng-click="filterSelectedProductCategoryId(node)" class="glyphicon glyphicon-plus"></i></span>'+
							'<span data-ng-if="[[currentModule]]==5"><i data-ng-if="[[node.' + level + ']]==2" data-ng-click="filterSelectedProductCategoryId(node)" class="glyphicon glyphicon-plus"></i></span>'+
							'<span data-ng-if="[[currentModule]]==6"><i data-ng-click="filterSelectedProductCategoryId(node)" class="glyphicon glyphicon-plus"></i></span>'+
							'<span data-ng-if="[[currentModule]]==7"><i data-ng-click="setSelectedProductCategoryId(node)" class="glyphicon glyphicon-plus" data-ng-if="[[node.' + level + ']]==2"></i></span>'+
							'<span data-ng-if="[[currentModule]]==8"><i data-ng-click="funSetSelectedParameterCategory(node)" class="glyphicon glyphicon-plus" data-ng-if="[[node.' + level + ']]==0 || [[node.' + level + ']]==1"></i></span>'+
							'<span data-ng-if="[[currentModule]]==9"><i data-ng-click="funSetSelectedParameterCategory(node)" class="glyphicon glyphicon-plus" data-ng-if="[[node.' + level + ']]==0 || [[node.' + level + ']]==1"></i></span>'+
							'<span data-ng-if="[[currentModule]]==10"><i data-ng-click="filterSelectedParameterCategoryId(node)" class="glyphicon glyphicon-plus"></i></span>'+
							'<span data-ng-if="[[currentModule]]==11"><i data-ng-click="filterSelectedParameterCategoryId(node)" class="glyphicon glyphicon-plus"></i></span>'+
							'<span data-ng-if="[[currentModule]]==12"><i data-ng-click="funSetSelectedProductCategory(node)" class="glyphicon glyphicon-plus" data-ng-if="[[node.' + level + ']]==0 || [[node.' + level + ']]==1"></i></span>'+
							'<span data-ng-if="[[currentModule]]==13">'+
								'<i title="Add Category" data-ng-click="addProductCategoryNode(node)" class="glyphicon glyphicon-plus mL5" data-ng-if="[[node.' + level + ']]==0 || [[node.' + level + ']]==1"></i>'+
								'<i title="Edit Category" data-ng-click="editProductCategory(node.p_category_id)" class="glyphicon glyphicon-edit mL5"></i>'+
								'<i title="Delete Category" data-ng-click="funConfirmDeleteMessage(node.p_category_id)" class="glyphicon glyphicon-trash mL5"></i>'+
							'</span>'+
							'<span data-ng-if="[[currentModule]]==14">'+
								'<i title="Add Category" data-ng-click="addParameterCategoryNode(node)" class="glyphicon glyphicon-plus mL5" data-ng-if="[[node.' + level + ']]==0 || [[node.' + level + ']]==1"></i>'+
								'<i title="Edit Category" data-ng-click="funEditTestParameter(node)" class="glyphicon glyphicon-edit mL5"></i>'+
								'<i title="Delete Category" data-ng-click="funConfirmDeleteMessage(node)" class="glyphicon glyphicon-trash mL5"></i>'+
							'</span>'+
							'<span data-ng-if="[[currentModule]]==19"><i data-ng-click="funGetSelectedNodeId(node)" class="glyphicon glyphicon-plus [[node.' + level + ']]" data-ng-if="[[node.' + level + ']]==1 || [[node.' + level + ']]==2"></i></span>'+
							'<span data-ng-if="[[currentModule]]==20"><i data-ng-click="funGetSelectedReportingToStateId(node)" class="glyphicon glyphicon-plus [[node.' + level + ']]" data-ng-if="[[node.' + level + ']]==1 || [[node.' + level + ']]==2"></i></span>'+
							'<span data-ng-if="[[currentModule]]==21"><i data-ng-click="funGetSelectedInvoicingStateId(node)" class="glyphicon glyphicon-plus [[node.' + level + ']]" data-ng-if="[[node.' + level + ']]==1 || [[node.' + level + ']]==2"></i></span>'+
							'<div data-ng-show="node.expanded" data-tree-model="node.' + d + '" data-node-id=' + (c.nodeId || "id") + " data-node-label=" + h + " data-node-children=" + d + "></div>"+
						'</li>'+
						'<li data-ng-if="[[currentModule]] == 15" class="treeli" data-ng-repeat="node in ' + e + '" data-filtertext="node.state_id">'+
							'<i class="collapsed glyphicon glyphicon-minus-sign" data-ng-show="node.' + d + '.length && node.expanded" data-ng-click="selectNodeHead(node, $event)"></i>'+
							'<i class="expanded glyphicon glyphicon-plus-sign" data-ng-show="node.' + d + '.length && !node.expanded" data-ng-click="selectNodeHead(node, $event)"></i>'+
							'<i class="normal glyphicon glyphicon-minus" data-ng-hide="node.' + d + '.length"></i>'+ 
							'<span data-ng-class="node.selected">[[node.' + h + ']]</span>'+
							'<span><i data-ng-click="funGetSelectedStateId(node)" class="glyphicon glyphicon-plus"></i></span>'+
							'<div data-ng-show="node.expanded" data-tree-model="node.' + d + '" data-node-id=' + (c.nodeId || "id") + " data-node-label=" + h + " data-node-children=" + d + "></div>"+
						'</li>'+
					'</ul>';
                e && e.length && (c.angularTreeview ? (a.$watch(e, function(m, b) {
                    g.empty().html($compile(k)(a))
                }, !1), a.selectNodeHead = a.selectNodeHead || function(a, b) {
                    b.stopPropagation && b.stopPropagation();
                    b.preventDefault && b.preventDefault();
                    b.cancelBubble = !0;
                    b.returnValue = !1;
                    a.expanded = !a.expanded
                }, a.selectNodeLabel = a.selectNodeLabel || function(c, b) {
                    b.stopPropagation && b.stopPropagation();
                    b.preventDefault && b.preventDefault();
                    b.cancelBubble = !0;
                    b.returnValue = !1;
                    a.currentNode && a.currentNode.selected && (a.currentNode.selected = void 0);
                    c.selected = "selected";
                    a.currentNode = c
                }) : g.html($compile(k)(a)))
            }
        }
    })
})(angular);