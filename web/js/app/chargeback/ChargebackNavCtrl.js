(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').controller('ChargebackNavCtrl', function($scope, chargeback) {

        $scope.chargeback = chargeback;

        $scope.isItemActive = function(item) {

            return chargeback.isFilteredBy(item);
        };

        $scope.onMenuItemClick = function( item ) {
            chargeback.setFilter(item);
        };
    });

})(window.angular);
