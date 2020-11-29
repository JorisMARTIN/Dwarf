<?php

class User {

  private $userid;
  private $email;
  private $nickname;
  private $password;
  private $ips;
  private $creationdate;
  private $birthdate;
  private $admin;

  function getId() : int {
    return $this->userid;
  }

  function getEmail() : string {
    return $this->email;
  }

  function getNickname() : string {
    return $this->nickname;
  }

  function getCreationDate() : string {
    return $this->creationdate;
  }

  function getIps() : array {
    return explode(',', trim($this->ips, "{}"));
  }

  function getBirthdate() : string {
    return $this->birthdate;
  }

  function isAdmin() : bool {
    return $this->admin;
  }
}

?>
