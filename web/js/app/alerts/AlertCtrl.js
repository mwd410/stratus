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

        $scope.accounts = [
            {
                id : '1',
                name : 'Account 1'
            },
            {
                id : '2',
                name : 'Account 2'
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
    });

})(window.angular);
