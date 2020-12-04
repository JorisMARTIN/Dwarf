<?php

class Rate {
  private $userId;
  private $pageId;
  private $vote;

  function getUserId() : int {
    return $this->userId;
  }

  function getPageId() : int {
    return $this->pageId;
  }

  function getVote() : bool {
    return $this->vote;
  }
}
?>