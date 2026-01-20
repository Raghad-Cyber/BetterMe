<?php
require_once __DIR__ . '/../secure_config/config.php';
require 'auth.php';
require_login();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title         = trim($_POST['title'] ?? '');
    $duration      = isset($_POST['duration']) ? (int)$_POST['duration'] : 0;
    $tracking_type = $_POST['tracking_type'] ?? '';
    $startDate     = $_POST['startDate'] ?? null;
    $reminders     = isset($_POST['reminders']) ? 1 : 0;
    $user_id       = $_SESSION['user_id'];

    // Validation
    if ($title === '') {
        $errors[] = "Habit name is required.";
    }

    if (!in_array($duration, [30, 60, 90], true)) {
        $errors[] = "Please select a valid duration (30 / 60 / 90 days).";
    }

    if (!in_array($tracking_type, ['daily', 'weekly'], true)) {
        $errors[] = "Please select a valid tracking type (daily or weekly).";
    }

    // If no errors → insert
    if (empty($errors)) {
        $sql = "INSERT INTO habits
                (user_id, name, duration_days, tracking_type, reminders_enabled, start_date)
                VALUES (:user_id, :name, :duration_days, :tracking_type, :reminders_enabled,
                        COALESCE(:start_date, CURRENT_DATE))";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id'          => $user_id,
            ':name'             => $title,
            ':duration_days'    => $duration,
            ':tracking_type'    => $tracking_type,
            ':reminders_enabled'=> $reminders,
            ':start_date'       => $startDate ?: null,
        ]);

        header('Location: dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>BetterMe – Create Your Habit</title>
  <link rel="stylesheet" href="style.css">
</head>

<body class="onboarding-page">
  <header class="app-header">
    <div class="container">
      <a href="home.php" class="brand"><strong>BetterMe</strong></a>
      <nav class="main-nav">
        <a href="help.html" class="btn btn-primary">Help</a>
        <a href="dashboard.php" class="btn btn-primary">Dashboard</a>
        <a href="logout.php" class="btn btn-secondary">Log out</a>
      </nav>
    </div>
  </header>

  <main id="main-content" class="onboarding">
    <section class="hero">
      <div class="container">
        <h1>Create Your Habit</h1>
        <p class="subtitle">Pick a name, select a duration, and choose how you want to track it.</p>
      </div>
    </section>

    <section class="container page-section">
      <form class="card" action="onboarding.php" method="post">

        <!-- ERROR BOX (PHP + JS) -->
        <?php
        $hasPhpErrors = !empty($errors);
        ?>
        <div id="errorBox" style="
          <?php echo $hasPhpErrors ? 'display:block;' : 'display:none;'; ?>
          background:white;
          border:1px solid #f5b5b5;
          padding:10px 14px;
          border-radius:12px;
          margin-bottom:15px;
          color:#c62828;
          font-size:0.9rem;">
          <?php if ($hasPhpErrors): ?>
            <?php foreach ($errors as $e): ?>
              &times; <?php echo htmlspecialchars($e, ENT_QUOTES); ?><br>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <!-- END ERROR BOX -->

        <div class="form-group">
          <label for="title">Habit name</label>
          <input
            id="title"
            name="title"
            type="text"
            placeholder="e.g. Read 10 pages"
            required
            value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title'], ENT_QUOTES) : ''; ?>"
          >
        </div>

        <div class="form-group">
          <p>Duration</p>

          <div class="chip-group" role="group" aria-label="Duration">
            <button type="button" class="chip" data-duration="30">30 days</button>
            <button type="button" class="chip" data-duration="60">60 days</button>
            <button type="button" class="chip" data-duration="90">90 days</button>
            </div>
        </div>

        <div class="form-group">
          <p>Tracking</p>

          <div class="segmented" role="group" aria-label="Tracking frequency">
            <button type="button" class="segment" data-tracking="daily">Daily</button>
            <button type="button" class="segment" data-tracking="weekly">Weekly</button>
          </div>
        </div>

        <div class="form-group">
          <label for="startDate">Start date (optional)</label>
          <input
            id="startDate"
            name="startDate"
            type="date"
            value="<?php echo isset($_POST['startDate']) ? htmlspecialchars($_POST['startDate'], ENT_QUOTES) : ''; ?>"
          >
        </div>

        <div class="form-group">
          <label for="reminders">
            <input
              id="reminders"
              name="reminders"
              type="checkbox"
              <?php echo isset($_POST['reminders']) ? 'checked' : ''; ?>
            >
            Enable reminders (optional)
          </label>
        </div>


        <input
          type="hidden"
          id="durationInput"
          name="duration"
          value="<?php echo isset($_POST['duration']) ? htmlspecialchars($_POST['duration'], ENT_QUOTES) : ''; ?>"
        >
        <input
          type="hidden"
          id="trackingInput"
          name="tracking_type"
          value="<?php echo isset($_POST['tracking_type']) ? htmlspecialchars($_POST['tracking_type'], ENT_QUOTES) : ''; ?>"
        >

        <div class="form-actions">
          <button class="btn btn-primary" type="submit">Save Habit</button>
          <a href="dashboard.php" class="link">Skip for now</a>
        </div>
      </form>
    </section>
  </main>

  <footer class="app-footer">
    <div class="container">
      <p>© 2025 BetterMe</p>
    </div>
  </footer>

  <script src="onboarding.js"></script>
</body>
</html>