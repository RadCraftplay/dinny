<?php

include 'Config.php';

class Database
{
    private $username;
    private $password;
    private $host;
    private $database;

    private static $instance;

    private function __construct()
    {
        $CONFIG = Config::getConfig();
        $this->username = $CONFIG["DB_USER"];
        $this->password = $CONFIG["DB_PASSWORD"];
        $this->host = $CONFIG["DB_HOST"];
        $this->database = $CONFIG["DB_NAME"];
    }

    public static function GetInstance(): Database {
        if (self::$instance == null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function connect()
    {
        try {
            $conn = new PDO("pgsql:host=$this->host;port=6543;dbname=$this->database",
                $this->username,
                $this->password,
                ["sslmode" => "disabled"]
            );
        } catch (PDOException $ex) {
            die("Connection failed: " . $ex);
        }

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
}