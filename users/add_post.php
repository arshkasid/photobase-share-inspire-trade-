<?php
include '../connect/connect.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$type = isset($_GET['type']) ? $_GET['type'] : 'text';

// Fetch only communities the user has joined for dropdown
$communities = [];
$res = mysqli_query($conn, "
    SELECT c.id, c.name 
    FROM communities c
    INNER JOIN join_comm j ON c.name = j.community
    WHERE j.username = '$username'
    ORDER BY c.name ASC
");
while ($row = mysqli_fetch_assoc($res)) {
    $communities[] = $row;
}

// Handle form submission
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caption = mysqli_real_escape_string($conn, $_POST['caption'] ?? '');
    $tags = mysqli_real_escape_string($conn, $_POST['tags'] ?? '');
    $community_id = intval($_POST['community'] ?? 0);

    // Get community name from id (optional)
    $comm_name = '';
    if ($community_id) {
        $comm_res = mysqli_query($conn, "SELECT name FROM communities WHERE id = $community_id LIMIT 1");
        if ($comm_row = mysqli_fetch_assoc($comm_res)) {
            $comm_name = $comm_row['name'];
        }
    }

    $picture = '';
    if ($type === 'picture' || $type === 'ad') {
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $picture = uniqid('post_', true) . '.' . $ext;
                move_uploaded_file($_FILES['picture']['tmp_name'], "../users/post_picture/$picture");
            } else {
                $error = "Invalid image format.";
            }
        } else {
            $error = "Please upload a picture.";
        }
    }

    $title = '';
    $price = '';
    if ($type === 'ad') {
        $title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
        $price = mysqli_real_escape_string($conn, $_POST['price'] ?? '');
    }

    if (!$error) {
        $stmt = $conn->prepare("INSERT INTO posts (username, caption, tags, community, picture, type, title, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssssss",
            $username,
            $caption,
            $tags,
            $comm_name,
            $picture,
            $type,
            $title,
            $price
        );
        $stmt->execute();
        $stmt->close();
        // Redirect to self_profile after posting
        header("Location: self_profile.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Post</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; }
        .form-container {
            max-width: 480px;
            margin: 48px auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.09);
            padding: 38px 32px 32px 32px;
        }
        .form-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 22px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
            color: #333;
        }
        input[type="text"], input[type="number"], textarea, select {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            background: #f9f9fa;
            transition: border 0.2s;
        }
        input[type="text"]:focus, input[type="number"]:focus, textarea:focus, select:focus {
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
        .success-msg {
            background: #e6f9e8;
            color: #28a745;
            border: 1px solid #b2e2c6;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 18px;
            text-align: center;
            font-weight: bold;
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
        .back-link {
            display: inline-block;
            margin-top: 18px;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            font-size: 15px;
            border-radius: 20px;
            padding: 8px 22px;
            background: #f5f5f7;
            transition: background 0.2s;
        }
        .back-link:hover { background: #e3f0ff; }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-title">Create a <?php echo ucfirst($type); ?> Post</div>
        <?php if ($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
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
            <div class="form-group">
                <label for="community">Community (optional)</label>
                <select name="community" id="community">
                    <option value="">Select a community</option>
                    <?php foreach ($communities as $comm): ?>
                        <option value="<?php echo $comm['id']; ?>"><?php echo htmlspecialchars($comm['name']); ?></option>
                    <?php endforeach; ?>
                </select>
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
            <button type="submit" class="form-btn">Post</button>
        </form>
        <a href="self_profile.php" class="back-link">&larr; Back to Profile</a>
    </div>
</body>
</html>
