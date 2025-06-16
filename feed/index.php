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

    $profile_pic = $result['picture'] ? $result['picture'] : 'default_profile_pic.png'; // Fallback to default if no profile pic
    $bio = $result['bio'] ? $result['bio'] : 'No bio available';

    
}

// Get the logged-in username




// Removed the dynamic post fetching code as we are using static posts now
?>
<!DOCTYPE html>
<html>
<head>
    <title>Feed</title>
    <style>
        body {
            background: #f4f6fb;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
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
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .site-punchline {
            font-size: 1.1rem;
            font-weight: normal;
            color: #ccc;
            margin-top: 6px;
            letter-spacing: 1px;
        }
        .logout-btn {
            position: absolute;
            right: 32px;
            top: 50%;
            transform: translateY(-50%);
            background: #444;
            color: #fff;
            border: none;
            border-radius: 22px;
            padding: 10px 26px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            text-decoration: none;
        }
        .logout-btn:hover {
            background: #222;
            color: #fff;
        }
        .navbar {
            background: #f5f5f7;
            color: #222;
            padding: 0;
            margin-bottom: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .nav-btn {
            flex: 1 1 0;
            background: #f5f5f7;
            color: #222;
            border: none;
            border-right: 1px solid #e0e0e0;
            border-radius: 0;
            padding: 18px 0;
            font-weight: bold;
            font-size: 17px;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            text-align: center;
            text-decoration: none;
            outline: none;
        }
        .nav-btn:last-child {
            border-right: none;
        }
        .nav-btn.active, .nav-btn:hover, .nav-btn:focus {
            background: #f0f0f2; /* lighter grey on hover/active */
            color: #007bff;
        }
        .search-bar-container {
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 22px 0 18px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            margin-bottom: 32px;
        }
        .search-bar {
            width: 350px;
            max-width: 90vw;
            padding: 10px 18px;
            border: 1.5px solid #e0e0e0;
            border-radius: 24px;
            font-size: 16px;
            outline: none;
            transition: border 0.2s;
            background: #f9f9fa;
            margin-right: 8px;
        }
        .search-bar:focus {
            border: 1.5px solid #007bff;
            background: #fff;
        }
        .search-btn {
            padding: 10px 22px;
            border-radius: 24px;
            border: none;
            background: #007bff;
            color: #fff;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .search-btn:hover {
            background: #0056b3;
        }
        .main-layout {
            display: flex;
            justify-content: center;
            gap: 32px;
            max-width: 1200px;
            margin: 0 auto 40px auto;
            padding: 0 12px;
        }
        .sidebar {
            flex: 0 0 250px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            padding: 24px 18px;
            height: fit-content;
        }
        .feed-container {
            flex: 1 1 600px;
            max-width: 600px;
            margin: 0 auto 40px auto;
            padding: 0 12px;
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
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 14px;
            border: 2px solid #007bff;
            transition: box-shadow 0.2s;
        }
        .user-link {
            text-decoration: none;
            color: #222;
            font-weight: bold;
            font-size: 18px;
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
            font-size: 16px;
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
        .profile-summary {
            text-align: center;
        }
        .profile-summary .profile-pic {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 2px solid #007bff;
        }
        .profile-summary .username {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .profile-summary .bio {
            color: #555;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .profile-summary a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        .random-accounts-title {
            font-weight: bold;
            margin-bottom: 12px;
            font-size: 17px;
        }
        .random-account {
            display: flex;
            align-items: center;
            margin-bottom: 14px;
        }
        .random-account .profile-pic {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            border: 2px solid #007bff;
        }
        .random-account .username {
            font-weight: bold;
            font-size: 15px;
            color: #222;
            margin-right: 8px;
        }
        .random-account .follow-btn {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 16px;
            padding: 4px 14px;
            font-size: 13px;
            cursor: pointer;
            margin-left: auto;
            transition: background 0.2s;
        }
        .random-account .follow-btn:hover {
            background: #0056b3;
        }
        @media (max-width: 1000px) {
            .main-layout { flex-direction: column; gap: 0; }
            .sidebar { margin-bottom: 24px; }
        }
        @media (max-width: 700px) {
            .feed-container { max-width: 98vw; }
            .post { padding: 16px 8px 14px 8px; }
        }
    </style>
    <script>
        function filterPosts(type) {
            var posts = document.querySelectorAll('.post');
            posts.forEach(function(post) {
                if (type === 'all') {
                    post.style.display = '';
                } else if (type === 'ad') {
                    post.style.display = post.classList.contains('ad-post') ? '' : 'none';
                } else if (type === 'post') {
                    post.style.display = post.classList.contains('ad-post') ? 'none' : '';
                }
            });
            document.querySelectorAll('.nav-btn').forEach(function(btn) {
                btn.classList.remove('active');
            });
            document.getElementById('btn-' + type).classList.add('active');
        }
        window.onload = function() {
            filterPosts('all');
        }
    </script>
</head>
<body>
    <div class="site-banner">
        photobase
        <div class="site-punchline">Share. Inspire. Trade. Your photography community.</div>
        <a href="../users/logout.php" class="logout-btn">Log Out</a>
    </div>
    <div class="navbar">
        <a href="index.php?page=all"<?php if (isset($_GET['page'])&& $_GET['page'] === 'all') echo ' class="nav-btn "'; else echo ' class="nav-btn"'; ?> id="btn-all">All</a>
        <a href="index.php?page=posts<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>"<?php if (isset($_GET['page']) && $_GET['page'] === 'posts') echo ' class="nav-btn active"'; else echo ' class="nav-btn"'; ?> id="btn-post">Posts</a>
        <a href="index.php?page=ads<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>"<?php if (isset($_GET['page']) && $_GET['page'] === 'ads') echo ' class="nav-btn active"'; else echo ' class="nav-btn"'; ?> id="btn-ad">Ads</a>
        <a href="index.php?page=following<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>"<?php if (isset($_GET['page']) && $_GET['page'] === 'following') echo ' class="nav-btn active"'; else echo ' class="nav-btn"'; ?> id="btn-following">Following</a>
    </div>
    <div class="search-bar-container">
        <form style="display:flex;align-items:center;width:100%;justify-content:center;" method="get" action="search.php">
            <input type="hidden" name="page" value="accounts">
            <input type="text" class="search-bar" placeholder="Search posts, users, or gear..." name="search">
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>
    <h1 style="text-align:center;">Feed</h1>
    <div class="main-layout">
        <!-- Left Sidebar: Profile Summary -->
        <div class="sidebar">
            <div class="profile-summary">
                <img src="../assests/images.jpg" class="profile-pic" alt="Profile">
                <div class="username"><?php
                echo $_SESSION['username'];
                ?></div>
                <div class="bio"><?php echo $bio; ?></div>
                <a href="../users/profile.php?username=<?php echo $_SESSION['username']; ?>">View Profile &rarr;</a>
            </div>
        </div>
        <!-- Center: Feed -->
        <div class="feed-container">
            <!-- Static posts -->
           <?php

           if (isset($_GET['page']) && $_GET['page'] === 'all') {
               include('all.php');
           }else if (isset($_GET['page']) && $_GET['page'] === 'posts') {
               include('posts.php');
           } else if (isset($_GET['page']) && $_GET['page'] === 'ads') {
               include('ads.php');
           } else if (isset($_GET['page']) && $_GET['page'] === 'following') {
               include('following.php');
           } else {
               include('all.php'); 
           }

           ?>
        </div>
        <!-- Right Sidebar: Random Accounts -->
        <div class="sidebar">
            <div class="random-accounts-title">Suggested Accounts</div>
            <div class="random-account">
                <img src="https://randomuser.me/api/portraits/women/44.jpg" class="profile-pic" alt="Profile">
                <span class="username">photog_anna</span>
                <a href="../users/profile.php"><button class="follow-btn">Follow</button></a>
            </div>
            <div class="random-account">
                <img src="https://randomuser.me/api/portraits/men/65.jpg" class="profile-pic" alt="Profile">
                <span class="username">gear_guru</span>
                <a href="../users/profile.php"><button class="follow-btn">Follow</button></a>
            </div>
            <div class="random-account">
                <img src="https://randomuser.me/api/portraits/women/68.jpg" class="profile-pic" alt="Profile">
                <span class="username">snapqueen</span>
                <a href="../users/profile.php"><button class="follow-btn">Follow</button></a>
            </div>
        </div>
    </div>
</body>
</html>
<?php

?>
