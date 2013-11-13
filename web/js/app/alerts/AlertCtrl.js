(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').controller('AlertCtrl', function($scope) {

        $scope.classificationTypes = [
            {
                label : 'Service Provider',
                value : '1'
            },
            {
                label : 'Service Type',
                value : '2'
            }
        ];

        $scope.alert = {};

    });

})(window.angular);
