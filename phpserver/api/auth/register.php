
<?php
// api/auth/register.php
// CORS headers
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
require_once __DIR__ . '/../../src/Auth/AuthHelper.php';
require_once __DIR__ . '/../../src/Database/DBConnection.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
// Log the received data for debugging
error_log(json_encode($data));

// Basic validation

$required = ['user_id', 'email', 'password', 'full_name'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(['error' => "$field is required"]);
        exit;
    }
}

$userId = $data['user_id'];
$email = $data['email'];
$password = $data['password'];
$fullName = $data['full_name'];
$role = $data['role'] ?? 'student';

// Hash password
$hash = AuthHelper::hashPassword($password);


try {
    $db = new DBConnection();
    $pdo = $db->getPDO();
    if ($role === 'admin') {
        // Check if an admin already exists
        $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
        $check->execute();
        $adminCount = $check->fetchColumn();
        if ($adminCount > 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Admin account already exists']);
            exit;
        }
    }
    $stmt = $pdo->prepare("INSERT INTO users (user_id, email, password_hash, full_name, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $email, $hash, $fullName, $role]);
    http_response_code(201);
    echo json_encode(['success' => true, 'user_id' => $userId]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Registration failed', 'details' => $e->getMessage()]);
}



