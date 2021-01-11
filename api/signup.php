<?php
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/UserDAO.class.php');
require_once(dirname(__FILE__) . '/includes/debug.inc.php');

$userDAO = new UserDAO();

//get data transmitted from client
$data = json_decode(file_get_contents("php://input"));

//default value of signup success
$signupOk = -1;

if (isset($data)) {
    $pseudo = htmlentities($data->name);
    $birthdate = htmlentities($data->date);
    $email = htmlentities($data->email);
    $emailC = htmlentities($data->emailConfirm);
    $password = htmlentities($data->password);
    $passwordC = htmlentities($data->passwordConfirm);

    $birthdateSplit = [];

    /*Gestion du format de la date*/
    /* Format par défault : "../../...." */
    $birthdateSplit1 = explode("/",$birthdate);
    $birthdateSplit2 = explode("-",$birthdate);
    
    if (count($birthdateSplit1) == 1 && count($birthdateSplit2) == 1) {
        echo json_encode([
            'success' => false,
            'messageError' => 'Format de date inconnu : ' . $birthdate
        ]);
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

        if (empty($pseudo)) {
            echo json_encode([
                'success' => false,
                'messageError' => 'Le champ "pseudo" est vide !'
            ]);
        } elseif (empty($birthdate)) {
            echo json_encode([
                'success' => false,
                'messageError' => 'Le champ "birthdate" est vide !'
            ]);
        }  elseif (empty($email)) {
            echo json_encode([
                'success' => false,
                'messageError' => 'Le champ "email" est vide !'
            ]);
        }  elseif (empty($emailC)) {
            echo json_encode([
                'success' => false,
                'messageError' => 'Le champ "email confirm" est vide !'
            ]);
        }  elseif (empty($password)) {
            echo json_encode([
                'success' => false,
                'messageError' => 'Le champ "password" est vide !'
            ]);
        } elseif (empty($passwordC)) {
            echo json_encode([
                'success' => false,
                'messageError' => 'Le champ "password confirm" est vide !'
            ]);
        } else if (strlen($pseudo) >= 16) {
            echo json_encode([
                'success' => false,
                'messageError' => 'Ton pseudo est trop long ! (Maximum 16 caractères)'
            ]);            
        } else if (strlen($email) >= 64) {
            echo json_encode([
                'success' => false,
                'messageError'=> 'Ton email est trop long ! (Maximum 64 caractères)'
            ]);
        } else if (strlen($password) >= 255) {
            echo json_encode([
                'success' => false,
                'messageError'=> 'Ton mot de passe est trop long ! (Maximum 255 caractères)'
            ]);
        } else if ($email != $emailC) {
            echo json_encode([
                'success' => false,
                'messageError' => 'L\'e-mail et l\'e-mail de confirmation sont différent !'
            ]);      
        } else if ($password != $passwordC) {
            echo json_encode([
                'success' => false,
                'messageError' => 'Le mot de passe et mot de passe de confirmation sont différent !'
            ]);
        } else {
            $signupOk = $userDAO->putUser($email, $pseudo, $password, getClientIP(), $birthdate);

            if ($signupOk == -1) {
                echo json_encode([
                    'success' => false,
                    'messageError' => 'Echec de la connexion ! Un compte existe déjà avec cette adresse e-mail.'
                ]);
            } else {
                echo json_encode([
                    'success' => true
                ]);
            }
        }
    }
}