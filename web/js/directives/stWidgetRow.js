(function(ng) {
    'use strict';

    ng.module('App')
        .directive('stWidgetRow', function() {

            return {
                require     : ['stWidgetRow', '^stDash'],
                scope       : {
                    widgetRow : '=stWidgetRow'
                },
                controller  : function($scope) {

                    var totalFlex;

                    this.init = function(row) {

                        totalFlex = Utils.pluck(row.widgetColumns, 'flex')
                            .reduce(function(prev, curr) {

                                return parseInt(prev, 10) + parseInt(curr, 10);
                            });
                        Utils.each(row.widgetColumns, function(column) {

                            column.width = 100 * column.flex / totalFlex;

                        }, this);
                    };
                },
                link        : function(scope, el, attrs, controllers) {

                    var rowController = controllers[0],
                        dashController = controllers[1];

                    rowController.init(scope.widgetRow);
                }
            };
        });

})(window.angular);