<?php
extendView('body');

beginPartial('content');

?>

    <div class="st-left-nav">
        <ul>
            <li class="st-nav-title">Breakdown By</li>
            <li>
                <a href="#" class="active">Service Provider</a>
            </li>
            <li>
                <a href="#">Service Type</a>
            </li>
        </ul>
        <ul>
            <li class="st-nav-title">Providers</li>
            <li>
                <a href="#">All</a>
            </li>
            <li class="active">
                <a href="#" class="active">Amazon</a>
                <ul>
                    <li>
                        <a href="#">S3</a>
                    </li>
                    <li>
                        <a href="#">EC Instances</a>
                    </li>
                    <li>
                        <a href="#">EC2 Volumes</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">Google</a>
            </li>
        </ul>
    </div>

    <div class="st-right-side">
        <div>
            <div>Monthly Projection</div>
            <div>$14,355.32</div>
            <div>32% MoM</div>
        </div>
        <div>
            <div>Last Month Spend</div>
            <div>$14,355.32</div>
            <div>32% MoM</div>
        </div>
        <div>
            <div>30 Day Rolling Average</div>
            <div>$14,355.32</div>
            <div>32% MoM</div>
        </div>
        <div>
            <div>7 Day Rolling Average</div>
            <div>$14,355.32</div>
            <div>32% MoM</div>
        </div>
    </div>

    <div data-ng-controller="DashboardController">
        <div data-st-dash="dashboard"
             class="st-dash">

            <div> <!-- Wrapper Div for recessed area. -->

                <div class="st-dash-title">
                    <div>
                        Service Provider - Amazon
                    </div>
                </div>

                <div data-ng-repeat="widgetRow in dash.widgetRows"
                     data-st-widget-row="widgetRow"
                     class="st-widget-row"
                     data-ng-style="{height : widgetRow.height+'px'}">

                    <div
                        data-ng-repeat="widgetColumn in widgetRow.widgetColumns"
                        data-st-widget-column="widgetColumn"
                        class="st-widget-column"
                        data-ng-style="{width : widgetColumn.width + '%'}">

                        <div data-ng-if="$index > 0"
                             class="st-widget-column-dragger">
                            <div></div>
                        </div>

                        <div data-ng-repeat="widget in widgetColumn.widgets"
                             data-st-widget="widget"
                             class="st-widget"
                             data-ng-style="{height : widget.height + '%'}">

                            <div data-ng-if="$index > 0"
                                 class="st-widget-dragger">
                                <div></div>
                            </div>

                            <div class="st-widget-wrapper panel panel-default">
                                <div data-ng-if="widget.title"
                                     class="panel-heading">
                                    <h4 class="panel-title">
                                        {{widget.title}}</h4>
                                </div>
                                <div class="panel-body">
                                    <div data-ng-if="widget.tpl" data-ng-include="widget.tpl"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

<?php

endPartial();