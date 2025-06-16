<?php
include '../connect/connect.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

$name = $user['name'];
$email = $user['email'];
$bio = $user['bio'];
$ig = isset($user['ig']) ? $user['ig'] : '';
$profile_pic = $user['picture'] ? $user['picture'] : 'default.png';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $new_ig = mysqli_real_escape_string($conn, $_POST['ig']);

    // Handle profile picture upload if provided
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "profile_picture/";
        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file);
        $profile_pic_update = ", picture='" . mysqli_real_escape_string($conn, $_FILES["profile_pic"]["name"]) . "'";
    } else {
        $profile_pic_update = "";
    }

    $update_query = "UPDATE users SET name='$new_name', email='$new_email', bio='$new_bio', ig='$new_ig' $profile_pic_update WHERE username='$username'";
    mysqli_query($conn, $update_query);

    header("Location: self_profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; }
        .edit-container {
            max-width: 480px;
            margin: 40px auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.09);
            padding: 32px 28px 28px 28px;
        }
        h2 { text-align: center; color: #007bff; margin-bottom: 24px; }
        label { display: block; margin-bottom: 6px; color: #333; font-weight: bold; }
        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 18px;
            border: 1.5px solid #e0e0e0;
            border-radius: 6px;
            font-size: 15px;
            background: #f9f9fa;
        }
        textarea { resize: vertical; min-height: 60px; }
        .profile-pic-preview {
            display: block;
            margin: 0 auto 18px auto;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
        }
        .edit-btn {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 24px;
            padding: 12px 32px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s;
        }
        .edit-btn:hover {
            background: #0056b3;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2>Edit Profile</h2>
        <form method="post" enctype="multipart/form-data">
            <img src="profile_picture/<?php echo htmlspecialchars($profile_pic); ?>" class="profile-pic-preview" alt="Profile Picture">
            <label for="profile_pic">Change Profile Picture</label>
            <input type="file" name="profile_pic" id="profile_pic" accept="image/*">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <label for="bio">Bio</label>
            <textarea name="bio" id="bio"><?php echo htmlspecialchars($bio); ?></textarea>
            <label for="ig">Instagram Username</label>
            <input type="text" name="ig" id="ig" value="<?php echo htmlspecialchars($ig); ?>">
            <button type="submit" class="edit-btn">Save Changes</button>
        </form>
        <a href="self_profile.php" class="back-link">&larr; Back to Profile</a>
    </div>
</body>
</html>
