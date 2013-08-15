<?php
extendView('body');

beginPartial('content');

?>
<div data-ng-controller="AccountController">

    <a href="#" class="button blockRight" data-ng-click="add()">
        <div class="icon-add"></div>
        Add
    </a>

    <div class="accountList">

        <div class="accountHeader">

            <div class="icon-space"></div>
            <div class="icon-space"></div>
            <div class="accountId">Account ID</div>
            <div class="accountName">Account Name</div>
            <div class="accountAws">AWS Key</div>
            <div class="accountIcons"></div>

        </div>

        <div class="account"
             data-ng-animate="'fade'"
             data-ng-repeat="account in accounts">
            <!-- Master Account Indicator -->
            <div class="icon-accept"
                 title="This is your master account."
                 data-ng-show="masterAccount.account_id == account.id"></div>
            <div class="icon-space"
                 data-ng-show="masterAccount.account_id != account.id"></div>

            <!-- Account value columns -->
            <div class="accountId">{{account.id}}</div>
            <div class="accountName">{{account.name}}</div>
            <div class="accountAws">{{account.aws_key}}</div>

            <div class="accountIcons" data-ng-show="!isModifying(account)">
                <a href="#" data-ng-click="edit(account)">
                    <div class="icon-edit"></div>
                    Edit
                </a>
                <div class="icon-space"></div>
                <a href="#" data-ng-click="delete(account)">
                    <div class="icon-delete"></div>
                    Delete
                </a>
            </div>
            <div class="accountIcons" data-ng-show="isEditing(account) || isAdding(account)">
                <a href="#" data-ng-click="commit(account)">
                    <div class="icon-save"></div>
                    Save
                </a>
                <div class="icon-space"></div>
                <a href="#" data-ng-click="cancel(account)">
                    <div class="icon-cancel"></div>
                    Cancel
                </a>
            </div>

            <div class="accountBody confirmDelete"
                 data-ng-show="isDeleting(account)">
                Are you sure you wish to delete {{account.name}}? This
                cannot be undone.

                <a href="#" class="button" data-ng-click="commit(account)">
                    <div class="icon-exclaim"></div>
                    Delete Account
                </a>

                <div class="icon-space"></div>
                <a href="#" class="button" data-ng-click="cancel(account)">
                    <div class="icon-cancel"></div>
                    Cancel
                </a>
            </div>

            <div class="accountBody"
                 data-ng-show="isEditing(account) || isAdding(account)"
                 data-ng-animate=" 'expandDown' ">

                <h4>Edit Account</h4>

                <form name="accountForm" action="/account/update" method="POST">
                    <fieldset>
                        <label for="editAccountId_{{account.id}}">Account ID</label>
                        <input name="id"
                               data-ng-model="account.id"
                               id="editAccountId_{{account.id}}"
                               type="text"
                               data-ng-disabled="!isAdding(account)">
                        <br>
                        <label for="editAccountName_{{account.id}}">Name</label>
                        <input name="name"
                               required
                               data-ng-model="account.name"
                               maxlength="50"
                               id="editAccountName_{{account.id}}"
                               type="text">
                        <br>
                        <label for="editAccountAws_{{account.id}}">AWS Key</label>
                        <input name="aws_key"
                               data-ng-model="account.aws_key"
                               id="editAccountAws_{{account.id}}"
                               pattern="{{'.{' + fv.aws_key.length + '}'}}"
                               required
                               data-ng-minlength="{{fv.aws_key.length}}"
                               maxlength="{{fv.aws_key.length}}"
                               type="text">

                        <span class="formError"
                              data-ng-show="accountForm.aws_key.$error.minlength || accountForm.aws_key.$error.required">
                            The AWS Key must be {{fv.aws_key.length}} characters long.
                        </span>
                        <br>
                        <label for="editAccountSecret_{{account.id}}">Secret Key</label>
                        <input name="secret_key"
                               data-ng-model="account.secret_key"
                               id="editAccountSecret_{{account.id}}"
                               type="password"
                               pattern="{{'.{' + fv.secret_key.length + '}'}}"
                               required
                               data-ng-minlength="{{fv.secret_key.length}}"
                               maxlength="{{fv.secret_key.length}}"
                               type="password">

                        <span class="formError"
                              data-ng-show="accountForm.secret_key.$error.minlength">
                            The Secret Key must be {{fv.secret_key.length}} characters long.
                        </span>
                        <br>

                        <label for="masterAccountCheck_{{account.id}}">Master Account</label>
                        <input
                            id="masterAccountCheck_{{account.id}}"
                            class="masterAccountCheckbox"
                            data-ng-model="isMaster"
                            data-ng-checked="masterAccount.account_id == account.id"
                            data-ng-click="masterAccount.account_id = isMaster ? account.id : null"
                            type="checkbox">

                        <br>
                        <div data-ng-show="masterAccount.account_id == account.id">
                            <label>Billing Bucket</label>
                            <input data-ng-model="masterAccount.billing_bucket"
                                   type="text">
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

</div>

<?php


endPartial();