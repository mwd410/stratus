<?php
extendView('body');

beginPartial('content');

?>
    <div class="container accountContainer"
         data-ng-controller="AccountManagementController">


    <div class="row st-account-header">

        <div class="pull-left spacer">&nbsp;</div>

        <div class="col-xs-4 col-md-3 hidden-xs">Account ID</div>

        <div class="col-xs-4 col-md-3">Account Name</div>

        <div class="visible-md visible-lg col-md-3">AWS Key</div>

        <button class="btn st-btn btn-default btn-sm pull-right"
                data-ng-click="add()">
            <span class="glyphicon glyphicon-plus"></span>
            Add
        </button>
    </div>

    <div class="st-account animate-fade row"
         data-ng-class="{'is-expanded' : isModifying(), 'is-master' : masterAccount.account_id == account.id}"
         data-ng-repeat="account in accounts"
         data-ng-controller="AccountController">

        <!-- Master Account Indicator-->
        <div class="pull-left column">
            <span class="st-master-icon glyphicon glyphicon-chevron-right"></span>
        </div>

        <!-- Account value columns -->
        <div class="col-xs-4 col-md-3 hidden-xs column">
            {{account.id || '&nbsp;'}}
        </div>
        <div class="col-xs-4 col-md-3 column">
            {{account.name || '&nbsp;'}}
        </div>
        <div class="col-md-3 visible-md visible-lg column">
            {{account.aws_key || '&nbsp;'}}
        </div>

        <!-- Collapsed Icons -->
        <div class="pull-right"
             data-ng-show="!isModifying()">
            <button class="st-account-edit-btn btn btn-default btn-sm"
                    data-ng-click="edit()"
                    type="button">
                <span class="glyphicon glyphicon-pencil"></span>
            </button>
            <button class="st-account-delete-btn btn btn-default btn-sm"

                    type="button">
                <span class="glyphicon glyphicon-remove"></span>
            </button>
        </div>

        <!-- Expanded Icons -->
        <div class="pull-right"
             data-ng-show="isEditing() || isAdding()">
            <button class="btn btn-default btn-sm"
                    data-ng-click="commit()">
                <span class="glyphicon glyphicon-save"></span>
                Save
            </button>
            <button class="btn btn-default btn-sm"
                    data-ng-click="cancel()">
                <span class="glyphicon glyphicon-remove-circle"></span>
                Cancel
            </button>
        </div>

        <!-- BEGIN: Confirm Delete --
        <div class="st-account-body confirmDelete"
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

        <div class="clearfix"></div>
        <!-- BEGIN: Edit Account -->
        <div class="st-account-body">

            <form class="form-horizontal"
                  name="accountForm"
                  action="/account/update"
                  method="POST">

                <div class="form-group">
                    <label class="col-sm-2 col-sm-offset-2 control-label"
                           for="editAccountId_{{account.id}}">
                        Account ID
                    </label>
                    <div class="col-sm-5">

                        <input class="form-control"
                               name="id"
                               data-ng-model="account.id"
                               id="editAccountId_{{account.id}}"
                               type="text"
                               required
                               placeholder="Account ID"
                               data-ng-disabled="!isAdding()">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 col-sm-offset-2 control-label"
                           for="editAccountName_{{account.id}}">
                        Name
                    </label>
                    <div class="col-sm-5">
                        <input class="form-control"
                               name="name"
                               required
                               data-ng-model="account.name"
                               maxlength="50"
                               id="editAccountName_{{account.id}}"
                               type="text">
                    </div>
                </div>

                <div class="form-group">

                    <label class="col-sm-2 col-sm-offset-2 control-label"
                           for="editAccountAws_{{account.id}}">
                        AWS Key
                    </label>
                    <div class="col-sm-5">
                        <input class="form-control"
                               name="aws_key"
                               data-ng-model="account.aws_key"
                               id="editAccountAws_{{account.id}}"
                               pattern="{{'.{' + fv.aws_key.length + '}'}}"
                               required
                               data-ng-minlength="{{fv.aws_key.length}}"
                               maxlength="{{fv.aws_key.length}}"
                               type="text">
                    </div>

                </div>

                <div class="form-group"
                     data-ng-class="{'has-error' : accountForm.secret_key.$invalid}">

                    <label class="col-sm-2 col-sm-offset-2 control-label"
                           for="editAccountSecret_{{account.id}}">
                        Secret Key
                    </label>
                    <div class="col-sm-5">

                        <input class="form-control"
                               name="secret_key"
                               data-ng-model="account.secret_key"
                               id="editAccountSecret_{{account.id}}"
                               type="password"
                               pattern="{{'.{' + fv.secret_key.length + '}'}}"
                               required
                               data-ng-minlength="{{fv.secret_key.length}}"
                               maxlength="{{fv.secret_key.length}}"
                               type="password">
                    </div>

                </div>

                <div class="form-group">

                    <div class="col-sm-5 col-sm-offset-4">

                        <div class="checkbox">
                            <label>
                                <input id="masterAccountCheck_{{account.id}}"
                                       class="masterAccountCheckbox"
                                       data-ng-model="isMaster"
                                       data-ng-checked="masterAccount.account_id == account.id"
                                       data-ng-click="masterAccount.account_id = isMaster ? account.id : null"
                                       type="checkbox">
                                Master Account
                            </label>
                        </div>
                    </div>

                </div>

                <div class="form-group"
                     data-ng-show="masterAccount.account_id == account.id">

                    <label class="col-sm-2 col-sm-offset-2 control-label"
                           for="billingBucket_{{account.id}}">
                        Billing Bucket
                    </label>
                    <div class="col-sm-5">

                        <input class="form-control"
                               id="billingBucket_{{account.id}}"
                               data-ng-model="masterAccount.billing_bucket"
                               type="text">
                    </div>

                </div>
<!--
                    <span class="formError"
                          data-ng-show="accountForm.id.$error.required">
                        You must specify an AWS Account ID.
                    </span>
                    <br>


                    <span class="formError"
                          data-ng-show="accountForm.name.$error.required">
                        You must specify an account name.
                    </span>
                    <br>

                    <span class="formError"
                          data-ng-show="accountForm.aws_key.$error.minlength || accountForm.aws_key.$error.required">
                        The AWS Key must be {{fv.aws_key.length}} characters long.
                    </span>
                    <br>

                    <span class="formError"
                          data-ng-show="accountForm.secret_key.$error.minlength">
                        The Secret Key must be {{fv.secret_key.length}} characters long.
                    </span>
                    <br>


                    <br>

                    <div>
                    </div>-->
            </form>
        </div>
        <!-- END: Edit Account -->

    </div>

    </div>

<?php


endPartial();