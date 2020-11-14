<?php

class User {

  private $userId;
  private $email;
  private $nickname;
  private $password;
  private $IPs;
  private $creationDate;

  function __get(string $prop) : int {
    return $this->$prop;
  }
}

?>
