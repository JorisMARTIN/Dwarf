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
    $pseudo = htmlspecialchars($data->name);
    $birthdate = htmlspecialchars($data->date);
    $email = htmlspecialchars($data->email);
    $emailC = htmlspecialchars($data->emailConfirm);
    $password = htmlspecialchars($data->password);
    $passwordC = htmlspecialchars($data->passwordConfirm);
    $cgu = htmlspecialchars($data->cgu);

    $birthdateExp = explode('-', $birthdate);

    if (empty($birthdate)) {
        echo json_encode([
            'success' => false,
            'messageError' => 'Le champ "date de naissance" est vide !'
        ]);
    } else if (!(count($birthdateExp) === 3 && checkdate($birthdateExp[1], $birthdateExp[2], $birthdateExp[0]))) {
        echo json_encode([
            'success' => false,
            'messageError' => 'La date de naissance est invalide'
        ]);
    } else if (empty($pseudo)) {
        echo json_encode([
            'success' => false,
            'messageError' => 'Le champ "pseudo" est vide !'
        ]);
    } else if (empty($email)) {
        echo json_encode([
            'success' => false,
            'messageError' => 'Le champ "e-mail" est vide !'
        ]);
    } else if (empty($emailC)) {
        echo json_encode([
            'success' => false,
            'messageError' => 'Le champ "confirmation de l\'e-mail" est vide !'
        ]);
    } else if (empty($password)) {
        echo json_encode([
            'success' => false,
            'messageError' => 'Le champ "mot de passe" est vide !'
        ]);
    } else if (empty($passwordC)) {
        echo json_encode([
            'success' => false,
            'messageError' => 'Le champ "confirmation du mot de passe" est vide !'
        ]);
    } else if (strlen($pseudo) >= 16) {
        echo json_encode([
            'success' => false,
            'messageError' => 'Ton pseudo est trop long ! (Maximum 16 caractères)'
        ]);
    } else if (strlen($email) >= 64) {
        echo json_encode([
            'success' => false,
            'messageError' => 'Ton email est trop long ! (Maximum 64 caractères)'
        ]);
    } else if (strlen($password) >= 255) {
        echo json_encode([
            'success' => false,
            'messageError' => 'Ton mot de passe est trop long ! (Maximum 255 caractères)'
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
    } else if ($cgu == 'off') {
        echo json_encode([
            'success' => false,
            'messageError' => 'Les conditions générales d\'utilisations doivent être accépté pour se creer un compte.'
            ]);
    }else {
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
