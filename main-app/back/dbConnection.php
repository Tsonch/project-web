<?php
$dsn = "pgsql:host=db;port=5432;dbname=group-5;user=group-5;password=group-5";  // host=db из Docker
try {
    $pdo = new PDO($dsn);
} catch (PDOException $e) {
    die($e->getMessage());
}

function sendToService($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        error_log("cURL error: " . curl_error($ch));
    }
    curl_close($ch);
    return $response;
}

// logAction: Разделение по сервисам на основе action
function logAction($pdo, $user_id, $action, $details = '') {
    if (empty($user_id)) return;

    $data = [
        'user_id' => $user_id,
        'action' => $action,
        'details' => $details,
        'ip' => $_SERVER['REMOTE_ADDR']
    ];

    if ($action === 'page_visit') {
        // в tracking-service для посещений + stats
        $url = 'http://tracking-service/tracking_service.php';
    } else {
        // Общие логи в log-service
        $url = 'http://log-service/log_service.php';
    }

    $response = sendToService($url, $data);
    error_log("Log attempt: Action=$action, Service=" . parse_url($url, PHP_URL_HOST) . ", Response=" . $response);
}
