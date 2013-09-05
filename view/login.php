<?php

$sitename = "Stratus";
$siteurl = "http://webapp-dev.stratus-cloudservices.com/";

$keystring = "stratusstring";

$tbl_customer = "customer";
$tbl_users = "users";
$tbl_account = "account";

extendView('body');

beginPartial('content');?>

<div class="container">

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-sm-offset-3">
            <h2 class="form-signin-heading">Login</h2>

            <form role="form" method="POST" action="/login">
                <div class="form-group">
                    <input type="text"
                           name="email"
                           class="form-control"
                           placeholder="Email Address">
                </div>
                <div class="form-group">
                    <input type="password" name="password"
                           class="form-control"
                           placeholder="Password">
                </div>
                <button class="btn btn-primary" type="submit">Sign in</button>
            </form>
        </div>
    </div>

</div>


<?php endPartial();