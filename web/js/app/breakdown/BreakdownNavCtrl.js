(function(ng) {
    'use strict';

    ng.module('app.breakdown').controller('BreakdownNavCtrl',
        function($scope, NavService, breakdown) {

            $scope.breakdown = breakdown;

            $scope.onMenuItemClick = function(item) {

                if (!item.type) {

                } else {
                    breakdown.update(item);
                }
            };

            $scope.isItemActive = function(item) {

                return item.isActive;
            };
        });

})(window.angular);
