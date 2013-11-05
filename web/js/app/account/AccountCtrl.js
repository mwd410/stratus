(function(ng) {
    'use strict';

    ng.module('app.account').controller('AccountCtrl', function($scope, account) {

        var original,
            currentState,
            commitActions = {
                CLEAN  : false,
                EDIT   : 'save',
                DELETE : 'remove',
                SAVE   : false
            };

        function saveOriginal(account) {
            original = ng.copy(account);
            $scope.account = account;
        }

        saveOriginal($scope.account);

        $scope.setState = function(state) {

            if (!commitActions.hasOwnProperty(state)) {
                throw new Error('Invalid state "' + state + '" was assigned.');
            }

            currentState = state;
        };

        $scope.setState('CLEAN');

        $scope.is = function(state, s_) {

            var states = Array.prototype.slice.call(arguments);

            return -1 !== states.indexOf(currentState);
        };

        $scope.commit = function() {

            if ($scope.is('EDIT')) {

                $scope.setState('SAVE');
                account.save($scope.account).then(
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
                account.remove($scope.account).then(
                    // Success
                    function(data) {

                    },
                    //
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

            if ($scope.is('EDIT', 'DELETE')) {

                $scope.reset();
                $scope.setState('CLEAN');
            }
        };
    });

})(window.angular);
