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

<div class="st-dash">
    <div class="widget"
         data-ng-repeat="widget in widgets"
         data-st-widget="widget">
        <div>
            <div class="st-widget-title">
                1
            </div>
        </div>
    </div>
</div>

<?php

endPartial();