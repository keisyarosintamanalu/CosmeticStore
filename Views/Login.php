<?php
session_start();
include_once "../config/Database.php";

$db = (new Database())->connect();

$error = "";

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $stmt = $db->prepare("SELECT * FROM user WHERE username = :user AND password = :pass");
    $stmt->execute([
        'user' => $username,
        'pass' => $password
    ]);

    if($stmt->rowCount() > 0){
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card p-4 mx-auto" style="max-width:400px;">
        
        <h3 class="text-center mb-3">🔐 Login Admin</h3>

        <?php if($error): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">

            <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>

            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

            <button name="login" class="btn btn-primary w-100">Login</button>

        </form>
    </div>
</div>

</body>
</html>