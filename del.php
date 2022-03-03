<?php
session_start();
if(isset($_SESSION["UserId"])){
    if($_POST['del']==true){
        $u=$_SESSION["UserId"];
        $i=$_POST["item_id"];

        $conn = new mysqli('localhost', 'tekweb', 'tekweb', 'uas');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        //Delete item.
        $sql = "DELETE FROM carts WHERE user_id = '$u' AND item_id = '$i'";
        $result = $conn->query($sql);

        $conn->close();
        echo $u.$i."<script>alert('Sukses'); window.location.href='main.php';</script>";
    }
    else{
        echo "<script>alert('Akses Ditolak!'); window.location.href='main.php';</script>";
    }                   
}
else{
    echo "<script>alert('Login dulu!'); window.location.href='login.php';</script>";
}