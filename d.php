<?php
session_start();

// Simulate login (remove this when login system is implemented)
$_SESSION['user_id'] = 1;

// Database Connection
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

// Logged-in user ID
$userId = $_SESSION['user_id'];

// Fetch user name
$stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
$userName = $user ? htmlspecialchars($user['name']) : 'User';

// Form Handling
if (isset($_POST['workout_name'])) {
  $stmt = $pdo->prepare("INSERT INTO workouts (user_id, workout_name, duration_minutes, calories_burned, workout_date) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$userId, $_POST['workout_name'], $_POST['duration_minutes'], $_POST['calories_burned'], $_POST['workout_date']]);
}

if (isset($_POST['meal_name'])) {
  $stmt = $pdo->prepare("INSERT INTO meals (user_id, meal_name, calories, meal_time, meal_date) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$userId, $_POST['meal_name'], $_POST['calories'], $_POST['meal_time'], $_POST['meal_date']]);
}

if (isset($_POST['goal'])) {
  $stmt = $pdo->prepare("INSERT INTO personal_plans (user_id, goal, target_weight, plan_description, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([$userId, $_POST['goal'], $_POST['target_weight'], $_POST['plan_description'], $_POST['start_date'], $_POST['end_date']]);
}

if (isset($_POST['weight']) && isset($_POST['height']) && isset($_POST['bmi'])) {
  $stmt = $pdo->prepare("INSERT INTO progress (user_id, weight, height, bmi) VALUES (?, ?, ?, ?)");
  $stmt->execute([$userId, $_POST['weight'], $_POST['height'], $_POST['bmi']]);
}

if (isset($_POST['rating']) && isset($_POST['comment'])) {
  $stmt = $pdo->prepare("INSERT INTO reviews (user_id, rating, comment) VALUES (?, ?, ?)");
  $stmt->execute([$userId, $_POST['rating'], $_POST['comment']]);
}

// Fetching Data (User-specific)
$workouts = $pdo->prepare("SELECT * FROM workouts WHERE user_id = ? ORDER BY workout_date DESC");
$workouts->execute([$userId]);
$workouts = $workouts->fetchAll();

$meals = $pdo->prepare("SELECT * FROM meals WHERE user_id = ? ORDER BY meal_date DESC");
$meals->execute([$userId]);
$meals = $meals->fetchAll();

$plans = $pdo->prepare("SELECT * FROM personal_plans WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$plans->execute([$userId]);
$plans = $plans->fetch();

$progress = $pdo->prepare("SELECT * FROM progress WHERE user_id = ? ORDER BY id DESC");
$progress->execute([$userId]);
$progress = $progress->fetchAll();

$reviews = $pdo->prepare("SELECT * FROM reviews WHERE user_id = ? ORDER BY id DESC");
$reviews->execute([$userId]);
$reviews = $reviews->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FitTrack - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f4f6f8; }
    .section {
      margin-bottom: 2rem;
      padding: 1.5rem;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    h2 { border-bottom: 2px solid #dee2e6; padding-bottom: .5rem; margin-bottom: 1rem; }
    table { font-size: 0.9rem; }
  </style>
</head>
<body>
<div class="container py-4">
  <h1 class="text-center mb-2">📊 Welcome, <?= $userName ?>!</h1>
  <p class="text-center text-muted mb-4">Here’s your FitTrack dashboard</p>

  <!-- Workouts Section -->
  <div class="section">
    <h2>🏋️ Workouts</h2>
    <form method="POST" class="mb-3">
      <div class="row g-2">
        <div class="col"><input type="text" name="workout_name" class="form-control" placeholder="Workout" required></div>
        <div class="col"><input type="number" name="duration_minutes" class="form-control" placeholder="Duration (min)" required></div>
        <div class="col"><input type="number" name="calories_burned" class="form-control" placeholder="Calories" required></div>
        <div class="col"><input type="date" name="workout_date" class="form-control" required></div>
        <div class="col-auto"><button type="submit" class="btn btn-primary">Add</button></div>
      </div>
    </form>
    <table class="table table-sm table-striped">
      <thead><tr><th>Name</th><th>Duration</th><th>Calories</th><th>Date</th></tr></thead>
      <tbody>
        <?php foreach ($workouts as $w): ?>
          <tr>
            <td><?= htmlspecialchars($w['workout_name']) ?></td>
            <td><?= $w['duration_minutes'] ?> min</td>
            <td><?= $w['calories_burned'] ?> kcal</td>
            <td><?= $w['workout_date'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Meals Section -->
  <div class="section">
    <h2>🍽 Meals</h2>
    <form method="POST" class="mb-3">
      <div class="row g-2">
        <div class="col"><input type="text" name="meal_name" class="form-control" placeholder="Meal Name" required></div>
        <div class="col"><input type="number" name="calories" class="form-control" placeholder="Calories" required></div>
        <div class="col">
          <select name="meal_time" class="form-select" required>
            <option value="">Time</option><option>Breakfast</option><option>Lunch</option><option>Dinner</option><option>Snack</option>
          </select>
        </div>
        <div class="col"><input type="date" name="meal_date" class="form-control" required></div>
        <div class="col-auto"><button type="submit" class="btn btn-success">Add</button></div>
      </div>
    </form>
    <table class="table table-sm table-striped">
      <thead><tr><th>Meal</th><th>Calories</th><th>Time</th><th>Date</th></tr></thead>
      <tbody>
        <?php foreach ($meals as $m): ?>
          <tr>
            <td><?= htmlspecialchars($m['meal_name']) ?></td>
            <td><?= $m['calories'] ?> kcal</td>
            <td><?= $m['meal_time'] ?></td>
            <td><?= $m['meal_date'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Personal Plan Section -->
  <div class="section">
    <h2>🎯 Personal Plan</h2>
    <form method="POST" class="mb-3">
      <div class="row g-2">
        <div class="col"><input type="text" name="goal" class="form-control" placeholder="Goal" required></div>
        <div class="col"><input type="number" name="target_weight" class="form-control" placeholder="Target Weight" required></div>
        <div class="col"><input type="text" name="plan_description" class="form-control" placeholder="Description" required></div>
        <div class="col"><input type="date" name="start_date" class="form-control" required></div>
        <div class="col"><input type="date" name="end_date" class="form-control" required></div>
        <div class="col-auto"><button type="submit" class="btn btn-warning">Save</button></div>
      </div>
    </form>
    <?php if ($plans): ?>
      <div class="border p-3 bg-light rounded">
        <strong>Goal:</strong> <?= htmlspecialchars($plans['goal']) ?><br>
        <strong>Target:</strong> <?= $plans['target_weight'] ?> kg<br>
        <strong>Plan:</strong> <?= htmlspecialchars($plans['plan_description']) ?><br>
        <strong>From:</strong> <?= $plans['start_date'] ?> <strong>To:</strong> <?= $plans['end_date'] ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Progress Section -->
  <div class="section">
    <h2>📈 Progress / BMI</h2>
    <form method="POST" onsubmit="return calculateBMI();">
      <div class="row g-2">
        <div class="col"><input type="number" name="weight" id="weight" class="form-control" placeholder="Weight (kg)" required></div>
        <div class="col"><input type="number" name="height" id="height" class="form-control" placeholder="Height (cm)" required></div>
        <div class="col"><input type="text" name="bmi" id="bmiResult" class="form-control" placeholder="BMI" readonly required></div>
        <div class="col-auto"><button type="submit" class="btn btn-info">Save</button></div>
      </div>
    </form>
    <table class="table table-sm mt-3">
      <thead><tr><th>Weight</th><th>Height</th><th>BMI</th></tr></thead>
      <tbody>
        <?php foreach ($progress as $p): ?>
          <tr>
            <td><?= $p['weight'] ?> kg</td>
            <td><?= $p['height'] ?> cm</td>
            <td><?= $p['bmi'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Reviews Section -->
  <div class="section">
    <h2>⭐ Reviews</h2>
    <form method="POST" class="mb-3">
      <div class="row g-2">
        <div class="col-md-2">
          <select class="form-select" name="rating" required>
            <option value="">Rating</option>
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <option><?= $i ?></option>
            <?php endfor; ?>
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
    <?php foreach ($reviews as $r): ?>
      <div class="border p-2 rounded mb-2 bg-light">
        <strong>Rating:</strong> <?= $r['rating'] ?> ⭐ | <?= htmlspecialchars($r['comment']) ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
function calculateBMI() {
  const w = parseFloat(document.getElementById("weight").value);
  const h = parseFloat(document.getElementById("height").value) / 100;
  if (w > 0 && h > 0) {
    const bmi = (w / (h * h)).toFixed(2);
    document.getElementById("bmiResult").value = bmi;
    return true;
  }
  return false;
}
</script>
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
