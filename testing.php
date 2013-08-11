<?php

include  (__DIR__.'/app/App.php');

$routes[] = new Route('app/:a', array());
$routes[] = new Route('app/:b', array());
$routes[] = new Route('app/:c?', array());


foreach($routes as $route) {
}

new Routing();