<?php
class ContactsController {
  public static function listContacts () {
    // Get all contacts
    $contacts = Contacts::findAll();

    if ( !$contacts->fetchArray() ){
      $contacts = null;
    }
    else {
      $contacts->reset();
    }

    $templates = new League\Plates\Engine('./views');
    echo $templates->render('list', array('contacts' => $contacts));
  }

  public static function addContact () {
    $errors = array();
    $values = array();
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

    $templates = new League\Plates\Engine('./views');
    echo $templates->render('add', array(
      'values' => $values,
      'errors' => $errors,
      'token' => $token
    ));
  }

  public static function addContactPost () {
    // validate and save the contact
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

    $contact = new Contacts($name, $email);

    // validate duplicate fields
    if ( $contact->isDuplicated() ){
      $errors["email"] = "The email address already exists in our database.";
    }

    if ( count ($errors) >0 ){
      $_SESSION["errors"] = $errors;
      $_SESSION["values"] = $values;

      header("Location: /add"); // go to the get page
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

  public static function deleteContact ($id) {
    if (isset($id)) {
      Contacts::deleteContact($id);
    }

    header("Location: /list");
    exit;
  }
}
