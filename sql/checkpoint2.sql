-- MySQL dump 10.13  Distrib 8.0.34, for macos13 (arm64)
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'dan','ga','','2023-10-17','1999-08-18','23 st','','houst','TX','12312','1231231230','daniel@email.com','$2y$10$Jru2IAiE1ooTYne6.c5xW.FaFLWHlg6lGSOHO52OaCcmn8.hnLyKK',0.00),(2,'Daniel','Garza','','2023-10-19','1999-08-17','123 st','','houston','TX','12312','2131237892','test@email.com','$2y$10$QSrwcLytKmNrq7UEZ1vC3ememVklZDxx88alQCRrPmplP9J9Q6ifK',0.00),(3,'test','test','','2023-10-20','1900-12-12','123sfd','','san an','TX','78945','4567894567','testing@email.com','$2y$10$Xf9hNS9R6kl5K/B82YEyzOI/KR4XpEsyI6g7ygzaUhkJfFFXvefsy',0.00),(4,'valid','twoo','','2023-10-20','2023-12-31','456 dr','','housrt','TN','12457','2356784512','valid@email.com','$2y$10$U0Uy9NWiQh/6XaRO0hiBCOTF755erPa1aSNLnqSYMdb/gBAFKJVS2',0.00),(5,'valid','test','','2023-10-21','2023-12-12','123 st','','sanjkd','TX','12345','1234561234','validtest@email.com','$2y$10$q6FITrPPH3lyxl7jT2GvUOx4U/SK5.tzvM14KYB3hhbsPFhR06Cuy',0.00),(6,'donald','duck','','2023-10-22','1990-12-25','duck lane','','duckville','OR','12389','1212121212','duck@email.com','$2y$10$IaX2ldLdNgGgFhC1JR/krOXwJpw/SL/GzffRdHZjibEbD90x4t6Iy',0.00),(7,'tom','jerry','','2023-10-22','1950-01-01','123 lane','','dallas','TX','18901','1237894561','tom@email.com','$2y$10$VT/3ZUrrDIWxj8fTroAg7.ovLUszjYQ9modurtYce/0qkL8RoZY/e',0.00),(8,'jose','altuve','','2023-10-22','1990-01-10','123 astro','','hoston','TX','77111','1234569089','jose@email.com','$2y$10$BZlpuGDh0mHhZaNZNIPh3ulBEW9S6g0ZoNNt2Y7YBsgFSNX7L6yVW',0.00),(9,'kyle','tucker','','2023-10-22','1999-08-20','123 astro','','houston','TX','77198','8791237891','kyle@email.com','$2y$10$uDPACkkE9W.w71SAyB1k0.623omtv1.GSoSpG4acOWS0jZKUHwC0i',0.00),(10,'sam','aja','','2023-10-26','2000-12-03','nsns ns sj','nns','jsnshns','IA','77444','2839989982','snjsjsns@hjnsn.com','$2y$10$mh6vQ65lzxJy6eRg2xwkZO/gpBvmjBV7JeXwVKTKKkbrOARLfvfea',0.00),(11,'new','test','q','2023-10-29','1910-10-10','ndsjaknjk','','dallas','TX','12390','1238901212','new@email.com','$2y$10$R2WjU9uvqmVvNqYnna56WOQaIfXC0XT6wz4HwRHpzrEHuQIFITmJy',0.00);
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
  `Employee_ID` int unsigned NOT NULL DEFAULT '0',
  `E_First_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `E_Last_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `Title_Role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `Supervisor_ID` int unsigned DEFAULT NULL,
  `Hire_Date` date DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clocked_in` tinyint(1) DEFAULT '0',
  `assigned_orders` int unsigned DEFAULT '0',
  `completed_orders` int unsigned DEFAULT '0',
  `Store_ID` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Employee_ID`),
  KEY `index_Store_ID` (`Store_ID`),
  CONSTRAINT `employee_to_store` FOREIGN KEY (`Store_ID`) REFERENCES `pizza_store` (`Pizza_Store_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (12345678,'Nathaniel','B','CEO',NULL,'2023-10-30','$2y$10$Qu7aje81OeAFXv240e/kfu7CHjwu9ubpDLo0oCMsn448eJI7/oX1S',1,0,0,1);
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guest`
--

DROP TABLE IF EXISTS `guest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guest` (
  `Guest_ID` int unsigned NOT NULL AUTO_INCREMENT,
  `G_First_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `G_Last_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
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
  `Item_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Default',
  `Vend_ID` int unsigned NOT NULL DEFAULT '0',
  `Cost` decimal(5,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Inventory_ID`),
  KEY `Vendor_ID_idx` (`Vend_ID`),
  CONSTRAINT `Vendor_ID` FOREIGN KEY (`Vend_ID`) REFERENCES `vendor` (`Vendor_ID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (0,12,10,'2023-10-29','2024-10-29','Bologna',123456,501.99);
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
INSERT INTO `menu` VALUES (1,'2023-10-25','Test');
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
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
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
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
  `Store_ID` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Order_ID`),
  KEY `ref_customers` (`O_Customer_ID`),
  KEY `ref_pizza_store_idx` (`Store_ID`),
  CONSTRAINT `ref_customers` FOREIGN KEY (`O_Customer_ID`) REFERENCES `customers` (`customer_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `ref_pizza_store` FOREIGN KEY (`Store_ID`) REFERENCES `pizza_store` (`Pizza_Store_ID`) ON DELETE RESTRICT ON UPDATE CASCADE
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
  `Size_Option` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Cost` decimal(4,2) NOT NULL DEFAULT '0.00',
  `Image_Path` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'img/ImageNotFound.png',
  `Description` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'This is a placeholder description.',
  `Name` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pizza Name',
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
INSERT INTO `pizza` VALUES (1000,1,1200,'S',12.99,'img/cheese_pizza.jpeg','This is a very yummy Cheese Pizza','Cheese Pizza'),(1001,1,900,'M',14.99,'img/cheese_pizza.jpeg','This is a very yummy medium sized cheese pizza','Cheese Pizza'),(1002,1,900,'L',18.99,'img/cheese_pizza.jpeg','This is a very yummy large sized cheese pizza','Cheese Pizza'),(1003,1,1900,'S',13.99,'img/pepperoni_pizza.jpeg','This is a small pepperoni pizza','Pepperoni Pizza');
/*!40000 ALTER TABLE `pizza` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pizza_store`
--

DROP TABLE IF EXISTS `pizza_store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pizza_store` (
  `Pizza_Store_ID` int unsigned NOT NULL AUTO_INCREMENT,
  `Store_Manager_ID` int unsigned NOT NULL DEFAULT '0',
  `Store_Address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `Store_City` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `Store_State` char(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Store_Zip_Code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `Store_Phone_Number` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`Pizza_Store_ID`),
  UNIQUE KEY `Store_Manager_ID_UNIQUE` (`Store_Manager_ID`),
  KEY `Employee_ID_idx` (`Store_Manager_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pizza_store`
--

LOCK TABLES `pizza_store` WRITE;
/*!40000 ALTER TABLE `pizza_store` DISABLE KEYS */;
INSERT INTO `pizza_store` VALUES (1,0,'123 UH Drive','Houston','','77204','2813308004');
/*!40000 ALTER TABLE `pizza_store` ENABLE KEYS */;
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
  `Topping_On_Pizza_ID` int unsigned NOT NULL AUTO_INCREMENT,
  `Amount` int unsigned DEFAULT '0',
  `Price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `topping_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`Topping_On_Pizza_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topping_on_pizza`
--

LOCK TABLES `topping_on_pizza` WRITE;
/*!40000 ALTER TABLE `topping_on_pizza` DISABLE KEYS */;
INSERT INTO `topping_on_pizza` VALUES (1,0,1.00,'Extra Cheese'),(2,0,1.25,'Pepperoni'),(3,0,1.25,'Sausage'),(4,0,1.25,'Beef'),(5,0,1.50,'Chicken'),(6,0,0.25,'Black Olives'),(7,0,0.25,'Jalapeno'),(8,0,30.00,'Pineapple'),(9,0,1.00,'Extra Sauce'),(10,0,0.00,'NO CHEESE'),(11,0,0.00,'NO SAUCE'),(12,0,0.25,'Garlic'),(13,0,1.25,'Ham');
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
  `V_Rep_Fname` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `V_Rep_Lname` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `V_Email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `V_Phone` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0000000000',
  PRIMARY KEY (`Vendor_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor`
--

LOCK TABLES `vendor` WRITE;
/*!40000 ALTER TABLE `vendor` DISABLE KEYS */;
INSERT INTO `vendor` VALUES (123456,'Good Shit Supplies','MeHoff','Jacqueline','imold@hotmail.com','1-800-867-5309');
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

-- Dump completed on 2023-11-01  1:13:02
