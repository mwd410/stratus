(function(ng) {
    'use strict';

    ng.module('app.dashboard')
        .directive('stWidget', function() {

            return {
                require   : ['stWidget', '^stDash'],
                scope     : {
                    widget : '=stWidget',
                    widgetService : '='
                },
                controller : function($scope) {

                },
                link      : function(scope, el, attrs, controllers) {

                    scope.widgetService.registerWidget(scope.widget);

                    scope.$watch('widgetService.getData(widget)', function(data) {
                        if (data) {
                            scope.widget.tpl = '/partials/widget/' + scope.widget.templateFile;
                            scope.widget.data = data;
                        }
                    });

                    /*
                     el.find('.panel-heading').bind('mousedown', function(event) {

                     var wrapper = $(this).parent('.st-widget-wrapper');

                     wrapper.css({
                     position : 'absolute',
                     left     : 6,
                     top      : 6,
                     width    : el.width(),
                     height   : el.height(),
                     'z-index' : 1000
                     });

                     initX = event.pageX;
                     initY = event.pageY;

                     $(document).on('mousemove', function(event) {

                     wrapper.css({
                     left : 6 + (event.pageX - initX),
                     top  : 6 + (event.pageY - initY)
                     });

                     if (window.getSelection) {
                     if (window.getSelection().empty) {  // Chrome
                     window.getSelection().empty();
                     } else if (window.getSelection().removeAllRanges) {  // Firefox
                     window.getSelection().removeAllRanges();
                     }
                     } else if (document.selection) {  // IE?
                     document.selection.empty();
                     }
                     });

                     $(document).bind('mouseup', function() {

                     $(document).off('mousemove');
                     wrapper.css({
                     position : 'static'
                     });
                     });
                     });*/
                }
            };
        });

})(window.angular);