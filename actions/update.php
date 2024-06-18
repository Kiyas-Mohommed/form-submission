<?php
session_start();

include_once '../dbConnection/config.php';

try {

    // Check connection
    if (!isset($conn)) {

        die("Connection failed: " . $conn);
    } else {

        // Prepare SQL and UPDATE recode
        $stmt = $conn->prepare("UPDATE registration SET first_name = :bindedFirstName, last_name = :bindedLastName, email_address = :bindedEmailAddress, password = :bindedConfirmPassword, phone_number = :bindedPhoneNumber, home_address = :bindedHomeAddress, gender = :bindedGender, city = :bindedCity, postal_code = :bindedPostalCode WHERE user_id = :userID");

        // Bind Parameters
        $stmt->bindParam(':bindedFirstName', $firstName);
        $stmt->bindParam(':bindedLastName', $lastName);
        $stmt->bindParam(':bindedEmailAddress', $emailAddress);
        $stmt->bindParam(':bindedConfirmPassword', $confirmPassword);
        $stmt->bindParam(':bindedPhoneNumber', $phoneNumber);
        $stmt->bindParam(':bindedHomeAddress', $homeAddress);
        $stmt->bindParam(':bindedGender', $gender);
        $stmt->bindParam(':bindedCity', $city);
        $stmt->bindParam(':bindedPostalCode', $postalCode);
        $stmt->bindParam(':userID', $userId, PDO::PARAM_INT);

        // Assign values to variables
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $emailAddress = trim(strtolower($_POST['emailAddress']));
        $confirmPassword = md5($_POST['confirmPassword']);
        $phoneNumber = $_POST['phoneNumber'];
        $homeAddress = trim(strtolower($_POST['homeAddress']));
        $gender = $_POST['gender'];
        $city = $_POST['city'];
        $postalCode = $_POST['postalCode'];
        $userId = $_POST['userId'];
        $stmt->execute();

        session_destroy();

        // Redirect to ../authentication/login.php
        header("Location: ../authentication/login.php");
        exit();
    }
} catch (PDOException $e) {

    echo "Error: " . $e->getMessage();
}
