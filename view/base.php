<?php

$sitename = "Stratus";
$siteurl = "http://webapp-dev.stratus-cloudservices.com/";

$keystring = "stratusstring";

$tbl_customer = "customer";
$tbl_users = "users";
$tbl_account = "account";
?>
<!DOCTYPE html>
<html lang="en" data-ng-app="app">
<head>
    <title>Stratus</title>

    <meta charset="utf-8">
    <meta name="description" content="Stratus Cloudservices">
    <meta name="author" content="Stratus">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    if (App::getEnv() === 'dev'):
        ?>
        <script>
            var less = {env : "development"};
        </script>
    <?php endif; ?>
</head>
<body>
    <?php
    insertPartial('body');
    includeStylesheets();
    includeJavaScripts();
    ?>
</body>
</html>