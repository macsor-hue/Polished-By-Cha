<?php
session_start();
?>
<?php
include("connect/conn/database.php");

$username = $_SESSION['user']['username'] ?? ''; 
  $time = $_POST['time'] ?? '';
    $day = $_POST['date'] ?? '';


if(isset($_POST["submit"])){

    $customer_name = $_POST["customer_name"]?? '';
    $appointment_date = $_POST["appointment_date"]?? '';
    $appointment_time = $_POST['appointment_time']?? '';
  
    // Split service, price, and duration
    if(isset($_POST['service'])){
        $service_data = $_POST['service'];
        list($service, $price, $duration) = explode('|', $service_data);
    } else {
        die("Service is required");
    }

   

$sql = "INSERT INTO clientinfo ( customer_name,appointment_date,appointment_time,service,price,duration) VALUES ('$customer_name', '$appointment_date','$appointment_time', '$service', '$price', '$duration')";
$query = mysqli_query($conn, $sql);

if($query){ 
  header("Location: index.php");
  exit();
}


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <form action="book.php" method="POST">
            
            <input type="hidden" name="customer_name" value="<?php echo $username ?>" required><br>
           <input type="hidden" name="appointment_time" value="<?php echo $time ?>">
           <input type="hidden" name="appointment_date" value="<?php echo $day ?>">
            <label for="Services">Services:</label><br>
            <select name="service" id="serve" required >
                <option value="Plain Gel Polish|150|1hr" >Plain Gel Polish  (Php150)</option>
                <option value="Nail Art Gel Polish|250|1hr30mins">Nail Art Gel Polish   (Php250)</option>
                <option value="Plain Nail Extension|300|1hr30mins">Plain Nail Extensionh  (Php300)</option>
                <option value="Nail Art Extension|400|2hrs">Nail Art Extension(Php350-php400)</option>
                <option value="Gel polish/Nail Extension Removal|100|35mins">Gel polish/Nail Extension Removal(Php100)</option>
            </select><br>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
    
    
</body>
</html>

