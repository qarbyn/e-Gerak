<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminsignin.php");
    exit();
}

include('..\config.php'); // Adjust the path if needed

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve staff names for dropdown
$staffNamesQuery = "SELECT DISTINCT StaffFullName FROM tblmovement";
$staffNamesResult = $conn->query($staffNamesQuery);

if ($staffNamesResult) {
    // Display JavaScript alert for successful retrieval of staff names
    
} else {
    // Display JavaScript alert for connection or retrieval error
    echo '<script>alert("Error retrieving staff names: ' . $conn->error . '");</script>';
}

// Retrieve staff movement records from the database with the latest Start Date at the top
$sql = "SELECT * FROM tblmovement ORDER BY StartDate DESC";
$result = $conn->query($sql);

if ($result) {
    // Display JavaScript alert for successful retrieval of movement records
} else {
    // Display JavaScript alert for connection or retrieval error
    echo '<script>alert("Error retrieving movement records: ' . $conn->error . '");</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <title>Admin Dashboard</title>
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
            text-align: center; /* Align content in the center */
        }

        h2 {
        color: #4a7bed;
        margin-bottom: 10px; /* Add margin bottom to create space between h2 and dropdown */
        }

        /* Additional styles for the dropdown */
        .dropdown {
        display: inline-block;
        margin-bottom: 15px;
        margin-top: 10px;
        }

        /* Styling for dropdown */
        select {
            padding: 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #4a7bed;
            color: white;
        }

        select:hover {
            background-color: #3564ad;
        }

        /* Styling for the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
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
    <?php include('includeadmin\leftnavbar.php'); ?>
    <main>
        <h2>Staff Movement Records</h2>
        
        <div class="dropdown">
            <label for="staffDropdown">Select Staff:</label>
            <select id="staffDropdown" onchange="filterStaff()">
                <option value="">All Staff</option>
                <?php
                while ($staffRow = $staffNamesResult->fetch_assoc()) {
                    echo "<option value=\"" . $staffRow['StaffFullName'] . "\">" . $staffRow['StaffFullName'] . "</option>";
                }
                ?>
            </select>
        </div>

        <table border="1">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Place</th>
                    <th>Program</th>
                    <th>Movement Picture</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through each record and display in the table
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    
                    // Check if the keys exist before using them
                    echo "<td>" . (isset($row['StaffFullName']) ? $row['StaffFullName'] : '') . "</td>";
                    echo "<td>" . (isset($row['StartDate']) ? $row['StartDate'] : '') . "</td>";
                    echo "<td>" . (isset($row['EndDate']) ? $row['EndDate'] : '') . "</td>";
                    echo "<td>" . (isset($row['Place']) ? $row['Place'] : '') . "</td>";
                    echo "<td>" . (isset($row['Program']) ? $row['Program'] : '') . "</td>";
                    echo "<td><img src='../staff/includestaff/uploads/" . (isset($row['MovementPicture']) ? $row['MovementPicture'] : '') . "' alt='Movement Picture' style='max-width: 100px;'></td>";

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <script>
        function filterStaff() {
            var selectedStaff = document.getElementById("staffDropdown").value;
            var table = document.querySelector("table");
            var rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

            for (var i = 0; i < rows.length; i++) {
                var fullNameColumn = rows[i].getElementsByTagName("td")[0];
                if (selectedStaff === "" || fullNameColumn.innerText === selectedStaff) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>
