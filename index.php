<?php
include('config.php');

// Retrieve staff movement records from the database with the latest Start Date at the top
$sql = "SELECT * FROM tblmovement ORDER BY StartDate DESC";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <title>e-Gerak Home</title>
    <!-- Add your stylesheet link here -->
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

        body {
            background-color: #c6e2e9;
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
        }

        header {
            background-color: #4a7bed;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1 {
            color: #fff;
            margin: 0;
        }

        nav {
            text-align: center;
            margin-top: 20px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            display: inline-block;
            border: 1px solid #fff;
            border-radius: 5px;
            margin: 0 10px;
        }

        nav a:hover {
            background-color: #fff;
            color: #4a7bed;
        }

        main {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #4a7bed;
            margin-bottom: 10px;
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
            border: 1px solid #ddd;
        }

        th {
            background-color: #4a7bed;
            color: #fff;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        /* Added a style for 'no-image' */
        .no-image {
            color: #777;
        }
    </style>
</head>
<body>
    <header>
        <h1>e-Gerak</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="signin.php">Sign In</a>
            <a href="adminsignin.php">Admin</a>
        </nav>
    </header>

    <main>
        <h2>Staff Movements</h2>

        <table border="1">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Place</th>
                    <th>Program</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through each record and display in the table
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";

                    // Check if the keys exist before using them
                    echo "<td>" . htmlspecialchars(isset($row['StaffFullName']) ? $row['StaffFullName'] : '') . "</td>";
                    echo "<td>" . htmlspecialchars(isset($row['StartDate']) ? $row['StartDate'] : '') . "</td>";
                    echo "<td>" . htmlspecialchars(isset($row['EndDate']) ? $row['EndDate'] : '') . "</td>";
                    echo "<td>" . htmlspecialchars(isset($row['Place']) ? $row['Place'] : '') . "</td>";
                    echo "<td>" . htmlspecialchars(isset($row['Program']) ? $row['Program'] : '') . "</td>";

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>
