<?php
session_start();

include("connect/conn/database.php");

function flash(string $type, string $text): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'text' => $text
    ];
}
function redirect_index(): void
{
    header("Location:/clientproject/index.php");
    exit;
}

function redirect_login(): void
{
    header("Location: login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Invalid access method");
}
$action = $_POST['action'] ?? '';

if($action === ''){
    flash('err', 'No action provided.');
    redirect_index();
}
//REGISTER

if($action === 'register'){
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $username = trim($_POST['username']?? '');
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
     $password = ($_POST['password'] ?? '');

    
    if(empty($username) ||empty($password)){
        flash('err' , 'Registration failed: username and password are required');
        redirect_index();
        exit(); 
    }
    elseif(strlen($username) < 3){
        flash('err' , 'Registration failed: username must be atleast 3 characters');
        redirect_index();
        exit();
    }   
    elseif(strlen($password) < 6){
        flash('err' , 'Registration failed: password must be atleast 6 characters');
        redirect_index();
        exit();
    } 
        $hash=password_hash($password, PASSWORD_DEFAULT);
    
        $stmt = $conn->prepare("INSERT INTO accinfo (username, pass) VALUES (?, ?)");
    if(!$stmt){
        flash('err' , 'Registration failed: database error');
        redirect_index();
        exit();
    }

    $stmt->bind_param("ss", $username, $hash);

    if($stmt->execute()) {
        flash('ok', 'Registration Successful! You can now log in');

    }else {
        flash('ok', 'Registration Failed! username may already exist');
    }
  
     $stmt->close();
    redirect_index();
    exit();
}


//login
if($action === 'login'){
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $username = trim($_POST['username']?? '');
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = ($_POST['password'] ?? '');

    
    if(empty($username) ||empty($password)){
        flash('err' , 'Login failed: username and password are required');
        redirect_login();
        exit(); 
    }
    
    $stmt = $conn->prepare("SELECT id, pass FROM accinfo WHERE username = ? LIMIT 1");
    if(!$stmt){
        flash('err' , 'Login failed: database error');
        redirect_login();
        exit();
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0){
        flash('err', 'Login failed: invalid username or password');
        redirect_login();
        exit();
    }

    $user = $result->fetch_assoc();

    if(password_verify($password, $user['pass'])){
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $username
        ];
        flash('ok', 'Login successful!');
        redirect_index();
        exit();
    } else {
        flash('err', 'Login failed: invalid username or password');
        redirect_login();
        exit();
    }
}


//logout
if($action === 'logout'){
    unset($_SESSION['user']);
    session_regenerate_id(true);
    flash('ok', 'Logout successful!');
    redirect_index();
}


//users permission
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
        redirect_index();
        exit();
    }
    
    $stmt->close();
   
    
}



?>

