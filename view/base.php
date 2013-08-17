<?php

$sitename = "Stratus";
$siteurl = "http://webapp-dev.stratus-cloudservices.com/";

$keystring = "stratusstring";

$tbl_customer = "customer";
$tbl_users = "users";
$tbl_account = "account";
?>

<!DOCTYPE html>
<html lang="en" data-ng-app="App">
<head>

    <!--
    <meta charset="utf-8">
    <title>Stratus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    //-->

</head>
<body>
    <?php
    insertPartial('body');
    includeStylesheets();
    includeJavaScripts();
    ?>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="bootstrap/js/html5shiv.js"></script>
    <![endif]-->
</body>
</html>