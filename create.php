<?php
session_start();
$message = '';
$messageType = 'error';

if(isset($_POST['create'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirmPassword']);

    // Server-side validation
    if(empty($email) || empty($password) || empty($confirm)){
        $message = "All fields are required!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = "Invalid email format! Example: user@example.com";
    } elseif($password !== $confirm){
        $message = "Passwords do not match!";
    } else {
        // Database connection
        $conn = new mysqli("localhost","root","","luxestate_db");
        if($conn->connect_error) die("Connection Failed: ".$conn->connect_error);

        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0){
            $message = "Email already registered!";
        } else {
            // Insert new user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users(email,password) VALUES(?,?)");
            $insert->bind_param("ss",$email,$hash);
            if($insert->execute()){
                $messageType = 'success';
                $message = "Account created successfully! You can now <a href='signin.php'>Sign In</a>.";
            } else {
                $message = "Error creating account!";
            }
        }
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account - LuxEstate</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#f5efe6;}
nav{display:flex;justify-content:space-between;align-items:center;padding:20px 60px;background:white;box-shadow:0 5px 15px rgba(0,0,0,0.05);}
.logo a{text-decoration:none;color:#c9a44c;font-size:24px;font-weight:600;}
.auth-buttons{display:flex;gap:15px;}
.auth-btn{padding:8px 18px;border-radius:8px;font-weight:500;cursor:pointer;transition:0.3s;}
.auth-btn:hover{opacity:0.85;}
.container{background:white;padding:40px 30px;border-radius:12px;width:400px;max-width:90%;box-shadow:0 10px 20px rgba(0,0,0,0.1);margin:50px auto;}
h2{text-align:center;color:#333;margin-bottom:30px;}
input{width:100%;padding:12px;margin-bottom:15px;border-radius:8px;border:1px solid #ccc;}
button{width:100%;padding:12px;border:none;border-radius:8px;background:#c9a44c;color:white;font-weight:600;cursor:pointer;transition:0.3s;}
button:hover{opacity:0.85;}
.message{font-size:14px;margin-bottom:15px;padding:10px;border-radius:6px;}
.message.error{color:#a94442;background:#f2dede;border:1px solid #ebccd1;}
.message.success{color:#3c763d;background:#dff0d8;border:1px solid #d6e9c6;}
.js-error{color:#a94442;background:#f2dede;border:1px solid #ebccd1;padding:10px;margin-bottom:15px;border-radius:6px;font-weight:500;}
</style>
</head>
<body>

<nav>
  <div class="logo"><a href="index.php">LuxEstate</a></div>
  <div class="auth-buttons">
    <a href="signin.php"><button class="btn auth-btn">Sign In</button></a>
    <a href="create.php"><button class="btn auth-btn" style="background:#fff;color:#c9a44c;border:1px solid #c9a44c;">Create Account</button></a>
  </div>
</nav>

<div class="container">
<h2>Create Account</h2>

<!-- PHP messages -->
<?php if($message != ''): ?>
    <div class="message <?= $messageType ?>"><?= $message ?></div>
<?php endif; ?>

<form method="post" action="" id="createForm">
<input type="email" name="email" placeholder="Email" maxlength="50">
<input type="password" name="password" placeholder="Password" maxlength="50">
<input type="password" name="confirmPassword" placeholder="Confirm Password" maxlength="50">
<button type="submit" name="create">Create Account</button>
</form>

<script>
const form = document.getElementById('createForm');
const emailInput = form.querySelector('input[name="email"]');
const passwordInput = form.querySelector('input[name="password"]');
const confirmInput = form.querySelector('input[name="confirmPassword"]');

const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

form.addEventListener('submit', function(e){
    const oldMsg = form.querySelector('.js-error');
    if(oldMsg) oldMsg.remove();

    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();
    const confirm = confirmInput.value.trim();

    // Empty email
    if(email === ''){
        e.preventDefault();
        const div = document.createElement('div');
        div.className = 'js-error';
        div.innerHTML = '<strong>304 Error:</strong> Email is required!';
        form.insertBefore(div, form.firstChild);
        emailInput.focus();
        return;
    }

    // Invalid email
    if(!emailRegex.test(email)){
        e.preventDefault();
        const div = document.createElement('div');
        div.className = 'js-error';
        div.innerHTML = '<strong>304 Error:</strong> Invalid email format! Example: user@example.com';
        form.insertBefore(div, form.firstChild);
        emailInput.focus();
        return;
    }

    // Empty password
    if(password === '' || confirm === ''){
        e.preventDefault();
        const div = document.createElement('div');
        div.className = 'js-error';
        div.innerHTML = '<strong>304 Error:</strong> Password and Confirm Password are required!';
        form.insertBefore(div, form.firstChild);
        return;
    }

    // Password mismatch
    if(password !== confirm){
        e.preventDefault();
        const div = document.createElement('div');
        div.className = 'js-error';
        div.innerHTML = '<strong>304 Error:</strong> Passwords do not match!';
        form.insertBefore(div, form.firstChild);
        return;
    }
});
</script>

</div>
</body>
</html>