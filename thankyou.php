<?php
    include 'database.php'; // Include the database connection details
    session_start();
    //limits access to page
    if (isset($_SESSION['selected_store_id'])) {
        $store_id = $_SESSION['selected_store_id'];
    } else {
        header('Location: checkout.php');
        exit;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>POS Pizza</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">
</head>
<body>
  <div class="navbar">
      <a href="index.php">Home</a>
  </div>

  <form action="" method="post">
      <h2>Thank you, we hope your enjoy your POS Pizza!</h2>

      <a href="menu.php" class="button">Place Another Order</a>

      <a href="logout.php" class="button">Logout</a>
      
  </form> 
</body>
</html>
