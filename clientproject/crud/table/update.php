<?php
// SESSION & DATABASE INITIALIZATION
session_start();
include("../../connect/conn/database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- Stylesheets -->
    <link rel="stylesheet" href="../../resources/style/cusAppointment.css">
    <link rel="stylesheet" href="../../resources/style/user_header.css">
    <link rel="stylesheet" href="../../resources/style/alerts.css">
    <title>Cha's Nails</title>
    <link rel="icon" type="image/x-icon" href="../../resources/style/photos/header_icon.png"> 
</head>
<body>
<!--UPDATE FORM OVERLAY
    Pre-fills the appointment date from the
    selected row. 
    Submits to crudcode.php -->
<?php $update_id = $_POST['update_id'];?>
    <div class="update_overlay">
        <div class="update_page">
            <form action="crudcode.php" method="POST">
                 <!-- Hidden Fields: Action & Target Record -->
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="update_id" value="<?php echo $update_id ?>">
                 <!-- Date Field -->
                <label for="appointment_date" class="updateDate">Appointment Date:</label><br>
                <input type="date" name="appointment_date" value="<?php echo $row['appointment_date'] ?? ''; ?>"><br>
                <!-- Service Selection -->
                <label for="Services" class="bookServices">Services:</label><br>
                <select name="service" id="serve"  class="bookChoice">
                    <option value="Plain Gel Polish|150|1hr" >Plain Gel Polish  (Php150)</option>
                    <option value="Nail Art Gel Polish|250|1hr30mins">Nail Art Gel Polish   (Php250)</option>
                    <option value="Plain Nail Extension|300|1hr30mins">Plain Nail Extension  (Php300)</option>
                    <option value="Nail Art Extension|400|2hrs">Nail Art Extension(Php350-php400)</option>
                    <option value="Gel polish/Nail Extension Removal|100|35mins">Gel polish/Nail Extension Removal(Php100)</option> 
                </select><br>
                 <!-- Form Actions -->
                <button type="submit" name="submit" class="updateSubmit">Submit</button>
                <a href="custable.php">
                <button type="button" name="cancel" class="updateSubmit">Cancel</button>
                </a>
            </form>
        </div>
    </div>
<!-- HEADER INCLUDE -->
     <?php include '../../includes/customerHeader_features.php'; ?>
    <!-- CRUD table same as custable.php (mainly used for an overlay effect) -->
    <div class="sched_flex">
        <div class="sched_container">
            <div class="sched_info">
                <table>
                    <thead>
                        <tr>
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
                        $sql = "SELECT * FROM clientinfo WHERE Customer_id = '$id' AND payment_status = '$payment' ";
                        $result = mysqli_query($conn, $sql);
                        if(!$result){
                            die("Query failed: " . mysqli_error($conn));
                        }
                        while($row = mysqli_fetch_assoc($result)){
                            ?>
                            <tr>
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
        </div>
    </div>
    <?php include '../../includes/footer_features.php'; ?>
    </body>
</html>

