(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').controller('ChargebackCtrl', function($scope, chargeback) {

        $scope.chargeback = chargeback;

        $scope.newStakeholder = {};

        $scope.canAdd = function() {
            var ns = $scope.newStakeholder;
            var can = ns.email && ns.name && ns.title;
            console.log(can);
            return can;
        };


    });

})(window.angular);
