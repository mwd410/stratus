(function(ng) {
    'use strict';

    ng.module('app.breakdown')
        .controller('BreakdownCtrl', function($scope, breakdown) {

            $scope.dashboard = {
                widgetRows : [
                    {
                        widgetColumns : [
                            {
                                flex    : 1,
                                widgets : [
                                    {
                                        flex         : 1,
                                        miniTitle    : 'Monthly Spend',
                                        type         : 'monthlySpend',
                                        templateFile : 'kpi.html'
                                    }
                                ]
                            },
                            {
                                flex    : 1,
                                widgets : [
                                    {
                                        flex         : 1,
                                        miniTitle    : 'MTD Spend',
                                        type         : 'monthToDate',
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
                    },
                    {
                        widgetColumns : [
                            {
                                flex : 1,
                                widgets : [
                                    {
                                        flex : 1,
                                        title : 'Top Spenders',
                                        type : 'topSpend',
                                        templateFile : 'topSpend.html'
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