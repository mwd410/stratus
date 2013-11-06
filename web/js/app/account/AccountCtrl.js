(function(ng, undefined) {
    'use strict';

    ng.module('app.account').controller('AccountCtrl', function($scope, accountApi) {

        var original = ng.copy($scope.account),
            currentState,
            commitActions = {
                CLEAN  : false,
                EDIT   : 'save',
                DELETE : 'remove',
                SAVE   : false,
                NEW    : false
            };

        function saveOriginal(account) {
            original = ng.copy(account);
            $scope.account = account;
        }

        $scope.setState = function(state) {

            if (!commitActions.hasOwnProperty(state)) {
                throw new Error('Invalid state "' + state + '" was assigned.');
            }

            currentState = state;
        };

        if ($scope.account.id === 0) {
            $scope.setState('NEW');
        } else {
            $scope.setState('CLEAN');
        }

        $scope.is = function(state, s_) {

            var states = Array.prototype.slice.call(arguments);

            return -1 !== states.indexOf(currentState);
        };

        $scope.commit = function() {

            if ($scope.is('EDIT')) {

                $scope.setState('SAVE');
                accountApi.save($scope.account, $scope.master).then(
                    // Success
                    function(data) {

                        saveOriginal(data.account);
                        $scope.saveMaster(data.master);

                        $scope.setState('CLEAN');
                    },
                    // Error
                    function() {


                    });

            } else if ($scope.is('DELETE')) {

                $scope.setState('SAVE');
                accountApi.remove($scope.account).then(
                    // Success
                    function(data) {

                        $scope.remove($scope.account);
                    },
                    //
                    function(data) {

                    }
                );

            } else if ($scope.is('NEW')) {

                $scope.setState('SAVE');
                accountApi.saveNew($scope.account, $scope.master).then(
                    function(data) {


                    }
                );

            } else {

                $scope.setState('CLEAN');
                throw new Error('Invalid current state "' + currentState + '" for commit.');
            }
        };

        $scope.reset = function() {

            ng.copy(original, $scope.account);
            $scope.resetMaster();
        };

        $scope.cancel = function() {

            if ($scope.is('EDIT', 'DELETE', 'NEW')) {

                $scope.reset();
                $scope.setState('CLEAN');
            }
        };
    });

})(window.angular);
