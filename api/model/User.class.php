<?php

class User {

  private $userid;
  private $email;
  private $nickname;
  private $password;
  private $ips;
  private $creationdate;

  function __get(string $prop) {
    return $this->$prop;
  }
}

?>
