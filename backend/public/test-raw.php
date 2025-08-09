<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $response = [
        'code' => 200,
        'message' => '原始PHP端点测试成功',
        'data' => [
            'method' => $_SERVER['REQUEST_METHOD'],
            'received_data' => $input,
            'time' => date('Y-m-d H:i:s')
        ]
    ];
    
    echo json_encode($response);
} else {
    $response = [
        'code' => 200,
        'message' => '原始PHP端点正常',
        'data' => [
            'method' => $_SERVER['REQUEST_METHOD'],
            'time' => date('Y-m-d H:i:s')
        ]
    ];
    
    echo json_encode($response);
}
?>

