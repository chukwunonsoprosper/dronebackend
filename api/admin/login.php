<?php
include '../access/header.php';
$passkey = $_POST['credentials'] ?? '';
if ($passkey == 'benindronelab@auth229') {
    session_start();
    $_SESSION['admin'] = 'drone.bj';
    $_SESSION['status'] = 'true';
    setcookie(session_name(), session_id(), time() + (86400 * 30), "/");
    echo json_encode(['status' => 'true', 'message' => 'admin access granted']);
} else {
    echo json_encode(['status' => 'false', 'message' => 'access denied']);
}