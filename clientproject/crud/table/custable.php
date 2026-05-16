<?php
// SESSION & DATABASE INITIALIZATION
session_start();
include("../../connect/conn/database.php");

// FETCH ALL APPOINTMENTS (Prepared Statement)
// Retrieves all appointments for the logged-in
// user, ordered by most recent date first
$id = $_SESSION['user']['id'] ?? '';
$stmt = $conn->prepare
(" SELECT * FROM clientinfo WHERE Customer_id = ? ORDER BY appointment_date DESC ");//DESC=descending order
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
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
    <title>Appointments | Polished By Cha</title>
    <link rel="icon" type="image/x-icon" href="../../resources/style/photos/header_icon.png">
</head>
<body>
    <!-- =============================================
         FLASH MESSAGE ALERT (Session-based)
         Displays success/error alerts then clears
         the session flash data immediately after
    ============================================= -->
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
     <!--HEADER INCLUDE-->
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
                            <th>Approval status</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query: Fetch unpaid appointments for current user to filter so only upcomming appointments are displayed
                        $id = $_SESSION['user']['id']??'';
                        $payment = "unpaid";
                        $sql = "SELECT * FROM clientinfo WHERE Customer_id = '$id' AND payment_status = '$payment' ";
                        $result = mysqli_query($conn, $sql);

                        //Error handling
                        if(!$result){
                            die("Query failed: " . mysqli_error($conn));
                        }
                        // Loop through and render each appointment row
                        while($row = mysqli_fetch_assoc($result)){
                            ?>
                            <tr>
                                 <!-- Appointment Details -->
                                <td><?php echo $row["appointment_date"]; ?></td>
                                <td><?php echo $row["appointment_time"]; ?></td>
                                <td><?php echo $row["service"]; ?></td>
                                <td><?php echo $row["price"]; ?></td>
                                <td><?php echo $row["duration"]; ?></td>
                                <td><?php echo $row["appointment_status"]; ?></td>

                                 <!-- Update Action -->
                            <td><form action="update.php" method="post"
                                onsubmit="return confirm('Are you sure you want to update this appointment?');">
                                <input type="hidden" name="update_id" value="<?php echo $row["appointment_id"]; ?>">
                                <input type="hidden" name="action" value="update">
                                <button type="submit">Update</button>
                                </form></td>
                                <!-- Delete Action -->
                                <td><form action="crudcode.php" method="post"
                                    onsubmit="return confirm('Are you sure you want to delete this appointment?');">
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
    <!--FOOTER INCLUDE-->
    <?php include '../../includes/footer_features.php'; ?>
</body>
</html>
