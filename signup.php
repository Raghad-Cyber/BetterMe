<?php
require_once __DIR__ . '/../secure_config/config.php';


$errors = [];


if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName        = trim($_POST['fullName'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    
    if ($fullName === '') {
        $errors[] = "Full name is required.";
    }

    if ($email === '') {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($password === '') {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if ($confirmPassword === '') {
        $errors[] = "Confirm password is required.";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }


    if (empty($errors)) {
        try {

            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $errors[] = "This email is already registered.";
            } else {
               
               $hash = password_hash($password, PASSWORD_DEFAULT);

$insert = $pdo->prepare("
    INSERT INTO users (name, email, password, created_at)
    VALUES (?, ?, ?, NOW())
");
$insert->execute([$fullName, $email, $hash]);

                $_SESSION['user_id']   = $pdo->lastInsertId();
                $_SESSION['user_name'] = $fullName;

                header('Location: onboarding.php');
                exit;
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
  <title>BetterMe – Sign Up</title>
  <link rel="stylesheet" href="style.css">
</head>

<body class="signup-page">
   <header class="app-header">
    <div class="container">
      <a href="home.php" class="brand">
        <strong>BetterMe</strong>
      </a>
  
      <nav class="main-nav">
        <a href="help.html" class="btn btn-primary">Help</a>
        <a href="login.php" class="btn btn-primary">Log In</a>
      </nav>
    </div>
  </header>

  <main id="main-content" class="onboarding">
    <section class="hero">
      <div class="container">
        <h1>Start your journey</h1>
        <p class="subtitle">Your progress begins with one simple step.</p>
      </div>
    </section>

    <section class="container page-section" aria-label="Sign up form">
      <form class="card" action="" method="post">

        <!-- ERROR BOX -->
        <div id="errorBox" style="
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

        <div class="form-group">
          <label for="fullName">Full name</label>
          <input
            id="fullName"
            name="fullName"
            type="text"
            placeholder="Your name"
            required
            value="<?php echo htmlspecialchars($_POST['fullName'] ?? ''); ?>"
          >
        </div>
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

        <div class="form-group">
          <label for="password">Password</label>
          <input
            id="password"
            name="password"
            type="password"
            minlength="8"
            placeholder="Minimum 8 characters"
            required
          >
        </div>

        <div class="form-group">
          <label for="confirmPassword">Confirm password</label>
          <input
            id="confirmPassword"
            name="confirmPassword"
            type="password"
            placeholder="Repeat your password"
            required
          >
        </div>

        <button class="btn btn-primary" type="submit">Sign Up</button>

        <p class="form-note">
          Already have an account?
          <a href="login.php" class="link">Log in</a>
        </p>
      </form>
    </section>
  </main>

  <footer class="app-footer">
    <div class="container">
      <p>© 2025 BetterMe</p>
    </div>
  </footer>

  <script src="signup.js"></script>
</body>
</html>