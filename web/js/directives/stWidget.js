(function(ng) {
    'use strict';

    ng.module('App')
        .directive('stWidget', function() {

            return {
                templateUrl : '/js/directives/tpl/stWidget.html',
                replace     : true,
                require     : ['stWidget', '^stWidgetColumn'],
                scope       : {
                    widget : '=stWidget',
                    index  : '='
                },
                controller  : function($scope) {

                },
                link        : function(scope, el, attrs, controllers) {

                    var widgetController = controllers[0],
                        columnController = controllers[1];

                }
            };
        });

})(window.angular);