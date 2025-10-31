<?php
require_once 'dbConnection.php';  // Копия с PDO

$input = json_decode(file_get_contents('php://input'), true);
if ($input && isset($input['action'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO user_logs (user_id, action, details, ip_address) VALUES (:user_id, :action, :details, :ip)");
        $stmt->execute([
            ':user_id' => $input['user_id'] ?? null,
            ':action' => $input['action'],
            ':details' => $input['details'] ?? '',
            ':ip' => $input['ip'] ?? $_SERVER['REMOTE_ADDR']
        ]);
        http_response_code(200);
        echo json_encode(['status' => 'logged']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
}
?>