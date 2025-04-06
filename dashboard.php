<?php
$host = 'localhost';
$db   = 'fittrack';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

// Handle Workout Form
if (isset($_POST['workout_name'])) {
  $stmt = $pdo->prepare("INSERT INTO workouts (user_id, workout_name, duration_minutes, calories_burned, workout_date) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([1, $_POST['workout_name'], $_POST['duration_minutes'], $_POST['calories_burned'], $_POST['workout_date']]);
  echo "<div class='alert alert-success'>Workout saved!</div>";
}

// Handle Meal Form
if (isset($_POST['meal_name'])) {
  $stmt = $pdo->prepare("INSERT INTO meals (user_id, meal_name, calories, meal_time, meal_date) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([1, $_POST['meal_name'], $_POST['calories'], $_POST['meal_time'], $_POST['meal_date']]);
  echo "<div class='alert alert-success'>Meal saved!</div>";
}

// Handle Personal Plan
if (isset($_POST['goal'])) {
  $stmt = $pdo->prepare("INSERT INTO personal_plans (user_id, goal, target_weight, plan_description, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([1, $_POST['goal'], $_POST['target_weight'], $_POST['plan_description'], $_POST['start_date'], $_POST['end_date']]);
  echo "<div class='alert alert-success'>Plan saved!</div>";
}

// Handle BMI/Progress Tracker
if (isset($_POST['weight']) && isset($_POST['height']) && isset($_POST['bmi'])) {
  $stmt = $pdo->prepare("INSERT INTO progress (user_id, weight, height, bmi) VALUES (?, ?, ?, ?)");
  $stmt->execute([1, $_POST['weight'], $_POST['height'], $_POST['bmi']]);
  echo "<div class='alert alert-success'>Progress saved!</div>";
}

// Handle Review
if (isset($_POST['rating']) && isset($_POST['comment'])) {
  $stmt = $pdo->prepare("INSERT INTO reviews (user_id, rating, comment) VALUES (?, ?, ?)");
  $stmt->execute([1, $_POST['rating'], $_POST['comment']]);
  echo "<div class='alert alert-success'>Review submitted!</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FitTrack Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .section {
      margin-bottom: 2rem;
      padding: 1.5rem;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    h2 {
      border-bottom: 2px solid #dee2e6;
      padding-bottom: 0.5rem;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>
<div class="container py-4">
  <h1 class="text-center mb-4">🏋️‍♀️ FitTrack Dashboard</h1>

  <!-- Workouts Section -->
  <div class="section">
    <h2>Workouts</h2>
    <form method="POST">
      <div class="row g-2">
        <div class="col-md-4">
          <input type="text" class="form-control" name="workout_name" placeholder="Workout Name" required>
        </div>
        <div class="col-md-2">
          <input type="number" class="form-control" name="duration_minutes" placeholder="Duration (min)" required>
        </div>
        <div class="col-md-2">
          <input type="number" class="form-control" name="calories_burned" placeholder="Calories Burned" required>
        </div>
        <div class="col-md-3">
          <input type="date" class="form-control" name="workout_date" required>
        </div>
        <div class="col-md-1">
          <button type="submit" class="btn btn-primary w-100">Add</button>
        </div>
      </div>
    </form>
  </div>

  <!-- Meals Section -->
  <div class="section">
    <h2>Meals</h2>
    <form method="POST">
      <div class="row g-2">
        <div class="col-md-4">
          <input type="text" class="form-control" name="meal_name" placeholder="Meal Name" required>
        </div>
        <div class="col-md-2">
          <input type="number" class="form-control" name="calories" placeholder="Calories" required>
        </div>
        <div class="col-md-2">
          <select class="form-select" name="meal_time" required>
            <option value="">Meal Time</option>
            <option>Breakfast</option>
            <option>Lunch</option>
            <option>Dinner</option>
            <option>Snack</option>
          </select>
        </div>
        <div class="col-md-3">
          <input type="date" class="form-control" name="meal_date" required>
        </div>
        <div class="col-md-1">
          <button type="submit" class="btn btn-success w-100">Add</button>
        </div>
      </div>
    </form>
  </div>

  <!-- Personal Plan Section -->
  <div class="section">
    <h2>Personal Plan</h2>
    <form method="POST">
      <div class="row g-2">
        <div class="col-md-3">
          <input type="text" class="form-control" name="goal" placeholder="Goal (e.g. Weight Loss)" required>
        </div>
        <div class="col-md-2">
          <input type="number" class="form-control" name="target_weight" placeholder="Target Weight" required>
        </div>
        <div class="col-md-3">
          <input type="text" class="form-control" name="plan_description" placeholder="Plan Description" required>
        </div>
        <div class="col-md-2">
          <input type="date" class="form-control" name="start_date" required>
        </div>
        <div class="col-md-2">
          <input type="date" class="form-control" name="end_date" required>
        </div>
      </div>
      <button type="submit" class="btn btn-warning mt-2">Save Plan</button>
    </form>
  </div>

  <!-- Progress / BMI Tracker -->
  <div class="section">
    <h2>Progress / BMI</h2>
    <form method="POST" onsubmit="return calculateBMI();">
      <div class="row g-2">
        <div class="col-md-3">
          <input type="number" class="form-control" name="weight" id="weight" placeholder="Weight (kg)" required>
        </div>
        <div class="col-md-3">
          <input type="number" class="form-control" name="height" id="height" placeholder="Height (cm)" required>
        </div>
        <div class="col-md-3">
          <input type="text" class="form-control" name="bmi" id="bmiResult" placeholder="BMI" readonly required>
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-info w-100">Calculate + Save</button>
        </div>
      </div>
    </form>
  </div>

  <!-- Auto Suggested Plan -->
  <div class="section" id="autoPlanSection" style="display:none;">
    <h2>Suggested Personal Plan</h2>
    <p id="bmiCategory" class="fw-bold"></p>
    <p id="planSuggestion"></p>
  </div>

  <!-- Diet Plan -->
  <div class="section" id="dietPlanSection" style="display:none;">
    <h2>Diet Plan</h2>
    <ul id="dietList" class="list-group"></ul>
  </div>

  <!-- Reviews Section -->
  <div class="section">
    <h2>Leave a Review</h2>
    <form method="POST">
      <div class="row g-2">
        <div class="col-md-2">
          <select class="form-select" name="rating" required>
            <option value="">Rating</option>
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
          </select>
        </div>
        <div class="col-md-8">
          <input type="text" class="form-control" name="comment" placeholder="Write your review..." required>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-dark w-100">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function calculateBMI() {
  const weight = parseFloat(document.getElementById("weight").value);
  const height = parseFloat(document.getElementById("height").value) / 100;
  if (weight > 0 && height > 0) {
    const bmi = (weight / (height * height)).toFixed(2);
    document.getElementById("bmiResult").value = bmi;
    generatePlan(bmi);
    return true; // allow form to submit
  }
  return false;
}

function generatePlan(bmi) {
  const bmiVal = parseFloat(bmi);
  let category = "", suggestion = "", meals = [];

  if (bmiVal < 18.5) {
    category = "Underweight";
    suggestion = "Increase calorie intake and strength training.";
    meals = ["🍳 Avocado toast + smoothie", "🍗 Chicken + rice", "🥜 Nuts + banana", "🥘 Salmon + quinoa"];
  } else if (bmiVal < 25) {
    category = "Normal";
    suggestion = "Maintain with balanced diet and regular exercise.";
    meals = ["🍳 Oatmeal + fruit", "🥗 Chicken + salad", "🍎 Yogurt + fruit", "🥘 Stir fry + protein"];
  } else if (bmiVal < 30) {
    category = "Overweight";
    suggestion = "Reduce calories and increase exercise.";
    meals = ["🍳 Eggs + whole grain toast", "🥗 Fish + broccoli", "🍏 Apple + almonds", "🥗 Soup + salad"];
  } else {
    category = "Obese";
    suggestion = "Start structured weight loss plan.";
    meals = ["🍳 Smoothie with greens", "🥗 Beans + quinoa", "🍇 Berries", "🥗 Zoodles + turkey"];
  }

  document.getElementById("autoPlanSection").style.display = "block";
  document.getElementById("dietPlanSection").style.display = "block";
  document.getElementById("bmiCategory").textContent = `BMI Category: ${category}`;
  document.getElementById("planSuggestion").textContent = suggestion;

  const dietList = document.getElementById("dietList");
  dietList.innerHTML = "";
  meals.forEach(item => {
    const li = document.createElement("li");
    li.classList.add("list-group-item");
    li.textContent = item;
    dietList.appendChild(li);
  });
}


</script>
<!-- Floating Button -->
<a href="d.php" class="btn btn-primary rounded-circle shadow" 
   style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px;
          display: flex; justify-content: center; align-items: center; font-size: 24px; z-index: 999;"
   title="Go to Details">
  ➕
</a>
<!-- Floating Logout Button -->
<a href="logout.php"
     style="position: fixed; top: 20px; right: 20px; background-color: #dc3545; color: white;
            padding: 10px 20px; border-radius: 50px; text-decoration: none; font-weight: bold;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15); transition: background-color 0.3s ease;">
    🔓 Logout
  </a>

  <!-- Floating Back Button -->
  <a href="javascript:history.back()"
     style="position: fixed; bottom: 20px; left: 20px; background-color: #007bff; color: white;
            padding: 10px 20px; border-radius: 50px; text-decoration: none; font-weight: bold;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2); transition: background-color 0.3s ease;">
    ⬅️ Back
  </a>

  <!-- Optional: JavaScript Hover Effects -->
  <script>
    const logoutBtn = document.querySelector('a[href="logout.php"]');
    logoutBtn.onmouseover = () => logoutBtn.style.backgroundColor = "#bb2d3b";
    logoutBtn.onmouseout = () => logoutBtn.style.backgroundColor = "#dc3545";

    const backBtn = document.querySelector('a[href^="javascript:history.back"]');
    backBtn.onmouseover = () => backBtn.style.backgroundColor = "#0056b3";
    backBtn.onmouseout = () => backBtn.style.backgroundColor = "#007bff";
  </script>
</body>
</html>
