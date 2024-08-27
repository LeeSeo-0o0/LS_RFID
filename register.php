<?php
include('dbconfig.php');

// 데이터베이스 연결 설정
$conn = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 사용자 등록 처리
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $company = $_POST['company'];
    $title = $_POST['title'];
    $card_id = $_POST['card_id'];

    // 사용자 정보 삽입
    $sql = "INSERT INTO users (name, email, phone, company, title, card_id) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $email, $phone, $company, $title, $card_id);

    if ($stmt->execute()) {
        echo "User registered successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>
    <h2>Register a New Business Card</h2>
    <form method="post" action="register.php">
        Name: <input type="text" name="name" required><br>
        Email: <input type="email" name="email" required><br>
        Phone: <input type="text" name="phone"><br>
        Company: <input type="text" name="company"><br>
        Title: <input type="text" name="title"><br>
        Card ID: <input type="text" name="card_id" required><br><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>
