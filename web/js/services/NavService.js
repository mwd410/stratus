(function(ng) {
    'use strict';

    ng.module('App').service('NavService', function() {

        var menus = {
            app : [
                {
                    state : 'app.overview',
                    name  : 'Overview'
                },
                {
                    state : 'app.breakdown',
                    name  : 'Breakdown'
                },
                {
                    state : 'app.chargeback',
                    name  : 'Chargeback'
                },
                {
                    state : 'app.alerts',
                    name  : 'Alerts'
                },
                {
                    state : 'app.reports',
                    name  : 'Reports'
                },
                {
                    state : 'app.savings',
                    name  : 'Savings'
                }
            ]
        };

        return {
            expandedMenu : null,
            expandMenu   : function(menu) {

                if (this.expandedMenu === menu) {
                    this.expandedMenu = null;
                } else {
                    this.expandedMenu = menu;
                }
            },
            getMenuItems : function() {

                return menus.app;
            }
        };
    });

    ng.module('App').controller('NavController', function($scope, NavService, $state) {

        $scope.menu = NavService;
        $scope.menuItems = NavService.getMenuItems();
        $scope.$state = $state;
    });

})(window.angular);