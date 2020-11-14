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

  function __get(string $prop) : int {
    return $this->$prop;
  }
}

?>
