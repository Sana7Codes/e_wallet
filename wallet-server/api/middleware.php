<?php
require_once __DIR__ . '../config/jwt.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

error_log("middleware.php included"); // Check if the file is loaded

function verifyJWT()
{
    error_log("verifyJWT() function is being called"); // Debug function execution

    $headers = apache_request_headers();
    error_log("Headers: " . json_encode($headers)); // Log headers to check if token exists

    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized - Missing token"]);
        exit;
    }

    $token = str_replace("Bearer ", "", $headers['Authorization']);
    error_log("Token received: " . $token);

    try {
        $decoded = JWT::decode($token, new Key(JWT_SECRET_KEY, 'HS256'));
        error_log("JWT Decoded Successfully");
        return (array) $decoded;
    } catch (Exception $e) {
        error_log("JWT Error: " . $e->getMessage());
        http_response_code(401);
        echo json_encode(["error" => "Invalid token"]);
        exit;
    }
}
