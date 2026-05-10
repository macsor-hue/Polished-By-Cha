<?php
session_start();
?>
<?php
    include("connect/conn/database.php");

        $username = $_SESSION['user']['username'] ?? '';
        $stmt=$conn->prepare("SELECT permission FROM accinfo WHERE username = ? LIMIT 1");
        if(!$stmt){
            flash('err' , 'Database error');
            redirect_login();
            exit();
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result=$stmt->get_result();
        $user = $result->fetch_assoc();
    ?>
<?php
        if(empty($_SESSION['user'])){
    ?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="resources/style/landing.css">
    <link rel="stylesheet" href="resources/style/sign.css">
    <link rel="stylesheet" href="resources/style/alerts.css">
    <link rel="stylesheet" href="resources/style/sign_footer.css">
    <title>Login Page | Polished By Cha</title>
    <link rel="icon" type="image/x-icon" href="resources/style/photos/header_icon.png">
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
    <?php include 'includes/log_header.php'; ?>
    <form action="code.php" method="POST">
        <input type="hidden" name="action" value="login">
        <section class="login_info">
            <div class="login_content">
                <div class="login_inside">
                    <h1 class="brand-in-login">Polished By Cha</h1>
                    <div class="brand-container-login">
                        <h2 class="tagline_in">Life Isn't Perfect,</h2>
                        <h2 class="tagline_in">But Your Nails Can Be!</h2>
                    </div>
                </div>
                <div class="login_inside">
                    <div class="info_container">
                        <h2 class="login_text">Login</h2><br>
                        <label for="username">Username:</label>
                        <input type="text" class="username" name="username"><br>
                        <label for="password">Password:</label>
                        <input type="password" class="password" name="password"><br>
                        <button type="submit" class="login_box" name="submit">LOGIN</button><br><br>
                        Don't have an account yet? <a href="main.php" class="register_link">Register here!</a>
                    </div>
                </div>
            </div>
        </section>
    </form>
    <?php include 'includes/footer.php'; ?>

</body>
</html>
<?php
        } else {
            header("Location: main.php");
            exit();
        }
?>
