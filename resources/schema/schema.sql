-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: noobster
-- ------------------------------------------------------
-- Server version	8.0.31

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
-- Table structure for table `event_categories`
--

DROP TABLE IF EXISTS `event_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_categories` (
                                    `id` int NOT NULL AUTO_INCREMENT,
                                    `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
                                    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_categories`
--

LOCK TABLES `event_categories` WRITE;
/*!40000 ALTER TABLE `event_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `identifier` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
                          `title` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
                          `description` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
                          `start_date` datetime NOT NULL COMMENT '(DC2Type:datetimetz_immutable)',
                          `end_date` datetime NOT NULL COMMENT '(DC2Type:datetimetz_immutable)',
                          `ownedBy_id` int DEFAULT NULL,
                          PRIMARY KEY (`id`),
                          KEY `IDX_5387574A752AFD0D` (`ownedBy_id`),
                          CONSTRAINT `FK_5387574A752AFD0D` FOREIGN KEY (`ownedBy_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'2def32d0-240f-4793-a397-074d2b82692c','Test Event','test','2023-10-01 00:00:00','2024-10-01 00:00:00',1),(3,'e287eae9-46a6-427f-a2f1-04501c9f942c','Updated Event','I have updated this event!','2022-02-02 00:00:00','2022-03-02 00:00:00',2),(4,'886e299b-5140-4df8-a071-6fddba6f439d','Test Event','Description','2022-02-02 00:00:00','2022-03-02 00:00:00',2),(5,'de964a2d-04b3-42ba-acbd-9489e8887414','Test Event','Description','2022-02-02 00:00:00','2022-03-02 00:00:00',2),(6,'df95cafc-0b51-4f8c-a5d3-c067db2a704e','Test Event','Description','2022-02-02 00:00:00','2022-03-02 00:00:00',2),(7,'b1533600-f4e3-4c96-a8ab-2c496de62522','Test Event','Description','2022-02-02 00:00:00','2022-03-02 00:00:00',2),(8,'6a31a6ff-2e07-49c1-a1c8-329bf29f0b49','Test Event','Description','2022-02-02 00:00:00','2022-03-02 00:00:00',2),(9,'ced33f2d-e463-4d43-9b82-438e86fbb930','Test Event','Description','2022-02-02 00:00:00','2022-03-02 00:00:00',2),(10,'e13e9b61-eb48-4dab-a4cd-24461d203475','Test Event','Description','2022-02-02 00:00:00','2022-03-02 00:00:00',2),(11,'9a254188-18ae-4708-80f3-e756757fdbee','Blaat','kees','2022-01-03 00:00:00','2023-01-03 00:00:00',2),(12,'90e736a0-4d20-42cc-8b27-58fe85b9a3e7','Blaat','kees','2022-01-03 00:00:00','2023-01-03 00:00:00',2),(13,'d416d6a3-b660-4891-be3d-421f6cbee88e','Blaat','kees','2022-01-03 00:00:00','2023-01-03 00:00:00',2),(14,'027a6d07-0ea8-4f26-875a-9c12ca584811','Blaat','kees','2022-01-03 00:00:00','2023-01-03 00:00:00',2),(15,'ed161f28-b177-4f4c-8063-05171de37f98','Bloep','Whoooo!','2022-12-23 22:50:02','2022-12-23 22:50:02',1),(16,'f1f6f347-4f5f-4fd6-84fa-84d1fdb2951b','Bloep','Whoooo!','2022-12-23 22:50:23','2022-12-23 22:50:23',1),(17,'5a3f1c28-3d7e-40de-a2ae-eb3cc1951c98','Bloep','Whoooo!','2022-12-23 22:50:48','2022-12-23 22:50:48',NULL),(18,'71a1bf45-fcd6-4107-a1d3-92be48b02cea','Bloep','Whoooo!','2022-12-23 22:51:59','2022-12-23 22:51:59',1),(19,'c253b91c-d0b8-40f4-a0ee-f8cf83555d24','Bloep','Whoooo!','2022-12-23 22:52:08','2022-12-23 22:52:08',1),(20,'40a44013-3f3c-482a-993b-b7c6e057d889','Bloep','Whoooo!','2022-12-23 22:52:35','2022-12-23 22:52:35',1),(21,'c0cc71c1-f5f2-4e9a-bcde-d2894750bc7e','Bloep1','Whoooo!','2022-12-23 22:52:42','2022-12-23 22:52:42',1),(22,'2c7c4c2a-5aae-4408-a26b-1811a2aa7837','Bloep1','Whoooo!','2022-12-23 22:52:53','2022-12-23 22:52:53',1),(23,'42d9ce7e-9409-4ed8-b88c-160cd8efdda0','Bloep1','Whoooo!','2022-12-23 22:53:24','2022-12-23 22:53:24',1),(24,'4d6a146d-c7d0-4d0d-9ce8-d7f7349ae97c','Bloep1','Whoooo!','2022-12-23 22:53:44','2022-12-23 22:53:44',1),(25,'8fdc30a9-e436-42c7-9a45-e5f4a32a0113','Bloep1','Whoooo!','2022-12-23 22:54:22','2022-12-23 22:54:22',1),(26,'5e9be7fd-6333-4c56-9f61-34e204f466b6','Bloep1','Whoooo!','2022-12-24 00:03:06','2022-12-24 00:03:06',1),(27,'fcd339d0-4249-4493-babd-349f1e603074','Bloep1','Whoooo!','2022-12-24 00:03:13','2022-12-24 00:03:13',1),(28,'ea39dd51-d29a-4e31-b445-e6493b2c1d2f','Bloep1','Whoooo!','2022-12-24 08:53:46','2022-12-24 08:53:46',1),(29,'15a729b5-995e-4e7d-b348-1bf2ab0905a9','Test Event','Description','2022-02-02 00:00:00','2022-03-02 00:00:00',2),(30,'72d895a0-8dc2-484b-a770-9a7ffba27c43','Test Event','Description','2022-02-02 00:00:00','2022-03-02 00:00:00',2),(31,'5976d358-6ba2-4ec1-b898-f9aa38179ef7','Test Event','Description','2022-02-02 00:00:00','2022-03-02 00:00:00',2),(32,'773891f7-fe4b-4bc0-8187-d8b47425aaa8','Test Event','Description','2022-02-02 00:00:00','2022-03-02 00:00:00',2),(33,'160be68e-bb2a-41cf-a32c-87284d3dd300','Updated Event','I have updated this event!','2022-02-02 00:00:00','2022-03-02 00:00:00',14),(34,'40cdc1d7-be2e-479e-83a7-b3f8b6600449','Test Event','Description','2022-02-02 00:00:00','2022-03-02 00:00:00',14),(35,'9ca67051-a8ea-467a-bb09-508f2199da5c','Test Event','Description','2022-02-02 00:00:00','2022-03-02 00:00:00',14);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invitees`
--

DROP TABLE IF EXISTS `invitees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invitees` (
                            `id` int NOT NULL AUTO_INCREMENT,
                            `event_id` int DEFAULT NULL,
                            `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
                            `visitorId` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
                            PRIMARY KEY (`id`),
                            UNIQUE KEY `UNIQ_439129C5B69CD2C` (`visitorId`),
                            KEY `IDX_439129C571F7E88B` (`event_id`),
                            CONSTRAINT `FK_439129C571F7E88B` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invitees`
--

LOCK TABLES `invitees` WRITE;
/*!40000 ALTER TABLE `invitees` DISABLE KEYS */;
/*!40000 ALTER TABLE `invitees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `necessities`
--

DROP TABLE IF EXISTS `necessities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `necessities` (
                               `id` int NOT NULL AUTO_INCREMENT,
                               `event_id` int DEFAULT NULL,
                               `member_id` int DEFAULT NULL,
                               `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
                               PRIMARY KEY (`id`),
                               KEY `IDX_15C708BD71F7E88B` (`event_id`),
                               KEY `IDX_15C708BD7597D3FE` (`member_id`),
                               CONSTRAINT `FK_15C708BD71F7E88B` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
                               CONSTRAINT `FK_15C708BD7597D3FE` FOREIGN KEY (`member_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `necessities`
--

LOCK TABLES `necessities` WRITE;
/*!40000 ALTER TABLE `necessities` DISABLE KEYS */;
INSERT INTO `necessities` VALUES (1,1,NULL,'test item'),(2,1,NULL,'test item 2'),(3,1,NULL,'test Item 3');
/*!40000 ALTER TABLE `necessities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_event`
--

DROP TABLE IF EXISTS `user_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_event` (
                              `user_id` int NOT NULL,
                              `event_id` int NOT NULL,
                              PRIMARY KEY (`user_id`,`event_id`),
                              KEY `IDX_D96CF1FFA76ED395` (`user_id`),
                              KEY `IDX_D96CF1FF71F7E88B` (`event_id`),
                              CONSTRAINT `FK_D96CF1FF71F7E88B` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
                              CONSTRAINT `FK_D96CF1FFA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_event`
--

LOCK TABLES `user_event` WRITE;
/*!40000 ALTER TABLE `user_event` DISABLE KEYS */;
INSERT INTO `user_event` VALUES (1,17);
/*!40000 ALTER TABLE `user_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_sessions`
--

DROP TABLE IF EXISTS `user_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_sessions` (
                                 `id` int NOT NULL AUTO_INCREMENT,
                                 `user_id` int DEFAULT NULL,
                                 `token` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
                                 `issued_at` datetime NOT NULL COMMENT '(DC2Type:datetimetz_immutable)',
                                 `last_visit` datetime NOT NULL COMMENT '(DC2Type:datetimetz_immutable)',
                                 PRIMARY KEY (`id`),
                                 UNIQUE KEY `UNIQ_7AED79135F37A13B` (`token`),
                                 KEY `IDX_7AED7913A76ED395` (`user_id`),
                                 CONSTRAINT `FK_7AED7913A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_sessions`
--

LOCK TABLES `user_sessions` WRITE;
/*!40000 ALTER TABLE `user_sessions` DISABLE KEYS */;
INSERT INTO `user_sessions` VALUES (1,2,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjIsInVzZXJuYW1lIjoiUm9iaW4xIn0.aoRTxgIgSPUNQ3su5-gtkpgs3rUbXYjk941ADJy4_WI','2022-12-22 07:05:00','2022-12-22 07:05:00'),(3,1,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjEsInVzZXJuYW1lIjoiUm9iaW4ifQ.l2LtHDVEI1VIhtngmAPsMbO3HNToUbIAsiAxuDDcGpc','2022-12-22 23:00:39','2022-12-22 23:00:39'),(14,12,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjEyfQ.MO4MrR-ynHLHuuefBGadBKrp-4H_sttMfXT3vqUzZ2E','2022-12-24 22:47:06','2022-12-24 22:47:06'),(15,14,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjE0fQ.TtoIGdZC8LE2QqOoBg8n88rjYSOOXhD-4rvaAnxo5cQ','2022-12-24 22:54:52','2022-12-24 22:54:52'),(16,15,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjE1fQ.9UFuVygEzqDDwQJziYis2P6nkRQeyD1wx1W3tGezTm8','2022-12-26 09:36:44','2022-12-26 09:36:44'),(17,16,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjE2fQ.2VQ5UXTjXzzwTvN4cSsycEjvGbh3LIjbB7wmNBuJvv8','2022-12-26 09:48:10','2022-12-26 09:48:10');
/*!40000 ALTER TABLE `user_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
                         `id` int NOT NULL AUTO_INCREMENT,
                         `username` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
                         `password` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
                         `first_visit` datetime NOT NULL COMMENT '(DC2Type:datetimetz_immutable)',
                         `visitorId` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
                         PRIMARY KEY (`id`),
                         UNIQUE KEY `UNIQ_1483A5E9F85E0677` (`username`),
                         UNIQUE KEY `UNIQ_1483A5E9B69CD2C` (`visitorId`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Robin','$2y$10$yAH50NaFkBszDzPvwBa5hu9BO0KhoXTWqAPD3eAFY5JU6ZWgzDQbK','2022-12-20 20:21:29',NULL),(2,'Robin1','$2y$10$YuHHhw5zgrjcSMuO1movHOGeP9bXUbcxdbAy1pLZACbkUcIbVWorO','2022-12-21 22:12:49',NULL),(12,NULL,NULL,'2022-12-24 22:47:06','Blaat2'),(14,NULL,NULL,'2022-12-24 22:54:52','Blaat2dsfsfsdfsdfsdf'),(15,'Pieter','$2y$10$Yw3fbocUc1mh/xtXgiSg..jjI2hale/BpBWIGyaA1b/YCqXrdTg7K','2022-12-26 09:36:44',NULL),(16,'Pieter2','$2y$10$WHSbYguuWacqNFCObB/Pwukz4vWpfzvvmXk6eZEcFdwORTF0Zkkyq','2022-12-26 09:48:10',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_events`
--

DROP TABLE IF EXISTS `users_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_events` (
                                `user_id` int NOT NULL,
                                `event_id` int NOT NULL,
                                PRIMARY KEY (`user_id`,`event_id`),
                                KEY `IDX_5C60D9DAA76ED395` (`user_id`),
                                KEY `IDX_5C60D9DA71F7E88B` (`event_id`),
                                CONSTRAINT `FK_5C60D9DA71F7E88B` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
                                CONSTRAINT `FK_5C60D9DAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_events`
--

LOCK TABLES `users_events` WRITE;
/*!40000 ALTER TABLE `users_events` DISABLE KEYS */;
INSERT INTO `users_events` VALUES (14,4);
/*!40000 ALTER TABLE `users_events` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-12-27 19:44:37
