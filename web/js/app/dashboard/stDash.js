(function(ng) {
    'use strict';

    ng.module('app.dashboard')
        .directive('stDash', function(_) {

            return {
                require : 'stDash',
                scope       : {
                    dash : '=stDash',
                    widgetService : '='
                },
                templateUrl : '/partials/stDash.html',
                controller  : function($scope, NavService, AccountService, toggle) {

                    $scope.isLeftExpanded = function() {

                        return NavService.isExpanded('left');
                    };

                    AccountService.accounts.then(
                        function(accounts) {

                            if (accounts.length > 1) {
                                accounts =  [
                                    {
                                        name : 'All'
                                    }
                                ].concat(accounts);
                            }

                            $scope.accounts = accounts;
                            $scope.widgetService.selectedAccount = accounts[0];
                        }
                    );

                    $scope.getTitle = function() {
                        return $scope.widgetService.title;
                    };

                    $scope.menu = toggle();

                    this.register = function(widget) {
                        $scope.widgetService.registerWidget(widget);

                        var lastRow = _.last($scope.dash.widgetRows),
                            lastColumn = _.last(lastRow.widgetColumns),
                            lastWidget = _.last(lastColumn.widgets);

                        if (lastWidget === widget) {
                            $scope.widgetService.init();
                        }
                    };
                },
                link        : function(scope, el, attrs, ctrl) {

                }
            };
        });

})(window.angular);
