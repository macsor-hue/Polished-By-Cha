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
        
        if(empty($_SESSION['user'])){
            $pageTitle = "Register | Polished By Cha";
        }
        elseif($user && $user['permission'] === 'yes'){
            $pageTitle = "Admin Dashboard | Polished By Cha";
        }
        else{
            $pageTitle = "Dashboard | Polished By Cha";
        }
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
    <title><?= $pageTitle ?></title>
    <link rel="icon" type="image/x-icon" href="resources/style/photos/header_icon.png">    
</head>
<body>

<?php    //Registration 
        if(empty($_SESSION['user'])){
?>
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
                        <h2 class="reg_text">Register</h2><br>
                        <label for="username">Username:</label>
                        <input type="text" class="username" name="username"><br>
                        <label for="password">Password:</label>
                        <input type="password" class="password" name="password"><br>
                        <button type="submit" class="reg_box" name="submit">REGISTER</button><br><br>
                        Already have an account? <a href="login.php" class="login_link">Login here!</a>
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/style/admin_header.css">
    <link rel="stylesheet" href="resources/style/admin_dashboard.css">
    <link rel="stylesheet" href="resources/style/alerts.css">
    <title><?= $pageTitle ?></title>
    <link rel="icon" type="image/x-icon" href="resources/style/photos/header_icon.png">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    <section id="adminDashboard" class="adminDashboard">
        <div class="dashboard_flex">
            <div class="dashboard_content">
                <img src="resources\style\photos\hello_dashboard.png" alt="hello image">
            </div>
            <div class="dashboard_content">
                <h1 class="desc_brand"> <span style="font-weight: lighter">Welcome,</span> Cha! </h1>
                <h3 class="greetings">Let's make today beautiful and productive</h3>
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
   <div class="table_calendar">
        <div class="table_container">
            <div class="table_title">
                <h1>Schedule for the Week</h1>
            </div>
            <div class="table_content">
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
                                if(in_array($dayname,['Monday','Tuesday','Wednesday','Thursday']))
                                    {
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
                                        <button type="button" disabled class="availableBtn">Available</button>
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
            </div>
        </div>
    </div>
    <?php include 'includes/admin_footer.php'; ?>
</body>
</html>
 

<?php   //customer ui
        }else{
            
       ?>
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/style/user_header.css">
    <link rel="stylesheet" href="resources/style/user_dashboard.css">
    <link rel="stylesheet" href="resources/style/alerts.css">
    <title><?= $pageTitle ?></title>
    <link rel="icon" type="image/x-icon" href="resources/style/photos/header_icon.png">
</head>
<body>
    <?php include 'includes/customer_header.php'; ?>
    <section id="userDashboard" class="userDashboard">
        <div class="dashboard_flex">
            <div class="dashboard_content">
                <img src="resources\style\photos\hello_dashboard.png" alt="hello image">
            </div>
            <div class="dashboard_content">
                <h1 class="desc_brand"> <span style="font-weight: lighter">Welcome, </span><?php echo htmlspecialchars($username); ?>!</h1>
                <h3 class="greetings">Ready for your next nail appointment?</h3>
            </div>
        </div>
    </section>
    <div class="table_calendar">
        <div class="table_container">
            <div class="table_title">
                <h1>Check what days are still available!</h1>
            </div>
            <div class="table_content">
                <?php
                    $startoftheweek = date('Y-m-d',strtotime('monday this week'));
                    $week=[];
                    for ($i=0;$i<7;$i++){
                        $week[]=date('Y-m-d',strtotime("$startoftheweek+$i days"));
                    }
                    $times = [
                            "9:00 AM","10:30 AM","12:00 PM","1:30 PM",
                            "3:00 PM","4:30 PM","6:00 PM","7:30 PM"
                    ];
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
                                        <button type="submit" class="availableBtn">Available</button>
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
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <?php } ?>
</body>
</html>