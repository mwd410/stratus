(function(ng, undefined) {
    'use strict';

    ng.module('app.account').factory('accountApi', function($http, $q, Utils, account) {

        function create(data) {

            return account(data);
        }

        function load(data) {

            return create(data).commit();
        }

        var factory = {
                add       : function() {

                    factory.all.then(function(accounts) {

                        accounts.unshift(create({}));
                    });
                },
                save      : function(account, master) {

                    return $http.post('/account/edit', {
                        account : account,
                        master  : master
                    }).then(
                        //Success
                        function(response) {

                            return response.data;
                        });
                },
                saveNew   : function(account, master) {

                    return $http.post('/account/add', {
                        account : account,
                        master  : master
                    }).then(
                        function(response) {

                            return response.data;
                        });
                },
                remove    : function(account) {

                    return $http.post('account/delete', account)
                        .then(
                        function(response) {

                            return response.data;
                        });
                },
                removeNew : function(account) {

                    factory.all.then(function(accounts) {

                        accounts.splice(accounts.indexOf(account), 1);
                    });
                }
            },
            accountMap;

        var accountsDefer = $q.defer(),
            masterDefer = $q.defer();

        $http.get('/getAccounts')
            .then(
            function(response) {

                var accounts = response.data.accounts.map(load);

                accountMap = Utils.mapObjects(accounts, 'id');

                accountsDefer.resolve(accounts);

                masterDefer.resolve(response.master);
            });

        factory.all = accountsDefer.promise;
        factory.master = masterDefer.promise;

        return factory;
    });

})(window.angular);
