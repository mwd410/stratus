(function(ng, undefined) {
    'use strict';

    ng.module('app').directive('stFocus', function() {

        return {
            scope : true,
            link  : function(scope, el, attrs, ctrl) {

                var el = ng.element('#' + attrs.stFocus);

                el.on('')
            }
        };
    });

})(window.angular);
