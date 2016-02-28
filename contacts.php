<?php
// class for contacts
class Contacts{

  private $_name;
  private $_email;
  private $_db;

  public function __construct( $db, $name, $email )
  {
    $this->_db = $db;
    $this->_name = $name;
    $this->_email = $email;
  }

  // Save a contact
  public function save(){
    if ( !$this->_email || !$this->_name ) return false;

    $stmt = $this->_db->prepare("INSERT INTO contacts (name, email) VALUES (:name, :email)");
    $stmt->bindValue(':name', self::sanitize( $this->_name ), SQLITE3_TEXT);
    $stmt->bindValue(':email', self::sanitize( $this->_email ), SQLITE3_TEXT);
    return $stmt->execute();
  }

  // Verify if the email already exists in database
  public function isDuplicated(){
    $stmt = $this->_db->prepare('SELECT email FROM contacts WHERE email = :email');
    $stmt->bindValue(':email', $this->_email, SQLITE3_TEXT);

    $rows = $stmt->execute();

    return $rows->fetchArray();
  }

  // validate the values on input fields - prevent cross-site scripting attack (XSS)
  private static function sanitize( $data ) {
     $data = trim($data);
     $data = strip_tags($data); // sanitize comment
     return $data;
  }

  // Get all contacts
  public static function findAll( $db ){
    $sql = "SELECT rowid as id, name, email FROM contacts ORDER BY name";
    $stmt = $db->prepare($sql);
    return $stmt->execute();
  }

  // delete contact
  public static function deleteContact( $db, $id ){
    if ( !$id ) return false;

    $stmt = $db->prepare('DELETE FROM contacts WHERE rowid = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    return $stmt->execute();
  }
}
