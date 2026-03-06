<?php
session_start();
$message = '';

if(isset($_POST['signin'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(empty($email) || empty($password)){
        $message = "All fields are required!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = "Invalid email format!";
    } else {
        $conn = new mysqli("localhost","root","","rental_db");
        if($conn->connect_error) die("Connection Failed: ".$conn->connect_error);

        $stmt = $conn->prepare("SELECT id,password FROM users WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id,$hash);
        if($stmt->num_rows == 1){
            $stmt->fetch();
            if(password_verify($password,$hash)){
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $email;
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
.error{color:red;font-size:14px;margin-bottom:10px;}
.success{color:green;font-size:14px;margin-bottom:10px;}
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
<h2>Sign In</h2>
<?php if($message != '') echo "<div class='error'>$message</div>"; ?>
<form method="post" action="">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit" name="signin">Sign In</button>
</form>
</div>

</body>
</html>