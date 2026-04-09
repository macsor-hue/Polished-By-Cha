<?php
session_start()
?>
<?php  
include("../../connect/conn/database.php");
if(isset($_GET["updateid"])){
            $id = $_GET["updateid"];
        
                $sql = "SELECT * FROM clientinfo where temp_id = '$id'";
                $result = mysqli_query($conn, $sql);
                if(!$result){
                    die("Query failed: " . mysqli_error($conn));
                }
               else{
                $row = mysqli_fetch_assoc($result);
               }
                
                    
} 
?>

<!--update logic-->
<?php
if(isset($_POST["updateid"])){

    if(isset($_GET["id_new"])){
        $id = $_GET["id_new"];
    }
    $temp_name = $_POST["temp_name"];
    $temp_date = $_POST["temp_date"];
    $temp_var1 = $_POST["temp_var1"];
    $temp_var2 = $_POST["temp_var2"];
    $temp_var3 = $_POST["temp_var3"];
    
    $sql = "UPDATE clientinfo SET temp_name='$temp_name', temp_date='$temp_date', temp_var1='$temp_var1', temp_var2='$temp_var2', temp_var3='$temp_var3' WHERE temp_id='$id'";
     $result = mysqli_query($conn, $sql);
    if(mysqli_query($conn, $sql)){
        header("Location: table.php");
        exit();
    }
}
 
?>
<!-- update form -->
<div>
        <form action="update.php?id_new=<?php echo $id; ?>" method="POST">
            <input type="text" name="temp_name" placeholder="Name" value="<?php echo $row['temp_name']; ?>"><br>
            <input type="date" name="temp_date" placeholder="Date" value="<?php echo $row['temp_date']; ?>"><br>
            <input type="text" name="temp_var1" placeholder="Variable 1" value="<?php echo $row['temp_var1']; ?>"><br>
            <input type="text" name="temp_var2" placeholder="Variable 2" value="<?php echo $row['temp_var2']; ?>"><br>
            <input type="text" name="temp_var3" placeholder="Variable 3" value="<?php echo $row['temp_var3']; ?>"><br>
            <button type="submit" name="updateid" value="UPDATE">Submit</button>
        </form>
    </div>