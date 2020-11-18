<?php

class Frame {

  private $frameid;
  private $creationdate;
  private $imageptr;
  private $drawable;
  private $done;
  private $width;
  private $height;
  private $pageid;
  private $userid;

  function getId() : int {
    return $this->frameid;
  }

  function getImagePtr() : string {
    return $this->imageptr;
  }

  function getCreationDate() : string {
    return $this->creationDate;
  }

  function getWidth() : int {
    return $this->width;
  }

  function getHeight() : int {
    return $this->height;
  }

  function getPageId() : int {
    return $this->pageid;
  }

  function getOwnerId() : int {
    return $this->userid;
  }

}

?>
