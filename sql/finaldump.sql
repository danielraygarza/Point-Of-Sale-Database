CREATE DATABASE  IF NOT EXISTS `pos` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `pos`;
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'dan','ga','','2023-10-17','1999-08-18','23 st','','houst','TX','12312','1231231230','daniel@email.com',0.00,0.00,'$2y$10$Jru2IAiE1ooTYne6.c5xW.FaFLWHlg6lGSOHO52OaCcmn8.hnLyKK'),(2,'Danny','Boy','','2023-10-19','1999-11-02','123 pizza lane','','houston','TX','12312','2131237892','test@email.com',298.85,0.00,'$2y$10$QSrwcLytKmNrq7UEZ1vC3ememVklZDxx88alQCRrPmplP9J9Q6ifK'),(3,'test','test','','2023-10-20','1900-12-12','123sfd','','san an','TX','78945','4567894567','testing@email.com',0.00,0.00,'$2y$10$Xf9hNS9R6kl5K/B82YEyzOI/KR4XpEsyI6g7ygzaUhkJfFFXvefsy'),(4,'valid','twoo','','2023-10-20','2023-12-31','456 dr','','housrt','TN','12457','2356784512','valid@email.com',0.00,0.00,'$2y$10$U0Uy9NWiQh/6XaRO0hiBCOTF755erPa1aSNLnqSYMdb/gBAFKJVS2'),(5,'valid','test','','2023-10-21','2023-12-12','123 st','','sanjkd','TX','12345','1234561234','validtest@email.com',0.00,0.00,'$2y$10$q6FITrPPH3lyxl7jT2GvUOx4U/SK5.tzvM14KYB3hhbsPFhR06Cuy'),(6,'donald','duck','','2023-10-22','1990-12-25','duck lane','','duckville','OR','12389','1212121212','duck@email.com',18.89,0.00,'$2y$10$IaX2ldLdNgGgFhC1JR/krOXwJpw/SL/GzffRdHZjibEbD90x4t6Iy'),(7,'tom','jerry','','2023-10-22','1950-01-01','123 lane','','dallas','TX','18901','1237894561','tom@email.com',38.22,0.00,'$2y$10$VT/3ZUrrDIWxj8fTroAg7.ovLUszjYQ9modurtYce/0qkL8RoZY/e'),(8,'jose','altuve','','2023-10-22','1990-01-10','123 astro','','hoston','TX','77111','1234569089','jose@email.com',95.89,0.00,'$2y$10$BZlpuGDh0mHhZaNZNIPh3ulBEW9S6g0ZoNNt2Y7YBsgFSNX7L6yVW'),(9,'kyle','tucker','','2023-10-22','1999-08-20','123 astro','','houston','TX','77198','8791237891','kyle@email.com',0.00,0.00,'$2y$10$uDPACkkE9W.w71SAyB1k0.623omtv1.GSoSpG4acOWS0jZKUHwC0i'),(10,'sam','aja','','2023-10-26','2000-12-03','nsns ns sj','nns','jsnshns','IA','77444','2839989982','snjsjsns@hjnsn.com',0.00,0.00,'$2y$10$mh6vQ65lzxJy6eRg2xwkZO/gpBvmjBV7JeXwVKTKKkbrOARLfvfea'),(11,'new','test','q','2023-10-29','1910-10-10','ndsjaknjk','','dallas','TX','12390','1238901212','new@email.com',0.00,0.00,'$2y$10$R2WjU9uvqmVvNqYnna56WOQaIfXC0XT6wz4HwRHpzrEHuQIFITmJy'),(12,'Eric','Parsons','B','2023-11-01','1997-04-17','1111 Queens Bay Dr','','Katy','TX','77494','2819046623','mcfixstuff2@gmail.com',0.00,0.00,'$2y$10$niP3QKMuGpLykqUMU9JOu.ouG7hJM0Af2Zh0C74ih9g.n9MOfZP6K'),(13,'hoo','ee','','2023-11-02','2000-12-12','jdmdnjwns','sjsns','jdndnmd','FL','77666','2345678901','jajajaja@gma.com',0.00,0.00,'$2y$10$IEv9bphYDiqESvXE6yC5GO43mCjhI8jjatmKKRLovHWK3WoDanL7C'),(14,'Dr','Suess','a','2023-11-14','2000-01-01','jdmdnjwns','','Humble ','AR','77444','2839989982','sam@gmail.com',969.98,0.00,'$2y$10$jOELQkSa9cIK3Mt0mtAS6uEcDFysIHOC6SB4cMXvDRqR327qX0eZu'),(15,'Erik','Parkonson','K','2023-11-14','2000-04-22','132 Kweens Dr','','Katy','TX','77494','2819046624','EP@gmail.com',194.48,0.00,'$2y$10$Diu4bW2oquN91COeIr5F3.HaPdDxCdLywC.pbRTkWoeGLel1yE14y'),(16,'Billy ','Cyrus','R','2023-11-16','1965-12-25','734 Malibu','','Los Angeles','CA','55612','2137284902','billy@email.com',15.99,0.00,'$2y$10$mhP7e82r35t6MxBnkC9BmOFGi9h188x8tELgKvyXrHkss.lf8LcYG'),(17,'David','Cooper','C','2023-11-16','1988-07-05','1234 Nunya Bidness','','Houston','TX','77025','7138675309','ismelledwhattherockwascookin@hotmail.com',91.94,0.00,'$2y$10$h.fvMwuw4K1I0RXoyYVFH.hL0PlkADTJP80iES95.8zzBh.SSriwO'),(18,'Eric','Par','B','2023-11-16','2000-04-17','1403 shasta lane','','Katy','TX','77494','2222222213','eric@updog.net',124.24,0.00,'$2y$10$51RDfqujR44cXck0h.qqgOrGYrbA6rOC9mVUNmDkfCxJrqr8TunRy'),(19,'aj','hawk','','2023-11-21','1980-01-06','456 packer way','','green bay','WI','45678','5678561245','aj@email.com',0.00,0.00,'$2y$10$FWm7dECXgGZU/hkc1TL6ZOSwC/FgXynkwQtGNb8I/w/r6Fy9dIqJS');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`danielgarza`@`%`*/ /*!50003 TRIGGER `customers_BEFORE_UPDATE` BEFORE UPDATE ON `customers` FOR EACH ROW BEGIN
  -- event: customers places order
  IF OLD.total_spent_toDate < NEW.total_spent_toDate THEN
	-- checking if total spent value passes a multiple of 100
    SET @hundreds = FLOOR(NEW.total_spent_toDate / 100) - FLOOR(OLD.total_spent_toDate / 100);
     -- condition
    IF @hundreds > 0 THEN
		-- action: add 10 dollars to store credit
      SET NEW.store_credit = NEW.store_credit + (@hundreds * 10);
    END IF;
  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
INSERT INTO `delivery` VALUES (1,'2023-11-16','00:04:02','13:13:12','123 st','','houston','TX',12312,111111),(4,'2023-11-16','08:28:34','00:00:00','duck lane','','duckville','OR',12389,101010),(7,'2023-11-16','11:34:55','00:00:00','jdmdnjwns','','Humble ','AR',77444,456789),(8,'2023-11-16','12:05:15','00:00:00','jdmdnjwns','','Humble ','AR',77444,892389),(10,'2023-11-16','12:06:21','00:00:00','jdmdnjwns','','Humble ','AR',77444,789456),(11,'2023-11-16','12:06:49','13:13:16','jdmdnjwns','','Humble ','AR',77444,111111),(14,'2023-11-16','12:08:02','00:00:00','jdmdnjwns','','Humble ','AR',77444,121212),(15,'2023-11-16','12:09:50','00:00:00','jdmdnjwns','','Humble ','AR',77444,456789),(19,'2023-11-16','12:17:44','00:00:00','jdmdnjwns','','Humble ','AR',77444,190290),(20,'2023-11-16','12:18:32','00:00:00','jdmdnjwns','','Humble ','AR',77444,111111),(22,'2023-11-16','12:20:56','00:00:00','jdmdnjwns','','Humble ','AR',77444,123789),(25,'2023-11-16','12:23:11','00:00:00','jdmdnjwns','','Humble ','AR',77444,890123),(26,'2023-11-16','12:26:06','00:00:00','jdmdnjwns','','Humble ','AR',77444,232323),(27,'2023-11-16','12:26:37','00:00:00','jdmdnjwns','','Humble ','AR',77444,123789),(30,'2023-11-16','12:28:28','00:00:00','jdmdnjwns','','Humble ','AR',77444,890123),(31,'2023-11-16','12:29:06','00:00:00','jdmdnjwns','','Humble ','AR',77444,123789),(32,'2023-11-16','12:29:48','00:00:00','jdmdnjwns','','Humble ','AR',77444,456789),(33,'2023-11-16','12:30:16','00:00:00','jdmdnjwns','','Humble ','AR',77444,239012),(36,'2023-11-16','13:57:15','00:00:00','734 Malibu','','Los Angeles','CA',55612,111111),(38,'2023-11-16','16:28:05','00:00:00','123 street','','houston','TX',12312,111111),(40,'2023-11-16','16:37:51','00:00:00','123 street','','houston','TX',12312,456789),(44,'2023-11-16','18:31:27','00:00:00','1403 shasta lane','','Katy','TX',77494,123789),(49,'2023-11-22','12:21:12','00:00:00','123 street','','houston','',12312,123789),(52,'2023-11-22','14:09:16','00:00:00','123 street','','houston','',12312,123789),(53,'2023-11-22','14:14:29','00:00:00','123 street','','houston','',12312,696969),(77,'2023-11-23','06:00:08','00:00:00','123 pizza lane','','houston','',12312,111111),(81,'2023-11-23','06:55:43','00:00:00','123 astro','','hoston','',77111,892389);
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
INSERT INTO `employee` VALUES (101010,'Sauce','Artist','SUP','2023-11-16',464646,3,0,0,0,1,'$2y$10$uibbknU.2eu4Kxa8wQxAJOnHjynBTcrgRJVmuFycWLDgdlmL4XfNa'),(111111,'Victor','Wemby','SUP','2023-11-08',890123,2,1,9,3,1,'$2y$10$QS6231y32lLGDAvm49.gue3jdpIPhrEAoYZcJmx0aAc.UDbsbpAny'),(121212,'Dirty','Curty','TM','2023-11-09',123456,3,1,2,0,1,'$2y$10$iWhHAiChLu6xNdAhIfcaRetx.Wy65sO6kfI2vGgVHLMZ.ocgOHhsy'),(123456,'Mozzarella','Master','MAN','2023-11-01',12345678,4,1,1,0,1,'$2y$10$hT/QjisOJjLGA90B7F.h2.ZtEmAelaHYxuPGtNyp21pIQAE1ngb02'),(123789,'Danny','B','TM','2023-11-01',890123,2,1,16,3,1,'$2y$10$eeod5i6i6Fc1cl8HmQX7Su7BXOxRZBIUQKNXmmXS4lqpHKl.pqDj.'),(151515,'Danny','Ray','TM','2023-11-16',789456,5,1,1,0,1,'$2y$10$Yv.Cai/gGiGBVHrWhpiutekbs6rh751J5335Cosol.2w004Bx82BC'),(190290,'Cody','Catfish','TM','2023-11-16',290290,4,1,1,0,1,'$2y$10$jSQOw/CqYedeb.Y0dm4YJezfSteEm9St.UAfIkvLGBlstOMl2TxYO'),(232323,'Michael','Jordan','TM','2023-11-16',290290,4,1,1,0,1,'$2y$10$NK5mVD7i0xL0YylsYDNr7ehnasbduWz09IcHGs9/99bqiKoLxqxQS'),(239012,'Dusty','Baker','SUP','2023-11-16',901234,5,1,1,0,1,'$2y$10$eHsdjlSefJbbPaCaN8IR7e8yNyZkSqqTLeFiJfCdJhZS0aCzZX4ji'),(272727,'Jose','Altuve','TM','2023-11-16',239012,5,1,0,0,1,'$2y$10$wMC8eaILowx3nhU/6h5pceuMNmV9MvW1eskx58iUHnaSt6heiOitC'),(290290,'Thomas','Brady','SUP','2023-11-16',123456,4,1,1,0,1,'$2y$10$ZkoYEBvXHZQejI0CaUqFa.M4Ovz3HiYWsHy./a6gwDB1AWuj1zosS'),(456456,'Bucky','Barnes','TM','2023-11-16',561256,4,1,0,0,1,'$2y$10$sBt1A7s0QodAPlGItvdnkuHH8DXSsvH5lDafC0VGzUSKlyHKV8E0u'),(456789,'Super','Visor','SUP','2023-11-01',123456,2,0,6,0,1,'$2y$10$fCkXwnZvFfY9YTZ.qVq38eiqLoUdlNkp/TGliaE/qUTdZyQrdaviq'),(464646,'Crust','Commander','MAN','2023-11-16',12345678,3,1,1,0,1,'$2y$10$xKy1/Z9hSuq96JxBQn/KYuA53gfARzTTaeejSv6S9Je0TYWoxgwAy'),(561256,'Steve','Rogers','SUP','2023-11-16',123456,4,1,1,0,1,'$2y$10$qdhVpxlDwkZPxPeXi5Z/1egywCPpcindUhZr.4Ke.BeafUeOuISnO'),(696969,'Erik','Persons','TM','2023-11-16',101010,3,1,1,0,1,'$2y$10$QvWJ7lIGF4FvM8jR2X/s2ON7Y66ynx4MmK5aN9ChgIAOpznVK8vf6'),(781278,'Billy','Joe','TM','2023-11-16',111111,2,0,0,0,1,'$2y$10$UO66GemOYV2HHh7sqIPvn.A7nDwGheMqPTm.XQtAPZQymKJamGAfm'),(789456,'Bruce','Banner','SUP','2023-11-16',901234,5,1,1,0,1,'$2y$10$R7otoDci1qeCZmri6WA7De9c5gC/BtcCZI8RXWkgif.O5QqXTOij2'),(890123,'Dinku','Doolium','MAN','2023-11-08',12345678,2,0,1,0,1,'$2y$10$lzAvUY.G/UiwRe/.chcay..WntabzS/jIVC1wT3M1R7TxrPRqiFj2'),(892389,'Pizza','Prodigy','SUP','2023-11-16',464646,3,1,2,0,1,'$2y$10$EEKx6DEvT/yAw5QiPpFs1ORf./0qKybTsAUQKxublWp32z98aeMye'),(901234,'Calzone','Captain','MAN','2023-11-16',12345678,5,1,1,0,1,'$2y$10$r7E0tI2C0oRceHLNJ.Bs.Oqlv5gf37MHUzpclU/5ACJy9P6AIEium'),(2222222,'Bryant','Huynh','TM','2023-11-16',111111,2,0,0,0,0,'$2y$10$2YbyvUOlwVOs6tC3wsWLp.DTca8aiE49RLgcjyIyLOeJzUHKenB22'),(12345678,'Shasta','VII','CEO','2023-10-30',NULL,1,1,0,0,1,'$2y$10$Qu7aje81OeAFXv240e/kfu7CHjwu9ubpDLo0oCMsn448eJI7/oX1S'),(98765432,'Joe','Dirt','TM','2023-11-16',890123,2,0,4,0,0,'$2y$10$83wkNjxLF/O13knrv1GXD.rMePI..XooM09T7Qt.3RCpWwUas0Jh2');
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`danielgarza`@`%`*/ /*!50003 TRIGGER `employee_BEFORE_UPDATE` BEFORE UPDATE ON `employee` FOR EACH ROW BEGIN
    -- if employee is fired, then clock them out to prevent faulty assigned orders
    IF NEW.active_employee = 0 THEN
        SET NEW.clocked_in = 0;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `guest`
--

DROP TABLE IF EXISTS `guest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guest` (
  `Guest_ID` int NOT NULL AUTO_INCREMENT,
  `G_First_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `G_Last_Name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ' ',
  `G_Phone_Number` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`Guest_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guest`
--

LOCK TABLES `guest` WRITE;
/*!40000 ALTER TABLE `guest` DISABLE KEYS */;
INSERT INTO `guest` VALUES (46,'daniel','guest','1237891232'),(47,'delivery ','guest','1237891230'),(48,'aiden','hutch','1278128912'),(50,'shawn','ozzy','2109251289');
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
INSERT INTO `inventory` VALUES (5,2,654321,108.00,'2023-11-10','2023-12-10'),(5,3,123456,120.00,'2023-11-10','2023-12-10'),(5,4,987654,116.00,'2023-11-10','2023-12-10'),(5,5,123456,150.00,'2023-11-10','2023-12-10'),(6,2,765432,138.00,'2023-11-09','2023-12-09'),(6,3,765432,114.00,'2023-11-09','2023-12-09'),(6,4,765432,150.00,'2023-11-09','2023-12-09'),(6,5,876543,150.00,'2023-11-09','2023-12-09'),(7,2,123456,118.00,'2023-11-11','2024-02-09'),(7,3,543210,149.00,'2023-11-11','2024-02-09'),(7,4,543210,150.00,'2023-11-11','2024-02-09'),(7,5,543210,150.00,'2023-11-11','2024-02-09'),(8,2,543210,105.50,'2023-11-16','2023-11-21'),(8,3,987654,145.00,'2023-11-16','2023-11-21'),(8,4,765432,141.50,'2023-11-16','2023-11-21'),(8,5,987654,144.00,'2023-11-16','2023-11-21'),(9,2,654321,105.50,'2023-11-15','2023-11-25'),(9,3,543210,145.00,'2023-11-15','2023-11-25'),(9,4,765432,141.50,'2023-11-15','2023-11-25'),(9,5,543210,144.00,'2023-11-15','2023-11-25'),(10,2,765432,106.50,'2023-11-15','2023-11-25'),(10,3,765432,145.00,'2023-11-15','2023-11-25'),(10,4,765432,141.50,'2023-11-15','2023-11-25'),(10,5,765432,144.00,'2023-11-15','2023-11-25'),(15,2,987654,110.00,'2023-11-05','2023-12-25'),(15,3,543210,130.00,'2023-11-05','2023-12-25'),(15,4,654321,140.00,'2023-11-05','2023-12-25'),(15,5,876543,130.00,'2023-11-05','2023-12-25'),(16,2,654321,90.00,'2023-11-05','2023-12-25'),(16,3,987654,140.00,'2023-11-05','2023-12-25'),(16,4,987654,140.00,'2023-11-05','2023-12-25'),(16,5,123456,130.00,'2023-11-05','2023-12-25'),(17,2,654321,170.00,'2023-11-22','2023-12-07'),(17,3,654321,190.00,'2023-11-22','2023-12-07'),(17,4,654321,150.00,'2023-11-16','2023-12-01'),(17,5,765432,130.00,'2023-11-16','2023-12-01'),(18,2,123456,150.00,'2023-11-16','2023-12-01'),(18,3,543210,130.00,'2023-11-16','2023-12-01'),(18,4,654321,150.00,'2023-11-16','2023-12-01'),(18,5,987654,130.00,'2023-11-16','2023-12-01'),(19,2,765432,180.00,'2023-11-16','2023-12-01'),(19,3,876543,140.00,'2023-11-16','2023-12-01'),(19,4,123456,140.00,'2023-11-16','2023-12-01'),(19,5,543210,130.00,'2023-11-16','2023-12-01'),(20,2,543210,90.00,'2023-11-16','2023-12-01'),(20,3,123456,150.00,'2023-11-16','2023-12-01'),(20,4,543210,120.00,'2023-11-16','2023-12-01'),(20,5,987654,130.00,'2023-11-16','2023-12-01'),(21,2,543210,180.00,'2023-11-16','2023-12-02'),(21,3,765432,140.00,'2023-11-12','2023-12-02'),(21,4,765432,140.00,'2023-11-12','2023-12-02'),(21,5,654321,120.00,'2023-11-12','2023-12-02'),(22,2,654321,100.00,'2023-11-16','2023-12-01'),(22,3,987654,150.00,'2023-11-16','2023-12-01'),(22,4,654321,135.00,'2023-11-16','2023-12-01'),(22,5,543210,105.00,'2023-11-16','2023-12-01'),(23,2,123456,90.00,'2023-11-16','2023-12-01'),(23,3,543210,150.00,'2023-11-16','2023-12-01'),(23,4,765432,90.00,'2023-11-16','2023-12-01'),(23,5,876543,90.00,'2023-11-16','2023-12-01'),(24,2,765432,100.00,'2023-11-16','2023-12-01'),(24,3,765432,150.00,'2023-11-16','2023-12-01'),(24,4,876543,105.00,'2023-11-16','2023-12-01'),(24,5,123456,120.00,'2023-11-16','2023-12-01'),(25,2,654321,150.00,'2023-11-16','2023-12-01'),(25,3,543210,150.00,'2023-11-16','2023-12-01'),(25,4,654321,150.00,'2023-11-16','2023-12-01'),(25,5,765432,150.00,'2023-11-16','2023-12-01'),(26,2,543210,142.00,'2023-11-15','2023-11-23'),(26,3,765432,149.00,'2023-11-15','2023-11-23'),(26,4,543210,150.00,'2023-11-15','2023-11-23'),(26,5,765432,149.00,'2023-11-15','2023-11-23'),(27,2,876543,142.00,'2023-11-16','2023-12-01'),(27,3,876543,150.00,'2023-11-16','2023-12-01'),(27,4,876543,150.00,'2023-11-16','2023-12-01'),(27,5,765432,148.00,'2023-11-16','2023-12-01');
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`danielgarza`@`%`*/ /*!50003 TRIGGER `inventory_BEFORE_UPDATE` BEFORE UPDATE ON `inventory` FOR EACH ROW BEGIN
	-- event: inventory is updated
    DECLARE daysToExpire INT DEFAULT 0;
	
    -- condition: if last stock date is changed, 
    IF OLD.Last_Stock_Shipment_Date <> NEW.Last_Stock_Shipment_Date THEN
        -- get the item's days to expire number
        SELECT days_to_expire INTO daysToExpire
        FROM items
        WHERE Item_ID = NEW.Item_ID;

        IF daysToExpire IS NOT NULL THEN
			-- action: expiration date will update by adding daysToExpire to last stock date
            SET NEW.Expiration_Date = DATE_ADD(NEW.Last_Stock_Shipment_Date, INTERVAL daysToExpire DAY);
        END IF;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'Small Pizza','Pizza',7.99,1.75,NULL,NULL,NULL),(2,'Medium Pizza','Pizza',10.99,3.50,NULL,NULL,NULL),(3,'Large Pizza','Pizza',13.99,5.25,NULL,NULL,NULL),(4,'XL Pizza','Pizza',16.99,7.00,NULL,NULL,NULL),(5,'Garlic Stix','Side',5.99,3.50,100,30,4.00),(6,'Cinnamon Rolls','Side',5.29,1.50,100,30,6.00),(7,'Soda','Side',4.99,2.75,50,90,1.00),(8,'Dough','Ingredient',0.00,0.50,100,5,NULL),(9,'Cheese','Ingredient',0.00,1.00,100,10,NULL),(10,'Sauce','Ingredient',0.00,2.00,100,10,NULL),(11,'Extra Cheese','Topping',1.00,0.00,NULL,NULL,0.00),(12,'Extra Sauce','Topping',1.00,0.00,NULL,NULL,0.00),(13,'No Cheese','Topping',0.00,0.00,NULL,NULL,0.00),(14,'No Sauce','Topping',0.00,0.00,NULL,NULL,0.00),(15,'Pepperoni','Topping',1.25,0.40,100,50,10.00),(16,'Sausage','Topping',1.25,0.40,100,50,10.00),(17,'Beef','Topping',1.25,0.40,100,15,10.00),(18,'Ham','Topping',1.25,0.40,100,15,10.00),(19,'Garlic','Topping',0.25,0.10,100,15,5.00),(20,'Chicken','Topping',1.50,0.40,100,15,10.00),(21,'Bacon','Topping',1.50,0.80,100,20,10.00),(22,'Jalapeno','Topping',0.25,0.10,100,15,15.00),(23,'Pineapple','Topping',30.00,1.00,100,15,20.00),(24,'Black Olives','Topping',0.25,0.10,100,15,15.00),(25,'Garlic','Topping',0.25,0.10,100,15,5.00),(26,'Onions','Topping',0.65,0.00,100,8,1.00),(27,'Garlic Butter Crust','Topping',1.29,0.70,100,15,2.00);
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
INSERT INTO `menu` VALUES (1,800,'S',7.99,'img/cheese_pizza.jpeg','Small pizza for those who cant handle American portion sizes.','Small Pizza',1,1.75),(2,1200,'M',10.99,'img/cheese_pizza.jpeg','Normal pizza for a normal person.','Medium Pizza',1,3.50),(3,1750,'L',13.99,'img/cheese_pizza.jpeg','Large pizza, so personal sized for Americans.','Large Pizza',1,5.25),(4,2100,'X',16.99,'img/cheese_pizza.jpeg','Family sized, or personal sized for an athlete.','XL Pizza',1,7.00),(5,725,'M',5.99,'img/stix.jpg','Four count garlic bread sticks.','Garlic Stix',0,3.50),(6,210,'L',4.99,'img/SODA.jpg','Large drink for a large human.','Soda',0,2.75),(7,820,'M',5.29,'img/dessert.jpg','High profit margin for us. Delicious for you.','Cinnamon Rolls',0,1.50);
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
) ENGINE=InnoDB AUTO_INCREMENT=486 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,5,'Garlic Stix',5.99,'2023-11-16'),(2,1,15,'Pepperoni',1.25,'2023-11-16'),(3,1,21,'Bacon',1.50,'2023-11-16'),(233,2,5,'Garlic Stix',5.99,'2023-11-16'),(234,2,2,'Medium Pizza',10.99,'2023-11-16'),(235,2,15,'Pepperoni',1.25,'2023-11-16'),(236,2,16,'Sausage',1.25,'2023-11-16'),(237,2,17,'Beef',1.25,'2023-11-16'),(238,2,18,'Ham',1.25,'2023-11-16'),(239,3,14,'No Sauce',0.00,'2023-11-16'),(240,3,1,'Small Pizza',7.99,'2023-11-16'),(241,3,4,'XL Pizza',16.99,'2023-11-16'),(242,3,15,'Pepperoni',1.25,'2023-11-16'),(243,3,16,'Sausage',1.25,'2023-11-16'),(244,3,17,'Beef',1.25,'2023-11-16'),(245,3,22,'Jalapeno',0.25,'2023-11-16'),(246,3,24,'Black Olives',0.25,'2023-11-16'),(247,4,2,'Medium Pizza',10.99,'2023-11-16'),(248,4,18,'Ham',1.25,'2023-11-16'),(249,4,19,'Garlic',0.25,'2023-11-16'),(250,4,21,'Bacon',1.50,'2023-11-16'),(251,4,26,'Onions',0.65,'2023-11-16'),(252,5,5,'Garlic Stix',5.99,'2023-11-16'),(253,5,6,'Soda',4.99,'2023-11-16'),(254,5,3,'Large Pizza',13.99,'2023-11-16'),(255,5,15,'Pepperoni',1.25,'2023-11-16'),(256,6,6,'Soda',4.99,'2023-11-16'),(257,6,1,'Small Pizza',7.99,'2023-11-16'),(258,6,15,'Pepperoni',1.25,'2023-11-16'),(259,6,21,'Bacon',1.50,'2023-11-16'),(260,6,22,'Jalapeno',0.25,'2023-11-16'),(261,6,23,'Pineapple',30.00,'2023-11-16'),(262,7,1,'Small Pizza',7.99,'2023-11-16'),(263,7,15,'Pepperoni',1.25,'2023-11-16'),(264,7,16,'Sausage',1.25,'2023-11-16'),(265,7,17,'Beef',1.25,'2023-11-16'),(266,8,1,'Small Pizza',7.99,'2023-11-16'),(267,8,15,'Pepperoni',1.25,'2023-11-16'),(268,8,16,'Sausage',1.25,'2023-11-16'),(269,8,17,'Beef',1.25,'2023-11-16'),(270,8,18,'Ham',1.25,'2023-11-16'),(271,8,19,'Garlic',0.25,'2023-11-16'),(272,9,5,'Garlic Stix',5.99,'2023-11-16'),(273,9,4,'XL Pizza',16.99,'2023-11-16'),(274,10,3,'Large Pizza',13.99,'2023-11-16'),(275,10,16,'Sausage',1.25,'2023-11-16'),(276,10,17,'Beef',1.25,'2023-11-16'),(277,10,18,'Ham',1.25,'2023-11-16'),(278,10,19,'Garlic',0.25,'2023-11-16'),(279,10,20,'Chicken',1.50,'2023-11-16'),(280,11,7,'Cinnamon Rolls',5.29,'2023-11-16'),(281,11,2,'Medium Pizza',10.99,'2023-11-16'),(282,11,21,'Bacon',1.50,'2023-11-16'),(283,11,22,'Jalapeno',0.25,'2023-11-16'),(284,11,23,'Pineapple',30.00,'2023-11-16'),(285,11,24,'Black Olives',0.25,'2023-11-16'),(286,11,19,'Garlic',0.25,'2023-11-16'),(287,12,1,'Small Pizza',7.99,'2023-11-16'),(288,12,22,'Jalapeno',0.25,'2023-11-16'),(289,12,23,'Pineapple',30.00,'2023-11-16'),(290,12,24,'Black Olives',0.25,'2023-11-16'),(291,12,19,'Garlic',0.25,'2023-11-16'),(292,12,26,'Onions',0.65,'2023-11-16'),(293,12,27,'Garlic Butter Crust',1.29,'2023-11-16'),(294,13,5,'Garlic Stix',5.99,'2023-11-16'),(295,13,6,'Soda',4.99,'2023-11-16'),(296,13,7,'Cinnamon Rolls',5.29,'2023-11-16'),(297,14,4,'XL Pizza',16.99,'2023-11-16'),(298,15,3,'Large Pizza',13.99,'2023-11-16'),(299,15,17,'Beef',1.25,'2023-11-16'),(300,15,20,'Chicken',1.50,'2023-11-16'),(301,15,21,'Bacon',1.50,'2023-11-16'),(302,15,22,'Jalapeno',0.25,'2023-11-16'),(303,15,23,'Pineapple',30.00,'2023-11-16'),(304,19,4,'XL Pizza',16.99,'2023-11-16'),(305,19,16,'Sausage',1.25,'2023-11-16'),(306,19,19,'Garlic',0.25,'2023-11-16'),(307,19,20,'Chicken',1.50,'2023-11-16'),(308,19,23,'Pineapple',30.00,'2023-11-16'),(309,19,24,'Black Olives',0.25,'2023-11-16'),(310,19,2,'Medium Pizza',10.99,'2023-11-16'),(311,19,19,'Garlic',0.25,'2023-11-16'),(312,19,20,'Chicken',1.50,'2023-11-16'),(313,19,23,'Pineapple',30.00,'2023-11-16'),(314,19,24,'Black Olives',0.25,'2023-11-16'),(315,19,5,'Garlic Stix',5.99,'2023-11-16'),(316,19,6,'Soda',4.99,'2023-11-16'),(317,19,7,'Cinnamon Rolls',5.29,'2023-11-16'),(318,20,5,'Garlic Stix',5.99,'2023-11-16'),(319,20,5,'Garlic Stix',5.99,'2023-11-16'),(320,20,5,'Garlic Stix',5.99,'2023-11-16'),(321,21,2,'Medium Pizza',10.99,'2023-11-16'),(322,21,17,'Beef',1.25,'2023-11-16'),(323,21,20,'Chicken',1.50,'2023-11-16'),(324,21,24,'Black Olives',0.25,'2023-11-16'),(325,21,1,'Small Pizza',7.99,'2023-11-16'),(326,21,15,'Pepperoni',1.25,'2023-11-16'),(327,21,20,'Chicken',1.50,'2023-11-16'),(328,21,6,'Soda',4.99,'2023-11-16'),(329,21,7,'Cinnamon Rolls',5.29,'2023-11-16'),(330,22,5,'Garlic Stix',5.99,'2023-11-16'),(331,22,7,'Cinnamon Rolls',5.29,'2023-11-16'),(332,22,6,'Soda',4.99,'2023-11-16'),(333,22,4,'XL Pizza',16.99,'2023-11-16'),(334,22,20,'Chicken',1.50,'2023-11-16'),(335,23,4,'XL Pizza',16.99,'2023-11-16'),(336,23,21,'Bacon',1.50,'2023-11-16'),(337,24,5,'Garlic Stix',5.99,'2023-11-16'),(338,24,6,'Soda',4.99,'2023-11-16'),(339,24,2,'Medium Pizza',10.99,'2023-11-16'),(340,24,16,'Sausage',1.25,'2023-11-16'),(341,25,2,'Medium Pizza',10.99,'2023-11-16'),(342,25,19,'Garlic',0.25,'2023-11-16'),(343,25,20,'Chicken',1.50,'2023-11-16'),(344,26,4,'XL Pizza',16.99,'2023-11-16'),(345,26,20,'Chicken',1.50,'2023-11-16'),(346,26,21,'Bacon',1.50,'2023-11-16'),(347,26,22,'Jalapeno',0.25,'2023-11-16'),(348,26,23,'Pineapple',30.00,'2023-11-16'),(349,26,24,'Black Olives',0.25,'2023-11-16'),(350,27,5,'Garlic Stix',5.99,'2023-11-16'),(351,27,6,'Soda',4.99,'2023-11-16'),(352,28,4,'XL Pizza',16.99,'2023-11-16'),(353,28,19,'Garlic',0.25,'2023-11-16'),(354,28,21,'Bacon',1.50,'2023-11-16'),(355,28,22,'Jalapeno',0.25,'2023-11-16'),(356,28,19,'Garlic',0.25,'2023-11-16'),(357,28,26,'Onions',0.65,'2023-11-16'),(358,29,3,'Large Pizza',13.99,'2023-11-16'),(359,29,21,'Bacon',1.50,'2023-11-16'),(360,29,22,'Jalapeno',0.25,'2023-11-16'),(361,29,23,'Pineapple',30.00,'2023-11-16'),(362,29,24,'Black Olives',0.25,'2023-11-16'),(363,29,3,'Large Pizza',13.99,'2023-11-16'),(364,29,19,'Garlic',0.25,'2023-11-16'),(365,29,22,'Jalapeno',0.25,'2023-11-16'),(366,29,24,'Black Olives',0.25,'2023-11-16'),(367,29,19,'Garlic',0.25,'2023-11-16'),(368,29,5,'Garlic Stix',5.99,'2023-11-16'),(369,30,1,'Small Pizza',7.99,'2023-11-16'),(370,30,19,'Garlic',0.25,'2023-11-16'),(371,30,26,'Onions',0.65,'2023-11-16'),(372,30,27,'Garlic Butter Crust',1.29,'2023-11-16'),(373,31,2,'Medium Pizza',10.99,'2023-11-16'),(374,31,23,'Pineapple',30.00,'2023-11-16'),(375,31,24,'Black Olives',0.25,'2023-11-16'),(376,31,19,'Garlic',0.25,'2023-11-16'),(377,31,26,'Onions',0.65,'2023-11-16'),(378,31,5,'Garlic Stix',5.99,'2023-11-16'),(379,31,5,'Garlic Stix',5.99,'2023-11-16'),(380,31,5,'Garlic Stix',5.99,'2023-11-16'),(381,32,7,'Cinnamon Rolls',5.29,'2023-11-16'),(382,32,6,'Soda',4.99,'2023-11-16'),(383,32,1,'Small Pizza',7.99,'2023-11-16'),(384,32,19,'Garlic',0.25,'2023-11-16'),(385,32,26,'Onions',0.65,'2023-11-16'),(386,32,27,'Garlic Butter Crust',1.29,'2023-11-16'),(387,32,1,'Small Pizza',7.99,'2023-11-16'),(388,32,22,'Jalapeno',0.25,'2023-11-16'),(389,32,23,'Pineapple',30.00,'2023-11-16'),(390,32,24,'Black Olives',0.25,'2023-11-16'),(391,32,19,'Garlic',0.25,'2023-11-16'),(392,32,26,'Onions',0.65,'2023-11-16'),(393,32,27,'Garlic Butter Crust',1.29,'2023-11-16'),(394,33,4,'XL Pizza',16.99,'2023-11-16'),(395,33,21,'Bacon',1.50,'2023-11-16'),(396,33,22,'Jalapeno',0.25,'2023-11-16'),(397,33,23,'Pineapple',30.00,'2023-11-16'),(398,33,24,'Black Olives',0.25,'2023-11-16'),(399,33,19,'Garlic',0.25,'2023-11-16'),(400,34,2,'Medium Pizza',10.99,'2023-11-16'),(401,34,19,'Garlic',0.25,'2023-11-16'),(402,34,21,'Bacon',1.50,'2023-11-16'),(403,34,22,'Jalapeno',0.25,'2023-11-16'),(404,34,23,'Pineapple',30.00,'2023-11-16'),(405,34,24,'Black Olives',0.25,'2023-11-16'),(406,35,4,'XL Pizza',16.99,'2023-11-16'),(407,35,22,'Jalapeno',0.25,'2023-11-16'),(408,35,24,'Black Olives',0.25,'2023-11-16'),(409,35,26,'Onions',0.65,'2023-11-16'),(410,35,5,'Garlic Stix',5.99,'2023-11-16'),(411,35,6,'Soda',4.99,'2023-11-16'),(412,35,7,'Cinnamon Rolls',5.29,'2023-11-16'),(413,36,2,'Medium Pizza',10.99,'2023-11-16'),(414,36,16,'Sausage',1.25,'2023-11-16'),(415,36,17,'Beef',1.25,'2023-11-16'),(416,36,19,'Garlic',0.25,'2023-11-16'),(417,37,4,'XL Pizza',16.99,'2023-11-16'),(418,37,15,'Pepperoni',1.25,'2023-11-16'),(419,37,16,'Sausage',1.25,'2023-11-16'),(420,37,17,'Beef',1.25,'2023-11-16'),(421,37,18,'Ham',1.25,'2023-11-16'),(422,37,19,'Garlic',0.25,'2023-11-16'),(423,37,20,'Chicken',1.50,'2023-11-16'),(424,37,21,'Bacon',1.50,'2023-11-16'),(425,37,22,'Jalapeno',0.25,'2023-11-16'),(426,37,23,'Pineapple',30.00,'2023-11-16'),(427,37,24,'Black Olives',0.25,'2023-11-16'),(428,37,19,'Garlic',0.25,'2023-11-16'),(429,37,26,'Onions',0.65,'2023-11-16'),(430,37,27,'Garlic Butter Crust',1.29,'2023-11-16'),(431,37,6,'Soda',4.99,'2023-11-16'),(432,37,5,'Garlic Stix',5.99,'2023-11-16'),(433,37,7,'Cinnamon Rolls',5.29,'2023-11-16'),(434,38,2,'Medium Pizza',10.99,'2023-11-16'),(435,38,17,'Beef',1.25,'2023-11-16'),(436,39,2,'Medium Pizza',10.99,'2023-11-16'),(437,39,17,'Beef',1.25,'2023-11-16'),(438,40,2,'Medium Pizza',10.99,'2023-11-16'),(439,40,17,'Beef',1.25,'2023-11-16'),(440,41,3,'Large Pizza',13.99,'2023-11-16'),(441,41,21,'Bacon',1.50,'2023-11-16'),(442,42,4,'XL Pizza',16.99,'2023-11-16'),(443,42,15,'Pepperoni',1.25,'2023-11-16'),(444,42,16,'Sausage',1.25,'2023-11-16'),(445,42,19,'Garlic',0.25,'2023-11-16'),(446,42,20,'Chicken',1.50,'2023-11-16'),(447,42,23,'Pineapple',30.00,'2023-11-16'),(448,42,5,'Garlic Stix',5.99,'2023-11-16'),(449,42,6,'Soda',4.99,'2023-11-16'),(450,42,7,'Cinnamon Rolls',5.29,'2023-11-16'),(451,43,2,'Medium Pizza',10.99,'2023-11-16'),(452,43,17,'Beef',1.25,'2023-11-16'),(453,44,2,'Medium Pizza',10.99,'2023-11-16'),(454,44,17,'Beef',1.25,'2023-11-16'),(455,45,1,'Small Pizza',7.99,'2023-11-17'),(456,45,15,'Pepperoni',1.25,'2023-11-17'),(457,47,2,'Medium Pizza',10.99,'2023-11-22'),(458,47,6,'Soda',4.99,'2023-11-22'),(459,48,2,'Medium Pizza',10.99,'2023-11-22'),(460,48,17,'Beef',1.25,'2023-11-22'),(461,49,2,'Medium Pizza',10.99,'2023-11-22'),(462,49,17,'Beef',1.25,'2023-11-22'),(463,51,1,'Small Pizza',7.99,'2023-11-22'),(464,51,1,'Small Pizza',7.99,'2023-11-22'),(465,51,17,'Beef',1.25,'2023-11-22'),(466,52,4,'XL Pizza',16.99,'2023-11-22'),(467,52,17,'Beef',1.25,'2023-11-22'),(468,53,2,'Medium Pizza',10.99,'2023-11-22'),(469,53,17,'Beef',1.25,'2023-11-22'),(477,77,5,'Garlic Stix',5.99,'2023-11-23'),(478,77,6,'Soda',4.99,'2023-11-23'),(479,77,7,'Cinnamon Rolls',5.29,'2023-11-23'),(480,79,5,'Garlic Stix',5.99,'2023-11-23'),(481,80,5,'Garlic Stix',5.99,'2023-11-23'),(482,81,6,'Soda',4.99,'2023-11-23'),(483,81,7,'Cinnamon Rolls',5.29,'2023-11-23'),(484,82,1,'Small Pizza',7.99,'2023-11-24'),(485,82,26,'Onions',0.65,'2023-11-24');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`danielgarza`@`%`*/ /*!50003 TRIGGER `order_items_AFTER_INSERT` AFTER INSERT ON `order_items` FOR EACH ROW BEGIN
	-- event: order placed
    DECLARE reorderThreshold INT;
    DECLARE currentInventory INT;

	-- gets threshold for item
	SELECT Reorder_Threshold INTO reorderThreshold
	FROM items
	WHERE Item_ID = NEW.Item_ID;

	-- gets current inventory for item
	SELECT Inventory_Amount INTO currentInventory
	FROM inventory
	WHERE Item_ID = NEW.Item_ID AND Store_ID = (SELECT Store_ID FROM orders WHERE Order_ID = NEW.Order_ID);

	-- condition: checks if current inventory is below the threshold
	IF currentInventory < reorderThreshold THEN
        UPDATE inventory
        -- action: add inventory
		SET Inventory_Amount = Inventory_Amount + 100,
			Last_Stock_Shipment_date = NOW()
		WHERE Item_ID = NEW.Item_ID AND Store_ID = (SELECT Store_ID FROM orders WHERE Order_ID = NEW.Order_ID);
	END IF;
    
    -- conidtion: if item is a pizza, check dough, sauce, and cheese
    IF (SELECT Item_Type FROM items WHERE Item_ID = NEW.Item_ID) = 'Pizza' THEN
        -- action: check/update dough, sauce, cheese
        
		-- Check Dough inventory
		UPDATE inventory
		SET Inventory_Amount = CASE
				WHEN Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Dough') AND Inventory_Amount < (SELECT Reorder_Threshold FROM items WHERE Item_Name = 'Dough')
				THEN Inventory_Amount + 50
				ELSE Inventory_Amount
			END,
			Last_Stock_Shipment_date = CASE
				WHEN Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Dough') AND Inventory_Amount < (SELECT Reorder_Threshold FROM items WHERE Item_Name = 'Dough')
				THEN NOW()
				ELSE Last_Stock_Shipment_date
			END
		WHERE Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Dough') AND Store_ID = (SELECT Store_ID FROM orders WHERE Order_ID = NEW.Order_ID);

		-- Check Sauce inventory
		UPDATE inventory
		SET Inventory_Amount = CASE
				WHEN Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Sauce') AND Inventory_Amount < (SELECT Reorder_Threshold FROM items WHERE Item_Name = 'Sauce')
				THEN Inventory_Amount + 50
				ELSE Inventory_Amount
			END,
			Last_Stock_Shipment_date = CASE
				WHEN Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Sauce') AND Inventory_Amount < (SELECT Reorder_Threshold FROM items WHERE Item_Name = 'Sauce')
				THEN NOW()
				ELSE Last_Stock_Shipment_date
			END
		WHERE Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Sauce') AND Store_ID = (SELECT Store_ID FROM orders WHERE Order_ID = NEW.Order_ID);

		-- Check Cheese inventory
		UPDATE inventory
		SET Inventory_Amount = CASE
				WHEN Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Cheese') AND Inventory_Amount < (SELECT Reorder_Threshold FROM items WHERE Item_Name = 'Cheese')
				THEN Inventory_Amount + 50
				ELSE Inventory_Amount
			END,
			Last_Stock_Shipment_date = CASE
				WHEN Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Cheese') AND Inventory_Amount < (SELECT Reorder_Threshold FROM items WHERE Item_Name = 'Cheese')
				THEN NOW()
				ELSE Last_Stock_Shipment_date
			END
		WHERE Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Cheese') AND Store_ID = (SELECT Store_ID FROM orders WHERE Order_ID = NEW.Order_ID);

    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
  `Customer_ID` int DEFAULT NULL,
  `Guest_ID` int DEFAULT NULL,
  `Store_ID` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Order_ID`),
  KEY `ref_pizza_store_idx` (`Store_ID`),
  KEY `ref_employeeOrders_idx` (`Employee_ID_assigned`),
  KEY `ref_customers_idx` (`Customer_ID`),
  KEY `ref_guest_idx` (`Guest_ID`),
  CONSTRAINT `ref_customers` FOREIGN KEY (`Customer_ID`) REFERENCES `customers` (`customer_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `ref_employeeOrders` FOREIGN KEY (`Employee_ID_assigned`) REFERENCES `employee` (`Employee_ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `ref_guest` FOREIGN KEY (`Guest_ID`) REFERENCES `guest` (`Guest_ID`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `ref_pizza_store` FOREIGN KEY (`Store_ID`) REFERENCES `pizza_store` (`Pizza_Store_ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'2023-11-16','00:04:02','13:13:12','Delivery','Completed',22.73,15.70,111111,2,NULL,2),(2,'2023-11-16','06:58:08','16:18:33','Pickup','Completed',31.98,8.60,123789,14,NULL,2),(3,'2023-11-16','08:25:40','00:00:00','Pickup','In Progress',33.23,10.15,781278,2,NULL,2),(4,'2023-11-16','08:28:34','00:00:00','Delivery','In Progress',18.89,4.90,121212,6,NULL,3),(5,'2023-11-16','08:31:49','00:00:00','Pickup','In Progress',38.22,11.90,190290,7,NULL,4),(6,'2023-11-16','08:32:40','00:00:00','Pickup','In Progress',57.98,6.80,151515,8,NULL,5),(7,'2023-11-16','11:34:55','00:00:00','Delivery','In Progress',12.74,2.95,456789,14,NULL,2),(8,'2023-11-16','12:05:15','00:00:00','Delivery','In Progress',25.49,3.55,892389,14,NULL,3),(9,'2023-11-16','12:05:48','00:00:00','Pickup','In Progress',34.98,10.50,561256,14,NULL,4),(10,'2023-11-16','12:06:21','00:00:00','Delivery','In Progress',10.74,7.05,789456,14,NULL,5),(11,'2023-11-16','12:06:49','13:13:16','Delivery','Completed',60.78,7.20,111111,14,NULL,2),(12,'2023-11-16','12:07:15','16:18:36','Pickup','Completed',41.93,3.85,123789,14,NULL,2),(13,'2023-11-16','12:07:40','00:00:00','Pickup','In Progress',7.27,7.75,781278,14,NULL,2),(14,'2023-11-16','12:08:02','00:00:00','Delivery','In Progress',18.99,7.00,121212,14,NULL,3),(15,'2023-11-16','12:09:50','00:00:00','Delivery','In Progress',49.49,7.95,456789,14,NULL,2),(19,'2023-11-16','12:17:44','00:00:00','Delivery','In Progress',117.00,22.05,190290,14,NULL,4),(20,'2023-11-16','12:18:32','00:00:00','Delivery','In Progress',0.97,10.50,111111,14,NULL,2),(21,'2023-11-16','12:20:20','00:00:00','Pickup','In Progress',44.01,11.20,123789,14,NULL,2),(22,'2023-11-16','12:20:56','00:00:00','Delivery','In Progress',38.76,15.15,456789,14,NULL,2),(23,'2023-11-16','12:21:38','00:00:00','Pickup','In Progress',20.49,7.80,111111,14,NULL,2),(24,'2023-11-16','12:22:03','00:00:00','Pickup','In Progress',14.22,10.15,123789,14,NULL,2),(25,'2023-11-16','12:23:11','00:00:00','Delivery','In Progress',16.99,4.10,781278,14,NULL,2),(26,'2023-11-16','12:26:06','00:00:00','Delivery','In Progress',51.49,9.40,232323,14,NULL,4),(27,'2023-11-16','12:26:37','00:00:00','Delivery','In Progress',11.98,6.25,123789,14,NULL,2),(28,'2023-11-16','12:27:10','00:00:00','Pickup','In Progress',33.39,8.30,456789,14,NULL,2),(29,'2023-11-16','12:27:41','00:00:00','Pickup','In Progress',68.47,16.60,111111,14,NULL,2),(30,'2023-11-16','12:28:28','00:00:00','Delivery','In Progress',1.43,2.65,781278,14,NULL,2),(31,'2023-11-16','12:29:06','00:00:00','Delivery','In Progress',72.36,15.30,123789,14,NULL,2),(32,'2023-11-16','12:29:48','00:00:00','Delivery','In Progress',62.64,10.75,456789,14,NULL,2),(33,'2023-11-16','12:30:16','00:00:00','Delivery','In Progress',40.49,9.20,239012,14,NULL,5),(34,'2023-11-16','13:20:37','00:00:00','Pickup','In Progress',44.49,5.70,123789,14,NULL,2),(35,'2023-11-16','13:31:30','00:00:00','Pickup','In Progress',36.41,14.95,111111,14,NULL,2),(36,'2023-11-16','13:57:15','00:00:00','Delivery','In Progress',15.99,4.50,111111,16,NULL,2),(37,'2023-11-16','15:45:56','00:00:00','Pickup','In Progress',79.70,19.85,901234,17,NULL,5),(38,'2023-11-16','16:28:05','00:00:00','Delivery','In Progress',17.24,3.90,111111,2,NULL,2),(39,'2023-11-16','16:29:30','00:00:00','Pickup','In Progress',17.24,3.90,890123,2,NULL,2),(40,'2023-11-16','16:37:51','00:00:00','Delivery','In Progress',62.24,3.90,456789,2,NULL,2),(41,'2023-11-16','17:31:08','00:00:00','Pickup','In Progress',6.49,6.05,123789,2,NULL,2),(42,'2023-11-16','18:26:59','18:32:29','Pickup','Completed',77.76,17.15,123789,18,NULL,2),(43,'2023-11-16','18:29:39','00:00:00','Pickup','In Progress',32.24,3.90,123789,18,NULL,2),(44,'2023-11-16','18:31:27','00:00:00','Delivery','In Progress',14.24,3.90,123789,18,NULL,2),(45,'2023-11-17','14:00:52','00:00:00','Pickup','In Progress',12.24,2.15,464646,17,NULL,3),(47,'2023-11-22','00:19:05','00:00:00','Pickup','In Progress',19.98,6.25,123789,2,NULL,2),(48,'2023-11-22','12:20:08','00:00:00','Pickup','In Progress',17.24,3.90,123789,2,NULL,2),(49,'2023-11-22','12:21:12','00:00:00','Delivery','In Progress',15.24,3.90,123789,2,NULL,2),(50,'2023-11-22','12:25:13','00:00:00','Pickup','In Progress',6.24,5.65,123789,2,NULL,2),(51,'2023-11-22','12:28:19','00:00:00','Pickup','In Progress',18.23,3.90,123789,2,NULL,2),(52,'2023-11-22','14:09:16','00:00:00','Delivery','In Progress',21.24,7.40,123789,2,NULL,2),(53,'2023-11-22','14:14:29','00:00:00','Delivery','In Progress',13.24,3.90,696969,2,NULL,3),(77,'2023-11-23','06:00:08','00:00:00','Delivery','In Progress',28.27,7.75,111111,2,NULL,2),(79,'2023-11-23','06:44:53','06:49:29','Pickup','Completed',8.99,3.50,111111,NULL,50,2),(80,'2023-11-23','06:54:22','00:00:00','Pickup','In Progress',10.99,3.50,290290,8,NULL,4),(81,'2023-11-23','06:55:43','00:00:00','Delivery','In Progress',13.28,4.25,892389,8,NULL,3),(82,'2023-11-24','09:27:29','00:00:00','Pickup','In Progress',13.64,1.75,111111,8,NULL,2);
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
INSERT INTO `pickup` VALUES (2,'2023-11-16','06:58:08','16:18:33',890123,'abc','sbss'),(3,'2023-11-16','08:25:40','00:00:00',123789,'Daniel','Garza'),(5,'2023-11-16','08:31:49','00:00:00',123456,'tom','jerry'),(6,'2023-11-16','08:32:40','00:00:00',151515,'jose','altuve'),(9,'2023-11-16','12:05:48','00:00:00',561256,'abc','sbss'),(12,'2023-11-16','12:07:15','16:18:36',890123,'abc','sbss'),(13,'2023-11-16','12:07:40','00:00:00',123789,'abc','sbss'),(21,'2023-11-16','12:20:20','00:00:00',890123,'abc','sbss'),(23,'2023-11-16','12:21:38','00:00:00',456789,'abc','sbss'),(24,'2023-11-16','12:22:03','00:00:00',111111,'abc','sbss'),(28,'2023-11-16','12:27:10','00:00:00',456789,'abc','sbss'),(29,'2023-11-16','12:27:41','00:00:00',111111,'abc','sbss'),(34,'2023-11-16','13:20:37','00:00:00',123789,'abc','sbss'),(35,'2023-11-16','13:31:30','00:00:00',890123,'abc','sbss'),(37,'2023-11-16','15:45:56','00:00:00',901234,'David','Cooper'),(39,'2023-11-16','16:29:30','00:00:00',890123,'Danny','Boy'),(41,'2023-11-16','17:31:08','00:00:00',123789,'Danny','Boy'),(42,'2023-11-16','18:26:59','18:32:29',123789,'Eric','Par'),(43,'2023-11-16','18:29:39','00:00:00',123789,'Eric','Par'),(45,'2023-11-17','14:00:52','00:00:00',464646,'David','Cooper'),(47,'2023-11-22','00:19:05','00:00:00',123789,'Danny','Boy'),(48,'2023-11-22','12:20:08','00:00:00',123789,'Danny','Boy'),(50,'2023-11-22','12:25:13','00:00:00',123789,'Danny','Boy'),(51,'2023-11-22','12:28:19','00:00:00',123789,'Danny','Boy'),(79,'2023-11-23','06:44:53','06:49:29',111111,'shawn','ozzy'),(80,'2023-11-23','06:54:22','00:00:00',290290,'jose','altuve'),(82,'2023-11-24','09:27:29','00:00:00',111111,'jose','altuve');
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
INSERT INTO `pizza_store` VALUES (1,12345678,'123 UH Drive','Houston','TX','77204','2813308004'),(2,890123,'789 Cougar Dr','Houston','TX','77024','2817893785'),(3,464646,'1711 Chicken Nug Rd','Houston','TX','77011','7131231234'),(4,123456,'486 Cheesy Ln','Houston','TX','77012','2810910293'),(5,901234,'281 Pizzeria Pkwy','Houston','TX','77007','2813841092');
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
INSERT INTO `transactions` VALUES (1,22.73,3.00,'Credit Card','2023-11-16','00:04:02'),(2,31.98,10.00,'Credit Card','2023-11-16','06:58:08'),(3,33.23,4.00,'Credit Card','2023-11-16','08:25:40'),(4,18.89,4.00,'Bitcoin','2023-11-16','08:28:34'),(5,38.22,12.00,'Credit Card','2023-11-16','08:31:49'),(6,57.98,12.00,'Bitcoin','2023-11-16','08:32:40'),(7,12.74,1.00,'Cash','2023-11-16','11:34:55'),(8,25.49,12.00,'Credit Card','2023-11-16','12:05:15'),(9,34.98,12.00,'Cash','2023-11-16','12:05:48'),(10,10.74,1.00,'Credit Card','2023-11-16','12:06:21'),(11,60.78,12.00,'Cash','2023-11-16','12:06:49'),(12,41.93,1.00,'Credit Card','2023-11-16','12:07:15'),(13,7.27,1.00,'Credit Card','2023-11-16','12:07:40'),(14,18.99,2.00,'Credit Card','2023-11-16','12:08:02'),(15,49.49,1.00,'Bitcoin','2023-11-16','12:09:50'),(19,117.00,7.00,'Cash','2023-11-16','12:17:44'),(20,0.97,3.00,'Credit Card','2023-11-16','12:18:32'),(21,44.01,9.00,'Credit Card','2023-11-16','12:20:20'),(22,38.76,4.00,'Cash','2023-11-16','12:20:56'),(23,20.49,2.00,'Credit Card','2023-11-16','12:21:38'),(24,14.22,1.00,'Cash','2023-11-16','12:22:03'),(25,16.99,4.00,'Bitcoin','2023-11-16','12:23:11'),(26,51.49,1.00,'Credit Card','2023-11-16','12:26:06'),(27,11.98,1.00,'Bitcoin','2023-11-16','12:26:37'),(28,33.39,23.00,'Credit Card','2023-11-16','12:27:10'),(29,68.47,1.00,'Cash','2023-11-16','12:27:41'),(30,1.43,1.00,'Credit Card','2023-11-16','12:28:28'),(31,72.36,12.00,'Bitcoin','2023-11-16','12:29:06'),(32,62.64,1.00,'Credit Card','2023-11-16','12:29:48'),(33,40.49,1.00,'Cash','2023-11-16','12:30:16'),(34,44.49,1.00,'Credit Card','2023-11-16','13:20:37'),(35,36.41,12.00,'Cash','2023-11-16','13:31:30'),(36,15.99,2.00,'Bitcoin','2023-11-16','13:57:15'),(37,79.70,5.00,'Bitcoin','2023-11-16','15:45:56'),(38,17.24,5.00,'Cash','2023-11-16','16:28:05'),(39,17.24,5.00,'Cash','2023-11-16','16:29:30'),(40,62.24,50.00,'Cash','2023-11-16','16:37:51'),(41,6.49,1.00,'Cash','2023-11-16','17:31:08'),(42,77.76,10.00,'V-Bucks','2023-11-16','18:26:59'),(43,32.24,20.00,'Cash','2023-11-16','18:29:39'),(44,14.24,12.00,'Credit Card','2023-11-16','18:31:27'),(45,12.24,3.00,'Bitcoin','2023-11-17','14:00:52'),(47,19.98,4.00,'Cash','2023-11-22','00:19:05'),(48,17.24,5.00,'Credit Card','2023-11-22','12:20:08'),(49,15.24,3.00,'Cash','2023-11-22','12:21:12'),(50,6.24,1.00,'Credit Card','2023-11-22','12:25:13'),(51,18.23,1.00,'Bitcoin','2023-11-22','12:28:19'),(52,21.24,3.00,'Credit Card','2023-11-22','14:09:16'),(53,13.24,1.00,'Cash','2023-11-22','14:14:29'),(77,28.27,12.00,'Credit Card','2023-11-23','06:00:08'),(79,8.99,3.00,'Credit Card','2023-11-23','06:44:53'),(80,10.99,5.00,'Bitcoin','2023-11-23','06:54:22'),(81,13.28,3.00,'Bitcoin','2023-11-23','06:55:43'),(82,13.64,5.00,'Credit Card','2023-11-24','09:27:29');
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

-- Dump completed on 2023-11-24  9:44:33
