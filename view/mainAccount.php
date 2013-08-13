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
            <div class="accountName">Account Name</div>
            <div class="accountAws">AWS Key</div>
            <div class="accountSecret">Secret Key</div>
            <div class="accountIcons"></div>
        </div>
        <div class="account"
             ng-animate="'fade'"
             data-ng-repeat="account in accounts">
            <div class="accountName">{{account.name}}</div>
            <div class="accountAws">{{account.aws_key}}</div>
            <div class="accountSecret">{{account.secret_key | obstructed}}</div>
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
            <div class="accountIcons" data-ng-show="isModifying(account)">
                <a href="#" data-ng-click="commit(account)">
                    <div class="icon-accept"></div>
                    OK
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
            </div>

            <div class="accountBody"
                 ng-show="isEditing(account) || isAdding(account)"
                 ng-animate=" 'expandDown' ">

                <h4>Edit Account</h4>

                <form action="/account/update" method="POST">
                    <fieldset>
                        <label for="editAccountName_{{account.id}}">Name</label>
                        <input name="name"
                               data-ng-model="account.name"
                               id="editAccountName_{{account.id}}"
                               type="text">
                        <br>
                        <label for="editAccountAws_{{account.id}}">AWS Key</label>
                        <input name="aws_key"
                               data-ng-model="account.aws_key"
                               id="editAccountAws_{{account.id}}"
                               type="text">
                        <br>
                        <label for="editAccountSecret_{{account.id}}">Secret Key</label>
                        <input name="secret_key"
                               data-ng-model="account.secret_key"
                               id="editAccountSecret_{{account.id}}"
                               type="password"
                               data-ng-change="validateSecretKey(account.secretKey)">
                        <br>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

</div>

<?php


endPartial();