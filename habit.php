
<?php
require_once __DIR__ . '/../secure_config/config.php';
require 'auth.php';
require_login();

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

$habit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$habit_id || $habit_id <= 0) {
    header('Location: dashboard.php');
    exit;
}

$sql = "SELECT id, name, duration_days, tracking_type, start_date
        FROM habits
        WHERE id = :id AND user_id = :user_id
        LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id'      => $habit_id,
    ':user_id' => $user_id
]);
$habit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$habit) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $deleteSql = "DELETE FROM habits WHERE id = :id AND user_id = :user_id";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->execute([
        ':id'      => $habit_id,
        ':user_id' => $user_id
    ]);

    header('Location: dashboard.php');
    exit;
}

$logsSql = "SELECT checkin_date
            FROM habit_checkins
            WHERE habit_id = :habit_id
            ORDER BY checkin_date DESC";
$logsStmt = $pdo->prepare($logsSql);
$logsStmt->execute([':habit_id' => $habit_id]);
$logs = $logsStmt->fetchAll(PDO::FETCH_ASSOC);

$completed = count($logs);
$total     = (int) $habit['duration_days'];
$progress  = $total > 0 ? round(($completed / $total) * 100) : 0;
$remaining = $total > 0 ? max($total - $completed, 0) : 0;

$streakSlots = 14;


$filledDays = min($completed, $streakSlots);


if ($progress === 0) {
    $msg = "Every big change starts with a single step.";
} elseif ($progress < 40) {
    $msg = "Great start! Keep going.";
} elseif ($progress < 80) {
    $msg = "You're more than halfway there.";
} elseif ($progress < 100) {
    $msg = "Almost done! Push through.";
} else {
    $msg = "Amazing! You completed this habit!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>BetterMe – Habit Details</title>
  <link rel="stylesheet" href="style.css">
</head>

<body class="habit-page">
  <header class="app-header">
    <div class="container">
      <a href="home.php" class="brand"><strong>BetterMe</strong></a>
      <nav class="main-nav">
          <a href="help.html" class="btn btn-primary">Help</a>
          <a href="dashboard.php" class="btn btn-primary">Dashboard</a>
      </nav>
    </div>
  </header>

  <main id="main-content" class="habit-detail">
    <section class="hero">
      <div class="container">
        <a href="dashboard.php" class="link">← Back to dashboard</a>

        <!-- Habit Name -->
        <h1><?= htmlspecialchars($habit['name'], ENT_QUOTES, 'UTF-8') ?></h1>

        <!-- Badges -->
        <div class="badges">
          <span class="badge">Duration: <?= $habit['duration_days'] ?> days</span>
          <span class="badge">Tracking: <?= htmlspecialchars($habit['tracking_type']) ?></span>
          <span class="badge">Start: <?= htmlspecialchars($habit['start_date']) ?></span>
          <span class="badge">Days left: <?= $remaining ?></span>
        </div>

        <!-- Actions -->
        <div class="habit-actions">

          <!-- Mark today done -->
          <form method="post" action="checkin.php" style="display:inline;">
            <input type="hidden" name="habit_id" value="<?= $habit['id'] ?>">
            <button class="btn btn-primary" type="submit">Mark Today as Done ✅</button>
          </form>

          <!-- Edit Habit (placeholder, optional) -->
          <a class="btn btn-secondary" href="onboarding.php">Edit Habit</a>
        </div>

      </div>
    </section>

    <section class="container page-section">
      <div class="grid-2">

        <!-- Progress Section -->
        <section class="section" aria-label="Visual progress">
          <h2>Progress</h2>

          <div class="progress-ring">
            <span><?= $progress ?>%</span>
          </div>

          <p style="text-align:center;">
            Completed <strong><?= $completed ?></strong> of <strong><?= $total ?></strong>
          </p>
        </section>

        <!-- Streak Section -->
        <section class="section" aria-label="Streak">
          <h2>Streak</h2>

          <p>Current streak: <strong><?= $completed ?></strong> days</p>

          <div class="streak-calendar" aria-label="Streak boxes">
           <?php for ($i = 1; $i <= $streakSlots; $i++): ?>
      <div class="day <?= ($i <= $filledDays) ? 'filled' : '' ?>"></div>
          <?php endfor; ?>
            </div>

          <p class="form-note">Filled squares = completed days</p>
        </section>

      </div>

      <!-- Encouragement -->
      <section class="section" aria-label="Encouragement"> 
        <h2>Encouragement</h2> 
        <article class="preview-card white"> 
          <h3>Encouragement message</h3> 
          <p><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>
        </article> 
      </section> 

      <!-- Activity Log -->
      <section class="section" aria-label="Activity log">
        <h2>Activity</h2>
        <ul class="activity-log">

          <?php if (empty($logs)): ?>
            <li>No check-ins yet.</li>
          <?php else: ?>
            <?php foreach ($logs as $log): ?>
              <li>Completed on <?= htmlspecialchars($log['checkin_date'], ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
          <?php endif; ?>

        </ul>
      </section>

      <!-- Danger Zone -->
      <section class="section danger" aria-label="Delete habit">
        <h2>Danger Zone</h2>
<p>Deleting this habit will remove all its data.</p>

        <form method="post" onsubmit="return confirm('Are you sure you want to delete this habit?');">
          <button class="btn btn-danger" type="submit" name="delete" value="1">
            Delete Habit
          </button>
        </form>
      </section>
    </section>
  </main>

  <footer class="app-footer">
    <div class="container">
      <p>© 2025 BetterMe</p>
    </div>
  </footer>
</body>
</html>



