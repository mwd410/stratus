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
                                        miniTitle    : '30 Day Rolling Avg.',
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
                                        miniTitle    : "Yesterday's Spend",
                                        type         : 'rollingAverage',
                                        params       : {
                                            days   : 1,
                                            labels : [
                                                'Yesterday',
                                                '2 Days Ago',
                                                'Difference'
                                            ]
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
                                flex    : 1,
                                widgets : [
                                    {
                                        flex         : 1,
                                        title        : 'Top Spenders',
                                        type         : 'topSpend',
                                        templateFile : 'tables.html'
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        widgetColumns : [
                            {
                                flex    : 1,
                                widgets : [
                                    {
                                        flex         : 1,
                                        title        : "Today's Top Line Items",
                                        type         : 'lineItems',
                                        templateFile : 'tables.html',
                                        columnStyles : [
                                            null,
                                            null,
                                            {
                                                width : '40px'
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            };

            $scope.breakdown = breakdown;

            breakdown.clean();
        });

})(window.angular);
