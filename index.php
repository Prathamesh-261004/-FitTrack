<?php
include 'config.php'; // Make sure this connects to your MySQL database

// Fetch reviews with user names using INNER JOIN
$sql = "SELECT reviews.rating, reviews.comment, users.name 
        FROM reviews 
        INNER JOIN users ON reviews.user_id = users.id 
        ORDER BY reviews.id DESC";

$result = mysqli_query($conn, $sql);


// Count users
$userCountResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$userCount = mysqli_fetch_assoc($userCountResult)['total'];

// Count workouts
$workoutsResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM workouts");
$workoutsCount = mysqli_fetch_assoc($workoutsResult)['total'];

// Count meals
$mealsResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM meals");
$mealsCount = mysqli_fetch_assoc($mealsResult)['total'];
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FitTrack - Your Health Companion</title>

  <!-- Bootstrap & Fonts -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css"/>

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f7fafd;
    }
    .navbar {
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .jumbotron {
      background: linear-gradient(rgba(0,123,255,0.7), rgba(0,198,255,0.7)), url('https://images.unsplash.com/photo-1558611848-73f7eb4001a1?auto=format&fit=crop&w=1400&q=80') no-repeat center center;
      background-size: cover;
      color: white;
      padding: 6rem 2rem;
    }
    .section-title {
      font-size: 2.2rem;
      font-weight: bold;
      margin-bottom: 2rem;
    }
    .card:hover {
      transform: translateY(-5px);
      transition: 0.3s ease;
      box-shadow: 0 12px 25px rgba(0,0,0,0.1);
    }
    .info-section {
      background: #e0f7ff;
      padding: 4rem 2rem;
    }
    .info-section img {
      max-width: 100%;
      border-radius: 15px;
    }
    .counter {
      font-size: 2.5rem;
      font-weight: bold;
      color: #20c997;
    }
    .timeline {
      border-left: 3px solid #007bff;
      padding-left: 2rem;
      margin-left: 1rem;
    }
    .timeline .step {
      margin-bottom: 1.5rem;
      position: relative;
    }
    .timeline .step::before {
      content: '';
      position: absolute;
      left: -32px;
      top: 3px;
      height: 15px;
      width: 15px;
      background: #007bff;
      border-radius: 50%;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">🏋️ FitTrack</a>
  
</nav>

<!-- Hero -->
<div class="jumbotron text-center">
  <h1>Transform Your Health</h1>
  <p class="lead">AI-powered fitness tracking, meal planning & progress monitoring.</p>
  <a href="register.php" class="btn btn-light btn-lg mt-3">Join Now</a>
</div>
<!-- Floating Register Button -->
<a href="register.php" class="floating-btn" title="Create your account">
  <span class="icon">📝</span> Register
</a>

<style>
  .floating-btn {
    position: fixed;
    bottom: 25px;
    right: 25px;
    background: linear-gradient(135deg, #007bff, #00d4ff);
    color: white;
    padding: 14px 24px;
    border-radius: 50px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    box-shadow: 0 6px 18px rgba(0, 123, 255, 0.3);
    transition: all 0.3s ease-in-out;
    z-index: 999;
  }

  .floating-btn .icon {
    margin-right: 8px;
    font-size: 18px;
  }

  .floating-btn:hover {
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 8px 22px rgba(0, 123, 255, 0.4);
    text-decoration: none;
  }
</style>

<!-- About Section -->


<section class="info-section">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6 mb-4" data-aos="fade-right">
        <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=1000&q=80" alt="Healthy Lifestyle">
      </div>
      <div class="col-md-6" data-aos="fade-left">
        <h2 class="section-title text-primary">Why FitTrack?</h2>
        <p>Personalized AI workouts, intelligent meal suggestions, BMI analysis, and real-time tracking – all in one place.</p>
        <ul class="list-unstyled mt-3">
          <li><i class="fas fa-check-circle text-success mr-2"></i> Tailored Fitness Plans</li>
          <li><i class="fas fa-check-circle text-success mr-2"></i> Meal Tracker & Calorie Goals</li>
          <li><i class="fas fa-check-circle text-success mr-2"></i> Weekly Progress Insights</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- Counter -->
<section class="py-5 bg-light text-center">
  <div class="container">
    <h2 class="text-primary mb-4">🚀 FitTrack By The Numbers</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="counter display-5 text-success fw-bold" data-target="<?= $userCount ?>">0</div>
        <p class="mt-2">Users Joined</p>
      </div>
      <div class="col-md-4">
        <div class="counter display-5 text-danger fw-bold" data-target="<?= $workoutsCount ?>">0</div>
        <p class="mt-2">Workouts Logged</p>
      </div>
      <div class="col-md-4">
        <div class="counter display-5 text-warning fw-bold" data-target="<?= $mealsCount ?>">0</div>
        <p class="mt-2">Meals Tracked</p>
      </div>
    </div>
  </div>
</section>

<!-- Counter Animation -->
<script>
  const counters = document.querySelectorAll('.counter');
  counters.forEach(counter => {
    const updateCount = () => {
      const target = +counter.getAttribute('data-target');
      const count = +counter.innerText;
      const increment = Math.ceil(target / 100); // Speed of counting

      if (count < target) {
        counter.innerText = count + increment;
        setTimeout(updateCount, 30);
      } else {
        counter.innerText = target;
      }
    };
    updateCount();
  });
</script>

<!-- Goal Highlights -->
<section class="text-center py-5">
  <div class="container">
    <h2 class="section-title text-primary">💪 Set & Crush Your Goals</h2>
    <div class="row">
      <div class="col-md-4">
        <i class="fas fa-running fa-3x text-info mb-2"></i>
        <h5>Daily Workout Goals</h5>
      </div>
      <div class="col-md-4">
        <i class="fas fa-apple-alt fa-3x text-success mb-2"></i>
        <h5>Healthy Eating Targets</h5>
      </div>
      <div class="col-md-4">
        <i class="fas fa-weight fa-3x text-danger mb-2"></i>
        <h5>Track BMI & Fat %</h5>
      </div>
    </div>
  </div>
</section>

<!-- BMI Calculator -->
<section class="bg-light py-5 text-center">
  <div class="container">
    <h2 class="section-title text-primary">📏 Calculate Your BMI</h2>
    <form class="form-inline justify-content-center">
      <input type="number" id="weight" class="form-control mr-2 mb-2" placeholder="Weight (kg)">
      <input type="number" id="height" class="form-control mr-2 mb-2" placeholder="Height (cm)">
      <button type="button" class="btn btn-primary mb-2" onclick="calculateBMI()">Calculate</button>
    </form>
    <p id="bmiResult" class="mt-3 font-weight-bold text-success"></p>
  </div>
</section>


<section class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center text-primary mb-5">👤 About Us</h2>
    <div class="row align-items-center">
      <!-- Founder Image & Name -->
      <div class="col-md-4 text-center mb-4 mb-md-0">
        <div class="founder-img-wrapper">
          <img src="j.png"
               alt="Founder"
               class="founder-img-rect shadow-sm">
          <p class="mt-2 font-weight-bold text-secondary">Prathamesh Rane– Founder</p>
        </div>
      </div>

      <!-- About Text + Button -->
      <div class="col-md-8">
        <p class="lead mb-3">
          Welcome to <strong>FitTrack</strong> – your trusted health & fitness companion.  
          Founded by a passionate wellness coach, we combine AI technology and real-world experience 
          to deliver personalized workout and meal plans that help you reach your goals faster. 
          Whether you're a beginner or a fitness enthusiast, FitTrack is here to support your transformation every step of the way.
        </p>
        <a href="about.html" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
          🔎 Know More
        </a>
      </div>
    </div>
  </div>
</section>
<style>.founder-img-wrapper {
  display: inline-block;
  overflow: hidden;
  border-radius: 15px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  max-width: 220px;
  margin: 0 auto;
}

.founder-img-rect {
  width: 100%;
  height: auto;
  border-radius: 15px;
  object-fit: cover;
  transition: transform 0.4s ease-in-out, box-shadow 0.4s ease-in-out;
}

.founder-img-wrapper:hover .founder-img-rect {
  transform: scale(1.05);
  box-shadow: 0 10px 25px rgba(0, 123, 255, 0.3);
}

.founder-img-wrapper p {
  font-size: 0.95rem;
  margin-top: 10px;
  transition: color 0.3s ease;
}

.founder-img-wrapper:hover p {
  color: #007bff;
}
</style>
<!-- Testimonials Carousel -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div style="max-width: 600px; margin: 0 auto; padding: 30px;">
  <h2 style="text-align: center; font-weight: 600; color:rgb(5, 112, 220); margin-bottom: 40px;">⭐ User Reviews</h2>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <div id="reviewCarousel" class="carousel slide" data-bs-ride="carousel" style="background-color: #f8f9fa; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 20px;">
      <div class="carousel-inner">

        <?php $active = true; ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <div class="carousel-item <?= $active ? 'active' : '' ?>">
            <div style="text-align: center;">
              <h5 style="color: #007bff; font-weight: bold;"><?= htmlspecialchars($row['name']) ?> says:</h5>
              <div style="font-size: 1rem; margin-top: 5px;"><strong>Rating:</strong> <?= $row['rating'] ?> ⭐</div>
              <p style="font-style: italic; color: #555; margin-top: 10px;">“<?= htmlspecialchars($row['comment']) ?>”</p>
            </div>
          </div>
          <?php $active = false; ?>
        <?php endwhile; ?>

      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#reviewCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1);"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#reviewCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1);"></span>
      </button>
    </div>
  <?php else: ?>
    <p style="text-align: center; color: #6c757d;">No reviews found.</p>
  <?php endif; ?>
</div>




<!-- Gallery -->
<section class="text-center bg-white py-5">
  <div class="container">
    <h2 class="section-title text-primary">📷 Explore Our Community</h2>
    <div class="row">
      <div class="col-md-4 mb-3">
        <img src="https://images.unsplash.com/photo-1605296867304-46d5465a13f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
             class="img-fluid rounded shadow gallery-img"
             alt="Healthy meal with greens and protein">
      </div>
      <div class="col-md-4 mb-3">
        <img src="https://images.pexels.com/photos/1472887/pexels-photo-1472887.jpeg?auto=compress&cs=tinysrgb&w=600"
             class="img-fluid rounded shadow gallery-img"
             alt="Outdoor fitness training">
      </div>
      <div class="col-md-4 mb-3">
        <img src="https://images.pexels.com/photos/8534767/pexels-photo-8534767.jpeg?auto=compress&cs=tinysrgb&w=600"
             class="img-fluid rounded shadow gallery-img"
             alt="Yoga at sunrise">
      </div>
    </div>
  </div>
</section>

<style>
  .gallery-img {
    height: 220px;
    width: 100%;
    object-fit: cover;
  }
</style>

    
  </div>
</section>

<!-- Steps -->
<section class="bg-light py-5">
  <div class="container">
    <h2 class="section-title text-primary text-center">📈 Your Journey with FitTrack</h2>
    <div class="timeline">
      <div class="step"><h5>Step 1: Register Your Account</h5></div>
      <div class="step"><h5>Step 2: Enter Your Goals</h5></div>
      <div class="step"><h5>Step 3: Get Your AI Plan</h5></div>
      <div class="step"><h5>Step 4: Track Meals & Workouts</h5></div>
      <div class="step"><h5>Step 5: Celebrate Progress!</h5></div>
    </div>
  </div>
</section>

<!-- Newsletter Signup -->
<section class="text-center py-5 bg-primary text-white">
  <div class="container">
    <h2 class="mb-4">📬 Stay Motivated</h2>
    <p>Subscribe for free workout guides, recipes, and weekly motivation.</p>
    <form class="form-inline justify-content-center">
      <input type="email" class="form-control mr-2 mb-2" placeholder="Your Email">
      <button type="submit" class="btn btn-light mb-2">Subscribe</button>
    </form>
  </div>
</section>

<!-- Footer -->
<footer class="text-center p-4">
  <p>&copy; 2025 FitTrack | Made with 💙 for your fitness journey</p>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();

  function animateCounter(id, target) {
    let el = document.getElementById(id);
    let count = 0;
    let step = Math.ceil(target / 60);
    let interval = setInterval(() => {
      count += step;
      if (count >= target) {
        count = target;
        clearInterval(interval);
      }
      el.textContent = count;
    }, 20);
  }

  animateCounter("userCount", 2500);
  animateCounter("workoutsLogged", 7400);
  animateCounter("mealsTracked", 4800);

  function calculateBMI() {
    const w = parseFloat(document.getElementById("weight").value);
    const h = parseFloat(document.getElementById("height").value) / 100;
    if (w > 0 && h > 0) {
      const bmi = (w / (h * h)).toFixed(2);
      document.getElementById("bmiResult").textContent = `Your BMI is ${bmi}`;
    } else {
      document.getElementById("bmiResult").textContent = "Please enter valid weight and height.";
    }
  }
</script>
</body>
</html>
