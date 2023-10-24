-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: pospizza.mysql.database.azure.com    Database: pos
-- ------------------------------------------------------
-- Server version	8.0.32

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `catering`
--

DROP TABLE IF EXISTS `catering`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catering` (
  `CA_Order_ID` int unsigned NOT NULL DEFAULT '0',
  `CA_Date` date NOT NULL DEFAULT '2020-10-12',
  `CA_Time_Processed` time NOT NULL DEFAULT '00:00:00',
  `CA_Street_Number` int unsigned NOT NULL DEFAULT '0',
  `CA_Street_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `CA_City` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `CA_State` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `CA_Zip_code` int unsigned NOT NULL DEFAULT '1',
  `CA_Apt_Num` int unsigned DEFAULT NULL,
  `Time_Catered` time NOT NULL DEFAULT '00:00:00',
  `employee` int unsigned DEFAULT NULL,
  PRIMARY KEY (`CA_Order_ID`),
  KEY `catering_to_employee` (`employee`),
  CONSTRAINT `catering_to_employee` FOREIGN KEY (`employee`) REFERENCES `employee` (`Employee_ID`),
  CONSTRAINT `ref_orders_c` FOREIGN KEY (`CA_Order_ID`) REFERENCES `orders` (`Order_ID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catering`
--

LOCK TABLES `catering` WRITE;
/*!40000 ALTER TABLE `catering` DISABLE KEYS */;
/*!40000 ALTER TABLE `catering` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `customer_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_initial` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `birthday` date NOT NULL DEFAULT '0001-01-01',
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `address2` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_spent_toDate` decimal(7,2) DEFAULT '0.00',
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delivery`
--

DROP TABLE IF EXISTS `delivery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `delivery` (
  `D_Order_ID` int unsigned NOT NULL DEFAULT '0',
  `D_Date` date NOT NULL DEFAULT '2020-10-12',
  `D_Time_Processed` time NOT NULL DEFAULT '00:00:00',
  `D_Street_Number` int unsigned NOT NULL DEFAULT '0',
  `D_Street_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `D_City` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `D_State` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `D_Zip_code` int unsigned NOT NULL DEFAULT '1',
  `D_Apt_Num` int unsigned DEFAULT NULL,
  `Estimated_Delivery_Time` time NOT NULL DEFAULT '00:00:00',
  `Time_Delivered` time NOT NULL DEFAULT '00:00:00',
  `Delivery_Status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `employee` int unsigned DEFAULT NULL,
  PRIMARY KEY (`D_Order_ID`),
  KEY `delivery_to_employee` (`employee`),
  CONSTRAINT `delivery_to_employee` FOREIGN KEY (`employee`) REFERENCES `employee` (`Employee_ID`),
  CONSTRAINT `ref_orders_d` FOREIGN KEY (`D_Order_ID`) REFERENCES `orders` (`Order_ID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery`
--

LOCK TABLES `delivery` WRITE;
/*!40000 ALTER TABLE `delivery` DISABLE KEYS */;
/*!40000 ALTER TABLE `delivery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee` (
  `E_Last_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `E_First_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `Employee_ID` int unsigned NOT NULL DEFAULT '0',
  `Title_Role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `Supervisor_ID` int unsigned NOT NULL DEFAULT '0',
  `Hire_Date` date NOT NULL DEFAULT '2020-10-12',
  `Shift_Start` time NOT NULL DEFAULT '00:00:00',
  `Shift_End` time NOT NULL DEFAULT '00:00:00',
  PRIMARY KEY (`Employee_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guest`
--

DROP TABLE IF EXISTS `guest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guest` (
  `Guest_ID` int unsigned NOT NULL DEFAULT '0',
  `G_First_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `G_Last_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `G_Middle_Init` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `G_Email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `G_Phone_Number` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Guest_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guest`
--

LOCK TABLES `guest` WRITE;
/*!40000 ALTER TABLE `guest` DISABLE KEYS */;
/*!40000 ALTER TABLE `guest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory` (
  `Inventory_ID` int unsigned NOT NULL DEFAULT '0',
  `Inventory_Amount` int unsigned NOT NULL DEFAULT '0',
  `Reorder_Threshold` int unsigned NOT NULL DEFAULT '0',
  `Last_Stock_Shipment_Date` date NOT NULL DEFAULT '2020-10-12',
  `Expiration_Date` date NOT NULL DEFAULT ((`Last_Stock_Shipment_Date` + 1)),
  PRIMARY KEY (`Inventory_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `Menu_ID` int unsigned NOT NULL DEFAULT '0',
  `Last_Updated` date NOT NULL DEFAULT '2020-10-12',
  `Menu_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  PRIMARY KEY (`Menu_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `Order_Item_ID` int unsigned NOT NULL DEFAULT '0',
  `I_Pizza_ID` int unsigned NOT NULL DEFAULT '0',
  `Estimated_Time_Ready` time NOT NULL DEFAULT '00:00:00',
  `I_Topping_On_Pizza_ID` int unsigned NOT NULL DEFAULT '0',
  `Price` decimal(4,2) NOT NULL DEFAULT '0.00',
  `I_Order_ID` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Order_Item_ID`),
  KEY `ref_orders` (`I_Order_ID`),
  CONSTRAINT `ref_orders` FOREIGN KEY (`I_Order_ID`) REFERENCES `orders` (`Order_ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `Order_ID` int unsigned NOT NULL DEFAULT '0',
  `Date_Of_Order` date NOT NULL DEFAULT '2020-10-12',
  `Time_Of_Order` time NOT NULL DEFAULT '00:00:00',
  `Total_Estimated_Time_Ready` time NOT NULL DEFAULT '00:00:00',
  `Order_Type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `Order_Status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `Total_Amount` decimal(5,2) NOT NULL DEFAULT '0.00',
  `ID_Of_Cashier` int unsigned NOT NULL DEFAULT '0',
  `ID_Of_Cook` int unsigned NOT NULL DEFAULT '0',
  `O_Customer_ID` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`Order_ID`),
  KEY `ref_customers` (`O_Customer_ID`),
  CONSTRAINT `ref_customers` FOREIGN KEY (`O_Customer_ID`) REFERENCES `customers` (`customer_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pickup`
--

DROP TABLE IF EXISTS `pickup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pickup` (
  `PU_Order_ID` int unsigned NOT NULL DEFAULT '0',
  `PU_Date` date NOT NULL DEFAULT '2020-10-12',
  `PU_Time_Processed` time NOT NULL DEFAULT '00:00:00',
  `PU_Time_Picked_Up` time NOT NULL DEFAULT '00:00:00',
  `employee` int unsigned DEFAULT NULL,
  PRIMARY KEY (`PU_Order_ID`),
  KEY `pickup_to_employee` (`employee`),
  CONSTRAINT `pickup_to_employee` FOREIGN KEY (`employee`) REFERENCES `employee` (`Employee_ID`),
  CONSTRAINT `ref_orders_p` FOREIGN KEY (`PU_Order_ID`) REFERENCES `orders` (`Order_ID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pickup`
--

LOCK TABLES `pickup` WRITE;
/*!40000 ALTER TABLE `pickup` DISABLE KEYS */;
/*!40000 ALTER TABLE `pickup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pizza`
--

DROP TABLE IF EXISTS `pizza`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pizza` (
  `Pizza_ID` int unsigned NOT NULL DEFAULT '0',
  `P_Menu_ID` int unsigned NOT NULL DEFAULT '0',
  `Calories` int unsigned NOT NULL DEFAULT '0',
  `Size_Options` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Cost` decimal(4,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Pizza_ID`,`P_Menu_ID`),
  KEY `menu_item_num` (`P_Menu_ID`),
  CONSTRAINT `menu_item_num` FOREIGN KEY (`P_Menu_ID`) REFERENCES `menu` (`Menu_ID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pizza`
--

LOCK TABLES `pizza` WRITE;
/*!40000 ALTER TABLE `pizza` DISABLE KEYS */;
/*!40000 ALTER TABLE `pizza` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pizza_shop`
--

DROP TABLE IF EXISTS `pizza_shop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pizza_shop` (
  `Pizza_Shop_ID` int unsigned NOT NULL DEFAULT '0',
  `Number_of_Orders_Daily` int unsigned NOT NULL DEFAULT '0',
  `Number_of_Pizzas_Sold_Daily` int unsigned NOT NULL DEFAULT '0',
  `Number_of_Customers_Daily` int unsigned NOT NULL DEFAULT '0',
  `Most_Popular_Pizza` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `Daily_Profit` decimal(7,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Pizza_Shop_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pizza_shop`
--

LOCK TABLES `pizza_shop` WRITE;
/*!40000 ALTER TABLE `pizza_shop` DISABLE KEYS */;
/*!40000 ALTER TABLE `pizza_shop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe`
--

DROP TABLE IF EXISTS `recipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipe` (
  `R_Pizza_ID` int unsigned NOT NULL DEFAULT '0',
  `R_Inventory_ID` int unsigned NOT NULL DEFAULT '0',
  `Amount` int unsigned NOT NULL DEFAULT '0',
  `Ingredient_Name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Recipe_ID` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Recipe_ID`),
  KEY `ref_pizza` (`R_Pizza_ID`),
  KEY `ref_inventory` (`R_Inventory_ID`),
  CONSTRAINT `ref_inventory` FOREIGN KEY (`R_Inventory_ID`) REFERENCES `inventory` (`Inventory_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ref_pizza` FOREIGN KEY (`R_Pizza_ID`) REFERENCES `pizza` (`Pizza_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe`
--

LOCK TABLES `recipe` WRITE;
/*!40000 ALTER TABLE `recipe` DISABLE KEYS */;
/*!40000 ALTER TABLE `recipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topping_on_pizza`
--

DROP TABLE IF EXISTS `topping_on_pizza`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `topping_on_pizza` (
  `Topping_On_Pizza_ID` int unsigned NOT NULL DEFAULT '0',
  `P_Order_Item_ID` int unsigned NOT NULL DEFAULT '0',
  `P_Inventory_ID` int unsigned NOT NULL DEFAULT '0',
  `Amount` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Topping_On_Pizza_ID`),
  KEY `ref_order_item` (`P_Order_Item_ID`),
  CONSTRAINT `ref_order_item` FOREIGN KEY (`P_Order_Item_ID`) REFERENCES `order_items` (`Order_Item_ID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topping_on_pizza`
--

LOCK TABLES `topping_on_pizza` WRITE;
/*!40000 ALTER TABLE `topping_on_pizza` DISABLE KEYS */;
/*!40000 ALTER TABLE `topping_on_pizza` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `T_Order_ID` int unsigned NOT NULL DEFAULT '0',
  `Total_Amount_Charged` decimal(5,2) NOT NULL DEFAULT '0.00',
  `Tax` decimal(4,2) NOT NULL DEFAULT '0.00',
  `Amount_Tipped` decimal(4,2) NOT NULL DEFAULT '0.00',
  `Payment_Method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `T_Date` date NOT NULL DEFAULT '2020-10-12',
  `Time_Processed` time NOT NULL DEFAULT '00:00:00',
  PRIMARY KEY (`T_Order_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor`
--

DROP TABLE IF EXISTS `vendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendor` (
  `Vendor_ID` int unsigned NOT NULL DEFAULT '0',
  `Vendor_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `Inventory_ID` int unsigned NOT NULL DEFAULT '0',
  `Cost` decimal(4,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Vendor_ID`),
  KEY `item_purchased` (`Inventory_ID`),
  CONSTRAINT `item_purchased` FOREIGN KEY (`Inventory_ID`) REFERENCES `inventory` (`Inventory_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor`
--

LOCK TABLES `vendor` WRITE;
/*!40000 ALTER TABLE `vendor` DISABLE KEYS */;
/*!40000 ALTER TABLE `vendor` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-10-16 21:50:20
