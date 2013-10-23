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
                                        flex         : 1,
                                        miniTitle    : 'Monthly Projection',
                                        type         : 'eomProjection',
                                        templateFile : 'kpi.html'
                                    }
                                ]
                            },
                            {
                                flex    : 1,
                                widgets : [
                                    {
                                        flex         : 1,
                                        miniTitle    : 'Last Month Spend',
                                        type         : 'lastMonthSpend',
                                        templateFile : 'kpi.html'
                                    }
                                ]
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

            $scope.registerWidget = function(widget) {

                breakdown.registerWidget(widget);
            };
        });

})(window.angular);