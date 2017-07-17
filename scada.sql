-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: scada
-- ------------------------------------------------------
-- Server version	5.5.35-0ubuntu0.12.04.2

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
-- Table structure for table `shield_wall_log`
--

DROP TABLE IF EXISTS `shield_wall_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shield_wall_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_text` varchar(255) NOT NULL,
  `user` varchar(90) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shield_wall_log`
--

LOCK TABLES `shield_wall_log` WRITE;
/*!40000 ALTER TABLE `shield_wall_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `shield_wall_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shield_wall_status`
--

DROP TABLE IF EXISTS `shield_wall_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shield_wall_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `batt1` int(11) NOT NULL DEFAULT '1',
  `batt2` int(11) NOT NULL DEFAULT '1',
  `batt3` int(11) NOT NULL DEFAULT '1',
  `batt4` int(11) NOT NULL DEFAULT '1',
  `cap1` int(11) NOT NULL DEFAULT '1',
  `wall` int(11) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shield_wall_status`
--

LOCK TABLES `shield_wall_status` WRITE;
/*!40000 ALTER TABLE `shield_wall_status` DISABLE KEYS */;
INSERT INTO `shield_wall_status` VALUES (1,0,0,1,1,1,1,1,'2014-03-20 17:34:31');
/*!40000 ALTER TABLE `shield_wall_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shield_wall_users`
--

DROP TABLE IF EXISTS `shield_wall_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shield_wall_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(90) NOT NULL,
  `password` varchar(90) NOT NULL,
  `access_level` int(11) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shield_wall_users`
--

LOCK TABLES `shield_wall_users` WRITE;
/*!40000 ALTER TABLE `shield_wall_users` DISABLE KEYS */;
INSERT INTO `shield_wall_users` VALUES (1,'admin','$2y$10$enosuOILRHqOeYHKqCAH$.GwuUf1LN/GLTDczvJLtDdimqmGtN0DC',100,'2014-10-08 18:18:17');
/*!40000 ALTER TABLE `shield_wall_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-07-17  7:44:33
