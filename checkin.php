
<?php
require_once __DIR__ . '/../secure_config/config.php';
require 'auth.php';
require_login();

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

$habit_id = filter_input(INPUT_POST, 'habit_id', FILTER_VALIDATE_INT);
if (!$habit_id) {
    $habit_id = filter_input(INPUT_GET, 'habit_id', FILTER_VALIDATE_INT);
}

if (!$habit_id || $habit_id <= 0) {
    header('Location: dashboard.php');
    exit;
}

$sql = "SELECT id FROM habits WHERE id = :id AND user_id = :user_id";
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

$today = date('Y-m-d');

$sqlCheck = "SELECT COUNT(*) FROM habit_checkins
             WHERE habit_id = :habit_id AND checkin_date = :today";
$stmtCheck = $pdo->prepare($sqlCheck);
$stmtCheck->execute([
    ':habit_id' => $habit_id,
    ':today'    => $today
]);
$exists = (int) $stmtCheck->fetchColumn();

if ($exists === 0) {
    $sqlInsert = "INSERT INTO habit_checkins (habit_id, checkin_date)
                  VALUES (:habit_id, :checkin_date)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([
        ':habit_id'     => $habit_id,
        ':checkin_date' => $today
    ]);
}

header('Location: habit.php?id=' . $habit_id);
exit;