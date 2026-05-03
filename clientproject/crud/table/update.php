<?php
session_start();
include("../../connect/conn/database.php");


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cha's Nails</title>
</head>
<body>
    

<!-- update form -->
<?php $update_id = $_POST['update_id'];?>
<div>
        <form action="crudcode.php" method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="update_id" value="<?php echo $update_id ?>">
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
    </body>
</html>