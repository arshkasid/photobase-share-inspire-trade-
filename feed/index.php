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
        /* Popup styles */
        .popup-bg {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0; top: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.35);
            justify-content: center;
            align-items: flex-start;
        }
        .popup-content {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.18);
            padding: 32px 28px 24px 28px;
            margin-top: 60px;
            min-width: 320px;
            max-width: 95vw;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
        }
        .popup-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 18px;
        }
        .popup-close {
            position: absolute;
            top: 14px;
            right: 18px;
            font-size: 1.5rem;
            color: #888;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        .popup-community-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .popup-community {
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 10px;
        }
        .popup-community:last-child {
            border-bottom: none;
        }
        .popup-community .profile-pic {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #007bff;
        }
        .popup-community .community-name {
            font-weight: bold;
            color: #232526;
            font-size: 1rem;
        }
        .popup-community .community-tag {
            background: #f0f0f2;
            color: #007bff;
            border-radius: 12px;
            padding: 2px 10px;
            font-size: 12px;
            margin-left: 8px;
        }
        .popup-community .join-btn {
            margin-left: auto;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 16px;
            padding: 6px 18px;
            font-size: 14px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s;
        }
        .popup-community .join-btn:hover {
            background: #0056b3;
        }
        .quick-navs-title {
            font-weight: bold;
            font-size: 17px;
            text-align: center;
            margin-bottom: 14px;
            letter-spacing: 1px;
            color: #007bff;
        }
        .quick-nav-btn {
            display: block;
            width: 100%;
            background: linear-gradient(90deg, #e3f0ff 0%, #f7faff 100%);
            color: #007bff !important;
            border: none;
            border-radius: 10px;
            padding: 13px 0;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            text-align: center;
            text-decoration: none !important;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.1s;
            box-shadow: 0 1px 6px rgba(0,123,255,0.06);
            cursor: pointer;
            letter-spacing: 0.5px;
        }
        .quick-nav-btn.active, .quick-nav-btn:hover, .quick-nav-btn:focus {
            background: linear-gradient(90deg, #007bff 60%, #00c6ff 100%);
            color: #fff !important;
            box-shadow: 0 2px 12px rgba(0,123,255,0.13);
            text-decoration: none !important;
            transform: translateY(-2px) scale(1.03);
        }
        .profile-summary .post-btn {
            background: #28a745;
            color: #fff !important;
            border: none;
            border-radius: 24px;
            padding: 10px 32px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            margin-top: 14px;
            text-decoration: none !important;
            box-shadow: 0 1px 6px rgba(40,167,69,0.10);
            display: inline-block;
        }
        .profile-summary .post-btn:hover {
            background: #218838;
            color: #fff !important;
        }
        #post-type-dialog {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.25);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        #post-type-dialog .dialog-inner {
            background: #fff;
            padding: 32px 28px 24px 28px;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.18);
            min-width: 260px;
            max-width: 95vw;
            position: relative;
            text-align: center;
        }
        #post-type-dialog .quick-nav-btn {
            background: #007bff;
            color: #fff !important;
            margin-bottom: 12px;
        }
        #post-type-dialog .quick-nav-btn:hover {
            background: #0056b3;
            color: #fff !important;
        }
        #post-type-dialog .close-btn {
            position:absolute;
            top:10px;
            right:16px;
            font-size:1.5rem;
            color:#888;
            background:none;
            border:none;
            cursor:pointer;
            font-weight:bold;
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
        function openCommunitiesPopup() {
            document.getElementById('communities-popup-bg').style.display = 'flex';
        }
        function closeCommunitiesPopup() {
            document.getElementById('communities-popup-bg').style.display = 'none';
        }
        function joinCommunity(communityName) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "join_community.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var btn = document.querySelector('button[data-community="' + communityName + '"]');
                    if (btn) {
                        btn.innerText = "Joined";
                        btn.disabled = true;
                        btn.style.background = "#28a745";
                    }
                }
            };
            xhr.send("community=" + encodeURIComponent(communityName));
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
        
        <a href="index.php?page=following<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>"<?php if (isset($_GET['page']) && $_GET['page'] === 'following') echo ' class="nav-btn active"'; else echo ' class="nav-btn"'; ?> id="btn-following">Following</a>

        <a href="../users/chats.php" class="nav-btn">Chats</a>

    </div>
    <div class="search-bar-container">
        <form style="display:flex;align-items:center;width:100%;justify-content:center;gap:10px;" method="get" action="search.php">
            <input type="hidden" name="page" value="all">
            <input type="text" class="search-bar" placeholder="Search posts, users, or gear..." name="search">
            <button type="submit" class="search-btn">Search</button>
            
        </form>
    </div>

<?php
if   (isset($_GET['page']) && $_GET['page'] === 'all') {
    echo "<h1 style='text-align:center;'>All Posts</h1>";  
    } else if (isset($_GET['page']) && $_GET['page'] === 'posts') {
            echo "<h1 style='text-align:center;'>Posts</h1>";
                } else if (isset($_GET['page']) && $_GET['page'] === 'ads') {
                        echo "<h1 style='text-align:center;'>Marketplace</h1>";
                                                } else if (isset($_GET['page']) && $_GET['page'] === 'following') {
                                                    echo "<h1 style='text-align:center;'>Following</h1>";
                                                    } else {
                                                        echo "<h1 style='text-align:center;'>Feed</h1>";
                                                    }
?>

    <div class="main-layout">
        <!-- Left Sidebar: Profile Summary and Quick Navs -->
        <div style="display: flex; flex-direction: column; gap: 24px; width: 250px;">
            <div class="sidebar">
                <div class="profile-summary">
                    <img src="../users/profile_picture/<?php echo $profile_pic; ?>" class="profile-pic" alt="Profile">
                    <div class="username"><?php echo $_SESSION['username']; ?></div>
                    <div class="bio"><?php echo $bio; ?></div>
                    <a href="../users/profile.php?username=<?php echo $_SESSION['username']; ?>">View Profile &rarr;</a>
                    <div style="margin-top:12px;">
                        <button class="post-btn" onclick="openPostDialog()">Post</button>
                    </div>
                </div>
            </div>
            <div class="sidebar">
                <div class="quick-navs-title" style="font-weight:bold;font-size:17px;text-align:center;margin-bottom:14px;letter-spacing:1px;color:#007bff;">Quick Navs</div>
                <div style="display: flex; flex-direction: column; gap: 0;">
                    <a href="index.php" class="quick-nav-btn<?php if (!isset($_GET['page']) || $_GET['page'] === 'feed') echo ' active'; ?>">
                        <?php echo (isset($_GET['page']) && $_GET['page'] === 'posts') ? 'Posts' : 'Home Feed'; ?>
                    </a>
                    <a href="search.php?page=trips&search=trip" class="quick-nav-btn<?php if (isset($_GET['page']) && $_GET['page'] === 'trips') echo ' active'; ?>">Trips</a>
                    <a href="search.php?page=uncycle&search=sell" class="quick-nav-btn<?php if (isset($_GET['page']) && $_GET['page'] === 'uncycle') echo ' active'; ?>">Upcycle</a>
                    <a href="index.php?page=ads" class="quick-nav-btn<?php if (isset($_GET['page']) && $_GET['page'] === 'market') echo ' active'; ?>">
                        Marketplace
                    </a>
                </div>
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
        <!-- Right Sidebar: Random Accounts and Communities -->
        <div style="display: block; width: 250px;">
            <div class="sidebar" style="margin-bottom:24px;">
                <div class="random-accounts-title">Suggested Accounts</div>
                <?php
                $getrandom3 = mysqli_query($conn, "SELECT * FROM users WHERE username != '$username' ORDER BY RAND() LIMIT 3");
                while ($row = mysqli_fetch_assoc($getrandom3)) {
                    $random_username = $row['username'];
                    $random_picture = $row['picture'] ? $row['picture'] : 'default_profile_pic.png';
                    echo "
                        <div class='random-account'>
                            <img src='../users/profile_picture/$random_picture' class='profile-pic' alt='Profile'>
                            <span class='username'>$random_username</span>
                            <a href='../users/profile.php?username=$random_username'><button class='follow-btn'>view</button></a>
                        </div>
                    ";
                }
                ?>
            </div>
            <div class="sidebar">
                <div class="random-accounts-title">Suggested Communities</div>
                <?php
                $gettop3 = mysqli_query($conn, "
                    SELECT c.* FROM communities c
                    LEFT JOIN join_comm j ON c.name = j.community AND j.username = '$username'
                    WHERE j.username IS NULL
                    ORDER BY c.members DESC LIMIT 3
                ");
                while ($row = mysqli_fetch_assoc($gettop3)) {
                    $community_name = $row['name'];
                    $community_id = $row['id'];
                    $pic_res = mysqli_query($conn, "SELECT picture FROM communities WHERE id = '$community_id' LIMIT 1");
                    $pic_row = mysqli_fetch_assoc($pic_res);
                    $community_picture = $pic_row && $pic_row['picture'] ? $pic_row['picture'] : 'default_community_pic.png';
                    echo "
                        <div class='random-account'>
                            <img src='../assests/community_pic/$community_picture' class='profile-pic' alt='Community'>
                            <a href='../communities/community.php?id=$community_id' style='font-weight:bold;color:#007bff;text-decoration:none;' class='username'>$community_name</a>
                            <button class='follow-btn' type='button' data-community=\"" . htmlspecialchars($community_name, ENT_QUOTES) . "\" onclick=\"joinCommunity('" . htmlspecialchars($community_name, ENT_QUOTES) . "')\">Join</button>
                        </div>
                    ";
                }
                ?>
                <div style="text-align:right; margin-top:8px;">
                    <a href="javascript:void(0);" onclick="openCommunitiesPopup()" style="font-size:13px;color:#007bff;text-decoration:none;font-weight:bold;">View more &rarr;</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Communities Popup -->
    <div class="popup-bg" id="communities-popup-bg" onclick="if(event.target===this)closeCommunitiesPopup();">
        <div class="popup-content">
            <button class="popup-close" onclick="closeCommunitiesPopup()">&times;</button>
            <div class="popup-title">All Communities</div>
            <div class="popup-community-list">
                <?php
                // Get all communities and check if user joined
                $getall = mysqli_query($conn, "
                    SELECT c.*, 
                        CASE WHEN j.username IS NULL THEN 0 ELSE 1 END AS joined
                    FROM communities c
                    LEFT JOIN join_comm j ON c.name = j.community AND j.username = '$username'
                    ORDER BY c.members DESC
                ");
                while ($row = mysqli_fetch_assoc($getall)) {
                    $community_id = $row['id'];
                    $community_name = $row['name'];
                    $community_picture = $row['picture'] ? $row['picture'] : 'default_community_pic.png';
                    $members = $row['members'];
                    $joined = $row['joined'];
                    $community_url = "../communities/community.php?id=" . urlencode($community_id);
                    echo "
                        <div class='popup-community'>
                            <img src='../assests/community_pic/$community_picture' class='profile-pic' alt='Community'>
                            <a href='$community_url' class='community-name' style='color:#007bff;text-decoration:none;font-weight:bold;' target='_blank'>$community_name</a>
                            <span class='community-tag'>$members members</span>";
                    if ($joined) {
                        echo "<span style='margin-left:auto;background:#28a745;color:#fff;padding:6px 18px;border-radius:16px;font-weight:bold;font-size:14px;'>Joined</span>";
                    } else {
                        echo "<button class='join-btn' type='button' data-community=\"" . htmlspecialchars($community_name, ENT_QUOTES) . "\" onclick=\"joinCommunity('" . htmlspecialchars($community_name, ENT_QUOTES) . "')\">Join now</button>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>
    <!-- Post Type Dialog -->
    <div id="post-type-dialog">
        <div class="dialog-inner">
            <button onclick="closePostDialog()" class="close-btn">&times;</button>
            <div style="font-size:1.2rem;font-weight:bold;color:#007bff;margin-bottom:18px;">Create a Post</div>
            <div style="display:flex;flex-direction:column;gap:16px;">
                <a href="../users/add_post.php?type=text" class="quick-nav-btn">Text Post</a>
                <a href="../users/add_post.php?type=picture" class="quick-nav-btn">Picture Post</a>
                <a href="../users/add_post.php?type=ad" class="quick-nav-btn">Ad</a>
            </div>
        </div>
    </div>
    <script>
        function openPostDialog() {
            document.getElementById('post-type-dialog').style.display = 'flex';
        }
        function closePostDialog() {
            document.getElementById('post-type-dialog').style.display = 'none';
        }
    </script>
</body>
</html>
<?php

?>
?>
