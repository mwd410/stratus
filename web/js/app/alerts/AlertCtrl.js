(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').controller('AlertCtrl', function($scope) {

        $scope.classifications = [
            {
                id : null,
                name : 'All'
            },
            {
                name : 'Service Provider',
                id : '1'
            },
            {
                name : 'Service Type',
                id : '2'
            }
        ];

        $scope.serviceProviders = [
            {
                id : '1',
                name : 'Amazon'
            },
            {
                id : '2',
                name : 'Google'
            }
        ];

        $scope.serviceProviderProducts = {
            '1' : [
                {
                    id : null,
                    name : 'All'
                },
                {
                    id : '1',
                    name : 'EC2'
                },
                {
                    id : '2',
                    name : 'S3'
                },
                {
                    id : '3',
                    name : 'VPC'
                }
            ],
            '2' : [
                {
                    id   : null,
                    name : 'All'
                },
                {
                    id : '1',
                    name : 'Google Product 1'
                },
                {
                    id : '2',
                    name : 'Google Product 2'
                }
            ]
        };

        $scope.serviceLabel = function() {

            if ($scope.alert.alert_classification_type_id == 1) {
                return 'Service Provider';
            } else if ($scope.alert.alert_classification_type_id == 2) {
                return 'Service Type';
            } else {
                return '';
            }
        };

        $scope.serviceOptions = function() {

            var classification = $scope.alert.alert_classification_type_id;

            if (classification == 1) {
                return $scope.serviceProviders;
            }
            return [];
        };

        $scope.products = function() {

            return $scope.serviceProviderProducts[$scope.alert.service_provider_id];
        };
    });

})(window.angular);
