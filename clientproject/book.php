<?php
// SESSION & DATABASE INITIALIZATION
session_start();
include("connect/conn/database.php");

// Retrieve logged-in user's session data
$username = $_SESSION['user']['username'] ?? ''; 
$time = $_POST['time'] ?? '';
$day = $_POST['date'] ?? '';
$id = $_SESSION['user']['id']??'';

// FORM HANDLER: BOOKING SUBMISSION
// Processes the appointment booking form,
// splits the service string into its parts,
// and inserts a new record into clientinfo
if(isset($_POST["submit"])){
    $customer_name = $_POST["customer_name"]?? '';
    $appointment_date = $_POST["appointment_date"]?? '';
    $appointment_time = $_POST['appointment_time']?? '';
    $Customer_id = $_POST['Customer_id']?? '';
    // Split service, price, and duration
    if(isset($_POST['service'])){
        $service_data = $_POST['service'];
        list($service, $price, $duration) = explode('|', $service_data);
    } else {
        die("Service is required");
    }

   
// Insert new appointment into the database
$sql = "INSERT INTO clientinfo (Customer_id,customer_name,appointment_date,appointment_time,service,price,duration) VALUES ('$Customer_id','$customer_name', 
'$appointment_date','$appointment_time', '$service', '$price', '$duration')";
$query = mysqli_query($conn, $sql);

// Redirect to main page on success
if($query){ 
  header("Location: main.php");
  exit();
}

// FORM HANDLER: BOOKING CANCELLATION
}
if (isset($_POST["cancel"])){
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="resources/style/user_header.css">
    <link rel="stylesheet" href="resources/style/user_dashboard.css">
    <link rel="stylesheet" href="resources/style/alerts.css">
    <title>Dashboard | Polished By Cha</title>
    <link rel="icon" type="image/x-icon" href="resources/style/photos/header_icon.png">
</head>
<body>
    <!-- BOOKING FORM OVERLAY
         Pre-fills customer name, ID, time, and date
         from session/POST; user selects a service
         and submits to book.php -->
    <div class="book_overlay">
        <div class="book_page">
            <form action="book.php" method="POST">
                <!-- Hidden Fields: Customer Info & Slot -->
                <input type="hidden" name="customer_name" value="<?php echo $username ?>" required><br>
                <input type="hidden" name="Customer_id" value="<?php echo $id ?>" required><br>
                <input type="hidden" name="appointment_time" value="<?php echo $time ?>">
                <input type="hidden" name="appointment_date" value="<?php echo $day ?>">
                <label for="Services" class="bookServices">Services:</label><br>

                <!-- Service Selection -->
                <select name="service" id="serve" class="bookChoice" required >
                    <option value="Plain Gel Polish|150|1hr" >Plain Gel Polish (Php150) </option>
                    <option value="Nail Art Gel Polish|250|1hr30mins">Nail Art Gel Polish (Php250) </option>
                    <option value="Plain Nail Extension|300|1hr30mins">Plain Nail Extension (Php300) </option>
                    <option value="Nail Art Extension|400|2hrs">Nail Art Extension (Php350-php400) </option>
                    <option value="Gel polish/Nail Extension Removal|100|35mins">Gel polish/Nail Extension Removal (Php100) </option>
                </select><br>
                <!-- Form Actions -->
                <button type="submit" name="submit" class="bookSubmit">Submit</button>
                <a href="main.php">
                <button type="button" name="cancel" class="bookSubmit">Cancel</button>
                </a>
            </form>
        </div>
    </div>

     <!-- HEADER INCLUDE -->
    <?php include 'includes/customer_header.php'; ?>

     <!-- WELCOME SECTION
          Displays a greeting with the logged-in
          user's username -->
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
    <!-- WEEKLY AVAILABILITY CALENDAR
         Displays the current week (Mon–Sun) against
         available time slots; marks unavailable days,
         booked slots, and available slots with a
         booking form button -->
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
</body>
</html>

