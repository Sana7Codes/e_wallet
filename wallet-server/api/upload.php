<?php

/**require 'auth.php';
require 'DocumentModel.php';

header('Content-Type: application/json');

$token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$decoded = validateJWT(str_replace('Bearer ', '', $token));

if (!$decoded) {
    http_response_code(401);
    exit(json_encode(['error' => 'Unauthorized']));
}

$documentModel = new DocumentModel();
$documentModel->create([
    'user_id' => $decoded->sub,
    'document_path' => saveUploadedFile($_FILES['document'])
]);

// Simulate verification process
sleep(2);
$documentModel->update($documentId, ['status' => 'approved']);

function saveUploadedFile($file) {
    // Implement secure file upload logic
    return 'uploads/' . basename($file['name']);
}
?>
