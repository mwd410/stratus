(function(ng) {
    'use strict';

    ng.module('app').directive('stAutoSize', function() {

        return {
            link : function(scope, el, attrs) {

                var bottomSpace = attrs.stAutoSize ? parseInt(attrs.stAutoSize) : 20;

                function applyHeight() {

                    var windowHeight = window.innerHeight,
                        height = windowHeight - el.offset().top - bottomSpace;

                    el.css('min-height', height + 'px');
                }

                ng.element(window).bind('resize', function() {

                    scope.$apply(function() {

                        applyHeight();
                    });
                });

                applyHeight();
            }
        };
    });

})(window.angular);
