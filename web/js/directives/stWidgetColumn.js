(function(ng) {
    'use strict';

    ng.module('App')
        .directive('stWidgetColumn', function() {

            return {
                require     : ['stWidgetColumn', '^stWidgetRow'],
                controller  : function($scope) {

                    var totalFlex;

                    this.init = function(column) {

                        totalFlex = Utils.pluck(column.widgets, 'flex')
                            .reduce(function(prev, curr) {

                                return parseInt(prev, 10) + parseInt(curr, 10);
                            });
                        Utils.each(column.widgets, function(widget) {

                            widget.height = 100 * widget.flex / totalFlex;
                        }, this);
                    };
                },
                scope       : {
                    widgetColumn : '=stWidgetColumn',
                    index        : '='
                },
                link        : function(scope, el, attrs, controllers) {

                    var columnController = controllers[0],
                        rowController = controllers[1];

                    columnController.init(scope.widgetColumn);
                }
            };
        });

})(window.angular);