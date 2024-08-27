<?php
include('dbconfig.php');

// 데이터베이스 연결 설정
$conn = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 관리자 로그인 처리
session_start();

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
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No such admin user found.";
    }
}

if (!isset($_SESSION['admin'])) {
    echo "<h2>Admin Login</h2>";
    echo "<form method='post' action='admin.php'>";
    echo "Username: <input type='text' name='username' required><br>";
    echo "Password: <input type='password' name='password' required><br><br>";
    echo "<input type='submit' name='login' value='Login'>";
    echo "</form>";
    exit();
}

// 공카드 유저 관리
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
    $stmt->execute();

    echo "User created successfully.";
}

if (isset($_POST['delete_user'])) {
    $card_id = $_POST['card_id'];

    $sql = "DELETE FROM users WHERE card_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $card_id);
    $stmt->execute();

    echo "User deleted successfully.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
    <h2>Admin Panel</h2>

    <h3>Create a New User</h3>
    <form method="post" action="admin.php">
        Name: <input type="text" name="name" required><br>
        Email: <input type="email" name="email" required><br>
        Phone: <input type="text" name="phone"><br>
        Company: <input type="text" name="company"><br>
        Title: <input type="text" name="title"><br>
        Card ID: <input type="text" name="card_id" required><br><br>
        <input type="submit" name="create_user" value="Create User">
    </form>

    <h3>Delete an Existing User</h3>
    <form method="post" action="admin.php">
        Card ID: <input type="text" name="card_id" required><br><br>
        <input type="submit" name="delete_user" value="Delete User">
    </form>

    <h3>Existing Users</h3>
    <table border="1">
        <tr>
            <th>Card ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Company</th>
            <th>Title</th>
        </tr>
        <?php
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['card_id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['company'] . "</td>";
            echo "<td>" . $row['title'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>
