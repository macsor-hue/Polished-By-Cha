<?php
session_start();
include("../../connect/conn/database.php");


if (!empty($_SESSION['flash'])){
        $type = $_SESSION['flash']['type'];
        $text = $_SESSION['flash']['text'];

        echo "<div class='msg " . htmlspecialchars($type) . "'>" . htmlspecialchars($text). "</div>";

        unset($_SESSION['flash']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cha's Nails</title>
    <link rel="icon" type="image/x-icon" href="resources/style/photos/header_icon.png">
</head>
<body>
     <div><a href=" /clientproject/main.php"><button>Home</button></a></div>

     <div>
        <table>
            <thead>
                <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Registration date</th>
                <th>Current Admin Permission</th>
                 <th>Permissions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM accinfo";
                $result = mysqli_query($conn, $sql);
                if(!$result){
                    die("Query failed: " . mysqli_error($conn));
                }
                while($row = mysqli_fetch_assoc($result)){
                    ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["username"]; ?></td>
                        <td><?php echo $row["reg_date"]; ?></td>
                        <td><?php echo $row["permission"];?></td>
                       <td>
                           <form action="feature_code.php" method="POST">
                           <input type="hidden" name="id" value="<?php echo $row["id"];?>">
                           <input type="hidden" name="action" value="permit">
                        Admin permission: <br>
                        <select name="permission" id="permission">
                            <option value="no">?</option>
                            <option value="yes">yes</option>
                            <option value="no">no</option>
                        </select>
                        <button type="submit">Permit</button>
                        </form>
                       </td>
                    </tr>
                 <?php
                 }?>
      </tbody>
  </table>
    </div>
  
</body>
</html>