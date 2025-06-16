<?php

$logged_in_user = $_SESSION['username'];

// Get users the logged-in user follows
$following_usernames = [];
$following_result = mysqli_query($conn, "SELECT following FROM following WHERE follower = '$logged_in_user'");
while ($row = mysqli_fetch_assoc($following_result)) {
    $following_usernames[] = $row['following'];
}

echo "<h2>Posts from People You Follow</h2>";

if (!empty($following_usernames)) {
    // Prepare user list for SQL IN clause
    $escaped = array_map(function($u) use ($conn) { return "'" . mysqli_real_escape_string($conn, $u) . "'"; }, $following_usernames);
    $user_list = implode(",", $escaped);

    $posts_query = "SELECT * FROM posts WHERE username IN ($user_list) ORDER BY created_at DESC";
    $posts_result = mysqli_query($conn, $posts_query);

    if (mysqli_num_rows($posts_result) > 0) {
        while ($row = mysqli_fetch_assoc($posts_result)) {
            $postuser = $row['username'];
            $post_id = $row['post_id'];
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
        echo "<p>No posts from people you follow yet.</p>";
    }
} else {
    echo "<p>You are not following anyone yet.</p>";
}
?>
  

     