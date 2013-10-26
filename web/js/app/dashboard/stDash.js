(function(ng) {
    'use strict';

    ng.module('app.dashboard')
        .directive('stDash', function() {

            return {
                require : 'stDash',
                scope       : {
                    dash : '=stDash',
                    widgetService : '='
                },
                controller  : function($scope, NavService, AccountService) {

                    $scope.isLeftExpanded = function() {

                        return NavService.isExpanded('left');
                    };

                    $scope.accountService = AccountService;

                    $scope.$watch('accountService.all', function(accounts) {

                        if (accounts.length <= 1) {
                            $scope.accounts = accounts;
                        } else {
                            $scope.accounts = [
                                {
                                    name : 'All'
                                }
                            ].concat(accounts);
                        }

                        $scope.widgetService.selectedAccount = $scope.accounts[0];
                    });
                },
                link        : function(scope, el, attrs, ctrl) {

                    scope.getTitle = function() {
                        return scope.widgetService.title;
                    };
                }
            };
        });

})(window.angular);