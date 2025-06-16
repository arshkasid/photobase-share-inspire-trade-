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
    </style>
</head>
<body>
    <div class="container">
        <a href="feed/index.php" class="back-link">&larr; Back to Feed</a>
        <div class="post">
            <div class="post-header">
                <a href="users/profile.php" class="user-link">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" class="profile-pic" alt="Profile">
                    photog_anna
                </a>
                <span class="post-time">2024-06-01 10:00</span>
            </div>
            <div class="post-content">Check out this sunrise!</div>
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80" class="post-img" alt="Sunrise">
        </div>
        <div class="comments-title">Comments</div>
        <div class="comment">
            <span class="comment-user">lensman_bob:</span> Amazing shot!
        </div>
        <div class="comment">
            <span class="comment-user">gear_guru:</span> Love the colors.
        </div>
        <form class="add-comment-form">
            <textarea placeholder="Add a comment..." rows="2"></textarea>
            <button type="submit">Comment</button>
        </form>
    </div>
</body>
</html>
