<?php
session_start();
if(isset($_SESSION["UserId"])){
    if($_POST['add']==true){
        $u=$_SESSION["UserId"];
        $i=$_POST["item_id"];

        $conn = new mysqli('localhost', 'tekweb', 'tekweb', 'uas');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        //Cari item di mycart, jika ada quantity + 1, jika belum tambah data.
        $sql = "SELECT * FROM carts WHERE user_id = '$u' AND item_id = '$i'";
        $result = $conn->query($sql);
        
        if ($result->num_rows == 0) {
            //jika belum tambah data
            $sql="SELECT item_price from items where item_id = '$i'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $price = $row["item_price"];

            $sql = "INSERT INTO carts VALUES (NULL, '$u', '$i', '1', '$price');";
            $result = $conn->query($sql);
        }
        else{
            //jika ada quantity + 1
            $sql = "SELECT item_price from items where item_id = (select item_id FROM carts WHERE user_id = '$u' AND item_id = '$i')";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $price = $row["item_price"];
            $sql = "UPDATE carts SET amount = amount + 1 WHERE user_id = '$u' AND item_id = '$i'";
            $result = $conn->query($sql);
            $sql = "UPDATE carts SET total = '$price' * amount WHERE user_id = '$u' AND item_id = '$i'";
            $result = $conn->query($sql);
        }
        $_POST["add"] = false;
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