<?php
session_start();
include("../../connect/conn/database.php");

// FETCH DATA
$row =[];
if(isset($_GET["Customer_id"])){
    $id = $_GET["Customer_id"];

    $sql = "SELECT * FROM clientinfo WHERE Customer_id = '$id'";
    $result = mysqli_query($conn, $sql);

    if($result){
        $row = mysqli_fetch_assoc($result);
    } else {
        die("Query failed: " . mysqli_error($conn));
    }
}

// UPDATE DATA
if(isset($_POST["submit"])){

    $id = $_GET["id"];

    $customer_name = $_POST["customer_name"];
    $appointment_date = $_POST["appointment_date"];

   
    if(isset($_POST['service'])){
        $service_data = $_POST['service'];
        list($service, $price, $duration) = explode('|', $service_data);
    } else {
        die("Service is required");
    }


    $sql = "UPDATE clientinfo  SET customer_name='$customer_name', appointment_date='$appointment_date', service='$service', price='$price',  duration='$duration' WHERE Customer_id='$id'";

    if(mysqli_query($conn, $sql)){
        header("Location: table.php");
        exit();
    }
}
?>
<!-- update form -->
<div>
        <form action="update.php?id=<?php echo $id; ?>" method="POST">
            
            <label for="appointment_date">Appointment Date:</label><br>
            <input type="date" name="appointment_date" value="<?php echo $row['appointment_date'] ?? ''; ?>"><br>

            <label for="Services">Services:</label><br>
            <select name="service" id="serve" >
                 <option value="Plain Gel Polish|150|1hr" >Plain Gel Polish  (Php150)</option>
                <option value="Nail Art Gel Polish|250|1hr30mins">Nail Art Gel Polish   (Php250)</option>
                <option value="Plain Nail Extension|300|1hr30mins">Plain Nail Extensionh  (Php300)</option>
                <option value="Nail Art Extension|400|2hrs">Nail Art Extension(Php350-php400)</option>
                <option value="Gel polish/Nail Extension Removal|100|35mins">Gel polish/Nail Extension Removal(Php100)</option> 
            </select><br>

            <button type="submit" name="submit">Submit</button>


        </form>
    </div>