(function(ng, undefined) {
    'use strict';

    ng.module('app').directive('stSwitch', function(toggle) {

        return {
            template : '<div class="st-switch"><div></div></div>',
            replace : true,
            link : function(scope, el, attrs, ctrl) {

                var modelName = attrs.stSwitch,
                    toggler = toggle();

                scope[modelName] = toggler;
                
                el.on('click', function() {

                    toggler.toggle();
                    el.toggleClass('is-on');
                });
            }
        };
    });

})(window.angular);
