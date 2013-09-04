<?php

$sitename = "Stratus";
$siteurl = "http://webapp-dev.stratus-cloudservices.com/";

$keystring = "stratusstring";

extendView('base');

beginPartial('menu');

?>

    <header data-ng-controller="MenuController"
            class="navbar navbar-inverse navbar-fixed-top st-navbar"
            role="banner">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle"
                        data-toggle="collapse"
                        data-target=".st-navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="brand" href="/">
                    <img src="/bootstrap/img/white-logo.png" width="80"/>
                </a>
            </div>

            <nav class="collapse navbar-collapse st-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li data-ng-repeat="option in menuOptions"
                        ng-class="{true:'active',false:''}[option.url==currentPath]">

                        <a href="{{option.url}}">{{option.name}}</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
<!--
    <nav data-ng-controller="MenuController"
         class="navbar navbar-default menu-bar" role="navigation">
        <div class="container">

            <div class="collapse navbar-collapse st-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li data-ng-repeat="option in menuOptions"
                        ng-class="{true:'active',false:''}[option.url==currentPath]">

                        <a href="{{option.url}}">{{option.name}}</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>-->
<?php
endPartial();

beginPartial('body');

insertPartial('menu');


insertPartial('content');

endPartial();