<?php
session_start();
if(isset($_SESSION["UserId"])){
    if($_POST['order']==true){
        $u=$_SESSION["UserId"];
        $i=$_POST["total"];

        $conn = new mysqli('localhost', 'tekweb', 'tekweb', 'uas');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $sql = "INSERT INTO orders VALUES (NULL, '$u', '1', '$i');";
        $result = $conn->query($sql);

        $sql = "DELETE FROM carts WHERE user_id = '$u';";
        $result = $conn->query($sql);

        $_POST["order"] = false;
        $conn->close();
        echo "<script>alert('Sukses'); window.location.href='main.php';</script>";
    }
    else{
        echo "<script>alert('Akses Ditolak!'); window.location.href='main.php';</script>";
    }                   
}
else{
    echo "<script>alert('Login dulu!'); window.location.href='login.php';</script>";
}