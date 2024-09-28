<!-- Included here because the External Resources panel is annoying to use -->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular-animate.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular-aria.min.js"></script>
<script type="text/javascript" src="{!! asset('public/ang/modules/angular-messages.js') !!}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.0.3/angular-material.min.js"></script>
<link href="https://ajax.googleapis.com/ajax/libs/angular_material/1.0.3/angular-material.min.css" rel="stylesheet" type="text/css"/>
<body ng-app="itcApp">
   <div ng-controller="indentController as vm">
        <md-toolbar class="md-padding">
            <md-autocomplete md-selected-item="selectedItem" md-search-text="searchText" md-items="item in vm.getMatches(searchText)" md-item-text="item.name" placeholder="Search states" md-no-cache="true">
                <md-item-template>
                    <span>[[item.abbreviation]] - </span>
                    <span md-highlight-text="searchText" md-highlight-flags="^i">[[item.name]]</span>
                </md-item-template>
                <md-not-found>
                    No matches found.
                </md-not-found>
            </md-autocomplete>
        </md-toolbar>
    </div>
</body>
<script>
	var app = angular.module('itcApp', ['ngMaterial'])
        .constant('BASE_URL', 'http://localhost/itcerp/')
		.config(function ($interpolateProvider){
			$interpolateProvider.startSymbol('[[');
			$interpolateProvider.endSymbol(']]');
		});
    app.controller('indentController', function($scope, $q, $timeout) {
        this.getMatches = function(searchText) {
            var deferred = $q.defer();

            $timeout(function() {
                var states = getStates().filter(function(state) {
                    return (state.name.toUpperCase().indexOf(searchText.toUpperCase()) !== -1 || state.abbreviation.toUpperCase().indexOf(searchText.toUpperCase()) !== -1);
                });
                deferred.resolve(states);
            }, 1500);

            return deferred.promise;
        }
    });


function getStates() {
    return [{
        "name": "Alabama",
        "abbreviation": "AL"
    }, {
        "name": "Alaska",
        "abbreviation": "AK"
    }, {
        "name": "American Samoa",
        "abbreviation": "AS"
    }, {
        "name": "Arizona",
        "abbreviation": "AZ"
    }, {
        "name": "Arkansas",
        "abbreviation": "AR"
    }, {
        "name": "California",
        "abbreviation": "CA"
    }, {
        "name": "Colorado",
        "abbreviation": "CO"
    }, {
        "name": "Connecticut",
        "abbreviation": "CT"
    }, {
        "name": "Delaware",
        "abbreviation": "DE"
    }, {
        "name": "District Of Columbia",
        "abbreviation": "DC"
    }, {
        "name": "Federated States Of Micronesia",
        "abbreviation": "FM"
    }, {
        "name": "Florida",
        "abbreviation": "FL"
    }, {
        "name": "Georgia",
        "abbreviation": "GA"
    }, {
        "name": "Guam",
        "abbreviation": "GU"
    }, {
        "name": "Hawaii",
        "abbreviation": "HI"
    }, {
        "name": "Idaho",
        "abbreviation": "ID"
    }, {
        "name": "Illinois",
        "abbreviation": "IL"
    }, {
        "name": "Indiana",
        "abbreviation": "IN"
    }, {
        "name": "Iowa",
        "abbreviation": "IA"
    }, {
        "name": "Kansas",
        "abbreviation": "KS"
    }, {
        "name": "Kentucky",
        "abbreviation": "KY"
    }, {
        "name": "Louisiana",
        "abbreviation": "LA"
    }, {
        "name": "Maine",
        "abbreviation": "ME"
    }, {
        "name": "Marshall Islands",
        "abbreviation": "MH"
    }, {
        "name": "Maryland",
        "abbreviation": "MD"
    }, {
        "name": "Massachusetts",
        "abbreviation": "MA"
    }, {
        "name": "Michigan",
        "abbreviation": "MI"
    }, {
        "name": "Minnesota",
        "abbreviation": "MN"
    }, {
        "name": "Mississippi",
        "abbreviation": "MS"
    }, {
        "name": "Missouri",
        "abbreviation": "MO"
    }, {
        "name": "Montana",
        "abbreviation": "MT"
    }, {
        "name": "Nebraska",
        "abbreviation": "NE"
    }, {
        "name": "Nevada",
        "abbreviation": "NV"
    }, {
        "name": "New Hampshire",
        "abbreviation": "NH"
    }, {
        "name": "New Jersey",
        "abbreviation": "NJ"
    }, {
        "name": "New Mexico",
        "abbreviation": "NM"
    }, {
        "name": "New York",
        "abbreviation": "NY"
    }, {
        "name": "North Carolina",
        "abbreviation": "NC"
    }, {
        "name": "North Dakota",
        "abbreviation": "ND"
    }, {
        "name": "Northern Mariana Islands",
        "abbreviation": "MP"
    }, {
        "name": "Ohio",
        "abbreviation": "OH"
    }, {
        "name": "Oklahoma",
        "abbreviation": "OK"
    }, {
        "name": "Oregon",
        "abbreviation": "OR"
    }, {
        "name": "Palau",
        "abbreviation": "PW"
    }, {
        "name": "Pennsylvania",
        "abbreviation": "PA"
    }, {
        "name": "Puerto Rico",
        "abbreviation": "PR"
    }, {
        "name": "Rhode Island",
        "abbreviation": "RI"
    }, {
        "name": "South Carolina",
        "abbreviation": "SC"
    }, {
        "name": "South Dakota",
        "abbreviation": "SD"
    }, {
        "name": "Tennessee",
        "abbreviation": "TN"
    }, {
        "name": "Texas",
        "abbreviation": "TX"
    }, {
        "name": "Utah",
        "abbreviation": "UT"
    }, {
        "name": "Vermont",
        "abbreviation": "VT"
    }, {
        "name": "Virgin Islands",
        "abbreviation": "VI"
    }, {
        "name": "Virginia",
        "abbreviation": "VA"
    }, {
        "name": "Washington",
        "abbreviation": "WA"
    }, {
        "name": "West Virginia",
        "abbreviation": "WV"
    }, {
        "name": "Wisconsin",
        "abbreviation": "WI"
    }, {
        "name": "Wyoming",
        "abbreviation": "WY"
    }];
}
</script>
