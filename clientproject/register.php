<?php
session_start();
include("connect/conn/database.php");
?>
    <?php
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
    <link rel="stylesheet" href="resources/style/register_header.css">
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
    <form action="code.php" method="POST">
        <input type="hidden" name="action" value="register">
        <header class="register_header"> 
            <div class="container">
                 <a href="#" >
                    <img src="resources/style/photos/header_icon.png" alt="Image of Logo" class="logo-img">
                    <h2 id="brand_name">Polished By Cha</h2>
                </a>
            </div>
    </header>
        <div class="reg_info">
            
                <h2 id="reg_text">Register</h2><br>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username"><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password"><br>
                <button type="submit" id="reg_box" name="submit">Register</button><br><br>
        </div>
    </form> 

Already have an account? <a href="login.php">Login here!</a>
</body>
</html>


<?php
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
<body id="dashboard_body">
    <nav class="navbar">
        <div class="navdiv">
            <div class="left-nav">
                <img src="resources/style/photos/logo.jpg" class="float-img">
                    <h2 id="dash_text">DASHBOARD</h2>
            </div>
            <div class="right-nav">
                <ul>
                    <li><a href="features/coolstuff/users.php">User Account</a></li>
                    <li><a href="crud/table/table.php">View scheduling</a></li>
                    <li><a href="features/coolstuff/search.php">Search</a></li>
                    <li><a href="features/coolstuff/salesreport.php">Sales report</a></li>
                    <form action="code.php" method="POST">
                        <input type="hidden" name="action" value="admin">
                        <li><button type="submit">Admin</button></li>
                        </form>
                    <form action="code.php" method="post">
                        <input type="hidden" name="action" value="logout">
                        <li><button type="submit">Logout</button></li>
                    </form>   
                </ul>
             </div>
        </div>
    </nav>
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
 

<?php   
        }else{
            
        
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
<body id="dashboard_body">
    <nav class="navbar">
        <div class="navdiv">
            <div class="left-nav">
                <img src="resources/style/photos/logo.jpg" class="float-img">
                    <h2 id="dash_text">DASHBOARD</h2>
            </div>
            <div class="right-nav">
                <ul>
                    <li><a href="features/coolstuff/users.php">User Account</a></li>    
                    <li><a href="features/coolstuff/search.php">Search</a></li>
                    <form action="code.php" method="post">
                        <input type="hidden" name="action" value="logout">
                        <li><button type="submit">Logout</button></li>
                    </form>   
                </ul>
             </div>
        </div>
    </nav>
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
            <?php } ?>
    </body>
</html>