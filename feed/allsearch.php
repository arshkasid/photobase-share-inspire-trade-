<?php


$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';



// Search posts (text, picture, ad)
$post_sql = "SELECT * FROM posts WHERE 
    title LIKE '%$search_query%' OR 
    caption LIKE '%$search_query%' OR 
    type LIKE '%$search_query%' OR
    tags LIKE '%$search_query%'
    ORDER BY created_at DESC";
$post_result = mysqli_query($conn, $post_sql);

if (mysqli_num_rows($post_result) > 0) {
    echo "<h3>Posts</h3>";
    while ($row = mysqli_fetch_assoc($post_result)) {
        $post_id = $row['post_id'];
        $postuser = $row['username'];
        $post_title = $row['title'];
        $post_content = $row['caption'];
        $post_image = $row['picture'];
        $post_time = $row['created_at'];
        $post_price = $row['price'];
        $post_type = $row['type'];

        // Fetch user info
        $user_query = "SELECT * FROM users WHERE username = '$postuser'";
        $user_result = mysqli_query($conn, $user_query);
        $user = mysqli_fetch_assoc($user_result);
        $profile_pic = $user['picture'] ? $user['picture'] : 'default.png';
        $name = $user['name'];

        echo "<div style='border:1px solid #eee; border-radius:8px; margin-bottom:18px; padding:14px; background:#fafbff; max-width:500px;'>";
        echo "<div style='display:flex;align-items:center;'>";
        echo "<img src='../users/profile_picture/$profile_pic' style='width:32px;height:32px;border-radius:50%;margin-right:10px;border:2px solid #007bff;'>";
        echo "<a href='../users/profile.php?username=$postuser' style='font-weight:bold;color:#222;text-decoration:none;margin-right:8px;'>$name</a>";
        echo "<span style='color:#888;font-size:12px;margin-left:auto;'>$post_time</span>";
        echo "</div>";
        echo "<div style='font-size:15px;color:#333;margin:8px 0;'>$post_content</div>";
        if ($post_type === 'picture' || $post_type === 'ad') {
            echo "<img src='../users/post_picture/$post_image' style='max-width:100%;border-radius:6px;box-shadow:0 1px 8px rgba(0,0,0,0.06);margin-bottom:8px;'>";
        }
        if ($post_type === 'ad') {
            echo "<div style='background:#f9f9e3;padding:8px 10px;border-radius:6px;border-left:3px solid #ffc107;margin-bottom:8px;'>";
            echo "<strong>$post_title</strong><br>Price: $post_price";
            echo "</div>";
        }
        echo "<a href='../comments.php?post_id=$post_id'><button style='background:#007bff;color:#fff;border:none;padding:6px 16px;border-radius:5px;cursor:pointer;font-weight:bold;'>Comments</button></a>";
        echo "</div>";
    }
} else {
    echo "<p>No posts found.</p>";
}

// Search users
$user_sql = "SELECT * FROM users WHERE 
    username LIKE '%$search_query%' OR 
    name LIKE '%$search_query%' OR 
    bio LIKE '%$search_query%'";
$user_result = mysqli_query($conn, $user_sql);

if (mysqli_num_rows($user_result) > 0) {
    echo "<h3>Users</h3>";
    while ($row = mysqli_fetch_assoc($user_result)) {
        $username = $row['username'];
        $name = $row['name'];
        $profile_pic = $row['picture'] ? $row['picture'] : 'default.png';
        $bio = $row['bio'];
        echo "<div style='border:1px solid #eee; border-radius:8px; margin-bottom:14px; padding:12px; background:#fff; max-width:400px;'>";
        echo "<img src='../users/profile_picture/$profile_pic' style='width:32px;height:32px;border-radius:50%;margin-right:10px;border:2px solid #007bff;vertical-align:middle;'>";
        echo "<a href='../users/profile.php?username=$username' style='font-weight:bold;color:#007bff;text-decoration:none;font-size:16px;'>$name</a>";
        echo "<div style='color:#555;font-size:13px;margin-top:4px;'>$bio</div>";
        echo "</div>";
    }
} else {
    echo "<p>No users found.</p>";
}

// Search communities
$comm_sql = "SELECT * FROM communities WHERE 
    name LIKE '%$search_query%'";
$comm_result = mysqli_query($conn, $comm_sql);

if (mysqli_num_rows($comm_result) > 0) {
    echo "<h3>Communities</h3>";
    while ($row = mysqli_fetch_assoc($comm_result)) {
        $comm_id = $row['id'];
        $comm_name = $row['name'];
        $comm_pic = isset($row['picture']) && $row['picture'] ? $row['picture'] : 'default_community_pic.png';
        $members = $row['members'];
        echo "<div style='border:1px solid #eee; border-radius:8px; margin-bottom:14px; padding:12px; background:#fff; max-width:400px;display:flex;align-items:center;'>";
        echo "<img src='../communities/pictures/$comm_pic' style='width:32px;height:32px;border-radius:50%;margin-right:10px;border:2px solid #007bff;'>";
        echo "<a href='../communities/community.php?id=$comm_id' style='font-weight:bold;color:#007bff;text-decoration:none;font-size:16px;'>$comm_name</a>";
        echo "<span style='margin-left:auto;font-size:13px;color:#888;'>$members members</span>";
        echo "</div>";
    }
} else {
    echo "<p>No communities found.</p>";
}
?>
<!-- ...existing code... -->
