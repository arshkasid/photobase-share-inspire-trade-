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
        }
        .user-posts h2 {
            font-size: 1.3rem;
            color: #007bff;
            margin-bottom: 18px;
            letter-spacing: 1px;
        }
        .post {
            border:1.5px solid #e0e0e0;
            border-radius: 10px;
            padding:18px 16px 14px 16px;
            margin-bottom:18px;
            background: #fafbff;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
            position: relative;
        }
        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        .post-date {
            color: #888;
            font-size: 13px;
            margin-left: auto;
        }
        .post-img {
            max-width:100%;
            height:auto;
            margin-top:8px;
            border-radius: 6px;
            box-shadow: 0 1px 8px rgba(0,0,0,0.07);
        }
        .ad {
            background:#f9f9e3;
            padding:8px;
            margin-top:8px;
            border-radius: 6px;
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




?>
    <div class="site-banner">
        photobase
        <div class="site-punchline">Share. Inspire. Trade. Your photography community.</div>
    </div>
    <div class="profile-container">
        <div class="profile-header">
            <img src="https://randomuser.me/api/portraits/men/45.jpg" class="profile-pic" alt="Profile Picture">
            <div class="profile-details">
                <div class="username"></div>
                <div class="profile-info">Name: Bob Lensman</div>
                <div class="profile-info">Email: bob@example.com</div>
                <div class="bio">
                    Passionate street photographer. Always looking for the next great shot.<br>
                    Based in New York City.
                </div>
                <div class="button-row">
                    <button class="follow-btn">Follow</button>
                    <a href="https://instagram.com/" target="_blank" class="ig-btn">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" class="ig-logo" alt="Instagram">
                        Connect on IG
                    </a>
                    <a href="#" class="demo-btn">Demo</a>
                </div>
            </div>
        </div>
        <div class="user-posts">
            <h2>Posts</h2>
            <div class="post">
                <div class="post-header">
                    <span style="font-weight:bold;">lensman_bob</span>
                    <span class="post-date">2024-06-01 09:30</span>
                </div>
                <p>Anyone up for a photo walk this weekend?</p>
                <a href="../comments.php?post_id=2">
                    <button class="comment-btn">Comments</button>
                </a>
            </div>
            <div class="post">
                <div class="post-header">
                    <span style="font-weight:bold;">lensman_bob</span>
                    <span class="post-date">2024-06-01 10:00</span>
                </div>
                <p>Check out this sunrise!</p>
                <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80" class="post-img" alt="Sunrise">
                <a href="../comments.php?post_id=1">
                    <button class="comment-btn">Comments</button>
                </a>
            </div>
            <div class="post ad">
                <div class="post-header">
                    <span style="font-weight:bold;">lensman_bob</span>
                    <span class="post-date">2024-06-01 09:00</span>
                </div>
                <strong>For Sale: Canon 50mm f/1.8</strong><br>
                Price: $80<br>
                <p>Lightly used, great condition. DM if interested!</p>
                <img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=600&q=80" class="post-img" alt="Lens">
                <a href="../comments.php?post_id=3">
                    <button class="comment-btn">Comments</button>
                </a>
            </div>
        </div>
        <a href="../feed/index.php" class="back-link">&larr; Back to Feed</a>
    </div>
</body>
</html>
