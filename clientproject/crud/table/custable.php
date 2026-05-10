<?php
session_start();
?>
<?php
include("../../connect/conn/database.php");
$id = $_SESSION['user']['id'] ?? '';

$stmt = $conn->prepare
("
    SELECT * 
    FROM clientinfo 
    WHERE Customer_id = ?
    ORDER BY appointment_date DESC
");

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../resources/style/cusAppointment.css">
    <link rel="stylesheet" href="../../resources/style/user_header.css">
    <link rel="stylesheet" href="../../resources/style/alerts.css">
    <title>Appointments | Polished By Cha</title>
    <link rel="icon" type="image/x-icon" href="../../resources/style/photos/header_icon.png">
</head>
<body>
    <?php include '../../includes/customerHeader_features.php'; ?>
    <!-- CRUD table -->
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
