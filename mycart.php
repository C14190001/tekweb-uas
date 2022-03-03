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

        function myorder() {
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
    <script>
        function order(t) {
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
                url: "orderall.php",
                data: {
                    total: t,
                    order: true
                }
            }).done(function(msg) {
                alert("Sukses");
                window.location.href = 'myorder.php';
            });
        };
    </script>
</head>

<body>
    <!--NavBar-->
    <nav class="navbar navbar-light bg-light justify-content-between">
        <a class="navbar-brand" href="main.php">Online Shop | Cart</a>
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
            $u = $_SESSION["UserId"];
            $conn = new mysqli('localhost', 'tekweb', 'tekweb', 'uas');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * from carts WHERE user_id = '$u'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $total = 0;
                while ($row = $result->fetch_assoc()) {
                    $ix = $row["item_id"];
                    $sql2 = "SELECT * from items WHERE item_id = '$ix'";
                    $result2 = $conn->query($sql2);
                    $row2 = $result2->fetch_assoc();
                    $p = $row["amount"] * $row2["item_price"];
                    $total += $p;
                    echo "
                    <div class='card text-center' style='width: 20rem; margin-top: 1rem;'>
                        <div class='card-body'>
                            <h5 class='card-title'>" . $row["amount"] . "x " . $row2["item_name"] . "</h5>
                            <p class='card-text'>Price: " . $row2["item_price"] . "</p>
                            <p class='card-text'>Total: " . $p . "</p>
                            <button type='button' class='btn btn-primary' onclick='del(" . $row["item_id"] . ")'>Delete</a>
                        </div>
                    </div>
                    ";
                }
                //Taruh di Dibawah
                echo "<nav class='navbar navbar-light bg-light fixed-bottom justify-content-center'>
                        <button type='button' class='btn btn-primary' onclick='order(" . $total . ")'>Order All (".$total.")</a>
                      </nav>";
            } else {
                echo "<p> No items in Cart</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>

</html>