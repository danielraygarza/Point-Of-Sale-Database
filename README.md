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

    Microsoft Azure
    MySQL Workbench
    PHP
    SQL
    HTML/CSS/JavaScript
    GitHub/GitHub Desktop
    Visual Studio Code

File Descriptions:

    checkout.php: displays items in cart select store and order method before finalizing order

    create_menuItem.php: adds a new item to the items page. only accessible by CEO. data inserted into items table

    create_store: creates a new store location. only accessible by CEO. data inserted into pizza_store table

    customer_login: login page for customers

    customize_pizza: displayed after a pizza is added to the cart. allows user to add toppings to pizza

    database.php: gets configuration information from Azure web app to connect to database

    delivery.php: displayed after checkout page when delivery is selected. final page of ordering process. Once order is placed, the following occurs: 
    - data inserted into orders, order_items, delivery, and transactions tables 
    - order is assigned to employee with least current assigned orders at selected store
    - item's inventory is updated at selected store
    - if customer is logged in, total spent and store credit is updated in customers table
    - if customer is guest, data inserted into guest table

    employee_home.php: multi purposed page based on employee's role
    For team members and supervisors, displays orders assigned to them and allows them to mark orders completed
    This will update the employee table by incrementing completed orders, decrementing assigned orders, 
    updating time completed in orders table, and time delivered/picked up in respective delivery/pickup table
    For CEO and managers, displays options to add/update employees, and add inventory, stores, and menu items

    employee_login.php: login page for employees

    employee_register.php: creates a new employee. only accessible by CEO and managers. data inserted into employee table

    export_csv.php: function called to export CSV files from reports page

    generate_report.php: queries for reports. displays selected report from reports.php

    getEmployees.php: function used in reports.php to get employee data

    home.php: home page for customers displayed after login. displays total spent and store credit

    index.php: Default URL page

    inventory.php: this page allows managers and CEO to order inventory for stores. manager can only order for their assigned store. inventory table is updated

    logout.php: function runs when logout is clicked. logs user out and sends to home or employee_home based on user

    menu.php: displays all menu items

    pickup.php: displayed after checkout page when pickup is selected. final page of ordering process. Once order is placed, the following occurs: 
    - data inserted into orders, order_items, pickup, and transactions tables 
    - order is assigned to employee with least current assigned orders at selected store
    - item's inventory is updated at selected store
    - if customer is logged in, total spent and store credit is updated in customers table
    - if customer is guest, data inserted into guest table

    reports.php: allows user to select the type of report to display. page submission runs generate_report.php

    signup.php: sign up page for customers. data inserted into customers page

    thankyou.php: displayed after finalizing order following the delivery/pickup page

    update_customer.php: allows customers to update their information. updates customers table

    update_employee.php: allows the CEO/managers to update employees accounts. managers can only see employees at their store. employee table is updated

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
        Name: DB_USERNAME | Value: admin username
        Name: DB_PASSWORD | Value: admin's password
        Name: DB_NAME | Value: database name you created earlier
    Your Azure database is now connected to the Web App.

Create a database.php file in the GitHub repository and paste the following:

    <?php
        $host = getenv("DB_HOST");
        $username = getenv("DB_USERNAME");
        $password = getenv("DB_PASSWORD");
        $dbname = getenv("DB_NAME");
        $port = 3306;
        $mysqli = mysqli_init();
        mysqli_ssl_set($mysqli, NULL, NULL, "./DigiCertGlobalRootCA.crt.pem", NULL, NULL);
        if (!$mysqli->real_connect($host, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
            die("Connection error: " . $mysqli->connect_error);
        }
           
