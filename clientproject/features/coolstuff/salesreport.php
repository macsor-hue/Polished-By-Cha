<?php
session_start();
include("../../connect/conn/database.php");


//sales report query for all total sales
$stmt=$conn->prepare("SELECT SUM(price) as total_sales, COUNT(*) as total_appointments FROM clientinfo");
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cha's Nails</title>
</head>
<body >
    <div class="nav_home">
        <ul>
             <li><a href="/clientproject/main.php">Home</a></li>
        </ul>
    </div>
    <div><?php
            echo "Total Sales: ₱". ($data['total_sales']?? 0);
echo "<br>Total Appointments: ".($data['total_appointments']?? 0)."<br><br>";
 ?> </div>

<hr>


<?php
//sales report query for today's sales
$today = date('Y-m-d');
$stmt=$conn->prepare("SELECT SUM(price) AS today_sales FROM clientinfo WHERE appointment_date = ?");
$stmt->bind_param("s",$today);
$stmt->execute();
$result=$stmt->get_result();
$row=$result->fetch_assoc();
?>
<div><?php echo "Today's Sales: ₱". ($row['today_sales']?? 0)."<br><br>";?> </div>
<hr>


<?php
//specific date sales report query ?>
<form method="POST">
    <label for="date_sales">SELECT REPORT DATE</label><br>
    <input type="date" name="date_sales" required>
    <button type="submit">Generate report</button>
</form>
<?php
if(isset($_POST['date_sales'])){
    $date = $_POST['date_sales'];
    
    $stmt = $conn->prepare("SELECT SUM(price) AS total, COUNT(*) as total_appointments FROM clientinfo WHERE appointment_date = ?");
    $stmt->bind_param("s",$date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row=$result->fetch_assoc();

//table to show needed information for that date ?>
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
        <td><?php echo $date;?></td>
        <td><?php echo ($row['total_appointments']??0);?></td>
        <td><?php echo ($row['total']??0);?></td>
    </tr>
   </tbody>
</table>

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
                $sql = "SELECT * FROM clientinfo WHERE appointment_date = '$date'";
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
           
<?php } 


//sales report query for specific date span ?>
 <hr>       
<p>SELECT SALES REPORT DATE SPAN</p>
<form method="POST">
    <label for="from">From:</label>
    <input type="date" name="from" required>
    <label for="to">To:</label>
    <input type="date" name="to" required>
    <button type="submit">Generate report</button>
</form>
<?php
if(isset($_POST['from']) && isset($_POST['to'])){
    $from = $_POST['from'];
    $to = $_POST['to'];

    $stmt = $conn->prepare("SELECT SUM(price) AS total, COUNT(*) as total_appointments FROM clientinfo WHERE appointment_date BETWEEN ? AND ?");
    $stmt->bind_param("ss",$from,$to);
    $stmt->execute();
    $result = $stmt->get_result();
    $row=$result->fetch_assoc();

//table to show needed information for that date ?>
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
                $sql = "SELECT * FROM clientinfo WHERE appointment_date  BETWEEN '$from' AND '$to' ";
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
<?php  } 


//sales report query for specific Month ?>
<hr>
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
    <button type="submit">Generate report</button>
</form>
<?php
if(isset($_POST['month'])){
    $month = $_POST['month'];

    $stmt = $conn->prepare("SELECT SUM(price) AS total, COUNT(*) as total_appointments FROM clientinfo WHERE DATE_FORMAT(appointment_date,'%Y-%m')= ?");
    $stmt->bind_param("s",$month);
    $stmt->execute();
    $result = $stmt->get_result();
    $row=$result->fetch_assoc();

// change month from numbers to words
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
}
//table to show needed information for that date ?>
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
                <?php $stmt = $conn->prepare("SELECT * FROM clientinfo WHERE DATE_FORMAT(appointment_date,'%Y-%m')= ?");
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
<?php  } 


//sales report query for specific Month ?>
</body>
</html>