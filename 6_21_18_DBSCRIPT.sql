-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: raihn
-- ------------------------------------------------------
-- Server version	5.5.56-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `BUS_BLACKOUT`
--

DROP TABLE IF EXISTS `BUS_BLACKOUT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BUS_BLACKOUT` (
  `driverID` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  `timeOfDay` char(2) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`driverID`,`date`,`timeOfDay`),
  CONSTRAINT `BUS_BLACKOUT_BUS_DRIVER_driverID` FOREIGN KEY (`driverID`) REFERENCES `BUS_DRIVER` (`driverID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BUS_BLACKOUT`
--

LOCK TABLES `BUS_BLACKOUT` WRITE;
/*!40000 ALTER TABLE `BUS_BLACKOUT` DISABLE KEYS */;
INSERT INTO `BUS_BLACKOUT` VALUES (1,'2018-01-01','AM'),(2,'2018-01-08','PM'),(3,'2018-01-15','AM'),(4,'2018-01-22','PM'),(5,'2018-01-29','AM'),(6,'2018-02-05','PM'),(7,'2018-02-12','AM'),(8,'2018-02-13','PM'),(9,'2018-02-14','AM'),(10,'2018-02-15','PM'),(11,'2018-02-16','AM');
/*!40000 ALTER TABLE `BUS_BLACKOUT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BUS_DRIVER`
--

DROP TABLE IF EXISTS `BUS_DRIVER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BUS_DRIVER` (
  `driverID` tinyint(1) NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `homePhone` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `cellPhone` varchar(45) DEFAULT NULL,
  `email` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`driverID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BUS_DRIVER`
--

LOCK TABLES `BUS_DRIVER` WRITE;
/*!40000 ALTER TABLE `BUS_DRIVER` DISABLE KEYS */;
INSERT INTO `BUS_DRIVER` VALUES (1,'Doug Dalke','585-208-5844','NULL','dougdalke1@gmail.com','53 Helen Road, Rochester, NY 14623'),(2,'Elizabeth Doyle','585-690-7270','NULL','ilutlmp@yahoo.com','19 Witherspoon Lane, Rochester, NY, 14625'),(3,'Rick Keller','585-225-7664','NULL','rnkeller@earthlink.net','82 Cider Creek Lane, Rochester, NY, 14616'),(4,'Bud Kress','585-865-7658','NULL','Budk2@frontiernet.net','254 Mobile Creek, Rochester, NY, 14616'),(5,'Jack Merrick','585-247-1006','585-802-2860','Jmerrick2@rochester.rr.com','29 Hickory Manor Drive, Gates, NY, 14606'),(6,'Jim Parks','585-265-3798','585-750-5590','jjjbparks@aol.com','142 Curtice Park, Webster, NY, 14580'),(7,'Dave Poland','585-586-0742','NULL','davidrpoland@aol.com','265 Alpine Drive, Rochester, NY, 14618'),(8,'Richard Reed','585-467-3739','NULL','Richardreed77@yahoo.com','122 Moulson St., Rochester, NY, 14621'),(9,'Rodney Vane','585-586-1498','585-410-2988','rodney.vane@gmail.com','9 Harvest Road, Fairport, NY, 14450'),(10,'David Williams','585-227-3342','NULL','dlwms@frontiernet.net','60 Ridgeway Estates, Rochester, NY, 14626'),(11,'Steve Zilora','585-388-0267','585-317-5844','stevez@cssconsult.com','43 Fox Hill Drive, Fairport, NY, 14450');
/*!40000 ALTER TABLE `BUS_DRIVER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BUS_SCHEDULE`
--

DROP TABLE IF EXISTS `BUS_SCHEDULE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BUS_SCHEDULE` (
  `driverID` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  `timeOfDay` char(2) CHARACTER SET latin1 NOT NULL,
  `role` varchar(8) CHARACTER SET latin1 NOT NULL,
  `congID` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`driverID`,`date`,`timeOfDay`,`role`),
  KEY `BUS_SCHEDULE_CONGREGATION_congID_idx` (`congID`),
  CONSTRAINT `BUS_SCHEDULE_BUS_DRIVER_driverID` FOREIGN KEY (`driverID`) REFERENCES `BUS_DRIVER` (`driverID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `BUS_SCHEDULE_CONGREGATION_congID` FOREIGN KEY (`congID`) REFERENCES `CONGREGATION` (`congID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BUS_SCHEDULE`
--

LOCK TABLES `BUS_SCHEDULE` WRITE;
/*!40000 ALTER TABLE `BUS_SCHEDULE` DISABLE KEYS */;
INSERT INTO `BUS_SCHEDULE` VALUES (1,'2018-01-01','AM','Primary',3),(2,'2018-01-01','PM','Primary',3),(3,'2018-01-01','AM','Backup',3),(4,'2018-01-01','PM','Backup',3);
/*!40000 ALTER TABLE `BUS_SCHEDULE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CONGREGATION`
--

DROP TABLE IF EXISTS `CONGREGATION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CONGREGATION` (
  `congID` tinyint(1) NOT NULL,
  `congName` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `congAddress` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `comments` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`congID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CONGREGATION`
--

LOCK TABLES `CONGREGATION` WRITE;
/*!40000 ALTER TABLE `CONGREGATION` DISABLE KEYS */;
INSERT INTO `CONGREGATION` VALUES (1,'Asbury First United Methodist','1050 East Ave, Rochester, NY, 14607','park on left side'),(2,'Church of the Assumption','20 East Ave, Fairport, NY, 14450','park on left side'),(3,'Downtown United Presbyterian (DUPC)','121 N. Fitzhugh St, Rochester, NY, 14614','park on left side'),(4,'First Presb Church of Pittsford','25 Church St, Pittsford, NY, 14534','park on left side'),(5,'First Unitarian Church','220 S.Winton Rd, Rochester. NY, 14610','park on left side'),(6,'First Universalist Church','150 S. Clinton Ave, Rochester, NY, 14604','park on left side'),(7,'Luth Church of Incarnate Word','597 East Ave, Rochester, NY, 14607','park on left side'),(8,'Mary Magdalene Church','1008 Main St, E. Rochester, NY, 14445','park on left side'),(9,'New Hope Free Methodist','62 N. Union St, Rochester, NY, 14607','park on left side'),(10,'St.Luke & St.Simon Cyrene Episcopal Church (2 Saints)','17 S. Fitzhugh St, Rochester, NY, 14614','park on left side'),(11,'St.Paul?s Episcodal Church','25 Westminister Rd, Rochester, NY, 14607','park on left side'),(12,'Temple Sinai','363 Penfield Rd, Rochester, NY, 14625','park on left side'),(13,'Third Presbyterian Church','4 Meigs St, Rochester, NY, 14607','park on left side');
/*!40000 ALTER TABLE `CONGREGATION` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CONGREGATION_BLACKOUT`
--

DROP TABLE IF EXISTS `CONGREGATION_BLACKOUT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CONGREGATION_BLACKOUT` (
  `congID` tinyint(1) NOT NULL,
  `weekNumber` tinyint(1) NOT NULL,
  `startDate` date NOT NULL,
  PRIMARY KEY (`congID`,`weekNumber`,`startDate`),
  KEY `CONGREGATION_BLACKOUT_CONGREGATION_idx` (`congID`),
  KEY `fk_CONGREGATION_BLACKOUT_DATE_RANGE1_idx` (`weekNumber`,`startDate`),
  CONSTRAINT `CONGREGATION_BLACKOUT_DATE_RANGE_startDate_weekNumber` FOREIGN KEY (`weekNumber`, `startDate`) REFERENCES `DATE_RANGE` (`weekNumber`, `startDate`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `CONGREGATION_BLACKOUT_CONGREGATION_congID` FOREIGN KEY (`congID`) REFERENCES `CONGREGATION` (`congID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CONGREGATION_BLACKOUT`
--

LOCK TABLES `CONGREGATION_BLACKOUT` WRITE;
/*!40000 ALTER TABLE `CONGREGATION_BLACKOUT` DISABLE KEYS */;
INSERT INTO `CONGREGATION_BLACKOUT` VALUES (3,2,'2018-01-08'),(6,1,'2018-01-01'),(6,3,'2018-01-15');
/*!40000 ALTER TABLE `CONGREGATION_BLACKOUT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CONGREGATION_COORDINATOR`
--

DROP TABLE IF EXISTS `CONGREGATION_COORDINATOR`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CONGREGATION_COORDINATOR` (
  `congID` tinyint(4) NOT NULL,
  `coordinatorName` varchar(100) NOT NULL,
  `coordinatorPhone` varchar(20) DEFAULT NULL,
  `coordinatorEmail` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`congID`,`coordinatorName`),
  CONSTRAINT `FK_CONGREGATION_COORDINATOR_CONGREGATION` FOREIGN KEY (`congID`) REFERENCES `CONGREGATION` (`congID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CONGREGATION_COORDINATOR`
--

LOCK TABLES `CONGREGATION_COORDINATOR` WRITE;
/*!40000 ALTER TABLE `CONGREGATION_COORDINATOR` DISABLE KEYS */;
INSERT INTO `CONGREGATION_COORDINATOR` VALUES (1,'Christine Lee','555-5555','c_s_lee29@msn.com'),(1,'Doug Lee','555-5556','c_s_lee29@msn.com'),(2,'Sue Owens','555-5557','sueowens813@gmail.com'),(3,'Ellen Horn','555-5560','ehorn7311@gmail.com'),(3,'Mary Bookout','555-5558','maryludlowbookout@gmail.com'),(3,'Mary McDowell','555-5559','mbggn@aol.com'),(4,'Barbara Smith','555-5561','barbarabsmith13@gmail.com'),(5,'Janice Hargave','555-5562','janiceh87@gmail.com'),(5,'Jerry Cheplowitz','555-5563','gcheplow@rochester.rr.com'),(6,'Marti Eggers','555-5565','mceggers@gmail.com'),(6,'Sarah Singal','555-5564','ssingal@rochester.rr.com'),(7,'Bruce Holmquist','555-5568','bholmquist@rochester.rr.com'),(7,'Eila Harkonen-Hart','555-5567','e.harkonenhart@gmail.com'),(7,'Joanne Lembach','555-5566','joanneelembach@rochester.rr.com'),(8,'Cathy Sanderson','555-5570','csander4@rochester.rr.com'),(8,'Lynne Hamilton','555-5569','equalrall@aol.com'),(8,'Paul Sanderson','555-5571','psander1@rochester.rr.com'),(9,'Amanda Holdridge','555-5572','apholdridge@gmail.com'),(9,'Tim Reyes','555-5573','timothy.reyes@live.com'),(10,'John Burr','555-5574','jburr28@frontier.com'),(11,'Bill Moore','555-5576','wmoore@rochester.rr.com'),(11,'Judy McGrath','555-5575','jpmcgrath@rochester.rr.com'),(12,'Hope Madonia','555-5578','hrmadonia@gmail.com'),(12,'Sue Bondy','555-5577','susanbondy@yahoo.com'),(13,'Laurie Mahoney','555-5580','lmahone1@rochester.rr.com'),(13,'Louis Loggi','555-5579','louis.loggi@gmail.com');
/*!40000 ALTER TABLE `CONGREGATION_COORDINATOR` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CONGREGATION_SCHEDULE`
--

DROP TABLE IF EXISTS `CONGREGATION_SCHEDULE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CONGREGATION_SCHEDULE` (
  `rotationNumber` tinyint(1) NOT NULL,
  `congID` tinyint(1) NOT NULL,
  `weekNumber` tinyint(1) NOT NULL,
  `startDate` date NOT NULL,
  PRIMARY KEY (`rotationNumber`,`congID`,`weekNumber`,`startDate`),
  KEY `CONGREGATION_SCHEDULE_ROTATION_DATE_rotationNumber_idx` (`rotationNumber`),
  KEY `CONGREGATION_SCHEDULE_CONGREGATION_congID_idx` (`congID`),
  KEY `fk_CONGREGATION_SCHEDULE_DATE_RANGE1_idx` (`weekNumber`,`startDate`),
  KEY `DATE_RANGE_startDate_idx` (`startDate`),
  CONSTRAINT `CONGREGATION_SCHEDULE_DATE_RANGE_startDate_weekNumber` FOREIGN KEY (`weekNumber`, `startDate`) REFERENCES `DATE_RANGE` (`weekNumber`, `startDate`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `CONGREGATION_SCHEDULE_CONGREGATION_congID` FOREIGN KEY (`congID`) REFERENCES `CONGREGATION` (`congID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `CONGREGATION_SCHEDULE_ROTATION_DATE_rotationNumber` FOREIGN KEY (`rotationNumber`) REFERENCES `ROTATION_DATE` (`rotationNumber`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CONGREGATION_SCHEDULE`
--

LOCK TABLES `CONGREGATION_SCHEDULE` WRITE;
/*!40000 ALTER TABLE `CONGREGATION_SCHEDULE` DISABLE KEYS */;
INSERT INTO `CONGREGATION_SCHEDULE` VALUES (10,5,1,'2018-01-01'),(11,1,2,'2018-01-08');
/*!40000 ALTER TABLE `CONGREGATION_SCHEDULE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DATE_RANGE`
--

DROP TABLE IF EXISTS `DATE_RANGE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DATE_RANGE` (
  `weekNumber` tinyint(1) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date DEFAULT NULL,
  `holiday` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`weekNumber`,`startDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DATE_RANGE`
--

LOCK TABLES `DATE_RANGE` WRITE;
/*!40000 ALTER TABLE `DATE_RANGE` DISABLE KEYS */;
INSERT INTO `DATE_RANGE` VALUES (1,'2018-01-01','2018-01-07',1),(2,'2018-01-08','2018-01-14',0),(3,'2018-01-15','2018-01-21',0),(4,'2018-01-22','2018-01-28',0),(5,'2018-01-29','2018-02-04',0),(6,'2018-02-05','2018-02-11',0),(7,'2018-02-12','2018-02-18',0),(8,'2018-02-19','2018-02-25',0),(9,'2018-02-26','2018-03-04',0),(10,'2018-03-05','2018-03-11',0),(11,'2018-03-12','2018-03-18',0),(12,'2018-03-19','2018-03-25',0),(13,'2018-03-26','2018-04-01',0),(14,'2018-04-02','2018-04-08',0),(15,'2018-04-09','2018-04-15',0),(16,'2018-04-16','2018-04-22',0),(17,'2018-04-23','2018-04-29',0),(18,'2018-04-30','2018-05-06',0),(19,'2018-05-07','2018-05-13',0),(20,'2018-05-14','2018-05-20',0),(21,'2018-05-21','2018-05-27',0),(22,'2018-05-28','2018-06-03',0),(23,'2018-06-04','2018-06-10',0);
/*!40000 ALTER TABLE `DATE_RANGE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LEGACY_HOST_BLACKOUT`
--

DROP TABLE IF EXISTS `LEGACY_HOST_BLACKOUT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LEGACY_HOST_BLACKOUT` (
  `congID` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date DEFAULT NULL,
  PRIMARY KEY (`congID`,`startDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LEGACY_HOST_BLACKOUT`
--

LOCK TABLES `LEGACY_HOST_BLACKOUT` WRITE;
/*!40000 ALTER TABLE `LEGACY_HOST_BLACKOUT` DISABLE KEYS */;
/*!40000 ALTER TABLE `LEGACY_HOST_BLACKOUT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ROTATION_DATE`
--

DROP TABLE IF EXISTS `ROTATION_DATE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ROTATION_DATE` (
  `rotationNumber` tinyint(1) NOT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  PRIMARY KEY (`rotationNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ROTATION_DATE`
--

LOCK TABLES `ROTATION_DATE` WRITE;
/*!40000 ALTER TABLE `ROTATION_DATE` DISABLE KEYS */;
INSERT INTO `ROTATION_DATE` VALUES (1,'2018-01-01','2018-01-07'),(2,'2018-01-08','2018-01-14'),(3,'2018-01-15','2018-01-21'),(4,'2018-01-22','2018-01-28'),(5,'2018-01-29','2018-02-04'),(6,'2018-02-05','2018-02-11'),(7,'2018-02-12','2018-02-18'),(8,'2018-02-19','2018-02-25'),(9,'2018-02-26','2018-03-04'),(10,'2018-03-05','2018-03-11'),(11,'2018-03-12','2018-03-18'),(12,'2018-03-19','2018-03-25'),(13,'2018-03-26','2018-04-01'),(14,'2018-04-02','2018-04-08'),(15,'2018-04-09','2018-04-15'),(16,'2018-04-16','2018-04-22'),(17,'2018-04-23','2018-04-29'),(18,'2018-04-30','2018-05-06'),(19,'2018-05-07','2018-05-13'),(20,'2018-05-14','2018-05-20'),(21,'2018-05-21','2018-05-27'),(22,'2018-05-28','2018-06-03'),(23,'2018-06-04','2018-06-10');
/*!40000 ALTER TABLE `ROTATION_DATE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USERS`
--

DROP TABLE IF EXISTS `USERS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USERS` (
  `userID` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `email` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `role` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `username` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `password` char(40) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USERS`
--

LOCK TABLES `USERS` WRITE;
/*!40000 ALTER TABLE `USERS` DISABLE KEYS */;
INSERT INTO `USERS` VALUES (1,'Christine Lee','c_s_lee29@msn.com','coordinator','asbury','password'),(2,'Sue Owens','sueowens813@gmail.com','coordinator','assumption','password');
/*!40000 ALTER TABLE `USERS` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-06-21 20:07:49
