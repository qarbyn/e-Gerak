<?php
session_start();

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminsignin.php");
    exit();
}

include('../config.php'); // Adjust the path if needed

// Handle staff deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_staff"])) {
    $selectedStaffId = $_POST["selected_staff"];

    // Delete movement records associated with the staff
    $deleteMovementQuery = "DELETE FROM tblmovement WHERE staff_id = $selectedStaffId";
    $deleteMovementResult = $conn->query($deleteMovementQuery);

    if (!$deleteMovementResult) {
        echo '<script>alert("Error deleting movement records: ' . $conn->error . '");</script>';
    }

    // Delete the staff from the database
    $deleteStaffQuery = "DELETE FROM tblstaff WHERE staff_id = $selectedStaffId";
    $deleteStaffResult = $conn->query($deleteStaffQuery);

    if ($deleteStaffResult) {
        echo '<script>alert("Staff deleted successfully!");</script>';
    } else {
        echo '<script>alert("Error deleting staff: ' . $conn->error . '");</script>';
    }
}

// Retrieve staff details for the table
$staffDetailsQuery = "SELECT staff_id, StaffFullName, StaffNickname, StaffGender, StaffPhoneNo FROM tblstaff";
$staffDetailsResult = $conn->query($staffDetailsQuery);

if (!$staffDetailsResult) {
    // Display JavaScript alert for connection or retrieval error
    echo '<script>alert("Error retrieving staff details: ' . $conn->error . '");</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Delete Staff</title>
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
            border-bottom: 1px solid #ddd;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4a7bed;
            color: #fff;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        form {
            margin-top: 20px;
        }

        input[type="checkbox"] {
            margin: 0; /* Center checkbox in column */
            display: block; /* Center checkbox in column */
            margin: auto; /* Center checkbox in column */
        }

        input[type="submit"] {
            background-color: #e74c3c; /* Red color */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block; /* Center button in column */
            margin: 10px auto; /* Center button in column */
        }

        input[type="submit"]:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <main>
        <h2>Delete Staff</h2>
        <?php include('includeadmin/leftnavbar.php'); ?>
        
        <form action="admindeletestaff.php" method="POST" onsubmit="return confirmDeleteStaff();">
            <table>
                <tr>
                    <th>Select</th>
                    <th>Full Name</th>
                    <th>Nickname</th>
                    <th>Gender</th>
                    <th>Phone Number</th>
                    <th>Action</th>
                </tr>
                <?php
                while ($staffRow = $staffDetailsResult->fetch_assoc()) {
                    echo "<tr>
                            <td><input type=\"checkbox\" name=\"selected_staff\" value=\"{$staffRow['staff_id']}\"></td>
                            <td>{$staffRow['StaffFullName']}</td>
                            <td>{$staffRow['StaffNickname']}</td>
                            <td>{$staffRow['StaffGender']}</td>
                            <td>{$staffRow['StaffPhoneNo']}</td>
                            <td><input type=\"submit\" value=\"Delete\" name=\"delete_staff\"></td>
                          </tr>";
                }
                ?>
            </table>
        </form>
    </main>

    <!-- Your JavaScript code here -->
    <script>
        function confirmDeleteStaff() {
            var confirmDelete = confirm("Are you sure you want to delete this staff?");
            return confirmDelete;
        }
    </script>
</body>
</html>
