(function(window, ng, undefined) {
    'use strict';

    ng.module('app')
        .directive('stCombo', function() {

            return {
                restrict : 'E',
                scope : {
                    items : '=',
                    selected : '='
                },
                template : '<div class="st-combo">{{selected.name}}'+
                    '<ul>'+
                    '<li data-ng-repeat="item in items">'+
                    '{{item.name}}'+
                    '</li>'+
                    '</ul>'+
                    '</div>',
                link : function(scope, el, attrs, ctrl) {

                    if (!scope.selected ||
                        scope.items.indexOf(scope.selected) === -1) {

                        scope.selected = scope.items[0];
                    }

                }
            };
        });

})(window, window.angular);