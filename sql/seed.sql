-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: localhost    Database: dayquest
-- ------------------------------------------------------
-- Server version	9.1.0

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
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'DQA1','$2y$12$gbuCkN/5v43Oz9aOJgqBTedlspiWSaxlZsK5Nxzo628QY1e0ckiFi',7,'2026-10-25 06:38:55'),(2,'DQA2','$2y$12$T4YS63oGIqMMQzMw5w3pzuJGZbJyE35jdQvphXp8cTqsxxgYTZ27K',0,NULL);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Nature',1),(2,'Religious',0),(3,'Heritage',0),(4,'Cultural',0),(5,'Recreation',1),(6,'Wildlife',1);
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `place`
--

LOCK TABLES `place` WRITE;
/*!40000 ALTER TABLE `place` DISABLE KEYS */;
INSERT INTO `place` VALUES (1,'Panadura Beach',1,'A quieter, less-crowded stretch of coastline with calm waters close to the town centre; popular for beach walks, sunbathing and photography.','-',90,6.711584,79.902081,'None',2.0,'swim only where safe; check currents.','Family/Kid Friendly','place-6adc1be7ed003.jpg'),(2,'Rankoth Viharaya',2,'A Buddhist temple in Panadura with a 200-plus-year-old image house adorned with fresco paintings; site of the historic Panadura Debate.','5.00 a.m - 9.00 p.m',45,6.713000,79.904000,'None',3.0,'Dress modestly; remove shoes and hats.','Family/Kid Friendly','place-6adc2011a25cd.jpg'),(3,'Wadduwa Beach',1,'Palm-fringed golden-sand beach south of Panadura with calmer waters than the main tourist beaches further south; popular for swimming and relaxing.','-',120,6.665000,79.928000,'None',6.0,'check currents','Family/Kid Friendly','place-6adc203ce7483.jpg'),(4,'Bolgoda Lake',1,'The largest natural lake in the country, covering an area of 374 square kilometers.','-',90,6.771978,79.907550,'None',8.0,'TBC','TBC','place-6adc1fc17bcdc.jpg'),(5,'Pearl Bay (Bolgoda)',5,'A lakeside recreation spot on Bolgoda Lake offering boating and water-sport activities with scenic sunset views.','10.00 a.m - 6.00 p.m',120,6.753000,79.932000,'Based on height  / 1.2 above meters - Rs. 5,500.00 / 1.2 below meters - Rs. 4,200.00',9.0,'plan around sunset.','Family/Kid Friendly','place-6adc1ff4c27fa.jpg'),(6,'Sri Lanka Air Force Museum',4,'Museum at Ratmalana displaying historic aircraft, uniforms and memorabilia tracing the history of the Sri Lanka Air Force.','6.00 a.m - 9.00 p.m',90,6.824033,79.891750,'Local - Rs 50.00-200.00 / Foreign - USD 15.00',13.0,'TBC','TBC','place-6adc20280133d.jpg'),(7,'Bellanwila Raja Maha Viharaya',2,'A historic Buddhist temple beside the Bellanwila wetland, known for its architecture and as a major site during the Duruthu Perahera festival.','6.00 a.m - 9.00 p.m',45,6.849582,79.890080,'None',15.0,'Dress modestly; remove shoes and hats.','kid-friendly','place-6adc221983742.jpg'),(8,'Kalutara Bodhiya',2,'Iconic hollow dagoba on the Kalutara river bank, famous for its unique interior murals and views over the Kalu Ganga estuary from the Galle Road bridge.','4.30 a.m -10.00 p.m',45,6.587404,79.959525,'None',16.0,'Dress modestly; remove shoes and hats.','Family/Kid Friendly','place-6adc1fe14e130.jpg'),(9,'Richmond Castle',3,'An early-20th-century colonial mansion near Kalutara built by a wealthy mudaliyar, blending Edwardian and traditional Sri Lankan architectural styles.','9.00 a.m - 4.00 p.m',45,6.601977,79.975230,'Local - Rs 50.00-100.00 / Foreign - Rs. 500.00 -1000.00',17.0,'check if open to visitors.','Family/Kid Friendly','place-6adc201ace7fa.jpg'),(10,'Pothupitiya Beach',1,'A relatively secluded beach north of Panadura offering a quieter alternative to the busier coastal stretches, good for a short seaside stop.','-',60,6.790000,79.880000,'-',12.0,'Secluded: add a safety note for solo visitors (e.g. avoid after dusk).','Family/Kid Friendly','place-6adc20040cc39.jpg'),(11,'Dehiwala Zoo',6,'National Zoological Gardens.','8.30 a.m - 5.30 p.m',180,6.856971,79.874367,'Local - Rs 120.00-300.00 / Foreign - USD 9.00-18.00',15.0,'check for elephant circus show times','Family/Kid Friendly','place-6adc1fcfe21bc.webp');
/*!40000 ALTER TABLE `place` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `template`
--

LOCK TABLES `template` WRITE;
/*!40000 ALTER TABLE `template` DISABLE KEYS */;
INSERT INTO `template` VALUES (1,'Solo Explorer','Solo'),(2,'Family Fun Day','Family');
/*!40000 ALTER TABLE `template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `template_place`
--

LOCK TABLES `template_place` WRITE;
/*!40000 ALTER TABLE `template_place` DISABLE KEYS */;
INSERT INTO `template_place` VALUES (1,2,1),(1,3,4),(1,8,2),(1,9,3),(2,3,5),(2,5,4),(2,6,1),(2,7,3),(2,11,2);
/*!40000 ALTER TABLE `template_place` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-25 13:08:00
