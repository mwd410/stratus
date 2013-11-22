(function(ng, undefined) {
    'use strict';

    ng.module('app.account').factory('account', function() {

        var relevantProps = [
            'id',
            'external_id',
            'name',
            'aws_key',
            'secret_key'
        ];

        function Account(data) {

            ng.copy(data, this);
            this._original = data;
        }

        Account.prototype = {
            commit : function() {

                relevantProps.forEach(function(prop) {

                    this._original[prop] = this[prop];
                }, this);

                return this;
            }
        };

        return function(data) {

            return new Account(data);
        };
    });

})(window.angular);
