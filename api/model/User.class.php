<?php

class User {

  private $userid;
  private $email;
  private $nickname;
  private $password;
  private $ips;
  private $creationdate;

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
    return $this->ips;
  }

}

?>
