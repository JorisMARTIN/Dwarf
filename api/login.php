<?php
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

$SECRET = "vHg&0WdWK6qo95fe";
$ISSUER = "dwarf.jorismartin.fr";

//get db connection


// JWT generation code from https://dev.to/robdwaller/how-to-create-a-json-web-token-using-php-3gml
function generateToken($userId, $secret, $expiration, $issuer) {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload = json_encode([
        'uid' => $userId,
        'exp' => $expiration,
        'iss' => $issuer
    ]);

    // Encode Header to Base64Url String
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

    // Encode Payload to Base64Url String
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    // Create Signature Hash
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

    // Encode Signature to Base64Url String
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    // Create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    return $jwt;
}

$data = json_decode(file_get_contents("php://input"));

$email = $data->email;
$password = $data->password;

//check si user existe dans la bdd
$validCredentials = true;
// à faire

if($validCredentials){
	
	$token = generateToken($userId, $SECRET, $expiration, $ISSUER);

	echo json_encode([
		'token' => $token,
		'status' => 200,
		'message' => 'Login success'
	]);

}else{
    echo json_encode([
		'status' => 400,
		'message' => 'Login failed!'
	]);
}

?>