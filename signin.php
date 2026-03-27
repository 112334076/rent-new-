<?php
session_start();
$message = '';

if(isset($_POST['signin'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Server-side validation
    if(empty($email) || empty($password)){
        $message = "All fields are required!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = "Invalid email format!";
    } else {
        $conn = new mysqli("localhost","root","","luxestate_db");
        if($conn->connect_error) die("Connection Failed: ".$conn->connect_error);

        // Fetch id, name, and password
        $stmt = $conn->prepare("SELECT id,name,password FROM users WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows == 1){
            $stmt->bind_result($id,$name,$hash);
            $stmt->fetch();

            if(password_verify($password,$hash)){
                // Store necessary session data
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $email;
                $_SESSION['user_name'] = $name; // ✅ Name for navbar

                header("Location: index.php");
                exit;
            } else {
                $message = "Incorrect password!";
            }
        } else {
            $message = "Email not registered!";
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
<title>Sign In - LuxEstate</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#f5efe6;}

nav{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:20px 60px;
  background:white;
  box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.logo a{
  text-decoration:none;
  color:#c9a44c;
  font-size:24px;
  font-weight:600;
}

.auth-buttons{
  display:flex;
  gap:15px;
}

.auth-btn{
  padding:8px 18px;
  border-radius:8px;
  font-weight:500;
  cursor:pointer;
  transition:0.3s;
  border:none;
}

.auth-btn:hover{
  opacity:0.85;
}

.container{
  background:white;
  padding:40px 30px;
  border-radius:12px;
  width:400px;
  max-width:90%;
  box-shadow:0 10px 20px rgba(0,0,0,0.1);
  margin:50px auto;
}

h2{text-align:center;color:#333;margin-bottom:30px;}

input{
  width:100%;
  padding:12px;
  margin-bottom:5px;
  border-radius:8px;
  border:1px solid #ccc;
  transition:0.3s;
}

input.error-border{
  border:1px solid red;
}

.error{
  color:red;
  font-size:13px;
  margin-bottom:10px;
}

button{
  width:100%;
  padding:12px;
  border:none;
  border-radius:8px;
  background:#c9a44c;
  color:white;
  font-weight:600;
  cursor:pointer;
  transition:0.3s;
}

button:hover{
  opacity:0.85;
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav>
  <div class="logo"><a href="index.php">LuxEstate</a></div>
  <div class="auth-buttons">
    <a href="signin.php"><button class="auth-btn" style="background:#c9a44c;color:white;">Sign In</button></a>
    <a href="create.php"><button class="auth-btn" style="background:#fff;color:#c9a44c;border:1px solid #c9a44c;">Create Account</button></a>
  </div>
</nav>

<!-- SIGNIN FORM -->
<div class="container">
<h2>Sign In</h2>

<?php if($message != '') echo "<div class='error'>$message</div>"; ?>

<form method="post" action="" id="signinForm">

<input type="email" id="email" name="email" placeholder="Email" maxlength="40" oninput="validateEmail()" required>
<div id="emailError" class="error"></div>

<input type="password" id="password" name="password" placeholder="Password" maxlength="20" oninput="validatePassword()" required>
<div id="passError" class="error"></div>

<button type="submit" name="signin">Sign In</button>
</form>
</div>

<script>
// Email validation
function validateEmail(){
    let emailInput = document.getElementById("email");
    let email = emailInput.value.trim();
    let emailError = document.getElementById("emailError");

    let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

    if(email === ""){
        emailError.innerHTML = "Email is required";
        emailInput.classList.add("error-border");
    } else if(!email.match(emailPattern)){
        emailError.innerHTML = "Invalid email format";
        emailInput.classList.add("error-border");
    } else{
        emailError.innerHTML = "";
        emailInput.classList.remove("error-border");
    }
}

// Password validation
function validatePassword(){
    let passInput = document.getElementById("password");
    let password = passInput.value.trim();
    let passError = document.getElementById("passError");

    if(password === ""){
        passError.innerHTML = "Password is required";
        passInput.classList.add("error-border");
    } else if(password.length < 4){
        passError.innerHTML = "Minimum 4 characters required";
        passInput.classList.add("error-border");
    } else{
        passError.innerHTML = "";
        passInput.classList.remove("error-border");
    }
}

// Submit-time validation
document.getElementById('signinForm').addEventListener('submit', function(e){
    validateEmail();
    validatePassword();
    let emailErr = document.getElementById("emailError").innerHTML;
    let passErr = document.getElementById("passError").innerHTML;
    if(emailErr || passErr){
        e.preventDefault();
    }
});
</script>

</body>
</html>