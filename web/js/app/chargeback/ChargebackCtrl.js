(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').controller('ChargebackCtrl', function($scope, chargeback) {

        $scope.chargeback = chargeback;

        $scope.newStakeholder = {};

        $scope.canAdd = function() {
            var ns = $scope.newStakeholder;
            return ns.email && ns.name && ns.title;
        };

        $scope.add = function() {
            chargeback.createStakeholder($scope.newStakeholder);
        };
    });

})(window.angular);
