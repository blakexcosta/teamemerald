-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: localhost    Database: raihn
-- ------------------------------------------------------
-- Server version	5.7.16-log

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
-- Table structure for table `bus_blackout`
--

DROP TABLE IF EXISTS `bus_blackout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bus_blackout` (
  `driverID` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  `timeOfDay` char(2) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`driverID`,`date`,`timeOfDay`),
  CONSTRAINT `BUS_BLACKOUT_BUS_DRIVER_driverID` FOREIGN KEY (`driverID`) REFERENCES `bus_driver` (`driverID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bus_blackout`
--

LOCK TABLES `bus_blackout` WRITE;
/*!40000 ALTER TABLE `bus_blackout` DISABLE KEYS */;
/*!40000 ALTER TABLE `bus_blackout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bus_driver`
--

DROP TABLE IF EXISTS `bus_driver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bus_driver` (
  `driverID` tinyint(1) NOT NULL,
  `userID` tinyint(4) NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `homePhone` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `cellPhone` varchar(45) DEFAULT NULL,
  `email` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`driverID`,`userID`),
  KEY `BUS_DRIVER_USERS_userID_idx` (`userID`),
  CONSTRAINT `FK_BUS_DRIVER_USERS_userID` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bus_driver`
--

LOCK TABLES `bus_driver` WRITE;
/*!40000 ALTER TABLE `bus_driver` DISABLE KEYS */;
/*!40000 ALTER TABLE `bus_driver` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bus_schedule`
--

DROP TABLE IF EXISTS `bus_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bus_schedule` (
  `driverID` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  `timeOfDay` char(2) CHARACTER SET latin1 NOT NULL,
  `role` varchar(8) CHARACTER SET latin1 NOT NULL,
  `congID` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`driverID`,`date`,`timeOfDay`,`role`),
  KEY `BUS_SCHEDULE_CONGREGATION_congID_idx` (`congID`),
  CONSTRAINT `BUS_SCHEDULE_BUS_DRIVER_driverID` FOREIGN KEY (`driverID`) REFERENCES `bus_driver` (`driverID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `BUS_SCHEDULE_CONGREGATION_congID` FOREIGN KEY (`congID`) REFERENCES `congregation` (`congID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bus_schedule`
--

LOCK TABLES `bus_schedule` WRITE;
/*!40000 ALTER TABLE `bus_schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `bus_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `congregation`
--

DROP TABLE IF EXISTS `congregation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `congregation` (
  `congID` tinyint(1) NOT NULL,
  `congName` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `congAddress` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `comments` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`congID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `congregation`
--

LOCK TABLES `congregation` WRITE;
/*!40000 ALTER TABLE `congregation` DISABLE KEYS */;
/*!40000 ALTER TABLE `congregation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `congregation_blackout`
--

DROP TABLE IF EXISTS `congregation_blackout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `congregation_blackout` (
  `congID` tinyint(1) NOT NULL,
  `weekNumber` tinyint(1) NOT NULL,
  `startDate` date NOT NULL,
  PRIMARY KEY (`congID`,`weekNumber`,`startDate`),
  KEY `CONGREGATION_BLACKOUT_CONGREGATION_idx` (`congID`),
  KEY `fk_CONGREGATION_BLACKOUT_DATE_RANGE1_idx` (`weekNumber`,`startDate`),
  CONSTRAINT `CONGREGATION_BLACKOUT_CONGREGATION_congID` FOREIGN KEY (`congID`) REFERENCES `congregation` (`congID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `CONGREGATION_BLACKOUT_DATE_RANGE_startDate_weekNumber` FOREIGN KEY (`weekNumber`, `startDate`) REFERENCES `date_range` (`weekNumber`, `startDate`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `congregation_blackout`
--

LOCK TABLES `congregation_blackout` WRITE;
/*!40000 ALTER TABLE `congregation_blackout` DISABLE KEYS */;
/*!40000 ALTER TABLE `congregation_blackout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `congregation_coordinator`
--

DROP TABLE IF EXISTS `congregation_coordinator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `congregation_coordinator` (
  `congID` tinyint(4) NOT NULL,
  `userID` tinyint(4) NOT NULL,
  `coordinatorName` varchar(100) DEFAULT NULL,
  `coordinatorPhone` varchar(20) DEFAULT NULL,
  `coordinatorEmail` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`congID`,`userID`),
  KEY `FK_CONGREGATION_COORDINATOR_USERS_userID_idx` (`userID`),
  CONSTRAINT `FK_CONGREGATION_COORDINATOR_CONGREGATION` FOREIGN KEY (`congID`) REFERENCES `congregation` (`congID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_CONGREGATION_COORDINATOR_USERS_userID` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `congregation_coordinator`
--

LOCK TABLES `congregation_coordinator` WRITE;
/*!40000 ALTER TABLE `congregation_coordinator` DISABLE KEYS */;
/*!40000 ALTER TABLE `congregation_coordinator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `congregation_schedule`
--

DROP TABLE IF EXISTS `congregation_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `congregation_schedule` (
  `rotationNumber` tinyint(1) NOT NULL,
  `congID` tinyint(1) NOT NULL,
  `weekNumber` tinyint(1) NOT NULL,
  `startDate` date NOT NULL,
  PRIMARY KEY (`rotationNumber`,`congID`,`weekNumber`,`startDate`),
  KEY `CONGREGATION_SCHEDULE_ROTATION_DATE_rotationNumber_idx` (`rotationNumber`),
  KEY `CONGREGATION_SCHEDULE_CONGREGATION_congID_idx` (`congID`),
  KEY `fk_CONGREGATION_SCHEDULE_DATE_RANGE1_idx` (`weekNumber`,`startDate`),
  KEY `DATE_RANGE_startDate_idx` (`startDate`),
  CONSTRAINT `CONGREGATION_SCHEDULE_CONGREGATION_congID` FOREIGN KEY (`congID`) REFERENCES `congregation` (`congID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `CONGREGATION_SCHEDULE_DATE_RANGE_startDate_weekNumber` FOREIGN KEY (`weekNumber`, `startDate`) REFERENCES `date_range` (`weekNumber`, `startDate`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `CONGREGATION_SCHEDULE_ROTATION_DATE_rotationNumber` FOREIGN KEY (`rotationNumber`) REFERENCES `rotation_date` (`rotationNumber`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `congregation_schedule`
--

LOCK TABLES `congregation_schedule` WRITE;
/*!40000 ALTER TABLE `congregation_schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `congregation_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `date_range`
--

DROP TABLE IF EXISTS `date_range`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `date_range` (
  `weekNumber` tinyint(1) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date DEFAULT NULL,
  `holiday` tinyint(1) DEFAULT NULL,
  `rotation_number` int(11) NOT NULL,
  PRIMARY KEY (`weekNumber`,`startDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `date_range`
--

LOCK TABLES `date_range` WRITE;
/*!40000 ALTER TABLE `date_range` DISABLE KEYS */;
/*!40000 ALTER TABLE `date_range` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `legacy_host_blackout`
--

DROP TABLE IF EXISTS `legacy_host_blackout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `legacy_host_blackout` (
  `congID` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date DEFAULT NULL,
  PRIMARY KEY (`congID`,`startDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `legacy_host_blackout`
--

LOCK TABLES `legacy_host_blackout` WRITE;
/*!40000 ALTER TABLE `legacy_host_blackout` DISABLE KEYS */;
/*!40000 ALTER TABLE `legacy_host_blackout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rotation_date`
--

DROP TABLE IF EXISTS `rotation_date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rotation_date` (
  `rotationNumber` tinyint(1) NOT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  PRIMARY KEY (`rotationNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rotation_date`
--

LOCK TABLES `rotation_date` WRITE;
/*!40000 ALTER TABLE `rotation_date` DISABLE KEYS */;
/*!40000 ALTER TABLE `rotation_date` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userID` tinyint(4) NOT NULL,
  `userType` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `email` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `password` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `salt` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-09-05 16:46:10
