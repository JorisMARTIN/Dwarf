<?php

class Page {

  private $pageid;
  private $creationdate;
  private $name;
  private $description;
  private $gamemode;
  private $template;
  private $completed;
  private $userid;

  function __get(string $prop) : int {
    return $this->$prop;
  }
}

?>
