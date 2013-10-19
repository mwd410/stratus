<?php

$sitename = "Stratus";
$siteurl = "http://webapp-dev.stratus-cloudservices.com/";

$keystring = "stratusstring";

extendView('base');

beginPartial('menu');

?>

    <div data-ng-include="'/partials/nav.html'"></div>
<?php
endPartial();

beginPartial('body');

insertPartial('menu');


insertPartial('content');

endPartial();