(function(ng, undefined) {
    'use strict';

    ng.module('app').factory('toggle', function() {

        function Toggle(isActive) {

            this.isActive = !!isActive;
        }

        Toggle.prototype = {
            on : function() {

                this.isActive = true;
                return this;
            },
            off : function() {

                this.isActive = false;
                return this;
            },
            toggle : function() {

                this.isActive = !this.isActive;
            }
        };

        return function(isActive) {

            return new Toggle(isActive);
        };
    });

})(window.angular);