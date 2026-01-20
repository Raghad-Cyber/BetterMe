<?php
require_once __DIR__ . '/../secure_config/config.php';
require 'auth.php';
require_login();

$user_id = $_SESSION['user_id'];

$sql = "SELECT h.id,
               h.name,
               h.duration_days,
               h.tracking_type,
               h.start_date,
               h.created_at,
               COUNT(c.id) AS completed_days
        FROM habits h
        LEFT JOIN habit_checkins c ON c.habit_id = h.id
        WHERE h.user_id = :user_id
        GROUP BY h.id, h.name, h.duration_days, h.tracking_type, h.start_date, h.created_at
        ORDER BY h.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$habits = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalHabits      = count($habits);
$totalProgressSum = 0;
$longestStreak    = 0;

foreach ($habits as $habit) {
    $completed = (int)$habit['completed_days'];
    $total     = (int)$habit['duration_days'];

    if ($total > 0) {
        $progress         = ($completed / $total) * 100;
        $totalProgressSum += $progress;
    }

    if ($completed > $longestStreak) {
        $longestStreak = $completed;
    }
}

$overallProgress = ($totalHabits > 0)
    ? round($totalProgressSum / $totalHabits)
    : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>BetterMe - Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard-page">
<header class="app-header">
  <div class="container">
    <a href="home.php" class="brand"><strong>BetterMe</strong></a>
    <nav class="main-nav">
      <a href="help.html" class="btn btn-primary">Help</a>
      <a href="onboarding.php" class="btn btn-primary">Add Habit</a>
      <a href="logout.php" class="btn">Log out</a>
    </nav>
  </div>
</header>

<main id="main-content" class="dashboard">

<section class="hero">
  <div class="container">
    <h1>Your Dashboard</h1>
    <p class="subtitle">Overview of your habits and daily consistency.</p>
  </div>
</section>

<section class="container page-section">
  <div class="summary">
    <div class="summary-card">
      <h3>Total Progress</h3>
      <p><?= $totalHabits === 0 ? '_' : $overallProgress . '%' ?></p>
    </div>

    <div class="summary-card">
      <h3>Current Streak</h3>
      <p><?= $totalHabits === 0 ? '_' : $longestStreak . ' days' ?></p>
    </div>

    <div class="summary-card">
      <h3>Your Habits</h3>
      <p><?= $totalHabits ?></p>
    </div>
  </div>
</section>

<section class="container habits" aria-label="Your habits">

<?php if ($totalHabits === 0): ?>
  <p>You dont have any habits yet. <a href="onboarding.php">Create your first habit</a>.</p>

<?php else: ?>

  <?php foreach ($habits as $habit):
    $completed = (int)$habit['completed_days'];
    $total     = (int)$habit['duration_days'];
    $progress  = $total > 0 ? round(($completed / $total) * 100) : 0;
  ?>
  <article class="habit-card">
    <h3><?= htmlspecialchars($habit['name']) ?></h3>
    <p>Progress: <?= $progress ?>% · Completed : <?= $completed ?> / <?= $total ?></p>

    <div class="progress-bar" aria-label="Progress bar">
      <div class="progress-bar-fill" style="width: <?= $progress ?>%;"></div>
    </div>

    <div class="habit-card-actions">
      <a class="btn btn-primary" href="habit.php?id=<?= $habit['id'] ?>">View</a>

      <form action="checkin.php" method="post" style="display:inline;">
        <input type="hidden" name="habit_id" value="<?= $habit['id'] ?>">
        <button class="btn btn-primary" type="submit">Check in</button>
      </form>
    </div>
  </article>
  <?php endforeach; ?>

<?php endif; ?>

</section>

</main>

<footer class="app-footer">
  <div class="container">
    <p>&copy; 2025 BetterMe</p>
  </div>
</footer>

</body>
</html>