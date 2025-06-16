<?php
$search_query = isset($_GET['search']) ? $_GET['search'] : '';



$getads = mysqli_query($conn, "
    SELECT * FROM posts 
    WHERE type = 'ad' AND (
        title LIKE '%$search_query%' OR 
        caption LIKE '%$search_query%' OR 
        tags LIKE '%$search_query%'
    )
    ORDER BY created_at DESC
");

while($row = mysqli_fetch_assoc($getads)) {
    $postuser = $row['username'];
    $post_id = $row['post_id'];
    $post_title = $row['title'];
    $post_content = $row['caption'];
    $post_image = $row['picture'];
    $post_time = $row['created_at'];
    $post_price = $row['price'];
    $post_type = $row['type'];
    

    // Fetch user information
    $user_query = "SELECT * FROM users WHERE username = '$postuser'";
    $user_result = mysqli_query($conn, $user_query);
    $user = mysqli_fetch_assoc($user_result);
    $profile_pic = $user['picture'];
    $name = $user['name'];
    $profile_pic = $profile_pic ? $profile_pic : 'default.png'; 
echo"<div class='feed-container'>";
if ($post_type === 'ad') {
    echo "
          <div class='post ad-post'>
                <div class='post-header'>
                    <a href='../users/profile.php?username=$postuser' class='user-link'>
                        <img src='../users/profile_picture/$profile_pic' class='profile-pic' alt='Profile'>
                        $name
                    </a>
                    <span class='post-time'>$post_time</span>
                </div>
                <div class='ad'>
                    <strong>$post_title</strong><br>
                    Price: $post_price<br>
                    <p>$post_content</p>
                    <img src='../users/post_picture/$post_image' class='post-img' alt='Lens'>
                </div>
                <!-- Comments Section -->
                <a href='../comments.php?post_id=$post_id'>
                    <button style='margin-top:14px;background:#007bff;color:#fff;border:none;padding:8px 20px;border-radius:6px;cursor:pointer;font-weight:bold;'>
                        Comments
                    </button>
                </a>
            </div>
    ";
}
echo "</div>";
}
?>