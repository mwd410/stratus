'use strict';

app.controller('AnalysisOverviewController', function($scope) {

    $scope.sort = {
        property : 'name',
        reverse  : false
    };

    $scope.sortBy = function(property) {
        $scope.sort.reverse = !$scope.sort.reverse;
        $scope.sort.property = property;
    };

    $scope.accounts = overviewGridData;

});