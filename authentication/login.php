<?php

session_start();
include_once '../dbConnection/config.php';

try {

    // Check connection
    if (!isset($conn)) {

        die("Connection failed: " . $conn);
    } else {

        // Check if the email address is NOT set in the session
        if (isset($_SESSION["sessionEmailAddress"])) {

            // Redirect to ../index.php if email address is not set
            header("Location: ../index.php");
            exit();
        } else {

            // Defind the empty variables
            $emailAddressEmpty = "";
            $passwordEmpty = "";

            // Defind the value set variables
            $emailAddress = "";
            $password = "";

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginBtn'])) {

                // Store the values from the form in variables and echo to display the stored values
                $emailAddress = $_POST['emailAddress'];
                $password = $_POST['password'];

                // Validate each field
                if (empty($_POST['emailAddress'])) {

                    $emailAddressEmpty = 'Please enter a valid email address!';
                } else {

                    // Check for specific domain ('gmail.com')
                    if (strpos($emailAddress, 'gmail.com') === false) {

                        $validationEmailAddress = "Invalid email domain! Please provide a valid email domain";
                    } else {

                        // Prepare SQL, SELECT STATEMENT and CHECK EMAIL EXISTS QUERY
                        $stmt = $conn->prepare("SELECT email_address, COUNT(email_address) as email_address_count FROM registration WHERE email_address = :emailAddress");
                        $stmt->bindParam(':emailAddress', $emailAddress);

                        $emailAddress = $_POST['emailAddress'];
                        $stmt->execute();

                        $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        $userEmail = $stmt->fetch();

                        $countUserEmail = $userEmail['email_address_count'];

                        if ($countUserEmail == 0) {

                            $validationEmailAddressIsNotAlreadyExists = "No such email address was found! Please provide a valid email address";
                        }
                    }
                }

                if (empty($_POST['password'])) {

                    $passwordEmpty = 'Please enter a valid password!';
                } else {

                    // Prepare SQL, SELECT STATEMENT and CHECK USER EXISTS QUERY
                    $stmt = $conn->prepare("SELECT email_address, password FROM registration WHERE email_address = :emailAddress");
                    $stmt->bindParam(':emailAddress', $emailAddress);

                    $emailAddress = $_POST['emailAddress'];
                    $stmt->execute();

                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $user = $stmt->fetch();

                    $countUser = $stmt->rowCount();

                    if ($countUser == 1) {

                        if ($user['password'] == md5($_POST['password'])) {

                            // Perform registration logic, for example, store the email in the session
                            $_SESSION['sessionEmailAddress'] = $_POST['emailAddress'];

                            // Redirect to ../index.php
                            header("Location: ../index.php");
                            exit();
                        } else {

                            $validationPasswordIsNotMatchWithEmailAddress = 'This password does not match the email address you provided!';
                        }
                    }
                }
            }
        }
    }
} catch (PDOException $e) {

    echo "Error: " . $e->getMessage();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    <title>Login-Form</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/icons/page-icon.png" type="image/x-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/plugins/bootstrap-5.3.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free-6.4.2-web/css/all.min.css">
</head>

<body>

    <!-- Navbar Included Start -->
    <?php include_once '../templates/navbar.php'; ?>
    <!-- Navbar Included End -->

    <div class="container-lg">

        <h3 class="title-txt">User <span class="text-warning">Login</span> Form</h3>
        <div class="row mt-4">

            <div class="col-lg-6">
                <!-- Login Banner -->
                <img class="img-fluid rounded-3 mx-auto d-block" src="../assets/images/login-banner.jpg">
                <a class="float-end mt-2" href="registration.php">I don't have a account</a>
            </div>

            <div class="col-lg-6">
                <!-- Login Form -->
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label><i class="fas fa-envelope"></i> Email Address</label>
                            <div>
                                <input type="email" name="emailAddress" value="<?php echo $emailAddress; ?>" placeholder="Enter your email address">
                                <span class="errMsg"><?php echo $emailAddressEmpty; ?></span>
                                <span class="errMsg">
                                    <?php
                                    if (isset($validationEmailAddress)) {

                                        echo $validationEmailAddress;
                                    }

                                    if (isset($validationEmailAddressIsNotAlreadyExists)) {

                                        echo $validationEmailAddressIsNotAlreadyExists;
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label><i class="fas fa-lock"></i> Confirm Password</label>
                            <div>
                                <input type="password" name="password" value="<?php echo $password; ?>" placeholder="Enter your password">
                                <span class="errMsg"><?php echo $passwordEmpty; ?></span>
                                <span class="errMsg">
                                    <?php
                                    if (isset($validationPasswordIsNotMatchWithEmailAddress)) {

                                        echo $validationPasswordIsNotMatchWithEmailAddress;
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <div class="row mb-3">
                        <div class="col-lg-12 text-end">
                            <button class="registerBtn" type="submit" name="loginBtn">Login</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>

</html>