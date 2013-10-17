(function(ng) {
    'use strict';

    ng.module('app').controller('LeftNavController',
        function($scope, NavService, BreakdownMenuService) {

            $scope.menu = NavService;

            $scope.menus = [
                {
                    name  : 'Breakdown By',
                    items : [
                        {
                            name : 'Service Provider',
                            type : 'provider'
                        },
                        {
                            name : 'Service Type',
                            type : 'type'
                        }
                    ]
                }
            ];

            function setType(item) {

                BreakdownMenuService.getAll(item.type).then(function(result) {

                    $scope.menus[1] = {
                        name  : item.name,
                        items : result
                    };
                });
            }

            setType($scope.menus[0].items[0]);

            $scope.itemClick = function(item) {

                if (item.type) {
                    setType(item);
                }
            };
        });

})(window.angular);