<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Seedstars</title>
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/custom.css" rel="stylesheet" />
  </head>

  <body>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">Home</a>
          </div>
          <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
              <li <?php echo getClass('list');?>><a href="/list">List</a></li>
              <li <?php echo getClass('add');?>><a href="/add">Add</a></li>
            </ul>
          </div>
        </div>
    </nav>

    <br />

    <div class="container body">


<?php
function getClass ( $menu ){
  if ( strpos($_SERVER['REQUEST_URI'], $menu ) ) return 'class="active"';
  return '';
}
?>
