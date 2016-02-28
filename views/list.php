<?php
require 'header.php';
require "database.php";
require "contacts.php";

// Get the Database instance and connection
$db = Database::getInstance();
$connection = $db->getConnection();


if ( isset($id) ){
  deleteContact( $connection, $id );
  header("Location: /list");
  exit;
}
?>

<div class="page-header">
  <h1>List</h1>
</div>

<?php
showContacts( $connection );
?>

<p><a href="/add" class="btn btn-primary">Add</a></p>

<?php require 'bottom.php';

$db->closeConnection();




function showContacts( $connection ){
  $list_contacts = Contacts::findAll($connection);

  /*if ( !$list_contacts->fetchArray() ){
    echo "There are no contacts to show.";
    return;
  }*/?>

  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ( $contact = $list_contacts->fetchArray() )
        {
          echo '<tr>
            <td>' . htmlspecialchars( $contact["name"] ) . '</td>
            <td>' . htmlspecialchars( $contact["email"] ) . '</td>
            <td><a href="/delete/' . $contact["id"] . '" class="btn btn-sm btn-default" onclick="return confirm(\'Are you sure you want to delete this item?\');">Delete</a></td>
          </tr>';
        }
        ?>
      </tbody>
    </table>

  </div>

  <?php
}

function deleteContact( $connection, $id ){
  Contacts::deleteContact($connection, $id);
}
?>
