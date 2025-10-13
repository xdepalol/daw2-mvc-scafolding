<?php

use Dom\Mysql;

class Database
{
    public static function connect($hostname = "localhost", $username = "laligauser", $password = "2obmv2uqZj3pxx", $database = "laliga"){
        // Create connection
        $conn = new mysqli($hostname, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }
}
