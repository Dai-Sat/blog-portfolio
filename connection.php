<?php

    function connection() {
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'blog';

        // pre difined object that allows php to communicate with mysql
        $conn = new mysqli($servername, $username, $password, $database);

        // validation
        if($conn->connect_error){
            die("Connection failed: ".$conn->connect_error);
        } else {
            return $conn;
        }

    }

?>