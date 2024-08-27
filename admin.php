<?php
session_start();
include('dbconfig.php');

// 데이터베이스 연결 설정
$conn = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 관리자 로그인 처리
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $username;
            header("Location: admin.php");
            exit();
        } else {
            $error = "잘못된 비밀번호입니다.";
        }
    } else {
        $error = "해당 사용자가 존재하지 않습니다.";
    }
}

// 로그인된 상태인지 확인
if (!isset($_SESSION['admin'])) {
    echo "<h2>관리자 로그인</h2>";
    if (isset($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    echo "<form method='post' action='admin.php'>";
    echo "사용자 이름: <input type='text' name='username' required><br>";
    echo "비밀번호: <input type='password' name='password' required><br><br>";
    echo "<input type='submit' name='login' value='로그인'>";
    echo "</form>";
    exit();
}

// 사용자 수정 처리
if (isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $company = $_POST['company'];
    $title = $_POST['title'];

    $sql = "UPDATE users SET name = ?, email = ?, phone = ?, company = ?, title = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $email, $phone, $company, $title, $id);

    if ($stmt->execute()) {
        $message = "사용자 정보가 성공적으로 수정되었습니다.";
    } else {
        $message = "오류가 발생했습니다: " . $stmt->error;
    }

    $stmt->close();
}

// 사용자가 선택한 ID로 사용자 정보 조회
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_user = $result->fetch_assoc();

    $stmt->close();
}

// 메시지 출력
if (isset($message)) {
    echo "<p>$message</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>관리자 패널</title>
</head>
<body>
    <h2>관리자 패널</h2>

    <?php if (isset($edit_user)) : ?>
    <h3>사용자 정보 수정</h3>
    <form method="post" action="admin.php">
        <input type="hidden" name="user_id" value="<?php echo $edit_user['id']; ?>">
        이름: <input type="text" name="name" value="<?php echo $edit_user['name']; ?>" required><br>
        이메일: <input type="email" name="email" value="<?php echo $edit_user['email']; ?>" required><br>
        전화번호: <input type="text" name="phone" value="<?php echo $edit_user['phone']; ?>"><br>
        회사명: <input type="text" name="company" value="<?php echo $edit_user['company']; ?>"><br>
        직책: <input type="text" name="title" value="<?php echo $edit_user['title']; ?>"><br><br>
        <input type="submit" name="update_user" value="수정">
    </form>
    <?php endif; ?>

    <h3>기존 사용자 목록</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>이름</th>
            <th>이메일</th>
            <th>전화번호</th>
            <th>회사명</th>
            <th>직책</th>
            <th>수정</th>
        </tr>
        <?php
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['company'] . "</td>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td><a href='admin.php?edit_id=" . $row['id'] . "'>수정</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
