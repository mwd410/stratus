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

                },
                replace : true,
                templateUrl : '/js/directives/tpl/stDash.html',
                link        : function(scope, el, attrs, controller) {

                }
            };
        });

})(window.angular);