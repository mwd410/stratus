(function(ng) {
    'use strict';

    ng.module('app.breakdown').controller('BreakdownNavCtrl',
        function($scope, NavService, breakdown) {

            $scope.breakdown = breakdown;

            var last = null;

            $scope.onMenuItemClick = function(item) {

                last = item;

                breakdown.update(item);
            };

            $scope.isItemActive = function(item) {

                return item.isActive;
            };
        });

})(window.angular);