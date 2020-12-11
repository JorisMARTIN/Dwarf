<?php

class Rate {
  private $userid;
  private $pageid;
  private $vote;

  /**
   * Get the user ID who voted
   * 
   * @return int ID of the user
   */
  function getUserId() : int {
    return $this->userid;
  }

  /**
   * Get the page ID which is rated
   * 
   * @return int ID of the page
   */
  function getPageId() : int {
    return $this->pageid;
  }

  /**
   * Get the value of the vote
   * 
   * @return bool true = 👍 | false = 👎
   */
  function getVote() : bool {
    return $this->vote;
  }
}
?>