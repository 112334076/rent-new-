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
        $conn = new mysqli("localhost","root","","luxestate_db");
        if($conn->connect_error) die("Connection Failed: ".$conn->connect_error);

        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0){
            $message = "Email already registered!";
        } else {
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
input{width:100%;padding:12px;margin-bottom:10px;border-radius:8px;border:1px solid #ccc;}
button{width:100%;padding:12px;border:none;border-radius:8px;background:#c9a44c;color:white;font-weight:600;cursor:pointer;}
.message{font-size:14px;margin-bottom:15px;padding:10px;border-radius:6px;}
.message.error{color:#a94442;background:#f2dede;border:1px solid #ebccd1;}
.message.success{color:#3c763d;background:#dff0d8;border:1px solid #d6e9c6;}
.error-text{color:red;font-size:13px;margin-bottom:10px;}
</style>
</head>
<body>

<nav>
  <div class="logo"><a href="index.php">LuxEstate</a></div>
  <div class="auth-buttons">
    <a href="signin.php"><button class="auth-btn">Sign In</button></a>
    <a href="create.php"><button class="auth-btn" style="background:#fff;color:#c9a44c;border:1px solid #c9a44c;">Create Account</button></a>
  </div>
</nav>

<div class="container">
<h2>Create Account</h2>

<?php if($message != ''): ?>
<div class="message <?= $messageType ?>"><?= $message ?></div>
<?php endif; ?>

<form method="post" id="createForm">

<input type="email" id="email" name="email" placeholder="Email" maxlength="50">
<div id="emailErr" class="error-text"></div>

<input type="password" id="password" name="password" placeholder="Password" maxlength="50">
<div id="passErr" class="error-text"></div>

<input type="password" id="confirm" name="confirmPassword" placeholder="Confirm Password" maxlength="50">
<div id="confirmErr" class="error-text"></div>

<button type="submit" name="create">Create Account</button>
</form>
</div>

<script>
const form = document.getElementById('createForm');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const confirmInput = document.getElementById('confirm');

const emailErr = document.getElementById('emailErr');
const passErr = document.getElementById('passErr');
const confirmErr = document.getElementById('confirmErr');

const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

// typing-time validation
emailInput.oninput = validate;
passwordInput.oninput = validate;
confirmInput.oninput = validate;

function validate(){
    let valid = true;

    // email
    if(emailInput.value.trim() === ''){
        emailErr.textContent = "Email is required";
        valid = false;
    } else if(!emailRegex.test(emailInput.value)){
        emailErr.textContent = "Invalid email format";
        valid = false;
    } else {
        emailErr.textContent = "";
    }

    // password
    if(passwordInput.value.trim().length < 4){
        passErr.textContent = "Minimum 4 characters required";
        valid = false;
    } else {
        passErr.textContent = "";
    }

    // confirm
    if(confirmInput.value !== passwordInput.value){
        confirmErr.textContent = "Passwords do not match";
        valid = false;
    } else {
        confirmErr.textContent = "";
    }

    return valid;
}

// submit-time validation
form.addEventListener('submit', function(e){
    if(!validate()){
        e.preventDefault();
    }
});
</script>

</body>
</html>