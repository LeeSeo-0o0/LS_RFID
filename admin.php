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
    echo "<div class='container'>";
    echo "<h2 class='mt-5'>관리자 로그인</h2>";
    if (isset($error)) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
    echo "<form method='post' action='admin.php'>";
    echo "<div class='mb-3'><label class='form-label'>사용자 이름:</label>";
    echo "<input type='text' name='username' class='form-control' required></div>";
    echo "<div class='mb-3'><label class='form-label'>비밀번호:</label>";
    echo "<input type='password' name='password' class='form-control' required></div>";
    echo "<button type='submit' name='login' class='btn btn-primary'>로그인</button>";
    echo "</form></div>";
    exit();
}

// 사용자 추가 처리
if (isset($_POST['create_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $company = $_POST['company'];
    $title = $_POST['title'];
    $card_id = $_POST['card_id'];

    $sql = "INSERT INTO users (name, email, phone, company, title, card_id) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $email, $phone, $company, $title, $card_id);

    if ($stmt->execute()) {
        $message = "사용자가 성공적으로 추가되었습니다.";
    } else {
        $message = "오류가 발생했습니다: " . $stmt->error;
    }

    $stmt->close();
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
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 패널</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">관리자 패널</h2>

        <?php if (isset($message)) : ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (isset($edit_user)) : ?>
        <h3 class="mt-4">사용자 정보 수정</h3>
        <form method="post" action="admin.php">
            <input type="hidden" name="user_id" value="<?php echo $edit_user['id']; ?>">
            <div class="mb-3">
                <label class="form-label">이름:</label>
                <input type="text" name="name" class="form-control" value="<?php echo $edit_user['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">이메일:</label>
                <input type="email" name="email" class="form-control" value="<?php echo $edit_user['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">전화번호:</label>
                <input type="text" name="phone" class="form-control" value="<?php echo $edit_user['phone']; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">회사명:</label>
                <input type="text" name="company" class="form-control" value="<?php echo $edit_user['company']; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">직책:</label>
                <input type="text" name="title" class="form-control" value="<?php echo $edit_user['title']; ?>">
            </div>
            <button type="submit" name="update_user" class="btn btn-success">수정</button>
        </form>
        <?php else : ?>
        <h3 class="mt-4">새 사용자 추가</h3>
        <form method="post" action="admin.php">
            <div class="mb-3">
                <label class="form-label">이름:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">이메일:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">전화번호:</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">회사명:</label>
                <input type="text" name="company" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">직책:</label>
                <input type="text" name="title" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">카드 ID:</label>
                <input type="text" name="card_id" class="form-control" required>
            </div>
            <button type="submit" name="create_user" class="btn btn-primary">추가</button>
        </form>
        <?php endif; ?>

        <h3 class="mt-5">기존 사용자 목록</h3>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>이름</th>
                    <th>이메일</th>
                    <th>전화번호</th>
                    <th>회사명</th>
                    <th>직책</th>
                    <th>수정</th>
                </tr>
            </thead>
            <tbody>
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
                    echo "<td><a href='admin.php?edit_id=" . $row['id'] . "' class='btn btn-warning btn-sm'>수정</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
