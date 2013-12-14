(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').controller('ChargebackNavCtrl', function($scope) {

        $scope.chargeback = {
            menus : [
                {
                    name : 'Pivot By',
                    items : [
                        {
                            type : 'provider',
                            name : 'Service Provider'
                        },
                        {
                            type : 'type',
                            name : 'Service Type'
                        }
                    ]
                }
            ]
        };
    });

})(window.angular);
