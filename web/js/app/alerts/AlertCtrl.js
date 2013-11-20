(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').controller('AlertCtrl', function($scope) {

        $scope.serviceLabel = function() {

            if ($scope.alert.alert_classification_type_id == 1) {
                return 'Service Provider';
            } else if ($scope.alert.alert_classification_type_id == 2) {
                return 'Service Type';
            } else {
                return '';
            }
        };

        $scope.getClassificationId = function() {

            return $scope.alert.alert_classification_type_id;
        };

        $scope.getClassification = function() {

            var classId = $scope.getClassificationId(),
                classification;

            if (!$scope.classifications) {
                return null;
            }

            for (var i = 0; i < $scope.classifications.length; ++i) {
                classification = $scope.classifications[i];

                if (classification.id == classId) {
                    return classification;
                }
            }

            return null;
        };

        $scope.getTypes = function() {

            var classification = $scope.getClassification();

            return classification && classification.types || null;
        };

        $scope.getTypeId = function() {

            var classId = $scope.getClassificationId();

            return classId == 1 && $scope.alert.service_provider_id
                || classId == 2 && $scope.alert.service_product_id
                || null;
        };

        $scope.getType = function() {

            var types = $scope.getTypes(),
                typeId = $scope.getTypeId();

            if (types && typeId) {
                for (var i = 0; i < types.length; ++i) {
                    if (types[i].id == typeId) {
                        return types[i];
                    }
                }
            }

            return null;
        };

        $scope.getSubTypes = function() {

            var type = $scope.getType();

            return type && type.sub_types || null;
        };

        $scope.getSubTypeId = function() {

            var classId = $csope.getClassificationId();

            return classId == 1 && $scope.alert.service_provider_product_id
                || classId == 2 && $scope.alert.service_type_category_id
                || null;
        };

        $scope.getSubType = function() {

            var subTypes = $scope.getSubTypes(),
                subTypeId = $scope.getSubTypeId();

            if (subTypes && subTypeId) {
                for (var i = 0; i < subTypes.length; ++i) {
                    if (subTypes[i].id == subTypeId) {
                        return subTypes[i];
                    }
                }
            }

            return null;
        };
    });

})(window.angular);
