<?php

$search_item=$_GET['search'];

$getaccountq = "SELECT * FROM users WHERE username LIKE '%$search_item%' OR name LIKE '%$search_item%'";



$getaccount = mysqli_query($conn, $getaccountq);
if ($getaccount && $getaccount->num_rows > 0): ?>
    <style>
        .account-cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 22px;
            justify-content: center;
        }
        .account-card {
            background: #f7f7fa;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
            width: 240px;
            padding: 16px 16px 12px 16px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            transition: box-shadow 0.2s, transform 0.2s;
            border: 1.5px solid #e0e0e0;
        }
        .account-card:hover {
            box-shadow: 0 6px 24px rgba(0,123,255,0.10);
            transform: translateY(-2px) scale(1.025);
            border-color: #b3d1ff;
        }
        .account-card-header {
            display: flex;
            align-items: center;
            width: 100%;
        }
        .account-card-img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #007bff;
            margin-right: 12px;
            background: #fff;
        }
        .account-card-username {
            font-weight: bold;
            font-size: 17px;
            color: #007bff;
            text-decoration: none;
            transition: color 0.2s;
        }
        .account-card-username:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        .account-card-bio {
            color: #444;
            font-size: 13px;
            text-align: left;
            margin-top: 10px;
            width: 100%;
            min-height: 32px;
        }
    </style>
    <div class="search-results">
    <div class="account-cards-container">
        <?php while($row = mysqli_fetch_assoc($getaccount)): ?>
            <div class="account-card">
                <div class="account-card-header">
                    <img src="<?php echo !empty($row['picture']) ? htmlspecialchars($row['picture']) : '../assests/default.png'; ?>" alt="Profile" class="account-card-img">
                    <a href="../users/profile.php?user=<?php echo urlencode($row['username']); ?>" class="account-card-username">
                        <?php echo htmlspecialchars($row['username']); ?>
                    </a>
                </div>
                <div class="account-card-bio">
                    <?php echo htmlspecialchars($row['bio']); ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    </div>
<?php else: ?>
    <div style="text-align:center; color:#888;">No accounts found.</div>
<?php endif; ?>