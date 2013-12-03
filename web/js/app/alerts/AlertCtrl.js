(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').controller('AlertCtrl', function($scope, alertApi) {

        $scope.isExpanded = function() {

            return $scope.isEditing();
        };

        //-----------------------------//
        // Editing Functions

        $scope.edit = function() {

            $scope.editing = true;
        };

        $scope.cancelEdit = function() {

            var original = alertApi.getOriginal($scope.alert);

            ng.copy(original, $scope.alert);
            $scope.editing = false;
        };

        $scope.isEditing = function() {

            return $scope.editing === true;
        };

        $scope.submit = function() {

            alertApi.submit($scope.alert);
        };

        //-----------------------------//
        // Deleting Functions

        $scope.askToDelete = function() {

            $scope.confirmDelete = true;
        };

        $scope.cancelDelete = function() {

            $scope.confirmDelete = false;
        };

        $scope.isDeleting = function() {

            return $scope.confirmDelete === true;
        };

        $scope.displayInTypes = [
            {
                value : 'overview',
                name : 'overview'
            },
            {
                value : 'breakdown',
                name : 'breakdown'
            }
        ];

        $scope.alert.displayIn = {};

        $scope.pivotChanged = function() {

            $scope.alert.service_provider_id = null;
            $scope.alert.service_provider_product_id = null;
            $scope.alert.service_type_id = null;
            $scope.alert.service_type_category_id = null;
        };

        $scope.getProviders = function() {

            return [
                {
                    id : null,
                    name : 'All Providers'
                }
            ].concat(alertApi.getProviders());
        };

        $scope.getProducts = function() {

            return [
                {
                    id : null,
                    name : 'All Providers',
                    subId : null,
                    subName : 'All Products'
                }
            ].concat(alertApi.getProducts($scope.alert));
        };

        $scope.getServiceTypes = function() {

            return [
                {
                    id : null,
                    name : 'All Types'
                }
            ].concat(alertApi.getServiceTypes());
        };

        $scope.getServiceCategories = function() {

            return [
                {
                    id : null,
                    name : 'All Types',
                    subId : null,
                    subName : 'All Categories'
                }
            ].concat(alertApi.getServiceCategories($scope.alert));
        };
    });

})(window.angular);
