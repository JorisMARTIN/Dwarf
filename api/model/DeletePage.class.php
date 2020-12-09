<?php

class DeletePage {

    private $pageId;
    private $userId;
    private $reason;
    private $date;

    function getPageId() : int {
        return $this->pageId;
    }

    function getUserWhoDeleteId() : int {
        return $this->userId;
    }

    function getReason() : string {
        return $this->reason;
    }

    function getDeleteDate() : string {
        return $this->date;
    }
}
