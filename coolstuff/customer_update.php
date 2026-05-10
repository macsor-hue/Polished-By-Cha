<?php
session_start();
include("../../connect/conn/database.php");

 $username = $_SESSION['user']['username'] ?? '';


 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../resources/style/user_header.css">
    <link rel="stylesheet" href="../../resources/style/update.css">
    <link rel="stylesheet" href="../../resources/style/alerts.css">

    <title>Account Settings | Polished By Cha</title>
    <link rel="icon" type="image/x-icon" href="../../resources/style/photos/header_icon.png">
</head>
<body>
    <?php if (!empty($_SESSION['flash'])): ?>
        <input type="checkbox" id="toggle-close" checked hidden>
        <div class="alert_overlay">
            <div class="alert_text <?php echo htmlspecialchars($_SESSION['flash']['type']); ?>">
                <p>
                    <?php echo htmlspecialchars($_SESSION['flash']['text']); ?>
                </p>
                <label for="toggle-close" class="close-overlay">
                 OK
                </label>
            </div>
        </div>
    <?php unset($_SESSION['flash']); endif; ?>
     <?php include '../../includes/customerHeader_features.php'; ?>
    
    <div class="page_title">
        <h1>Account Settings</h1>
        <p>Manage your login credentials securely</p>
    </div>
    <img src="../../resources/style/photos/update_photo2.png" alt="Update Photo" class="update_photo2">
    <img src="../../resources/style/photos/update_photo.png" alt="Update Photo" class="update_photo">
    <div class="update_flex">
        <div class="update_container">
            <form action="feature_code.php" method="POST"
                onsubmit="return confirm('Are you sure you want to update your account?');">
                <input type="hidden" name="action" value="users">
                <div class="update_info">
                    <h3 class="update_text">You may update your username, <br> password, or both.</h3><br><br>
                    <input type="hidden" name="action" value="users">
                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username" class="username" value="<?php echo $username;  ?>"><br><br>
                    <label for="password" class="password">Current Password:</label><br>
                    <input type="text" id="cpassword" name="cpassword" class="password" placeholder="Input Current Password"><br><br>
                    <label for="password" class="password">New Password:</label><br>
                    <input type="text" id="npassword" name="npassword" class="password" placeholder="Input New Password"><br><br>
                    <button type="submit" name="submit" class="update_box">Update</button>
                </div> 
            </form>
        </div>
    </div>
    <?php include '../../includes/footer_features.php'; ?>
</body> 
</html>