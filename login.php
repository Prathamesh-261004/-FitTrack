<?php
include 'config.php';
session_start();
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];
  $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
  $user = mysqli_fetch_assoc($result);
  if ($user && password_verify($password, $user["password"])) {
    $_SESSION["user_id"] = $user["id"];
    header("Location: dashboard.php");
    exit();
  } else {
    $msg = "Invalid credentials!";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login | FitTrack</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap & Animate.css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(to right, #2c3e50, #3498db);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }
    .login-card {
      background: white;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      max-width: 400px;
      width: 100%;
    }
    .form-control {
      border-radius: 8px;
    }
    .btn-primary {
      width: 100%;
      border-radius: 8px;
    }
    .form-title {
      font-weight: 600;
      text-align: center;
      margin-bottom: 1.5rem;
      color: #2c3e50;
    }
    .fade-in {
      animation: fadeInUp 1s ease-in-out;
    }
  </style>
</head>
<body>

<div class="login-card animate__animated animate__fadeInUp">
  <h2 class="form-title">👋 Welcome Back to <span style="color: #3498db;">FitTrack</span></h2>
  <form method="POST" class="fade-in">
    <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
    <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
    <button class="btn btn-primary">Login</button>
  </form>
  <?php if (!empty($msg)): ?>
    <div class="alert alert-danger mt-3"><?= $msg ?></div>
  <?php endif; ?>
</div><!-- Floating Back Button -->
<a href="javascript:history.back()" 
   style="position: fixed; bottom: 20px; left: 20px; background-color: #007bff; color: white; 
          padding: 12px 18px; border-radius: 50px; text-decoration: none; font-weight: bold; 
          box-shadow: 0 4px 12px rgba(0,0,0,0.2); transition: background-color 0.3s ease;">
  ⬅️ Back
</a>


</body>
</html>
