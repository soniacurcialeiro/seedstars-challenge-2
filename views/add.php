<?php
session_start();

require "database.php";
require "contacts.php";

// Get the Database instance and connection
$db = Database::getInstance();
$connection = $db->getConnection();

$errors = array();
$values = array();
if ($_SERVER["REQUEST_METHOD"] != "POST") {
  $salt = "ZCzsgyH6K8";
  $token = md5( $salt . time() );
  $_SESSION["token"] = $token;

  if (isset($_SESSION["errors"])){
    $errors = $_SESSION["errors"];
    unset($_SESSION["errors"]);
  }

  if (isset($_SESSION["values"])){
    $values = $_SESSION["values"];
    unset($_SESSION["values"]);
  }
}
else{
  $token = $_SESSION["token"];

  saveForm($connection);
}
require 'header.php';
?>

<div class="page-header">
  <h1>Add a contact</h1>
</div>

<form method="post">
  <p>All fields are required.</p>
  <?php printError($errors, "global");?>

  <fieldset class="form-group">
    <label for="name">Name</label>
    <input required type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php echo getValue($values, 'name');?>">
    <?php printError($errors, "name");?>
  </fieldset>
  <fieldset class="form-group">
    <label for="email">Email address</label>
    <input required type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php echo getValue($values, 'email');?>">
    <?php printError($errors, "email");?>
  </fieldset>
  <input type="hidden" name="token" id="token" value="<?php echo $token;?>">
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php require 'bottom.php';
$db->closeConnection();

// validate and save
function saveForm( $connection ){
  $values = $errors = array();
  $name = $email = "";

  // preventing CSRF
  $token = $_SESSION["token"];
  unset($_SESSION["token"]);

  if ( !isset($_POST["token"]) || $_POST["token"] != $token ){
    $errors["global"] = "An error has occurred";
    $_SESSION["errors"] = $errors;
    header("Location: /add");
    exit;
  }


  // validate required fields and format
  if ( !isset($_POST["name"]) || !$_POST["name"] ){
    $errors["name"] = "The name is required.";
  }
  else {
    $name = $_POST["name"];
    $values["name"] = $name;
  }

  if ( !isset($_POST["email"]) || !$_POST["email"] ){
    $errors["email"] = "The email address is required.";
  }
  else if ( isset($_POST["email"]) ){
    $email = $_POST["email"];
    $values["email"] = $email;

    // check email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors["email"] = "Invalid email address.";
    }
  }

  $contact = new Contacts( $connection, $name, $email);

  // validate duplicate fields
  if ( $contact->isDuplicated() ){
    $errors["email"] = "The email address already exists in our database.";
  }

  if ( count ($errors) >0 ){
    $_SESSION["errors"] = $errors;
    $_SESSION["values"] = $values;

    header("Location: /add");
    exit;
  }

  // Save the form
  $result = $contact->save();
  if ( !$result ){
     $errors["global"] = "Please check that you have correctly fill the form.";
     $_SESSION["errors"] = $errors;
     $_SESSION["values"] = $values;
     header("Location: /add");
     exit;
   }

   header('Location: /list');
   exit;
}

function printError( $errors, $field ){
  if ( !isset($errors[$field]) ) return;

  echo '<span class="error">' . $errors[$field] . '</span>';
}

function getValue($values, $field){
  if (!isset($values[$field])) return "";

  return htmlspecialchars($values[$field]);
}
