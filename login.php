<?php
require_once __DIR__ . '/../secure_config/config.php';


$errors = [];


if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '') {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($password === '') {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['name'];

    header('Location: dashboard.php');
    exit;
} else {
    $errors[] = "Incorrect email or password.";
}
        } catch (PDOException $e) {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>BetterMe – Log In</title>
  <link rel="stylesheet" href="style.css">
</head>

<body class="login-page">

  <!-- Header -->
  <header class="app-header">
    <div class="container">
      <a href="home.php" class="brand">
        <strong>BetterMe</strong>
      </a>
      <nav class="main-nav">
          <a href="help.html" class="btn btn-primary">Help</a>
      </nav>
    </div>
  </header>

  <!-- Main content -->
  <main id="main-content" class="onboarding">

    <!-- Hero intro -->
    <section class="hero">
      <div class="container">
        <h1>Ready to continue your journey?</h1>
        <p class="subtitle">Unlock your next step in the journey.</p>
      </div>
    </section>

    <!-- Login form -->
    <section class="container page-section">
      <form class="card" action="" method="post">

        <!-- ERROR BOX -->
        <div id="errorBox" class="error-box" style="
          background:white;
          border:1px solid #f5b5b5;
          padding:10px 14px;
          border-radius:12px;
          margin-bottom:15px;
          color:#c62828;
          font-size:0.9rem;
          <?php echo empty($errors) ? 'display:none;' : 'display:block;'; ?>
        ">
          <?php
          if (!empty($errors)) {
              echo implode('<br>', array_map(fn($e) => '✖ ' . htmlspecialchars($e), $errors));
          }
          ?>
        </div>
        <!-- END ERROR BOX -->

        <!-- Email field -->
        <div class="form-group">
          <label for="email">Email</label>
          <input
            id="email"
            name="email"
            type="email"
            placeholder="email@example.com"
            required
            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
          >
        </div>

        <!-- Password field -->
        <div class="form-group">
          <label for="password">Password</label>
          <input
            id="password"
            name="password"
            type="password"
            required
          >
        </div>

     
        <div class="form-group checkbox-group">
         <label for="remember">
           <input id="remember" name="remember" type="checkbox">
            Remember me
         </label>
        </div>

        <!-- Submit button -->
        <button class="btn btn-primary" type="submit">Log In</button>

        <!-- Sign up link -->
        <p class="form-note">
          Don’t have an account yet?
          <a href="signup.php" class="link">Sign up</a>
        </p>

      </form>
    </section>
  </main>

  <!-- Footer -->
  <footer class="app-footer">
    <div class="container">
      <p>© 2025 BetterMe</p>
    </div>
  </footer>

  <!-- Link JS -->
  <script src="login.js"></script>

</body>
</html>