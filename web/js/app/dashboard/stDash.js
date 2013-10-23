(function(ng) {
    'use strict';

    ng.module('app.dashboard')
        .directive('stDash', function() {

            return {
                require : 'stDash',
                scope       : {
                    dash : '=stDash',
                    registerWidget : '&'
                },
                controller  : function($scope, NavService, breakdown) {

                    $scope.isLeftExpanded = function() {

                        return NavService.isExpanded('left');
                    };

                    $scope.getTitle = function() {
                        return breakdown.title;
                    };

                    this.registerWidget = function(widget) {

                        $scope.registerWidget({widget : widget});
                    };
                },
                link        : function(scope, el, attrs, controller) {

                }
            };
        });

})(window.angular);