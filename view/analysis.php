<?php

extendView('body');

beginPartial('content');
?>
    <!-- BEGIN: Analysis Secondary Navbar -->
    <div class="navbar navbar-inverse second-bar">
        <div class="navbar-inner inner2">
            <div class="container">
                <a class="brand" href="#"></a>

                <div class="menu2" data-ng-controller="AnalysisPageController">
                    <ul class="nav nav-secondary">
                        <li data-ng-repeat="page in pages"
                            data-ng-class="{active : path == page.path}">
                            <a href="{{page.path}}">{{page.title}}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Analysis Secondary Navbar -->

<?php
insertPartial('analysisContent');
endPartial();