<?php
session_start();

include_once '../dbConnection/config.php';

try {

    // Check connection
    if (!isset($conn)) {

        die("Connection failed: " . $conn);
    } else {

        $stmt = $conn->prepare("DELETE FROM registration WHERE user_id = :userID");
        $stmt->bindParam(':userID', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();

        unset($_SESSION['sessionEmailAddress']);
        session_destroy();

        // Redirect to ../authentication/login.php
        header("Location: ../authentication/login.php");
        exit();
    }
} catch (PDOException $e) {

    echo "Error: " . $e->getMessage();
}

$conn = null;
