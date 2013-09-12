(function(ng) {
    'use strict';

    ng.module('App')
        .directive('stDash', function() {

            return {
                require : 'stDash',
                scope       : {
                    dash : '=stDash'
                },
                controller  : function() {

                    function init(dash) {

                        Utils.each(dash.widgetRows, function(row) {

                            var totalFlex = Utils.pluck(row.widgetColumns, 'flex')
                                .reduce(function(prev, curr) {

                                    return parseInt(prev) + parseInt(curr);
                                });
                            Utils.each(row.widgetColumns, function(column) {

                                column.width = 100 * column.flex / totalFlex;

                                var totalWidgetFlex = Utils.pluck(column.widgets, 'flex')
                                    .reduce(function(prev, curr) {

                                        return parseInt(prev) + parseInt(curr);
                                    });
                                Utils.each(column.widgets, function(widget) {

                                    widget.height = 100 * widget.flex / totalWidgetFlex;
                                }, this);
                            }, this);

                        }, this);
                    }

                    this.init = init;
                },
                replace : true,
                templateUrl : '/js/directives/tpl/stDash.html',
                link        : function(scope, el, attrs, controller) {

                    controller.init(scope.dash);
                }
            };
        });

})(window.angular);