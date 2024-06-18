<?php
session_start();

include_once 'dbConnection/config.php';

try {

    // Check connection
    if (!isset($conn)) {

        die("Connection failed: " . $conn);
    } else {

        // Check if the email address is NOT set in the session
        if (!isset($_SESSION["sessionEmailAddress"])) {

            // Redirect to authentication/login.php if email address is not set
            header("Location: authentication/login.php");
            exit();
        } else {

            $stmt = $conn->prepare("SELECT * FROM registration ORDER BY user_id DESC");
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $users = $stmt->fetchAll();
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
    <title>Dashboard-Form</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/icons/page-icon.png" type="image/x-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/plugins/bootstrap-5.3.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome-free-6.4.2-web/css/all.min.css">
</head>

<body>

    <div class="container-lg mt-3">
        <div class="row">
            <!-- Navbar -->
            <nav class="navbar bg-white rounded-3 ps-2 pe-2">
                <!-- Logo & Text -->
                <a class="navbar-brand" href="#">
                    <img src="assets/icons/page-icon.png" width="30" height="30" class="d-inline-block">
                    <span class="align-middle">Registration</span>
                </a>

                <!-- Logout Button -->
                <span class="ms-auto pe-3">
                    <a class="logoutBtn" href="authentication/logout.php?emailAddress=<?php echo $_SESSION['sessionEmailAddress']; ?>">Logout</a>
                </span>

                <!-- Dark & Light Mode -->
                <div class="toggle-btn" id="themeBtn">
                    <span id="themeTxt">Dark</span>
                    <img id="themeImg" class="img-logo" src="assets/icons/dark-mode.png">
                </div>
            </nav>
        </div>

        <div class="row">
            <div class="col-lg-12">

                <table class="mt-5">
                    <tr align="center">
                        <th class="tbHeader">ID</th>
                        <th class="tbHeader">First Name</th>
                        <th class="tbHeader">Last Name</th>
                        <th class="tbHeader">Email</th>
                        <th class="tbHeader">Phone No</th>
                        <th class="tbHeader">Address</th>
                        <th class="tbHeader">Gender</th>
                        <th class="tbHeader">City</th>
                        <th class="tbHeader">Postal Code</th>

                        <th class="tbHeader" colspan="2">Created & Updated</th>
                        <th class="tbHeader" colspan="2">Action</th>
                    </tr>

                    <?php
                    foreach ($users as $user) {
                    ?>

                        <tr align="center" class="trHover">

                            <td class="tbData"><?php echo $user['user_id']; ?></td>
                            <td class="tbData"><?php echo $user['first_name']; ?></td>
                            <td class="tbData"><?php echo $user['last_name']; ?></td>
                            <td class="tbData"><?php echo substr($user['email_address'], 0, 16); ?></td>
                            <td class="tbData"><?php echo $user['phone_number']; ?></td>
                            <td class="tbData"><?php echo substr($user['home_address'], 0, 20); ?></td>
                            <td class="tbData"><?php echo $user['gender']; ?></td>
                            <td class="tbData"><?php echo $user['city']; ?></td>
                            <td class="tbData"><?php echo $user['postal_code']; ?></td>

                            <td class="tbData"><?php echo str_replace('-', '/', $user['created_at']); ?></td>
                            <td class="tbData"><?php echo str_replace('-', '/', $user['updated_at']); ?></td>

                            <td class="tbData">
                                <a class="editBtn" href="actions/edit.php?id=<?php echo $user['user_id']; ?>">Edit</a>
                            </td>

                            <td class="tbData">
                                <a class="deleteBtn" href="actions/trash.php?id=<?php echo $user['user_id']; ?>">Trash</a>
                            </td>
                        </tr>

                    <?php
                    }
                    ?>

                </table>

            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="assets/js/script.js"></script>
</body>

</html>