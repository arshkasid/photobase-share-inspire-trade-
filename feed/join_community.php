<?php
include '../connect/connect.php';
session_start();

if (!isset($_SESSION['username']) || !isset($_POST['community'])) {
    http_response_code(400);
    exit('Invalid request');
}

$username = $_SESSION['username'];
$community = mysqli_real_escape_string($conn, $_POST['community']);

// Prevent duplicate join
$exists = mysqli_query($conn, "SELECT * FROM join_comm WHERE username='$username' AND community='$community'");
if (mysqli_num_rows($exists) == 0) {
    mysqli_query($conn, "INSERT INTO join_comm (username, community) VALUES ('$username', '$community')");
    // Optionally increment members count
    mysqli_query($conn, "UPDATE communities SET members = members + 1 WHERE name='$community'");
}

echo "success";
?>
