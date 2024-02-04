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

$user_id = $_SESSION['user_id'];

// Retrieve staff details from the database
$sql = "SELECT * FROM tblstaff WHERE staff_id = '$user_id'";
$result = $conn->query($sql);

// Fetch the current data
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $full_name = $row['StaffFullName'];
    $nickname = $row['StaffNickname'];
    $email = $row['StaffEmail'];
    $gender = $row['StaffGender'];
    $dob = $row['StaffDOB'];
    $address = $row['StaffAddress'];
    $phone_no = $row['StaffPhoneNo'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_profile"])) {
    // Update only nickname, password, and picture profile
    $nickname = $_POST["nickname"];
    $new_password = $_POST["new_password"];

    // Check if a new password is provided
    if (!empty($new_password)) {
        // Perform the SQL update for nickname and password
        $stmt = $conn->prepare("UPDATE tblstaff SET StaffNickname=?, StaffPassword=? WHERE staff_id=?");
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssi", $nickname, $hashed_password, $user_id);
    } else {
        // Perform the SQL update for nickname only
        $stmt = $conn->prepare("UPDATE tblstaff SET StaffNickname=? WHERE staff_id=?");
        $stmt->bind_param("si", $nickname, $user_id);
    }

    if ($stmt->execute()) {
        echo '<script>alert("Profile updated successfully!");</script>';
    } else {
        echo '<script>alert("Error updating profile: ' . $stmt->error . '");</script>';
    }

    $stmt->close();

    // Handle picture profile upload
    if (isset($_FILES['picture_profile']) && $_FILES['picture_profile']['error'] === 0) {
        $uploadDir = 'staffpictureprofile/';
        $uploadFile = $uploadDir . basename($_FILES['picture_profile']['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($imageFileType, $allowedExtensions)) {
            if (move_uploaded_file($_FILES['picture_profile']['tmp_name'], $uploadFile)) {
                echo '<script>alert("File has been uploaded successfully.");</script>';
                
                // Update the database with the file path
                $updatePictureQuery = "UPDATE tblstaff SET StaffPicProfile=? WHERE staff_id=?";
                $stmt = $conn->prepare($updatePictureQuery);
                $stmt->bind_param("si", $uploadFile, $user_id);
                
                if ($stmt->execute()) {
                    echo '<script>alert("Database updated successfully.");</script>';
                    // Success: You may redirect or perform other actions after successful file upload
                } else {
                    echo '<script>alert("Error updating picture profile in the database: ' . $stmt->error . '");</script>';
                }

                $stmt->close();
            } else {
                echo '<script>alert("Error moving uploaded file to destination directory.");</script>';
            }
        } else {
            echo '<script>alert("Invalid file format. Please upload a valid image.");</script>';
        }
    } else {
        echo '<script>alert("File upload error: ' . $_FILES['picture_profile']['error'] . '");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Profile</title>
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

        .edit-mode {
        display: none;
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

        input[type="button"] {
            background-color: #4a7bed;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="button"]:hover {
            background-color: #3564ad;
        }

        input[type="submit"] {
            background-color: #4a7bed;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #3564ad;
        }
    </style>
</head>
<body>
    <?php include('includestaff\staffheader.php'); ?>
    <?php include('includestaff/leftnavbar.php'); ?>
    <main class="view-mode">
        <h2>Staff Profile</h2>
        <form action="staffprofile.php" method="POST">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo $full_name; ?>" disabled>

            <label for="nickname">Nickname:</label>
            <input type="text" id="nickname" name="nickname" value="<?php echo $nickname; ?>" disabled>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" disabled>

            <label for="gender">Gender:</label>
            <input type="text" id="gender" name="gender" value="<?php echo $gender; ?>" disabled>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>" disabled>

            <label for="address">Address:</label>
            <input id="address" name="address" value="<?php echo $address; ?>" disabled>

            <label for="phone_no">Phone Number:</label>
            <input type="tel" id="phone_no" name="phone_no" value="<?php echo $phone_no; ?>" disabled>

            <input type="button" value="Edit Profile" name="edit_profile" id="editButton">
        </form>
    </main>

    <main class="edit-mode">
        <h2>Update Profile</h2>
        <form action="staffprofile.php" method="POST" enctype="multipart/form-data">
            <label for="nickname">Nickname:</label>
            <input type="text" id="nickname" name="nickname" value="<?php echo $nickname; ?>" required>

            <label for="picture_profile">Picture Profile:</label>
            <input type="file" id="picture_profile" name="picture_profile" accept="image/*">

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" minlength="6">
            
            <!-- You can add the file input for picture profile here -->

            <input type="submit" value="Update Profile" name="submit_profile">
        </form>
    </main>

    <!-- Add your JavaScript file link here -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Get references to view and edit mode sections
            const viewModeSection = document.querySelector(".view-mode");
            const editModeSection = document.querySelector(".edit-mode");

            // Get reference to the edit button
            const editButton = document.querySelector("#editButton");

            // Handle click event on edit button
            editButton.addEventListener("click", function () {
                // Toggle between view and edit mode
                viewModeSection.style.display = "none";
                editModeSection.style.display = "block";
            });
        });
    </script>
</body>
</html>
