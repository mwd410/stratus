<?php

$sitename = "Stratus";
$siteurl = "http://webapp-dev.stratus-cloudservices.com/";

$keystring = "stratusstring";

$tbl_customer = "customer";
$tbl_users = "users";
$tbl_account = "account";

extendView('body');

beginPartial('content');?>


<form class="form-signin" method="POST" action="/login">
    <h2 class="form-signin-heading">Login</h2>
    <input type="text" name="username" class="input-block-level"
           placeholder="Username">
    <input type="password" name="password" class="input-block-level"
           placeholder="Password">
    <button class="btn btn-primary" type="submit">Sign in</button>
</form>


<?php endPartial();