<?php
include '../access/header.php';
session_start();
session_unset();
session_destroy();
echo json_encode(['status' => 'true', 'message' => 'admin access revoked']);