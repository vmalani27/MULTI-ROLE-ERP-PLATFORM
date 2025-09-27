<?php
// api/student/profile.php
require_once __DIR__ . '/../../src/Database/DBConnection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get profile by student_id (from query param)
    $studentId = $_GET['student_id'] ?? null;
    if (!$studentId) {
        http_response_code(400);
        echo json_encode(['error' => 'student_id is required']);
        exit;
    }
    try {
        $db = new DBConnection();
        $pdo = $db->getPDO();
        $stmt = $pdo->prepare('SELECT * FROM students WHERE student_id = ?');
        $stmt->execute([$studentId]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($profile) {
            echo json_encode(['success' => true, 'profile' => $profile]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Profile not found']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update profile fields for student_id
    $data = json_decode(file_get_contents('php://input'), true);
    $studentId = $data['student_id'] ?? null;
    if (!$studentId) {
        http_response_code(400);
        echo json_encode(['error' => 'student_id is required']);
        exit;
    }
    // Only allow updating these fields
    $fields = ['email', 'full_name', 'department', 'admission_year', 'date_of_admission', 'phone', 'parent_name', 'parent_phone'];
    $updates = [];
    $params = [];
    foreach ($fields as $field) {
        if (isset($data[$field])) {
            $updates[] = "$field = ?";
            $params[] = $data[$field];
        }
    }
    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['error' => 'No fields to update']);
        exit;
    }
    $params[] = $studentId;
    $sql = 'UPDATE students SET ' . implode(', ', $updates) . ' WHERE student_id = ?';
    try {
        $db = new DBConnection();
        $pdo = $db->getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Update failed', 'details' => $e->getMessage()]);
    }
    exit;
}

// Method not allowed
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
