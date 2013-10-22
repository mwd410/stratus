(function(ng) {
    'use strict';

    ng.module('app.dashboard').service('widget', function(breakdown) {

        return {
            getData : function(type) {

                switch(type) {
                    case 'eomProjection':
                        return breakdown.projection;
                }
            }
        };
    });

})(window.angular);