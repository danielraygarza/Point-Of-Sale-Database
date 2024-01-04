To set up the project, refer to this Notion: https://massive-nitrogen-4fb.notion.site/COSC-3380-Database-Project-1c6861ef7d9345389f08d57cc7f18fff?pvs=4

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
    6. Optionally, set up local server
     Now Workbench is connected to the database, the app is connected to the database, and the repository is connected to the app.
           
