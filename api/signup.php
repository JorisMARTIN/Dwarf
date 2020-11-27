<?php
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/UserDAO.class.php');

$userDAO = new UserDAO();

//get data transmitted from client
$data = json_decode(file_get_contents("php://input"));

//default value of signup success
$signupOk = false;

function getClientIP() {
    $ipaddress = 'UNKNOWN';
    $keys=array('HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_FORWARDED_FOR','HTTP_FORWARDED','REMOTE_ADDR');
    foreach($keys as $k) {
        if (isset($_SERVER[$k]) && !empty($_SERVER[$k]) && filter_var($_SERVER[$k], FILTER_VALIDATE_IP)) {
            $ipaddress = $_SERVER[$k];
            break;
        }
    }
    return $ipaddress;
}

if (isset($data)) {
    $pseudo = $date->name;
    $birthdate = $date->date;
    $email = $data->email;
    $emailC = $data->emailConfirm;
    $password = $data->password;
    $passwordC = $data->passwordConfirm;
}

if (($email == $emailC) && ($password == $passwordC))   {
    $signupOk = $userDAO->putUser($email, $pseudo, $password, getClientIP());
} 


if ($signupOk) {
    echo json_encode([
        'success' => true,
        'status' => 200,
        'message' => 'User added successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => 'Signup failed !'
    ]);
}