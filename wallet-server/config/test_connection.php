<?php
require_once "connection.php";

// global $conn
global $conn; 

if (!isset($conn)) {
    die("❌ Connection variable is still not set!");
}

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
} else {
    echo "✅ Connected successfully to database!";
}

