-- MySQL dump 10.13  Distrib 8.0.22, for osx10.15 (x86_64)
--
-- Host: 127.0.0.1    Database: tongmeng
-- ------------------------------------------------------
-- Server version	8.0.22

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `admin_menu`
--

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;
INSERT INTO `admin_menu` VALUES (1,0,1,'Index','feather icon-bar-chart-2','/','2020-11-03 06:19:07',NULL),(2,0,6,'Admin','feather icon-settings','','2020-11-03 06:19:07','2020-11-04 09:09:47'),(3,2,7,'Users','','auth/users','2020-11-03 06:19:07','2020-11-04 09:09:47'),(4,2,8,'Roles','','auth/roles','2020-11-03 06:19:07','2020-11-04 09:09:47'),(5,2,9,'Permission','','auth/permissions','2020-11-03 06:19:07','2020-11-04 09:09:47'),(6,2,10,'Menu','','auth/menu','2020-11-03 06:19:07','2020-11-04 09:09:47'),(7,0,2,'Tags','fa-tags','tags','2020-11-04 02:52:27','2020-11-04 09:09:47'),(8,0,3,'Types','fa-book','types','2020-11-04 02:56:07','2020-11-04 09:09:47'),(9,0,4,'Lessons','fa-video-camera','lessons','2020-11-04 03:02:39','2020-11-04 09:09:47'),(10,0,5,'Videos','fa-film','videos','2020-11-04 03:05:09','2020-11-04 09:09:47');
/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_permission_menu`
--

LOCK TABLES `admin_permission_menu` WRITE;
/*!40000 ALTER TABLE `admin_permission_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_permission_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_permissions`
--

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES (1,'Auth management','auth-management','','',1,0,'2020-11-03 06:19:07',NULL),(2,'Users','users','','/auth/users*',2,1,'2020-11-03 06:19:07',NULL),(3,'Roles','roles','','/auth/roles*',3,1,'2020-11-03 06:19:07',NULL),(4,'Permissions','permissions','','/auth/permissions*',4,1,'2020-11-03 06:19:07',NULL),(5,'Menu','menu','','/auth/menu*',5,1,'2020-11-03 06:19:07',NULL);
/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_menu`
--

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;
INSERT INTO `admin_role_menu` VALUES (1,7,NULL,NULL),(1,8,NULL,NULL),(1,9,NULL,NULL),(1,10,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_permissions`
--

LOCK TABLES `admin_role_permissions` WRITE;
/*!40000 ALTER TABLE `admin_role_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_users`
--

LOCK TABLES `admin_role_users` WRITE;
/*!40000 ALTER TABLE `admin_role_users` DISABLE KEYS */;
INSERT INTO `admin_role_users` VALUES (1,1,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_roles`
--

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;
INSERT INTO `admin_roles` VALUES (1,'Administrator','administrator','2020-11-03 06:19:07','2020-11-03 06:19:07');
/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_settings`
--

LOCK TABLES `admin_settings` WRITE;
/*!40000 ALTER TABLE `admin_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','$2y$10$q89TwZpiDpCFv3EX54C.W.kFgDaKuhLe4ItkbHGxuMcsi.HRWUBiC','Administrator',NULL,'9cnTPi1uT5Gizh2CcRQFluc6wsqHsq5N5ct6jP4ttrrYuzjPT0744EJu5Aal','2020-11-03 06:19:07','2020-11-03 06:19:07');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'趋势','2020-11-04 03:34:59','2020-11-28 04:59:40'),(4,'入场图','2020-11-04 03:36:28','2020-11-04 03:36:28'),(5,'股票','2020-11-04 03:37:03','2020-11-04 03:37:03'),(6,'数字货币','2020-11-04 03:37:09','2020-11-04 03:37:09'),(7,'外汇','2020-11-04 03:37:14','2020-11-04 03:37:14'),(8,'黄金','2020-11-04 03:37:19','2020-11-04 03:37:19'),(9,'周期数列','2020-11-06 09:58:22','2020-11-06 09:58:22'),(10,'亚当理论','2020-11-06 09:58:34','2020-11-06 09:58:34'),(11,'工具','2020-11-06 09:58:45','2020-11-06 09:58:45'),(12,'压力线','2020-11-06 09:59:23','2020-11-06 09:59:23'),(19,'资金管理','2020-11-07 11:57:57','2020-11-07 11:57:57'),(20,'入场方法','2020-11-28 05:19:40','2020-11-28 05:19:40'),(21,'看盘','2020-11-28 05:20:19','2020-11-28 05:20:19');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `types`
--

LOCK TABLES `types` WRITE;
/*!40000 ALTER TABLE `types` DISABLE KEYS */;
INSERT INTO `types` VALUES (1,'入门课程','1000209317','2020-11-04 03:40:12','2020-11-28 05:02:08'),(2,'进阶课程','1000209328','2020-11-04 03:42:19','2020-11-28 05:01:57'),(3,'高手课程','1000209329','2020-11-04 03:42:30','2020-11-28 05:02:17'),(4,'交易实战','1000210409','2020-11-07 11:48:23','2020-11-27 09:13:44'),(5,'直播回放','1000216451','2020-11-27 09:10:25','2020-11-27 09:14:24');
/*!40000 ALTER TABLE `types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `lessons`
--

LOCK TABLES `lessons` WRITE;
/*!40000 ALTER TABLE `lessons` DISABLE KEYS */;
INSERT INTO `lessons` VALUES (1,'基础课程',9.90,199.00,1,'covers/6feaf0582f944d5725c980659dd754d6.jpeg','测试','2020-11-04 03:59:13','2020-11-29 13:09:16'),(2,'趋势判断',199.00,299.00,2,'covers/e7e16600398c2db089ef0d5972fe845c.jpeg','判断趋势是行情分析的开始','2020-11-04 04:35:12','2020-11-29 13:09:36'),(3,'标准入场图',299.00,399.00,2,'covers/5e51e683009268d3d377976f7058c7e8.jpeg','各个趋势下的入场图与如何翻亚当','2020-11-04 04:36:17','2020-11-29 13:09:47'),(4,'中周期入场',599.00,999.00,3,'covers/9843fc7971e4671138725bdb3fe3f459.jpeg','主要讲中周期入场','2020-11-04 04:37:05','2020-11-29 13:10:02'),(5,'资金管理',399.00,599.00,3,'covers/515918d034396f6bbdbe053738e34fea.jpeg','入场位置、止损、资金管理、止盈、拉不赔','2020-11-04 04:37:56','2020-11-29 13:10:20'),(6,'交易实战',299.00,599.00,4,'covers/2eeb271ec7e95a31e89e17c5a398895d.jpeg','实战讲解整个交易系统','2020-11-08 07:09:54','2020-11-29 13:10:48');
/*!40000 ALTER TABLE `lessons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tagables`
--

LOCK TABLES `tagables` WRITE;
/*!40000 ALTER TABLE `tagables` DISABLE KEYS */;
INSERT INTO `tagables` VALUES (8,1,9,NULL,NULL),(9,1,10,NULL,NULL),(16,3,4,NULL,NULL),(20,5,19,NULL,NULL),(22,1,12,NULL,NULL),(23,2,1,NULL,NULL),(25,4,20,NULL,NULL),(26,6,21,NULL,NULL);
/*!40000 ALTER TABLE `tagables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `videos`
--

LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
INSERT INTO `videos` VALUES (2,'认识上涨趋势','854c6b67822541a9b52585937146bcf8',1,0,NULL,1,1,'2020-11-06 00:29:33','2020-11-28 06:54:17'),(3,'周期数列',NULL,1,0,NULL,4,0,'2020-11-07 12:30:35','2020-11-28 06:54:24'),(4,'如何画压力线',NULL,1,0,NULL,6,0,'2020-11-07 12:30:45','2020-11-28 06:54:31'),(5,'亚当理论',NULL,1,0,NULL,8,0,'2020-11-07 12:30:57','2020-11-28 06:54:53'),(6,'什么是趋势线？',NULL,2,0,NULL,1,0,'2020-11-07 12:36:26','2020-11-17 15:12:38'),(7,'用周期数列画趋势线',NULL,2,0,NULL,2,0,'2020-11-07 12:36:39','2020-11-28 04:56:19'),(8,'上涨趋势',NULL,2,0,NULL,3,0,'2020-11-07 12:38:33','2020-11-17 15:15:42'),(9,'上涨趋势入场图',NULL,3,0,NULL,1,0,'2020-11-07 12:39:41','2020-11-08 01:51:51'),(10,'箱体入场图',NULL,3,0,NULL,4,0,'2020-11-07 12:39:52','2020-11-28 12:16:53'),(11,'下跌趋势入场图',NULL,3,0,NULL,3,0,'2020-11-07 13:34:40','2020-11-28 12:16:40'),(15,'为何采用中周期入场？',NULL,4,0,NULL,1,0,'2020-11-08 04:29:24','2020-11-08 04:29:24'),(16,'中周期入场图一',NULL,4,0,NULL,3,0,'2020-11-08 05:08:06','2020-11-27 08:10:41'),(17,'判断主周期的顶是否形成',NULL,4,0,NULL,2,0,'2020-11-08 05:24:11','2020-11-27 15:49:31'),(18,'中周期入场图二',NULL,4,0,NULL,4,0,'2020-11-08 05:34:59','2020-11-27 08:11:43'),(19,'成为优秀的猎手',NULL,4,0,NULL,6,0,'2020-11-08 05:51:19','2020-11-27 15:49:20'),(20,'为何需要资金管理？',NULL,5,0,NULL,1,0,'2020-11-08 06:07:08','2020-11-28 05:29:07'),(23,'你的单笔最大亏损是多少？',NULL,5,0,NULL,2,0,'2020-11-08 06:09:09','2020-11-27 16:09:59'),(24,'如何合理地移动止损线',NULL,5,0,NULL,5,0,'2020-11-08 06:12:35','2020-11-28 12:30:28'),(25,'如何动态止盈',NULL,5,0,NULL,6,0,'2020-11-08 06:12:50','2020-11-28 12:30:33'),(26,'20201106直播',NULL,6,0,NULL,1,0,'2020-11-08 07:12:40','2020-11-08 07:12:40'),(27,'下跌趋势',NULL,2,0,NULL,4,0,'2020-11-11 02:48:02','2020-11-17 15:15:47'),(28,'边界箱体',NULL,2,0,NULL,5,0,'2020-11-11 02:48:14','2020-11-27 12:52:57'),(29,'认识下跌趋势',NULL,1,0,NULL,2,1,'2020-11-17 09:29:35','2020-11-28 06:54:20'),(30,'认识箱体趋势',NULL,1,0,NULL,3,1,'2020-11-17 09:29:56','2020-11-28 06:54:22'),(32,'如何翻亚当',NULL,1,0,NULL,7,0,'2020-11-17 09:47:01','2020-11-28 06:54:44'),(34,'特殊箱体',NULL,2,0,NULL,6,0,'2020-11-17 15:13:35','2020-11-27 12:53:18'),(35,'赚钱要靠大周期',NULL,1,0,NULL,5,0,'2020-11-26 05:09:43','2020-11-28 06:54:27'),(36,'上涨趋势入场条件',NULL,3,0,NULL,2,0,'2020-11-26 16:35:15','2020-11-26 16:35:27'),(38,'持仓量计算公式',NULL,5,0,NULL,3,0,'2020-11-27 08:35:47','2020-11-27 16:10:14'),(39,'判断待入场的顶是否形成',NULL,4,0,NULL,5,0,'2020-11-27 15:48:39','2020-11-29 11:00:51'),(42,'为何不能多空都做',NULL,3,0,NULL,5,0,'2020-11-28 06:52:55','2020-11-28 12:16:57'),(43,'为何不能同时做多笔交易',NULL,5,0,NULL,7,0,'2020-11-28 06:53:57','2020-11-28 12:30:36'),(44,'提前挂单与设置止损',NULL,5,0,NULL,4,0,'2020-11-28 12:29:35','2020-11-28 12:30:23');
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-11-29 21:11:00
