<?php
session_start()
?>
<?php
include("../../connect/conn/database.php");

if(isset($_GET["deleteid"])){
    $delete_id = $_GET["deleteid"];
    $sql = "DELETE FROM clientinfo WHERE temp_id = '$delete_id'";
    if(mysqli_query($conn, $sql)){
        header("Location: table.php");
        exit();
    }
}
?>