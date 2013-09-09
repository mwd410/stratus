(function(ng) {
    'use strict';

    ng.module('stDashboard', [])
        .directive('stDash', function() {

            return {
                require : ['stDash'],
                scope : {
                    dashConfig : '=stDash'
                },
                link : function(scope, attrs, el, controller) {


                }
            };
        });

})(window.angular);