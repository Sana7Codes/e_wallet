<?php
/* require 'config.php';

/*use Firebase\JWT\JWT;

function generateJWT($userId)
{
    $payload = [
        'iat' => time(),
        'exp' => time() + (60 * 60),
        'sub' => $userId
    ];
    return JWT::encode($payload, JWT_SECRET, 'HS256');
}

function validateJWT($token)
{
    try {
        return JWT::decode($token, JWT_SECRET, ['HS256']);
    } catch (Exception $e) {
        return false;
    }
}
