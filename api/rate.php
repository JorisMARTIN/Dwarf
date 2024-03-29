<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
// require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/model/RateDAO.class.php');


$userId = tokenToUserId();

// User logged in
if($userId != -1){
    
    $data = json_decode(file_get_contents("php://input"));
    // voteType = 1 pour like et 0 pour dislike
    $voteType = $data->rateType;
    $pageId = $data->pageId;
    $rateDAO = new RateDAO();

    $rateDAO->removeVote($pageId, $userId);

    // if($voteType !== $rateDAO->getUserVotePage($userId, $pageId)) peut etre à faire fonctionner un jour
    $rateDAO->putVote($userId, $pageId, (($voteType == 1) ? true : false));

    $votes = $rateDAO->getVotes($pageId);

    $out = [
        'likes' => $votes[0],
        'dislikes' => $votes[1]
    ];
    
}else{
    // User not logged in
    $out = [
        'message' => "Tu dois être connecté pour voter !\nSouhaite-tu te connecter ou t'inscrire ?"
    ];
}

echo json_encode($out);

?>