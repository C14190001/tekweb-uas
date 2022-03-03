<?php
session_start();
//LogIn Check
if (!isset($_SESSION["UserId"])) {
    echo "<script>alert('Not Logged in'); window.location.href='login.php';</script>";
    exit;
}
//LogOut
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["UserId"])) {
        session_destroy();
        header("location: main.php");
    } else {
        echo "<script>alert('Already Logged Out!'); </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Proyek UAS Online Shop</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function login() {
            window.location.href = 'login.php';
        };

        function cart() {
            window.location.href = 'mycart.php';
        };
        function myorder(){
            window.location.href = 'myorder.php';
        }
    </script>
        <script>
        function del(it) {
            $usr = <?php
                    if (isset($_SESSION["UserId"])) {
                        echo $_SESSION["UserId"];
                    } else {
                        echo "'Nothing'";
                    }
                    ?>;
            if ($usr == "Nothing") {
                alert('Login dulu!');
                window.location.href = 'login.php';
            }
            $.ajax({
                type: "POST",
                url: "del.php",
                data: {
                    item_id: it,
                    del: true
                }
            }).done(function(msg) {
                alert("Item Deleted");
                window.location.href = 'mycart.php';
            });
        };
    </script>
</head>

<body>
    <!--NavBar-->
    <nav class="navbar navbar-light bg-light justify-content-between">
        <a class="navbar-brand" href="main.php">Online Shop | Orders</a>
        <form class="form-inline" method="post">
            <?php
            if (isset($_SESSION["UserId"])) {
                echo "<p>Welcome " . $_SESSION["Name"] . "! ";
                echo "<input type='submit' class='btn btn-primary' value='Logout'>";
            } else {
                echo "<p>Welcome! Please Log in ";
                echo "<input type='button' class='btn btn-primary' onclick='login()' value='Login'>";
            }
            ?>
            <input type='button' class='btn btn-primary' onclick='cart()' value='Cart'></p>
            <input type='button' class='btn btn-primary ml-1' onclick='myorder()' value='Order'></p>
        </form>
    </nav>
    <!-- Items -->
    <div class="container">
        <div class="card-columns">
            <?php
            $u=$_SESSION["UserId"];
            $conn = new mysqli('localhost', 'tekweb', 'tekweb', 'uas');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * from orders WHERE user_id = '$u'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $z=$row["status"];
                    $sql2 = "SELECT * from order_status WHERE order_status_id = '$z'";
                    $result2 = $conn->query($sql2);
                    $row2 = $result2->fetch_assoc();
                    echo "
                    <div class='card text-center' style='width: 20rem; margin-top: 1rem;'>
                        <div class='card-body'>
                            <h5 class='card-title'>Order ID: " . $row["order_id"] . "</h5>
                            <p class='card-text'>Total Price: " . $row["total"] . "</p>
                            <p class='card-text'>Status: " . $row2["status_name"] . "</p>
                        </div>
                    </div>
                    ";
                }
            }
            else{
                echo "<p> No orders</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>

</html>