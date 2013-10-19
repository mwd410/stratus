(function(ng) {
    'use strict';

    ng.module('app.nav').controller('NavController', function($scope, NavService, $state) {

        $scope.menu = NavService;
        $scope.menuItems = NavService.getMenuItems();
        $scope.$state = $state;

        $scope.isAvailable = function(menu) {

            return NavService.isAvailable(menu);
        };

        $scope.expandMenu = function(menu) {

            NavService.expandMenu(menu);
        };

        $scope.isExpanded = function(menu) {

            return NavService.isExpanded(menu);
        };
    });

})(window.angular);