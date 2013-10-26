(function(ng) {
    'use strict';

    ng.module('app.breakdown')
        .controller('BreakdownCtrl', function($scope, breakdown) {

            $scope.dashboard = {
                widgetRows : [
                    {
                        height        : 105,
                        widgetColumns : [
                            {
                                flex    : 1,
                                widgets : [
                                    {
                                        flex         : 1,
                                        miniTitle    : 'Monthly Spend',
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
                                flex    : 1,
                                widgets : [
                                    {
                                        flex         : 1,
                                        miniTitle    : '30 Day Rolling Average',
                                        type         : 'rollingAverage',
                                        params       : {
                                            days : 30
                                        },
                                        templateFile : 'kpi.html'
                                    }
                                ]
                            },
                            {
                                flex    : 1,
                                widgets : [
                                    {
                                        flex         : 1,
                                        miniTitle    : '7 Day Rolling Average',
                                        type         : 'rollingAverage',
                                        params       : {
                                            days : 7
                                        },
                                        templateFile : 'kpi.html'
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        height        : 300,
                        widgetColumns : [
                            {
                                flex    : 1,
                                widgets : [
                                    {
                                        flex         : 1,
                                        title        : 'Total Spend per Day',
                                        type         : 'dailyCost',
                                        templateFile : 'bigGraph.html',
                                        tplService   : 'dailyCost'
                                    }
                                ]
                            }
                        ]
                    }
                ]
            };

            $scope.breakdown = breakdown;
        });

})(window.angular);