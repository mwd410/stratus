<?php
extendView('body');

beginPartial('content');

?>

<div class="st-left-nav">
    <ul>
        <li>
            <a class="active" href="#">Dashboard</a>
        </li>
        <li>
            <a href="#">Dashboard</a>
        </li>
        <li>
            <a href="#">Dashboard</a>
        </li>
        <li>
            <a href="#">Dashboard</a>
        </li>
    </ul>
</div>
    <!--

<div class="st-dash">
<div>

    <div class="st-widget-row" style="height:400px;">
        <div class="st-widget-column col-md-4 col-md-offset-3">
            <div class="st-widget" style="height:100%;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Widget</h4>
                    </div>
                    <div class="panel-body">
                    </div>
                </div>
            </div>
        </div>
        <div class="st-widget-column col-md-2">
            <div class="st-widget-column-dragger">
                <div>
                </div>
            </div>
            <div class="st-widget" style="height:50%;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Widget</h4>
                    </div>
                    <div class="panel-body">

                    </div>
                </div>
            </div>
            <div class="st-widget" style="height:50%;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title inline" style="display: inline-block;">Widget</h4>
                        <button type="button"
                                class="st-widget-btn btn btn-xs">
                            <span class="glyphicon-move glyphicon"></span>
                        </button>
                        <button type="button"
                                class="st-widget-btn btn btn-xs">
                            <span class="glyphicon-move glyphicon"></span>
                        </button>
                    </div>
                    <div class="panel-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
    -->
<div data-ng-controller="DashboardController">
    <div data-st-dash="dashboard"></div>
</div>
<?php

endPartial();