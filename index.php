<?php
// dbconfig.php 파일이 존재하는지 확인
if (!file_exists('dbconfig.php')) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 입력받은 데이터베이스 정보 및 관리자 계정 정보 처리
        $servername = $_POST['servername'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $dbname = $_POST['dbname'];
        $admin_username = $_POST['admin_username'];
        $admin_password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);

        // dbconfig.php 파일 생성
        $config_content = "<?php\n";
        $config_content .= "define('DB_SERVERNAME', '$servername');\n";
        $config_content .= "define('DB_USERNAME', '$username');\n";
        $config_content .= "define('DB_PASSWORD', '$password');\n";
        $config_content .= "define('DB_NAME', '$dbname');\n";
        $config_content .= "?>";

        file_put_contents('dbconfig.php', $config_content);

        // 데이터베이스 연결 및 관리자 테이블 생성
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // 관리자 테이블 생성
        $sql = "CREATE TABLE IF NOT EXISTS admins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )";
        $conn->query($sql);

        // 관리자 계정 생성
        $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $admin_username, $admin_password);
        $stmt->execute();

        $stmt->close();
        $conn->close();

        echo "Database configuration saved and admin account created successfully. Please <a href='admin.php'>login</a>.";
        exit();
    }
} else {
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Setup Database Configuration</title>
</head>
<body>
    <h2>Setup Database and Admin Account</h2>
    <form method="post" action="index.php">
        <h3>Database Information</h3>
        Servername: <input type="text" name="servername" required><br>
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        Database Name: <input type="text" name="dbname" required><br>
        
        <h3>Admin Account</h3>
        Admin Username: <input type="text" name="admin_username" required><br>
        Admin Password: <input type="password" name="admin_password" required><br><br>
        
        <input type="submit" value="Save Configuration">
    </form>
</body>
</html>
