<?php
include '../connect/connect.php';

session_start();


// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../users/login.php");
    exit();
}else{
    $username = $_SESSION['username'];
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $result = mysqli_fetch_assoc($result);
    $name = $result['name'];

    $profile_pic = $result['picture'] ? $result['picture'] : 'default.png'; // Fallback to default if no profile pic
    $bio = $result['bio'] ? $result['bio'] : 'No bio available';
    $email = $result['email'] ? $result['email'] : 'No email provided';
    
    
   


    
}

// Get the logged-in username




// Removed the dynamic post fetching code as we are using static posts now
?>



<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; }
        .site-banner {
            width: 100%;
            background: linear-gradient(90deg, #232526 0%, #414345 100%);
            color: #fff;
            text-align: center;
            padding: 28px 0 10px 0;
            font-size: 2.2rem;
            font-weight: bold;
            letter-spacing: 2px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            margin-bottom: 32px;
        }
        .site-punchline {
            font-size: 1.1rem;
            font-weight: normal;
            color: #ccc;
            margin-top: 6px;
            letter-spacing: 1px;
        }
        .profile-container {
            max-width: 950px;
            margin: 40px auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.09);
            padding: 38px 36px 32px 36px;
            text-align: left;
        }
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 32px;
        }
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 44px;
            border: 5px solid #007bff;
            background: #f4f6fb;
            box-shadow: 0 2px 16px rgba(0,123,255,0.10);
        }
        .profile-details {
            flex: 1;
        }
        .username {
            font-size: 2.2rem;
            font-weight: bold;
            margin-bottom: 8px;
            color: #232526;
        }
        .profile-info {
            margin-bottom: 8px;
            color: #666;
            font-size: 1.08rem;
        }
        .bio {
            color: #444;
            margin-bottom: 18px;
            font-size: 1.08rem;
            background: #f7f7fa;
            border-radius: 8px;
            padding: 14px 12px;
            display: inline-block;
            max-width: 90%;
        }
        .button-row {
            display: flex;
            gap: 18px;
            margin-bottom: 24px;
        }
        .follow-btn, .ig-btn, .demo-btn {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 24px;
            padding: 10px 32px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .follow-btn:hover, .ig-btn:hover, .demo-btn:hover {
            background: #0056b3;
        }
        .ig-btn {
            background: #E1306C;
        }
        .ig-btn:hover {
            background: #b92d5e;
        }
        .ig-logo {
            width: 20px;
            height: 20px;
            display: inline-block;
            vertical-align: middle;
        }
        .user-posts {
            margin-top: 32px;
            text-align: left;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .user-posts h2 {
            font-size: 1.15rem;
            color: #007bff;
            margin-bottom: 14px;
            letter-spacing: 1px;
        }
        .post {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 10px 10px 10px;
            margin-bottom: 14px;
            background: #fafbff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            position: relative;
            max-width: 540px;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }
        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 6px;
            justify-content: space-between;
        }
        .post-header-left {
            display: flex;
            align-items: center;
        }
        .post-header .profile-pic {
            width: 26px;
            height: 26px;
            margin-right: 8px;
            border-width: 2px;
        }
        .user-link {
            text-decoration: none;
            color: #111;
            font-weight: bold;
            font-size: 15px;
            margin-right: 6px;
            transition: color 0.2s;
            letter-spacing: 0.5px;
            background: linear-gradient(90deg, #007bff 40%, #00c6ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .user-link:hover {
            color: #007bff;
            -webkit-text-fill-color: #007bff;
        }
        .post-time {
            color: #888;
            font-size: 12px;
            margin-left: 18px;
            white-space: nowrap;
            font-style: italic;
        }
        .post-content {
            font-size: 13px;
            color: #333;
            margin-bottom: 7px;
            line-height: 1.5;
        }
        .post-img {
            max-width: 100%;
            height: auto;
            margin-top: 7px;
            border-radius: 5px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .ad {
            background: #f9f9e3;
            padding: 8px 10px;
            margin-top: 7px;
            border-radius: 6px;
            border-left: 3px solid #ffc107;
            font-size: 13px;
        }
        .comment-btn {
            margin-top: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 13px;
            transition: background 0.2s;
        }
        .comment-btn:hover {
            background: #0056b3;
        }
        .back-link {
            margin-top: 32px;
            display: inline-block;
            color: #007bff;
            text-decoration: none;
            font-size: 1.05rem;
            font-weight: bold;
            letter-spacing: 1px;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        @media (max-width: 900px) {
            .profile-container { max-width: 98vw; }
            .profile-header { flex-direction: column; align-items: center; text-align: center; }
            .profile-pic { margin-right: 0; margin-bottom: 18px; }
            .profile-details { width: 100%; }
        }
    </style>
</head>
<body>

<?php

$searchname = isset($_GET['username']) ? $_GET['username'] : '';

if ($_SESSION['username']  == $searchname) {
    echo "<script>window.location.href = '../users/self_profile.php';</script>";
// if user is same redirect to self_profile
    
}


$getalldata = mysqli_query($conn, "SELECT * FROM users WHERE username = '$_SESSION[username]'");
$user_data = mysqli_fetch_assoc($getalldata);

$username = $user_data['username'];
$name = $user_data['name'];
$email = $user_data['email'];
$bio = $user_data['bio'];
$profile_pic = $user_data['picture'];
$ig = $user_data['ig'] ? $user_data['ig'] : 'No Instagram linked';

?>
    <div class="site-banner">
        photobase
        <div class="site-punchline">Share. Inspire. Trade. Your photography community.</div>
    </div>
    <div class="profile-container">
        <div class="profile-header">
            <img src="../users/profile_picture/<?php echo $profile_pic; ?>" class="profile-pic" alt="Profile Picture">
            <div class="profile-details">
                <div style="text-align:right;">
                    <a href="../feed/index.php" class="back-link" style="background:#444; color:#fff; padding:10px 24px; border-radius:20px; text-decoration:none; font-weight:bold; font-size:15px; box-shadow:0 2px 8px rgba(0,0,0,0.06); margin-bottom:18px; transition:background 0.2s; display:inline-block;">
                        &larr; Back to Home Feed
                    </a>
                </div>
                <div class="username"><?php echo $username; ?></div>
                <div class="profile-info">Name: <?php echo $name; ?></div>
                <div class="profile-info">Email: <?php echo $email; ?></div>
                <div class="bio">
                    <?php echo !empty($bio) ? htmlspecialchars($bio) : 'No bio available'; ?>
                </div>
                <div class="button-row">
                    <a href="edit.php" class="follow-btn">Edit</a>
                    <a href="https://instagram.com/<?php echo $ig; ?>" target="_blank" class="ig-btn">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" class="ig-logo" alt="Instagram">
                        Connect on IG
                    </a>
                </div>
            </div>
        </div>
        <div class="user-posts">
            <h2>Posts</h2>
            <?php
            $getpostsrandom = "SELECT * FROM posts WHERE username = '$username' ORDER BY created_at DESC";
            $getpostsrandom = mysqli_query($conn, $getpostsrandom);

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
                $profile_pic_post = $user['picture'];
                $name_post = $user['name'];
                $profile_pic_post = $profile_pic_post ? $profile_pic_post : 'default.png';

                if ($post_type === 'picture') {
                    echo "
                    <div class='post'>
                        <div class='post-header'>
                            <div class='post-header-left'>
                                <a href='../users/profile.php?username=$postuser' class='user-link'>
                                    <img src='../users/profile_picture/$profile_pic_post' class='profile-pic' alt='Profile'>
                                    $name_post
                                </a>
                            </div>
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
                            <div class='post-header-left'>
                                <a href='../users/profile.php?username=$postuser' class='user-link'>
                                    <img src='../users/profile_picture/$profile_pic_post' class='profile-pic' alt='Profile'>
                                    $name_post
                                </a>
                            </div>
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
                            <div class='post-header-left'>
                                <a href='../users/profile.php?username=$postuser' class='user-link'>
                                    <img src='../users/profile_picture/$profile_pic_post' class='profile-pic' alt='Profile'>
                                    $name_post
                                </a>
                            </div>
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
            ?>
        </div>
    </div>
</body>
</html>
</html>
