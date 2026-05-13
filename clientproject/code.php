<?php
// SESSION & DATABASE INITIALIZATION
session_start();
include("connect/conn/database.php");

// HELPER FUNCTIONS

// Set a session flash message for alert
function flash(string $type, string $text): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'text' => $text
    ];
}

// Redirect to the main dashboard page
function redirect_main(): void
{
    header("Location:/clientproject/main.php");
    exit;
}

// Redirect to the login page
function redirect_login(): void
{
    header("Location: login.php");
    exit;
}

// REQUEST METHOD GUARD
// Only allows POST requests; blocks direct
// URL access or non-form submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Invalid access method");
}
$action = $_POST['action'] ?? '';

// ACTION ROUTER
// Determines which action to perform based on
// the POST 'action' field value
if($action === ''){
    flash('err', 'No action provided.');
    redirect_main();
}

// ACTION: REGISTER
// Validates input, hashes the password, and
// inserts a new user into the accinfo table.
// Catches duplicate username errors (code 1062)
if($action === 'register'){
   $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
     $password = ($_POST['password'] ?? '');

    // Validate required fields
    if(empty($username) ||empty($password)){
        flash('err' , 'Registration failed: username and password are required');
        redirect_main();
        exit(); 
    }

    // Validate username length   
    elseif(strlen($username) < 3){
        flash('err' , 'Registration failed: username must be atleast 3 characters');
        redirect_main();
        exit();
    }   

    // Validate password length   
    elseif(strlen($password) < 6){
        flash('err' , 'Registration failed: password must be atleast 6 characters');
        redirect_main();
        exit();
    } 
        // Hash password before storing
        $hash=password_hash($password, PASSWORD_DEFAULT);
    
        // Prepare insert statement
        $stmt = $conn->prepare("INSERT INTO accinfo (username, pass) VALUES (?, ?)");
    if(!$stmt){
        flash('err' , 'Registration failed: database error');
        redirect_main();
        exit();
    }

    $stmt->bind_param("ss", $username, $hash);

    // Execute and handle duplicate username error
   try {
    $stmt->execute();
        flash('ok', 'Registration Successful! You can now log in');
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() === 1062) {
        flash('err', 'Registration failed: username already exists');
    } else {
        flash('err', 'Registration failed: database error');
    }
}
  
     $stmt->close();
    redirect_main();
    exit();
}


// ACTION: LOGIN
// Validates credentials against the database
// and stores user data in the session on success
if($action === 'login'){
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = ($_POST['password'] ?? '');

    // Validate required fields
    if(empty($username) ||empty($password)){
        flash('err' , 'Login failed: username and password are required');
        redirect_login();
        exit(); 
    }
    
    // Fetch user record by username
    $stmt = $conn->prepare("SELECT id, pass FROM accinfo WHERE username = ? LIMIT 1");
    if(!$stmt){
        flash('err' , 'Login failed: database error');
        redirect_login();
        exit();
    }


    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if username exists
    if($result->num_rows === 0){
        flash('err', 'Login failed: invalid username or password');
        redirect_login();
        exit();
    }

    $user = $result->fetch_assoc();

    // Verify password against stored hash
    if(password_verify($password, $user['pass'])){
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $username
        ];
        flash('ok', 'Login successful!');
        redirect_main();
        exit();
    } else {
        flash('err', 'Login failed: invalid username or password');
        redirect_login();
        exit();
    }
}


// ACTION: LOGOUT
// Clears the user session, regenerates the
// session ID to prevent session fixation,
// and redirects to the main page
if($action === 'logout'){
    unset($_SESSION['user']);
    session_regenerate_id(true);
    flash('ok', 'Logout successful!');
    redirect_main();
}


// ACTION: ADMIN PERMISSION CHECK
// Verifies if the logged-in user has admin
// access before redirecting to the admin panel
if($action === 'admin'){
   $username = $_SESSION['user']['username'] ?? '';
    
// Fetch permission level from database
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

?>