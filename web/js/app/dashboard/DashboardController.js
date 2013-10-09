(function(ng) {
    'use strict';

    ng.module('app.dashboard')
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
                                        flex : 1
                                    }
                                ]
                            }
                        ]
                    }
                ]
            };
        });

})(window.angular);