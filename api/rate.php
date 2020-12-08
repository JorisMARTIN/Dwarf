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
<<<<<<< HEAD
    $rateDAO = new RateDAO();

    // if ($votetype = 1) {
    //     $rateDAO->putVote()
    // }
=======
    $pageId = $data->pageId;
>>>>>>> 9a87199730daec4a325800631bd8b404eea95b26

    $out = [];

}else{
    // User not logged in
    $out = [
        'message' => "You have to be logged to rate a page !\nDo you want to log in or sign up ?"
    ];
}

echo json_encode($out);

?>