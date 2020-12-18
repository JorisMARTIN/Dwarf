<?php
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/UserDAO.class.php');

$userDAO = new UserDAO();

//get data transmitted from client
$data = json_decode(file_get_contents("php://input"));

if (isset($data)) {
    $email = $data->email;
    $password = $data->password;
}

if (isset($email) && isset($password))
    $userId = $userDAO->logUser($email, $password);
else $userId = -1;

if ($userId != -1) {
    $expiration = time() + 24 * 60 * 60; //token valide 24h   int time() = timestamp UNIX en secondes
    $token = generateToken($userId, $expiration);

    echo json_encode([
        'token' => $token,
        'status' => 200,
        'message' => 'Connexion réussie'
    ]);
} else {
    echo json_encode([
        'status' => 400,
        'messageError' => 'La connexion a échoué. Veuillez réessayer.'
    ]);
}
