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
    <title>Cha's Nails</title>
    <link rel="icon" type="image/x-icon" href="../../resources/style/photos/header_icon.png">
</head>
<body id="schedule_body">
    <nav class="navbar">
        <div class="navdiv">
            <div class="schedule-nav">
                <img src="../../resources/style/photos/logo.jpg" alt="Image of Logo" class="float-img">
                <h2 id="sched_text">Scheduling View</h2>
            </div>
            <div class="nav_home">
                <ul> 
                    <li><a href=" /clientproject/main.php">Home</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- CRUD table -->
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
                </tr>
            </thead>
            <tbody>
                <?php
                $id = $_SESSION['user']['id']??'';
                $payment = "unpaid";
                $sql = "SELECT * FROM clientinfo WHERE Customer_id = '$id'AND payment_status = '$payment' ";
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
                       <td><form action="update.php" method="post">
                        <input type="hidden" name="update_id" value="<?php echo $row["appointment_id"]; ?>">
                        <input type="hidden" name="action" value="update">
                        <button type="submit">Update</button>
                        </form></td>
                        <td><form action="crudcode.php" method="post">
                        <input type="hidden" name="delete_id" value="<?php echo $row["appointment_id"]; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit">Delete</button>
                        </form></td>
                    </tr>
                    <?php
                }?>
            </tbody>
        </table>
    </div>
</body>
</html>
