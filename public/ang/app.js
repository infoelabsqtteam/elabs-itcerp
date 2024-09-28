var app = angular.module('itcApp', ['ngMessages', 'angularUtils.directives.dirPagination','ngMaterial','ngRoute','dndLists','cp.ngConfirm','angularTreeview','textAngular','ui.tinymce'])
.constant('BASE_URL', SITE_URL)
.constant('CURRENTROLE', CURRENTROLE)
.config(function ($interpolateProvider){
	$interpolateProvider.startSymbol('[[');
	$interpolateProvider.endSymbol(']]');
}).config(function($routeProvider) {
    $routeProvider
    .when("/uploadDivisions", {
        templateUrl : "uploadDivisions.htm"
    });
});

app.filter('capitalize', function() {
  return function(input, scope){
   if(input){
		input = input.toLowerCase();
		return input.substring(0,1).toUpperCase()+input.substring(1);
	}
  }
});

app.filter('capitalizeAll', function() {
  return function(input, scope){
   if(input){
		return input.toUpperCase();
	}
  }
});

app.filter('removeUnderscores', [function() {
	return function(string){
		if (!angular.isString(string)) {
		    return string;
		}
		input = string.replace(/[/_/]/g, ' ');
		input = input.toLowerCase();
		return input.substring(0,1).toUpperCase()+input.substring(1);
	};
}]);

app.filter('strReplace', function () {
  return function (input, from, to) {
    input = input || '';
    from = from || '';
    to = to || '';
    input = input.replace(new RegExp(from, 'g'), to);
    return input.toLowerCase();
  };
});

// remove whitespaces from a string
app.filter('nospace', function () {
    return function (value) {
        return (!value) ? '' : value.replace(/ /g, '');
    };
});

//Applying Yes/No Filters
app.filter('yesOrNo', function() {
	return function(input) {
		return input ? 'Yes' : 'No' ;
	};
});

//Applying Active/Inactive Filters
app.filter('activeOrInactive', function() {
	return function(input) {
		return input ? 'Active' : 'Inactive' ;
	};
});

//Applying Active/Inactive Filters for Users
app.filter('activeOrInactiveUsers', function() {
	return function(input) {
		if (input == '1') {
			return 'Active';
		}else if (input == '2') {
			return 'Inactive' ;
		}else{
			return '-';
		}
	};
});

