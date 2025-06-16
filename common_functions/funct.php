<?php

function search_accounts($conn, $searchTerm) {
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    $query = "SELECT * FROM users WHERE username LIKE '%$searchTerm%' OR name LIKE '%$searchTerm%'";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function search_posts($conn, $searchTerm) {
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    $query = "SELECT * FROM posts WHERE title LIKE '%$searchTerm%' OR caption LIKE '%$searchTerm%'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}



function random_posts($conn) {
    $query = "SELECT * FROM posts ORDER BY RAND()  LIMIT 30";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}



?>