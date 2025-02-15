<?php 
include '../access/header.php';
session_start();
echo json_encode(['status' => $_SESSION['status'] ?? 'false']);