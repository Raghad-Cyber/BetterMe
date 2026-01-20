<?php
require_once __DIR__ . '/../secure_config/config.php';

// if the user already logged in, go to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>BetterMe – Home page</title>
  <meta name="description" content="BetterMe helps you build and track habits easily — set goals, stay consistent, and visualize your progress.">
  <link rel="stylesheet" href="style.css">
</head>
<body class="home-page">
  
  <!-- =====================================
       Header (Navigation bar)
       Contains the site logo and main links
  ====================================== -->
  <header class="app-header">
    <div class="container">
      
      <!-- Site Logo - link to home page -->
      <a href="home.html" class="brand">
        <strong>BetterMe</strong>
      </a>

      <!-- Main Navigation Menu -->
      <nav class="main-nav">
        <a href="help.html"class="btn btn-primary">Help</a>
        <a href="login.php" class="btn btn-primary">Log In</a>
      </nav>

    </div>
  </header>

  <!-- Main -->
  <main id="main-content">
  
    <!-- =====================================
         Hero Section (Top Intro Section)
         First thing the user sees 
    ====================================== -->
    <section class="hero">
      <div class="container">
        
       
        <h1>Build Small Habits, See Big Results</h1>

        <!-- Short description of the platform -->
        <p class="subtitle">
          Choose a habit, set your duration (30 / 60 / 90 days), and track your progress daily or weekly — with visual motivation every step of the way.
        </p>
        <a href="login.php" class="btn btn-primary">Get Started</a>

        <!-- <div class="hero-illustration" aria-hidden="true"></div> -->
      </div>
    </section>

  <!-- =====================================
         Features Section (Why BetterMe)
         Displays key benefits of the platform
    ====================================== -->
    <section class="features">
      <div class="container">

        <h2>Why BetterMe?</h2>

        <div class="features-list">

          <!-- Feature 1 -->
          <div class="feature-item">
            <h3>Simple Tracking</h3>
            <p>Log your daily or weekly progress with just one click.</p>
          </div>

          <!-- Feature 2 -->
          <div class="feature-item">
            <h3>Visual Progress</h3>
            <p>See your achievements through progress bars and streak indicators.</p>
          </div>

          <!-- Feature 3 -->
          <div class="feature-item">
            <h3>Motivational Feedback</h3>
            <p>Get encouraging messages every time you stay consistent.</p>
          </div>

          <!-- Feature 4 -->
          <div class="feature-item">
            <h3>Flexible Durations</h3>
            <p>Choose from 30, 60, or 90-day plans that fit your lifestyle and goals.</p>
          </div>

        </div>
      </div>
    </section>

    <!-- =====================================
         Our Mission Section
         Simple description of the platform's purpose
    ====================================== -->
    <section class="preview">
      <div class="container">

        <h2>Our Mission</h2>
        <div class="preview-card white">
          <p>
           We believe that transformation starts with small steps.
Our mission is to empower you with the tools, structure, and motivation needed to turn simple actions into lasting personal growth.
        </div>

      </div>
    </section>
    
  </main>
  <!-- =====================================
       Footer Section
       Contains copyright information
  ====================================== -->
  <footer class="app-footer">
    <div class="container">
      <p>© 2025 BetterMe</p>
    </div>
  </footer>
</body>
</html>