<?php
require_once __DIR__ . '/session.php';

header('Cache-Control: no-store');

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}