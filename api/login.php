<?php
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
header("Access-Control-Allow-Headers: Content-Type");

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/UserDAO.php');

$userDAO = new UserDAO();

//get data transmitted from client
$data = json_decode(file_get_contents("php://input"));
$email = $data->email;
$password = $data->password;

$userId = $userDAO->logUser($email, $password);

if ($userId != -1) {
    $expiration = time() + 24 * 60 * 60; //token valide 24h   int time() = timestamp UNIX en secondes
    $token = generateToken($userId, $expiration);

    echo json_encode([
        'token' => $token,
        'status' => 200,
        'message' => 'Login success'
    ]);
} else {
    echo json_encode([
        'status' => 400,
        'message' => 'Login failed!'
    ]);
}
