<?php
session_start();
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
        function add(it) {
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
                url: "add.php",
                data: {
                    item_id: it,
                    add: true
                },
            }).done(function(msg) {
                alert("Added to cart");
            });
        };
    </script>
</head>

<body>
    <!--NavBar-->
    <nav class="navbar navbar-light bg-light justify-content-between">
        <a class="navbar-brand" href="main.php">Online Shop</a>
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
            $conn = new mysqli('localhost', 'tekweb', 'tekweb', 'uas');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * from items";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                //Yg dipakai Metode 1 sekarang (addtocart mode AJAX).
                //Metode 2: Button Submit diubah jadi form dengan input hidden + sumbit button sbg addtocart
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <div class='card text-center' style='width: 20rem; margin-top: 1rem;'>
                        <img class='card-img-top' src='" . $row["item_img"] . "'>
                        <div class='card-body'>
                            <h5 class='card-title'>" . $row["item_name"] . "</h5>
                            <p class='card-text'>" . $row["item_desc"] . "</p>
                            <p class='card-text'>" . $row["item_price"] . "</p>
                            <button type='button' class='btn btn-primary' onclick='add(" . $row["item_id"] . ")'>Add to Cart</a>
                        </div>
                    </div>
                    ";
                }
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>

</html>