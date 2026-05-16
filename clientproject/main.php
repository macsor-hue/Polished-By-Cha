<?php
// SESSION & DATABASE INITIALIZATION
session_start();
include("connect/conn/database.php");

    // PERMISSION CHECK
    // Fetches the logged-in user's permission
    // level from the database
    $username = $_SESSION['user']['username'] ?? '';
    $stmt=$conn->prepare("SELECT permission FROM accinfo WHERE username = ? LIMIT 1");

    // DATABASE ERROR HANDLING
    // Redirects the user if the query fails
    if(!$stmt){
        flash('err' , 'Database error');
            redirect_login();
            exit();
        }

    // Executes the prepared statement
    // and stores the user data
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result=$stmt->get_result();
    $user = $result->fetch_assoc();
        
    // DYNAMIC PAGE TITLE
    // Changes the browser title depending
    // on the current user type
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

    <!-- STYLESHEETS -->
    <link rel="stylesheet" href="resources/style/landing.css">
    <link rel="stylesheet" href="resources/style/sign.css">
    <link rel="stylesheet" href="resources/style/alerts.css">
    <link rel="stylesheet" href="resources/style/sign_footer.css">

    <!-- PAGE TITLE -->
    <title><?= $pageTitle ?></title>

    <!-- WEBSITE ICON -->
    <link rel="icon" type="image/x-icon" href="resources/style/photos/header_icon.png">    
</head>
<body>

<?php    
    // REGISTRATION PAGE
    // Displays the registration form
    // for guests or non-logged-in users
    if(empty($_SESSION['user'])){
?>

    <!-- FLASH MESSAGE ALERT
         Displays success/error messages
         then clears the session flash data -->
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

    <!-- HEADER INCLUDE -->
    <?php include 'includes/log_header.php'; ?>

    <!-- REGISTRATION FORM
         Sends user registration data
         to code.php -->
    <form action="code.php" method="POST">

        <input type="hidden" name="action" value="register">

        <section class="reg_info">
            <div class="reg_content">

                <!-- BRAND PANEL -->
                <div class="reg_inside">
                    <h1 class="brand-in">Polished By Cha</h1>

                    <div class="brand-container">
                        <h2 class="tagline_in">Life Isn't Perfect,</h2>
                        <h2 class="tagline_in2">But Your Nails Can Be!</h2>
                    </div>
                </div>

                <!-- REGISTRATION INPUT PANEL -->
                <div class="reg_inside">
                    <div class="info_container">

                        <h2 class="reg_text">Register</h2><br>

                        <!-- USERNAME FIELD -->
                        <div class="reg_cont">
                            <label for="username">Username:</label>
                            <input type="text" class="username_reg" name="username"><br>
                        </div>

                        <!-- PASSWORD FIELD -->
                        <div class="reg_cont2">
                            <label for="password">Password:</label>
                            <input type="password" class="password_reg" name="password"><br>
                        </div>

                        <!-- SUBMIT BUTTON -->
                        <button type="submit" class="reg_box" name="submit">REGISTER</button><br><br>

                        <!-- LOGIN LINK -->
                        Already have an account? 
                        <a href="login.php" class="login_link">Login here!</a>

                    </div>
                </div>
            </div>
        </section>
    </form>

    <!-- FOOTER INCLUDE -->
    <?php include 'includes/footer.php'; ?> 
</body>
</html>


<?php 
    // ADMIN DASHBOARD
    // Displays the admin interface
    // if the user has admin permission
    } elseif ($user && $user['permission'] === 'yes'){   
?>

    <!-- FLASH MESSAGE ALERT -->
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

    <!-- ADMIN STYLESHEETS -->
    <link rel="stylesheet" href="resources/style/admin_header.css">
    <link rel="stylesheet" href="resources/style/admin_dashboard.css">
    <link rel="stylesheet" href="resources/style/alerts.css">

    <!-- PAGE TITLE -->
    <title><?= $pageTitle ?></title>

    <!-- WEBSITE ICON -->
    <link rel="icon" type="image/x-icon" href="resources/style/photos/header_icon.png">
</head>

<body>

    <!-- ADMIN HEADER -->
    <?php include 'includes/admin_header.php'; ?>

    <!-- ADMIN DASHBOARD WELCOME SECTION -->
    <section id="adminDashboard" class="adminDashboard">
        <div class="dashboard_flex">

            <div class="dashboard_content">
                <img src="resources\style\photos\hello_dashboard.png" alt="hello image">
            </div>

            <div class="dashboard_content">
                <h1 class="desc_brand"> 
                    <span style="font-weight: lighter">Welcome,</span> Cha! 
                </h1>

                <h3 class="greetings">
                    Let's make today beautiful and productive
                </h3>
            </div>
        </div>
    </section>
    

   <?php
   // WEEKLY CALENDAR GENERATION
   // Creates an array containing
   // the current week's dates
   $startoftheweek = date('Y-m-d',strtotime('monday this week'));

   $week=[];

   for ($i=0;$i<7;$i++){
     $day=date('Y-m-d',strtotime("$startoftheweek+$i days"));
     if(!in_array(date('l',strtotime($day)),['Monday','Tuesday','Wednesday','Thursday'])){
         $week[]=$day;
     }
}
   // APPOINTMENT TIME SLOTS
   $times = [
        "9:00 AM","10:30 AM","12:00 PM","1:30 PM",
        "3:00 PM","4:30 PM","6:00 PM","7:30 PM"
   ];
   ?>

   <!-- ADMIN APPOINTMENT TABLE -->
   <div class="table_calendar">
        <div class="table_container">

            <div class="table_title">
                <h1>Schedule for the Week</h1>
            </div>

            <div class="table_content">
                <table>

                    <!-- TABLE HEADER -->
                    <thead>
                        <th>Time</th>

                        <?php foreach($week as $day):?>
                        <th>
                            <?php echo date('D',strtotime($day));?><br>
                            <?php echo $day;?>
                        </th>
                        <?php endforeach;?>
                    </thead>

                    <!-- TABLE BODY -->
                    <tbody>

                        <?php foreach($times as $time):?>
                            <tr>
                                <!-- TIME SLOT -->
                                <td><?php echo $time;?></td>     
                            <?php foreach($week as $day):?>

                            <td>

                            <?php 
                                // DAY CHECK
                                // Monday to Thursday are marked unavailable
                                $dayname=date('l',strtotime($day));
                                if(in_array($dayname,['Monday','Tuesday','Wednesday','Thursday']))
                                    {
                                        echo"At School (Unavailable)";
                                    }

                                // APPOINTMENT CHECK
                                // Checks if the selected schedule
                                // already has a booking
                                else{
                                    $stmt=$conn->prepare("SELECT * FROM clientinfo WHERE appointment_date=? AND appointment_time=? AND appointment_status='approved'");
                                    $stmt->bind_param("ss",$day,$time);
                                    $stmt->execute();
                                    $result=$stmt->get_result();
                                    $row=$result->fetch_assoc();
                                    // BOOKED SLOT
                                    if($result->num_rows>0){
                                      
                                             echo 'BOOKED BY ' . htmlspecialchars($row["customer_name"]) . '<br>';
                                                echo '<small>Status: ' . htmlspecialchars($row["appointment_status"]) . '</small><br>';
                                                            
                                    }

                                    // AVAILABLE SLOT
                                    else{
                                        echo '<button type="submit" disable     class="availableBtn">
                                            Available
                                        </button>';
                                
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

    <!-- ADMIN FOOTER -->
    <?php include 'includes/admin_footer.php'; ?>

</body>
</html>
 

<?php   
    // CUSTOMER DASHBOARD
    // Displays the regular customer interface
    // for logged-in non-admin users
    }else{
?>

    <!-- FLASH MESSAGE ALERT -->
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

    <!-- CUSTOMER STYLESHEETS -->
    <link rel="stylesheet" href="resources/style/user_header.css">
    <link rel="stylesheet" href="resources/style/user_dashboard.css">
    <link rel="stylesheet" href="resources/style/alerts.css">

    <!-- PAGE TITLE -->
    <title><?= $pageTitle ?></title>

    <!-- WEBSITE ICON -->
    <link rel="icon" type="image/x-icon" href="resources/style/photos/header_icon.png">
</head>

<body>

    <!-- CUSTOMER HEADER -->
    <?php include 'includes/customer_header.php'; ?>

    <!-- CUSTOMER DASHBOARD WELCOME SECTION -->
    <section id="userDashboard" class="userDashboard">
        <div class="dashboard_flex">

            <div class="dashboard_content">
                <img src="resources\style\photos\hello_dashboard.png" alt="hello image">
            </div>

            <div class="dashboard_content">
                <h1 class="desc_brand"> 
                    <span style="font-weight: lighter">Welcome, </span>
                    <?php echo htmlspecialchars($username); ?>!
                </h1>

                <h3 class="greetings">
                    Ready for your next nail appointment?
                </h3>
            </div>
        </div>
    </section>

    <!-- CUSTOMER APPOINTMENT TABLE -->
    <div class="table_calendar">
        <div class="table_container">

            <div class="table_title">
                <h1>Check what days are still available!</h1>
            </div>

            <div class="table_content">

                <?php
                    // WEEKLY CALENDAR GENERATION
                    $startoftheweek = date('Y-m-d',strtotime('monday this week'));

                    $week=[];

                    for ($i=0;$i<7;$i++){
    $day=date('Y-m-d',strtotime("$startoftheweek+$i days"));
    if(!in_array(date('l',strtotime($day)),['Monday','Tuesday','Wednesday','Thursday'])){
        $week[]=$day;
    }
}
                    // APPOINTMENT TIME SLOTS
                    $times = [
                            "9:00 AM","10:30 AM","12:00 PM","1:30 PM",
                            "3:00 PM","4:30 PM","6:00 PM","7:30 PM"
                    ];
                ?>

                <table>

                    <!-- TABLE HEADER -->
                    <thead>
                        <th>Time</th>

                        <?php foreach($week as $day):?>
                        <th>
                            <?php echo date('D',strtotime($day));?><br>
                            <?php echo $day;?>
                        </th>
                        <?php endforeach;?>

                    </thead>

                    <!-- TABLE BODY -->
                    <tbody>

                        <?php foreach($times as $time):?>

                        <tr>

                            <!-- TIME SLOT -->
                            <td><?php echo $time;?></td>

                            <?php foreach($week as $day):?>

                            <td>

                            <?php 
                                // DAY CHECK
                                // Monday to Thursday are unavailable
                                $dayname=date('l',strtotime($day));

                                if(in_array($dayname,['Monday','Tuesday','Wednesday','Thursday'])){
                                    echo"At School (Unavailable)";
                                }

                                // APPOINTMENT CHECK
                                // Checks if the schedule
                                // is already booked
                                else{

                                    $stmt=$conn->prepare("SELECT * FROM clientinfo WHERE appointment_date=? AND appointment_time=? AND appointment_status='approved'");                                         $stmt->bind_param("ss",$day,$time);
                                    $stmt->execute();
                                    $result=$stmt->get_result();

                                    // BOOKED SLOT
                                    if($result->num_rows>0){
                                        echo "BOOKED";
                                    }

                                    // AVAILABLE SLOT
                                    else{
                                        echo ' <form action="book.php" method="post">
                                        <input type="hidden" name="time" value="'.$time.'">
                                        <input type="hidden" name="date" value="'.$day.'">
                                        <button type="submit" class="availableBtn">
                                            Available
                                        </button>
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

    <!-- FOOTER INCLUDE -->
    <?php include 'includes/footer.php'; ?>

    <?php } ?>

</body>
</html>