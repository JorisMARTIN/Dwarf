<?php

class Page {

  private $pageId;
  private $creationDate;
  private $name;
  private $description;
  private $gameMode;
  private $template;
  private $completed;
  private $userId;

  function __get(string $prop) : int {
    return $this->$prop;
  }
}

?>
