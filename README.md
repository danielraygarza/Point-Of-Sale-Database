University of Houston Database Systems (COSC 3380) with Dr. Uma Ramamurthy

This project is a web-based point-of-sale management system for the University of Houston. A pizza shop was selected to demonstrate the point-of-sale concept. It was developed by Team 13 as part of the COSC 3380 course. 

Web App URL

    https://pospizzawebapp.azurewebsites.net/
    
Team Members

    Daniel Garza
    Ibtisam Amjad
    Bryant Huynh
    David Cooper
    Eric Parsons

The following technologies were used to develop this project:

    HTML/CSS/JavaScript
    PHP
    SQL
    Microsoft Azure
    MySQL Workbench
    GitHub/GitHub Desktop
    Visual Studio Code

Steps to set up a PHP-based Web App:

    1. Create Azure Database for MySQL flexible server
    2. Connect MySQL Workbench to Azure Database
    3. Create Azure Web App
    4. Setup continuous deployment from the Github repository to the Web App
    5. Connect Web App to Azure Database
     Now Workbench is connected to the database, the app is connected to the database, and the repository is connected to the app.

To create an Azure SQL flexible server:

    Log in to the Azure portal (https://portal.azure.com).
    Click on "Create a resource" in the upper left-hand corner of the screen.
    Search for "Azure MySQL" and select "Azure Database for MySQL servers" from the results.
    Click "Create" to begin creating the server.
    Select Flexible Server.
    Fill out the required information, such as server name, admin username and password, and location. This will be needed when connecting to Workbench.
    Choose the appropriate pricing tier and click "Review + create" to review your selections.
    Click "Create" to create the server.
    Once the server is created, click on the "Networking" tab and add your IP address to the firewall rules. You can do this by clicking "+ Add current client IP address".
    You will need to update your IP address anytime you access the database from a new IP.
    Go to databases and select Add. Name the database and select:
        Character Set: utf8mb4
        Collation: utf8mb4_unicode_ci
    Go to "Overview" and take note of your Server Name. You will need this for the next step.
    Now your server is created.

To connect with MySQL workbench:

    Click the + symbol in the MySQL Connections tab to add a new connection.
    Enter a name for the connection in the Connection name field.
    Select Standard (TCP/IP) as the Connection Type.
    Enter {servername}.mysql.database.azure.com in the hostname field.
    Enter {adminuser} as username and then enter your Password.
    Leave port # as 3306
    Go to the SSL tab and update the "Use SSL" field to Require.
    You will need to download the proper certificate authority before continuing: 
        Go to DigiCert's official Root Certificates page: https://www.digicert.com/kb/digicert-root-certificates.htm
        Find the "DigiCert Global Root CA" certificate.
        Download the PEM version.
    In the SSL CA File field, enter the file location of the DigiCertGlobalRootCA.crt.pem file.
    Additionally, place the certificate in your GitHub repository.
    Click Test Connection to test the connection.
    If the connection is successful, click OK to save the connection.
    Your Azure database is now connected to Workbench.

To create an Azure Web App and deploy the project files to it:

    Log in to the Azure portal (https://portal.azure.com/).
    Click on "Create a resource" button in the left menu, then select "Web App".
    "Name" will be used in your URL. ({webappname}.azurewebsites.net)
    Fill in the required information, such as the subscription, resource group, name of the web app, and operating system.
    Under "Publish", select "Code".
    Under "Runtime stack", select PHP (latest version).
    Under the deployment tab, enable continous deployment, and link the GitHub repository you will use for your project. 
    This connects the Web App to the repository. Once the web app is created, you can deploy your project files by uploading to this repository.
    Under the networking tab, ensure "Enable public access" is on.
    Click "Review + create".
    After the deployment is complete, click "Go to resource".
    Under "Overview", locate "Default domain". This is your web app's URL.

To connect Web App to Azure Database:

    In your web app, under "Configuration", select "New application setting" and enter the following individually:
        Name: DB_HOST | Value: your server name
        Name: DB_NAME | Value: admin username
        Name: DB_PASS | Value: admin's password
        Name: DB_NAME | Value: database name you created earlier
    Your Azure database is now connected to the Web App.


The next steps will show how to store user input from your web page into your database. In this example, we will have the user sign up with a username and password and store the data.

In MySQL Workbench, create a table titled "users" by running the following SQL command:

    CREATE TABLE users (
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL    
    );

Create a database.php file in the GitHub repository and paste the following:

    <?php
    $host = "{servername}.mysql.database.azure.com";
    $dbname = "{database name}";
    $username = "{admin user}";
    $password = "{admin user password}";
    $port = 3306;
    $mysqli = mysqli_init();
    mysqli_ssl_set($mysqli, NULL, NULL, "./DigiCertGlobalRootCA.crt.pem", NULL, NULL);
    if (!$mysqli->real_connect($host, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
        die("Connection error: " . $mysqli->connect_error);
    }

Create an index.php file in GitHub repository. This will be the default web page when you deploy your web app. For this example, it will be a basic page to create an account. Paste the following:

    <!DOCTYPE html>
    <head>
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .account-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .account-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .account-input {
            width: 92%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .account-button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }
    </style>
    </head>
    <body>
        <div class="account-container">
            <h2>Create Account</h2>
            <!-- This will run valid_signup.php when you press submit -->
            <form action="valid_signup.php" method="post">
                <input type="text" name="username" placeholder="Username" class="account-input" required>
                <input type="password" name="password" placeholder="Password" class="account-input" required>
                <button type="submit" class="account-button">Sign up now</button>
            </form>
        </div>
    </body>
    </html>


Create a valid_signup.php file in GitHub repository. This is the SQL code that will send the input to your database. Paste the following:

    <?php
        include 'database.php';
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
            // Extracting data from the form
            $username = $mysqli->real_escape_string($_POST['username']);
            $password = password_hash($mysqli->real_escape_string($_POST['password']), PASSWORD_DEFAULT); // Hashing the password before storing it in the database
        
            // Inserting the data into the database
            $sql = "INSERT INTO users (username, password) 
                    VALUES ('$username', '$password')";
        
            if ($mysqli->query($sql) === TRUE) {
                $mysqli->close();
                // If successful, redirect to a specified page
                header('Location: index.php');
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . $mysqli->error;
            }
        
        }
    ?>

Ensure your data was entered into the database. Enter the following command in MySQL Workbench to see your data:

    SELECT * FROM USERS;
   
To give database access to other users:

    Have the user give you their IP address. Under "Networking" in the Azure database, create a new firewall rule name. 
    Enter the IP address under the Start IP address and End IP address.
    From the admin user's account in MySQL Workbench, run the command: CREATE USER 'username'@'%' IDENTIFIED BY 'password'
    Then run the command: GRANT ALL PRIVILEGES ON database_name.* TO 'username'@'%'
    This will create the username and password for the user, and give the user access to view and edit the database in Workbench.
    Now the user will do the following:
        Go to MySQL Workbench and select the + sign.
        Enter a name for the connection in the Connection name field.
        Select Standard (TCP/IP) as the Connection Type.
        Leave port # as 3306
        Enter the server name under hostname: {servername}.mysql.database.azure.com
        Enter the username and password the admin created for the user.
        Test connection and press OK after successfully connecting.
    

 How to run a PHP Web App locally through VS Code on MacOS:

     Download VS extensions: PHP Intelephense by Ben Mewburn and PHP Server by brapifra
     You will need to have PHP installed on your Mac. 
     Open a terminal window in VS Code:
     Check if you have PHP with command "php -v"
     If you do not have PHP, can will need to dowload it using Homebrew.
     Check if you have Homebrew installed with command "brew help"
     
     If you do not have Homebrew, run the following commands:
         1. /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
         2. echo; echo 'eval "$(/opt/homebrew/bin/brew shellenv)"'
         3. /Users/YOURUSERNAME/.zprofile 
             eval "$(/opt/homebrew/bin/brew shellenv)"
     
     If you get this message (zsh: permission denied: /Users/YOURUSERNAME/.zprofile), run the following commands:
         4. touch /Users/YOURUSERNAME/.zprofile
         5. echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> /Users/YOURUSERNAME/.zprofile
         6. source /Users/YOURUSERNAME/.zprofile
         
     Check if Homebrew was installed correctly with command "brew help"
     Now that you have Homebrew, you can download PHP with command "brew install php"
     Check if you have PHP with command "php -v"
     Now close VS Code and start again.

     You now have PHP installed:
     On your PHP files in VS Code, you should see a blue icon in the top right corner. 
     This button will launch a local server and open your web app locally.
     Any changed you make can be seen before pushing to the GitHub repository. 

 To run a PHP Web App locally through VS Code on Windows, refer to this video: 
 
     https://www.youtube.com/watch?v=Ry8tRRfxxf4&ab_channel=BoostMyTool
        
         
         
           
