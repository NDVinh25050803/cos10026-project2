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
    <header>
        <span>Logged in as: <strong><?php echo htmlspecialchars((string) ($_SESSION['username'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong></span>
        <form method="post" action="logout.php" style="display:inline; margin-left:12px;">
            <button type="submit">Logout</button>
        </form>
    </header>
    <main>
        <h1>HR Manager Dashboard</h1>
    </main>
</body>
</html>