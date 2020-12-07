<?php
require_once(dirname(__FILE__) . '/includes/httpheaders.inc.php');

require_once(dirname(__FILE__) . '/model/AuthMethods.php');
require_once(dirname(__FILE__) . '/model/UserDAO.class.php');
require_once(dirname(__FILE__) . '/includes/debug.inc.php');

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
    $pseudo = $data->name;
    $birthdate = $data->date;
    $email = $data->email;
    $emailC = $data->emailConfirm;
    $password = $data->password;
    $passwordC = $data->passwordConfirm;

    if (empty($pseudo) || empty($birthdate) || empty($email) || empty($emailC) || empty($password) || empty($passwordC)) {
        echo json_encode([
            'success' => false,
            'message' => 'A field is empty !'
        ]);
    } else {
        if ($email == $emailC) {
            if ($password == $passwordC) {
                try {
                    $signupOk = $userDAO->putUser($email, $pseudo, $password, getClientIP(), $birthdate);
                    if ($signupOk) {
                        echo json_encode([
                            'success' => true,
                            'message' => 'User added successfully'
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => 'Signup failed !'
                        ]);
                    }
                } catch (PDOException $e) {
                    $msg = $e->getMessage();
                    echo json_encode([
                        'success' => false,
                        'message' => 'Signup failed : ' . $msg
                    ]);
                    exit;
                }    
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Password wrong !'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Email wrong !'
            ]);    
        }
    }
}


