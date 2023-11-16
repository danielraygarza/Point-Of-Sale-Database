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
  `total_spent_toDate` decimal(7,2) NOT NULL DEFAULT '0.00',
  `store_credit` decimal(7,2) NOT NULL DEFAULT '0.00',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'dan','ga','','2023-10-17','1999-08-18','23 st','','houst','TX','12312','1231231230','daniel@email.com',0.00,0.00,'$2y$10$Jru2IAiE1ooTYne6.c5xW.FaFLWHlg6lGSOHO52OaCcmn8.hnLyKK'),(2,'Daniel','Garza','','2023-10-19','1999-11-02','123 st','','houston','TX','12312','2131237892','test@email.com',22.73,0.00,'$2y$10$QSrwcLytKmNrq7UEZ1vC3ememVklZDxx88alQCRrPmplP9J9Q6ifK'),(3,'test','test','','2023-10-20','1900-12-12','123sfd','','san an','TX','78945','4567894567','testing@email.com',0.00,0.00,'$2y$10$Xf9hNS9R6kl5K/B82YEyzOI/KR4XpEsyI6g7ygzaUhkJfFFXvefsy'),(4,'valid','twoo','','2023-10-20','2023-12-31','456 dr','','housrt','TN','12457','2356784512','valid@email.com',0.00,0.00,'$2y$10$U0Uy9NWiQh/6XaRO0hiBCOTF755erPa1aSNLnqSYMdb/gBAFKJVS2'),(5,'valid','test','','2023-10-21','2023-12-12','123 st','','sanjkd','TX','12345','1234561234','validtest@email.com',0.00,0.00,'$2y$10$q6FITrPPH3lyxl7jT2GvUOx4U/SK5.tzvM14KYB3hhbsPFhR06Cuy'),(6,'donald','duck','','2023-10-22','1990-12-25','duck lane','','duckville','OR','12389','1212121212','duck@email.com',0.00,0.00,'$2y$10$IaX2ldLdNgGgFhC1JR/krOXwJpw/SL/GzffRdHZjibEbD90x4t6Iy'),(7,'tom','jerry','','2023-10-22','1950-01-01','123 lane','','dallas','TX','18901','1237894561','tom@email.com',0.00,0.00,'$2y$10$VT/3ZUrrDIWxj8fTroAg7.ovLUszjYQ9modurtYce/0qkL8RoZY/e'),(8,'jose','altuve','','2023-10-22','1990-01-10','123 astro','','hoston','TX','77111','1234569089','jose@email.com',0.00,0.00,'$2y$10$BZlpuGDh0mHhZaNZNIPh3ulBEW9S6g0ZoNNt2Y7YBsgFSNX7L6yVW'),(9,'kyle','tucker','','2023-10-22','1999-08-20','123 astro','','houston','TX','77198','8791237891','kyle@email.com',0.00,0.00,'$2y$10$uDPACkkE9W.w71SAyB1k0.623omtv1.GSoSpG4acOWS0jZKUHwC0i'),(10,'sam','aja','','2023-10-26','2000-12-03','nsns ns sj','nns','jsnshns','IA','77444','2839989982','snjsjsns@hjnsn.com',0.00,0.00,'$2y$10$mh6vQ65lzxJy6eRg2xwkZO/gpBvmjBV7JeXwVKTKKkbrOARLfvfea'),(11,'new','test','q','2023-10-29','1910-10-10','ndsjaknjk','','dallas','TX','12390','1238901212','new@email.com',0.00,0.00,'$2y$10$R2WjU9uvqmVvNqYnna56WOQaIfXC0XT6wz4HwRHpzrEHuQIFITmJy'),(12,'Eric','Parsons','B','2023-11-01','1997-04-17','1111 Queens Bay Dr','','Katy','TX','77494','2819046623','mcfixstuff2@gmail.com',0.00,0.00,'$2y$10$niP3QKMuGpLykqUMU9JOu.ouG7hJM0Af2Zh0C74ih9g.n9MOfZP6K'),(13,'hoo','ee','','2023-11-02','2000-12-12','jdmdnjwns','sjsns','jdndnmd','FL','77666','2345678901','jajajaja@gma.com',0.00,0.00,'$2y$10$IEv9bphYDiqESvXE6yC5GO43mCjhI8jjatmKKRLovHWK3WoDanL7C'),(14,'abc','sbss','a','2023-11-14','2000-01-01','jdmdnjwns','','Humble ','AR','77444','2839989982','sam@gmail.com',31.98,0.00,'$2y$10$jOELQkSa9cIK3Mt0mtAS6uEcDFysIHOC6SB4cMXvDRqR327qX0eZu'),(15,'Erik','Parkonson','K','2023-11-14','2000-04-22','132 Kweens Dr','','Katy','TX','77494','2819046624','EP@gmail.com',194.48,0.00,'$2y$10$Diu4bW2oquN91COeIr5F3.HaPdDxCdLywC.pbRTkWoeGLel1yE14y');
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
  `Time_Delivered` time NOT NULL DEFAULT '00:00:00',
  `D_Address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `D_Address2` text COLLATE utf8mb4_unicode_ci,
  `D_City` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `D_State` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `D_Zip_Code` int unsigned NOT NULL DEFAULT '1',
  `delivery_employeeID` int unsigned NOT NULL,
  PRIMARY KEY (`D_Order_ID`),
  KEY `delivery_to_employee` (`delivery_employeeID`),
  CONSTRAINT `delivery_to_employee` FOREIGN KEY (`delivery_employeeID`) REFERENCES `employee` (`Employee_ID`),
  CONSTRAINT `ref_orders_d` FOREIGN KEY (`D_Order_ID`) REFERENCES `orders` (`Order_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery`
--

LOCK TABLES `delivery` WRITE;
/*!40000 ALTER TABLE `delivery` DISABLE KEYS */;
INSERT INTO `delivery` VALUES (1,'2023-11-16','00:04:02','00:00:00','123 st','','houston','TX',12312,111111);
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
  `Hire_Date` date DEFAULT NULL,
  `Supervisor_ID` int unsigned DEFAULT NULL,
  `Store_ID` int unsigned NOT NULL DEFAULT '0',
  `clocked_in` tinyint(1) DEFAULT '0',
  `assigned_orders` int unsigned DEFAULT '0',
  `completed_orders` int unsigned DEFAULT '0',
  `active_employee` tinyint(1) DEFAULT '1',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`Employee_ID`),
  KEY `index_Store_ID` (`Store_ID`),
  CONSTRAINT `employee_to_store` FOREIGN KEY (`Store_ID`) REFERENCES `pizza_store` (`Pizza_Store_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (101010,'Sauce','Artist','SUP','2023-11-16',464646,3,1,0,0,1,'$2y$10$uibbknU.2eu4Kxa8wQxAJOnHjynBTcrgRJVmuFycWLDgdlmL4XfNa'),(111111,'Victor','Wemby','SUP','2023-11-08',890123,2,1,1,0,1,'$2y$10$QS6231y32lLGDAvm49.gue3jdpIPhrEAoYZcJmx0aAc.UDbsbpAny'),(121212,'Dirty','Curty','TM','2023-11-09',123456,3,1,0,0,1,'$2y$10$iWhHAiChLu6xNdAhIfcaRetx.Wy65sO6kfI2vGgVHLMZ.ocgOHhsy'),(123456,'Mozzarella','Master','MAN','2023-11-01',12345678,4,1,0,0,1,'$2y$10$hT/QjisOJjLGA90B7F.h2.ZtEmAelaHYxuPGtNyp21pIQAE1ngb02'),(123789,'Danny','B','TM','2023-11-01',890123,2,1,0,0,1,'$2y$10$eeod5i6i6Fc1cl8HmQX7Su7BXOxRZBIUQKNXmmXS4lqpHKl.pqDj.'),(151515,'Danny','Ray','TM','2023-11-16',789456,5,1,0,0,1,'$2y$10$Yv.Cai/gGiGBVHrWhpiutekbs6rh751J5335Cosol.2w004Bx82BC'),(190290,'Cody','Catfish','TM','2023-11-16',290290,4,1,0,0,1,'$2y$10$jSQOw/CqYedeb.Y0dm4YJezfSteEm9St.UAfIkvLGBlstOMl2TxYO'),(232323,'Michael','Jordan','TM','2023-11-16',290290,4,1,0,0,1,'$2y$10$NK5mVD7i0xL0YylsYDNr7ehnasbduWz09IcHGs9/99bqiKoLxqxQS'),(239012,'Dusty','Baker','SUP','2023-11-16',901234,5,1,0,0,1,'$2y$10$eHsdjlSefJbbPaCaN8IR7e8yNyZkSqqTLeFiJfCdJhZS0aCzZX4ji'),(272727,'Jose','Altuve','TM','2023-11-16',239012,5,1,0,0,1,'$2y$10$wMC8eaILowx3nhU/6h5pceuMNmV9MvW1eskx58iUHnaSt6heiOitC'),(290290,'Thomas','Brady','SUP','2023-11-16',123456,4,1,0,0,1,'$2y$10$ZkoYEBvXHZQejI0CaUqFa.M4Ovz3HiYWsHy./a6gwDB1AWuj1zosS'),(456456,'Bucky','Barnes','TM','2023-11-16',561256,4,1,0,0,1,'$2y$10$sBt1A7s0QodAPlGItvdnkuHH8DXSsvH5lDafC0VGzUSKlyHKV8E0u'),(456789,'Super','Visor','SUP','2023-11-01',123456,2,1,0,0,1,'$2y$10$fCkXwnZvFfY9YTZ.qVq38eiqLoUdlNkp/TGliaE/qUTdZyQrdaviq'),(464646,'Crust','Commander','MAN','2023-11-16',12345678,3,1,0,0,1,'$2y$10$xKy1/Z9hSuq96JxBQn/KYuA53gfARzTTaeejSv6S9Je0TYWoxgwAy'),(561256,'Steve','Rogers','SUP','2023-11-16',123456,4,1,0,0,1,'$2y$10$qdhVpxlDwkZPxPeXi5Z/1egywCPpcindUhZr.4Ke.BeafUeOuISnO'),(696969,'Erik','Persons','TM','2023-11-16',101010,3,1,0,0,1,'$2y$10$QvWJ7lIGF4FvM8jR2X/s2ON7Y66ynx4MmK5aN9ChgIAOpznVK8vf6'),(789456,'Bruce','Banner','SUP','2023-11-16',901234,5,1,0,0,1,'$2y$10$R7otoDci1qeCZmri6WA7De9c5gC/BtcCZI8RXWkgif.O5QqXTOij2'),(890123,'Dinku','Doolium','MAN','2023-11-08',12345678,2,1,1,0,1,'$2y$10$lzAvUY.G/UiwRe/.chcay..WntabzS/jIVC1wT3M1R7TxrPRqiFj2'),(892389,'Pizza','Prodigy','SUP','2023-11-16',464646,3,1,0,0,1,'$2y$10$EEKx6DEvT/yAw5QiPpFs1ORf./0qKybTsAUQKxublWp32z98aeMye'),(901234,'Calzone','Captain','MAN','2023-11-16',12345678,5,0,0,0,1,'$2y$10$r7E0tI2C0oRceHLNJ.Bs.Oqlv5gf37MHUzpclU/5ACJy9P6AIEium'),(12345678,'Shasta','VII','CEO','2023-10-30',NULL,1,1,0,0,1,'$2y$10$Qu7aje81OeAFXv240e/kfu7CHjwu9ubpDLo0oCMsn448eJI7/oX1S'),(98765432,'Billy','Joe','TM','2023-11-16',890123,2,0,0,0,0,'$2y$10$83wkNjxLF/O13knrv1GXD.rMePI..XooM09T7Qt.3RCpWwUas0Jh2');
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
  `G_Phone_Number` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Guest_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
  `Item_ID` int unsigned NOT NULL DEFAULT '0',
  `Store_ID` int unsigned NOT NULL DEFAULT '0',
  `Vend_ID` int unsigned NOT NULL DEFAULT '0',
  `Inventory_Amount` decimal(7,2) unsigned NOT NULL DEFAULT '0.00',
  `Last_Stock_Shipment_Date` date NOT NULL DEFAULT '2020-10-12',
  `Expiration_Date` date NOT NULL DEFAULT ((`Last_Stock_Shipment_Date` + 1)),
  PRIMARY KEY (`Item_ID`,`Store_ID`),
  KEY `Vendor_ID_idx` (`Vend_ID`),
  KEY `ref_pizza_store_idx` (`Store_ID`),
  KEY `inventory_to_item_idx` (`Item_ID`),
  CONSTRAINT `inventory_to_item` FOREIGN KEY (`Item_ID`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ref_pizza_store_id` FOREIGN KEY (`Store_ID`) REFERENCES `pizza_store` (`Pizza_Store_ID`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `Vendor_ID` FOREIGN KEY (`Vend_ID`) REFERENCES `vendor` (`Vendor_ID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (5,2,654321,150.00,'2023-11-10','2023-12-10'),(5,3,123456,150.00,'2023-11-10','2023-12-10'),(5,4,987654,150.00,'2023-11-10','2023-12-10'),(5,5,123456,150.00,'2023-11-10','2023-12-10'),(6,2,765432,150.00,'2023-11-09','2023-12-09'),(6,3,765432,150.00,'2023-11-09','2023-12-09'),(6,4,765432,150.00,'2023-11-09','2023-12-09'),(6,5,876543,150.00,'2023-11-09','2023-12-09'),(7,2,123456,150.00,'2023-11-11','2024-02-09'),(7,3,543210,150.00,'2023-11-11','2024-02-09'),(7,4,543210,150.00,'2023-11-11','2024-02-09'),(7,5,543210,150.00,'2023-11-11','2024-02-09'),(8,2,543210,149.00,'2023-11-16','2023-11-21'),(8,3,987654,150.00,'2023-11-16','2023-11-21'),(8,4,765432,150.00,'2023-11-16','2023-11-21'),(8,5,987654,150.00,'2023-11-16','2023-11-21'),(9,2,654321,149.00,'2023-11-15','2023-11-25'),(9,3,543210,150.00,'2023-11-15','2023-11-25'),(9,4,765432,150.00,'2023-11-15','2023-11-25'),(9,5,543210,150.00,'2023-11-15','2023-11-25'),(10,2,765432,150.00,'2023-11-15','2023-11-25'),(10,3,765432,150.00,'2023-11-15','2023-11-25'),(10,4,765432,150.00,'2023-11-15','2023-11-25'),(10,5,765432,150.00,'2023-11-15','2023-11-25'),(15,2,987654,150.00,'2023-11-05','2023-12-25'),(15,3,543210,150.00,'2023-11-05','2023-12-25'),(15,4,654321,150.00,'2023-11-05','2023-12-25'),(15,5,876543,150.00,'2023-11-05','2023-12-25'),(16,2,654321,140.00,'2023-11-05','2023-12-25'),(16,3,987654,150.00,'2023-11-05','2023-12-25'),(16,4,987654,150.00,'2023-11-05','2023-12-25'),(16,5,123456,150.00,'2023-11-05','2023-12-25'),(17,2,654321,150.00,'2023-11-16','2023-12-01'),(17,3,654321,150.00,'2023-11-16','2023-12-01'),(17,4,654321,150.00,'2023-11-16','2023-12-01'),(17,5,765432,150.00,'2023-11-16','2023-12-01'),(18,2,123456,150.00,'2023-11-16','2023-12-01'),(18,3,543210,150.00,'2023-11-16','2023-12-01'),(18,4,654321,150.00,'2023-11-16','2023-12-01'),(18,5,987654,150.00,'2023-11-16','2023-12-01'),(19,2,765432,150.00,'2023-11-16','2023-12-01'),(19,3,876543,150.00,'2023-11-16','2023-12-01'),(19,4,123456,150.00,'2023-11-16','2023-12-01'),(19,5,543210,150.00,'2023-11-16','2023-12-01'),(20,2,543210,150.00,'2023-11-16','2023-12-01'),(20,3,123456,150.00,'2023-11-16','2023-12-01'),(20,4,543210,150.00,'2023-11-16','2023-12-01'),(20,5,987654,150.00,'2023-11-16','2023-12-01'),(21,2,543210,150.00,'2023-11-12','2023-12-02'),(21,3,765432,150.00,'2023-11-12','2023-12-02'),(21,4,765432,150.00,'2023-11-12','2023-12-02'),(21,5,654321,150.00,'2023-11-12','2023-12-02'),(22,2,654321,150.00,'2023-11-16','2023-12-01'),(22,3,987654,150.00,'2023-11-16','2023-12-01'),(22,4,654321,150.00,'2023-11-16','2023-12-01'),(22,5,543210,150.00,'2023-11-16','2023-12-01'),(23,2,123456,150.00,'2023-11-16','2023-12-01'),(23,3,543210,150.00,'2023-11-16','2023-12-01'),(23,4,765432,150.00,'2023-11-16','2023-12-01'),(23,5,876543,150.00,'2023-11-16','2023-12-01'),(24,2,765432,150.00,'2023-11-16','2023-12-01'),(24,3,765432,150.00,'2023-11-16','2023-12-01'),(24,4,876543,150.00,'2023-11-16','2023-12-01'),(24,5,123456,150.00,'2023-11-16','2023-12-01'),(25,2,654321,150.00,'2023-11-16','2023-12-01'),(25,3,543210,150.00,'2023-11-16','2023-12-01'),(25,4,654321,150.00,'2023-11-16','2023-12-01'),(25,5,765432,150.00,'2023-11-16','2023-12-01'),(26,2,543210,150.00,'2023-11-15','2023-11-23'),(26,3,765432,150.00,'2023-11-15','2023-11-23'),(26,4,543210,150.00,'2023-11-15','2023-11-23'),(26,5,765432,150.00,'2023-11-15','2023-11-23'),(27,2,876543,150.00,'2023-11-16','2023-12-01'),(27,3,876543,150.00,'2023-11-16','2023-12-01'),(27,4,876543,150.00,'2023-11-16','2023-12-01'),(27,5,765432,150.00,'2023-11-16','2023-12-01');
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `Item_ID` int unsigned NOT NULL AUTO_INCREMENT,
  `Item_Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Default',
  `Item_Type` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Default',
  `Item_Cost` decimal(6,2) NOT NULL DEFAULT '0.00',
  `Cost_Of_Good` decimal(6,2) NOT NULL DEFAULT '0.00',
  `Reorder_Threshold` int DEFAULT NULL,
  `Days_to_expire` int DEFAULT NULL,
  `Amount_per_order` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`Item_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'Small Pizza','Pizza',0.00,0.00,NULL,NULL,NULL),(2,'Medium Pizza','Pizza',0.00,0.00,NULL,NULL,NULL),(3,'Large Pizza','Pizza',0.00,0.00,NULL,NULL,NULL),(4,'XL Pizza','Pizza',0.00,0.00,NULL,NULL,NULL),(5,'Garlic Stix','Side',5.99,3.50,100,30,4.00),(6,'Cinnamon Rolls','Side',5.29,1.50,100,30,6.00),(7,'Soda','Side',4.99,2.75,50,90,1.00),(8,'Dough','Ingredient',0.00,0.50,100,5,NULL),(9,'Cheese','Ingredient',0.00,1.00,100,10,NULL),(10,'Sauce','Ingredient',0.00,2.00,100,10,NULL),(11,'Extra Cheese','Topping',1.00,0.00,NULL,NULL,0.00),(12,'Extra Sauce','Topping',1.00,0.00,NULL,NULL,0.00),(13,'No Cheese','Topping',0.00,0.00,NULL,NULL,0.00),(14,'No Sauce','Topping',0.00,0.00,NULL,NULL,0.00),(15,'Pepperoni','Topping',1.25,0.40,100,50,10.00),(16,'Sausage','Topping',1.25,0.40,100,50,10.00),(17,'Beef','Topping',1.25,0.40,100,15,10.00),(18,'Ham','Topping',1.25,0.40,100,15,10.00),(19,'Garlic','Topping',0.25,0.10,100,15,5.00),(20,'Chicken','Topping',1.50,0.40,100,15,10.00),(21,'Bacon','Topping',1.50,0.80,100,20,10.00),(22,'Jalapeno','Topping',0.25,0.10,100,15,15.00),(23,'Pineapple','Topping',30.00,1.00,100,15,20.00),(24,'Black Olives','Topping',0.25,0.10,100,15,15.00),(25,'Garlic','Topping',0.25,0.10,100,15,5.00),(26,'Onions','Topping',0.65,0.00,100,8,1.00),(27,'Garlic Butter Crust','Topping',1.29,0.70,100,15,2.00);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `Pizza_ID` int unsigned NOT NULL DEFAULT '0',
  `Calories` int unsigned NOT NULL DEFAULT '0',
  `Size_Option` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Price` decimal(6,2) NOT NULL DEFAULT '0.00',
  `Image_Path` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'img/ImageNotFound.png',
  `Description` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'This is a placeholder description.',
  `Name` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pizza Name',
  `Is_Pizza` tinyint NOT NULL DEFAULT '1',
  `Cost_Of_Good` decimal(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Pizza_ID`),
  CONSTRAINT `menu_to_items` FOREIGN KEY (`Pizza_ID`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,800,'S',7.99,'img/cheese_pizza.jpeg','This is a placeholder description.','Small Pizza',1,1.75),(2,1200,'M',10.99,'img/cheese_pizza.jpeg','This is a placeholder description.','Medium Pizza',1,3.50),(3,1750,'L',13.99,'img/cheese_pizza.jpeg','This is a placeholder description.','Large Pizza',1,5.25),(4,2100,'X',16.99,'img/cheese_pizza.jpeg','Family sized, or personal sized for an athlete.','XL Pizza',1,7.00),(5,725,'M',5.99,'img/stix.jpg','Four count garlic bread sticks.','Garlic Stix',0,3.50),(6,210,'L',4.99,'img/SODA.jpg','Large drink for a large human.','Soda',0,2.75),(7,820,'M',5.29,'img/dessert.jpg','High profit margin for us. Delicious for you.','Cinnamon Rolls',0,1.50);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `Order_Item_ID` int unsigned NOT NULL AUTO_INCREMENT,
  `Order_ID` int unsigned NOT NULL DEFAULT '0',
  `Item_ID` int unsigned NOT NULL DEFAULT '0',
  `Item_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Price` decimal(4,2) NOT NULL DEFAULT '0.00',
  `Date_ordered` date DEFAULT NULL,
  PRIMARY KEY (`Order_Item_ID`),
  KEY `oi_o_item_id_idx` (`Item_ID`),
  KEY `oi_o_order_id_idx` (`Order_ID`),
  CONSTRAINT `oi_o_item_id` FOREIGN KEY (`Item_ID`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `oi_o_order_id` FOREIGN KEY (`Order_ID`) REFERENCES `orders` (`Order_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=239 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,5,'Garlic Stix',5.99,'2023-11-16'),(2,1,15,'Pepperoni',1.25,'2023-11-16'),(3,1,21,'Bacon',1.50,'2023-11-16'),(233,2,5,'Garlic Stix',5.99,'2023-11-16'),(234,2,2,'Medium Pizza',10.99,'2023-11-16'),(235,2,15,'Pepperoni',1.25,'2023-11-16'),(236,2,16,'Sausage',1.25,'2023-11-16'),(237,2,17,'Beef',1.25,'2023-11-16'),(238,2,18,'Ham',1.25,'2023-11-16');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `Order_ID` int unsigned NOT NULL AUTO_INCREMENT,
  `Date_Of_Order` date NOT NULL DEFAULT '2020-10-12',
  `Time_Of_Order` time NOT NULL DEFAULT '00:00:00',
  `Time_Completed` time NOT NULL DEFAULT '00:00:00',
  `Order_Type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Order_Status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'In Progress',
  `Total_Amount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `Cost_Of_Goods` decimal(8,2) NOT NULL DEFAULT '0.00',
  `Employee_ID_assigned` int unsigned NOT NULL DEFAULT '0',
  `Customer_ID` int NOT NULL DEFAULT '0',
  `Store_ID` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Order_ID`),
  KEY `ref_pizza_store_idx` (`Store_ID`),
  KEY `ref_employeeOrders_idx` (`Employee_ID_assigned`),
  KEY `ref_customers` (`Customer_ID`),
  CONSTRAINT `ref_customers` FOREIGN KEY (`Customer_ID`) REFERENCES `customers` (`customer_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `ref_employeeOrders` FOREIGN KEY (`Employee_ID_assigned`) REFERENCES `employee` (`Employee_ID`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `ref_pizza_store` FOREIGN KEY (`Store_ID`) REFERENCES `pizza_store` (`Pizza_Store_ID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'2023-11-16','00:04:02','00:00:00','Delivery','In Progress',22.73,15.70,111111,2,2),(2,'2023-11-16','06:58:08','00:00:00','Pickup','In Progress',31.98,8.60,890123,14,2);
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
  `PU_firstName` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `PU_lastName` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`PU_Order_ID`),
  KEY `pickup_to_employee` (`employee`),
  CONSTRAINT `pickup_to_employee` FOREIGN KEY (`employee`) REFERENCES `employee` (`Employee_ID`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `ref_orders_p` FOREIGN KEY (`PU_Order_ID`) REFERENCES `orders` (`Order_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pickup`
--

LOCK TABLES `pickup` WRITE;
/*!40000 ALTER TABLE `pickup` DISABLE KEYS */;
INSERT INTO `pickup` VALUES (2,'2023-11-16','06:58:08','00:00:00',890123,'abc','sbss');
/*!40000 ALTER TABLE `pickup` ENABLE KEYS */;
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
  KEY `Employee_ID_idx` (`Store_Manager_ID`),
  CONSTRAINT `manager_to_employee` FOREIGN KEY (`Store_Manager_ID`) REFERENCES `employee` (`Employee_ID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pizza_store`
--

LOCK TABLES `pizza_store` WRITE;
/*!40000 ALTER TABLE `pizza_store` DISABLE KEYS */;
INSERT INTO `pizza_store` VALUES (1,12345678,'123 UH Drive','Houston','TX','77204','2813308004'),(2,890123,'789 Cougar Dr','Houston','TX','77024','2817893785'),(3,123456,'1711 Chicken Nug Rd','Houston','TX','77011','7131231234'),(4,123456,'486 Cheesy Ln','Houston','TX','77012','2810910293'),(5,901234,'281 Pizzeria Pkwy','Houston','TX','77007','2813841092');
/*!40000 ALTER TABLE `pizza_store` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `T_Order_ID` int unsigned NOT NULL DEFAULT '0',
  `Total_Amount_Charged` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Amount_Tipped` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Payment_Method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `T_Date` date NOT NULL DEFAULT '2020-10-12',
  `Time_Processed` time NOT NULL DEFAULT '00:00:00',
  PRIMARY KEY (`T_Order_ID`),
  CONSTRAINT `transaction_order` FOREIGN KEY (`T_Order_ID`) REFERENCES `orders` (`Order_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,22.73,3.00,'Credit Card','2023-11-16','00:04:02'),(2,31.98,10.00,'Credit Card','2023-11-16','06:58:08');
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
INSERT INTO `vendor` VALUES (123456,'Good Stuff Supplies','DB','Cooper','imold@hotmail.com','1-800-867-5309'),(543210,'Toppings Unlimited','Chris','Green','chris.green@example.com','1-800-555-8765'),(654321,'DoughDelights Suppliers','Megan','Miller','megan.miller@example.com','1-800-325-4321'),(765432,'SauceMaster Inc.','Robert','Johnson','robert.johnson@example.com','1-800-764-9876'),(876543,'CheeseCraft Distributors','Alice','Smith','alice.smith@example.com','1-800-189-5678'),(987654,'PizzaPro Supplies','John','Doe','john.doe@example.com','1-800-387-1234');
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

-- Dump completed on 2023-11-16  8:21:24
