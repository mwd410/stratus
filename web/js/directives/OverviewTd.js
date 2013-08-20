'use strict';

app.directive('ddOverviewTd', function() {

    return {
        scope : {
            value : '@'
        },
        restrict : 'A',
        template :
            '<div data-ng-show="true"></div>{{value}}%',
        controller : function($scope, $element) {
            console.log('controller');
            var cls,
                iconCls;
            if ($scope.value > 0) {
                cls = 'gain';
                iconCls = 'icon-arrow-up';
            } else if ($scope.value < 0) {
                cls = 'loss';
                iconCls = 'icon-arrow-down';
            } else {
                cls = 'neutral';
                iconCls = false;
            }

            $element.addClass(cls);
            if (iconCls) {
                $element.find('> div:first-child').addClass(iconCls);
            }
        },
        link : function() {
            console.log('link');

        }
    };
});