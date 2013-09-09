(function(ng) {
    'use strict';

    ng.module('stDashboard')
        .directive('stWidget', function() {

            return {
                require : ['stWidget', '^stDash'],
                scope : {
                    gridWidth : '=',
                    gridHeight : '=',
                    gridX : '=',
                    gridY : '='
                }
            };
        });

})(window.angular);