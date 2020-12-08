<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/PageDAO.class.php');


$userId = tokenToUserId();

// User logged in
if($userId != -1){
    
    $data = json_decode(file_get_contents("php://input"));
    // voteType = 1 pour like et 0 pour dislike
    $voteType = $data->voteType;
    $pageId = $data->pageId;

    $out = [];

}else{
    // User not logged in
    $out = [
        'message' => "You have to be logged for rate a page !\nDo you want to Log In or Sign Up ?"
    ];
}

echo json_encode($out);

?>