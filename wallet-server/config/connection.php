<?php

// Set CORS Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Handle OPTIONS request (Preflight for CORS)
if ($_SERVER['REQUEST_METHOD'] === "OPTIONS") {
    http_response_code(200);
    exit;
}

// Database Connection Class
if (!class_exists('WalletDatabase')) {
    class WalletDatabase
    {
        private $host = "localhost";
        private $user = "root";
        private $password = "";
        private $dbname = "ewallet_db";
        private $conn;

        public function __construct()
        {
            //  Connect to MySQL first (without selecting a database)
            $this->conn = new mysqli($this->host, $this->user, $this->password);

            //  Check if connection to MySQL server works
            if ($this->conn->connect_error) {
                die(json_encode(["error" => "❌ MySQL connection failed: " . $this->conn->connect_error]));
            }

            //  Create Database if it doesn't exist
            $this->conn->query("CREATE DATABASE IF NOT EXISTS $this->dbname");

            //  Now select the database
            $this->conn->select_db($this->dbname);

            //  Check if the database selection was successful
            if ($this->conn->error) {
                die(json_encode(["error" => "❌ Database selection failed: " . $this->conn->error]));
            }
        }

        public function getConnection()
        {
            return $this->conn;
        }
    }
}

// Initialize Database Connection
$database = new WalletDatabase();
$conn = $database->getConnection();
