(function( ng, undefined ) {
    'use strict';

    ng.module( 'app.chargeback' ).controller( 'StakeholderCtrl', function( $scope, chargeback, modalDialog ) {

        $scope.assign = function( unit ) {
            chargeback.assign( unit, $scope.stakeholder );
        };

        $scope.unassign = function( unit ) {
            chargeback.unassign( unit, $scope.stakeholder );
        };

        $scope.showAssigned = function() {

            return chargeback.showAssigned;
        };

        $scope.toggleShowAssigned = function() {

            chargeback.showAssigned = !chargeback.showAssigned;
        };

        $scope.unitCount = function() {
            return Object.keys( $scope.stakeholder.units ).length;
        };

        $scope.isExpanded = function() {
            return !!$scope.stakeholder.isExpanded;
        };

        $scope.toggle = function() {
            $scope.stakeholder.isExpanded = !$scope.stakeholder.isExpanded;
        };

        $scope.edit = function(field) {
            chargeback.editStakeholder($scope.stakeholder, field);
        };

        $scope.remove = function() {

            modalDialog({
                title : 'Delete Stakeholder?',
                message : 'Are you sure you want to delete ' + $scope.stakeholder.name + '?',
                buttons : [
                    {
                        text : 'Yes',
                        iconCls : 'icon-ok'
                    },
                    {
                        text : 'No',
                        iconCls : 'icon-remove'
                    }
                ]
            }).then(function(selection) {
                    console.log(selection);
                    if (selection === 'Yes') {
                        chargeback.removeStakeholder($scope.stakeholder);
                    }
                });
        };
    } );

})( window.angular );
