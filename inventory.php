<?php
// Start the session at the beginning of the file
include 'database.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Redirects if not manager/CEO or accessed directly via URL
//  if (!isset($_SESSION['user']['Title_Role']) || ($_SESSION['user']['Title_Role'] !== 'CEO' && $_SESSION['user']['Title_Role'] !== 'MAN')) {
//     header("Location: employee_login.php");
//     exit; // Make sure to exit so that the rest of the script won't execute
// }
if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
    // Process AJAX request and return data

    // Make sure to sanitize the input
    $Item_ID = $mysqli->real_escape_string($_POST['Item_ID']);

    // Query to get Days_to_expire based on Item_ID
    $query = "SELECT Days_to_expire FROM items WHERE Item_ID = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Now you can echo the Days_to_expire and it will be sent back as a response to the AJAX call
    echo json_encode($row);
    $mysqli->close();
    exit; // Stop script execution after AJAX request
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form has been submitted

    // Extracting data from the form
    $Store_ID = $mysqli->real_escape_string($_POST['Store_ID']);
    $Item_ID = $mysqli->real_escape_string($_POST['Item_ID']);
    $Inventory_Amount = $mysqli->real_escape_string($_POST['Inventory_Amount']);
    $Vend_ID = $mysqli->real_escape_string($_POST['Vend_ID']);
    $Last_Stock_Shipment_Date = $mysqli->real_escape_string($_POST['Last_Stock_Shipment_Date']);
    $Expiration_Date = $mysqli->real_escape_string($_POST['Expiration_Date']);
    // $Item_Cost = $mysqli->real_escape_string($_POST['Item_Cost']);
    // $Reorder_Threshold = $mysqli->real_escape_string($_POST['Reorder_Threshold']);


    // Inserting the data into the database
    $sql = "INSERT INTO inventory (Store_ID, Item_ID, Inventory_Amount, Vend_ID, Last_Stock_Shipment_Date, Expiration_Date) 
                VALUES ('$Store_ID', '$Item_ID', '$Inventory_Amount', '$Vend_ID','$Last_Stock_Shipment_Date', '$Expiration_Date')";
    if ($mysqli->query($sql) === TRUE) {
        $mysqli->close();
        header('Location: employee_home.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}

?>

<!DOCTYPE html>

<head>
    <title>Inventory</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">
</head>

<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="employee_home.php">Employee Home</a>
        <?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo '<a href="logout.php">Logout</a>';
        }
        ?>
    </div>
    <form action="inventory.php" method="post">
        <h2>Inventory</h2>
        <div>
            <label for="Store_ID">Store Location </label>
            <select id="Store_ID" name="Store_ID" required>
                <option value="" selected disabled>Select Store</option>
                <?php
                $stores = $mysqli->query("SELECT * FROM pizza_store");

                if ($stores->num_rows > 0) {
                    while ($row = $stores->fetch_assoc()) {
                        // if ($row["Pizza_Store_ID"] == 1) { continue; }
                        echo '<option value="' . $row["Pizza_Store_ID"] . '" ' . $selected . '>' . $row["Store_Address"] . ' - ' . $row["Store_City"] . '</option>';
                    }
                }
                ?>
            </select>
        </div><br>

        <div>
            <label for="Item_ID">Item </label>
            <select id="Item_ID" name="Item_ID" required>
                <option value="" selected disabled>Select Item</option>
                <?php
                $items = $mysqli->query("SELECT * FROM items");
                if ($items->num_rows > 0) {
                    while ($row = $items->fetch_assoc()) {
                        echo '<option value="' . $row["Item_ID"] . '" ' . $selected . '>' . $row["Item_Name"] . '</option>';
                    }
                }
                ?>
            </select>
        </div><br>

        <div>
            <label for="Inventory_Amount">Amount Requested </label>
            <input type="number" id="Inventory_Amount" name="Inventory_Amount" min="1" placeholder="Enter amount" style="width: 120px;" required>
        </div><br>

        <div>
            <label for="Vend_ID">Vendor </label>
            <select id="Vend_ID" name="Vend_ID" required>
                <option value="" selected disabled>Select Vendor</option>
                <?php
                $vendors = $mysqli->query("SELECT * FROM vendor");
                if ($vendors->num_rows > 0) {
                    while ($row = $vendors->fetch_assoc()) {
                        echo '<option value="' . $row["Vendor_ID"] . '" ' . $selected . '>' . $row["Vendor_Name"] . '</option>';
                    }
                }
                ?>
            </select>
        </div><br>

        <!-- pulls current date and assigns to Last_Stock_Shipment_Date -->
        <input type="hidden" id="Last_Stock_Shipment_Date" name="Last_Stock_Shipment_Date">
        <input type="hidden" id="Expiration_Date" name="Expiration_Date">
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Your existing script to handle Last_Stock_Shipment_Date
                const currentDate = new Date();
                const formattedDate = `${currentDate.getFullYear()}-${(currentDate.getMonth() + 1).toString().padStart(2, '0')}-${currentDate.getDate().toString().padStart(2, '0')}`;
                document.getElementById('Last_Stock_Shipment_Date').value = formattedDate;

                // Add a listener for when the Item_ID changes
                document.getElementById('Item_ID').addEventListener('change', function() {
                    var itemId = this.value;

                    // Make a POST request to the server
                    fetch(window.location.href, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'ajax=1&Item_ID=' + encodeURIComponent(itemId)
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Assuming 'data' is the response which contains 'Days_to_expire'
                            if (data && data.Days_to_expire) {
                                // Use the 'Days_to_expire' from the response to set the expiration date
                                const daysToExpire = parseInt(data.Days_to_expire, 10);
                                const expirationDate = new Date(currentDate.getTime());
                                expirationDate.setDate(currentDate.getDate() + daysToExpire);

                                // Format the expiration date and set the value
                                const formattedExpirationDate = `${expirationDate.getFullYear()}-${(expirationDate.getMonth() + 1).toString().padStart(2, '0')}-${expirationDate.getDate().toString().padStart(2, '0')}`;
                                document.getElementById('Expiration_Date').value = formattedExpirationDate;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });
        </script>


        <!-- <script>
            document.addEventListener("DOMContentLoaded", function() {
                const currentDate = new Date();
                currentDate.setDate(currentDate.getDate() + 100);
                const formattedDate = `${currentDate.getFullYear()}/${(currentDate.getMonth() + 1).toString().padStart(2, '0')}/${currentDate.getDate().toString().padStart(2, '0')}`;
                document.getElementById('Expiration_Date').value = formattedDate;
            });
        </script> -->

        <?php
        //displays error messages here 
        if (isset($_SESSION['error'])) {
            echo '<div id="errorMessage">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);  // Unset the error message after displaying it
        } else {
            echo "<h2>Good job</h2>";
        }
        ?>

        <div>
            <input class=button type="submit" value="Place Inventory Order">
    </form>
</body>

</html>