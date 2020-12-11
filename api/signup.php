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

/* Get the code from here : https://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php*/
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
    $pseudo = htmlentities($data->name);
    $birthdate = htmlentities($data->date);
    $email = htmlentities($data->email);
    $emailC = htmlentities($data->emailConfirm);
    $password = htmlentities($data->password);
    $passwordC = htmlentities($data->passwordConfirm);
    
    /*Gestion du formatde la date*/
    $birthdateSplit = explode("-",$birthdate);
    
    if (strlen($birthdateSplit[0]) != 4) {
        $birthdate = $birthdateSplit[2] . "-" . $birthdateSplit[1] . "-" . $birthdateSplit[0];
    }

    if (empty($pseudo)) {
        echo json_encode([
            'success' => false,
            'message' => 'The field "pseudo" is empty !'
        ]);
    } elseif (empty($birthdate)) {
        echo json_encode([
            'success' => false,
            'message' => 'The field "birthdate" is empty !'
        ]);
    }  elseif (empty($email)) {
        echo json_encode([
            'success' => false,
            'message' => 'The field "email" is empty !'
        ]);
    }  elseif (empty($emailC)) {
        echo json_encode([
            'success' => false,
            'message' => 'The field "email confirm" is empty !'
        ]);
    }  elseif (empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'The field "password" is empty !'
        ]);
    } elseif (empty($passwordC)) {
        echo json_encode([
            'success' => false,
            'message' => 'The field "password confirm" is empty !'
        ]);
    } else if (strlen($pseudo) > 16) {
        echo json_encode([
            'success' => false,
            'message' => 'Pseudo too long ! (Must be less than 17 character)'
        ]);            
    } else if (strlen($email) > 64) {
        echo json_encode([
            'success' => false,
            'message'=> "Email too long ! (Must be less than 65 character)"
        ]);
    } else if (strlen($password) > 255) {
        echo json_encode([
            'success' => false,
            'message'=> "Password too long ! (Must be less than 256 character)"
        ]);
    } else if ($email != $emailC) {
        echo json_encode([
            'success' => false,
            'message' => 'Email and email confirm are different !'
        ]);      
    } else if ($password != $passwordC) {
        echo json_encode([
            'success' => false,
            'message' => 'Password and password confirm are different !'
        ]);
    } else {
        $signupOk = $userDAO->putUser($email, $pseudo, $password, getClientIP(), $birthdate);

        if ($signupOk == -1) {
            echo json_encode([
                'success' => false,
                'message' => 'Signup failed ! An account with this email already exist'
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'User added successfully !'
            ]);
        }
    }
}


