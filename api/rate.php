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
    $userId = $data->userId;
    $pageId = $data->pageId;
    $rateDAO = new RateDAO();

    $rateDAO->putVote($userId, $pageId, ($voteType == 1) ? true : false);

    $out = [];

}else{
    // User not logged in
    $out = [
        'message' => "Tu dois être connecté pour voter !\nVeux tu te connecter ou t'inscrire ?"
    ];
}

echo json_encode($out);

?>