<?php
extendView('body');

beginPartial('content');

?>

<div class="container"
     data-ng-controller="RegisterController">
    <div class="row">
        <div class="span8 offset4">

            <form action="/register"
                  class=""
                  name="registerForm"
                  method="POST">
                <h2>Register</h2>
                <fieldset>

                    <input type="email"
                           name="email"
                           data-ng-model="email"
                           <?php if (isset($email)) echo "data-ng-init=\"email = '$email'\""; ?>
                           required
                           data-ng-blur="validateRegistration()"
                           placeholder="Email Address">
                    <span class="formError"
                          data-ng-show="!emailAvailable || registerForm.email.$invalid">
                        <span data-ng-show="!emailAvailable">
                            This email is already registered.
                        </span>
                    </span>
                    <br>

                    <input type="text"
                           name="customer_name"
                           data-ng-model="customer_name"
                           <?php if (isset($customerName)) echo "data-ng-init=\"customer_name = '$customerName'\""; ?>
                           required
                           placeholder="Customer Name">
                    <span class="formError"
                          data-ng-show="registerForm.customer_name.$invalid">
                    </span>
                    <br>

                    <input type="password"
                           data-ng-model="password"
                           name="password"
                           data-ng-minlength="6"
                           required
                           placeholder="Password">
                    <div class="icon-info-sign"
                         title="Password must be 6 characters long."></div>
                    <br>

                    <input type="password"
                           data-ng-model="confirm"
                           name="confirm"
                           required
                           placeholder="Confirm Password">
                    <span class="formError"
                          data-ng-show="confirm != password ">
                        Passwords do not match
                    </span>
                    <br>

                    <button type="submit"
                            data-ng-disabled="registerForm.$invalid"
                            class="btn btn-primary">
                        Submit
                    </button>

                </fieldset>
            </form>
            <span class="error">
                <?php if (isset($message)) echo $message; ?>
            </span>
        </div>
    </div>
</div>

<?php

endPartial();