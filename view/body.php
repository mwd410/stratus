<?php

$sitename = "Stratus";
$siteurl = "http://webapp-dev.stratus-cloudservices.com/";

$keystring = "stratusstring";

extendView('base');

beginPartial('menu');

?>

    <header data-ng-controller="MenuController"
            class="st-navbar"
            role="banner">
        <div class="wrapper">
            <div class="st-navbar-header">
                <button data-ng-click="expandMenu('left')">
                    <i class="icon-expand"></i>
                </button>
                <a class="st-brand" href="/">
                    <img src="/bootstrap/img/white-logo.png" width="80"/>
                </a>
                <button data-ng-click="expandMenu('main')">
                    <i class="icon-ellipsis-vertical"></i>
                </button>
                <button class="for-config"
                        data-ng-click="expandMenu('config')">
                    <i class="icon-cog"></i>
                </button>
            </div>

            <div class="st-right-menu-nav"
                 data-ng-class="{'is-expanded' : expandedMenu == 'main'}">
                <nav>
                    <ul>
                        <li data-ng-repeat="option in menuOptions"
                            ng-class="{'is-active': option.url==currentPath}">

                            <a href="{{option.url}}">{{option.name}}</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <nav class="st-config-menu"
                 data-ng-class="{'is-expanded' : expandedMenu == 'config'}">
                <ul>
                    <li>
                        <a href="#">Profile</a>
                    </li>
                    <li>
                        <a href="#">Providers</a>
                    </li>
                    <li>
                        <a href="#">Logout</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
<?php
endPartial();

beginPartial('body');

insertPartial('menu');


insertPartial('content');

endPartial();