(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').controller('AlertManagementCtrl', [
        '$scope', 'alertApi', 'AccountService', 'serviceApi',
        function($scope, alertApi, accountApi, serviceApi) {

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
                            id   : null,
                            name : 'All Accounts'
                        }
                    ].concat(data.accounts);
                });
            }

            $scope.$watch(function() {return accountApi.data;}, function(accountPromise) {

                updateAccounts(accountPromise);
            });

            function updateServiceClassifications(data) {

                data.then(function(data) {

                    $scope.classifications = [
                        {
                            id : null,
                            name : 'None'
                        }
                    ].concat(data.pivots);
                });
            }

            function updateComparisonTypes(data) {

                $scope.comparisonTypes = data.then(function(data) {

                    return data.comparisonTypes;
                });
            }

            function updateCalculationTypes(data) {

                $scope.calculationTypes = data.then(function(data) {
                    return data.calculationTypes;
                });
            }

            function updatetimeFrames(data) {

                $scope.timeFrames = data.then(function(data) {

                    return data.timeFrames;
                });
            }

            function updateValueTypes(data) {

                $scope.valueTypes = data.then(function(data) {

                    return data.valueTypes;
                });
            }

            $scope.$watch(function() {return serviceApi.data;}, function(data) {

                updateServiceClassifications(data);
                updateComparisonTypes(data);
                updateCalculationTypes(data);
                updatetimeFrames(data);
                updateValueTypes(data);
            });

        }]);

})(window.angular);
