(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').controller('ChargebackCtrl', function($scope, chargeback) {

        $scope.chargeback = chargeback;

    });

})(window.angular);
