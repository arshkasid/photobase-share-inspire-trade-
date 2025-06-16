<?php
include '../connect/connect.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../users/login.php");
    exit();
}

$community_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$community_id) {
    echo "<h2>Community not found.</h2>";
    exit();
}

// Fetch community info
$comm = mysqli_query($conn, "SELECT * FROM communities WHERE id = $community_id LIMIT 1");
$community = mysqli_fetch_assoc($comm);

if (!$community) {
    echo "<h2>Community not found.</h2>";
    exit();
}

$community_name = $community['name'];
$community_picture = $community['picture'] ? $community['picture'] : 'default_community_pic.png';
$members = $community['members'];
$username = $_SESSION['username'];

// Handle join/leave POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comm_action'])) {
    if ($_POST['comm_action'] === 'join') {
        $exists = mysqli_query($conn, "SELECT 1 FROM join_comm WHERE username='$username' AND community='$community_name'");
        if (mysqli_num_rows($exists) == 0) {
            mysqli_query($conn, "INSERT INTO join_comm (username, community) VALUES ('$username', '$community_name')");
            mysqli_query($conn, "UPDATE communities SET members = members + 1 WHERE name='$community_name'");
        }
    } elseif ($_POST['comm_action'] === 'leave') {
        mysqli_query($conn, "DELETE FROM join_comm WHERE username='$username' AND community='$community_name'");
        mysqli_query($conn, "UPDATE communities SET members = GREATEST(members - 1, 0) WHERE name='$community_name'");
    }
    header("Location: community.php?id=" . urlencode($community_id));
    exit();
}

// Check if user already joined
$is_joined = false;
$check = mysqli_query($conn, "SELECT 1 FROM join_comm WHERE username='$username' AND community='$community_name' LIMIT 1");
if (mysqli_num_rows($check) > 0) {
    $is_joined = true;
}

// --- Handle Add Post Dialog and Submission ---
$type = isset($_GET['type']) ? $_GET['type'] : '';
$show_post_dialog = in_array($type, ['text', 'picture', 'ad']);
$add_post_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_post_type'])) {
    $add_type = $_POST['add_post_type'];
    $caption = mysqli_real_escape_string($conn, $_POST['caption'] ?? '');
    $tags = mysqli_real_escape_string($conn, $_POST['tags'] ?? '');
    $picture = '';
    if ($add_type === 'picture' || $add_type === 'ad') {
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $picture = uniqid('post_', true) . '.' . $ext;
                move_uploaded_file($_FILES['picture']['tmp_name'], "../users/post_picture/$picture");
            } else {
                $add_post_error = "Invalid image format.";
            }
        } else {
            $add_post_error = "Please upload a picture.";
        }
    }
    $title = '';
    $price = '';
    if ($add_type === 'ad') {
        $title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
        $price = mysqli_real_escape_string($conn, $_POST['price'] ?? '');
    }
    if (!$add_post_error) {
        $stmt = $conn->prepare("INSERT INTO posts (username, caption, tags, community, picture, type, title, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssssss",
            $username,
            $caption,
            $tags,
            $community_name,
            $picture,
            $add_type,
            $title,
            $price
        );
        $stmt->execute();
        $stmt->close();
        header("Location: community.php?id=" . urlencode($community_id));
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($community_name); ?> - Community</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; }
        .community-header {
            max-width: 900px;
            margin: 32px auto 0 auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.09);
            padding: 32px 36px 24px 36px;
            display: flex;
            align-items: center;
            gap: 32px;
        }
        .community-pic {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
            background: #f4f6fb;
        }
        .community-info {
            flex: 1;
        }
        .community-title {
            font-size: 2rem;
            font-weight: bold;
            color: #232526;
            margin-bottom: 8px;
        }
        .community-meta {
            color: #555;
            font-size: 15px;
            margin-bottom: 10px;
        }
        .back-link {
            background: #444;
            color: #fff;
            padding: 10px 24px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: bold;
            font-size: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            margin-bottom: 18px;
            transition: background 0.2s;
            display: inline-block;
        }
        .back-link:hover { background: #222; color: #fff; }
        .posts-section {
            max-width: 900px;
            margin: 24px auto 0 auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.09);
            padding: 32px 36px 24px 36px;
        }
        .post {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 10px 10px 10px;
            margin-bottom: 14px;
            background: #fafbff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            position: relative;
            max-width: 700px;
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
            border-radius: 50%;
            border: 2px solid #007bff;
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
        .community-actions {
            margin-top: 12px;
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            align-items: center;
        }
        .community-btn {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 18px;
            padding: 10px 28px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
            margin: 0;
        }
        .community-btn:hover {
            background: #0056b3;
            color: #fff;
        }
        .community-btn.secondary {
            background: #28a745;
        }
        .community-btn.secondary:hover {
            background: #1e7e34;
        }
        .community-btn.leave {
            background: #dc3545;
        }
        .community-btn.leave:hover {
            background: #a71d2a;
        }
        .post-type-dialog {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.25);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .post-type-dialog.active { display: flex; }
        .post-type-dialog-inner {
            background: #fff;
            padding: 32px 28px 24px 28px;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.18);
            min-width: 260px;
            max-width: 95vw;
            position: relative;
            text-align: center;
        }
        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
            color: #333;
        }
        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            background: #f9f9fa;
            transition: border 0.2s;
        }
        input[type="text"]:focus, input[type="number"]:focus, textarea:focus {
            border: 1.5px solid #007bff;
            background: #fff;
        }
        input[type="file"] {
            margin-top: 6px;
        }
        .form-btn {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 24px;
            padding: 12px 32px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
            width: 100%;
        }
        .form-btn:hover {
            background: #0056b3;
        }
        .error-msg {
            background: #ffeaea;
            color: #dc3545;
            border: 1px solid #f5bcbc;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 18px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="community-header">
        <img src="../assests/community_pic/<?php echo htmlspecialchars($community_picture); ?>" class="community-pic" alt="Community">
        <div class="community-info">
            <div class="community-title"><?php echo htmlspecialchars($community_name); ?></div>
            <div class="community-meta"><?php echo $members; ?> members</div>
            <div class="community-actions">
                <form method="post" action="community.php?id=<?php echo urlencode($community_id); ?>" style="display:inline;">
                    <?php if ($is_joined): ?>
                        <button type="submit" name="comm_action" value="leave" class="community-btn leave">Leave</button>
                    <?php else: ?>
                        <button type="submit" name="comm_action" value="join" class="community-btn">Join</button>
                    <?php endif; ?>
                </form>
                <button class="community-btn secondary" onclick="openPostTypeDialog()">Post</button>
                <a href="../feed/index.php" class="community-btn" style="background:#444;">&larr; Back to Feed</a>
            </div>
          
        </div>
    </div>
    <!-- Post Type Dialog (choose type) -->
    <div id="post-type-dialog" class="post-type-dialog<?php if ($show_post_dialog) echo ' active'; ?>">
        <div class="post-type-dialog-inner">
            <button onclick="closePostTypeDialog()" style="position:absolute;top:10px;right:16px;font-size:1.5rem;color:#888;background:none;border:none;cursor:pointer;font-weight:bold;">&times;</button>
            <?php if (!$show_post_dialog): ?>
                <div style="font-size:1.2rem;font-weight:bold;color:#007bff;margin-bottom:18px;">Create a Post</div>
                <div style="display:flex;flex-direction:column;gap:16px;">
                    <a href="community.php?id=<?php echo urlencode($community_id); ?>&type=text" class="community-btn" style="background:#007bff;">Text Post</a>
                    <a href="community.php?id=<?php echo urlencode($community_id); ?>&type=picture" class="community-btn" style="background:#007bff;">Picture Post</a>
                    <a href="community.php?id=<?php echo urlencode($community_id); ?>&type=ad" class="community-btn" style="background:#007bff;">Ad</a>
                </div>
            <?php else: ?>
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="add_post_type" value="<?php echo htmlspecialchars($type); ?>">
                    <?php if ($type === 'ad'): ?>
                        <div class="form-group">
                            <label for="title">Title<span style="color:#dc3545;">*</span></label>
                            <input type="text" name="title" id="title" required>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="caption">Caption<span style="color:#dc3545;">*</span></label>
                        <textarea name="caption" id="caption" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="tags">Tags (comma separated)</label>
                        <input type="text" name="tags" id="tags">
                    </div>
                    <?php if ($type === 'picture' || $type === 'ad'): ?>
                        <div class="form-group">
                            <label for="picture">Picture<span style="color:#dc3545;">*</span></label>
                            <input type="file" name="picture" id="picture" accept="image/*" required>
                        </div>
                    <?php endif; ?>
                    <?php if ($type === 'ad'): ?>
                        <div class="form-group">
                            <label for="price">Price<span style="color:#dc3545;">*</span></label>
                            <input type="number" name="price" id="price" min="0" step="0.01" required>
                        </div>
                    <?php endif; ?>
                    <?php if ($add_post_error): ?>
                        <div class="error-msg"><?php echo $add_post_error; ?></div>
                    <?php endif; ?>
                    <button type="submit" class="form-btn">Post</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function openPostTypeDialog() {
            document.getElementById('post-type-dialog').classList.add('active');
        }
        function closePostTypeDialog() {
            document.getElementById('post-type-dialog').classList.remove('active');
            // Remove ?type=... from URL if present
            if (window.location.search.indexOf('type=') !== -1) {
                window.location.href = window.location.pathname + '?id=<?php echo urlencode($community_id); ?>';
            }
        }
    </script>
    <div class="posts-section">
        <h2 style="color:#007bff;">Posts in <?php echo htmlspecialchars($community_name); ?></h2>
        <?php
        $posts = mysqli_query($conn, "SELECT * FROM posts WHERE community = '$community_name' ORDER BY created_at DESC");
        if (mysqli_num_rows($posts) == 0) {
            echo "<p>No posts in this community yet.</p>";
        }
        while ($row = mysqli_fetch_assoc($posts)) {
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

            echo "<div class='post'>";
            echo "<div class='post-header'>";
            echo "<div class='post-header-left'>";
            echo "<a href='../users/profile.php?username=$postuser' class='user-link'>";
            echo "<img src='../users/profile_picture/$profile_pic' class='profile-pic' alt='Profile'>$name</a>";
            echo "</div>";
            echo "<span class='post-time'>$post_time</span>";
            echo "</div>";
            echo "<div class='post-content'>$post_content</div>";
            if ($post_type === 'picture' || $post_type === 'ad') {
                echo "<img src='../users/post_picture/$post_image' class='post-img' alt='Post Image'>";
            }
            if ($post_type === 'ad') {
                echo "<div class='ad'><strong>$post_title</strong><br>Price: $post_price<br><p>$post_content</p></div>";
            }
            echo "<a href='../comments.php?post_id=$post_id'><button class='comment-btn'>Comments</button></a>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
