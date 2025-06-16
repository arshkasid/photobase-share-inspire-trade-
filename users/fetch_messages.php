<?php
include '../connect/connect.php';
session_start();

if (!isset($_SESSION['username']) || !isset($_GET['user'])) exit;

$username = $_SESSION['username'];
$chat_user = mysqli_real_escape_string($conn, $_GET['user']);

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
