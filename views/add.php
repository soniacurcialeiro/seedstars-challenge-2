<?php
require "database.php";
require "contacts.php";

// Get the Database instance and connection
$db = Database::getInstance();
$connection = $db->getConnection();

$errors = saveForm($connection);

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
    <input required type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';?>">
    <?php printError($errors, "name");?>
  </fieldset>
  <fieldset class="form-group">
    <label for="email">Email address</label>
    <input required type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';?>">
    <?php printError($errors, "email");?>
  </fieldset>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php require 'bottom.php';
$db->closeConnection();

// validate and save
function saveForm( $connection ){
  $errors = array();
  $name = $email = "";

  if ($_SERVER["REQUEST_METHOD"] != "POST") return $errors;

  // validate required fields and format
  if ( !isset($_POST["name"]) || !$_POST["name"] ){
    $errors["name"] = "The name is required.";
  }
  else {
    $name = $_POST["name"];
  }

  if ( !isset($_POST["email"]) || !$_POST["email"] ){
    $errors["email"] = "The email address is required.";
  }
  else if ( isset($_POST["email"]) ){
    $email = $_POST["email"];

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

  if ( count ($errors) >0 ) return $errors;

  // Save the form
  $result = $contact->save();
  if ( !$result ){
     $errors["global"] = "Please check that you have correctly fill the form.";
     return $errors;
   }

   header('Location: /list');
   exit;
}

function printError( $errors, $field ){
  if ( !isset($errors[$field]) ) return;

  echo '<span class="error">' . $errors[$field] . '</span>';
}
?>
