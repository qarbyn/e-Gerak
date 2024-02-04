<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminsignin.php");
    exit();
}

include('../config.php'); // Adjust the path if needed

// Initialize variables
$full_name = $nickname = $email = $gender = $dob = $address = $phone_no = "";
$password = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_profile"])) {
    $full_name = strtoupper($_POST["full_name"]); // Capitalize full name
    $nickname = strtoupper($_POST["nickname"]);   // Capitalize nickname
    $email = $_POST["email"];
    $gender = isset($_POST["gender"]) ? strtoupper($_POST["gender"]) : ''; // Check if the "gender" key is set in $_POST
    $dob = $_POST["dob"];
    $address = strtoupper($_POST["address"]);     // Capitalize address
    $phone_no = $_POST["phone_no"];
    $password = $_POST["password"];

    // Validate email format and domain
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@moe\.gov\.my$/', $email)) {
        echo '<script>alert("Invalid email format or domain. Email must end with @moe.gov.my");</script>';
        exit();
    }

    // Validate password for symbol and number
    if (!empty($password) && !preg_match('/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,}$/', $password)) {
        echo '<script>alert("Password must contain at least one symbol and one number.");</script>';
        exit();
    }

    // Perform the SQL insertion
    $stmt = $conn->prepare("INSERT INTO tblstaff (StaffFullName, StaffNickname, StaffEmail, StaffGender, StaffDOB, StaffAddress, StaffPhoneNo, StaffPassword) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("ssssssss", $full_name, $nickname, $email, $gender, $dob, $address, $phone_no, $hashed_password);

    // Execute the insertion statement
    if ($stmt->execute()) {
        echo '<script>alert("Staff added successfully!");</script>';
    } else {
        echo '<script>alert("Error adding staff: ' . $stmt->error . '");</script>';
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff</title>
    <!-- Add your stylesheet link here -->
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

        body {
            background-color: #c6e2e9;
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
        }

        main {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #4a7bed;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
            border-radius: 4px;
        }

        input[type="button"],
        input[type="submit"] {
            background-color: #4a7bed;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="button"]:hover,
        input[type="submit"]:hover {
            background-color: #3564ad;
        }
    </style>
</head>
<body>
    <main>
        <h2>Add New Staff</h2>
        <?php include('includeadmin/leftnavbar.php'); ?>
        <form action="adminaddstaff.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo $full_name; ?>">

            <label for="nickname">Nickname:</label>
            <input type="text" id="nickname" name="nickname" value="<?php echo $nickname; ?>">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>">

            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="MALE">MALE</option>
                <option value="FEMALE">FEMALE</option>
            </select>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>">

            <label for="address">Address:</label>
            <input id="address" name="address" value="<?php echo $address; ?>">

            <label for="phone_no">Phone Number:</label>
            <input type="tel" id="phone_no" name="phone_no" value="<?php echo $phone_no; ?>">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" minlength="6">

            <input type="submit" value="Add Staff" name="submit_profile">
        </form>
    </main>

    <!-- Your JavaScript code here -->
    <script>
        function validateForm() {
            var fullName = document.getElementById('full_name').value;
            var nickname = document.getElementById('nickname').value;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;

            // Capitalize full name, nickname, and gender
            document.getElementById('full_name').value = fullName.toUpperCase();
            document.getElementById('nickname').value = nickname.toUpperCase();

            // Validate email format and domain
            if (!email.match(/[a-zA-Z0-9._%+-]+@moe\.gov\.my$/)) {
                alert('Invalid email format or domain. Email must end with @moe.gov.my');
                return false;
            }

            // Validate password for symbol and number
            if (password !== '' && !password.match(/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,}$/)) {
                alert('Password must contain at least one symbol and one number.');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
