(function(ng, undefined) {

    ng.module('app.chargeback' ).filter('chargebackAccounts', function(_) {
        return function( accounts, selected ) {
            if (selected.id === null) {
                return accounts;
            }

            var filteredAccounts = [];

            _.forEach( accounts, function( account ) {

                if (selected.id == account.service_provider_id) {
                    filteredAccounts.push(account);
                }
            });

            return filteredAccounts;
        };
    })

})(window.angular);
