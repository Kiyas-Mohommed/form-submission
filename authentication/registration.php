<?php
session_start();

// Database connection
include_once '../dbConnection/config.php';

try {

    // Check connection
    if (!isset($conn)) {

        die("Connection failed: " . $conn);
    } else {

        // Defind the empty variables
        $firstNameEmpty = "";
        $lastNameEmpty = "";
        $emailAddressEmpty = "";
        $passwordEmpty = "";
        $confirmPasswordEmpty = "";
        $phoneNumberEmpty = "";
        $homeAddressEmpty = "";
        $postalCodeEmpty = "";

        // Defind the value set variables
        $firstName = "";
        $lastName = "";
        $emailAddress = "";
        $password = "";
        $confirmPassword = "";
        $phoneNumber = "";
        $homeAddress = "";
        $gender = "";
        $city = "";
        $postalCode = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registerBtn'])) {

            // Store the values from the form in variables and echo to display the stored values
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $emailAddress = $_POST['emailAddress'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];
            $phoneNumber = $_POST['phoneNumber'];
            $homeAddress = $_POST['homeAddress'];
            $gender = $_POST['gender'];
            $city = $_POST['city'];
            $postalCode = $_POST['postalCode'];

            // Validate each field
            if (empty($_POST['firstName'])) {

                $firstNameEmpty = 'Please enter a valid first name!';
            } else {

                if (!preg_match("/^[A-Z][a-z]*$/", $firstName)) {

                    $validationFirstName = "First letter must be capitalized, and only alphabets are allowed in the first name";
                }
            }

            if (empty($_POST['lastName'])) {

                $lastNameEmpty = 'Please enter a valid last name!';
            } else {

                if (!preg_match("/^[A-Z][a-z]*$/", $lastName)) {

                    $validationLastName = "First letter must be capitalized, and only alphabets are allowed in the last name";
                }
            }

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

                    if ($countUserEmail > 0) {

                        $validationEmailAddressIsAlreadyExists = "This email is already registered! Please use a different email";
                    }
                }
            }

            if (empty($_POST['password'])) {

                $passwordEmpty = 'Please enter a valid password!';
            } else {

                if (strlen($_POST["password"]) <= '7') {

                    $validationPassword = "Your Password Must Contain At Least 8 Characters!";
                } elseif (!preg_match("#[0-9]+#", $password)) {

                    $validationPassword = "Your Password Must Contain At Least 1 Number!";
                } elseif (!preg_match("#[A-Z]+#", $password)) {

                    $validationPassword = "Your Password Must Contain At Least 1 Capital Letter!";
                } elseif (!preg_match("#[a-z]+#", $password)) {

                    $validationPassword = "Your Password Must Contain At Least 1 Lowercase Letter!";
                }
            }

            if (empty($_POST['confirmPassword'])) {

                $confirmPasswordEmpty = 'Please enter a valid confirm password!';
            } else {

                if ($_POST['password'] !== $_POST['confirmPassword']) {

                    $doNotMatch = "Password do not Match! Please enter the same password";
                }
            }

            if (empty($_POST['phoneNumber'])) {

                $phoneNumberEmpty = 'Please enter a valid mobile number!';
            } else {

                // Check for specific startup phone no ('07')                
                if (substr($phoneNumber, 0, 1) !== '0' || substr($phoneNumber, 1, 1) !== '7') {

                    $validationPhoneNumber = "Invalid startup phone no! Please provide a valid startup (07) phone no";
                } else if (strlen($_POST["phoneNumber"]) <=> '10') {

                    $validationPhoneNumber = "Your phone number Must Contain 10 Numbers!";
                } else {

                    // Prepare SQL, SELECT STATEMENT and CHECK PHONENUMBER EXISTS QUERY
                    $stmt = $conn->prepare("SELECT phone_number, COUNT(phone_number) as phone_number_count FROM registration WHERE phone_number = :phoneNumber");
                    $stmt->bindParam(':phoneNumber', $phoneNumber);

                    $phoneNumber = $_POST['phoneNumber'];
                    $stmt->execute();

                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $userPhoneNumber = $stmt->fetch();

                    $countPhoneNumber = $userPhoneNumber['phone_number_count'];

                    if ($countPhoneNumber > 0) {

                        $validationPhoneNumberIsAlreadyExists = "This phone number is already registered! Please use a different phone number";
                    }
                }
            }

            if (empty($_POST['homeAddress'])) {

                $homeAddressEmpty = 'Please enter a valid home address!';
            } else {

                if (!preg_match("/^[A-Za-z0-9\s\/,]{1,60}$/", $homeAddress)) {

                    $validationHomeAddress = "Invalid characters in the home address";
                } else if (strlen($_POST['homeAddress']) > '60') {

                    $validationHomeAddress = "The home address is too long! Please limit it to 60 characters or fewer";
                }
            }

            if (empty($_POST['postalCode'])) {

                $postalCodeEmpty = 'Please enter a valid postal code!';
            } else {

                if (strlen($_POST["postalCode"]) <=> '5') {

                    $validationPostalCode = "Your Postal Code Must Contain 5 Numbers!";
                } else {

                    // Prepare SQL and INSERT recode
                    $stmt = $conn->prepare("INSERT INTO registration (first_name, last_name, email_address, password, phone_number, home_address, gender, city, postal_code)
                    VALUES (:bindedFirstName, :bindedLastName, :bindedEmailAddress, :bindedConfirmPassword, :bindedPhoneNumber, :bindedHomeAddress, :bindedGender, :bindedCity, :bindedPostalCode)");

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
                    $stmt->execute();

                    // Perform registration logic, for example, store the email in the session
                    $_SESSION['sessionEmailAddress'] = $_POST['emailAddress'];

                    // Redirect to ../index.php
                    header("Location: ../index.php");
                    exit();
                }
            }
        }
    }
} catch (PDOException $e) {

    echo "Error: " . $e->getMessage();
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    <title>Registration-Form</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/icons/page-icon.png" type="image/x-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/plugins/bootstrap-5.3.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free-6.4.2-web/css/all.min.css">
</head>

<body>

    <!-- Navbar Included Start -->
    <?php include_once '../templates/navbar.php'; ?>
    <!-- Navbar Included End -->

    <div class="container-lg">

        <h3 class="title-txt">User <span class="text-warning">Registration</span> Form</h3>
        <div class="row mt-4">

            <div class="col-lg-6">
                <!-- Registration Banner -->
                <img class="img-fluid rounded-3 mx-auto d-block" src="../assets/images/registration-banner.jpg">
                <a class="float-end mt-2" href="login.php">I have an account</a>
            </div>

            <div class="col-lg-6">
                <!-- Registration Form -->
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label><i class="fas fa-user"></i> First Name</label>
                            <div>
                                <input type="text" name="firstName" value="<?php echo $firstName; ?>" placeholder="Enter your first name">
                                <span class="errMsg"><?php echo $firstNameEmpty; ?></span>
                                <span class="errMsg">
                                    <?php if (isset($validationFirstName)) {

                                        echo $validationFirstName;
                                    } ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label><i class="fas fa-user"></i> Last Name</label>
                            <div>
                                <input type="text" name="lastName" value="<?php echo $lastName; ?>" placeholder="Enter your last name">
                                <span class="errMsg"><?php echo $lastNameEmpty; ?></span>
                                <span class="errMsg">
                                    <?php if (isset($validationLastName)) {

                                        echo $validationLastName;
                                    } ?>
                                </span>
                            </div>
                        </div>
                    </div>

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

                                    if (isset($validationEmailAddressIsAlreadyExists)) {

                                        echo $validationEmailAddressIsAlreadyExists;
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label><i class="fas fa-lock"></i> Password</label>
                            <div>
                                <input type="password" name="password" value="<?php echo $password; ?>" placeholder="Enter your password">
                                <span class="errMsg"><?php echo $passwordEmpty; ?></span>
                                <span class="errMsg">
                                    <?php if (isset($validationPassword)) {

                                        echo $validationPassword;
                                    } ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label><i class="fas fa-lock"></i> Confirm Password</label>
                            <div>
                                <input type="password" name="confirmPassword" value="<?php echo $confirmPassword; ?>" placeholder="Enter your confirm password">
                                <span class="errMsg"><?php echo $confirmPasswordEmpty; ?></span>
                                <span class="errMsg">
                                    <?php if (isset($doNotMatch)) {

                                        echo $doNotMatch;
                                    } ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label><i class="fas fa-mobile"></i> Phone Number</label>
                            <div>
                                <input type="number" name="phoneNumber" maxlength="10" value="<?php echo $phoneNumber; ?>" placeholder="Enter your phone number">
                                <span class="errMsg"><?php echo $phoneNumberEmpty; ?></span>
                                <span class="errMsg">
                                    <?php
                                    if (isset($validationPhoneNumber)) {

                                        echo $validationPhoneNumber;
                                    }

                                    if (isset($validationPhoneNumberIsAlreadyExists)) {

                                        echo $validationPhoneNumberIsAlreadyExists;
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label><i class="fas fa-home"></i> Home Address</label>
                            <div>
                                <input type="text" name="homeAddress" value="<?php echo $homeAddress; ?>" placeholder="Enter your home address">
                                <span class="errMsg"><?php echo $homeAddressEmpty; ?></span>
                                <span class="errMsg">
                                    <?php if (isset($validationHomeAddress)) {

                                        echo $validationHomeAddress;
                                    } ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label><i class="fas fa-genderless"></i> Gender</label>
                            <div class="select">
                                <select name="gender">
                                    <option>Select your gender</option>
                                    <option selected value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label><i class="fas fa-city"></i> City</label>
                            <div class="select">
                                <select name="city">
                                    <option>Select your city</option>
                                    <option selected value="puttalam">Puttalam</option>
                                    <option value="negombo">Negombo</option>
                                    <option value="colombo">Colombo</option>
                                    <option value="jaffna">Jaffna</option>
                                    <option value="kandy">Kandy</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label><i class="fas fa-mail-bulk"></i> Postal Code</label>
                            <div>
                                <input type="number" name="postalCode" maxlength="5" value="<?php echo $postalCode; ?>" placeholder="Enter your postal code">
                                <span class="errMsg"><?php echo $postalCodeEmpty; ?></span>
                                <span class="errMsg">
                                    <?php if (isset($validationPostalCode)) {

                                        echo $validationPostalCode;
                                    } ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Button -->
                    <div class="row mb-3">
                        <div class="col-lg-12 text-end">
                            <button class="registerBtn" type="submit" name="registerBtn">Register</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>