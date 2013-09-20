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
                link        : function(scope, el, attrs, controller) {

                }
            };
        });

})(window.angular);