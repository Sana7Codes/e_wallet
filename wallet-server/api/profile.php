<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../models/users.php';
require_once __DIR__ . '/middleware.php';

$userData = verifyJWT(); // Authenticate user using JWT
$userModel = new UserModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch user profile
    $userProfile = $userModel->getUserById($userData['user_id']);

    if ($userProfile) {
        http_response_code(200);
        echo json_encode($userProfile);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "User not found"]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user profile
    $input = json_decode(file_get_contents("php://input"), true);
    $name = $input['name'] ?? null;
    $phone = $input['phone'] ?? null;
    $birthdate = $input['birthdate'] ?? null;

    if (!$name || !$phone || !$birthdate) {
        http_response_code(400);
        echo json_encode(["error" => "All fields are required."]);
        exit;
    }

    $updated = $userModel->updateUserProfile($userData['user_id'], $name, $phone, $birthdate);

    if ($updated) {
        http_response_code(200);
        echo json_encode(["message" => "Profile updated successfully!"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Profile update failed."]);
    }
    exit;
}

// If method is not GET or POST
http_response_code(405);
echo json_encode(["error" => "Method Not Allowed"]);
exit;
