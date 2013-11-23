(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').controller('AlertManagementCtrl', [
        '$scope', 'alertApi', 'AccountService', 'serviceApi',
        function($scope, alertApi, accountApi, serviceApi) {

            // Can't $watch(alertApi.data), because that won't catch if
            // alertApi re-assigns alertApi.data to a different promise.
            $scope.$watch(function() {return alertApi.data;}, function(dataPromise) {

                dataPromise.then(function(data) {

                    $scope.alerts = data.alerts;
                });
            });

            accountApi.data.then(function(data) {

                $scope.accounts = [
                    {
                        id   : null,
                        name : 'All Accounts'
                    }
                ].concat(data.accounts);
            });

            serviceApi.data.then(function(data) {

                $scope.classifications = [
                    {
                        id   : null,
                        name : 'None'
                    }
                ].concat(data.pivots);
                $scope.comparisonTypes = data.comparisonTypes;
                $scope.calculationTypes = data.calculationTypes;
                $scope.timeFrames = data.timeFrames;
                $scope.valueTypes = data.valueTypes;
            });

        }]);

})(window.angular);
