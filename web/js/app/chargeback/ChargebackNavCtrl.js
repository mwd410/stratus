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
            ]
        };

        $scope.isItemActive = function(item) {

            return item.isActive;
        };
    });

})(window.angular);
