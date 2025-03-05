<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../models/users.php';

// Debugging: Log request method and incoming data
error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
error_log("POST Data: " . json_encode($_POST));
error_log("FILES Data: " . json_encode($_FILES));
error_log("Raw Input: " . file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed. Only POST requests are accepted."]);
    exit;
}

// Retrieve form data
$name = $_POST['full_name'] ?? null;
$email = $_POST['email'] ?? null;
$phone = $_POST['phone'] ?? null;
$password = $_POST['password'] ?? null;
$birthdate = $_POST['birthdate'] ?? null;

// Check if form data is empty
if (empty($_POST)) {
    http_response_code(400);
    echo json_encode(["error" => "No form data received. Make sure you are using multipart/form-data."]);
    exit;
}

// Check required fields
if (!$name || !$email || !$phone || !$password || !$birthdate) {
    http_response_code(400);
    echo json_encode(["error" => "All fields are required."]);
    exit;
}

// Debugging: Log successful field reception
error_log("Form Data Received: Name=$name, Email=$email, Phone=$phone, Birthdate=$birthdate");



// Validate Required Fields
if (!$name || !$email || !$phone || !$password || !$birthdate) {
    http_response_code(400);
    echo json_encode(["error" => "All fields are required."]);
    exit;
}
/*
// Handle File Upload & Store URL
$verificationDocumentURL = null;
$uploadDirectory = __DIR__ . "/assets/";

if (!is_dir($uploadDirectory)) {
    mkdir($uploadDirectory, 0777, true);
}

if (isset($_FILES['verification_document']) && $_FILES['verification_document']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['verification_document']['tmp_name'];
    $fileName = time() . "_" . basename($_FILES['verification_document']['name']); // Unique file name
    $filePath = $uploadDirectory . $fileName;
    
    if (move_uploaded_file($fileTmpPath, $filePath)) {
        $verificationDocumentURL = "/Digital_Wallet/wallet-client/assets/" . $fileName; // URL to access the file
    }
}

// Ensure file was processed
if (!$verificationDocumentURL) {
    http_response_code(400);
    echo json_encode(["error" => "File upload failed."]);
    exit;
}
*/
// Hash Password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert User Into Database
$userData = [
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'birthdate' => $birthdate,
    'password_hash' => $hashedPassword
    //'verification_document' => $verificationDocumentURL, // Stores file URL
    //'verification_status' => 'pending'
];
$userModel = new UserModel($conn);
$userCreated = $userModel->createUser($name, $email, $password, $phone, $birthdate);

if (!$userCreated) {
    http_response_code(500);
    echo json_encode(["error" => "User registration failed."]);
    exit;
}

// Success Response
http_response_code(201);
echo json_encode([
    "message" => "Registration successful!",
    "email" => $email,
    "result" => $userCreated
    // "verification_document_url" => $verificationDocumentURL
]);
exit;
