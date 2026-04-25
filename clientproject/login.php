<?php
session_start();
?>

 <?php
    if (!empty($_SESSION['flash'])){
        $type = $_SESSION['flash']['type'];
        $text = $_SESSION['flash']['text'];

        echo "<div class='msg " . htmlspecialchars($type) . "'>" . htmlspecialchars($text). "</div>";

        unset($_SESSION['flash']);
    }
    ?>
<?php
        if(empty($_SESSION['user'])){
    ?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/style/header.css">
    <link rel="stylesheet" href="resources/style/style.css">
    <title>Cha's Nails|Login Page</title>
    <link rel="icon" type="image/x-icon" href="resources/style/photos/header_icon.png">
</head>
<body id="login">
    <form action="code.php" method="POST">
        <nav class="navbar">
            <div class="navdiv">
                <div class="nav_cont">
                    <input type="hidden" name="action" value="login">
                    <img src="resources/style/photos/logo.jpg" alt="Image of Logo" class="float-img">
                    <h2 id="welcome">Welcome Back to Polished By Cha</h2>
                </div>
            </div>
        </nav>
        <div class="login_info">
            <h2 id="login_text">Login</h2><br>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username"><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password"><br>
            <button type="submit" id="login_box" name="submit">Login</button><br><br>
        
        </div>
    </form>

Don't have an account yet? <a href="register.php">Register here!</a>

</body>
</html>
<?php
        } else {
            header("Location: register.php");
            exit();
        }
?>
