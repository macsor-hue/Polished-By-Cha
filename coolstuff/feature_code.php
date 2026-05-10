<?php
session_start();
include("../../connect/conn/database.php");



function redirect_users(): void
{
    header("Location:/clientproject/features/coolstuff/users.php");
    exit;
}
function flash(string $type, string $text): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'text' => $text
    ];
}

$action = $_POST['action'] ?? ''; 

//check for users permission
if($action === 'admin'){
   $username = $_SESSION['user']['username'] ?? '';
    
   if($username === ''){
    flash('err', 'User not logged in');
    redirect_login();
    exit();
    }


    $stmt=$conn->prepare("SELECT permission FROM accinfo WHERE username = ? LIMIT 1");
    if(!$stmt){
        flash('err' , 'Database error');
        redirect_login();
        exit();
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result=$stmt->get_result();
    $user = $result->fetch_assoc();
     
     if($user && strtolower(trim($user['permission'])) === 'yes'){
        flash('ok','Valid permission');
        header("Location:features/coolstuff/Admin.php");
        exit();
    }
    else{
        flash('err','Sorry, permission Invalid');
        redirect_main();
        exit();
    }
    
    $stmt->close();
   
}

//user account update
if($action === 'users'){
    $userid = (INT)($_SESSION['user']['id']);  
    $username = trim($_POST['username']?? '');
    $cpassword = ($_POST['cpassword'] ?? '');
    $npassword = ($_POST['npassword'] ?? '');
    
    if(strlen($username) < 3){
        flash('err' , 'Update failed: username must be atleast 3 characters');
        redirect_users();
        exit();
    }     

    $stmt = $conn->prepare("SELECT username, pass FROM accinfo WHERE id = ?");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
   
    //only changing password
    if ($npassword !== ''&& $cpassword !== '') { 
         if(strlen($npassword) < 6){
        flash('err' , 'Update failed: password must be atleast 6 characters');
        redirect_users();
        exit();
        } 
         if(password_verify($cpassword, $user['pass'])){

            $hash=password_hash($npassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE accinfo SET username = ?,pass = ? WHERE id = ?");

            if(!$stmt){
            flash('err' , 'Update failed: database error');
             redirect_users();
            exit();
            }

            $stmt->bind_param("ssi", $username,$hash,$userid);

            }
         else{
            flash('err','Current password does not match');
                redirect_users();
                exit();
             }}

        //changing everything  
        else{
             $stmt = $conn->prepare("UPDATE accinfo SET username = ? WHERE id = ?");
                if(!$stmt){
                flash('err' , 'Update failed: database error');
                redirect_users();
                exit();}
                $stmt->bind_param("si", $username,$userid);
                  }
                if($stmt->execute()) {
                    flash('ok', 'Update successful!');
                    $stmt->close(); 
                    redirect_users();
                    exit();
                     }
}

    //change permission
    if($action==='permit'){
        $id = $_POST['id'] ?? ''; 
        $permission = ($_POST['permission']?? '');

        if ($id === '' || $permission === '') {
        flash('err', 'Missing required data.');
        redirect_users();
        exit();
    }
        $stmt = $conn->prepare("UPDATE accinfo SET permission = ? WHERE id = ?");
        $stmt->bind_param("si", $permission,$id);
        if($stmt->execute()){
            flash('ok', 'Permission update succesful!');
            $stmt->close();
            header("Location:Admin.php");
            exit();
        }
    }

    

?>