<?php
// SESSION & DATABASE INITIALIZATION
session_start();
include("../../connect/conn/database.php");

// HELPER FUNCTIONS
// Redirect to the users page
function redirect_users(): void
{
    header("Location:/clientproject/features/coolstuff/users.php");
    exit;
}

// Set a session flash message for alerts
function flash(string $type, string $text): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'text' => $text
    ];
}

// ACTION ROUTER
// Determines which action to perform based on
// the POST 'action' field value
$action = $_POST['action'] ?? ''; 

// ACTION: ADMIN PERMISSION CHECK
// Verifies if the logged-in user has admin
// access before redirecting to the admin panel
if($action === 'admin'){
   $username = $_SESSION['user']['username'] ?? '';
 
    // Ensure user is logged in
   if($username === ''){
    flash('err', 'User not logged in');
    redirect_login();
    exit();
    }

    // Query permission level from database
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

    // Grant or deny access based on permission value
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

// ACTION: USER ACCOUNT UPDATE
// Allows the user to update their username,
// password, or both
if($action === 'users'){
    $userid = (INT)($_SESSION['user']['id']);  
    $username = trim($_POST['username']?? '');
    $cpassword = ($_POST['cpassword'] ?? '');
    $npassword = ($_POST['npassword'] ?? '');
    
    // Validate username length
    if(strlen($username) < 3){
        flash('err' , 'Update failed: username must be atleast 3 characters');
        redirect_users();
        exit();
    }     

    // Fetch current username and hashed password from DB
    $stmt = $conn->prepare("SELECT username, pass FROM accinfo WHERE id = ?");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
   
    // Branch: Update both username AND password
    if ($npassword !== ''&& $cpassword !== '') {

        // Validate new password length
         if(strlen($npassword) < 6){
        flash('err' , 'Update failed: password must be atleast 6 characters');
        redirect_users();
        exit();
        } 
        
        // Verify current password before allowing change
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
            // Current password does not match
            flash('err','Current password does not match');
                redirect_users();
                exit();
             }}

            // Branch: Update username only
        else{
             $stmt = $conn->prepare("UPDATE accinfo SET username = ? WHERE id = ?");
                if(!$stmt){
                flash('err' , 'Update failed: database error');
                redirect_users();
                exit();}
                $stmt->bind_param("si", $username,$userid);
                  }

                // Execute update and redirect
                if($stmt->execute()) {
                    flash('ok', 'Update successful!');
                    $stmt->close(); 
                    redirect_users();
                    exit();
                     }
}

    // ACTION: CHANGE USER PERMISSION (Admin only)
    // Updates a user's admin permission level
    if($action==='permit'){
        $id = $_POST['id'] ?? ''; 
        $permission = ($_POST['permission']?? '');

        // Validate required fields
        if ($id === '' || $permission === '') {
        flash('err', 'Missing required data.');
        redirect_users();
        exit();
    }
        // Update permission in database
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