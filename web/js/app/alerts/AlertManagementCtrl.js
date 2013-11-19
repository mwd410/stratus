(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').controller('AlertManagementCtrl', function($scope, alertApi, AccountService) {

        // Any assigning of alertApi data to $scope should be done here.
        function updateData(dataPromise) {

            $scope.alerts = dataPromise.then(function(data) {

                return data.alerts;
            });
        }

        // Can't $watch(alertApi.data), because that won't catch if
        // alertApi re-assigns alertApi.data to a different promise.
        $scope.$watch(function() {return alertApi.data;}, function(dataPromise) {

            updateData(dataPromise);
        });

        function updateAccounts(accountPromise) {

            $scope.accounts = accountPromise.then(function(data) {

                return [
                    {
                        id : null,
                        name : 'All Accounts'
                    }
                ].concat(data.accounts);
            });
        }

        $scope.$watch(function(){return AccountService.data;}, function(accountPromise) {

            updateAccounts(accountPromise);
        });
    });

})(window.angular);
