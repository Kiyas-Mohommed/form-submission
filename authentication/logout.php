<?php

session_start();
include_once '../dbConnection/config.php';

try {

    // Check connection
    if (!isset($conn)) {

        die("Connection failed: " . $conn);
    } else {

        unset($_SESSION['sessionEmailAddress']);
        session_destroy();

        // Redirect to login.php
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {

    echo "Error: " . $e->getMessage();
}
