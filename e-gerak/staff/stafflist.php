<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

include('../config.php'); // Adjust the path if needed

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve staff details from the database
$sql = "SELECT * FROM tblstaff";
$result = $conn->query($sql);

if ($result) {
    // Display JavaScript alert for successful retrieval
} else {
    // Display JavaScript alert for connection or retrieval error
    echo '<script>alert("Error: ' . $conn->error . '");</script>';
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <title>Staff List</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd; /* Added border property */
        }

        th {
            background-color: #4a7bed;
            color: #fff;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <?php include('includestaff\staffheader.php'); ?>
    <?php include('includestaff/leftnavbar.php'); ?>
    <main>
        <h2>Staff List</h2>
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Nickname</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through each record and display in the table
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . (isset($row['StaffFullName']) ? $row['StaffFullName'] : '') . "</td>";
                    echo "<td>" . (isset($row['StaffNickname']) ? $row['StaffNickname'] : '') . "</td>";
                    echo "<td>" . (isset($row['StaffEmail']) ? $row['StaffEmail'] : '') . "</td>";
                    echo "<td>" . (isset($row['StaffGender']) ? $row['StaffGender'] : '') . "</td>";
                    echo "<td>" . (isset($row['StaffDOB']) ? $row['StaffDOB'] : '') . "</td>";
                    echo "<td>" . (isset($row['StaffAddress']) ? $row['StaffAddress'] : '') . "</td>";
                    echo "<td>" . (isset($row['StaffPhoneNo']) ? $row['StaffPhoneNo'] : '') . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>
