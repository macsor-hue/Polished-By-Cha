<?php
session_start();
include("../../connect/conn/database.php");


if (!empty($_SESSION['flash'])){
        $type = $_SESSION['flash']['type'];
        $text = $_SESSION['flash']['text'];

        echo "<div class='msg " . htmlspecialchars($type) . "'>" . htmlspecialchars($text). "</div>";

        unset($_SESSION['flash']);
    }

 $username = $_SESSION['user']['username'] ?? '';


 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../resources/style/update.css">
    <title>Cha's Nails</title>
    <link rel="icon" type="image/x-icon" href="../../resources/style/photos/header_icon.png">
</head>
<body id="update_body">
    <form action="feature_code.php" method="POST">
        <nav class="navbar">
            <div class="navdiv">
                <div class="update-nav">
                    <img src="../../resources/style/photos/logo.jpg" alt="Image of Logo" class="float-img">
                        <h1 id="update_text">Account Update</h1>
                </div>
                <div class="nav_home">
                    <ul>
                        <li><a href="/clientproject/index.php">Home</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="update_info">
            <h3>You can change either username or password only, or both</h3><br>
            <input type="hidden" name="action" value="users">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $username;  ?>"><br>
            <label for="password">Current Password:</label>
            <input type="text" id="cpassword" name="cpassword" placeholder="Input Current Password"><br>
            <label for="password">New Password:</label>
            <input type="text" id="npassword" name="npassword" placeholder="Input New Password"><br>
            <button type="submit" name="submit">Update</button>
        </div> 
    </form>
</body> 
</html>