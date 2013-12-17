(function(ng) {
    'use strict';

    ng.module('app.dashboard')
        .directive('stWidget', function() {

            return {
                require    : ['stWidget', '^stDash'],
                controller : function($scope, $injector) {

                    function resize(el) {

                        var wrapper = el.find('.st-widget-wrapper'),
                            header = wrapper.find('> header'),
                            body = wrapper.find('> div'),
                            height = wrapper.height() - (header.outerHeight() || 0);

                        body.innerHeight(height);
                    }

                    this.inject = function(el) {

                        if ($scope.widget.tplService) {
                            $injector.invoke([
                                $scope.widget.tplService,
                                function(service) {

                                    resize(el);

                                    service.apply($scope.widget, el);
                                }
                            ]);
                        }
                    };
                },
                link       : function(scope, el, attrs, controllers) {

                    var ctrl = controllers[0],
                        stDash = controllers[1];

                    stDash.register(scope.widget);
                    //scope.widgetService.registerWidget(scope.widget);

                    scope.$watch('widgetService.getData(widget)', function(data) {
                        if (data) {
                            scope.widget.tpl = '/partials/widget/' + scope.widget.templateFile;
                            scope.widget.data = data;


                            setTimeout(function() {

                                ctrl.inject(el);

                            }, 100);
                        }
                    });

                    scope.title = function() {

                        if (typeof scope.widget.title === 'function') {
                            return scope.widget.title();
                        } else if (scope.widget.dynamicTitle !== false) {
                            return [scope.widgetService.lastTitle,
                                    scope.widget.title].join(' - ');
                        } else {
                            return scope.widget.title;
                        }
                    };

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
