-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: journals_db
-- ------------------------------------------------------
-- Server version	8.0.34

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
-- Table structure for table `authors`
--

DROP TABLE IF EXISTS `authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `authors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authors`
--

LOCK TABLES `authors` WRITE;
/*!40000 ALTER TABLE `authors` DISABLE KEYS */;
INSERT INTO `authors` VALUES (1,'Иванов','Иван','Иванович'),(2,'Петров','Игорь','Петрович'),(3,'Сидоров','Сидор','Сидорович'),(4,'Смирнов','Алексей','Алексеевич'),(5,'Кузнецов','Дмитрий',NULL),(6,'Смирнов','Петр','Константинович'),(9,'Кузнецов','Михаил',''),(11,'Петров','Петр','Петрович'),(12,'Сидоров','Антон','Тагирович'),(13,'Смирнов','Алексей','Иванович'),(17,'Алексеев','Василий','Васильевич'),(18,'Новиков','Виктор',NULL),(19,'Беляев','Игорь','Анатольевич'),(20,'Морозов','Андрей',NULL),(21,'Федоров','Николай','Петрович'),(22,'Соловьев','Юрий','Иванович'),(23,'Михайлов','Станислав','Александрович'),(24,'Громов','Евгений',NULL),(25,'Иванов','Иван','Иванович');
/*!40000 ALTER TABLE `authors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journal_author`
--

DROP TABLE IF EXISTS `journal_author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `journal_author` (
  `journal_id` int NOT NULL,
  `author_id` int NOT NULL,
  PRIMARY KEY (`journal_id`,`author_id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `journal_author_ibfk_1` FOREIGN KEY (`journal_id`) REFERENCES `journals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `journal_author_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journal_author`
--

LOCK TABLES `journal_author` WRITE;
/*!40000 ALTER TABLE `journal_author` DISABLE KEYS */;
INSERT INTO `journal_author` VALUES (4,2),(4,3),(17,3),(7,4),(19,4),(20,4),(7,5),(17,9),(27,12),(20,18),(21,18),(22,19),(23,20),(24,21),(25,22),(26,23),(14,24),(28,25);
/*!40000 ALTER TABLE `journal_author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journals`
--

DROP TABLE IF EXISTS `journals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `journals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `short_description` text,
  `release_date` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journals`
--

LOCK TABLES `journals` WRITE;
/*!40000 ALTER TABLE `journals` DISABLE KEYS */;
INSERT INTO `journals` VALUES (4,'Медицинский журнал','Последние исследования в области медицины','07.08.2024'),(7,'Журнал технологий','Новые технологии прямо здесь','05.12.2024'),(14,'Музыкальный Журнал','Музыка для хорошего настроения','01.01.1970'),(17,'Журнал искусств','Рецензии и обзоры на выставки и произведения искусства.','12.05.2010'),(19,'Игровой Журнал','Игровые новинки этого года.','01.01.1970'),(20,'Общий Журнал','Общая информация о различных темах.','02.01.1970'),(21,'Научный обзор','Обзор новых научных открытий и исследований.','11.12.2024'),(22,'Технические инновации','Самые свежие новости в мире технологий и инженерии.','01.01.1970'),(23,'Культура и общество','Анализ культурных и социальных трендов.','01.01.1970'),(24,'Современная литература','Последние книги и авторские рецензии.','01.01.1970'),(25,'Экономические тенденции','Анализ текущих экономических трендов и изменений.','12.10.2024'),(26,'Туризм и путешествия','Обзоры популярных туристических направлений и советов по путешествиям.','01.01.1970'),(27,'Спортивные достижения','Новости и достижения в мире спорта.','01.01.1970'),(28,'Космический Журнал ','Факты про космос','16.08.2024');
/*!40000 ALTER TABLE `journals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (7,'qwerty','1q2w3e4r5t6y','qwerty@gmail.com');
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

-- Dump completed on 2024-08-15 18:55:44
