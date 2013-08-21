'use strict';

app.directive('ddOverviewTd', function() {

    return {
        scope      : {
            value : '@ddOverviewTd'
        },
        restrict   : 'A',
        template   : '<div data-ng-show="true"></div>{{value}}%',
        controller : function($scope, $element) {
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
        link       : function(scope) {

            console.log(scope);
        }
    };
});

app.directive('ddOverviewTh', function() {

    return {
        restrict   : 'A',
        require    : 'ddOverviewTh',
        controller : function($scope) {

            this.sortBy = function(property) {
                $scope.sortBy(property);
            };
        },
        link       : function(scope, element, attrs, controller) {

            scope.$watch(attrs.ddOverviewTh);
        }
    };
});