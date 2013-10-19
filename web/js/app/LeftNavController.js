(function(ng) {
    'use strict';

    ng.module('app').controller('LeftNavController',
        function($scope, NavService, breakdown) {

            $scope.breakdown = breakdown;

            var last = null;

            $scope.onMenuItemClick = function(item) {

                last = item;

                breakdown.update(item);
            };

            $scope.isItemActive = function(item) {

                if (!last) {
                    return false;
                }

                if (item.type !== last.type) {
                    return false;
                }

                if (item.hasOwnProperty('id') && item.id !== last.id) {
                    return false;
                }

                if (item.hasOwnProperty('sub_id') && item.sub_id != last.sub_id) {
                    return false;
                }

                return true;
            };
        });

})(window.angular);