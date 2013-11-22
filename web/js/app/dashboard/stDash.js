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
                controller  : function($scope, NavService, AccountService, toggle) {

                    $scope.isLeftExpanded = function() {

                        return NavService.isExpanded('left');
                    };

                    $scope.accounts = AccountService.data.then(
                        function(accounts) {
                            console.log(arguments);

                            if (accounts.length > 1) {
                                return [
                                    {
                                        name : 'All'
                                    }
                                ].concat(accounts);
                            } else {
                                return accounts;
                            }
                        }
                    );
                    /*
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
                    });*/

                    $scope.getTitle = function() {
                        return $scope.widgetService.title;
                    };

                    $scope.menu = toggle();
                },
                link        : function(scope, el, attrs, ctrl) {

                }
            };
        });

})(window.angular);
