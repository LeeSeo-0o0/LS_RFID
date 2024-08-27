<?php
include('dbconfig.php');

// 데이터베이스 연결 설정
$conn = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// URL에서 card_id 가져오기
$card_id = $_GET['id'];
 
// 데이터베이스에서 명함 정보 조회
$sql = "SELECT name, email, phone, company, title FROM users WHERE card_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $card_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No business card found for this ID.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $user['name']; ?>'s Business Card</title>
</head>
<body>
    <h1><?php echo $user['name']; ?></h1>
    <p>Email: <?php echo $user['email']; ?></p>
    <p>Phone: <?php echo $user['phone']; ?></p>
    <p>Company: <?php echo $user['company']; ?></p>
    <p>Title: <?php echo $user['title']; ?></p>
</body>
</html>
