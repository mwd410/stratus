'use strict';

app.controller('RegisterController', function($scope, $http) {

    $scope.emailAvailable = true;

    $scope.validateRegistration = function() {

        if ($scope.registerForm.email.$valid) {

            $http.post(
                '/isEmailAvailable',
                {email : $scope.email}
            ).success(
                function(result) {
                    $scope.emailAvailable = result.available;
                }
            );
        }
    };

});