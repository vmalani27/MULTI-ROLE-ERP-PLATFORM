session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized: Please log in.']);
    exit();
}

<?php
// api/student/admissions.php
require_once __DIR__ . '/../../src/Database/DBConnection.php';
// CORS headers
header('Access-Control-Allow-Origin: http://localhost:3001');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$required = ['student_id', 'department', 'admission_year', 'phone', 'parent_name', 'parent_phone'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "$field is required"]);
        exit;
    }
}


$studentId = $data['student_id'];
$department = $data['department'];
$admissionYear = $data['admission_year'];
$dateOfAdmission = $data['date_of_admission'] ?? date('Y-m-d');
$phone = $data['phone'];
$parentName = $data['parent_name'];
$parentPhone = $data['parent_phone'];

try {
    $db = new DBConnection();

    <?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized: Please log in.']);
        exit();
    }
    require_once __DIR__ . '/../../src/Database/DBConnection.php';
    // CORS headers
    header('Access-Control-Allow-Origin: http://localhost:3001');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
    header('Content-Type: application/json');

    $db = new DBConnection();
    $pdo = $db->getPDO();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // List all admissions (pending, approved, rejected)
        $stmt = $pdo->query("SELECT * FROM admissions ORDER BY created_at DESC");
        $admissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'admissions' => $admissions]);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $required = ['user_id', 'department', 'admission_year', 'phone', 'parent_name', 'parent_phone'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "$field is required"]);
                exit;
            }
        }
        $userId = $data['user_id'];
        $department = $data['department'];
        $admissionYear = $data['admission_year'];
        $dateOfAdmission = $data['date_of_admission'] ?? date('Y-m-d');
        $phone = $data['phone'];
        $parentName = $data['parent_name'];
        $parentPhone = $data['parent_phone'];
        $status = 'pending';
        try {
            $stmt = $pdo->prepare("INSERT INTO admissions (user_id, department, admission_year, date_of_admission, phone, parent_name, parent_phone, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $department, $admissionYear, $dateOfAdmission, $phone, $parentName, $parentPhone, $status]);
            http_response_code(201);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Profile creation failed', 'details' => $e->getMessage()]);
        }
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['user_id']) || empty($data['status'])) {
            http_response_code(400);
            echo json_encode(['error' => 'user_id and status are required']);
            exit();
        }
        $userId = $data['user_id'];
        $status = $data['status'];
        try {
            $stmt = $pdo->prepare("UPDATE admissions SET status = ? WHERE user_id = ?");
            $stmt->execute([$status, $userId]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Status update failed', 'details' => $e->getMessage()]);
        }
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['user_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'user_id is required']);
            exit();
        }
        $userId = $data['user_id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM admissions WHERE user_id = ?");
            $stmt->execute([$userId]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Delete failed', 'details' => $e->getMessage()]);
        }
        exit();
    }

    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
