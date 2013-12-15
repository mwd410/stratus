(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').controller('ChargebackNavCtrl', function($scope) {

        $scope.chargeback = {
            menus    : [
                {
                    name  : 'Service Provider',
                    items : [
                        {
                            id       : 1,
                            name     : 'Amazon',
                            isActive : true,
                            subItems : [
                                {
                                    id   : 1,
                                    name : 'EC2'
                                }
                            ]
                        }
                    ]
                }
            ],
            accounts : {
                '1' : {
                    name     : 'Stratus 1',
                    products : {
                        '1' : {
                            name  : 'Amazon AWS',
                            chargebackUnit  : {
                                id : '1',
                                name : 'Head of Product Development',
                                user : {
                                    id         : '1',
                                    first_name : 'Matt',
                                    last_name  : 'Deady',
                                    email      : 'mwd410@comcast.net'
                                }
                            }
                        }
                    }
                }
            }
        };

        $scope.isItemActive = function(item) {

            return item.isActive;
        };
    });

})(window.angular);
