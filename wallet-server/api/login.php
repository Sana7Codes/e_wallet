<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../models/users.php';
require_once __DIR__ . '/../config/jwt.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$jwt = JWT::encode($payload, JWT_SECRET_KEY, 'HS256'); //  Encode JWT

$decoded = JWT::decode($jwt, new Key(JWT_SECRET_KEY, 'HS256')); //  Decode JWT


// Ensure request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST requests are allowed."]);
    exit;
}

// Debugging: Log raw input
$rawInput = file_get_contents("php://input");
error_log("Raw Input: " . $rawInput);

$input = json_decode($rawInput, true);

// Check if JSON is valid
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid JSON format"]);
    exit;
}

// Validate required fields
$email = $input['email'] ?? null;
$password = $input['password'] ?? null;

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "Email and password are required."]);
    exit;
}

// Initialize user model
$userModel = new UserModel($conn);
$user = $userModel->getUserByEmail($email);

if (!$user || !password_verify($password, $user['password_hash'])) {
    error_log("Failed login attempt for email: $email"); // Log failed attempts
    http_response_code(401);
    echo json_encode(["error" => "Invalid email or password."]);
    exit;
}

//  Generate JWT Token
$payload = [
    "user_id" => $user['id'],
    "email" => $user['email'],
    "iat" => time(),
    "exp" => time() + (60 * 60 * 24) // Token expires in 24 hours
];

$jwt = JWT::encode($payload, JWT_SECRET_KEY, 'HS256');

http_response_code(200);
echo json_encode([
    "message" => "Login successful!",
    "token" => $jwt,  //  Include JWT token in response
    "user" => [
        "id" => $user['id'],
        "name" => $user['name'],
        "email" => $user['email']
    ]
]);
exit;
