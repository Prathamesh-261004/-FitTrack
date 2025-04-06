<?php
include 'config.php';
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

  $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
  if (mysqli_query($conn, $sql)) {
    $msg = "✅ Registered successfully. <a href='login.php' class='text-decoration-underline'>Login here</a>";
  } else {
    $msg = "❌ Error: " . mysqli_error($conn);
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register | FitTrack</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
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
    .register-card {
      background: #fff;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 450px;
    }
    .form-title {
      text-align: center;
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 1.5rem;
    }
    .form-control {
      border-radius: 8px;
    }
    .btn {
      border-radius: 8px;
    }
  </style>
</head>
<body>

<div class="register-card animate__animated animate__fadeIn">
  <h2 class="form-title">📝 Create Your <span style="color: #3498db;">FitTrack</span> Account</h2>
  <form method="POST">
    <input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>
    <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
    <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
    <div class="d-grid gap-2 mb-2">
      <button class="btn btn-primary">Register</button>
      <a href="login.php" class="btn btn-outline-secondary">Already have an account? Login</a>
    </div>
  </form>
  <?php if (!empty($msg)): ?>
    <div class="alert alert-info mt-3"><?= $msg ?></div>
  <?php endif; ?>
</div>
<!-- Floating Back Button -->
<a href="javascript:history.back()" 
   style="position: fixed; bottom: 20px; left: 20px; background-color: #007bff; color: white; 
          padding: 12px 18px; border-radius: 50px; text-decoration: none; font-weight: bold; 
          box-shadow: 0 4px 12px rgba(0,0,0,0.2); transition: background-color 0.3s ease;">
  ⬅️ Back
</a>


</body>
</html>
