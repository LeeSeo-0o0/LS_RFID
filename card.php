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
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $user['name']; ?>의 디지털 명함</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body text-center">
                <h2 class="card-title"><?php echo $user['name']; ?></h2>
                <p class="card-text"><?php echo $user['title']; ?> @ <?php echo $user['company']; ?></p>
                <p class="card-text"><a href="mailto:<?php echo $user['email']; ?>"><?php echo $user['email']; ?></a></p>
                <p class="card-text">📞 <a href="tel:<?php echo $user['phone']; ?>"><?php echo $user['phone']; ?></a></p>
                <div class="mt-4">
                    <a href="tel:<?php echo $user['phone']; ?>" class="btn btn-primary">통화하기</a>
                    <a href="#" onclick="saveContact('<?php echo $user['name']; ?>', '<?php echo $user['phone']; ?>')" class="btn btn-secondary">저장하기</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    function saveContact(name, phone) {
        // vCard 데이터 생성
        let vCardData = `BEGIN:VCARD
VERSION:3.0
FN:${name}
TEL:${phone}
END:VCARD`;

        // Blob을 생성하고 다운로드 링크를 만들기
        let blob = new Blob([vCardData], { type: 'text/vcard' });
        let url = window.URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = `${name}.vcf`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
