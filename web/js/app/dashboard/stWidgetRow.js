(function(ng) {
    'use strict';

    ng.module('app.dashboard')
        .directive('stWidgetRow', function(Utils) {

            return {
                scope       : {
                    widgetRow : '=stWidgetRow'
                },
                controller  : function($scope) {

                },
                link        : function(scope, el, attrs, controllers) {

                    var totalFlex = Utils.pluck(scope.widgetRow.widgetColumns, 'flex')
                        .reduce(function(prev, curr) {

                            return parseInt(prev, 10) + parseInt(curr, 10);
                        });
                    Utils.each(scope.widgetRow.widgetColumns, function(column) {

                        column.width = 100 * column.flex / totalFlex;

                    }, this);
                }
            };
        });

})(window.angular);