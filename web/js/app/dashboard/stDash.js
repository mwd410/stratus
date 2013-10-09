(function(ng) {
    'use strict';

    ng.module('app.dashboard')
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