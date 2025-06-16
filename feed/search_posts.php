<?php
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$getpostsrandom = "SELECT * FROM posts WHERE title LIKE  '%$search_query%' OR caption LIKE '%$search_query%' ORDER BY created_at DESC";
$getpostsrandom = mysqli_query($conn, $getpostsrandom);

echo "<style>
.feed-container {
    padding: 0 0 30px 0;
}
.post {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    padding: 24px 20px 20px 20px;
    margin-bottom: 28px;
    transition: box-shadow 0.2s;
    position: relative;
}
.post:hover {
    box-shadow: 0 6px 32px rgba(0,123,255,0.10);
}
.post-header {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}
.profile-pic {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 14px;
    border: 2px solid #007bff;
    transition: box-shadow 0.2s;
    background: #f4f6fb;
}
.user-link {
    text-decoration: none;
    color: #222;
    font-weight: bold;
    font-size: 17px;
    margin-right: 8px;
    transition: color 0.2s;
}
.user-link:hover {
    color: #007bff;
}
.post-time {
    color: #888;
    font-size: 13px;
    margin-left: auto;
}
.post-content {
    font-size: 15px;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.6;
}
.post-img {
    max-width: 100%;
    height: auto;
    margin-top: 10px;
    border-radius: 8px;
    box-shadow: 0 1px 8px rgba(0,0,0,0.06);
}
.ad {
    background: #f9f9e3;
    padding: 12px 16px;
    margin-top: 10px;
    border-radius: 8px;
    border-left: 4px solid #ffc107;
}
.comment-btn {
    margin-top: 14px;
    background: #007bff;
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.2s;
}
.comment-btn:hover {
    background: #0056b3;
}
</style>";

echo "<div class='feed-container'>";
while($row = mysqli_fetch_assoc($getpostsrandom)) {
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

    if ($post_type === 'picture') {
        echo "
        <div class='post'>
            <div class='post-header'>
                <a href='../users/profile.php?username=$postuser' class='user-link'>
                    <img src='../users/profile_picture/$profile_pic' class='profile-pic' alt='Profile'>
                    $name
                </a>
                <span class='post-time'>$post_time</span>
            </div>
            <div class='post-content'>$post_content</div>
            <img src='../users/post_picture/$post_image' class='post-img' alt='Post Image'>
            <a href='../comments.php?post_id=$post_id'>
                <button class='comment-btn'>Comments</button>
            </a>
        </div>
        ";
    } else if ($post_type === 'ad') {
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
            <a href='../comments.php?post_id=$post_id'>
                <button class='comment-btn'>Comments</button>
            </a>
        </div>
        ";
    } else if ($post_type === 'text') {
        echo "
        <div class='post'>
            <div class='post-header'>
                <a href='../users/profile.php?username=$postuser' class='user-link'>
                    <img src='../users/profile_picture/$profile_pic' class='profile-pic' alt='Profile'>
                    $name
                </a>
                <span class='post-time'>$post_time</span>
            </div>
            <div class='post-content'>$post_content</div>
            <a href='../comments.php?post_id=$post_id'>
                <button class='comment-btn'>Comments</button>
            </a>
        </div>
        ";
    }
}
echo "</div>";
?>