(function(ng, undefined) {
    'use strict';

    ng.module('app').directive('stCheckbox', function() {

        return {
            restrict   : 'E',
            replace    : true,
            transclude : true,
            template   : function(tElement, tAttrs) {

                return '<div class="st-checkbox">' +
                    (tAttrs.after == '' ? '<label ng-transclude></label>' : '') +
                    '<i class="icon-large" data-ng-class="{true : \'icon-check\', false : \'icon-unchecked\'}[!!'+tAttrs.ngModel+']"></i>' +
                    (tAttrs.after != '' ? '<label ng-transclude></label>' : '') +
                    '</div>';
            },
            link       : function(scope, el, attrs, ctrl) {

                var isDisabled = !!scope[attrs.ngModel];

                el.on('click', function() {

                    if (isDisabled) {
                        return;
                    }
                    scope.$apply(attrs.ngModel + ' = !' + attrs.ngModel);
                });

                if (attrs.isDisabled) {

                    scope.$watch(attrs.isDisabled, function(disabled) {

                        isDisabled = !!disabled;
                        if (attrs.uncheckOnDisable !== 'off') {
                            scope.$evalAsync(attrs.ngModel + ' = false');
                        }
                    });
                }

                scope.$watch(attrs.ngModel, function(checked) {

                    scope.$eval(attrs[checked ? 'onCheck' : 'onUncheck']);
                });
            }
        };
    });

})(window.angular);
