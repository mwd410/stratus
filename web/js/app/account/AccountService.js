(function(ng) {
    'use strict';

    var CLOSED = 0,
        NEW = 1,
        EDIT = 2,
        DELETE = 3,
        SAVE = 4;

    var Account = function(data) {

        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                this[key] = data[key];
            }
        }

        this._state = CLOSED;
    };

    Account.prototype = {
        edit        : function() {

            this._state = EDIT;
        },
        isSaving    : function() {

            return this._state === SAVE;
        },
        isModifying : function() {

            return [EDIT, NEW, DELETE].indexOf(this._state) !== -1;
        },
        isEditing   : function() {

            return this._state === EDIT;
        },
        cancel      : function() {

            this._state = CLOSED;
        },
        submit      : function() {

            this._state = SAVE;
        }
    };

    ng.module('app.account').service('AccountService', function($http) {

        var serverData,
            master,
            allAccounts = [];

        $http.get('/getAccounts').success(function(response) {

            serverData = response;

            var copy = ng.copy(serverData);
            master = copy.masterAccount;
            allAccounts = copy.accounts;
        });

        return {
            all : allAccounts
        };
    });

})(window.angular);