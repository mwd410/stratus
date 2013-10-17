(function(ng) {
    'use strict';

    ng.module('app').directive('stLeftNav', function() {

        return {
            controller  : function($scope) {

                $scope.activeItem = null;
                $scope.activeSubItem = null;

                $scope.itemClick = function(item) {

                    $scope.activeItem = item;
                    $scope.activeSubItem = null;

                    $scope.onItemClick({item : item});
                };

                $scope.subItemClick = function(subItem) {

                    $scope.activeSubItem = subItem;

                    $scope.onSubItemClick({subItem : subItem});
                };
            },
            templateUrl : '/js/app/stLeftNav.html',
            replace     : true,
            scope       : {
                onItemClick    : '&',
                onSubItemClick : '&',
                menus          : '=stLeftNav',
                activeItem     : '=?',
                activeSubItem  : '=?'
            },
            link        : function(scope, el, attrs, ctrl) {

            }
        };
    });

})(window.angular);