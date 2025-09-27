<?php
// api/auth/me.php
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], ['http://localhost:3000', 'http://localhost:3001'])) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
}
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
header('Content-Type: application/json');

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    echo json_encode([
        'success' => true,
        'user_id' => $_SESSION['user_id'],
        'role' => $_SESSION['role']
    ]);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
}
