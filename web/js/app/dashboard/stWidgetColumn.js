(function(ng) {
    'use strict';

    ng.module('app.dashboard')
        .directive('stWidgetColumn', function(Utils) {

            return {
                controller  : function($scope) {

                },
                scope       : {
                    widgetColumn : '=stWidgetColumn'
                },
                link        : function(scope, el, attrs, controllers) {

                    if (scope.widgetColumn.widgets) {

                        var totalFlex = Utils.pluck(scope.widgetColumn.widgets, 'flex')
                            .reduce(function(prev, curr) {

                                return parseInt(prev, 10) + parseInt(curr, 10);
                            });
                        Utils.each(scope.widgetColumn.widgets, function(widget) {

                            widget.height = 100 * widget.flex / totalFlex;
                        }, this);
                    }
                }
            };
        });

})(window.angular);