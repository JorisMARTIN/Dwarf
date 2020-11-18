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

  function getId() : int {
    return $this->pageid;
  }

  function getName() : string {
    return $this->name;
  }

  function getDescription() : string {
    return $this->desciption;
  }

  function getGameMode() : int {
    return $this->gamemode;
  }

  function getTemplateType() : int {
    return $this->template;
  }

  function getCreationDate() : string {
    return $this->creationdate;
  }

  function isCompleted() : bool {
    return $this->completed;
  }

  function getOwnerId() : int {
    return $this->userid;
  }

}

?>
