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

  /**
   * Get user ID
   * 
   * @return int The user ID
   */
  function getId() : int {
    return $this->userid;
  }

  /**
   * Get user Email
   * 
   * @return string The user email
   */
  function getEmail() : string {
    return $this->email;
  }

  /**
   * Get username
   * 
   * @return string The username
   */
  function getNickname() : string {
    return $this->nickname;
  }

  /**
   * Get the creaction date of the user
   * 
   * @return string The creation data
   */
  function getCreationDate() : string {
    return $this->creationdate;
  }

  /**
   * Get all ip address link to the user
   * 
   * @return array Array of string of all IP address
   */
  function getIps() : array {
    return explode(',', trim($this->ips, "{}"));
  }

  /**
   * Get birthdate of the user
   * 
   * @return string The user birthdate
   */
  function getBirthdate() : string {
    return $this->birthdate;
  }

  /**
   * Check if the user is admin
   * 
   * @return bool true = admin | false = not admin
   */
  function isAdmin() : bool {
    return $this->admin;
  }
}

?>
