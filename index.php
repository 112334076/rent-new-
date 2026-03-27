<?php
session_start();

// Logout handling
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LuxEstate - Home</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#f5efe6;color:#2c2c2c;}

/* NAVBAR */
nav{display:flex;justify-content:space-between;align-items:center;padding:20px 60px;background:white;box-shadow:0 5px 15px rgba(0,0,0,0.05);}
.logo a{text-decoration:none;color:#c9a44c;font-size:24px;font-weight:600;}
.menu a{margin-left:25px;text-decoration:none;color:#555;font-weight:500;cursor:pointer;}
.menu a:hover{color:#c9a44c;}
.auth-buttons{display:flex;gap:15px;align-items:center;}
.auth-btn{padding:8px 18px;border-radius:8px;font-weight:500;cursor:pointer;transition:0.3s;}
.auth-btn:hover{opacity:0.85;}

/* HERO */
.hero{display:flex;justify-content:space-between;align-items:center;padding:80px 60px;}
.hero-text{max-width:500px;}
.hero h1{font-size:48px;margin-bottom:15px;}
.hero span{color:#c9a44c;}
.hero p{color:#666;margin-bottom:25px;}
.btn{padding:12px 30px;background:#c9a44c;border:none;border-radius:8px;color:white;font-weight:600;cursor:pointer;text-decoration:none;display:inline-block;}

/* SEARCH BOX */
.search-box{margin:40px 60px;background:white;padding:25px;border-radius:12px;display:flex;gap:20px;box-shadow:0 10px 20px rgba(0,0,0,0.05);}
.search-box input, .search-box select{padding:10px;border-radius:8px;border:1px solid #ddd;flex:1;}

/* PROPERTIES */
.section{padding:60px;}
.section h2{margin-bottom:30px;font-size:32px;}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:30px;}
.card{background:white;border-radius:12px;overflow:hidden;box-shadow:0 10px 20px rgba(0,0,0,0.05);}
.card img{width:100%;height:220px;object-fit:cover;}
.card-content{padding:20px;}
.tag{background:#c9a44c;color:white;font-size:12px;padding:4px 10px;border-radius:20px;display:inline-block;margin-bottom:10px;}
.price{color:#c9a44c;font-weight:600;margin-top:10px;}
.details{display:flex;gap:15px;margin-top:8px;color:#777;font-size:14px;}
.card-buttons{margin-top:15px;display:flex;gap:10px;}
.card-buttons a{flex:1;}
.card-buttons button{width:100%;padding:8px;border-radius:6px;border:1px solid #c9a44c;background:none;cursor:pointer;}
.card-buttons button:hover{background:#c9a44c;color:white;}

/* FOOTER */
footer{text-align:center;padding:30px;background:#222;color:white;margin-top:40px;}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav>
  <div class="logo"><a href="index.php">LuxEstate</a></div>

  <div class="menu">
    <a onclick="filterProperty('all')">All</a>
    <a onclick="filterProperty('buy')">Buy</a>
    <a onclick="filterProperty('rent')">Rent</a>
    <a onclick="filterProperty('villa')">Villas</a>
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

<!-- HERO -->
<section class="hero">
  <div class="hero-text">
    <h1>Find Your <span>Perfect</span> Villa or Rental</h1>
    <p>Discover luxury villas and premium rental homes.</p>
    <a href="#properties" class="btn">Explore Properties</a>
  </div>
</section>

<!-- SEARCH -->
<div class="search-box">
  <input type="text" placeholder="Location">
  <select>
    <option>Property Type</option>
    <option>Villa</option>
    <option>Apartment</option>
    <option>House</option>
  </select>
  <select>
    <option>Budget</option>
    <option>₹20L</option>
    <option>₹50L</option>
    <option>₹1Cr</option>
  </select>
  <button class="btn">Search</button>
</div>

<!-- PROPERTIES -->
<section class="section" id="properties">
  <h2>Featured Properties</h2>
  <div class="grid">

    <!-- Property 1 -->
    <div class="card" data-type="buy villa">
      <img src="1.webp" alt="Cliffside Villa">
      <div class="card-content">
        <span class="tag">For Sale</span>
        <h3>Cliffside Villa with Private Beach</h3>
        <p>📍 Goa</p>
        <div class="details"><span>4 Beds</span><span>5 Baths</span><span>4800 sqft</span></div>
        <div class="price">₹4,50,00,000</div>
        <div class="card-buttons">
          <?php if(isset($_SESSION['email'])): ?>
            <a href="details.php?id=1"><button>View Details</button></a>
          <?php else: ?>
            <a href="signin.php"><button>View Details</button></a>
          <?php endif; ?>
          <button>❤ Favorite</button>
        </div>
      </div>
    </div>

    <!-- Property 2 -->
    <div class="card" data-type="buy villa">
      <img src="2.webp" alt="Sea Facing Villa">
      <div class="card-content">
        <span class="tag">For Sale</span>
        <h3>Sea Facing Luxury Villa</h3>
        <p>📍 Chennai ECR</p>
        <div class="details"><span>5 Beds</span><span>6 Baths</span><span>5200 sqft</span></div>
        <div class="price">₹3,20,00,000</div>
        <div class="card-buttons">
          <?php if(isset($_SESSION['email'])): ?>
            <a href="details.php?id=2"><button>View Details</button></a>
          <?php else: ?>
            <a href="signin.php"><button>View Details</button></a>
          <?php endif; ?>
          <button>❤ Favorite</button>
        </div>
      </div>
    </div>

    <!-- Property 3 -->
    <div class="card" data-type="rent house">
      <img src="3.webp" alt="Rental Home">
      <div class="card-content">
        <span class="tag" style="background:#6c8f5a">For Rent</span>
        <h3>3 BHK Furnished Rental Home</h3>
        <p>📍 Bengaluru</p>
        <div class="details"><span>3 Beds</span><span>3 Baths</span><span>1600 sqft</span></div>
        <div class="price">₹45,000 / month</div>
        <div class="card-buttons">
          <?php if(isset($_SESSION['email'])): ?>
            <a href="details.php?id=3"><button>View Details</button></a>
          <?php else: ?>
            <a href="signin.php"><button>View Details</button></a>
          <?php endif; ?>
          <button>❤ Favorite</button>
        </div>
      </div>
    </div>

  </div>
</section>

<footer>© 2026 LuxEstate</footer>

<script>
function filterProperty(type){
  let cards = document.querySelectorAll(".card");
  cards.forEach(card => {
    let propertyType = card.getAttribute("data-type");
    if(type === "all"){ card.style.display = "block"; }
    else if(propertyType.includes(type)){ card.style.display = "block"; }
    else{ card.style.display = "none"; }
  });
}
</script>

</body>
</html>