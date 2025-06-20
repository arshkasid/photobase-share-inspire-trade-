<?php
include 'connect/connect.php';

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
    <title>Post & Comments</title>
    <style>
        body { background: #f4f6fb; font-family: 'Segoe UI', Arial, sans-serif; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); padding: 32px 24px; }
        .post { margin-bottom: 32px; }
        .post-header { display: flex; align-items: center; margin-bottom: 12px; }
        .profile-pic { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; margin-right: 14px; border: 2px solid #007bff; }
        .user-link { text-decoration: none; color: #222; font-weight: bold; font-size: 18px; margin-right: 8px; }
        .post-time { color: #888; font-size: 13px; margin-left: auto; }
        .post-content { font-size: 16px; color: #333; margin-bottom: 10px; line-height: 1.6; }
        .post-img { max-width: 100%; height: auto; margin-top: 10px; border-radius: 8px; }
        .ad { background: #f9f9e3; padding: 12px 16px; margin-top: 10px; border-radius: 8px; border-left: 4px solid #ffc107; }
        .comments-title { font-size: 20px; font-weight: bold; margin-bottom: 16px; }
        .comment { background: #f7f7fa; border-radius: 8px; padding: 12px 16px; margin-bottom: 12px; }
        .comment-user { font-weight: bold; color: #007bff; }
        .add-comment-form textarea { width: 100%; border-radius: 8px; border: 1px solid #ccc; padding: 8px; resize: vertical; }
        .add-comment-form button { margin-top: 8px; background: #007bff; color: #fff; border: none; padding: 8px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .back-link { display: inline-block; margin-bottom: 24px; color: #007bff; text-decoration: none; }
        .comment-form textarea {
    width: 100%;
    border-radius: 8px;
    padding: 10px;
    border: 1.5px solid #ccc;
    font-size: 15px;
    margin-bottom: 8px;
    background: #f9f9fa;
    resize: vertical;
    outline: none;
    transition: border 0.2s;
}
.comment-form textarea:focus {
    border: 1.5px solid #007bff;
    background: #fff;
}
.comment-form .comment-btn {
    background: #007bff;
    color: #fff;
    border: none;
    padding: 8px 24px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    font-size: 15px;
    transition: background 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(0,123,255,0.07);
    text-decoration: none;
    display: inline-block;
}
.comment-form .comment-btn:hover {
    background: #0056b3;
}
a { text-decoration: none; }
    </style>
</head>
<body>

<?php

$postid=isset($_GET['post_id']) ? $_GET['post_id'] : null;

if (!$postid) {
    echo "<script>alert('Post not found'); window.location.href='feed/index.php';</script>";
    exit();
}

$GETPOSTDETAILS = mysqli_query($conn, "SELECT * FROM posts WHERE post_id = '$postid'");

$POSTDETAIL = mysqli_fetch_assoc($GETPOSTDETAILS);

$USERPOSTED = $POSTDETAIL['username'];
$GETPROFILE = mysqli_query($conn, "SELECT * FROM users WHERE username = '$USERPOSTED'");
$PROFILEDETAIL = mysqli_fetch_assoc($GETPROFILE);
$IMAGE = $PROFILEDETAIL['picture'] ? $PROFILEDETAIL['picture'] : 'default.png'; // Fallback to default if no image
$datediff = $POSTDETAIL['created_at'];
$caption = $POSTDETAIL['caption'] ? $POSTDETAIL['caption'] : '';   





?>
    <div class="container">
        <a href="feed/index.php" class="back-link">&larr; Back to Feed</a>
        <div class="post">
            <div class="post-header">
                <a href="users/profile.php" class="user-link">
                    <img src="users/profile_picture/<?php echo $IMAGE; ?>" class="profile-pic" alt="Profile">
                    <?php echo $USERPOSTED; ?>
                </a>
                <span class="post-time"><?php echo $datediff; ?></span>
            </div>
            
            <?php if ($POSTDETAIL['type'] === 'text') 
            {
                echo "<div class='post-content'>".$POSTDETAIL['caption']."</div>";
            } elseif ($POSTDETAIL['type'] === 'picture') {
                echo " <div class='post-content'>".$POSTDETAIL['caption']."</div><img src='users/post_picture/".$POSTDETAIL['picture']."' class='post-img' alt='Post Image'>
                                       ";
            } elseif ($POSTDETAIL['type'] === 'ad') {
                echo "<div class='ad'>".$POSTDETAIL['caption']."<br>Price: ".$POSTDETAIL['price']."</div>
                                <img src='users/post_picture/".$POSTDETAIL['picture']."' class='post-img' alt='Post Image'>";
            }
            
            
            
            ?>
                
           
        </div>
        <div class="comments-title">Comments</div>
<form method="post" class="comment-form" style="margin-top:24px;">
    <textarea name="comment" rows="3" placeholder="Add a comment..." required></textarea>
    <br>
    <button type="submit" class="comment-btn">Post Comment</button>
</form>

        <?php

        $getcomments = mysqli_query($conn, "SELECT * FROM comments WHERE post_id = '$postid' ORDER BY commented_on DESC");
        while ($comment = mysqli_fetch_assoc($getcomments)) {
            $comment_user = $comment['username'];
            $comment_text = $comment['comment'];
            

            echo "<div class='comment'>
                   <a href='users/profile.php?username=$comment_user'> <span class='comment-user'>$comment_user:</span> $comment_text</a>
                </div>";
        }
        // echo"<div class='comment'>
        //     <span class='comment-user'>lensman_bob:</span> Amazing shot!
        // </div>";
        
        

?>
        
    </div>
</body>
</html>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $username = $_SESSION['username'];

    if (!empty($comment)) {
        $insert_comment = mysqli_query($conn, "INSERT INTO comments (post_id, username, comment) VALUES ('$postid', '$username', '$comment')");
        if ($insert_comment) {
            echo "<script>window.location.href='comments.php?post_id=$postid';</script>";
        } else {
            echo "<script>alert('Failed to add comment. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Comment cannot be empty.');</script>";
    }
}
?>
