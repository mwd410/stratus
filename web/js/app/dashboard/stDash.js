(function(ng) {
    'use strict';

    ng.module('app.dashboard')
        .directive('stDash', function(_, $compile) {

            return {
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
                            if ($scope.widgetService) {

                                $scope.widgetService.selectedAccount = accounts[0];
                            }
                        }
                    );

                    $scope.getTitle = function() {
                        return ($scope.widgetService || {}).title;
                    };

                    $scope.menu = toggle();

                    this.register = function(widget) {

                        if (!$scope.widgetService) {
                            return;
                        }

                        if ($scope.widgetService.registerWidget) {

                            $scope.widgetService.registerWidget(widget);
                        }

                        if ($scope.widgetService.init) {

                            var lastRow = _.last($scope.dash.widgetRows),
                                lastColumn = _.last(lastRow.widgetColumns),
                                lastWidget = _.last(lastColumn.widgets);

                            if (lastWidget === widget) {
                                $scope.widgetService.init();
                            }
                        }
                    };
                },
                require     : 'stDash',
                scope       : {
                    dash          : '=stDash',
                    widgetService : '=',
                    toolbar       : '=?'
                },
                templateUrl : '/partials/stDash.html',
                link        : function(scope, el, attrs, ctrl) {

                    scope.toolbar = scope.toolbar || [];

                    _.each(el.find('span'), function(span) {

                        var toolbar = ng.element(span);
                        if (toolbar.hasClass('st-dash-toolbar')) {

                            var toolbarItems = scope.toolbar.map(function(item) {

                                var itemScope = scope.$new();

                                _.each(item.scope, function(value, key) {
                                    itemScope[key] = value;
                                });

                                return $compile(item.el)(itemScope);
                            });

                            toolbar.append(toolbarItems);

                            return false;
                        }
                    });
                }
            };
        });

})(window.angular);
