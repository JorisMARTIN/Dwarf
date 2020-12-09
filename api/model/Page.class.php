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
  private $deleted;

  /**
   * Get the ID of the poge
   * 
   * @return int ID of the page
   */
  function getId() : int {
    return $this->pageid;
  }

  /**
   * Get the name of the page
   * 
   * @return string Name of the page
   */
  function getName() : string {
    return $this->name;
  }

  /**
   * Get the description of the page
   * 
   * @return string Descritpion of the page
   */
  function getDescription() : string {
    return $this->description;
  }

  /**
   * Get the gamemode of the Page
   * 
   * @return int Gamemode of the page
   */
  function getGameMode() : int {
    return $this->gamemode;
  }

  /**
   * Get the template of the page
   * 
   * @return int Template of the page
   */
  function getTemplateType() : int {
    return $this->template;
  }

  /**
   * Get the creation date of the page
   * 
   * @return string Creation date of the page
   */
  function getCreationDate() : string {
    return $this->creationdate;
  }

  /**
   * Check if the page is completed
   * 
   * @return bool true = completed | false = not completed
   */
  function isCompleted() : bool {
    return $this->completed;
  }

  /**
   * Get the ID of the user who created the page
   * 
   * @return int ID of the user
   */
  function getOwnerId() : int {
    return $this->userid;
  }

  /**
   * Check if the page is deleted
   * 
   * @return bool true = deleted | false = not deleted
   */
  function isDeleted() : bool {
    return $this->deleted;
  }
}

?>
