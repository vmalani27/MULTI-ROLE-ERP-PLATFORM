
<?php
// api/auth/login.php
// Set secure session cookie params and start session
session_set_cookie_params([
    'httponly' => true,
    'secure' => false, // set true if using HTTPS
    'samesite' => 'Lax',
]);
session_start();
require_once __DIR__ . '/../../src/Auth/AuthHelper.php';
require_once __DIR__ . '/../../src/Database/DBConnection.php';
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

$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Email and password required']);
    exit;
}

try {
    $db = new DBConnection();
    $pdo = $db->getPDO();
    $stmt = $pdo->prepare("SELECT user_id, password_hash, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user || !AuthHelper::verifyPassword($password, $user['password_hash'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
        exit;
    }
    AuthHelper::setSecureSession($user['user_id'], $user['role']);
    // Optionally: AuthHelper::setRememberMeCookie($user['user_id']);
    echo json_encode(['success' => true, 'role' => $user['role'], 'user_id' => $user['user_id']]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Login failed', 'details' => $e->getMessage()]);
}
