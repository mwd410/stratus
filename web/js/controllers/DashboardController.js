(function(ng) {
    'use strict';

    ng.module('App')
        .controller('DashboardController', function($scope) {

            $scope.dashboard = {
                widgetRows : [
                    {
                        height        : 400,
                        widgetColumns : [
                            {
                                flex    : 1,
                                widgets : [
                                    {
                                        flex : 1,
                                        tpl : '/js/directives/tpl/total-cost.html'
                                    }
                                ]
                            }
                        ]
                    }
                ]
            };
        });

})(window.angular);