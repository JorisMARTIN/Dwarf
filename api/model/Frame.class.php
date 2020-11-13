<?php

class Frame {

  private $frameId;
  private $creationDate;
  private $imagePtr;
  private $drawable;
  private $done;
  private $width;
  private $height;
  private $pageId;
  private $userId;

  function __get(string $prop) : int {
    return $this->$prop;
  }
}

?>
