<?php
if (php_sapi_name() === 'cli-server') {
    // running under built-in server so
    // route static assets and return false
    $extensions = array('php', 'jpg', 'jpeg', 'gif', 'css', 'js');
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if (in_array($ext, $extensions)) {
        return false;
    }
}

session_start();

require 'vendor/autoload.php';
require 'lib/database.php';
require 'models/contacts.php';
require 'controllers/contacts.php';

$router = new AltoRouter();

$router->map( 'GET', '/', function () {
  $templates = new League\Plates\Engine('./views');
  echo $templates->render('home');
});

$router->map( 'GET', '/list', 'ContactsController#listContacts');
$router->map( 'GET', '/add', 'ContactsController#addContact');
$router->map( 'POST', '/add', 'ContactsController#addContactPost');
$router->map( 'GET', '/delete/[i:id]', 'ContactsController#deleteContact');

// assuming current request url = '/'
$match = $router->match();
$notfound = true;

// call closure or throw 404 status
if ($match) {
  if (is_callable($match['target'])) {
    $notfound = false;
    call_user_func_array($match['target'], $match['params']);
  } else {
    $parts = explode('#', $match['target']);
    if (is_callable(array($parts[0], $parts[1]))) {
      $notfound = false;
      call_user_func_array(array($parts[0], $parts[1]), $match['params']);
    }
  }
}

if ($notfound) {
  header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
  echo "Page not found";
}

Database::getInstance()->closeConnection();
