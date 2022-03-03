<?php
session_start();
//LogIn Check
if (isset($_SESSION["UserId"])) {
    echo "<script>alert('Already Logged in'); window.location.href='main.php';</script>";
    exit;
}
//Log In
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Dapatkan value
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    //MySqli
    $conn = new mysqli('localhost', 'tekweb', 'tekweb', 'uas');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //Password di MD5
    $passmd5 = md5($pass);

    //Cari User
    $sql = "SELECT user_name as name, user_id as id FROM users WHERE user_email = '$email' AND user_pass = '$passmd5'";
    $result = $conn->query($sql);
    $hasil = $result->fetch_assoc();

    if ($result->num_rows != 1) {
        echo "<script>alert('Email atau Password Salah!');</script>";
    } else {
        //Simpan ke Sesi dan Lanjut...
        $_SESSION["UserId"] = $hasil['id'];
        $_SESSION["Name"] = $hasil['name'];
        $conn->close();
        echo "<script>alert('Login Success'); window.location.href='main.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=deivce-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function regis() {
            window.location.href = 'register.php';
        };
        function back() {
            window.location.href = 'main.php';
        };
    </script>
</head>

<body>
    <div class="container">
        <div class="card text-center">
            <form method="post">
                <div><h5>Online Shop Login</h5></div>
                <div class="form-group">
                    <input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required name="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="password" required name="pass" placeholder="Password">
                </div>
                <div>
                <input type="submit" class='btn btn-primary' value="Login" />
                </div>
                <div>
                <input type='button' class='btn btn-primary my-2' onclick='regis()' value='Register'></p>
                </div>
                <div>
                <input type='button' class='btn btn-primary my-3' onclick='back()' value='Go Back'></p>
                </div>
            </form>
        </div>
    </div>
</body>

</html>