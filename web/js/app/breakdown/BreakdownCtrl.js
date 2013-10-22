(function(ng) {
    'use strict';

    ng.module('app.breakdown')
        .controller('BreakdownCtrl', function($scope, breakdown) {

            $scope.dashboard = {
                widgetRows : [
                    {
                        height        : 70,
                        widgetColumns : [
                            {
                                flex    : 1,
                                widgets : [
                                    {
                                        flex : 1,
                                        type : 'eomProjection'
                                    }
                                ]
                            },
                            {
                                flex : 1
                            },
                            {
                                flex : 1
                            },
                            {
                                flex : 1
                            }
                        ]
                    }
                ]
            };
        });

})(window.angular);