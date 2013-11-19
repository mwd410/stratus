(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').controller('AlertManagementCtrl', function($scope, alertApi) {

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

    });

})(window.angular);
