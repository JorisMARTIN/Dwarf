<?php

class Frame {

  private int $frameId;
  private string $creationDate;
  private string $imagePtr;
  private bool $next;
  private bool $done;
  private int $width;
  private int $height;
  private int $pageId;
  private int $userId;

  function __get(string $prop) : int {
    return $this->$prop;
  }
}

?>
