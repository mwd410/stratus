<?php

$sitename = "Stratus";
$siteurl = "http://webapp-dev.stratus-cloudservices.com/";

$keystring = "stratusstring";

extendView('base');

beginPartial('menu');

?>

    <div data-ng-controller="MenuController" class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="brand" href="/">
                    <img src="/bootstrap/img/white-logo.png" width="80"/>
                </a>
                <div class="nav-collapse collapse menu1">
                    <ul class="nav">
                        <li data-ng-repeat="option in menuOptions"
                            ng-class="{true:'active',false:''}[option.url==currentPath]">
                            <a href="{{option.url}}">{{option.name}}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php
endPartial();

beginPartial('body');

insertPartial('menu');


insertPartial('content');

endPartial();