<?php
session_start();

include('config.php');

// Handle password reset request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset"])) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $token = $_POST["token"];
    $newPassword = $_POST["new_password"];

    // Validate email to have the domain @moe.gov.my
    $allowedDomain = "@moe.gov.my";
    if (strpos($email, $allowedDomain) === false) {
        echo "<script>alert('You must use an official email! $allowedDomain');</script>";
        exit();
    }

    // Check if the token and email are valid
    $stmt = $conn->prepare("SELECT * FROM tblpasswordreset WHERE email = ? AND token = ?");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, update the password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the staff's password in the tblstaff table
        $updateStmt = $conn->prepare("UPDATE tblstaff SET StaffPassword = ? WHERE StaffEmail = ?");
        $updateStmt->bind_param("ss", $hashedPassword, $email);

        if ($updateStmt->execute()) {
            // Password updated successfully
            echo "<script>alert('Password updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating password! " . $updateStmt->error . "');</script>";
        }

        $updateStmt->close();

        // Remove the used token from tblpasswordreset
        $deleteStmt = $conn->prepare("DELETE FROM tblpasswordreset WHERE email = ? AND token = ?");
        $deleteStmt->bind_param("ss", $email, $token);
        $deleteStmt->execute();
        $deleteStmt->close();
    } else {
        echo "<script>alert('Invalid token or email!');</script>";
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password</title>
    <!-- Include your CSS styles here -->
</head>
<body>
    <main>
        <div class="box">
            <div class="inner-box">
                <div class="forms-wrap">
                    <!-- Password Reset form -->
                    <form action="forgotpassword.php" method="post" autocomplete="off" class="password-reset-form">
                        <div class="logo">
                            <h4>e-Gerak</h4>
                        </div>

                        <div class="heading">
                            <h2>Reset Password</h2>
                            <h6>Enter your new password</h6>
                            <!-- Include a hidden input field to pass the token -->
                            <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? $_GET['token'] : ''; ?>" />
                            <a href="signin.php" class="toggle">Sign in</a>
                        </div>

                        <div class="actual-form">
                            <!-- Include your form fields for password reset here -->
                            <div class="input-wrap">
                                <input
                                    type="password"
                                    name="new_password"
                                    class="input-field"
                                    autocomplete="off"
                                    required
                                />
                                <label>New Password</label>
                            </div>

                            <input type="submit" name="reset" value="Reset Password" class="sign-btn" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Include your script tag or link to your JavaScript file -->
</body>
</html>
