(function(ng) {
    'use strict';

    ng.module('app.nav').controller('NavController', function($scope, NavService, $state) {

        $scope.menu = NavService;
        $scope.menuItems = NavService.getMenuItems();
        $scope.$state = $state;
    });

})(window.angular);