<?php
require_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage - SwinEduTech</title>
</head>
<body>
    <h1>HR Manager Dashboard</h1>
    <p>Logged in as: <?php echo htmlspecialchars((string) ($_SESSION['username'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
</body>
</html>