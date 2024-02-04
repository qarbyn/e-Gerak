<!-- leftnavbar.php -->

<nav class="left-navbar">
    <ul>
        <li><a href="../index.php">Home</a></li>
        <li><a href="admindashboard.php">Dashboard</a></li>
        <li class="submenu">
            Manage Staff
            <ul class="sub-menu-list">
                <li><a href="adminupdatestaff.php">Update Staff</a></li>
                <li><a href="adminaddstaff.php">Add Staff</a></li>
                <li><a href="admindeletestaff.php">Delete Staff</a></li>
            </ul>
        </li>
        <li><a href="adminanalytic.php">Analytics</a></li>
        <li><a href="../logout.php">Log Out</a></li>
    </ul>
</nav>

<style>
/* Navbar styles */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

.left-navbar {
    background-color: #2c3e50; /* Set your background color */
    height: 100vh;
    width: 190px;
    position: fixed;
    top: 0;
    left: 0;
    overflow-x: hidden;
    padding-top: 20px;
    font-family: "Poppins", sans-serif;
}

.left-navbar ul {
    list-style-type: none;
    padding: 0;
}

.left-navbar li {
    padding: 20px;
    text-align: center;
    font-size: 20px;
    text-decoration: none;
    color: #ecf0f1; /* Set your text color */
    font-family: "Poppins", sans-serif;
    transition: background-color 0.7s ease-in-out, color 0.7s ease-in-out; /* Smooth transition */
}

.left-navbar a {
    text-decoration: none;
    color: #ecf0f1; /* Set your link color */
    font-size: 20px;
    font-family: "Poppins", sans-serif;
    display: block;
    position: relative;
}

.left-navbar a:hover {
    /* Set your hover background color */
    color: #fff; /* Set your hover text color */
    box-shadow: 0 0 20px #3498db; /* Set the glow effect */
    background-color: #3498db;
}

/* Submenu styles */
.submenu {
    position: relative;
}

.submenu:hover .sub-menu-list {
    display: block;
}

.sub-menu-list {
    display: none;
    position: absolute;
    width: 180px;
    top: 100%; /* Align the submenu below the parent */
    left: 50;
    z-index: 1; /* Ensure the submenu is above other elements */
    background-color: #2c3e50; /* Submenu background color */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.sub-menu-list li {
    padding: 15px;
    font-size: 18px;
    transition: background-color 0.7s ease-in-out, color 0.7s ease-in-out; /* Smooth transition */
}

.sub-menu-list a {
    text-decoration: none;
    text-align: center;
    color: #ecf0f1; /* Submenu text color */
    font-family: "Poppins", sans-serif;
    font-size: 14px;
    display: block;
}

.sub-menu-list a:hover {
    color: #fff; /* Submenu hover text color */
    background-color: #3498db; /* Submenu hover background color */
}
</style>
