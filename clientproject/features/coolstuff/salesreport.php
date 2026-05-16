<?php
// SESSION & DATABASE INITIALIZATION
session_start();
include("../../connect/conn/database.php");

// QUERY: OVERALL TOTALS
// Fetches total sales amount and total number
// of appointments across all records
$stmt=$conn->prepare("SELECT SUM(price) as total_sales, COUNT(*) as total_appointments FROM clientinfo WHERE appointment_status = 'approved' AND payment_status = 'paid' ");
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../../resources/style/admin_header.css">
    <link rel="stylesheet" href="../../resources/style/sales.css">
    <link rel="stylesheet" href="../../resources/style/alerts.css">
    <title>Sales Report | Polished By Cha </title>
    <link rel="icon" type="image/x-icon" href="../../resources/style/photos/header_icon.png">
</head>
<body >
    <!-- FLASH MESSAGE ALERT (Session-based)
         Displays success/error alerts then clears
         the session flash data immediately after -->
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
    <?php include '../../includes/adminHeader_features.php'; ?>
    <div class="sales">
        <!-- Page Title -->
        <div class="page_title">
            <h1>Sales Report</h1>
            <p>View your sales performance</p>
        </div>

        <div class="sales_main">
            <div class="sales_container">
                <!--  CARD: TOTAL SALES & APPOINTMENTS (All Time) -->
                <div class="sales_info highlight-sales">
                    <div class="total_sales"><?php echo "Total Sales:"  ?> </div> 
                    <div class="totalSalesAmt"> <?php echo "₱" . ($data['total_sales']?? 0); ?></div>
                </div>
                 <!-- CARD: TODAY'S SALES
                     Fetches total sales for the current date -->
                <div class="sales_info">
                    <div class="total_appmt"><?php echo "Total Appointments: "  ?> </div> 
                    <div class="totalAppmtAmt"> <?php echo ($data['total_appointments']?? 0); ?></div>
                </div>

                <div class="sales_info">
                    <?php
                    //sales report query for today's sales
                    $today = date('Y-m-d');
                    $stmt=$conn->prepare("SELECT SUM(price) AS today_sales FROM clientinfo WHERE appointment_date = ? AND appointment_status = 'approved' AND payment_status = 'paid' ");
                    $stmt->bind_param("s",$today);
                    $stmt->execute();
                    $result=$stmt->get_result();
                    $row=$result->fetch_assoc();
                    ?>
                    <?php echo "Today's Sales: ₱". ($row['today_sales']?? 0)."<br><br>";?>  
                </div>
                <!-- CARD: SALES BY SPECIFIC DATE
                     Form to select a date and view sales report -->
                <div class="sales_info">
                    <form method="POST">
                        <label for="date_sales">SELECT REPORT DATE</label>
                        <input type="date" name="date_sales" required>
                        <button type="submit">Generate report</button>
                    </form>
                    <?php
                    if(isset($_POST['date_sales'])){
                        $date = $_POST['date_sales'];

                        // Query: Summary totals for selected date
$stmt = $conn->prepare("SELECT SUM(price) AS total, COUNT(*) as total_appointments FROM clientinfo WHERE appointment_date = ? AND appointment_status = 'approved'AND payment_status = 'paid' ");                        $stmt->bind_param("s",$date);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row=$result->fetch_assoc();?>

                    <!-- Summary Table -->
                    <table class="dates_report">
                    <thead>
                        <tr>
                            <td>Date</td>
                            <td>Amount of appointments</td>
                            <td>Sales</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $date;?></td>
                            <td><?php echo ($row['total_appointments']??0);?></td>
                            <td><?php echo ($row['total']??0);?></td>
                        </tr>
                    </tbody>
                    </table>

                    <!-- Customer Detail Table for Selected Date -->
                    <h3>Customers that day</h3>
                    <table>
                                <thead>
                                    <tr>  
                                        <th>Customer Name</th>
                                        <th>Appointment time</th>
                                        <th>Service</th>
                                        <th>Price</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  
                                    // Query: All appointments on selected date 
                                    $sql = "SELECT * FROM clientinfo WHERE appointment_date = '$date' AND appointment_status = 'approved' AND payment_status = 'paid'";
                                    $result = mysqli_query($conn, $sql);
                                    if(!$result){
                                        die("Query failed: " . mysqli_error($conn));
                                    }
                                    while($row = mysqli_fetch_assoc($result)){
                                        ?>
                                        <tr>
                                            <td><?php echo $row["customer_name"]; ?></td>
                                            <td><?php echo $row["appointment_time"]; ?></td>
                                            <td><?php echo $row["service"]; ?></td>
                                            <td><?php echo $row["price"]; ?></td>
                                            <td><?php echo $row["duration"]; ?></td>            
                                        </tr>
                                        <?php }?>
                                </tbody>
                        </table> 
                            
                    <?php } ?>
                </div>
                <!-- CARD: SALES BY DATE RANGE
                     Form to select a from/to range and view report -->
                <div class="sales_info">
                    <p>SELECT SALES REPORT DATE SPAN</p>
                    <form method="POST">
                        <label for="from">From:</label>
                        <input type="date" name="from" required>
                        <br>
                        <label for="to">To:</label>
                        <input type="date" name="to" required>
                        <br>
                        <button type="submit">Generate report</button>
                    </form>
                    <?php
                    if(isset($_POST['from']) && isset($_POST['to'])){
                        $from = $_POST['from'];
                        $to = $_POST['to'];

                        // Query: Summary totals for selected date range
                        $stmt = $conn->prepare("SELECT SUM(price) AS total, COUNT(*) as total_appointments FROM clientinfo WHERE appointment_date BETWEEN ? AND ? AND appointment_status = 'approved' AND payment_status = 'paid'");
                        $stmt->bind_param("ss",$from,$to);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row=$result->fetch_assoc();?>
                    
                    <!-- Summary Table -->
                    <table>
                    <thead>
                        <tr>
                            <td>Date</td>
                            <td>Amount of appointments</td>
                            <td>Sales</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $from;?> - <?php echo $to;?> </td>
                            <td><?php echo ($row['total_appointments']??0);?></td>
                            <td><?php echo ($row['total']??0);?></td>
                        </tr>
                    </tbody>
                    </table>

                    <!-- Customer Detail Table for Selected Date Range -->
                    <h3>Customers that day</h3>
                    <table>
                                <thead>
                                    <tr>  
                                        <th>Customer Name</th>
                                        <th>Appointment time</th>
                                        <th>Service</th>
                                        <th>Price</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Query: All appointments within selected date range
                                    $sql = "SELECT * FROM clientinfo WHERE appointment_date  BETWEEN '$from' AND '$to' AND appointment_status = 'approved' AND payment_status = 'paid'";
                                    $result = mysqli_query($conn, $sql);
                                    if(!$result){
                                        die("Query failed: " . mysqli_error($conn));
                                    }
                                    while($row = mysqli_fetch_assoc($result)){
                                        ?>
                                        <tr>
                                            <td><?php echo $row["customer_name"]; ?></td>
                                            <td><?php echo $row["appointment_time"]; ?></td>
                                            <td><?php echo $row["service"]; ?></td>
                                            <td><?php echo $row["price"]; ?></td>
                                            <td><?php echo $row["duration"]; ?></td>            
                                        </tr>
                                    <?php }?>
                                </tbody>
                        </table> 
                    <?php  }  ?>
                </div>
                <!-- CARD: SALES BY MONTH
                     Form to select a month and view report -->
                <div class="sales_info">
                    <?php
                    $year = date('Y');
                    ?>
                    <p>SELECT MONTHLY SALES REPORT </p>
                    <form method="POST">
                        <label for="from">Month:</label>
                        <select name="month" id="month">
                            <option value="">select</option>
                            <option value="<?php echo $year.'-01'; ?>">January</option>
                            <option value="<?php echo $year.'-02'; ?>">February</option>
                            <option value="<?php echo $year.'-03'; ?>">March</option>
                            <option value="<?php echo $year.'-04'; ?>">April</option>
                            <option value="<?php echo $year.'-05'; ?>">May</option>
                            <option value="<?php echo $year.'-06'; ?>">June</option>
                            <option value="<?php echo $year.'-07'; ?>">July</option>
                            <option value="<?php echo $year.'-08'; ?>">August</option>
                            <option value="<?php echo $year.'-09'; ?>">September</option>
                            <option value="<?php echo $year.'-10'; ?>">October</option>
                            <option value="<?php echo $year.'-11'; ?>">November</option>
                            <option value="<?php echo $year.'-12'; ?>">December</option>
                        </select>
                        <br><button type="submit">Generate report</button>
                    </form>
                    <?php
                    if(isset($_POST['month'])){
                        $month = $_POST['month'];

                        // Query: Summary totals for selected month
                        $stmt = $conn->prepare("SELECT SUM(price) AS total, COUNT(*) as total_appointments FROM clientinfo WHERE DATE_FORMAT(appointment_date,'%Y-%m')= ? AND appointment_status = 'approved' AND payment_status = 'paid'");
                        $stmt->bind_param("s",$month);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row=$result->fetch_assoc();

                    // Convert numeric month value to full month name
                    $year = date('Y');
                    switch($month){
                        case $year . "-01":
                            $monthName = "January";
                            break;
                        case $year . "-02":
                            $monthName = "February";
                            break;
                        case $year . "-03":
                            $monthName = "March";
                            break;
                        case $year . "-04":
                            $monthName = "April";
                            break;
                        case $year . "-05":
                            $monthName = "May";
                            break;
                        case $year . "-06":
                            $monthName = "June";
                            break;
                        case $year . "-07":
                            $monthName = "July";
                            break;
                        case $year . "-08":
                            $monthName = "August";
                            break;
                        case $year . "-09":
                            $monthName = "September";
                            break;
                        case $year . "-10":
                            $monthName = "October";
                            break;
                        case $year . "-11":
                            $monthName = "November";
                            break;
                        case $year . "-12":
                            $monthName = "December";
                            break;
                        default:
                            $monthName = "Invalid month";
                    }?>
                    <!-- Summary Table -->
                    <table>
                    <thead>
                        <tr>
                            <td>Month</td>
                            <td>Amount of appointments</td>
                            <td>Sales</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $monthName;?> </td>
                            <td><?php echo ($row['total_appointments']??0);?></td>
                            <td><?php echo ($row['total']??0);?></td>
                        </tr>
                    </tbody>
                    </table>

                    <!-- Customer Detail Table for Selected Month -->
                    <h3>Customers that month</h3>
                    <table>
                                <thead>
                                    <tr>  
                                        <th>Customer Name</th>
                                        <th>Appointment time</th>
                                        <th>Service</th>
                                        <th>Price</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        // Query: All appointments in selected month
                                        $stmt = $conn->prepare("SELECT * FROM clientinfo WHERE DATE_FORMAT(appointment_date,'%Y-%m')= ? AND appointment_status = 'approved' AND payment_status = 'paid'");
                                        $stmt->bind_param("s",$month);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if(!$result){
                                        die("Query failed: " . mysqli_error($conn));
                                    }
                                    while($row=$result->fetch_assoc()){
                                        ?>
                                        <tr>
                                            <td><?php echo $row["customer_name"]; ?></td>
                                            <td><?php echo $row["appointment_time"]; ?></td>
                                            <td><?php echo $row["service"]; ?></td>
                                            <td><?php echo $row["price"]; ?></td>
                                            <td><?php echo $row["duration"]; ?></td>            
                                        </tr>
                                    <?php }?>
                                </tbody>
                        </table> 
                    <?php  }  ?>
                </div> 
            </div>
        </div>
    </div>
    <!-- FOOTER INCLUDE -->
    <?php include '../../includes/admin_footer.php'; ?>
</body>
</html>