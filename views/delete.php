<?php
require "database.php";
require "contacts.php";

// Get the Database instance and connection
$db = Database::getInstance();
$connection = $db->getConnection();

if ( isset($id) ){
  Contacts::deleteContact($connection, $id);
}
header("Location: /list");
exit;
