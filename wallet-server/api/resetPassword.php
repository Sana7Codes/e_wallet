<?

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../models/users.php';

$token = $_POST['token'] ?? null;
$newPassword = $_POST['new_password'] ?? null;

if (!$token || !$newPassword) {
    http_response_code(400);
    echo json_encode(["error" => "Token and new password are required."]);
    exit;
}

/*$userModel = new UserModel($conn);
if ($userModel->resetPassword($token, $newPassword)) {
    echo json_encode(["message" => "Password reset successful."]);
} else {
    echo json_encode(["error" => "Invalid or expired token."]);
}
exit;
