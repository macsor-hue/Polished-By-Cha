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
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/style/header.css">
    <link rel="stylesheet" href="resources/style/style.css">
    <link rel="stylesheet" href="resources/style/alerts.css">
    <link rel="stylesheet" href="resources/style/sign_footer.css">
    <title>Cha's Nails</title>
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

<?php    //Registration 
        if(empty($_SESSION['user'])){
?>
    <header class="register_header"> 
        <div class="container">
            <a href="#" >
                <img src="resources/style/photos/header_icon.png" alt="Image of Logo" class="logo-img">
                <h2 id="brand_name">Polished By Cha</h2>
            </a>
        </div>
    </header>
    <form action="code.php" method="POST">
        <input type="hidden" name="action" value="register">
        <section class="reg_info">
            <div class="reg_content">
                <div class="reg_inside">
                    <h1 class="brand-in">Polished By Cha</h1>
                    <div class="brand-container">
                        <h2 class="tagline_in">Life Isn't Perfect,</h2>
                        <h2 class="tagline_in">But Your Nails Can Be!</h2>
                    </div>
                </div>
                <div class="reg_inside">
                    <div class="info_container">
                        <h2 id="reg_text">Register</h2><br>
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username"><br>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password"><br>
                        <button type="submit" id="reg_box" name="submit">REGISTER</button><br><br>
                        Already have an account? <a href="login.php">Login here!</a>
                    </div>
                </div>
            </div>
        </section>
    </form>
    <?php include 'includes/footer.php'; ?> 
</body>
</html>


<?php //admin ui
        } elseif ($user && $user['permission'] === 'yes'){   
           
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/style/dashboard.css">
    <title>Cha's Nails</title>
    <link rel="icon" type="image/x-icon" href="resources/style/photos/header_icon.png">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    <section id="home" class="home">
        <div class="home_content">
            <h1>Welcome to </h1>
            <h1 class="desc_brand">Polished By Cha!</h1>
            <h2>life isn't perfect, but your nails can be</h2>
            <div class="landing_button">
                <a href="main.php"> BOOK AN APPOINTMENT NOW</a>
            </div>
        </div>
    </section>            
   <?php
   $startoftheweek = date('Y-m-d',strtotime('monday this week'));
   $week=[];
   for ($i=0;$i<7;$i++){
    $week[]=date('Y-m-d',strtotime("$startoftheweek+$i days"));
   }
   $times = [
    "9:00 AM","10:30 AM","12:00 PM","1:30 PM",
    "3:00 PM","4:30 PM","6:00 PM","7:30 PM"];
   ?>
   <table>
    <thead>
        <th>Time</th>
        <?php foreach($week as $day):?>
        <th><?php echo date('D',strtotime($day));?><br><?php echo $day;?></th>
        <?php endforeach;?>
    </thead>

    <tbody>
        <?php foreach($times as $time):?>
            <tr>
                <td><?php echo $time;?></td>
          
            
            <?php foreach($week as $day):?>
            <td>
            <?php $dayname=date('l',strtotime($day));
            if(in_array($dayname,['Monday','Tuesday','Wednesday','Thursday'])){
                echo"At School (Unavailable)";
            }
            else{
                $stmt=$conn->prepare("SELECT * FROM clientinfo WHERE appointment_date=? and appointment_time=?");
                $stmt->bind_param("ss",$day,$time);
                $stmt->execute();
                $result=$stmt->get_result();

                if($result->num_rows>0){
                    echo "BOOKED";
                }
                else{
                    echo ' <form action="book.php" method="post">
    <input type="hidden" name="time" value="'.$time.'">
    <input type="hidden" name="date" value="'.$day.'">
    <button type="submit">Available</a></button>
  </form>';
                
                }
            }
            ?>
            </td>
            
            <?php endforeach;?>
             </tr>
             <?php endforeach;?>
    </tbody>
   </table>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
 

<?php   //customer ui
        }else{
       ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/style/user_header.css">
    <title>Cha's Nails</title>
    <link rel="icon" type="image/x-icon" href="resources/style/photos/header_icon.png">
</head>
<body>
    <?php include 'includes/user_header.php'; ?>
    <section id="home" class="home">
        <div class="home_content">
            <h1>Welcome to </h1>
            <h1 class="desc_brand">Polished By Cha!</h1>
            <h2>life isn't perfect, but your nails can be</h2>
            <div class="landing_button">
                <a href="main.php"> BOOK AN APPOINTMENT NOW</a>
            </div>
        </div>

    </section>
   <?php
   $startoftheweek = date('Y-m-d',strtotime('monday this week'));
   $week=[];
   for ($i=0;$i<7;$i++){
    $week[]=date('Y-m-d',strtotime("$startoftheweek+$i days"));
   }
   $times = [
    "9:00 AM","10:30 AM","12:00 PM","1:30 PM",
    "3:00 PM","4:30 PM","6:00 PM","7:30 PM"];
   ?>
        <table>
            <thead>
                <th>Time</th>
                <?php foreach($week as $day):?>
                <th><?php echo date('D',strtotime($day));?><br><?php echo $day;?></th>
                <?php endforeach;?>
            </thead>

            <tbody>
                <?php foreach($times as $time):?>
                <tr>
                    <td><?php echo $time;?></td>
          
            
                    <?php foreach($week as $day):?>
                    <td>
                        <?php $dayname=date('l',strtotime($day));
                        if(in_array($dayname,['Monday','Tuesday','Wednesday','Thursday'])){
                            echo"At School (Unavailable)";
                        }
                        else{
                            $stmt=$conn->prepare("SELECT * FROM clientinfo WHERE appointment_date=? and appointment_time=?");
                            $stmt->bind_param("ss",$day,$time);
                            $stmt->execute();
                            $result=$stmt->get_result();

                            if($result->num_rows>0){
                                echo "BOOKED";
                            }
                            else{
                                echo ' <form action="book.php" method="post">
                                <input type="hidden" name="time" value="'.$time.'">
                                <input type="hidden" name="date" value="'.$day.'">
                                <button type="submit">Available</a></button>
                                </form>';
                            }
                        }
                        ?>
                    </td>
                    <?php endforeach;?>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <?php include 'includes/footer.php'; ?>
        <?php } ?>
    </body>
</html>