<?php

class DeletePage {

    private $pageid;
    private $userid;
    private $reason;
    private $deletedate;

    /**
     * Get the ID of the deleted page
     * 
     * @return int ID of the page deleted
     */
    function getPageId() : int {
        return $this->pageid;
    }

    /**
     * Get the ID of the user who deleted the page
     * 
     * @return int ID of the user who deleted the page
     */
    function getUserIdWhoDelete() : int {
        return $this->userid;
    }

    /**
     * Get the reson of the delete
     * 
     * @return string Reason of the delete
     */
    function getReason() : string {
        return $this->reason;
    }

    /**
     * Get the date of the delete
     * 
     * @return string Date of the delete
     */
    function getDeleteDate() : string {
        return $this->deletedate;
    }
}
?>