<?php
include('dbconfig.php');

// 데이터베이스 연결 설정
$conn = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// URL에서 card_id 가져오기
$card_id = $_GET['id'];

// 데이터베이스에서 해당 card_id의 URL 조회
$sql = "SELECT card_id FROM users WHERE card_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $card_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // card_id가 존재하면, 해당 명함 페이지로 리디렉션
    header("Location: card.php?id=" . $card_id);
    exit();
} else {
    // card_id가 존재하지 않으면 에러 메시지 출력
    echo "No business card found for this ID.";
}

$stmt->close();
$conn->close();
?>
