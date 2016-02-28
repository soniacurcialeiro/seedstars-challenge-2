<?php
require 'vendor/autoload.php';

$router = new AltoRouter();

$router->map( 'GET', '/', function() {
    require __DIR__ . '/views/home.php';
});

$router->map( 'GET', '/list', function() {
    require __DIR__ . '/views/list.php';
});

$router->map( 'GET|POST', '/add', function() {
    require __DIR__ . '/views/add.php';
});

$router->map( 'GET', '/delete/[i:id]', function( $id ) {
  require __DIR__ . '/views/delete.php';
});

// assuming current request url = '/'
$match = $router->match();

// call closure or throw 404 status
if( $match && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] );
} else {
	// no route was matched
	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
  echo "Page not found";
}
