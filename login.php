<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db_connect.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: manage.php');
    exit;
}

$errorMessage = '';
$usernameInput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = trim((string) ($_POST['username'] ?? ''));
    $passwordInput = (string) ($_POST['password'] ?? '');

    if ($usernameInput === '' || $passwordInput === '') {
        $errorMessage = 'Invalid username or password.';
    } else {
        $sql = 'SELECT id, username, password_hash FROM users WHERE username = ? LIMIT 1';
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $usernameInput);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = $result ? mysqli_fetch_assoc($result) : null;
            mysqli_stmt_close($stmt);

            if ($user && password_verify($passwordInput, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['username'] = $user['username'];

                header('Location: manage.php');
                exit;
            }
        }

        $errorMessage = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SwinEduTech</title>
    <?php include __DIR__ . '/header.inc'; ?>
</head>
<body>
    <?php include __DIR__ . '/nav.inc'; ?>

    <main class="container">
        <h1>HR Manager Login</h1>

        <?php if ($errorMessage !== ''): ?>
            <p role="alert"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <form method="post" action="login.php">
            <div>
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?php echo htmlspecialchars($usernameInput, ENT_QUOTES, 'UTF-8'); ?>"
                    required
                    autocomplete="username"
                >
            </div>

            <div>
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit">Log in</button>
        </form>
    </main>

    <?php include __DIR__ . '/footer.inc'; ?>
</body>
</html>