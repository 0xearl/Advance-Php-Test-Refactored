<?php

include_once('../vendor/autoload.php');
include_once('autoload.php');

//lets make a cute little router here
$uri = explode('/', $_SERVER['REQUEST_URI']);

if($uri[2] === '' || $uri[2] == 'report'){ //if the user is on the index page
    include_once('Views/report.php');
}else if($uri[2] == 'export') {
    include_once('Views/export.php');
}else {
    http_response_code(404);
}
