<?php

require_once(__DIR__ . '/../config/connection.php');
require_once(__DIR__ . '/../models/models.php');

class UserModel extends BaseModel
{
    public function createUser($name, $email, $password, $phone, $birthdate)
    {
        // Validate Inputs
        $errors = [];
        if (!$this->validateName($name)) $errors[] = "Invalid name (2-100 characters)";
        if (!$this->validateEmail($email)) $errors[] = "Invalid email format";
        if ($this->exists('users', 'email', $email)) $errors[] = "Email already exists";
        if (!$this->validatePassword($password)) $errors[] = "Password must be 8+ chars with 1 uppercase and 1 number";
        if (!$this->validatePhone($phone)) $errors[] = "Invalid phone format";

        if (!empty($errors)) return ["errors" => $errors];

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Create User
        $data = [
            'name' => $name,
            'email' => $email,
            'password_hash' => $hashedPassword,
            'phone' => $phone,
            'birthdate' => date('Y-m-d', strtotime($birthdate)),
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->create('users', $data)
            ? ["success" => "Registration successful"]
            : ["errors" => ["Database error"]];
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT id, name, email, password_hash FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Return user data
    }

    public function getUserById($userId)
    {
        $stmt = $this->conn->prepare("SELECT name, email, phone, birthdate FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function updateUserProfile($userId, $name, $phone, $birthdate)
    {
        // Prepare SQL statement to update user details
        $stmt = $this->conn->prepare("UPDATE users SET name = ?, phone = ?, birthdate = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $phone, $birthdate, $userId);

        // Execute query and return success/failure
        return $stmt->execute();
    }

    /* public function resetPassword($token, $newPassword)
    {
        // Verify if the token exists
        $stmt = $this->conn->prepare("SELECT email FROM password_resets WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return false; // Token is invalid or expired
        }

        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update the user's password
        $stmt = $this->conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        $stmt->execute();

        // Delete the reset token after successful reset
        $stmt = $this->conn->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        return true;
    }
*/

    public function emailExists($email)
    {
        return $this->exists('users', 'email', $email);
    }

    public function phoneExists($phone)
    {
        return $this->exists('users', 'phone', $phone);
    }

    private function validateName($name)
    {
        return preg_match('/^[a-zA-Z ]{2,100}$/', $name);
    }

    private function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function validatePassword($password)
    {
        return strlen($password) >= 8
            && preg_match('/[A-Z]/', $password)
            && preg_match('/[0-9]/', $password);
    }

    private function validatePhone($phone)
    {
        $clean = preg_replace('/[^0-9+]/', '', $phone);
        return preg_match('/^\+?[0-9]{8,15}$/', $clean);
    }
}
