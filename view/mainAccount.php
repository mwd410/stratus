<?php
extendView('body');

beginPartial('content');

?>
<div data-ng-controller="AccountManagementController">

    <h2>Account Management</h2>

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

        <div class="account animate-fade"
             data-ng-repeat="account in accounts"
             data-ng-controller="AccountController">

            <!-- Master Account Indicator-->
            <div class="icon-accept"
                 title="This is your master account."
                 data-ng-show="masterAccount.account_id == account.id"></div>
            <div class="icon-space"
                 data-ng-show="masterAccount.account_id != account.id"></div>

            <!-- Account value columns -->
            <div class="accountId">{{account.id || '&nbsp;'}}</div>
            <div class="accountName">{{account.name || '&nbsp;'}}</div>
            <div class="accountAws">{{account.aws_key || '&nbsp;'}}</div>

            <!-- BEGIN: Collapsed Icons-->
            <div class="accountIcons" data-ng-show="!isModifying()">
                <a href="#" data-ng-click="edit()">
                    <div class="icon-edit"></div>
                    Edit
                </a>
                <div class="icon-space"></div>
                <a href="#" data-ng-click="delete()">
                    <div class="icon-delete"></div>
                    Delete
                </a>
            </div>
            <!-- END: Collapsed Icons-->

            <!-- BEGIN: Expanded Icons -->
            <div class="accountIcons" data-ng-show="isEditing() || isAdding()">
                <a href="#" data-ng-click="commit()">
                    <div class="icon-save"></div>
                    Save
                </a>
                <div class="icon-space"></div>
                <a href="#" data-ng-click="cancel()">
                    <div class="icon-cancel"></div>
                    Cancel
                </a>
            </div>
            <!-- END: Expanded Icons -->

            <!-- BEGIN: Confirm Delete -->
            <div class="accountBody confirmDelete"
                 data-ng-show="isDeleting()">
                Are you sure you wish to delete {{account.name}}? This
                cannot be undone.

                <a href="#" class="button" data-ng-click="commit()">
                    <div class="icon-exclaim"></div>
                    Delete Account
                </a>

                <div class="icon-space"></div>
                <a href="#" class="button" data-ng-click="cancel()">
                    <div class="icon-cancel"></div>
                    Cancel
                </a>
            </div>
            <!-- END: Confirm Delete -->

            <!-- BEGIN: Edit Account -->
            <div class="accountBody expandDown"
                 data-ng-show="isEditing() || isAdding()">

                <h4>Edit Account</h4>

                <form name="accountForm" action="/account/update" method="POST">
                    <fieldset>

                        <label for="editAccountId_{{account.id}}">Account ID</label>
                        <input name="id"
                               data-ng-model="account.id"
                               id="editAccountId_{{account.id}}"
                               type="text"
                               required
                               data-ng-disabled="!isAdding()">

                        <span class="formError"
                              data-ng-show="accountForm.id.$error.required">
                            You must specify an AWS Account ID.
                        </span>
                        <br>

                        <label for="editAccountName_{{account.id}}">Name</label>
                        <input name="name"
                               required
                               data-ng-model="account.name"
                               maxlength="50"
                               id="editAccountName_{{account.id}}"
                               type="text">

                        <span class="formError"
                              data-ng-show="accountForm.name.$error.required">
                            You must specify an account name.
                        </span>
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
                            <label for="billingBucket_{{account.id}}">Billing Bucket</label>
                            <input id="billingBucket_{{account.id}}"
                                   data-ng-model="masterAccount.billing_bucket"
                                   type="text">
                        </div>
                    </fieldset>
                </form>
            </div>
            <!-- END: Edit Account -->

        </div>
    </div>

</div>

<?php


endPartial();