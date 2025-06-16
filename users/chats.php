<?php
include '../connect/connect.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Handle sending a message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiver'], $_POST['message'])) {
    $receiver = mysqli_real_escape_string($conn, $_POST['receiver']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    if ($receiver && $message) {
        $stmt = $conn->prepare("INSERT INTO messages (sender, receiver, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $receiver, $message);
        $stmt->execute();
        $stmt->close();
    }
    // Redirect to avoid resubmission
    header("Location: chats.php?user=" . urlencode($receiver));
    exit();
}

// Get selected chat user
$chat_user = isset($_GET['user']) ? $_GET['user'] : '';

// Fetch all users except self
$users = mysqli_query($conn, "SELECT username, picture, name FROM users WHERE username != '$username' ORDER BY name ASC");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Chats</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; margin:0; }
        .chat-layout { display: flex; max-width: 900px; margin: 40px auto; background: #fff; border-radius: 14px; box-shadow: 0 4px 18px rgba(0,0,0,0.09); min-height: 500px; }
        .user-list { width: 220px; border-right: 1px solid #eee; padding: 24px 0 24px 0; background: #f9f9fb; border-radius: 14px 0 0 14px; }
        .user-list-title { text-align: center; font-weight: bold; color: #007bff; margin-bottom: 18px; font-size: 18px; }
        .user-link { display: flex; align-items: center; padding: 10px 18px; text-decoration: none; color: #222; border-bottom: 1px solid #f2f2f2; transition: background 0.15s; }
        .user-link:hover, .user-link.active { background: #eaf4ff; }
        .user-list .profile-pic { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; margin-right: 12px; border: 2px solid #007bff; }
        .user-list .user-name { font-weight: bold; font-size: 15px; }
        .chat-main { flex: 1; padding: 32px 28px 24px 28px; display: flex; flex-direction: column; }
        .chat-header { font-size: 1.2rem; font-weight: bold; color: #007bff; margin-bottom: 18px; }
        .chat-box-fixed {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 400px;
            max-height: 400px;
            height: 400px;
            background: #f7faff;
            border-radius: 8px;
            margin-bottom: 18px;
            overflow: hidden;
        }
        .messages-box {
            flex: 1;
            overflow-y: auto;
            padding: 18px 12px;
        }
        .message-row { margin-bottom: 14px; display: flex; }
        .message-row.sent { justify-content: flex-end; }
        .message-bubble { max-width: 60%; padding: 10px 16px; border-radius: 18px; font-size: 15px; background: #e3f0ff; color: #222; box-shadow: 0 1px 4px rgba(0,123,255,0.07); }
        .message-row.sent .message-bubble { background: #007bff; color: #fff; }
        .message-meta { font-size: 11px; color: #888; margin-top: 2px; }
        .send-form { display: flex; gap: 10px; }
        .send-form textarea { flex: 1; border-radius: 8px; padding: 10px; border: 1.5px solid #ccc; font-size: 15px; resize: none; }
        .send-form button { background: #007bff; color: #fff; border: none; border-radius: 8px; padding: 10px 24px; font-weight: bold; font-size: 15px; cursor: pointer; transition: background 0.2s; }
        .send-form button:hover { background: #0056b3; }
        .no-chat { color: #888; text-align: center; margin-top: 60px; font-size: 18px; }
        @media (max-width: 700px) {
            .chat-layout { flex-direction: column; min-height: 0; }
            .user-list { width: 100%; border-radius: 14px 14px 0 0; border-right: none; border-bottom: 1px solid #eee; }
            .chat-main { padding: 18px 8px 14px 8px; }
            .chat-box-fixed { min-height: 180px; max-height: 180px; height: 180px; }
        }
    </style>
</head>
<body>
    <div class="site-banner" style="width:100%;background:linear-gradient(90deg,#232526 0%,#414345 100%);color:#fff;text-align:center;padding:28px 0 10px 0;font-size:2.2rem;font-weight:bold;letter-spacing:2px;box-shadow:0 2px 8px rgba(0,0,0,0.06);margin-bottom:0;">
        photobase
        <div class="site-punchline" style="font-size:1.1rem;font-weight:normal;color:#ccc;margin-top:6px;letter-spacing:1px;">Chat with other photographers</div>
    </div>
    <div style="max-width:900px;margin:24px auto 0 auto;">
        <a href="../feed/index.php" style="background:#444; color:#fff; border:none; border-radius:24px; padding:10px 24px; font-weight:bold; font-size:15px; text-decoration:none; box-shadow:0 2px 8px rgba(0,0,0,0.06); display:inline-block; transition:background 0.2s; margin-bottom:18px;">
            &larr; Back to Feed
        </a>
    </div>
    <div class="chat-layout">
        <div class="user-list">
            <div class="user-list-title">All Users</div>
            <?php
            while ($row = mysqli_fetch_assoc($users)) {
                $u = $row['username'];
                $pic = $row['picture'] ? $row['picture'] : 'default_profile_pic.png';
                $n = $row['name'];
                $active = ($chat_user === $u) ? 'active' : '';
                echo "<a href='chats.php?user=" . urlencode($u) . "' class='user-link $active'>
                        <img src='../users/profile_picture/$pic' class='profile-pic' alt='Profile'>
                        <span class='user-name'>$n</span>
                    </a>";
            }
            ?>
        </div>
        <div class="chat-main">
            <?php if ($chat_user): ?>
                <div class="chat-header">
                    Chat with <?php echo htmlspecialchars($chat_user); ?>
                </div>
                <div class="chat-box-fixed">
                    <div class="messages-box" id="messages-box">
                        <?php
                        // Fetch messages between logged-in user and selected user
                        $msg_query = "
                            SELECT * FROM messages
                            WHERE (sender='$username' AND receiver='$chat_user')
                               OR (sender='$chat_user' AND receiver='$username')
                            ORDER BY created_at ASC
                        ";
                        $msgs = mysqli_query($conn, $msg_query);
                        while ($msg = mysqli_fetch_assoc($msgs)) {
                            $sent = $msg['sender'] === $username ? 'sent' : 'received';
                            $bubble = htmlspecialchars($msg['message']);
                            $meta = date('M d, H:i', strtotime($msg['created_at']));
                            echo "<div class='message-row $sent'>
                                    <div>
                                        <div class='message-bubble'>$bubble</div>
                                        <div class='message-meta'>$meta</div>
                                    </div>
                                  </div>";
                        }
                        ?>
                    </div>
                    <form class="send-form" method="post" action="chats.php?user=<?php echo urlencode($chat_user); ?>">
                        <input type="hidden" name="receiver" value="<?php echo htmlspecialchars($chat_user); ?>">
                        <textarea name="message" rows="2" placeholder="Type your message..." required></textarea>
                        <button type="submit">Send</button>
                    </form>
                </div>
                <script>
                    // Auto-scroll to bottom of messages
                    var box = document.getElementById('messages-box');
                    if (box) box.scrollTop = box.scrollHeight;
                </script>
            <?php else: ?>
                <div class="no-chat">Select a user to start chatting.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
