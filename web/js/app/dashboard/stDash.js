(function(ng) {
    'use strict';

    ng.module('app.dashboard')
        .directive('stDash', function() {

            return {
                require : 'stDash',
                scope       : {
                    dash : '=stDash',
                    widgetService : '='
                },
                controller  : function($scope, NavService) {

                    $scope.isLeftExpanded = function() {

                        return NavService.isExpanded('left');
                    };

                },
                link        : function(scope, el, attrs, ctrl) {

                    scope.getTitle = function() {
                        return scope.widgetService.title;
                    };
                }
            };
        });

})(window.angular);