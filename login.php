<?php
/*** mysql hostname ***/
$hostname = 'localhost';

/*** mysql username ***/
$username = 'root';

/*** mysql password ***/
$password = '';

$database = 'pisid';

// Establishing the connection
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the username from the form
if(isset($_POST['username'])){
    $username = $_POST['username'];

    // Query to check if the username exists in the database
    $query = "SELECT * FROM utilizador WHERE Nome = '$username'";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        // Check if any rows were returned
        if(mysqli_num_rows($result) > 0) {
            // Set a cookie with the username
            setcookie("techmice_user", $username);
            echo "Username found in the database!";
            // Redirect the user to another page
            exit();
        } else {
            echo "Username not found in the database!";
        }
    } else {
        echo "Error executing query: " . mysqli_error($conn);
    }
}

// Close the connection
mysqli_close($conn);
?>
