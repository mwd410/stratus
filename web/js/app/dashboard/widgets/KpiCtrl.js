(function(ng, undefined) {
    'use strict';

    ng.module('app.dashboard').controller('KpiCtrl', function($scope, toggle) {

        $scope.toggle = toggle();
    });

})( window.angular);