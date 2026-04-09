<?php
session_start()
?>
<?php
include("../../connect/conn/database.php");
if(isset($_POST["submit"])){
$temp_name = $_POST["temp_name"];
$temp_date = $_POST["temp_date"];
$temp_var1 = $_POST["temp_var1"];
$temp_var2 = $_POST["temp_var2"];
$temp_var3 = $_POST["temp_var3"];

$sql = "INSERT INTO clientinfo ( temp_name, temp_date, temp_var1, temp_var2, temp_var3) VALUES ('$temp_name', '$temp_date', '$temp_var1', '$temp_var2', '$temp_var3')";
$query = mysqli_query($conn, $sql);
if($query){ 
  header("Location: table.php");
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
        <form action="add.php" method="POST">
            <input type="text" name="temp_name" placeholder="Name"><br>
            <input type="date" name="temp_date" placeholder="Date"><br>
            <input type="text" name="temp_var1" placeholder="Variable 1"><br>
            <input type="text" name="temp_var2" placeholder="Variable 2"><br>
            <input type="text" name="temp_var3" placeholder="Variable 3"><br>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
</body>
</html>