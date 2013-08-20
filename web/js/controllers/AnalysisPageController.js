'use strict';

app.controller('AnalysisPageController', function($scope) {

    $scope.path = window.location.pathname;

    var id,
        pathSuffix = '';
    if (id = $scope.path.split('/').pop().match(/^\d+$/)) {
        pathSuffix = '/' + id;
    }

    $scope.pages = [
        {
            path  : '/analysis/overview' + pathSuffix,
            title : 'Overview'
        },
        {
            path  : '/analysis/totals' + pathSuffix,
            title : 'Totals'
        },
        {
            path  : '/analysis/instances' + pathSuffix,
            title : 'Instances'
        },
        {
            path  : '/analysis/volumes' + pathSuffix,
            title : 'Volumes'
        }
    ];

});