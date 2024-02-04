<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url=signin.php">
    <title>Logout</title>
    <script>
        window.onload = function() {
            alert("You have been logged out!");
        };
    </script>
</head>
<body>
    <p>If you are not redirected, <a href="signin.php">click here</a>.</p>
</body>
</html>
