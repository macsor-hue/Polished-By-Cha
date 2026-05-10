<?php
session_start()
?>
<?php
include("../../connect/conn/database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../resources/style/schedule.css">
    <link rel="stylesheet" href="../../resources/style/admin_header.css">
    <link rel="stylesheet" href="../../resources/style/alerts.css">
    <title>Appointments | Polished By Cha</title>
    <link rel="icon" type="image/x-icon" href="../../resources/style/photos/header_icon.png">
</head>
<body id="schedule_body">
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
    <?php include '../../includes/adminHeader_features.php'; ?>
    <div class="page_title">
        <h1>Appoinment Schedules</h1>
        <p>Your complete overview of upcoming appointments</p>
    </div>
    <!-- CRUD table -->
    <div class="sched_flex">
        <div class="sched_container">
            <div class="sched_info">
                <table>
                    <thead>
                        <tr>
                            
                            <th>Customer Name</th>
                            <th>Appointment date</th>
                            <th>Appointment time</th>
                            <th>Service</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Update</th>
                            <th>Delete</th>
                            <th>Payment status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM clientinfo";
                        $result = mysqli_query($conn, $sql);
                        if(!$result){
                            die("Query failed: " . mysqli_error($conn));
                        }
                        while($row = mysqli_fetch_assoc($result)){
                            ?>
                            <tr>
                                <td><?php echo $row["customer_name"]; ?></td>
                                <td><?php echo $row["appointment_date"]; ?></td>
                                <td><?php echo $row["appointment_time"]; ?></td>
                                <td><?php echo $row["service"]; ?></td>
                                <td><?php echo $row["price"]; ?></td>
                                <td><?php echo $row["duration"]; ?></td>
                                <td><form action="update.php" method="post"
                                     onsubmit="return confirm('Are you sure you want to update this appointment?');"></onsubmit>
                                    <input type="hidden" name="update_id" value="<?php echo $row["appointment_id"]; ?>">
                                    <input type="hidden" name="action" value="adminUpdate">
                                    <button disabled class="appointmentBtn">Update</button>
                                </form>
                                </td>
                                <td><form action="crudcode.php" method="post"
                                    onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                    <input type="hidden" name="delete_id" value="<?php echo $row["appointment_id"]; ?>">
                                    <input type="hidden" name="action" value="adminDelete">
                                    <button type="submit" class="appointmentBtn">Delete</button>
                                    </form>
                                </td>
                                
                                <td><?php if($row["payment_status"]!="paid"){?>
                                    <form action="crudcode.php" method="post"
                                         onsubmit="return confirm('Are you sure you want to mark this as paid?');"></onsubmit>
                                        <input type="hidden" name="pay_id" value="<?php echo $row["appointment_id"];?>">
                                        <input type="hidden" name="payment_stat" value="paid">
                                        <input type="hidden" name="action" value="payment">
                                        <button type="submit" class="appointmentBtn"> Mark as Paid </button>
                                        </form>
                            <?php } 
                                    else{
                                       echo "PAID";
                                    }
                                    ?></td>
                            </tr>
                            <?php
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include '../../includes/admin_footer.php'; ?>
</body>
</html>
