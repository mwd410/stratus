(function(ng) {
    'use strict';

    ng.module('App').directive('stAccount', function() {

        var CLOSED = 0,
            NEW = 1,
            EDIT = 2,
            DELETE = 3,
            SAVE = 4;

        return {
            controller : function($scope, Provider) {

                var state = CLOSED;
                $scope.edit = function() {
                    state = EDIT;
                };

                $scope.isSaving = function() {

                    return state === SAVE;
                };

                $scope.isModifying = function() {

                    return state !== CLOSED;
                };

                $scope.isEditing = function() {

                    return state === EDIT;
                };

                $scope.cancel = function() {

                    state = CLOSED;

                    ng.copy($scope.originalAccount, $scope.account);
                    ng.copy($scope.originalMaster, $scope.master);
                };

                $scope.submit = function() {

                    state = SAVE;
                };

                $scope.setMaster = function(isMaster) {

                    if (isMaster) {
                        $scope.master.account_id = $scope.account.id;
                    } else {
                        $scope.master.account_id = null;
                    }
                };
            },
            scope : {
                account : '=stAccount',
                master  : '='
            },
            templateUrl : '/js/directives/tpl/stAccount.html',
            replace : true,
            link : function(scope, el, attrs, ctrl) {

                var readjustSize = function(isModifying) {

                    var body = el.find('.st-account-body'),
                        form = body.find('form'),
                        padding = form.parents('li').padding(),
                        ul = form.parents('ul'),
                        height = ul.outerHeight() - padding.top - padding.bottom,
                        scroll;

                    if (isModifying) {
                        form.css('height', height - 1);
                        body.css('max-height', height - 1);

                        ul.scrollTop(ul.scrollTop() + el.position().top);

                        var intervalId = setInterval(function() {
                            ul.scrollTop(ul.scrollTop() + el.position().top);
                        }, 10);

                        setTimeout(function() {
                            clearInterval(intervalId);
                        }, 300);
                    } else {
                        body.css('max-height', 0);
                        ul.css('overflow', 'auto');
                        ul.css('padding-bottom', '0');
                    }
                };

                scope.originalMaster = ng.copy(scope.master);
                scope.originalAccount = ng.copy(scope.account);

                scope.$watch('isModifying()', readjustSize);

                scope.ulHeight = function() {

                    return el.parents('ul').outerHeight();
                };
                scope.$watch('ulHeight()', function() {

                    readjustSize(scope.isModifying());
                });
            }
        };
    });

})(window.angular);