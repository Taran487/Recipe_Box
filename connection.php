<!-- Database connection file -->
<?php
    //database connection
    $servername = "localhost";
    $db_username = 'root';
    $db_password = 'root';
    $dbname = "recipe";

    // Create connection
    $conn = new mysqli($servername, $db_username, $db_password, $dbname); // Create a new connection to the MySQL database using the provided server name, username, password, and database name  

    // Check connection
    if ($conn->connect_error) {
        echo "Failed to connect to MYSQL";
    die("Connection failed: " . $conn->connect_error); // Check if the connection was successful, if not, display an error message
    }
?>            