<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['email'])){
    header("Location: signin.php");
    exit;
}

// Property ID from URL
$property_id = $_GET['id'] ?? 1;

// Hardcoded properties (replace with DB later)
$properties = [
    1 => [
        'name'=>'Cliffside Villa with Private Beach',
        'location'=>'Goa',
        'owner'=>'Rajesh Kumar',
        'mobile'=>'+91 9876543210',
        'beds'=>4,'baths'=>5,'area'=>'4800 sqft','price'=>'₹4,50,00,000',
        'images'=>['1.webp','1_1.webp','1_2.webp']
    ],
    2 => [
        'name'=>'Sea Facing Luxury Villa',
        'location'=>'Chennai ECR',
        'owner'=>'Suresh Mehta',
        'mobile'=>'+91 9876543211',
        'beds'=>5,'baths'=>6,'area'=>'5200 sqft','price'=>'₹3,20,00,000',
        'images'=>['2.webp','2_1.webp','2_2.webp']
    ],
    3 => [
        'name'=>'3 BHK Furnished Rental Home',
        'location'=>'Bengaluru',
        'owner'=>'Ravi Sharma',
        'mobile'=>'+91 9876543212',
        'beds'=>3,'baths'=>3,'area'=>'1600 sqft','price'=>'₹45,000 / month',
        'images'=>['3.webp','3_1.webp','3_2.webp']
    ],
];

$property = $properties[$property_id] ?? $properties[1];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($property['name']); ?> - LuxEstate</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
body{font-family:Poppins;margin:0;padding:0;background:#f5efe6;}
/* NAVBAR */
nav{display:flex;justify-content:space-between;align-items:center;padding:20px 60px;background:white;box-shadow:0 5px 15px rgba(0,0,0,0.05);}
.logo a{text-decoration:none;color:#c9a44c;font-size:24px;font-weight:600;}
.menu a{margin-left:25px;text-decoration:none;color:#555;font-weight:500;cursor:pointer;}
.menu a:hover{color:#c9a44c;}
.auth-buttons{display:flex;gap:15px;align-items:center;}
.auth-btn{padding:8px 18px;border-radius:8px;font-weight:500;cursor:pointer;transition:0.3s;}
.auth-btn:hover{opacity:0.85;}

/* PROPERTY DETAILS */
.container{max-width:1000px;margin:auto;padding:20px;}
.back-btn{margin-bottom:20px;}
.back-btn button{padding:10px 20px;background:#c9a44c;color:white;border:none;border-radius:8px;cursor:pointer;}
.property-title{font-size:32px;margin-bottom:10px;}
.property-location{color:#777;margin-bottom:20px;font-size:18px;}
.slider{position:relative;overflow:hidden;border-radius:12px;}
.slider img{width:100%;height:450px;object-fit:cover;transition:0.5s;}
.slider-controls{position:absolute;top:50%;width:100%;display:flex;justify-content:space-between;transform:translateY(-50%);}
.slider-controls button{background:rgba(0,0,0,0.5);color:white;border:none;padding:10px;border-radius:50%;cursor:pointer;font-size:18px;}
.details{display:flex;flex-wrap:wrap;gap:20px;margin-top:20px;}
.details div{background:white;padding:20px;border-radius:12px;flex:1;min-width:200px;box-shadow:0 5px 15px rgba(0,0,0,0.05);}
.details div h4{margin-bottom:10px;color:#c9a44c;}
.contact{margin-top:30px;display:flex;gap:20px;flex-wrap:wrap;}
.contact a{flex:1;text-align:center;text-decoration:none;padding:12px;border-radius:8px;color:white;font-weight:600;}
.call-btn{background:#28a745;}
.whatsapp-btn{background:#25D366;}
@media(max-width:768px){
    .slider img{height:300px;}
    .details{flex-direction:column;}
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav>
  <div class="logo"><a href="index.php">LuxEstate</a></div>

  <div class="menu">
    <a onclick="window.location.href='index.php?filter=all'">All</a>
    <a onclick="window.location.href='index.php?filter=buy'">Buy</a>
    <a onclick="window.location.href='index.php?filter=rent'">Rent</a>
    <a onclick="window.location.href='index.php?filter=villa'">Villas</a>
  </div>

  <div class="auth-buttons">
    <?php if(isset($_SESSION['email'])): ?>
      <span>
        Welcome,
        <b><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?></b>
      </span>

      <a class="btn auth-btn" 
         href="index.php?logout=true"
         style="background:#fff;color:#c9a44c;border:1px solid #c9a44c;">
         Logout
      </a>
    <?php else: ?>
      <a class="btn auth-btn" href="signin.php">Sign In</a>
      <a class="btn auth-btn" 
         href="create.php"
         style="background:#fff;color:#c9a44c;border:1px solid #c9a44c;">
         Create Account
      </a>
    <?php endif; ?>
  </div>
</nav>

<div class="container">
    <div class="back-btn"><a href="index.php"><button>← Back to Home</button></a></div>

    <div class="property-title"><?php echo htmlspecialchars($property['name']); ?></div>
    <div class="property-location">📍 <?php echo htmlspecialchars($property['location']); ?></div>

    <!-- Image Slider -->
    <div class="slider">
        <?php foreach($property['images'] as $index => $img): ?>
            <img src="<?php echo $img; ?>" style="<?php echo $index===0 ? 'display:block;' : 'display:none;'; ?>">
        <?php endforeach; ?>
        <div class="slider-controls">
            <button onclick="prevSlide()">‹</button>
            <button onclick="nextSlide()">›</button>
        </div>
    </div>

    <!-- Property Details -->
    <div class="details">
        <div><h4>Owner</h4><?php echo htmlspecialchars($property['owner']); ?></div>
        <div><h4>Mobile</h4><?php echo htmlspecialchars($property['mobile']); ?></div>
        <div><h4>Bedrooms</h4><?php echo htmlspecialchars($property['beds']); ?></div>
        <div><h4>Bathrooms</h4><?php echo htmlspecialchars($property['baths']); ?></div>
        <div><h4>Area</h4><?php echo htmlspecialchars($property['area']); ?></div>
        <div><h4>Price</h4><?php echo htmlspecialchars($property['price']); ?></div>
    </div>

    <!-- Contact Buttons -->
    <div class="contact">
        <a href="tel:<?php echo htmlspecialchars($property['mobile']); ?>" class="call-btn">📞 Call Owner</a>
        <a href="https://wa.me/<?php echo preg_replace('/\D/', '', $property['mobile']); ?>" target="_blank" class="whatsapp-btn">💬 WhatsApp</a>
    </div>
</div>

<script>
let slideIndex = 0;
const slides = document.querySelectorAll(".slider img");

function showSlide(index){
    slides.forEach((slide,i)=>{
        slide.style.display = i===index ? 'block':'none';
    });
}

function nextSlide(){
    slideIndex = (slideIndex+1) % slides.length;
    showSlide(slideIndex);
}

function prevSlide(){
    slideIndex = (slideIndex-1 + slides.length) % slides.length;
    showSlide(slideIndex);
}
</script>

</body>
</html>