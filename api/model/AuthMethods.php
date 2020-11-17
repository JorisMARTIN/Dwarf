<?php
$DWARF_SECRET = "vHg&0WdWK6qo95fe";

function tokenToUserId() : int { // returns userId OR -1 if it fails
    $headers = apache_request_headers();

    //check if token is provided
    if (isset($headers['Authorization'])) $auth = $headers['Authorization'];
    else return -1;

    //check token format
    if (preg_match('/Bearer\s((.*)\.(.*)\.(.*))/', $auth, $matches)) $jwt =  $matches[1];
    else return -1;

    $tokenParts = explode('.', $jwt);
    $header = $tokenParts[0];
    $payload = $tokenParts[1];
    $signature = $tokenParts[2];

    $payload_decoded = json_decode(base64_decode($payload));
    if ($payload_decoded == NULL) return -1;

    //check expiration
    if (!isset($payload_decoded->exp) || $payload_decoded->exp < time()) return -1;

    //check signature
    $secret = $GLOBALS['DWARF_SECRET'];
    if ($signature != hash_hmac('sha256', $header . "." . $payload, $secret, true)) return -1;

    if(isset($payload_decoded->uid))
        return $payload_decoded->uid;
    else
        return -1;
}


// JWT generation adapted from https://dev.to/robdwaller/how-to-create-a-json-web-token-using-php-3gml
function generateToken(int $userId, int $expiration) : string {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload = json_encode([
        'uid' => $userId,
        'exp' => $expiration,
        'iss' => "dwarf.jorismartin.fr"
    ]);

    // Encode Header to Base64Url String
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

    // Encode Payload to Base64Url String
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    // Create Signature Hash
    $secret = $GLOBALS['DWARF_SECRET'];
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

    // Encode Signature to Base64Url String
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    // Create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    return $jwt;
}