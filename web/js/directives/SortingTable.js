'use strict';

app.directive('ddSortTable', function() {
    return {
        require : 'ddSortTable',
        controller : function($scope, $element) {

            this.sortBy = function(property) {
                $scope.sortBy(property);
            };
        },
        link : function(scope, element, attrs, controller) {

            element.find('th').each(function() {

                $(this).click(function() {

                    controller.sortBy($(this).attr('sort-property'));
                });
            });
        }
    };
});