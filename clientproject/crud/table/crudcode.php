<?php
session_start()
?>
<?php
include("../../connect/conn/database.php");

function flash(string $type, string $text): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'text' => $text
    ];
}
$action = $_POST['action']??'';

//DELETE DATA
if($action==='delete'){
    $delete_id = $_POST['delete_id'];
    $sql = "DELETE FROM clientinfo WHERE appointment_id = '$delete_id'";
    if(mysqli_query($conn, $sql)){
        header("Location: custable.php");
        exit();
    }
    
}
if($action==='adminDelete'){
    $delete_id = $_POST['delete_id'];
    $sql = "DELETE FROM clientinfo WHERE appointment_id = '$delete_id'";
    if(mysqli_query($conn, $sql)){
        header("Location: table.php");
        exit();
    }
    
}           


// UPDATE DATA
if($action==='update'){
    $id = $_POST["update_id"];
    $appointment_date = $_POST["appointment_date"];
    if(isset($_POST['service'])){
        $service_data = $_POST['service'];
        list($service, $price, $duration) = explode('|', $service_data);
    } else {
        die("Service is required");
    }
   $stmt = $conn->prepare("UPDATE clientinfo SET appointment_date=?, service=?, price=?, duration=? 
    WHERE appointment_id=?");
$stmt->bind_param("ssssi", $appointment_date, $service, $price, $duration, $id);

if($stmt->execute()){
    header("Location: custable.php");
    exit();
}
}
if($action==='adminUpdate'){
    $id = $_POST["update_id"];
    $appointment_date = $_POST["appointment_date"];
    if(isset($_POST['service'])){
        $service_data = $_POST['service'];
        list($service, $price, $duration) = explode('|', $service_data);
    } else {
        die("Service is required");
    }
   $stmt = $conn->prepare("UPDATE clientinfo SET appointment_date=?, service=?, price=?, duration=? 
    WHERE appointment_id=?");
$stmt->bind_param("ssssi", $appointment_date, $service, $price, $duration, $id);

if($stmt->execute()){
    header("Location: table.php");
    exit();
}
}

//payment
if($action === 'payment'){
    $money = $_POST["payment_stat"]?? '';
    $id = $_POST["pay_id"]?? '';

    
        $stmt = $conn->prepare("UPDATE clientinfo SET payment_status = ? WHERE appointment_id = ?");
        $stmt->bind_param("si", $money,$id);
        if($stmt->execute()){
            flash('ok', 'Permission update succesful!');
            $stmt->close();
            header("Location:table.php");
            exit();
        }

}


?>