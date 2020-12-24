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
  private $ttl;

  /**
   * Get the ID of the frame
   * 
   * @return int ID og the frame
   */
  function getId() : int {
    return $this->frameid;
  }

  /**
   * Get the url of the image on the server of the frame
   * 
   * @return string URL of the image of the frame
   */
  function getImagePtr() : string {
    return $this->imageptr;
  }

  /**
   * Get the creation date of the image
   * 
   * @return string Creation date of the frame
   */
  function getCreationDate() : string {
    return $this->creationdate;
  }

  /**
   * Get the width of the frame
   * 
   * @return int Widht of the frame
   */
  function getWidth() : int {
    return $this->width;
  }

  /**
   * Get the height of the frame
   * 
   * @return int Height of the frame
   */
  function getHeight() : int {
    return $this->height;
  }

  /**
   * Get the ID of the page which includes the frame
   * 
   * @return int ID of the page
   */
  function getPageId() : int {
    return $this->pageid;
  }

  /**
   * Get the ID of the user who created the frame
   * 
   * @return ID of the user
   */
  function getOwnerId() : int {
    return $this->userid;
  }

  /**
   * Check if the frame can be drawn
   * 
   * @return bool true = drawable | false = not drawable
   */
  function isDrawable() : bool {
    return $this->drawable;
  }

  /**
   * Check if the frame is done
   * 
   * @return bool true = done | false = not done
   */
  function isDone() : bool {
    return $this->done;
  }

  /**
   * Check if frame is free to use (no ongoing ttl)
   * 
   * @return bool true = free | false = not free
   */
  function isFree() : bool {
    return empty($this->ttl) || $this->ttl < time();
  }

}

?>
