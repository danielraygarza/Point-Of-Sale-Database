<?php
    include 'database.php';
    session_start();

    $sql = "SELECT * FROM pizza;";
    $result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>POS Pizza</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="menu.php">Order now</a>
        <!-- <a href="#">Profile</a> -->
        <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo '<a href="logout.php">Logout</a>';
            }
        ?>
    </div>

    <?php 
        while($row = mysqli_fetch_assoc($result)) {
    ?>

        <main>
            <div class = "card">
                <div class = "image">
                    <img src= <?php echo $row["Image_Path"]; ?> alt="">
                </div>
                <p class = "pizza_name"><?php echo $row["Name"]; ?></p>
                <p class = "description"><?php echo $row["Description"]; ?></p>
                <p class = "calories"><?php echo $row["Calories"]; ?> cals</p>
                <p class = "price"><b>$<?php echo $row["Cost"]; ?></b></p>
                <div class = "add-to-cart">ADD TO ORDER</div>
            </div>
        </main>
    
    <?php } ?>

    <form action="" method="post">
            <h2>Menu</h2>

            
    </form>

</body>
</html>