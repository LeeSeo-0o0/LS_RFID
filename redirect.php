<?php
// 환경 변수에서 데이터베이스 연결 정보 가져오기
$servername = getenv('LS_RFID_DB_SERVERNAME');
$username = getenv('LS_RFID_DB_USERNAME');
$password = getenv('LS_RFID_DB_PASSWORD');
$dbname = getenv('LS_RFID_DB_NAME');

// 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// URL에서 카드 ID 가져오기
$card_id = $_GET['id'];

// 카드 ID로 명함 URL 조회
$sql = "SELECT url FROM cards WHERE card_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $card_id); // 카드 ID를 쿼리에 바인딩
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // 명함 URL로 리디렉션
    $row = $result->fetch_assoc();
    header("Location: " . $row["url"]);
    exit();
} else {
    echo "No URL found for this card.";
}

$stmt->close();
$conn->close();
?>
