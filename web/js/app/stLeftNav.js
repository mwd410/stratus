(function(ng, undefined) {
    'use strict';

    ng.module('app').directive('stLeftNav', function() {

        return {
            controller  : function($scope, NavService) {

                NavService.registerMenu('left');

                $scope.isExpanded = function() {

                    return NavService.isExpanded('left');
                };
            },
            templateUrl : '/js/app/stLeftNav.html',
            replace     : true,
            scope       : {
                menus           : '=stLeftNav',
                onItemClick     : '&',
                onSubItemClick  : '&',
                isItemActive    : '&',
                isSubItemActive : '&'
            },
            link : function(scope, el, attrs) {

                if (attrs.isItemActive && !attrs.isSubItemActive) {
                    scope.isSubItemActive = function(o) {
                        return scope.isItemActive({item : o.subItem});
                    };
                }

                if (attrs.onItemClick && !attrs.onSubItemClick) {
                    scope.onSubItemClick = function(o) {
                        scope.onItemClick({item : o.subItem});
                    };
                }

                var originalOffset;

                ng.element(window.document).bind('scroll', function() {

                    if (originalOffset === undefined) {
                        originalOffset = el.prop('offsetTop');
                    }

                    if (window.pageYOffset > originalOffset - 20) {
                        el.css('position', 'fixed');
                    } else {
                        el.css('position', '');
                    }
                });
            }
        };
    });

})(window.angular);