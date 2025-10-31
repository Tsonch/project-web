<?php
require_once 'dbConnection.php';

// Получение JSON-данных из запроса (POST)
$input = json_decode(file_get_contents('php://input'), true);

if ($input && isset($input['action'])) {
    try {
        $stmt = $pdo->prepare(
            "INSERT INTO user_logs (user_id, action, details, ip_address) 
             VALUES (:user_id, :action, :details, :ip)"
        );
        $stmt->execute([
            ':user_id' => $input['user_id'] ?? null,
            ':action' => $input['action'],
            ':details' => $input['details'] ?? '',
            ':ip' => $input['ip'] ?? $_SERVER['REMOTE_ADDR']
        ]);

        if ($input['action'] === 'page_visit') {
            $page = $input['details'] ?? 'unknown'; 

            $stmt_stats = $pdo->prepare(
                "INSERT INTO page_stats (page, visits) 
                 VALUES (:page, 1) 
                 ON CONFLICT (page) DO UPDATE SET visits = page_stats.visits + 1"
            );
            $stmt_stats->execute([':page' => $page]);
        }

        http_response_code(200);
        echo json_encode(['status' => 'tracked', 'message' => 'Action tracked successfully']);
    } catch (PDOException $e) {
        error_log("Tracking error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid data: Missing action or incorrect format']);
}
?>
