<?php
/*
 * Shared <head> and site header.
 * Set these before including this file:
 *   $page_title, $page_description, $page_keywords, $body_id
 *   $page_style (optional) - extra CSS for this page only
 */
$esc = fn($s) => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?= $esc($page_description ?? '') ?>">
  <meta name="keywords" content="<?= $esc($page_keywords ?? '') ?>">
  <meta name="theme-color" content="#1c2551">
  <title>SwinEduTech - <?= $esc($page_title ?? '') ?></title>

  <!-- Favicon -->
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="icon" href="images/logo.svg" type="image/svg+xml">
  <link rel="apple-touch-icon" href="images/apple-touch-icon.png">

  <!-- Fonts: Atkinson Hyperlegible (Braille Institute) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible+Mono:wght@700&amp;family=Atkinson+Hyperlegible+Next:wght@400;600;700;800&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles/style.css">
<?php if (!empty($page_style)): ?>
  <!-- Embedded CSS (written in the page file, never from user input) -->
  <style>
<?= $page_style . "\n" ?>
  </style>
<?php endif; ?>
</head>
<body id="<?= $esc($body_id ?? '') ?>">
  <a class="skip-link" href="#main-content">Skip to main content</a>

  <header class="site-header">
    <div class="header-inner">
      <a href="index.php" class="brand">
        <img src="images/logo.png" alt="" width="40" height="40">
        <span translate="no">SwinEduTech</span>
      </a>
<?php include 'nav.inc'; ?>
    </div>
  </header>
