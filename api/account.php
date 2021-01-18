<?php
require_once(dirname(__FILE__) . '/includes/debug.inc.php');
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/UserDAO.class.php');
require_once(dirname(__FILE__) . '/model/PageDAO.class.php');
require_once(dirname(__FILE__) . '/model/FrameDAO.class.php');

$userDAO = new UserDAO();

//get data transmitted from client
$data = json_decode(file_get_contents("php://input"));

$out = [];

// Get current user
$userId = tokenToUserId();

if($userId != -1){
    $action = htmlentities($data->action);

    switch ($action) {
        case 'save':
            if (isset($data)) {
                $pseudo = htmlentities($data->pseudo);
                $email = htmlentities($data->email);
                $password = htmlentities($data->password);
                $passwordC = htmlentities($data->passwordConfirm);
                $birthdate = htmlentities($data->date);
            } else {
                $out = [
                    'success' => false,
                    'messageError' => 'Erreur dans l\'envoie des données' 
                ];
            }

            $birthdateSplit = [];

            /*Gestion du format de la date*/
            /* Format par défault : "../../...." */
            $birthdateSplit1 = explode("/",$birthdate);
            $birthdateSplit2 = explode("-",$birthdate);

            if (empty($birthdate)) {
                $birthdate = $userDAO->getUser($userId)->getBirthdate();
                $out = [
                    'warning' => 'Le champ "birthdate" est vide ! Il a été remplacé par '.$birthdate
                ];
            } elseif (count($birthdateSplit1) == 1 && count($birthdateSplit2) == 1) {
                $out = [
                    'success' => false,
                    'messageError' => 'Format de date inconnu : ' . $birthdate
                ];
            } else {
                $birthdateSplit = explode("/",$birthdate);
                if (count($birthdateSplit1) == 1) {
                    $birthdateSplit = explode("-",$birthdate);
                }
                
                if (strlen($birthdateSplit[0]) != 4) {
                    $birthdate = $birthdateSplit[2] . "-" . $birthdateSplit[1] . "-" . $birthdateSplit[0];
                } else {
                    $birthdate = $birthdateSplit[0] . "-" . $birthdateSplit[1] . "-" . $birthdateSplit[2];
                }
            }

            if (empty($pseudo)) {
                $pseudo = $userDAO->getUser($userId)->getNickname();
                $out = [
                    'warning' => 'Le champ "pseudo" est vide ! Il a été remplacé par '.$pseudo
                ];
            }
            if (empty($email)) {
                $email = $userDAO->getUser($userId)->getEmail();
                $out = [
                    'warning' => 'Le champ "email" est vide ! Il a été emplacé par ' . $email
                ];
            }
            
            if (empty($password)) {
                $out = [
                    'success' => false,
                    'messageError' => 'Le champ "password" est vide !'
                ];
            } elseif (empty($passwordC)) {
                $out = [
                    'success' => false,
                    'messageError' => 'Le champ "password confirm" est vide !'
                ];
            } else if (strlen($pseudo) >= 16) {
                $out = [
                    'success' => false,
                    'messageError' => 'Ton pseudo est trop long ! (Maximum 16 caractères)'
                ];            
            } else if (strlen($email) >= 64) {
                $out = [
                    'success' => false,
                    'messageError'=> 'Ton email est trop long ! (Maximum 64 caractères)'
                ];
            } else if (strlen($password) >= 255) {
                $out = [
                    'success' => false,
                    'messageError'=> 'Ton mot de passe est trop long ! (Maximum 255 caractères)'
                ];
            } else if ($password != $passwordC) {
                $out = [
                    'success' => false,
                    'messageError' => 'Le mot de passe et mot de passe de confirmation sont différent !'
                ];
            } else {
                $majOk = $userDAO->updateUser(strval($userId), $pseudo, $email,$password,$birthdate);
                if ($majOk) {
                    $out = [
                        'success' => true
                    ];
                } else {
                    $out = [
                        'success' => false,
                        'messageError' => 'La mise à jour à échoué.'
                    ];
                }
            }
            break;

        case 'delete':
            $out = [
                'success' => false,
                'messageError' => 'Pas encore implémenté...'
            ];
            # code...
            break;
        
        default:
            $out = [
                'success' => false,
                'messageError' => 'Les données envoyés ne peuvent pas être prises en compte.'
            ];
            break;
    }
} else {
    $out = [
        'success' => false,
        'messageError' => 'Vous n\'etes pas connecté !' 
    ];
}

echo json_encode($out);

?>