<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Ensure Composer autoload is included

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Define a secret key for signing tokens
define("JWT_SECRET_KEY", "your_secret_key_here");
