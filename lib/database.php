<?php
// Class Database - SQLite3
class Database {

  private static $_instance;
  private $_db;

  private function __construct($file)
  {
    $this->_db = new SQLite3($file); // create database

    $this->_initialize(); // create tables
    return $this->_db;
  }

  // return the database instance
  public static function getInstance( $file = 'db/seedstarsdb.db' ) {
		if(!self::$_instance) {
			self::$_instance = new self( $file );
		}
		return self::$_instance;
	}

  // return database connection
	public function getConnection() {
		return $this->_db;
	}

  public function closeConnection() {
    $this->_db->close();
  }

  // Create tables
  private function _initialize(){
    $this->_db->exec('CREATE TABLE IF NOT EXISTS contacts (name text, email text, PRIMARY KEY (email));');
  }

  // prevent duplication of connection
	private function __clone() { }
}
