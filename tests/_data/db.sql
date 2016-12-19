-- MySQL dump 10.13  Distrib 5.6.23, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: baseportal
-- ------------------------------------------------------
-- Server version	5.6.23-log

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
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` smallint(5) unsigned NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `fk_auth_assignment_auth_user1_idx` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_auth_assignment_auth_user1` FOREIGN KEY (`user_id`) REFERENCES `auth_user` (`auth_user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('Administrator',1,1455532965);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('Administrator',1,'Администратор системы',NULL,NULL,1455532965,1473228322),('BasePreparatEdit',1,'Оператор справочника \"Препараты\"',NULL,NULL,1465634420,1465634439),('BuildEdit',2,'Редактирование справочника \"Здания\"',NULL,NULL,1456030259,1456030259),('DocfilesEdit',2,'Редактирование справочника \"Загруженные документы\"',NULL,NULL,NULL,NULL),('DocfilesEditRole',1,'Фрегат: Оператор справочника \"Загруженные документы\"',NULL,NULL,NULL,NULL),('DolzhEdit',2,'Редактирование справочника \"Должности\"',NULL,NULL,1456029967,1456029967),('EmailConfig',2,'Настройка электронной почты',NULL,NULL,1473228223,1473228223),('EmailConfigRole',1,'Основная: Изменение настроек электронной почты',NULL,NULL,1473228284,1473228296),('EmployeeBuildEdit',2,'Редактирование здания у сотрудника',NULL,NULL,1466499285,1466499285),('EmployeeBuildEditRole',1,'Основная: Редактирование здания специальности сотрудника',NULL,NULL,1466499422,1466499499),('EmployeeEdit',2,'Редактирование справочника \"Сотрудники\"',NULL,NULL,1456030308,1456030308),('EmployeeSpecEdit',2,'Редактирование специальностей сотрудников',NULL,NULL,1473396095,1473396095),('EmployeeSpecEditRole',1,'Основная: Редактирование специальностей сотрудников',NULL,NULL,1473396135,1473396161),('FregatAdmin',1,'Фрегат: Администратор',NULL,NULL,1456028825,1472452751),('FregatBuildEdit',1,'Фрегат: Оператор справочника \"Здания\"',NULL,NULL,1456030556,1456030565),('FregatConfig',2,'Изменение настроек системы \"Фрегат\"',NULL,NULL,1473227990,1473227990),('FregatConfigRole',1,'Фрегат: Изменение настроек системы',NULL,NULL,1473228027,1473228050),('FregatDolzhEdit',1,'Фрегат: Оператор справочника \"Должности\"',NULL,NULL,1456030475,1456030491),('FregatEmployeeEdit',1,'Фрегат: Оператор справочника \"Сотрудники\"',NULL,NULL,1456030603,1456030623),('FregatGrupaEdit',1,'Фрегат: Оператор справочника \"Группы материальных ценностей\"',NULL,NULL,1456037858,1456037875),('FregatHozSister',1,'Фрегат: Сестра-хозяйка',NULL,NULL,1472037875,1472451328),('FregatImport',2,'Настройка импорта из 1С',NULL,NULL,1456028535,1456028535),('FregatImportOperator',1,'Фрегат: Оператор импорта из 1С',NULL,NULL,1456028677,1456028729),('FregatInstallEdit',1,'Фрегат: Оператор журнала перемещений материальных ценностей',NULL,NULL,1467014876,1467014892),('FregatIzmerEdit',1,'Фрегат: Оператор справочника \"Единицы измерения\"',NULL,NULL,1467013627,1467014442),('FregatMaterialEdit',1,'Фрегат: Оператор прихода материальных ценностей',NULL,NULL,1472037237,1472037273),('FregatMaterialMolDelete',1,'Фрегат: Удаление привязки материально-ответственного лица к материальной ценности',NULL,NULL,1472037330,1472037350),('FregatMatvidEdit',1,'Фрегат: Оператор справочника \"Виды материальных ценностей\"',NULL,NULL,1456037699,1456037762),('FregatMolEdit',1,'Фрегат: Смена материально-ответственного лица',NULL,NULL,1472451295,1472451307),('FregatOrganEdit',1,'Фрегат: Оператор справочника \"Организации\"',NULL,NULL,1460973296,1460973308),('FregatOsmotraktEdit',1,'Фрегат: Оператор журнала осмотров материальных ценностей',NULL,NULL,1467014674,1467014674),('FregatPodrazEdit',1,'Фрегат: Оператор справочника \"Подразделения\"',NULL,NULL,1456030519,1456030532),('FregatReasonEdit',1,'Фрегат: Оператор справочника \"Шаблоны актов осмотра материальной ценности\"',NULL,NULL,1460973319,1460973331),('FregatRecoveryEdit',1,'Фрегат: Оператор журналов восстановления материальных ценностей',NULL,NULL,1472037470,1472037480),('FregatRemoveaktEdit',1,'Фрегат: Оператор журнала снятия комплектующих с материальных ценностей',NULL,NULL,1472037520,1472038072),('FregatSpravEdit',1,'Фрегат: Оператор справочников',NULL,NULL,1456030666,1456037920),('FregatUserPermission',2,'Пользователь \"Фрегат\"',NULL,NULL,1456028493,1456028493),('FregatUserRole',1,'Фрегат: Пользователь',NULL,NULL,1456028592,1456028770),('GlaukAdmin',1,'Регистр глаукомных пациентов: Администратор',NULL,NULL,1465634943,1465652494),('GlaukOperatorPermission',2,'Оператор регистра глаукомных пациентов',NULL,NULL,1465634651,1465634651),('GlaukOperatorRole',1,'Регистр глаукомных пациентов: Оператор',NULL,NULL,1465634774,1465634813),('GlaukUserPermission',2,'Пользователь регистра глаукомных пациентов',NULL,NULL,1465634609,1465634609),('GlaukUserRole',1,'Регистр глаукомных пациентов: Пользователь',NULL,NULL,1465634723,1465634737),('GrupaEdit',2,'Редактирование справочника \"Группы материальных ценностей\"',NULL,NULL,1456037618,1456037618),('InstallEdit',2,'Редактирование журнала перемещений материальных ценностей',NULL,NULL,1467014841,1467014841),('IzmerEdit',2,'Редактирование справочника \"Единицы измерения\"',NULL,NULL,1467013578,1467013578),('MaterialEdit',2,'Редактирование прихода материальной ценности',NULL,NULL,1472037016,1472037016),('MaterialMolDelete',2,'Удаление привязки материально-ответственных лиц к материальной ценности',NULL,NULL,1472037061,1472037390),('MatvidEdit',2,'Редактирование справочника \"Виды материальных ценностей\"',NULL,NULL,1456037581,1456037581),('MolEdit',2,'Смена материально-ответственного лица',NULL,NULL,1472451282,1472451282),('NakladEdit',2,'╨а╨╡╨┤╨░╨║╤В╨╕╤А╨╛╨▓╨░╨╜╨╕╨╡ ╨╢╤Г╤А╨╜╨░╨╗╨░ ╤В╤А╨╡╨▒╨╛╨▓╨░╨╜╨╕╨╣-╨╜╨░╨║╨╗╨░╨┤╨╜╤Л╤Е',NULL,NULL,NULL,NULL),('NakladEditRole',1,'╨д╤А╨╡╨│╨░╤В: ╨Ю╨┐╨╡╤А╨░╤В╨╛╤А ╨╢╤Г╤А╨╜╨░╨╗╨░ ╤В╤А╨╡╨▒╨╛╨▓╨░╨╜╨╕╨╣-╨╜╨░╨║╨╗╨░╨┤╨╜╤Л╤Е',NULL,NULL,NULL,NULL),('OrganEdit',2,'Редактирование справочника \"Организации\"',NULL,NULL,1460973255,1460973255),('OsmotraktEdit',2,'Редактирование журнала осмотров материальных ценностей',NULL,NULL,1467014608,1467014608),('PatientRemove',2,'Разрешение на удаление пациентов',NULL,NULL,1465652376,1465652376),('PatientRemoveRole',1,'Удаление пациентов из системы',NULL,NULL,1465652429,1465652450),('PodrazEdit',2,'Редактирование справочника \"Подразделения\"',NULL,NULL,1456030230,1456030230),('PreparatEdit',2,'Редактирование справочника \"Препараты\"',NULL,NULL,1465634361,1465634361),('ReasonEdit',2,'Редактирование справочника \"Шаблоны актов осмотра материальной ценности\"',NULL,NULL,1460973272,1460973272),('RecoveryEdit',2,'Редактирование восстановления материальных ценностей',NULL,NULL,1472037093,1472037093),('RemoveaktEdit',2,'Редактирование снятия комплектующих с материальных ценностей',NULL,NULL,1472037133,1472037133),('RoleEdit',2,'Редактирование ролей пользователя',NULL,NULL,1455944269,1455944269),('RoleOperator',1,'Оператор менеджера ролей',NULL,NULL,1455944597,1455945058),('SchetuchetEdit',2,'Редактирование справочника \"Счета учета\"',NULL,NULL,NULL,NULL),('SchetuchetEditRole',1,'Фрегат: Оператор справочника \"Счета учета\"',NULL,NULL,NULL,NULL),('SpisosnovaktEdit',2,'Редактирование журнала списания основных средств',NULL,NULL,NULL,NULL),('SpisosnovaktEditRole',1,'Фрегат: Оператор журнала списания основных средств',NULL,NULL,NULL,NULL),('UserEdit',2,'Редактирование пользователей',NULL,NULL,1455944445,1455944445),('UserOperator',1,'Оператор менеджера пользователей',NULL,NULL,1455944546,1455944564);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` VALUES ('GlaukAdmin','BasePreparatEdit'),('FregatBuildEdit','BuildEdit'),('DocfilesEditRole','DocfilesEdit'),('FregatSpravEdit','DocfilesEditRole'),('FregatDolzhEdit','DolzhEdit'),('EmailConfigRole','EmailConfig'),('Administrator','EmailConfigRole'),('EmployeeBuildEditRole','EmployeeBuildEdit'),('FregatEmployeeEdit','EmployeeEdit'),('EmployeeSpecEditRole','EmployeeSpecEdit'),('Administrator','FregatAdmin'),('FregatSpravEdit','FregatBuildEdit'),('FregatConfigRole','FregatConfig'),('FregatAdmin','FregatConfigRole'),('FregatSpravEdit','FregatDolzhEdit'),('FregatSpravEdit','FregatEmployeeEdit'),('FregatSpravEdit','FregatGrupaEdit'),('FregatImportOperator','FregatImport'),('FregatAdmin','FregatImportOperator'),('FregatAdmin','FregatInstallEdit'),('FregatHozSister','FregatInstallEdit'),('FregatSpravEdit','FregatIzmerEdit'),('FregatAdmin','FregatMaterialEdit'),('FregatAdmin','FregatMaterialMolDelete'),('FregatSpravEdit','FregatMatvidEdit'),('FregatAdmin','FregatMolEdit'),('FregatHozSister','FregatMolEdit'),('FregatSpravEdit','FregatOrganEdit'),('FregatAdmin','FregatOsmotraktEdit'),('FregatHozSister','FregatOsmotraktEdit'),('FregatSpravEdit','FregatPodrazEdit'),('FregatSpravEdit','FregatReasonEdit'),('FregatAdmin','FregatRecoveryEdit'),('FregatHozSister','FregatRecoveryEdit'),('FregatAdmin','FregatRemoveaktEdit'),('FregatHozSister','FregatRemoveaktEdit'),('FregatAdmin','FregatSpravEdit'),('FregatHozSister','FregatUserPermission'),('FregatUserRole','FregatUserPermission'),('FregatAdmin','FregatUserRole'),('Administrator','GlaukAdmin'),('GlaukOperatorRole','GlaukOperatorPermission'),('GlaukAdmin','GlaukOperatorRole'),('GlaukOperatorRole','GlaukUserPermission'),('GlaukUserRole','GlaukUserPermission'),('FregatGrupaEdit','GrupaEdit'),('FregatInstallEdit','InstallEdit'),('FregatIzmerEdit','IzmerEdit'),('FregatMaterialEdit','MaterialEdit'),('FregatMaterialMolDelete','MaterialMolDelete'),('FregatMatvidEdit','MatvidEdit'),('FregatMolEdit','MolEdit'),('NakladEditRole','NakladEdit'),('FregatAdmin','NakladEditRole'),('FregatHozSister','NakladEditRole'),('FregatOrganEdit','OrganEdit'),('FregatOsmotraktEdit','OsmotraktEdit'),('PatientRemoveRole','PatientRemove'),('Administrator','PatientRemoveRole'),('FregatPodrazEdit','PodrazEdit'),('BasePreparatEdit','PreparatEdit'),('FregatReasonEdit','ReasonEdit'),('FregatRecoveryEdit','RecoveryEdit'),('FregatRemoveaktEdit','RemoveaktEdit'),('RoleOperator','RoleEdit'),('Administrator','RoleOperator'),('SchetuchetEditRole','SchetuchetEdit'),('FregatSpravEdit','SchetuchetEditRole'),('SpisosnovaktEditRole','SpisosnovaktEdit'),('FregatAdmin','SpisosnovaktEditRole'),('FregatHozSister','SpisosnovaktEditRole'),('UserOperator','UserEdit'),('Administrator','UserOperator');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_rule`
--

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_user`
--

DROP TABLE IF EXISTS `auth_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_user` (
  `auth_user_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `auth_user_fullname` char(128) NOT NULL COMMENT 'Фамилия Имя Отчество',
  `auth_user_login` char(128) NOT NULL COMMENT 'Логин',
  `auth_user_password` char(255) NOT NULL COMMENT 'Пароль',
  PRIMARY KEY (`auth_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1196 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_user`
--

LOCK TABLES `auth_user` WRITE;
/*!40000 ALTER TABLE `auth_user` DISABLE KEYS */;
INSERT INTO `auth_user` VALUES (1,'АДМИНИСТРАТОР','admin','$2y$13$kq0OMrtT/xoNnKgjhf.pAuZFT3EXQk/BwClTiQTIR61mALPxoEtOG');
/*!40000 ALTER TABLE `auth_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authassignment`
--

DROP TABLE IF EXISTS `authassignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authassignment` (
  `itemname` varchar(64) NOT NULL COMMENT 'Наименование',
  `userid` int(11) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  KEY `userid` (`userid`),
  CONSTRAINT `authassignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `authassignment_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authassignment`
--

LOCK TABLES `authassignment` WRITE;
/*!40000 ALTER TABLE `authassignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `authassignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authassignmentldap`
--

DROP TABLE IF EXISTS `authassignmentldap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authassignmentldap` (
  `itemname` varchar(64) NOT NULL COMMENT 'Наименование',
  `groupid` int(11) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`groupid`),
  KEY `groupid` (`groupid`),
  CONSTRAINT `authassignmentldap_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `authassignmentldap_ibfk_2` FOREIGN KEY (`groupid`) REFERENCES `groupldap` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authassignmentldap`
--

LOCK TABLES `authassignmentldap` WRITE;
/*!40000 ALTER TABLE `authassignmentldap` DISABLE KEYS */;
/*!40000 ALTER TABLE `authassignmentldap` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authitem`
--

DROP TABLE IF EXISTS `authitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authitem` (
  `name` varchar(64) NOT NULL COMMENT 'Имя роли (на латинице)',
  `type` int(11) NOT NULL COMMENT 'Тип роли',
  `description` text COMMENT 'Имя роли (описание)',
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authitem`
--

LOCK TABLES `authitem` WRITE;
/*!40000 ALTER TABLE `authitem` DISABLE KEYS */;
/*!40000 ALTER TABLE `authitem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authitemchild`
--

DROP TABLE IF EXISTS `authitemchild`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `authItemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `authItemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authitemchild`
--

LOCK TABLES `authitemchild` WRITE;
/*!40000 ALTER TABLE `authitemchild` DISABLE KEYS */;
/*!40000 ALTER TABLE `authitemchild` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `build`
--

DROP TABLE IF EXISTS `build`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `build` (
  `build_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `build_name` char(100) NOT NULL COMMENT 'Здание',
  PRIMARY KEY (`build_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `build`
--

LOCK TABLES `build` WRITE;
/*!40000 ALTER TABLE `build` DISABLE KEYS */;
/*!40000 ALTER TABLE `build` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_mkb`
--

DROP TABLE IF EXISTS `class_mkb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_mkb` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `name` varchar(512) NOT NULL COMMENT 'Наименование диагноза',
  `code` varchar(20) NOT NULL COMMENT 'Код МКБ10',
  `parent_id` int(11) DEFAULT NULL COMMENT 'Вышестоящий объект',
  `parent_code` varchar(20) DEFAULT NULL COMMENT 'Код вышестоящего объекта',
  `node_count` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Количество вложенных в текущую ветку',
  `additional_info` text COMMENT 'Дополнительные данные по диагнозу',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `parent_code` (`parent_code`),
  CONSTRAINT `class_mkb_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `class_mkb` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22769 DEFAULT CHARSET=utf8 COMMENT='МКБ-10 Международный классификатор болезней';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_mkb`
--

LOCK TABLES `class_mkb` WRITE;
/*!40000 ALTER TABLE `class_mkb` DISABLE KEYS */;
INSERT INTO `class_mkb` VALUES (6204,'ГЛАУКОМА','H40-H42',6001,'H00-H59',2,NULL),(6205,'Глаукома','H40',6204,'H40-H42',9,'Исключены: абсолютная глаукома (H44.5) врожденная глаукома (Q15.0) травматическая глаукома при родовой травме (P15.3)'),(6206,'Подозрение на глаукому','H40.0',6205,'H40',0,'Глазная гипертензия'),(6207,'Первичная открытоугольная глаукома','H40.1',6205,'H40',0,'Глаукома (первичная) (остаточная стадия): . капсулярная с ложным отслоением хрусталика . хроническая простая . с низким давлением . пигментная'),(6208,'Первичная закрытоугольная глаукома','H40.2',6205,'H40',0,'Закрытоугольная глаукома (первичная) (остаточная стадия): . острая . хроническая . перемежающаяся'),(6209,'Глаукома вторичная посттравматическая','H40.3',6205,'H40',0,'При необходимости идентифицировать причину используют дополнительный код.'),(6210,'Глаукома вторичная вследствие воспалительного заболевания глаза','H40.4',6205,'H40',0,'При необходимости идентифицировать причину используют дополнительный код.'),(6211,'Глаукома вторичная вследствие других болезней глаз','H40.5',6205,'H40',0,'При необходимости идентифицировать причину используют дополнительный код.'),(6212,'Глаукома вторичная, вызванная приемом лекарственных средств','H40.6',6205,'H40',0,'При необходимости идентифицировать лекарственный препарат, вызвавший поражение, используют дополнительный код внешних причин (класс XX).'),(6213,'Другая глаукома','H40.8',6205,'H40',0,NULL),(6214,'Глаукома неуточненная','H40.9',6205,'H40',0,NULL);
/*!40000 ALTER TABLE `class_mkb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `docfiles`
--

DROP TABLE IF EXISTS `docfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `docfiles` (
  `docfiles_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `docfiles_name` char(255) NOT NULL COMMENT '╨Ш╨╝╤П ╤Д╨░╨╣╨╗╨░',
  `docfiles_hash` char(255) NOT NULL COMMENT '╨Ш╨╝╤П ╤Д╨░╨╣╨╗╨░ ╨▓ ╤Д╨░╨╣╨╗╨╛╨▓╨╛╨╣ ╤Б╨╕╤Б╤В╨╡╨╝╨╡',
  `docfiles_ext` char(10) NOT NULL COMMENT '╨а╨░╤Б╤И╨╕╤А╨╡╨╜╨╕╨╡ ╤Д╨░╨╣╨╗╨░',
  PRIMARY KEY (`docfiles_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `docfiles`
--

LOCK TABLES `docfiles` WRITE;
/*!40000 ALTER TABLE `docfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `docfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dolzh`
--

DROP TABLE IF EXISTS `dolzh`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dolzh` (
  `dolzh_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `dolzh_name` char(100) NOT NULL COMMENT 'Должность',
  PRIMARY KEY (`dolzh_id`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dolzh`
--

LOCK TABLES `dolzh` WRITE;
/*!40000 ALTER TABLE `dolzh` DISABLE KEYS */;
/*!40000 ALTER TABLE `dolzh` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee` (
  `employee_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Код специальности',
  `id_dolzh` smallint(5) unsigned NOT NULL COMMENT 'Должность',
  `id_podraz` smallint(5) unsigned NOT NULL COMMENT 'Подразделение',
  `id_build` tinyint(3) unsigned DEFAULT NULL COMMENT 'Здание',
  `id_person` smallint(5) unsigned DEFAULT NULL COMMENT 'Сотрудник',
  `employee_username` char(128) NOT NULL COMMENT 'Пользователь изменивший запись',
  `employee_lastchange` datetime NOT NULL COMMENT 'Дата изменения записи',
  `employee_dateinactive` date DEFAULT NULL COMMENT 'Дата с которой специальность неактивна',
  `employee_forinactive` tinyint(1) unsigned DEFAULT NULL COMMENT 'Используется при импорте сотрудников',
  `employee_importdo` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Запись изменяема при импортировании',
  PRIMARY KEY (`employee_id`),
  KEY `fk_employee_dolzh1_idx` (`id_dolzh`),
  KEY `fk_employee_podraz1_idx` (`id_podraz`),
  KEY `fk_employee_build1_idx` (`id_build`),
  KEY `fk_employee_auth_user1_idx` (`id_person`),
  CONSTRAINT `fk_employee_auth_user1` FOREIGN KEY (`id_person`) REFERENCES `auth_user` (`auth_user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_employee_build1` FOREIGN KEY (`id_build`) REFERENCES `build` (`build_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_employee_dolzh1` FOREIGN KEY (`id_dolzh`) REFERENCES `dolzh` (`dolzh_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_employee_podraz1` FOREIGN KEY (`id_podraz`) REFERENCES `podraz` (`podraz_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1175 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employeelog`
--

DROP TABLE IF EXISTS `employeelog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employeelog` (
  `employeelog_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_logreport` int(10) unsigned NOT NULL,
  `employeelog_filename` char(255) NOT NULL COMMENT 'Имя файла',
  `employeelog_filelastdate` datetime DEFAULT NULL COMMENT 'Дата изменения файла',
  `employeelog_rownum` mediumint(9) NOT NULL COMMENT 'Номер строки',
  `employeelog_type` tinyint(1) NOT NULL COMMENT 'Тип сообщения',
  `employeelog_message` varchar(1000) NOT NULL COMMENT 'Сообщение',
  `employee_fio` char(255) DEFAULT NULL COMMENT 'ФИО сотрудника',
  `dolzh_name` char(255) DEFAULT NULL COMMENT 'Должность',
  `podraz_name` char(255) DEFAULT NULL COMMENT 'Подразделение',
  `build_name` char(255) DEFAULT NULL COMMENT 'Здание',
  PRIMARY KEY (`employeelog_id`),
  KEY `fk_employeelog_logreport1_idx` (`id_logreport`),
  CONSTRAINT `fk_employeelog_logreport1` FOREIGN KEY (`id_logreport`) REFERENCES `logreport` (`logreport_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employeelog`
--

LOCK TABLES `employeelog` WRITE;
/*!40000 ALTER TABLE `employeelog` DISABLE KEYS */;
/*!40000 ALTER TABLE `employeelog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fias`
--

DROP TABLE IF EXISTS `fias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fias` (
  `AOGUID` char(36) NOT NULL,
  `OFFNAME` char(120) NOT NULL,
  `SHORTNAME` char(10) NOT NULL,
  `IFNSFL` char(4) NOT NULL,
  `AOLEVEL` int(11) NOT NULL,
  `PARENTGUID` char(36) NOT NULL,
  PRIMARY KEY (`AOGUID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fias`
--

LOCK TABLES `fias` WRITE;
/*!40000 ALTER TABLE `fias` DISABLE KEYS */;
INSERT INTO `fias` VALUES ('00092ea0-5fc2-4e9c-bc8a-0f049c315535','Меторождение нефти Варьёганское','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('00a53583-07b8-42e1-9af4-cd35b6134e9c','Кедровая','ул','8603',7,'ee5884c3-4dd9-42dd-9542-e8f704a2c852'),('0100445f-0924-4bc0-b3a2-11134e611eb2','Садово-огородническое товарищество \"Наладчик\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('010b0710-e8f4-4412-bed7-bf892a624102','Садово-огородническое некоммерческое товарищество \"Химик\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('013fbc64-efa3-446b-b217-999709e61651','Лицензионный участок Бахиловский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('017d3cdb-0584-4ad3-943f-3cc5eec27411','Новоаганск','пгт','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('019c4f3c-b477-4b0c-bcd3-c718ce5c4872','Садово-огородническое товарищество \"Татра\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('019ebbde-4ec8-4aa0-a424-e45e0180cd8d','Береговая','ул','8603',7,'3cfb0a60-f45c-44c5-bd2c-f58fdc87a9d0'),('01a7f003-6edd-4ab5-b980-4089c40907c7','Геологов','ул','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('02094704-8773-4c4e-b83a-e67c2e946b41','Садово-огородническое товарищество \"Щит\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('022ee97e-e15a-4e8c-8696-f2860608aa69','Садово-огородническое товарищество \"Колер\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('023ec44c-3fc6-4678-8e69-6803696432b3','Производственная база','тер','8603',90,'b5a933b0-b567-495e-9bea-ba80d2900833'),('0257298d-d242-476b-8435-1013991843b4','Центральная','ул','8603',7,'73dd5544-5d41-44aa-a90b-c19724237f96'),('0267fc23-b14f-412c-a097-ba604c5b25a2','4П','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('0288dfd3-0fb3-4e18-aa24-33fd39cf5a7a','УМР-2','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('02dc11a6-4e30-454a-b2ab-2233df0f5ba5','НЗРА','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('02e7d259-cedb-4634-ab5c-17cd00bf6277','Энтузиастов','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('02ed9ea0-f14d-4daf-896a-506f80988ba7','Садоводческое товарищество \"Ермак\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('03156d1d-79e2-4e27-bf78-d82419846790','Комсомольский','б-р','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('031c9b1c-f30a-478b-9721-996724f487f9','Спортивная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('033ad295-b9ff-4551-8540-1088075d7e74','Садовое некомерческое товарищество \"Иртыш\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('037c5014-f975-4f2f-8783-68e9b4b0e425','Полевой','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('03c33488-fd5a-4b30-93d2-ef765d7df07d','Тихая','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('04a83e8f-763b-4b25-90c1-7346edd2a00b','НАСКО-Радужный','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('04bf08ba-0da6-441a-83c7-a96186ba8ba7','Заводская','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('04ee302c-7125-4e13-ba6b-71b61da270ac','СОТ \"Водник-2\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('04f22621-5058-4c5c-9504-6ad840d6bb18','Новая','ул','8603',7,'91e27416-360a-4e1a-aef6-be21d69ff97f'),('052746df-a671-4069-81f6-b56eb49d5221','Лицензионный участок Северо-Покачевский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('052c5f94-a384-46d2-a62f-93cbff68030f','Восточная','ул','8603',7,'ee5884c3-4dd9-42dd-9542-e8f704a2c852'),('0555b7da-947e-4451-a4a9-caf8b30d6c89','Цветочная','ул','8603',7,'d77ac5d7-461f-4fde-9bb4-3028366ec592'),('05b346ad-1e30-41fe-87da-e1d6514cb3ed','ЗНС-УМ-5','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('05cde66b-2efb-4506-9903-2e32630e0147','7ПС','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('062ae276-5f1b-493d-a978-36c8b296e857','Весенняя','ул','8603',7,'e2233268-280d-4f7a-acb6-90abc99b9629'),('066da0c0-9b11-43e5-917f-c351e18ce857','Зайцева Речка','п','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('06b16d64-f01c-411c-94cb-2a831b41791f','Рыбников','ул','8603',7,'7da55fe9-4830-4a1d-8b06-232b118f213a'),('06e5dc64-93aa-4027-9bc6-16c1c8e81985','Самотлорная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('06e91092-2c73-45e7-87c7-911ddfc4b33d','Садово-огородническое товарищество \"Автомобилист-1\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('06f7721c-6c5c-4fd3-820c-61e00ce58f2c','Таежная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('073aa211-73a3-4422-a529-338030dd4520','Садовая','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('0757b3d2-4e8e-4fdf-b517-e266bf6a6aeb','ДРСУ','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('078470ce-978e-4221-a729-7d859051fa62','Первопоселенцев','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('078af68d-30b5-4be3-8983-ca6c7c6425be','Нижневартовский','р-н','8603',3,'d66e5325-3a25-4d29-ba86-4ca351d9704b'),('07ea35ad-5110-470a-ba01-5f7f1c0a4f81','Кедровый','пер','8603',7,'cef36edf-c8f7-474d-a3e6-17573924be26'),('086e3147-be7e-4aed-ac7d-beeaba263755','Школьная','ул','8603',7,'d77ac5d7-461f-4fde-9bb4-3028366ec592'),('08973732-00f8-408b-9033-4b7ddd62c1ff','Садово-огородническое товарищество \"Люмас\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('08aafc7d-5cd8-4bc4-8808-f66b03129835','Тампонажная Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('08f41083-e46b-467f-985e-31e6a72c9da0','Зеленый','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('0a06c768-00c4-4953-9155-46a8934bd854','6-я','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('0a0b7513-66fa-4fff-905e-a901dbcefed6','Новая','ул','8603',7,'7da55fe9-4830-4a1d-8b06-232b118f213a'),('0a3baae0-a12a-4a3b-ba42-f4ba4c2dc670','Бамовская','ул','8603',7,'73dd5544-5d41-44aa-a90b-c19724237f96'),('0a7666a8-2c8a-48ad-bb0d-037552c55d82','60 лет Октября','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('0a84afe6-f945-4e39-bf90-525d5d06e139','Садово-огородническое товарищество \"Фантазия\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('0b05dbc3-d99b-4ebe-a372-1b4e059069d6','Лицензионный участок Ватинский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('0b15e863-86f5-486a-ab11-f9ebddfce91a','7а','мкр','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('0b559a11-f777-4acc-bfcb-bb1afe209bbd','Садово огородническое товарищество \"Дорожник-3\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('0ba9e5dc-e085-44e8-a7cb-d2176afa1fca','ВССУ','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('0be72058-4df7-4806-9d77-e6df972278fe','Южная промышленная зона','промзона','8603',6,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('0bf0f4ed-13f8-446e-82f6-325498808076','Нижневартовск','г','8603',4,'d66e5325-3a25-4d29-ba86-4ca351d9704b'),('0bf39b0d-35b8-45d9-9559-0f6af48a8390','Белорусского УБР','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('0c2a824d-93f4-4b39-b7f2-97bec6c29521','Садово-огородническое некоммерческое товарищество \"Бытовик\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('0c566a7c-963d-4c85-a9e9-2c7c6971cb91','Дачное некоомерческое товарищество инвалидов по зрению \"Радуга\"','днп','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('0c7e434c-3bff-4540-b153-52f73aa11ea0','Титова','ул','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('0c9e8464-0709-465a-b2e8-d01bdfdee36c','Набережная','ул','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('0cfddec3-0693-4057-b5df-6edca0f8893d','Садово-огородническое потребительское общество \"Старт\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('0d1e0bcf-4cd9-4aa9-b230-55f30ce9757e','Автодорога \"г. Мегион-г. Лангепас\"','промзона','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('0d230dd5-94ed-42aa-842c-50a71e9962a5','Садовая','ул','8603',7,'6b7f49f1-c9c0-4d5e-b2c6-2b1c1f9a0dc9'),('0d63ee26-5f02-4e75-bf35-5a9c5a2886d3','Ламбина','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('0dcffa2e-d550-4a4b-8715-7a7fa9d301e6','Баклажан','снт','8603',90,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('0de4bd53-4830-4322-9baf-3c929f7ecfcf','Садово-огородническое товарищество','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('0e29e82d-85b6-4f84-90ff-71f985d9a94a','15-я','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('0e38b9c8-6ec3-47d9-961b-06b0c5b9a3e3','Садово-огородническое товарищество \"Жилищник\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('0e57d0f1-6050-4db5-affa-75dd124fcadf','Дачное некомерческое товарищество \"Вышкостроитель\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('0e631c1e-1c4c-4907-a4f3-c22bf5d2d677','Садоводческое огородническое некоммерческое товарищество \"Мега-2\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('0e8a65fd-3732-4703-95e0-86e7b410506d','Мелик-Карамова','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('0e913a46-2551-46b0-8e57-ea6e64851004','Пролетарская','ул','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('0ec3f892-8442-461e-8010-45b5b119d53a','Гагарина','ул','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('0ed3de0e-6704-4a07-ba46-eb5471226d26','Садоводческое, огородническое некомерческое товарищество \"Природа\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('0ef37d60-ef17-47ca-8472-801c11733e72','Светлая','ул','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('0fab1bab-491b-4b20-ac65-30fe41a9a8ce','Садово-огородническое некоммерческое товарищество \"Дорожник-2\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('0fc9fa51-b1af-4ade-a771-0c90502d3bac','Потребительский садово-огороднический кооператив \"Энергетик Севера\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('0fe5d3a3-063c-4869-bafa-279ff97bc17b','Карьерная Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('0fe5f4dd-21ff-4908-9916-a83096158b3c','Речная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('101a2f32-022a-4a00-9eda-c594f8856ad0','Усть-Колекъеган','д','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('103ec544-ac02-4e47-a78f-e9e906c32b36','Трудовой','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('10b3c79f-4a84-4c99-b7e0-6035bc8e8ab9','Летняя','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1106f2b2-3407-40b8-87dc-cefe3adf35a4','Лицензионный участок Ершовый','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('115946e2-6b15-47a6-b6b6-cffc4f8728a8','Пермская','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('11886072-72b4-481e-83fb-6733bf5f275a','Колекъеган','д','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('11bee0b0-fb04-4c58-abe5-7fe4ddb71ef5','Меторождение нефти Егурьяхское','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('11c4f5e9-8472-4b04-9c9c-3f88fb8ced30','Садово-огородническое товарищество \"Калинка\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('11f89f99-ab74-4d68-8c8b-fb3cb66d03ba','Садово огородническое товарищество \"Волна\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('124ff59b-2c11-4bd2-84db-9e8f74aea9b5','2ПС','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('12b736df-199d-4e89-acc6-dad4bae3d395','Геологов','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('12cd090f-af9f-4322-81ee-d8fc21d00b4a','Лесная','ул','8603',7,'91e27416-360a-4e1a-aef6-be21d69ff97f'),('13f670ff-11ff-4282-a062-2d99daaadef0','Садово-огородническое некоммерческое товарищество \"Ветеран\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('140c5526-baf9-433e-902f-c736a88ccbe3','Энергетиков','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('1444d975-a33d-43f8-b1fd-d0bbb3c3cf70','Школьная','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('1497984c-836d-40bd-9499-559a56d50566','Садово-огородническое некоммерческое товарищество \"Брусничка\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('14a5077b-ee7e-4e5e-ba78-ef604218c220','Кедровый','снт','8603',90,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('14b7f9f6-e9cd-49a4-93a0-b7646540e4c5','Школьный','пер','8603',7,'18b9ce52-c79f-4133-97a9-c19a0d4b1e0f'),('14c4de8b-7df7-41e9-bf9a-36ddf12226ac','Садовое товарищество \"Сибиряк-1\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('150db4a1-9cb5-490a-8031-e392d1804da4','Садово-огородническое некоммерческое товарищество \"Мега-84\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('158968ae-1426-4486-ac39-f0b8b14108a4','Рябиновый','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('163464df-882d-4a8d-aa5e-4c467c78886b','ВМУ-1','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1653241f-6209-4ace-8e93-a2d79234e689','Теплотехник','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('16807b3b-c163-4f35-82de-ccb1fd11a97e','Былино','с','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('1688fc1b-04ba-4fb8-89bc-7101571c33fa','УБР-2','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1769e7f2-1c5a-4332-93e4-fbadb6ca2740','Музейный','пер','8603',7,'3dcc3e84-518a-4b33-90d2-fb322a084fe6'),('186512cb-43e7-4fa7-9755-a283b6f8ea80','Савкинская','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('18b9ce52-c79f-4133-97a9-c19a0d4b1e0f','Чехломей','д','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('191f4182-6224-49cf-a159-085d504fa7f0','Северный','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('199ddc15-24b3-4d91-83b0-38b538cb15bf','МУ-15','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('19a314c6-7d2e-4d9a-b841-2db3129b5929','Парк Победы','тер','8603',90,'e9163806-f1ee-449a-b71c-aa3eb251a17a'),('1aafde38-da42-4ec3-947b-653c21fb44fc','Юбилейный','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1b2315cc-e86c-4aa9-9f4e-1d6f5c70fc19','Таежный','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('1b4e0eac-5c6b-472a-b0a8-49ee6b91b63d','Садово-огородническое некоммерческое товарищество \"Надежда\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('1b9aae98-790a-4e76-b93e-3910a21c99a9','Садово-огороднический кооператив \"Лада\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1bb75b5d-7dde-45a2-9776-14a5bc5ef162','Приречная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('1bf9e2c2-c666-4a71-abe6-ab838a5de229','Дачное некоммерческое товарищество \"Огородник-3\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1c117398-960d-4084-922c-ff1aef5aea6f','АБ-2','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1c9716a2-c763-402b-9465-6b6f5172392e','Аэродромная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('1cb110a7-6efc-431b-8eef-499b9c67fbaf','Проточная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('1cf6968a-0796-468f-ab64-8a227221bfcf','Дачное некоммерческое товарищество \"Ручеёк\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1d05cbbb-53f3-4cb6-851d-381ba91f4947','Садово-огородническое товарищество \"СОНТ Транспортник-9\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('1d50f37c-761c-4ece-976b-f5bb18bf53a6','Садово-огородническое некомерческое товарищество \"Безымянное\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('1d70eed1-c7ff-407f-adbc-084cd605ac9c','Нижневартовский','тракт','8603',7,'0be72058-4df7-4806-9d77-e6df972278fe'),('1dfa30a8-91a7-4c01-be0a-798836e0284a','Производственная база','тер','8603',90,'cba79f66-635b-4721-b599-4481e9ae8114'),('1e235b7b-d539-4fc6-a4dd-0ac5062be5b0','УТТ-2','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1e4b8fa3-f818-4978-ab0a-d972f99b098b','Охтеурский','д','8603',6,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1e91b40a-2c89-480a-924f-ff2f217d170c','Трактовый','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1e9766b7-aa2d-438b-9aef-b9eeb63c2122','Губкина','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('1e9a8832-b261-4843-8ae2-bb2a371aa1c1','Рябиновый','б-р','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1e9e7c26-6bd4-4646-b27a-f20b2ae03f2f','Энергетик','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('1f1ba8c4-f006-44c8-9b88-b8f09fbae527','Садово-огородническое некоммерческое товарищество \"Строитель-86\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1f3eb987-6ae0-4c9f-bdd2-e628b84167cd','Садово-огородническое товарищество \"Факел НВ\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1f41d58c-d8d4-4ecd-bb26-0c28ad0d90d0','Светлый','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1f9440c2-585c-4095-87be-b86f3f869297','Садово-огородническое некоммерческое товарищество \"Авиатор-1\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('1f985992-71d8-4b7c-98ea-3fda0d7005fd','Производственная база','тер','8603',90,'27980690-f844-4536-985c-ce9ba7aa3b4b'),('1fb6c0e1-d03a-4fe3-a6ee-53645dd57d52','Вишневая','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('2059539d-4136-4acd-9dcc-433658680841','Садовая','ул','8603',7,'e2233268-280d-4f7a-acb6-90abc99b9629'),('20853ac5-ad87-46fa-bcb2-141359c89d5e','Садово-огородническое товарищество \"Горэлектросеть-1\"','гск','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('209f610e-4bbe-4b69-a212-6eea6f3ac3cc','Рябиновая','ул','8603',7,'e2233268-280d-4f7a-acb6-90abc99b9629'),('20eb8109-a00e-4628-b1a7-1d9fb5794037','Урьевнефть','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('216784ab-453e-439f-8fb7-3fb3b3fbecbe','Больничный','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('21907740-501c-42ad-8144-7b95e93a26ea','СОК \"Газ\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('21f62467-146e-4fd0-a330-3e94ae491985','Садоводческое товарищество \"Луговое\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('224da8d1-cb17-41d3-8b32-cfc8abe90944','Нефтяников','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('2274e455-f553-46f5-8db9-1f76107f0faf','Комплексная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('22888807-7984-4f3b-8906-3b9858bd8e83','Геодезическая','ул','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('23b8c7d6-3afb-4c13-ba1f-e7e317c9d141','3ПС Северный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('23ee550c-33cd-4e1d-9662-ff9d1c8edee5','Первопроходцев','ул','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('23ffa497-184e-4786-bc3d-4769f9d165c7','Садово-огородническое некоммерческое товарищество \"Телесад\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('2442f9cb-dddd-4816-821c-39dadfa849c9','Садово-огородническое некоммерческое товарищество \"Голубое озеро\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('24e509b0-2758-42b8-a7c8-4882a1cff5c9','Дачное некомерческое товарищество \"Электрон\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('24ec052a-ea7a-4bfe-b0f7-8b892e99b0f4','Таежная','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('2527acca-f8e2-4173-a426-9ca019f179d8','1ПС Северный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('26238a18-9398-432e-930f-69d9e2db5b82','СОНТ Энергетик-карьеры (Энергетик-82)','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('2686d4a8-0550-4e17-99f2-7ab99350cd12','Октябрьская','ул','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('26a8cf60-3599-464c-b1ee-453d35e121b5','Лицензионный участок Тарховский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('26fab781-6726-4da3-9b96-07fe3edd74a8','Ларьяк','с','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('2714a272-7f00-4925-afc5-f1af6933869a','Молодежная','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('271b2314-3ee2-416f-a50a-73ad7ce5bb7d','Садово-огородническое товарищество \"Сантехник\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('275aed29-f21f-4d51-abf1-dd940e2eba2c','Потребительский садово-огороднический кооператив \"Дионис\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('27980690-f844-4536-985c-ce9ba7aa3b4b','Н.Н.Суслика','ул','8603',7,'0be72058-4df7-4806-9d77-e6df972278fe'),('28412623-751a-4e28-a98c-896bc82387f6','Сибирская','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('28544c32-a8d0-4c4a-9aa4-428aec2b1889','Хвойная','ул','8603',7,'87e7d3bc-66ef-4694-bd38-0ab4ec558e8c'),('2900c23c-5570-40dd-8015-2899a7e556de','Садово-огородническое некоммерческое товарищество \"Транспортник-2\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('29451143-67c6-4049-892d-64463d782f61','ГПЗ','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('29763e0b-3c4c-40ab-9a40-9f40a965e981','9-я','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('29beb641-38a6-461b-bcf2-299949ab3daf','Садово-огородническое товарищество \"Калина\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('29f777dd-c2c6-410a-bfd2-524921f9bb6a','Дружбы','ул','8603',7,'ee5884c3-4dd9-42dd-9542-e8f704a2c852'),('2a429427-3ca1-48b3-965b-b95e945475ca','Совхозный','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('2ab0764e-8353-4619-81d8-06c7d8e44cd7','Кооперативный','пер','8603',7,'d77ac5d7-461f-4fde-9bb4-3028366ec592'),('2ac09b98-34e6-4203-9115-b5b6567917df','Салманова','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('2ad41484-0d63-43d8-81ec-908fac0f01ac','Садово-огородническое некоммерческое товарищество \"Погружник\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('2b014476-fb2e-4344-9b1c-01cedb6fe8ae','22П Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('2b059526-34be-4fdd-8aab-644e00356d78','Клубный','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('2bd70412-3174-4820-9a88-4a7babd4f636','Снежная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('2bedd3df-01ac-45f0-a44c-456bd54d1b95','Кузоваткина','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('2c1c4065-6530-4344-81ba-4048b39fda13','Садово-огородническое некоммерческое товарищество \"Северяне\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('2c2d5c0f-5c0c-4e16-a345-fafee42c8960','Садово-огородническое товарищество \"Дружба\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('2cc3b39c-c3eb-4bc5-ac69-fac5daf2c197','Садово-огородническое товарищество \"Сибиряк\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('2cd4a2f8-0ede-49c9-8717-8322cfee0df7','Производственная база','тер','8603',90,'88b717ae-116c-4506-9e35-147f26ca2c6f'),('2cee79ad-dfcc-44c2-a473-12588ced9f85','Потребительский садово-огороднический кооператив \"Баграс\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('2d63361f-0cd1-43ec-a1ce-a20680a4bc91','Набережная','ул','8603',7,'3dcc3e84-518a-4b33-90d2-fb322a084fe6'),('2d6dc48f-3687-432a-9ee0-8956cf75f2d1','10 П','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('2e22ce6e-192f-4460-b866-0632e4de8b50','3ПС','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('2e70d6b6-f3dd-4abf-b61b-b5a8622d9a7e','Школьная','ул','8603',7,'cef36edf-c8f7-474d-a3e6-17573924be26'),('2ebb98dd-4f2d-47ba-a91e-bd6e5d2e0b23','Садово-огородническое товарищество \"Гидромеханизатор\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('30c36c4e-b42b-4783-8e3c-2723c52cb457','Самотлорской дороги 9','км','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('30e4580d-3cb3-4ddf-a24b-ccfdb284c795','Магистральная','ул','8603',7,'0be72058-4df7-4806-9d77-e6df972278fe'),('318d8a98-806a-44c7-9e06-77ce46bb021b','Набережная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('318f450f-27fd-4dd7-bc5a-7234107e6d20','СУ-941','мкр','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('31d3ff4f-2444-4b49-9d18-513bc891a042','Школьная','ул','8603',7,'7da55fe9-4830-4a1d-8b06-232b118f213a'),('325d3439-bdef-44c6-9a7a-6cb28c0c2faa','Садово огородническое товарищество \"Жилищник\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('32ab4175-958a-4ae9-bcff-6303adcdd40d','Садовое некомерческое товарищество \"Зелёный уголок\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('3326983e-9026-4f94-82f2-d3d3b270b4b4','Садово-огороднический кооператив \"Проектировщик\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('33293b6e-d5ae-4787-8819-d03c759060c5','Рабочая','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('33448527-ec45-4318-b7a8-d0ef134a6af8','Садово-огородническое некоммерческое товарищество \"Авиатор-3\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('334d113f-ca52-460b-96c0-87dbc5a988a2','Солнечная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('3366093a-e8b0-4abb-8cf4-15fa62b50e5a','Клубная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('33f8b813-5d14-48ed-99a9-0e870bff2413','Самотлорский дороги 10','км','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('341019d7-0734-45f9-95b8-f42d809a3ec4','Кедровая','ул','8603',7,'91e27416-360a-4e1a-aef6-be21d69ff97f'),('344a0598-8b2f-4e9a-b32c-29f0d5af71fd','Самотлорское месторождение нефти','тер','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('34d7c89e-ed5d-475c-a88e-03aa99e82c93','Садово-огородническое некоммерческое товарищество \"Апельсин\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('35278d03-1fc5-403b-b68f-4daf1b59d675','Садоводческое некоммерческое товарищество \"Автомобилист-1\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('363d6355-c401-444a-be7a-563032dcce3e','Энергетиков','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('366a7c4a-0e74-499f-aed5-def1de571cad','Советская','ул','8603',7,'7da55fe9-4830-4a1d-8b06-232b118f213a'),('3687d16c-769a-43cd-ab27-5973f51e22d0','Садовое товарищество \"Сибиряк-2\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('369c8d99-7bfc-4f9f-ab9b-781e65056057','Факел','снт','8603',90,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('36f74876-9249-4c35-986b-beeb2bd5f3fc','Осиновая','мкр','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('36f83efc-cc58-438f-acbd-38d9e0082fc5','СМП-553','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('3717ea58-9bd2-412f-9fc7-e72eb53ff110','Садово-огородническое некоммерческое товарищество \"Ремонтник\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('380fe8f6-f42f-463c-9f95-5f7f4edd9956','Нефтяник','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('3835ff6f-904f-41c0-b7ed-bb883211f172','Луговая','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('3837591e-fba6-460f-ab81-1abcf17ca9fd','Магистраль','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('3842334b-72bf-483f-9946-39fc97a63d1c','Набережная','ул','8603',7,'cef36edf-c8f7-474d-a3e6-17573924be26'),('38635b9b-b60e-4231-93c2-a073efee42a3','Садово-огороднический кооператив \"Хуторок\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('388f9de5-bb41-4c2e-80fd-64cf64a1199d','ННДСР','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('38b26c64-7ca9-42e4-aadb-fa21ae17884d','Лицензионный участок Луговой','тер','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('38ed1445-d1ed-4297-a3d7-725570c52542','Садово-огородническое товарищество \"Ручеёк\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('38fdf5c0-6785-4699-acc1-cf7428386fd7','Центральная','ул','8603',7,'ee5884c3-4dd9-42dd-9542-e8f704a2c852'),('394a840f-9502-406f-a8be-3a2aa9e8f075','Радужный','г','8603',4,'d66e5325-3a25-4d29-ba86-4ca351d9704b'),('3955ff84-1167-431d-af12-5c64bc0accd1','6-й','мкр','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('39921714-ec53-4e8c-a08e-c799306fb98b','Сосновая','ул','8603',7,'ee5884c3-4dd9-42dd-9542-e8f704a2c852'),('399ddd05-95e5-4353-af56-14a1399063d9','Автомобилист','гск','8603',90,'636fbb6c-2c53-477f-92c6-c4c17aca90ae'),('39a68505-a495-4206-9c8a-31f7cf2be261','22П','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('3a3ee968-7a52-484f-9f3d-4f30b1e6e906','Садово огородническое товарищество \"Вышкарь-1\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('3b05ed11-9641-4ebd-a971-574f0fbb31cb','Зеленая','ул','8603',7,'87e7d3bc-66ef-4694-bd38-0ab4ec558e8c'),('3b30996b-4326-4bc7-a782-30ad0681adfc','Садово-огородническое товарищество \"Ватинский-Ёган\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('3b7733dc-c650-4b69-8225-c0251ecd9a91','Северная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('3b7b730f-e785-481a-84c6-2b12b60869e7','Садовое некоммерческое название \"Кедр\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('3b9a3c04-9973-4c2f-936c-be3d999c679b','Садово-огороднический потребительский кооператив \"Автодорожник\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('3c24f87d-f5b6-47d5-8c0a-718d576cc5d4','Садово-огородническое некомерческое товарищество \"Огородник-81\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('3c511cf3-3f54-430f-ac47-a9e6f5a5ebfc','2-й','мкр','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('3c5cc6c0-4dfc-49c6-8bb5-d82c1cdfccb5','Садово-огородническое некомерческое товарищество \"Кедровый\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('3c865fb3-4513-4a67-8dde-696c191b2961','Лицензионный участок Лас-Еганский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('3cbfe998-56fa-42c0-94e6-9ebe7d38ce7e','Хвойная','ул','8603',91,'feeca429-b4a8-412e-b189-c0a9a7a232ce'),('3cfb0a60-f45c-44c5-bd2c-f58fdc87a9d0','Покур','с','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('3d4598fd-65e6-4268-ab41-766a02b337ca','Радужная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('3d55b2ea-097e-42f3-bcfb-a6ff22d2fd0b','АБ-10','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('3d91fdef-9a01-4673-b964-adb05c607d6d','70 лет Октября','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('3dcc3e84-518a-4b33-90d2-fb322a084fe6','Варьеган','с','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('3de3047c-79e0-40c1-9d30-1c0608313460','Садово-огородническое некоммерческое товарищество \"Оптимист\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('3e0bad3e-a8d2-4835-b44a-30daa5c17ca4','Малая','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('3e27d63a-c038-4756-a907-0c85176b7210','Речная','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('3ee3f7b4-bcc3-4ac4-9fea-80878b816910','Таежная','ул','8603',7,'ee5884c3-4dd9-42dd-9542-e8f704a2c852'),('3f5ae43f-e589-491a-baa1-f07f58acfeeb','Геофизиков','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('3f8c9bea-56a8-4b90-9ff3-6c1db30f40ed','Потребительский садово-огороднический кооператив \"Мечта\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('4005383b-2f66-4618-b54d-3e092097f0bb','Северная Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('4048032b-e894-4498-aaba-5336cb3fd9f6','Коттеджная','ул','8603',7,'e2233268-280d-4f7a-acb6-90abc99b9629'),('40e9bfc7-742c-4c52-9433-061916efd03c','Общество Радужный','снт','8603',90,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('410390c8-efaa-447d-80ea-2ae68481b023','Район НВ ГПЗ','тер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('410f8e7c-35f6-44d0-b60f-c847d6a2a243','Садовое товарищество \"Энергетик-85\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('41131455-5ef3-4371-83fd-c626f674a2e6','Хлебозавод','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('41681ed4-677b-46e7-9755-2fc731db58af','Буровиков','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('418a576f-9237-42b9-b62a-761775b60de6','Садово-огороднический кооператив \"Протока\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('41eb4519-78f0-4caf-8da9-c67a2b274dd8','СУТиР-35','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('41f04ee3-cdd2-4ac5-80fa-78a925b3d0c9','Юбилейная','ул','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('41f54ace-6c41-4814-8f91-8a177c3e58a1','2-я Промышленная','ул','8603',7,'0be72058-4df7-4806-9d77-e6df972278fe'),('422aafa2-7649-48e8-bf95-5a9f94d5c2e9','14 П','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('428e4de6-cc80-4256-85b3-f7d318ef5f16','Строителей','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('429f7bac-3d90-45f0-a81b-775732df6297','Садово-огородническое товарищество \"Луч\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('42ae0c41-050f-4d83-a63d-568e2d453930','Солнечная','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('42ba22b9-c8eb-4a50-a47f-a1669084858c','Садово-огородническое товарищество \"Ватинский-Ёган\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('42eecef8-fb37-4486-9f1a-6ce6f797fca2','садово-огороднический кооператив \"Дружба-96\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('431146d8-eef5-4e22-9edd-4ea31bc2b5fd','Садово-огородническое некоммерческое товарищество \"Черемушки\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('431db49c-9cb2-4ffd-9d46-14b3750d659e','СОТ \"Лира\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('43393d74-6823-4b58-ad1d-b2ca91b066f7','Индустриальный','проезд','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('4358d809-0a91-4686-a614-fb35a4c93588','Лицензионный участок Ван-Ёганский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('439fb9ee-0bff-4f9f-8187-e84b93afc3e8','Старовартовская','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('44633ded-0028-4ca8-ac0e-64350169d24f','Садово-огородническое некомерческое товарищество \"Горняк\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('447c93ec-62ae-47c3-af68-68ced207715d','Садово-огороднический потребительский кооператив \"Дорожник\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('45145e87-0885-4a84-bb2d-802122cd2983','Летняя Мостоотряд-95','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('45b31c87-c1fc-4d78-82d4-0703e3d213d6','СУ-909','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('45c728e9-7eea-4a56-a9d9-f12877f3eef4','Грошева','ул','8603',7,'3dcc3e84-518a-4b33-90d2-fb322a084fe6'),('45d9ee84-93b8-46eb-9940-eb21578310a8','Березовый','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('465e032a-02de-4506-8e5d-4141ab43b4cd','УМ-5','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('46b0c3eb-4b5a-4184-922f-29a09133ebf8','Потребительский садово-огороднический кооператив \"Оптимист\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('47ba59d3-4056-4c2e-8fe2-55cfb3e9b415','Садово-огородническое товарищество \"Ромашка\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('47d3db63-b23e-4a68-a549-30720c7e7bda','Космонавтов','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('47e60feb-d0a3-4e39-a1a3-33daaaea2288','Апир','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('48142cc9-3570-4347-a90e-10b78b556b06','Потребительский садово-огороднический кооператив \"Нефтестроевец-1\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('481aa263-d8fd-43d8-88b9-53cf99d85a0c','Комплекс гаражей 7','гск','8603',90,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('487c2c6a-0cbd-491c-ad91-fb4286f1a32f','Лучистая','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('492c7d30-fd88-4bfc-ba25-5f23bbdb529e','Северная промышленная зона','промзона','8603',6,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('494fb980-e204-4deb-9b53-ccdbae1dfea3','Садово-огородническое некоммерческое товарищество \"Водник-1\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('498ffc20-5f80-47aa-b1ac-349d90bbcba2','Садовое некомерческое товарищество \"Мега\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('49f9c8b7-0e4e-4571-866e-646e289c0578','Садово-огородническое товарищество \"Трассовик\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('4a0da438-7903-4480-b034-8e1a2a7f062a','Потребительский садово-огороднический кооператив Буровик-Савкино','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('4a6f98d3-26b3-4e01-9ab6-962e625f87ae','Ручейная','ул','8603',7,'87e7d3bc-66ef-4694-bd38-0ab4ec558e8c'),('4a8fe43d-9101-4600-a5ff-833102cc81eb','Новая','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('4ac82b62-f0c1-4058-be6f-020bdd7215c5','50 лет району','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('4b089c0a-1f04-49d9-9c60-f199a636a745','Садово-огородническое некоммерческое товарищиство \"Чайка\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('4bcf0c4d-144a-4e7d-b12f-589feb248087','Южная','ул','8603',7,'e03dcded-1b9e-4c68-b9dc-45fd7247e99d'),('4c3757b8-7ef9-4986-a5bd-f13156427ec9','Садово-огородническое некоммерческое товарищество \"Березовый\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('4cc395a5-f3ce-4d99-bf25-295f1c06dc42','Дивный','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('4d050acd-175d-4878-823b-d6b53e7a3a11','Садово огородническое товарищество \"Восток\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('4d0a4a2c-18d9-40b6-8e85-d6bbf8606bd6','Садов-огородническое товарищество \"Рябинушка\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('4d95ab1d-b4f8-4d61-94f7-e66346f10f18','Островная','ул','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('4ef9c9f5-9e31-4761-9d6f-bba44efdee05','Коммунальная','ул','8603',7,'e2233268-280d-4f7a-acb6-90abc99b9629'),('4f04a7e9-f4b3-402b-b305-a14c399ecd6b','Мирюгина','ул','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('4f2328f0-fddc-4265-82fc-843a2d964b81','Садово-огородническое некоммерческое товарищество \"Сибирский огородник\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('4f6600b3-c1e0-4d7b-884a-66182f60efaa','Брусничная','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('4f70bdff-f764-4170-8a39-5cebdea44baa','Лицензионный участок Пермяковский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('4fb6806a-9951-4a10-bc2d-befe0c0591d9','2П-2','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('505813a8-e12a-482f-8359-0726a1ba9a73','Брусничный','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('50cbfd0c-6f2a-44ae-85b1-e34c7b0e0ea3','Казамкина','ул','8603',7,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('50d46525-23cf-40ff-a865-51f09df177ee','Садово-огородническое товарищество \"Снежинка\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('519734ce-7509-4c94-b659-0a4e0a07c6e3','Строителей','пер','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('525949e5-0633-47e5-af3a-0595a09a1146','Садово-огородническое товарищество \"Раздолье\" Нижневартовского лесхоза','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('5285067c-ee8d-4b3f-bc5c-bd91d2b31510','Лесная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('52d708db-2a1f-42db-a47b-09c9cfd98957','Комплекс гаражей 20В','гск','8603',90,'d557be14-f72f-44d7-a1b1-de1b92a0f4d6'),('530d95f2-b8f8-41be-9278-6c84c1266bc3','СОТ \"Кентавр\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('532c3540-f9ee-4a17-8055-4029189d0889','2П-2 Юго-Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('53d88667-82f6-40ba-8402-e4e39f216835','5-я','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('558e1bce-034d-4312-a5df-e3a309829f9a','Городской парк культуры и отдыха','тер','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('55a7be80-1087-4304-b90c-c7050c259cef','Садово-огородническое товарищество \"Озерки\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('5607b1ca-8965-434b-b19b-b1ed0d84f72e','Садово-огородническое некоммерческое товарищество \"Родничок\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('560d2e50-96b0-4d70-a1f6-fd4457069744','Лицензионный участок Орехово-Ермаковский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('564399da-b1a4-4ddf-99ce-95d3d7483b5d','Садово-огородническое некоммерческое товарищество \"Мечта\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('567fec37-54b1-4fc1-ada0-e9a9b4e4e9c3','Поселковая','ул','8603',7,'87e7d3bc-66ef-4694-bd38-0ab4ec558e8c'),('56fda33e-f05e-467d-bf65-7d892f5f8178','Садово-огородническое некомерческое товарищество \"Мечта-2\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('56ffbc43-52de-4167-847a-a52a91a3ae2f','Осенняя','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('5793ffc2-1e06-43fd-8093-3573a3222df0','Строителей','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('57a8c859-ffe5-4c94-b07e-fe2c105316cc','Молодежный','пер','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('593fac41-816e-4e1a-b387-3e4f33f7ddde','5ПС Северный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('59a3a661-d492-4292-a3e5-afcda7653372','Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('59aaa096-4774-4f34-9326-7bac57ef36b9','ТДРСУ','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('59d37fdc-5022-4883-aa55-856bb838f1e1','50 лет Победы','ул','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('59d703e9-ba9b-4472-8a09-943bbf011e42','Энергетиков','ул','8603',7,'e2233268-280d-4f7a-acb6-90abc99b9629'),('5a4c1488-35ef-4043-812a-13701f1c15d7','Аэропорт','тер','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('5a66cd47-6a78-4c95-8083-cbbdcdd5745a','Садово-огородническое некоммерческое товарищество \"Комарово\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('5a86bed2-e6e5-42e4-9a71-05b993614007','Лучезарная','ул','8603',7,'e2233268-280d-4f7a-acb6-90abc99b9629'),('5aac16d1-f88b-4db0-b6c5-2e9c3b0dbd54','Молодежная','ул','8603',7,'ee5884c3-4dd9-42dd-9542-e8f704a2c852'),('5b1a16de-7cfb-427d-b024-f49982a37fa6','Финский','п','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('5b23f4c6-4ffe-487f-ac86-65016cd42a2a','Южный','мкр','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('5b46c905-b57a-40a5-94ec-e55b5426d97f','МУ-18','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('5b643359-760c-4a90-8613-2cb9b97462b6','Садово-огородническое товарищество \"Бытовик-2\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('5bb4a575-126d-4b8d-a0a1-818e90399467','Озёрная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('5cd7a8fe-9101-4fbf-9505-f8ed16484fde','Молодежный','пер','8603',7,'91e27416-360a-4e1a-aef6-be21d69ff97f'),('5cfd005b-40fe-441c-9f62-62d2e2b0a31a','Автодорога \"г. Мегион-г. Покачи ПК26+00-ПК531+00\"','промзона','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('5d61e6c0-88ca-4c06-8d67-07b3c58cd0c6','Вампугольск','п','8603',6,'0bf0f4ed-13f8-446e-82f6-325498808076'),('5d8a4417-237a-4419-91d1-0ab3c39abc45','Садово-огородническое некомерческое товарищество \"Черногорка\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('5dc5ffd5-ec0d-401f-a400-78be378d0f30','Куропаткина','проезд','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('5ddfc3ff-9530-4d51-9ada-dbeede5d5792','Кооперативная','ул','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('5e243ab6-e122-4f83-a587-a0e7921742c2','Кооперативный','пер','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('5e4de6db-ffa6-4e00-9efc-00752f1a2784','СУ-7','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('5e4e9ef7-d471-4cb8-8bdf-2ee749edddfc','Набережная','ул','8603',7,'ee5884c3-4dd9-42dd-9542-e8f704a2c852'),('5e5e2222-6219-478a-9acd-c6688f36e912','Садово-огородническое некомерческое товарищество \"Вышкостроитель-1\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('5e68ee80-da60-48d7-a7a4-1518cebc9cb1','Пионерская','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('5ebf442b-26ed-4721-adfc-3ca5d33b1ab4','Лицензионный участок Ваньёганский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('5f652b3c-523c-4866-b35a-d3b917976561','Мира Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('5fd8389a-f01b-42ac-b957-1f8de4a4383e','Мысовая','ул','8603',7,'91e27416-360a-4e1a-aef6-be21d69ff97f'),('5fde37c3-a58c-4e02-8f62-1b95f2974c00','МО-69','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6016b675-3500-4096-96f5-1f38e7ad6ed6','Юго-Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('60377ccf-087e-4805-9a73-6566997295b5','Торговый ряд Мостоотряд-95','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('6041d5d1-9e68-4453-9573-9a9ff368bac3','Садовое некомерческое товарищество \"Любитель\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('606038d1-df12-41e0-beae-e767e7d3b0c8','Школьный','пер','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('606822db-0c24-4a84-a2e8-1948f6f45f20','Лесная','ул','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('60ecbb50-ec78-444e-83c6-4a146b23c6c7','Победы','ул','8603',7,'ee5884c3-4dd9-42dd-9542-e8f704a2c852'),('61090269-32e2-4017-a7fe-32654ad360b2','СОТ \"Обь\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('6148f78e-4ae0-432e-8ca9-3e8991ddfd77','Садово-огородническое товарищество \"Виктория\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('618296af-6bbf-4bb6-808d-fc0bd1a38630','Бугульминского УБР','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('618a20f6-d84e-4124-be63-93fcb267ccf5','Садово-огороднический потребительский кооператив \"Монтажник-2\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('61a23118-4ba6-4ab9-9747-f3f668932c03','Леспромхоз-1','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('61e8296c-347f-4f6d-b056-23a986cdc42f','Садово-огордническое некоммерческое товарищество \"Нефтяник\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('621bc646-7b82-49f7-8a13-ac90917a247f','Сосновый','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('62a74cb6-2000-434e-bf13-c71c7624a381','Балыкина Пионерная база','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('62fd4d42-7f7f-4a51-af5b-060bdfc92cc1','Первомайская','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6342cb78-1011-4269-b477-605b95077ca3','4П Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('636fbb6c-2c53-477f-92c6-c4c17aca90ae','Мира','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('639b25a3-968e-4037-bac5-374d93f89aa6','Строителей','ул','8603',7,'73dd5544-5d41-44aa-a90b-c19724237f96'),('639d4245-d364-41df-b92b-11988ed425bb','Садово-огородническое товарищество \"Рябинушка\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('63aadf0e-a70a-401b-a6a8-ef2232fde148','Садово-огородническое товарищество \"Мега-2\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('63d1a311-c6c0-411e-bb5d-7a38e4775dfc','10-й','мкр','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('63f07d4b-e2e1-4613-974b-c9a37a214834','Лицензионный участок Нивагальский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('64004d45-01f7-403d-bde8-1ab43216a334','СОТ \"Горэлектросеть-2\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('64c7f8ca-6f05-4fa1-afb1-2518ad304874','Лесная','ул','8603',7,'7da55fe9-4830-4a1d-8b06-232b118f213a'),('64df2845-4ca4-4361-87d6-752b0f26e3a5','Менделеева','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('65093b12-9bcd-4f32-a516-e29afc1d57fb','3-я','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('653c79f1-c47e-44d4-85ad-59250e62bde7','Садово-огородническое некомерческое товарищество \"Пищевик\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('65be62f1-dac2-429a-bd01-a6d473ef207b','Пионерная','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('6632fb4d-3c35-4240-af19-4b23f1ef5829','ЯЦ 34/15','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('663d278d-e7f9-4c24-a184-295cdda87aa5','Дружба','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('665a44c0-03c3-45cd-b75c-b68d849a7bb7','14 км Самотлорской дороги','тер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6691bb8c-a8d3-4492-a251-3a92d43a71a5','Соснина','д','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('66d84e62-f12a-4e7c-a3f5-6739eac0d9d8','Вахская Мостоотряд-95','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('673705ce-51f7-4a3d-89d0-56f752621a47','Промышленная','ул','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('673ba890-ff76-4bc0-b6fa-90b59752ee9c','Восточная','ул','8603',7,'e2233268-280d-4f7a-acb6-90abc99b9629'),('67defa92-e988-425b-a1c2-5604a7d96858','Песчаная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('683b1710-54c2-407d-a298-16628184e178','Советская','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6856c3a9-9e7d-415b-b657-ba9e91c78f34','Лопарева','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('68cc13f6-c06c-4958-a9ef-1f597c897136','Садово-огородническое товарищество \"Рыбник\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('690f2755-2628-4ce6-8242-c2c557276546','Зырянова','ул','8603',7,'6b7f49f1-c9c0-4d5e-b2c6-2b1c1f9a0dc9'),('698f31e6-5b1d-417d-9018-e2e76faef80d','Садово-огородническое товарищество \"Проектировщик-1\"','гск','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('69f81721-b81f-40a6-bf7c-abed6fa2d7e1','Речников','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('6a3ff1e0-fe27-4a60-8a2c-7cd86dbfa699','Садово-огородническое товарищество \"Уралец\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6a59cb7b-532d-46a7-8e30-35f7232e05df','Индустриальная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6abb03e1-3695-4735-b1a2-fc84fc78e99a','Садово-огородническое некомерческое товарищество \"Восход\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('6aca1870-726f-4716-ab58-bc69b9544414','ЗСНХМ','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6acc3577-af1c-4f67-ae3a-70109fa54458','Карьерная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6b5cf4f8-e669-48f7-b7c6-f030f49e3eed','Клиновый','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6b7392d9-2d18-4971-bb50-bd52e76df608','Приречный','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('6b7f49f1-c9c0-4d5e-b2c6-2b1c1f9a0dc9','Вампугол','д','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('6bd718e7-9b66-48d2-af38-620ab4101ea2','Садово-огородническое товарищество \"Окуневка\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6c00e638-1457-4353-a3ce-cc2d7d8fb9b3','Садово-огородный кооператив \"Долгий\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6c39c830-954d-47f8-9dc1-5bd74119c4a6','Губкина','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('6c51ddb7-3a27-4d32-a336-78d03302ed1d','Строителей','ул','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('6c52d3a6-3224-41ec-bcfd-a643c42777a5','НЦТБ','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6c964d6c-7c7e-402b-a6f9-82983249cb73','Аэропортная','ул','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('6ca13c06-7c71-468c-9b32-878003cf1644','9П Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6ca207ab-e441-4a65-836b-b7e82a9da55b','Спортивная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6cbe785c-77b4-4c03-ba3e-e9ed5e86c3e0','Улица имени Леонида Захарова','ул','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('6d0da831-31ce-46c5-953c-d06e153d97e7','Нефтяников','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('6d436dcc-d7d1-451d-813e-918ecd13e901','Югорская','ул','8603',7,'3dcc3e84-518a-4b33-90d2-fb322a084fe6'),('6d738cc3-595d-4171-8d70-ce9b32a4bdad','20П Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6dc85ddb-c419-4dcd-b149-b5cf44dce129','Садоводческое огордническое товарищество \"Труд\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('6dca1f25-94d0-4a02-917a-bd5d98366a6d','Садово-огородническое некоммерческое товарищество \"Энергетик\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('6ddb1f5c-70a4-448e-9d96-c4ecd34342b8','Садово-огородническое некоммерческое товарищество \"Энтузиаст\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('6e17f347-98b4-43cc-804f-19819ccf6361','Садово-огородническое некоммерчиское товарищество \"Водник-2\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('6e35019f-0ec6-428a-83ec-68ac089b7fc3','Цветочный','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('6e365308-b6c9-4c16-8068-4610e9ef9e43','Автомобилист','гск','8603',90,'8acea25d-3b44-49f8-9722-3bef00008414'),('6e854e25-f1c8-49dc-afc4-e8effc23f634','1-й','мкр','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('6e958010-d5c1-4452-baa4-4c34d80ca0d4','Лицензионный участок Нижневартовский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('6ea1bbe6-5989-4791-aa13-a6f01c914433','Кедровая','ул','8603',7,'85194abd-7061-4332-9a0d-5270bcfb96f9'),('6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e','Южный','мкр','8603',6,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('6f3baf1c-ae5c-4e3f-988d-6fe9a321936b','Ленина Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('707c99df-affe-4fe6-91af-f6ee593d3de1','Садово-огородническое товарищество \"Успех\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('7102019e-73a5-4a6c-8212-71b84e1686eb','Садово-огородническое товарищество \"Трудовые резервы\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('711c1b53-8532-4a4b-871d-bc994cabb145','Садово-огородническое некомерческое товарищество \"Лотос\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('71d59e61-d77d-44cd-a1cc-90113d548f6d','Вышкомонтажников','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('72095517-7848-4bdf-bbcb-76dba1accd1b','Дачное некомерческое товарищество \"Дорожник-1\"','гск','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('727b6345-5b8e-438d-beb2-03b7542ad3e5','Весенняя','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('72aac698-0599-4b08-9058-bce26597ae7c','Энтузиастов','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('72b462ea-4496-4528-8363-f03d1c63de6f','Кедровая','ул','8603',7,'3cfb0a60-f45c-44c5-bd2c-f58fdc87a9d0'),('734ae5c3-686a-4ec9-9008-ef0996643fdb','Дачное некоммерческое товарищество \"Монастырский двор\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('73a89b2c-e8fb-461d-ac3d-b4a60a5cbb75','АБ-5','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('73c19f41-a1b0-44df-bbb1-dce77a3b00be','Садово-огородническое товарищество \"Теплоэнергетик\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('73dd5544-5d41-44aa-a90b-c19724237f96','МК-148','мкр','8603',6,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('73e01fa8-ece9-4977-bb34-08bbde7d3f93','Дружбы','ул','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('73fadd01-c20c-4147-bcf0-779fe679c583','Лицензионный участок Мало Черногорский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('7411b630-c397-4c5e-a01e-66375db56eca','Новая','ул','8603',7,'73dd5544-5d41-44aa-a90b-c19724237f96'),('7468d059-92a2-456a-8196-0b9235846dcb','Садово-огородническое товарищество \"Берёзка\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('754ed7c6-9caf-4dfe-a675-ba534830b1d6','Лесная','ул','8603',7,'87e7d3bc-66ef-4694-bd38-0ab4ec558e8c'),('75607237-ca27-435a-978c-7e49bd71c229','Лицензионный участок Ново-Покурский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('762bd6a7-fd9a-42e6-89f2-576a685058ed','Юбилейная','ул','8603',7,'3cfb0a60-f45c-44c5-bd2c-f58fdc87a9d0'),('76347ab8-7dec-48c0-aa33-b3865e730d52','Набережная','ул','8603',7,'d77ac5d7-461f-4fde-9bb4-3028366ec592'),('7638e9d0-90e6-4b6b-8f0e-93d5024de588','Лицензионный участок Вахский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('76a1c2e8-094e-4842-a436-c3960a33d90e','Месторождение нефти Вахское','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('76c879fb-78b2-4409-a885-0003c1f570b2','Садово-огородническое некомерческое товарищество \"Новый Сибиряк\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('76ef7c8e-9cb9-4976-8c80-76ddd6d5ea23','Садово-огородническое товарищество \"Швейник\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('771b5c25-0234-4867-8339-1f917e17b0f1','Маршала Жукова','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('77696c54-c03f-4a2a-9993-9bd2b6654398','Совхозная','ул','8603',7,'3cfb0a60-f45c-44c5-bd2c-f58fdc87a9d0'),('777c3e5f-1a2e-4327-9bd1-714ecde39f28','Буденного','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('77a09451-b5a4-43e9-8675-9ee79776dc5f','Подгорный','пер','8603',7,'cef36edf-c8f7-474d-a3e6-17573924be26'),('77d3c017-3451-497e-b1a9-c58257f3d8b8','Лицензионный участок Урьевский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('77ed9a49-024b-4d5d-8d49-3ee2127e96e8','2ПС Северный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('77f336e6-38b0-430e-90d0-911fa0d2299e','Садово-огородническое товарищество Градостроитель (РЭБ Флота)','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('77f861b9-0bff-4763-a0d7-062fc2862b55','Садово-огородническое некоммерческое товарищество \"Радуга\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('7900c6df-0869-4b0f-8652-f2ec2e29223a','МУ-5','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('795ed3d8-2918-4960-90e6-c773e6f8124e','Мостоотряд-95','п','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('7a156a95-d642-420b-ae73-917843d74729','Леспромхозная','ул','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('7a85665a-fe75-4f16-8008-0395fa5a70bc','Марии Петтухиной','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('7a960d71-e000-4a65-a542-1200d2da282d','Центральная','ул','8603',7,'3dcc3e84-518a-4b33-90d2-fb322a084fe6'),('7a99b57f-9fd7-442f-a7bc-1e16fbd7fcc4','Почтовый','пер','8603',7,'cef36edf-c8f7-474d-a3e6-17573924be26'),('7ae524d4-f27c-457b-b9c6-240dc53f0c1c','Люк-Пай','д','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('7b299f8a-8510-46e7-859c-050da10371e2','Дружбы Народов','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('7b489424-b135-4492-a8e4-43f726fb8f27','Мегионский лицензионный участок','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('7baca228-9cf7-4457-a813-9466ee3c0074','Савкино','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('7c97c3f9-cae9-41de-87e9-da79410d91e0','Ханты-Мансийская','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('7cb96034-764d-40c5-ad59-f4b787a6cb74','11П Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('7ccaa38a-e15f-4d54-8943-ed98738008b2','Центральная','ул','8603',7,'3cfb0a60-f45c-44c5-bd2c-f58fdc87a9d0'),('7d10361a-edab-4a06-b407-a9521d26ee0c','Садово-огородническое товарищество \"Прибрежный\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('7d1e256b-0fbb-49a0-bf10-4eb174d4692b','Промышленный','проезд','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('7d35755c-c174-4816-a03e-7a46e3146484','Причальная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('7d501f1b-179e-4db1-a059-7077436fdff2','Динамо','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('7da55fe9-4830-4a1d-8b06-232b118f213a','Аган','п','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('7dc7431d-ab78-4512-a0e0-d95c57153999','Промышленная','ул','8603',7,'0be72058-4df7-4806-9d77-e6df972278fe'),('7e6b2d27-cddb-4a6e-b469-8a064e815b00','Вагон-городок','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('7e6d53fd-bfc6-4d2e-aafa-cc75396a7819','Луговая','ул','8603',91,'b5b2b0b2-2402-4b66-b377-78634dfcb969'),('7ea14d85-6165-4bd7-9acf-3fbdf988a06a','Лесная','ул','8603',7,'101a2f32-022a-4a00-9eda-c594f8856ad0'),('7ecdcd61-6491-4274-91f4-a3f6fc5f885d','Зеленая','ул','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('7f596552-f588-4dc9-b759-541fa46ecb5a','Чкалова','ул','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('7f5e6575-6b90-47c7-b899-acde123c1f51','Белорусская','ул','8603',7,'3cfb0a60-f45c-44c5-bd2c-f58fdc87a9d0'),('7fe27651-13ed-4f5c-89fc-9942dcbeecb6','Производственная база','тер','8603',90,'30e4580d-3cb3-4ddf-a24b-ccfdb284c795'),('7fef5d25-cc8f-45a3-b79d-68343ba9950e','Район карьера Запорный','уч-к','8603',7,'344a0598-8b2f-4e9a-b32c-29f0d5af71fd'),('80745384-c409-4faf-9b88-c5f28a01c0c4','Садово-огородническое некоммерческое товарищество \"Черемушки\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('808c11ed-b2c1-4eba-8f8c-fbb298a0eb18','Северная','ул','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('80d8607a-137c-43c0-ba7c-6d97b8b34e52','Таежная','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('80e87156-dd60-4f4b-9ccd-a65a99d6ae23','Бульварная','ул','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('8140e113-055c-4724-8b60-c9e6e9d474c8','Садово-огородный кооператив \"Тампонажник-1\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('81691926-32a5-4b21-a1db-59c2c552ce9f','Дачное некоммерческое товарищество','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('816922f6-5e8b-4933-8e0d-daaa72656f3c','Садово-огородническое некоммерческое товарищество \"Взлет\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('818d589f-6bad-4e71-9006-b96e4be748d3','9ПС Северный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('81b8e5da-4853-4c42-8e1e-0084c69011c4','Техснаб','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('81d1028d-265e-412d-97e4-7594ea83d4a4','Почтовый','пер','8603',7,'d77ac5d7-461f-4fde-9bb4-3028366ec592'),('81f428e5-59ad-4063-80e7-f1980a47c0b5','Садово-огороднический потребительский кооператив \"Ремонтник-87\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('82d330c3-5f41-4c65-b008-87c12fc1c7f8','Мира','ул','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('832e6658-27f2-4f1f-9de7-ebd35113709b','Садово-огородническое товарищество \"Гидромеханизатор\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('838fd598-7d40-44e0-934d-ea5b5b6c63d4','Белорусского УПНПиКРС','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('841e9a3b-54a8-4311-aa0f-170a98f3896a','Сирена','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('8438e907-a8ef-4262-b541-cb0d83953303','Солнечная','ул','8603',91,'b88ad621-2207-4f10-bd4a-6a6009598d0e'),('844504ca-09ca-40c5-81e9-3df5f96bb95f','4ПС Северный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('8469660a-ee42-4338-a984-9b5cb656c5fc','Самотлорная Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('84a16942-e69b-4c7f-b62a-2bf881a1e0b0','Садово огородническое товарищество \"Зеленый лог\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('84a70161-ad77-4817-82cb-a91d7e93494a','Лесная','ул','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('84bad0c1-9370-417c-8b04-7c857cfe56ca','Мира','ул','8603',7,'ee5884c3-4dd9-42dd-9542-e8f704a2c852'),('8511ec51-27c1-4c02-8075-b5d5a27dce53','Чумина','ул','8603',7,'18b9ce52-c79f-4133-97a9-c19a0d4b1e0f'),('85194abd-7061-4332-9a0d-5270bcfb96f9','Пасол','д','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('85897bb5-8632-4155-8f6b-10fd0ef1d4c2','Молодежная','ул','8603',7,'73dd5544-5d41-44aa-a90b-c19724237f96'),('860b36a8-55b3-4e02-869e-764a2e44bff3','Угловой','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('8630c4e1-c2c8-48b2-a3ce-23eb4514b3c5','Октябрьская','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('865ae79f-bb06-4320-8002-6739d3c6793e','Садово-огородническое некоммерческое товарищество \"Березка-88\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('86a1a84a-20e2-4061-a6da-c6ef7f837e32','Озерная','ул','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('86c4fc62-32de-436c-9be3-64f191251ba9','ВТН','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('8700801a-b7e7-4a54-80cc-f3ec121d00fe','Некоммерческое садово-огородническое товарищество \"Автомобилист БУТТ\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('876c0184-a471-4c53-9c55-422ab8d87cc0','Дачное некоммерческое товарищество \"Химик\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('877223a9-10af-48ff-8d5b-201afad77a2c','6П Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('87e7d3bc-66ef-4694-bd38-0ab4ec558e8c','СУ-968','мкр','8603',6,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('880fafed-8856-492b-8caf-e8e29eb54b76','Северная','ул','8603',7,'e03dcded-1b9e-4c68-b9dc-45fd7247e99d'),('884245e6-010e-4375-a163-3a8d3462e53c','Победы','ул','8603',7,'91e27416-360a-4e1a-aef6-be21d69ff97f'),('88b16301-4929-4136-8d17-839b53c26db1','Вышкарь','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('88b717ae-116c-4506-9e35-147f26ca2c6f','Индустриальная','ул','8603',7,'0be72058-4df7-4806-9d77-e6df972278fe'),('88c52cf3-6a0f-4303-bc69-7d0c6a1ab853','Осипенко','ул','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('89293d27-a8e4-45d1-9b66-1e6f88f3373d','Зимняя','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('8abc5ce7-6579-46b0-8bee-d3bb867ccf29','Малый','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('8acea25d-3b44-49f8-9722-3bef00008414','Центральная','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('8b3eb3e0-67a3-45ed-bfad-7b9001d484ad','Ягельная','ул','8603',7,'3dcc3e84-518a-4b33-90d2-fb322a084fe6'),('8b445b1e-1755-47b3-a0d1-9d977d2f2da5','Авиаторов','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('8b5ff8a7-1e73-4f13-a19d-8ca1fd5d1266','Югорская','ул','8603',7,'e2233268-280d-4f7a-acb6-90abc99b9629'),('8b67966b-34ce-45a5-9a24-eefa4c174fbe','Садово-огородническое некомерческое товарищество \"Домостроитель\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('8b9da16c-94b1-4007-9941-68ca31e23aa0','Потребительский садово-огороднический кооператив \"Транспортник-4\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('8bd33617-d03d-4657-9de4-5fd369c7b499','Садоводческое товарищество \"Транспортник-5\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('8bd970c6-51f8-4524-9ea5-33980d677904','7-я','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('8bfa00ac-6407-4cfb-9f87-5075d9ec3e76','Район ж/д станции \"Нижневартовск-2\"','промзона','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('8c33faf7-048b-4cc8-bea6-10c5c7dff370','Садово-огородническое товарищество \"Перестройка\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('8c71dab1-3f41-442e-a905-a23954044d3b','14-я','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('8cb6e745-fba2-4aa1-8c7c-ca7a038edf5b','Озерная','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('8d0c1299-b644-4dfd-a4db-8bcd558cfd07','Садово-огородническое некоммерческое товарищество \"Строитель-2\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('8d334c90-4df7-47b7-bde5-ead523c60094','Прометей','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('8dae7dff-5f69-4493-9d92-26948f60215b','Усадебная','ул','8603',7,'e2233268-280d-4f7a-acb6-90abc99b9629'),('8e844443-2df2-4067-8c90-c88c913de37e','Школьная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('8f9d209e-3e0e-4cef-bcb6-27030ac1769c','Парковая','ул','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('8fd689dc-f7d1-46c9-836f-7b3cff555038','Г.И.Пикмана','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('90b30c5d-667a-4110-ad14-72abb0b59ac2','8-я','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('90de926e-94ec-4cc2-a144-15cc3d50a956','Садово огородническое товарищество \"Энергетик\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('90fd3fdb-f68d-4450-8e12-d29202fefa97','Цветная','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('9179205e-4209-473e-9e3b-23e9e71e8511','Район подстанции Мегион','уч-к','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('91ad1a1a-1132-4e50-b57c-ba4035bff1fc','СУ-35','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('91bc2b99-83a4-45db-8d45-5dd88f46066f','СУ-496','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('91e27416-360a-4e1a-aef6-be21d69ff97f','Вата','д','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('91f04c26-6017-4722-ac6e-78106b97d76c','АТП-10','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('91fa6d9a-ae4b-4dcc-8c14-890d0556014a','Юности','ул','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('92215e8a-e8ca-412d-975a-06eeea5506b4','Садово-огородническое некомерческое товарищество \"Светлоозерное\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('9229a37e-ecdb-4b70-bc41-c1b93c8e8449','Центральная','ул','8603',7,'91e27416-360a-4e1a-aef6-be21d69ff97f'),('9260cf2e-07e6-4143-9178-aabca6f3c52b','ЦТБ','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9285257f-4a74-4c9b-8370-90d5dd4c7b4c','территория Белозёрного УТТ','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('92f6fc5b-4e4f-4279-92eb-c016f059db83','Садово-огороднический кооператив \"Досуг\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('93fa0207-efb6-4731-bf33-d698b79a4d45','18П','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9456ae9c-ce39-44d4-85b3-3b4054f4b20e','Новая','ул','8603',7,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('94a6205c-2ab3-45db-83b2-8230128b1c11','Садово-огородническое товарищество \"Эдельвейс\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('94dc2459-ecaf-4674-8165-2cbbe01cf430','Нзра','п','8603',6,'0bf0f4ed-13f8-446e-82f6-325498808076'),('94de6654-caf6-43fd-a308-9645b5f324d7','База отдыха Татра','уч-к','8603',7,'344a0598-8b2f-4e9a-b32c-29f0d5af71fd'),('94fb5edb-9bdc-4c4f-a4fe-034b8744a57a','Мусы Джалиля','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9520ddde-e3a5-4922-8961-04c86eb8eb3d','Романтиков','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9566f245-d691-4377-88e7-a288d6903730','Строителей','сквер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('95d96020-d192-4b49-ab23-df53a9ba75d8','Возрождения','ул','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('961a84ac-c78f-4789-9eff-f69d7feb07dc','Центральная-пром','ул','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('96217e62-2be3-4304-9915-45660615af71','Лицензионный участок Самотлорский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('96325e7a-621f-452c-872b-65485b43ac2e','Кедровая','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('96850e98-1d0f-475b-99af-d5b4097f7faf','Маргариты Анисимковой','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9699eb65-56fe-4c0d-93bb-71b77b573234','Солнечная','ул','8603',91,'81691926-32a5-4b21-a1db-59c2c552ce9f'),('973f2a95-6e3f-460b-808a-78cad8b48bcc','Потребительский садово-огороднический кооператив \"Деревня\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('973f7450-bf30-4dd9-8f31-98022a10d9b0','Спасателей','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('978f0902-1a23-48f5-a7e0-02e9cc9f3fb2','13-я','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('98225ffa-2a60-4b45-bb79-edf973a27376','Садоводческое,огородническое некомерческое товарищество \"Геофизик-1\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('98bff0cd-3caa-40ba-92d4-33acb07213eb','Садоводческое огородническое некоммерческое товарищество \"Березовский\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('98d50f4a-481a-48d8-bc75-bde85026505e','Садово-огородническое некоммерческое товарищество \"Строитель\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('98fbe175-9723-445c-b1c3-f82f0a9f2555','Пугъюг','д','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('9943123d-8aa4-49f7-bf67-04bc5aabef53','Садово-огородническое некоммерческое товарищество \"Здоровье\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9993e82c-1b75-4dac-ae3d-805c8082a46a','Клубный','пер','8603',7,'73dd5544-5d41-44aa-a90b-c19724237f96'),('99b1e7af-ae93-4359-8459-3ec6f3b1d5e8','Лесников','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9a0a0033-1828-4df8-88a9-12957abfe3c1','Садовое товарищество \"Авант\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('9a3ff038-23fc-4e04-ace8-da7e3ec53c49','Кербунова','ул','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('9a5c6d71-b98f-4bf4-ad1b-918a4208e5a9','Новая','ул','8603',7,'d77ac5d7-461f-4fde-9bb4-3028366ec592'),('9af212c8-2fe3-415c-bbe4-452443e94b62','Леспромхоз','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9b2772c2-c23f-45db-bbdf-3d2af24d7c7c','МЖК','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9b6392b7-c20d-499e-a7fe-cdf847de5fb1','Лесная','ул','8603',7,'cef36edf-c8f7-474d-a3e6-17573924be26'),('9b75384e-ac20-4088-a067-f07764a1bfd9','Радужныйнефть','снт','8603',90,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('9ba855f0-b060-4c2a-acf9-9abb66c80da8','16П','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9bb6bd18-f944-4702-bb81-3b1b79017a77','Ягодный','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9c1e9f98-bf25-457f-a9ca-98c201abefc7','СУ-66','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9c1f38e0-f5e0-4d36-8680-6cfe39567fb6','Геофизиков','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('9c32190b-2990-4083-86c2-b463ba04a834','Югорская','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('9c3931c6-c153-4233-91d5-b5eacf482348','Дивный','п','8603',6,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9c480c73-a394-411e-8e98-0246df246197','Клубничка СОНТ','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9c845146-b82d-4317-9389-a71191c29d10','Набережная','ул','8603',7,'101a2f32-022a-4a00-9eda-c594f8856ad0'),('9c9f123b-7047-4a1e-bbb7-1862251e2351','Школьная','ул','8603',7,'3dcc3e84-518a-4b33-90d2-fb322a084fe6'),('9caf096b-6a36-4cb4-bd40-691e6bf956f1','2П Западнай промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9cef2a7f-52fd-4195-84ee-4a31082de3ed','Интернациональная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9d636ba4-dd2d-4ca1-9809-51764329b6f1','СПК \"Мыхпай-90\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('9d671166-3d12-4809-b2d0-d526bb65ac5e','Садово-огородническое товарищество \"Сапфир\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9d798d5b-23d6-4448-9a86-dbbba9f77865','Нижневартовская база по ремонту труб','тер','8603',90,'344a0598-8b2f-4e9a-b32c-29f0d5af71fd'),('9dca5f9d-1c6b-46ce-830b-98358caf55ae','11П','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9dd8cadb-0a18-4624-a891-a67a668895c1','Геодезическая','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('9dfef571-d1a1-44de-90c4-89fa39c227b9','Тёплый','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9efca463-c3bb-407f-816a-7f92a81e36dd','6П','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9f590bcc-b3e6-471d-a95a-0b3c4d6b2b15','Декабристов','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9f7a6c92-c863-40fe-bef5-bf23bc089814','Садово-огородническое некоммерческое товарищество \"Газовик\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9f7b46f0-c24c-4247-b4e5-d9252b9632ec','Кузоваткина Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9f8b13bf-2982-4f0c-8bb6-af2950df6475','Месторождение нефти Могутлорское','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('9fe4db5c-0dcb-4b62-9a90-31d02cb22f62','9П','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('9febea53-600f-4f95-8e74-b643600b3cef','Береговая','ул','8603',7,'101a2f32-022a-4a00-9eda-c594f8856ad0'),('a00b5333-9a59-47af-b61e-d422f9c25848','Лицензионный участок Самотлорский','тер','8603',6,'0bf0f4ed-13f8-446e-82f6-325498808076'),('a02581fc-eb80-45aa-b8b5-99089da0cfa8','Кедровая','ул','8603',7,'18b9ce52-c79f-4133-97a9-c19a0d4b1e0f'),('a11d1eba-445c-4f9d-97fa-bad156a27fbb','Лесная','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('a123afab-b478-4952-891f-0687f4243d99','Садово огородническое товарищество \"Раздолье\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('a149c26c-e72a-4f3d-a10c-b160c884cfc0','Садово-огородническое некоммерческое товарищество \"Уралочка\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('a1945d3a-d4a1-4c28-b302-276ed7e3d879','Профсоюзная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('a20e9281-a79f-4356-b4fd-8dd052cf2da7','Школьная','ул','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('a24662dd-15f9-447a-b301-3f87cd45571a','Лицензионный участок Западно-Полуденный','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('a2661778-e0dc-4cbe-adb8-1a3065860466','4-й','мкр','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('a40a26a5-2e68-4905-889b-c523e2fe41c0','Спортивно-оздоровительная база \"Черная горка\"','тер','8603',90,'344a0598-8b2f-4e9a-b32c-29f0d5af71fd'),('a40fded7-0728-4f13-9fec-b12f7cba396d','5ПС','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('a4303bd9-ab1a-42cc-b269-3735186b1e17','Общество Энергонефть','снт','8603',90,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('a4c28242-12e3-49ca-be0d-58c9507752f9','Садовое некомерческое товарищество им. Терентия Мальцева','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('a512fa92-e697-4b0b-a382-532315735ee0','Потребительский садово-огороднический кооператив \"Автомобилист-Север\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('a592cca5-7671-4cab-8b6b-71f3832ae62a','Садово-огородническое товарищество \"Рябинка\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('a61915d8-6005-4b14-8235-0f52fbd74abf','Центральная','ул','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('a68e4baa-9717-4b5e-9191-4a72703b367f','СУ-53','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('a6a25a87-847b-48eb-baaf-6cae861304c2','Садово-огородническое некомерческое товарищество \"Окуневка\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('a6aa908d-a2f3-44e6-896b-4d6f0c276320','Леспромхоз-2','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('a73db9cf-58a6-4da0-a058-31d0040a67d6','Солнечный','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('a7661d24-6b80-4d1d-b05e-f9568487c8d1','Ягельная','ул','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('a7a8fcc4-ce93-446a-a555-4f122d43378d','Садово-огородническое товарищество \"Тови\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('a7c5061a-09c9-4c41-8b02-1504d9d27b39','Садово-огородническое некоммерческое товарищество \"Импульс\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('a8003c8c-5791-46c1-a5d8-cd2d93501dc5','Первостроителей','ул','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('a8552a19-25ec-44cf-a0bb-8e1c5d40167e','2П-2 Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('a868ee93-1439-4dd4-adfd-f497f07193a4','Садово-огородническое товарищество \"Статистик\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('a8ac3e58-8691-4789-82ca-7a875b42baa9','12-я','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('a8fd7943-e15a-4b7f-b520-dbcefa146929','12П Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('a933ae6d-6b64-4d8d-b56b-783448c2aff9','Садово-огородническое некоммерческое товарищество \"Первенец\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('a9741e48-6248-4a95-ac5e-5e2c508620b5','Безымянный','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('a9795c69-82ca-4aa8-89ed-3103f2aef123','Авиатор','снт','8603',90,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('aa1dea2c-4038-42ce-b838-c7b8cbb56306','Дзержинского','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('aa3fd6c3-3d09-49a5-9844-f99b3b372659','Месторождение нефти Мамонтовское','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('aa7db532-4ab2-4aff-b2d3-6a7e0dd0bfd4','Поточное месторождение нефти','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('aac4d265-26bd-43a7-9cd9-a816adc7c309','Садово-огородническое товарищество \"Механизатор\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('aafa014d-d370-4939-a500-7952149ea662','Советов','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('ab52c757-df58-4286-9f92-db010388b069','Дачное некоммерческое товарищество \"Соболь\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('acceab01-e269-4738-b49d-98dea7b99c57','Садово-огородническое некомерческое товарищество \"Стрела\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('ace09f34-31a2-4bcc-9c7b-7db9d563e90a','Садово-огородный потребительский кооператив \"Буровик-81\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('ad56c3f1-1058-469c-a845-2b1931f440c3','Октябрьская','ул','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('ad6809a0-ee61-4b8e-ae98-71b79c527d8a','СОТ \"Авиатор-2\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('ae244d12-10e0-4e24-9ae9-320159c34743','Садово-огородническое некоммерческое товарищество \"У озера\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ae56436a-2ace-4b27-b66d-e9214b4adf20','Береговая','ул','8603',7,'91e27416-360a-4e1a-aef6-be21d69ff97f'),('ae94bb74-4c06-4d53-bd02-8b992cb3b61c','Береговая','ул','8603',7,'6691bb8c-a8d3-4492-a251-3a92d43a71a5'),('af290bbb-a031-40d8-b772-7960783528f1','Северная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('af95dd58-c0f6-4eae-ae61-0a3df8ca0fbb','Садово-огородническое товарищество \"Транспортник\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('afaadd34-c048-431c-afa0-b9e8c590c01e','Садово-огородническое товарищество \"Механизатор\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('afda4ed0-bf2e-4ed4-a0bb-cb1beef69f8d','РУРП','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('afff75e3-eb8a-465c-8f0c-4f1e0122e181','СУ-14','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('b0ffc89e-2e93-499a-8d08-f2dcdf996014','Производственная база','тер','8603',90,'7dc7431d-ab78-4512-a0e0-d95c57153999'),('b1a7a4d1-6e15-4fba-b1f7-739bb62d8c91','Садово-огородническое некомерческое товарищество \"Мостовик\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('b1d27df3-4de4-435c-98be-bd61f8f3dd0d','Фурманова','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('b1f2a468-bce2-46f7-b0a1-1dede92df24a','Большой Ларьяк','д','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('b21e5465-b0dd-4c98-8389-046d19020df6','Солнечная','ул','8603',7,'ee5884c3-4dd9-42dd-9542-e8f704a2c852'),('b29fc67b-e4d6-41d1-81c1-822d95374f55','Новая','ул','8603',7,'3cfb0a60-f45c-44c5-bd2c-f58fdc87a9d0'),('b2f1bbef-ca7f-4144-8adf-f6cecd225998','Гранит','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('b38b7f99-6eb4-4ce8-9ffe-ce447a8fc480','Садово-огородническое некомерческое товарищество \"Мичуринец\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('b3b8d329-3768-4d38-be42-4e7df9aa2f9a','Садовое некомерческое товарищество \"Зелёный уголок\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('b4069068-0ecc-48bb-b9be-ff8ba067cb33','Заозёрный','проезд','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('b4468b31-dde5-480c-99ee-7fe922788d4a','Дачное некоммерческое товарищество \"Транснефть\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('b4bb56a7-eace-47e3-89b5-edea6f8591ad','Энтузиастов','п','8603',6,'0bf0f4ed-13f8-446e-82f6-325498808076'),('b4be411d-c0e3-4ac4-814e-66e0f6680508','Въездная','ул','8603',7,'e2233268-280d-4f7a-acb6-90abc99b9629'),('b4c56ed8-2dea-41c7-9ad1-b27cd0ab8589','9ПС','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('b4dc99d9-c0f1-477d-9407-9297b94472ee','Владимира Белого','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('b5047cef-a8f6-4f37-93d3-cb5d9737003b','Садово-огородническое некоммерческое товарищество \"Коммунальник\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('b569c47f-8461-46d6-a575-13497767a95f','Производственная база','тер','8603',90,'1d70eed1-c7ff-407f-adbc-084cd605ac9c'),('b56d7a49-4dfe-46a1-99b8-623116e4db39','Таежная','ул','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('b5a933b0-b567-495e-9bea-ba80d2900833','2-й Индустриальный','проезд','8603',7,'0be72058-4df7-4806-9d77-e6df972278fe'),('b5b2b0b2-2402-4b66-b377-78634dfcb969','Садово-огородническое некоммерческое товарищество \"Факел\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('b617e316-f9b2-40df-a13b-092c43ed6e8f','Садово-огородническое некоммерческое товарищество \"Надежда 44\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('b64ed55b-8eef-4cb9-b723-0e9d7b14e391','Молодежная','ул','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('b6537f3e-b4e9-4666-9672-1d2503ef500b','Автомобилистов','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('b6726c3f-41e8-454b-83ad-29b2f3e60412','Кедровая Пионерная база','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('b6815201-b043-485c-8ef4-48c7dcc72f16','11-я','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('b6a3e727-0b89-4c5b-bfb9-64270b1a88c3','Садово-огородническое некоммерческое товарищество \"Березка\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('b6cb554c-85ea-4cb5-b01e-94406f7b1525','Лицензионный участок Ермаковский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('b71b5017-00cc-4719-8840-4b4e3374f502','Дальняя','ул','8603',7,'e2233268-280d-4f7a-acb6-90abc99b9629'),('b7aaba76-b811-4127-8242-261a2534fb33','Транспортная','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('b80f8cfe-5291-4bb9-a3ed-f8812ef1eb0a','20П','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('b81d3a53-2943-46ac-b624-308711582b7e','Киевская','ул','8603',7,'3cfb0a60-f45c-44c5-bd2c-f58fdc87a9d0'),('b81eb9b0-ef8d-4e27-8b28-e01d69f448a6','1-й','мкр','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('b840fd2f-7904-4816-8c39-5b0972572f56','Садово-огородническое товарищество \"Черёмушки\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('b88ad621-2207-4f10-bd4a-6a6009598d0e','Садово-огородническое некоммерческое товарищество \"Энергетик-82\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('b8912e8a-6ffa-4da9-bf98-e0ef2884aa67','Садово огородническое товарищество \"Колхозник\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('b8b0e604-70ea-4304-989a-68439d7463fb','Набережная','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('b8da68f8-15fe-476d-9a8d-a5c9a93905e7','Мирный','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('b8ea1062-f607-4b6d-b248-e2af8259120a','Тепличный','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('b992697c-c3e7-450b-9b7f-193d97f1cddc','НССУ-1','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('b9ae176c-1d22-47f5-a2ff-04326a2b2f25','Теплый','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ba4b44f7-4655-4532-b297-fe365faab9c3','4ПС','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ba90e132-baf7-4b3d-8863-09f806be77c2','Некоммерческое садово-огородническое товарищество \"Автомобилист\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ba9d0129-c0a6-4c65-a7bb-dd70356e8d9f','Садовое некомерческое товарищество \"Северянин\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('babdebfc-e1b7-4890-ab89-992d8789370f','Садово-огородническое товарищество \"Калинка\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('bac1e4df-1af1-4144-8793-3a6960618e2a','РЭБ Флота','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('bac32592-bf98-4f55-a464-15dde2833451','Центральная','ул','8603',7,'d77ac5d7-461f-4fde-9bb4-3028366ec592'),('bb3b60a5-ba0a-4cc9-82db-3607497d236b','Лицензионный участок Аганский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('bb61fa36-3675-4e63-a253-2aa05971746a','Набережная','ул','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('bb956412-770c-406c-b88b-efc99e68bfcc','Озерная','ул','8603',7,'d77ac5d7-461f-4fde-9bb4-3028366ec592'),('bb9ceadc-85b2-43c4-81bf-81b869848756','Здоровье','снт','8603',90,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('bbab1d9b-d93b-4048-84d8-b559dcec0ce3','Садово-огородническое некоммерческое товарищество \"Озерный\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('bbc6000b-154c-43bc-b512-89cd00f3d4d8','Северный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('bbdd8467-e450-4524-91dd-f0bd05349539','Садово-огородническое некоммерческое товарищество \"Дубрава\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('bbf9bfaf-7748-48d3-9e2b-b70229cb35e5','Речная','ул','8603',7,'16807b3b-c163-4f35-82de-ccb1fd11a97e'),('bc22ef17-4b5c-48f6-bac3-fd10eb23bad9','Комплекс гаражей N 8','гск','8603',90,'9456ae9c-ce39-44d4-85b3-3b4054f4b20e'),('bc3d0909-a4ef-4be1-abe7-3d6bdf2e11fd','Куликовой','ул','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('bddffe80-24ad-4603-89d7-711c21ecfa5e','7ПС Северный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('bedc2541-a4b0-4b17-a835-6b4d5e09f07e','СОНТ \"Коммунальник\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('bf22577f-af0b-4252-8511-1b5082c6d645','Садово-огородническое некоммерческое товарищество \"Кедр\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('bf4c3bbc-274c-4096-a4e6-59c3458c649e','Советская','ул','8603',7,'3cfb0a60-f45c-44c5-bd2c-f58fdc87a9d0'),('bff9e76a-447f-444f-8aa9-db5e64cc149a','Брусничная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('c0068579-6482-4cee-9116-e9f54dae09d3','Потребительский садово-огороднический кооператив \"Кедр Сибири\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('c049f044-0c3a-4fd0-9248-b423c9470244','Садово-огороднический кооператив \"Берёзка-1\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('c08c8ac9-fce8-403a-b132-94f745b07585','Садово-огородническое некомерческое товарищество \"Тампонажник-4\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('c092c5dc-7224-415c-b08d-9645f9cde56c','Север','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('c09606c0-ec34-4bec-8bce-5af3f820db63','Садово-огороднический кооператив \"Незабудка-86\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('c0a25f89-94d4-43dd-b7ba-953f36909ae2','Садово-огороднический потребительский кооператив \"Ландыш\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('c15ce3db-9f7c-4e2b-a1f9-684018dc64fe','Обской','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('c1966b86-d6dd-42c3-ad47-3c3b1e32c415','Спортивная','ул','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('c1aa0305-1b97-43c1-b275-94be07671c74','16П Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('c1d48e03-9f41-4272-9b4d-c39d55d29e73','Садово-огородническое некоммерческое товарищество \"Кедровый\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('c20c7625-dc53-49ab-a47a-e2a47e8573b9','Александра Танюхина','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('c220352c-efdc-4500-9ec6-baaa51c631ce','Тихая','ул','8603',7,'e2233268-280d-4f7a-acb6-90abc99b9629'),('c2edfa17-9244-4801-88b4-608638d5c473','Зырянова','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('c2f27c9c-4133-4210-ba13-c4ac6e952a90','Кедровая','ул','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('c300570d-3af6-4f75-b722-d235efcc45d2','Тепличный','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('c374ca95-f0db-4fac-b7bb-8b961a2ff6eb','Садово огородническое товарищество \"Рябинушка МВМУ\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('c3bc148f-3daa-46d7-ba50-71a1b48d5916','Огородническое некоммерческое товарищество \"У озера\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('c3e1e781-5aaa-4956-89fd-ab054bee3d14','Садово-огородническое товарищество \"Морошка\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('c3fd3d88-310b-4860-8cd0-999e03038757','Лицензионный участок Северо-Ореховский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('c421927c-9627-45d6-a456-e0e05bd541e6','Школьная','ул','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('c4728933-f3d5-4069-ad30-49dfa17d5c8e','Магылорская','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('c51ecd2c-2ad5-4797-9afe-f829f2cdfbc0','ВНСС','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('c5243fce-68db-4fc3-878e-f3d3dc958751','Мира','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('c559a7ec-87d3-4419-98be-0860e6a16b98','Садово-огородническое некоммерческое товарищество \"Малиновка\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('c587085a-5cff-4984-b362-8270a3908aa9','Учительская','ул','8603',7,'d77ac5d7-461f-4fde-9bb4-3028366ec592'),('c59b5427-ce63-4d76-806f-059bd8b12161','Садово-огородническое некомерческое товарищество \"Чайка\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('c5c2345e-db16-409f-bc2f-cd283eb8671c','Садово-огородническое товарищество \"Сияние Севера\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('c65ff27e-49fb-4711-93bb-18b78cb8cb69','Ломоносова','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('c736b043-8389-46fe-bc19-d80624231ee0','Потребительский садово-огороднический кооператив \"Газовик\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('c757c8e4-5a19-4ef4-9303-4ee144c6f01c','Ваховск','п','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('c7644e75-d823-443b-81d2-ddfdab6380c5','Садово-огородническое некоммерческое товарищество \"Связист\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('c765ffef-0964-47a1-b257-0892499978a4','Ленина','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('c79e8142-e7d1-42cb-976a-99967fb6c1ed','Школьная','ул','8603',7,'91e27416-360a-4e1a-aef6-be21d69ff97f'),('c7bb1164-3011-4c05-9049-2cbf72f8555f','Казачий','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('c7bd3b2e-4441-46a3-9053-5d058638f25e','Радуга','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('c7caa1e7-c255-47a4-8fb6-d9adc1d73048','Белозерец','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('c7cd7ff9-fb51-4eea-9f77-d661f5185ec1','Почтовая','ул','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('c8823c23-f15d-4622-a354-3dc4bd832045','Садово-огородническое некоммерческое товарищество \"Просека\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('c8c61fd4-8976-43b4-bfbd-27d79c6c3fd9','Лицензионный участок Полуденный','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('c8e2a22a-36b7-4557-b74d-4a797b0e73bf','Красный Луч','ул','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('c9025ef4-f6a6-4d68-b2c2-ed8c426ad0d0','Больничный','пер','8603',7,'066da0c0-9b11-43e5-917f-c351e18ce857'),('c923bd03-3a8b-44d9-a408-4b7158d28386','10-я','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('c9c96a6e-d330-472b-9ae9-3e44b3e4d8ea','Школьная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('cb35851c-1914-41d2-af41-feb0ba1809d6','Оренбургский','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('cba79f66-635b-4721-b599-4481e9ae8114','1-й Индустриальный','проезд','8603',7,'0be72058-4df7-4806-9d77-e6df972278fe'),('cbdb7d11-5ac3-4e73-a263-be25b45c1c49','Агапова','ул','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('cbe8f15e-57f3-4be4-a882-a24cf7593b26','Магистральный','пер','8603',7,'3dcc3e84-518a-4b33-90d2-fb322a084fe6'),('cc2ebeac-ba16-42d6-80b7-71b6a1ac5b12','Гагарина','ул','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('cc4bfda3-8a88-49bd-a1cb-6dbeba505121','УПНПиКРС','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ccf39807-d43e-4a1c-bec1-605d7f19df5e','Лицензионный участок Аригольский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('cd31d2b1-3906-4c9d-8f2c-733ec4bd68ca','Айваседа Мэру','ул','8603',7,'3dcc3e84-518a-4b33-90d2-fb322a084fe6'),('cd453cdd-d122-4894-8b77-b3cb81e338da','УМ-9','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('cd5ae499-c065-49ac-a6e6-5414755a6b94','Берёзовый','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ce9bbc2e-11ec-4ad5-b799-081177f8b75a','Омская','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ceb01ddc-375e-436b-a0ba-edcff8e9a428','Варьегантеплонефть','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('cef36edf-c8f7-474d-a3e6-17573924be26','Большетархово','с','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('cf27f0d7-86fa-4f97-88c2-7c73f4ba03a6','Звёздный','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('cf5d91f0-5f65-48a0-98b5-ef489417a289','Ромашка','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('cf7f3cf7-ce11-4dea-8c33-18bccbc7be85','Некомерческое садово-огородническое товарищество \"Таежник-1\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('cf9dae04-2cd8-4ea8-ba30-f576f0c979d9','Причальный','пер','8603',7,'d77ac5d7-461f-4fde-9bb4-3028366ec592'),('cfa02b96-9f39-486b-a14d-a45a2b15f3fc','Югорская','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('cfa4cd29-d298-42e7-87c1-458d550e5fc5','Первомайская','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('cfb91c48-9916-4ed4-a9ea-9404220df7c9','ГП-77','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('cfdb6e1c-fc66-4fc8-8aa6-90a1dec47f82','Садово-огородническое товарищество \"Урожай\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('d02c60ca-c5b6-46c2-bc15-381ebee9814b','Буровик','снт','8603',90,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('d02db649-b68b-4280-ab1a-c38792e3f657','Лесная','ул','8603',7,'ee5884c3-4dd9-42dd-9542-e8f704a2c852'),('d067fe1f-6a16-481c-91b0-b0f28d3493db','Садово-огородный кооператив \"Спецстроевец\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('d0934b7a-d596-45ea-86a2-c410cdac938f','Садово-огородническое товарищество \"Левада\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('d1630783-8bfe-4401-8c97-061fb56cf8ab','Набережная','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('d1e921f7-4cb6-4c57-a62e-7ccd2ed561a9','Береговая','ул','8603',7,'7da55fe9-4830-4a1d-8b06-232b118f213a'),('d23ca88a-dd2a-4a8d-ae21-da6f380ff468','СОТ \"Березка\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('d2a761b6-8530-4a9a-ae5b-cf3aaaf3b7af','Молодежный','пер','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('d3a50f1f-c9fc-4874-98c2-039189b373a0','Солнечная','ул','8603',7,'16807b3b-c163-4f35-82de-ccb1fd11a97e'),('d4c79072-e855-4696-97e0-e4d8f8725013','Ермаковский','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('d4cb2b70-855d-4918-a7f0-75117b498d64','Садово-огородническое некоммерческое товарищество \"Ветераны\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('d4dcb356-69c8-4ec7-be44-ab5224b1e4aa','Восточный','пер','8603',7,'e2233268-280d-4f7a-acb6-90abc99b9629'),('d557be14-f72f-44d7-a1b1-de1b92a0f4d6','7-й','мкр','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('d567aa5c-d9f7-485c-a984-75e8abe06b95','Повха','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('d5c5508d-88b8-4f5d-b4bf-bba2f4fc2ea7','Магистральная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('d5cb8b5d-80c0-4046-a12c-223574397336','Ягельный','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('d5d13346-9744-42d3-b3e3-b35558d8c360','Лесной','пер','8603',7,'3dcc3e84-518a-4b33-90d2-fb322a084fe6'),('d639849b-48b0-4d21-9323-59f66f11ed80','Садово огородническое товарищество \"Мегионский народный суд\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('d63e56a2-3d0e-4c55-8569-c6edc14f3844','Нижневартовская ГРЭС','мкр','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('d6c7f92f-3537-4b5d-b4c8-be8dd97101b4','Излучинск','пгт','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('d6f47575-02c3-4974-9865-50b5b3104584','Садово-огородническое некоммерческое товарищество инвалидов детства \"Солнышко\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('d7312f70-c423-4ffb-bf60-72a03f3425b1','Садово-огородническое товарищество \"Градостроитель\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('d77ac5d7-461f-4fde-9bb4-3028366ec592','Охтеурье','с','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('d7f4088e-cde4-4b7b-8d3d-3c6aa33a1b07','Садово-огородническое некоммерческое товарищество \"Любитель\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('d817a1ec-4878-42b5-9141-3837f735a34c','Садово-огородническое некоммерческое товарищество \"Транспортник-1\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('d82a92f7-3ce3-467b-8a77-fcffb188e24f','Садово-огородническое общество \"Рубин\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('d82ffd22-cfcb-43f2-a932-2a76e5a2c22e','Садовое некомерческое товарищество \"Мега\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('d83144ac-abc8-4977-b419-64de8da202cc','Восточный','проезд','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('d8526f4b-dd7e-4259-84b2-f8e05cbb2a19','Радужное','снт','8603',90,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('d97366fa-b415-43d9-a5dc-7775438821c5','Дачное некомерческое товарищество \"Трассовик\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('d9d0544b-520d-4b11-886d-cf374dc02a0e','Садово-огородническое некоммерческое товарищество \"Белозерец\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('da19c200-4691-4be0-9f56-1a454be36b87','Балдакова','пер','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('da9eb921-b0f4-4930-9f1a-3caf79e13d9b','Больничный','пер','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('db03595e-3080-42d5-bcec-3024d6682a7d','Садово-огородническое некоммерческое товарищество \"Сибирские черемушки\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('dbc36aad-7fc9-424f-a628-b8bb5b5e5603','Садово-огородническое товарищество \"Разведчик\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('dc0eb443-dce8-4cf9-86b5-855e297102cd','Садово огородническое товарищество \"Черемушки РЭБ Флота\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('dc99551a-d673-4732-ab95-bda011bb0623','Садово-огородническое некомерческое товарищество \"Земляне\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('dca4a277-2f3f-4308-8166-9fa9360e8c53','Автодорога \"г. Нижневартовск-г. Мегион\"','промзона','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('dca82ff5-7163-48f8-a842-928026ba706d','Светлая','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('dcbaece9-2a09-40fa-8a28-8018d782fa67','Садово-огородническое товарищество \"Речник\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('dcc079e8-d08d-4398-982a-40546f48abfd','Автомобильный','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('dd1c24c9-56cf-4ad5-b8d0-1070232de036','Энергетиков','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('dd3ad3e1-13fb-4ebb-8476-e06bff9de1ee','Потребительский дачный кооператив \"Савкино\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('dd4da4a2-f376-436d-8af5-b05ce6241c3b','Авиаторов','ул','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('dde82405-1932-4792-952b-7bbe6313bdc8','Н.Н.Суслика','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('de422cb4-d414-4088-853b-8ab4d3b51643','дачное некоммерческое товарищество \"Семь Я\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('de5cac19-7b4a-4e07-b4d7-0930501999f5','Потребительский садово-огороднический кооператив \"Сибиряк-1\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('de69770d-f75a-4cba-bd56-246af1e0c90d','Таежная','ул','8603',7,'7da55fe9-4830-4a1d-8b06-232b118f213a'),('de7429ea-24c3-404b-8785-f1bd1da2de45','Садово-огородническое товарищество \"Проектировщик-1\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('de904b87-80c9-4395-a8d9-91b0b70720ac','Дорожников','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('dea1c773-d381-4511-abda-19bfacf79fcb','Энтузиастов','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('deacfef0-7d0b-4c36-9ced-d7a3ef59fbf0','9-й','мкр','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('deae3986-9484-49dd-b12c-a61d3f9d79cf','Потребительский садово-огороднический кооператив \"Нива\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('decb4473-987e-408f-8814-656d4a2075ca','Новая','ул','8603',7,'cef36edf-c8f7-474d-a3e6-17573924be26'),('ded33a62-e053-4fb7-be35-7a893a21d490','Лицензионный участок Тагринский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('dfae3918-aacf-402f-9f85-5c3d5c36593d','Садово-огородническое товарищество','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('dfdfde47-0be3-4b31-a139-d1feb25d7899','Дачный потребительский кооператив \"Ермак-Ёган\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('e03dcded-1b9e-4c68-b9dc-45fd7247e99d','Сосновый Бор','д','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('e06096a0-97f0-4993-8adb-32209ec96510','Радужная','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('e0aed116-2687-4561-b3f5-8f23d25cdcf7','Дачное некоммерческое товарищество \"Индустрия\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('e10bdc77-2f96-4422-b853-d60c8d576871','Лицензионный участок Варьеганский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('e13be00c-5ecf-4014-9b52-48253c4189ff','Лицензионный участок Советский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('e1641a95-76e7-4555-81db-d8559b751097','Строителей','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('e17e17a0-76b1-46f4-9586-c6fc3547f55a','12П','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('e2233268-280d-4f7a-acb6-90abc99b9629','22','мкр','8603',6,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('e224ece2-781c-4ded-a41c-c8d0e899c4e6','Белорусский','п','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('e23b3ab0-6006-4d38-8f16-175115d4037e','Садоводческое некоммерческое товарищество \"Монтажник\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('e265072e-6d15-45c5-afb3-89e512cb937d','Ломоносова','ул','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('e2855395-98df-4c3f-b4cf-d6b4b084ef28','Зеленый','пер','8603',7,'91e27416-360a-4e1a-aef6-be21d69ff97f'),('e352cfd5-b7aa-4ac9-a620-f69c52c5a90e','Гагарина','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('e3649dd7-f0b7-4efc-82ad-119bf11323cd','Садово-огородническое некомерческое товарищество \"Ягодка\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('e3bbea1b-0440-488c-a3ff-69f9c559f5ed','Депутатский','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('e3f4bb4c-b634-4f77-b00a-e6cb55286bf6','ВМК','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('e3f96691-b2c1-4d92-b9df-6b598f7bb313','Старт','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('e47a32ec-b075-4a82-a394-4971d3bf8ace','Набережная','ул','8603',7,'18b9ce52-c79f-4133-97a9-c19a0d4b1e0f'),('e4ce1767-e42a-47c4-86dd-e7bd6dfa6579','Монтажников Пионерная база','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('e4eb85ff-503a-4309-9fc6-67f0ad1fbb94','Садово-огородническое товарищество \"Поиск\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('e51612b9-cdc3-4a71-9b63-b23b2590a68f','24-я','ул','8603',7,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('e5d0e621-6f30-4d23-a9d0-63a9cc42dc3d','Садово-огородническое некоммерческое товарищество \"Буровик\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('e5d35fa9-5ad8-4690-bf3a-8fbae3578999','Набережная','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('e60a1ef5-3311-4140-85b5-b9dfd2aac8ce','НПС-2','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('e656d32c-2b6f-4908-b19e-cfb21f2f35a2','Чапаева','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('e69ebc13-f401-4e07-9c9e-cb718ca3851e','Зелёная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('e72564b1-b643-4a29-8327-426346de89a8','Садово-огороднический потребительский кооператив \"Заречный\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('e779c862-8e7d-44b2-92b7-f063ea6eb709','Садово-огородническое товарищество \"Весна\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('e7c522ea-45f1-4e7f-b639-78ce495edc11','Садово-огородническое товарищество \"Строитель\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('e8206e35-1a28-4603-a33a-4fb35e98375c','Садово-огородническое товарищество \"Ивушка\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('e8699e5c-2c95-49c7-957c-083b55a7e3fe','Садово-огородническое некоммерческое товарищество \"Малиновка РЭБ Флота\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('e8da3c32-4988-4287-a9ea-ce00a0299096','Героев Самотлора','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('e8f55a19-50b6-4b32-bcda-6b39b8082918','Садово-огородническое товарищество \"Ромашка\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('e902f0ea-132d-415a-a693-5da8943082a8','Садоводческое товарищество \"Рябинка\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('e9102048-f588-4c4c-8f8b-66857bcfb22e','Двигательмонтаж','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('e9163806-f1ee-449a-b71c-aa3eb251a17a','3-й','мкр','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('ea872068-0ce2-49f7-bbb6-dd36ff34d0b7','Фаэтон','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('eb5ea76a-3d71-4dea-aa12-44093386d4fd','Садоводческое товарищество \"Шахтер\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('ebc3228f-1b4b-4710-8ce3-f672ca3acd9e','Садово-огорднический потребительский кооператив \"Ремонтник-84\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('ec030f12-e512-4184-843b-e4cd51d09984','Садово-огородническое товарищество \"Гек\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ec832d4d-ec41-479d-bb23-daa650c259d4','Лицензионный участок Северо-Покурский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('eca77b3e-a3b9-4f6c-8dec-e86eb26c3b93','Новая','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ecfecbae-4b9e-4044-8eea-c25665fe495c','Яблоневая','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ed01ed6c-8564-4e55-ac8b-f3a0a0d45247','Озерный','кв-л','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ed84865b-4aab-451b-8cf2-34dbc1f23262','5-й','мкр','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('eda0f812-6f38-4e02-9782-cf3f74e46ff8','Садово-огородническое товарищество \"Просека\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('edbb20fc-0930-425e-ba7a-2502a1bda8ee','Лицензионный уч-к Верхне-Колек-Ёганский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('edde1278-6e64-4e5d-a73b-ca8301c12801','Лицензионный участок Хохряковский','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('ede092b3-9763-42d6-ba32-a520211c5c50','УТТ-4','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ee1e15cc-75fe-4018-8b77-5211555ac59a','Месторождение нефти Мыхпайское','тер','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('ee549d6b-c9e5-4140-8d56-a33ca5362423','Садово-огородническое товарищество \"СОНТ Транспортник-9\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('ee5884c3-4dd9-42dd-9542-e8f704a2c852','Корлики','с','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('ee7b7a9f-26fc-49b3-bfe4-ad8b096f05cb','Кооперативный','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('eea7c8db-a577-4224-a338-acadf26c4765','Южная промышленная','зона','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('eef28670-305d-46c3-9b5a-b5f98bff17b9','Садово-огородническое некоммерческое товарищество \"Самотлор-85\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ef14bb0e-fd5d-4ee0-b1c4-c058a529deed','Комплекс гаражей 7а','гск','8603',90,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('ef1ad78c-12fc-468d-ae40-cc935f5dd0d6','Молодежная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ef22343d-d7f1-4235-9812-19bb38afc0e8','Брусничная','ул','8603',7,'87e7d3bc-66ef-4694-bd38-0ab4ec558e8c'),('efbbc83c-ab12-4ded-b472-171e7a0f11d2','Победы','пр-кт','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f011f724-148d-4d06-882d-c36f7d901ec2','Спортивный','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('f01ba429-0896-4fb0-b631-48cc151a3b28','Парковый','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f0bdb980-4b5b-4c88-89ff-6b4b14a8d21a','Нововартовская','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f0c6894f-c0ce-4f56-b845-3cc6ba740de1','Еловый','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f0e03b78-449f-4706-95e5-dee3404d232d','Прохладная','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('f1412652-c840-4d7f-9838-76195d57a7c5','3П Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f2404f9d-06c6-4d71-a7a7-ee05b993387b','Садово-огородническое некоммерческое товарищество \"Урожай\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f2664ef9-f817-4549-9599-0b009f9ccee0','Геологов','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411'),('f2ffc684-b47c-40cd-b7f1-2b312b07d565','Моховая','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f3d47574-e385-48ea-93fc-7041e5027149','УМР-1','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f400326c-fff2-4a38-8354-3f63d0f6009d','Садовое товарищество \"Приозерное\"','снт','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('f447e725-b9ba-4128-bbec-daee9c695376','Садово-огороднический кооператив \"Хозяюшка\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f46733e1-d68b-43e1-9fb3-9e45e2fdc65e','Автомобилист','гск','8603',90,'f471350c-1dd9-44b1-8ef8-ecc51788b113'),('f471350c-1dd9-44b1-8ef8-ecc51788b113','Северо-западная коммунальная зона','промзона','8603',6,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('f4bcfc3a-d28a-450b-9a63-620c9d05550b','Садово-огородный кооператив \"Пенсионер\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f4bd6074-123d-4d73-9fa0-892a7128888f','Лицензионный участок Рославльский','гск','8603',90,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('f4e5f59c-1de9-426b-9993-951be0cf74fe','Нефтяников','ул','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('f4eaac09-4bf5-4764-a20b-e51e27fcbd7d','Московкина','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f5577392-3746-422c-bc92-ee98fef5a71b','Ромашка','п','8603',6,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f58405d8-dcac-47b9-90ba-64a1da70d6e2','Садово-огородническое некоммерческое товарищество \"Эксперимент\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('f5cbafea-9ed9-4df9-b1cb-a030a5207ecd','Тупиковый','пер','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('f5ec135d-2511-40aa-9226-a1fd5bcb2546','18П Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f63203e0-920a-4b75-802a-43cb8438dc3d','Сквер имени Виктора Ивановича Муравленко','тер','8603',90,'d557be14-f72f-44d7-a1b1-de1b92a0f4d6'),('f6d4996c-bb07-4583-8c32-73ec2e577af8','Зимняя Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f71b3a9b-5f08-452b-9670-86c129c832e7','УПТК','п','8603',6,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('f71ef667-579e-417b-bec3-a37d0a73264d','Интернациональная','ул','8603',7,'c757c8e4-5a19-4ef4-9303-4ee144c6f01c'),('f76f0644-ff8a-478f-a898-69057caaf52d','Березовая','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('f7a29da1-9393-4579-96b7-0e95234180b7','Садовое некомерческое товарищество \"Энергетик\"','снт','8603',90,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('f8264ff5-41f5-4a8f-83d7-fdada2c6991d','Калиновый','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f85b4eb8-5a55-4c13-8cfc-e9e205793dae','Пионерная база','п','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('f87970f5-4aef-4520-86b0-c229ca9aab42','Савкино','п','8603',6,'078af68d-30b5-4be3-8983-ca6c7c6425be'),('f881b778-43b0-427d-8471-55b2298cca5e','16-я','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('f89b0521-2ad6-418b-84b6-011c948bd3b1','Дачное некомерческое товарищество \"Черёмушки\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f8c0b3de-6c02-444e-9df5-8be5643f7418','Садово-огородническое некоммерческое товарищество \"Клубничка-1\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f8e8d21e-109d-4c9c-a2cb-20a85401cfbb','Садово-огородническое товарищество \"Электрик\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('f8eba3c4-56fe-4319-bf10-6719c365a185','Северный','пер','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('fa795403-66d2-429e-9f73-cf80aa7d5774','Тампонажная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('fa82fb6b-3b6d-439b-ae5a-beb99a10da6a','8-й','мкр','8603',7,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('fa906492-d50a-439a-84e3-e997275efffe','Летная','ул','8603',7,'d77ac5d7-461f-4fde-9bb4-3028366ec592'),('fae578f4-5d7b-4664-8c87-b056019d7fb1','Авиаторов Западный промышленный узел','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('fbad7748-c3a3-4b92-9369-6b0f96088589','Сосновая','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('fbb2d27c-9285-440a-ad73-bc4e2ffb4938','СУ-56','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('fbe1b4ae-d1e1-48b8-89e6-bc6e33c4fbb6','Лесной','пер','8603',7,'3cfb0a60-f45c-44c5-bd2c-f58fdc87a9d0'),('fbef85f0-b321-4325-9ac4-7b2fd07b9491','Садово-дачное некоммерческое партнерство \"Мега-Плюс\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('fbf4fa7e-fe09-4443-b89d-d4c1b99765fc','Автомобилист','гск','8603',90,'81b8e5da-4853-4c42-8e1e-0084c69011c4'),('fc286c30-99c2-4089-bc32-c87b4c3c7e3f','Кедровая','ул','8603',7,'6ed9c8cf-84dd-4fcb-a782-ae4c06ff015e'),('fc847e36-f8b4-4ce5-9910-f17e9974ea59','4-я','ул','8603',7,'9c3931c6-c153-4233-91d5-b5eacf482348'),('fd1494b6-1740-4ddc-8baf-1339e51b0e2b','Производственная база','тер','8603',90,'41f54ace-6c41-4814-8f91-8a177c3e58a1'),('fd149f5f-93c6-4cd5-9b67-ebfb3236373b','Речная','ул','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('fd5239fe-0e26-44b7-821e-251c60088518','Чумина','ул','8603',7,'26fab781-6726-4da3-9b96-07fe3edd74a8'),('fd57be2f-6cbf-4f79-889b-7e460acfe8ac','Энергетик','снт','8603',90,'394a840f-9502-406f-a8be-3a2aa9e8f075'),('fd871307-c6d0-4fb8-a908-3651f50700cf','Садово-огородническое некоммерческое товарищество \"Черногорец\"','снт','8603',90,'bac1e4df-1af1-4144-8793-3a6960618e2a'),('fda4d935-ef21-4ebb-adc6-46cfee72ec7d','Автомобилистов','ул','8603',7,'d6c7f92f-3537-4b5d-b4c8-be8dd97101b4'),('fe281609-11e2-4956-9878-9ad86016934d','Рыбников','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('fe46e107-41af-4d4c-85c5-4d18987d664b','СНА','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('fe55935c-c337-4c48-b40f-82904fb2289b','Садово-огородническое товарищество \"Озерки\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('fe9479c0-4e25-42b5-86dd-f69a9fc9eaec','Беловежский','п','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('fea05037-2bed-4e14-a78d-9ad557cb05db','Таежная','ул','8603',7,'11886072-72b4-481e-83fb-6733bf5f275a'),('feeca429-b4a8-412e-b189-c0a9a7a232ce','Садово-огородническое некоммерческое товарищество \"Подземник\"','снт','8603',90,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ff6c2787-6a3e-43cd-9bae-769121214166','Родниковый','пер','8603',7,'0bf0f4ed-13f8-446e-82f6-325498808076'),('ff755b1b-7731-487e-9abb-54f25f01b855','Береговая','ул','8603',7,'017d3cdb-0584-4ad3-943f-3cc5eec27411');
/*!40000 ALTER TABLE `fias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fieldslabel`
--

DROP TABLE IF EXISTS `fieldslabel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fieldslabel` (
  `fieldslabel_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `model` char(128) NOT NULL,
  `attribute` char(128) NOT NULL,
  `label` char(128) NOT NULL,
  PRIMARY KEY (`fieldslabel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fieldslabel`
--

LOCK TABLES `fieldslabel` WRITE;
/*!40000 ALTER TABLE `fieldslabel` DISABLE KEYS */;
/*!40000 ALTER TABLE `fieldslabel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fregatsettings`
--

DROP TABLE IF EXISTS `fregatsettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fregatsettings` (
  `fregatsettings_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `fregatsettings_recoverysend_emailtheme` char(255) DEFAULT NULL COMMENT 'Тема электронного письма',
  `fregatsettings_recoverysend_emailfrom` char(255) DEFAULT NULL COMMENT 'Электронная почта, от которой отправляется письмо',
  `fregatsettings_glavvrach_name` char(255) DEFAULT NULL COMMENT 'ФИО Главного врача',
  `fregatsettings_uchrezh_namesokr` char(255) DEFAULT NULL COMMENT 'Сокращенное наименование учреждения',
  `fregatsettings_uchrezh_name` char(255) DEFAULT NULL COMMENT 'Полное наименование учреждения',
  PRIMARY KEY (`fregatsettings_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fregatsettings`
--

LOCK TABLES `fregatsettings` WRITE;
/*!40000 ALTER TABLE `fregatsettings` DISABLE KEYS */;
INSERT INTO `fregatsettings` VALUES (1,'БУ \"Нижневартовская городская поликлиника\"','it@mugp-nv.ru','БЛЮСОВА МАРИЯ ЕВСТИГНЕЕВНА','БУ \"Нижневартовская городская поликлиника\"','Бюджетное учреждение Ханты-Мансийского автономного округа - Югры \"Нижневартовская городская поликлиника\"');
/*!40000 ALTER TABLE `fregatsettings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `generalsettings`
--

DROP TABLE IF EXISTS `generalsettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `generalsettings` (
  `generalsettings_id` int(11) NOT NULL AUTO_INCREMENT,
  `ofoms_host` varchar(255) DEFAULT NULL,
  `ofoms_port` int(11) DEFAULT NULL,
  `ofoms_login` varchar(255) DEFAULT NULL,
  `ofoms_password` varchar(255) DEFAULT NULL,
  `ofoms_remotehost` varchar(255) DEFAULT NULL,
  `version_db` char(10) DEFAULT NULL,
  `version_base` char(10) DEFAULT NULL,
  `version_fregat` char(10) DEFAULT NULL,
  `version_portalofoms` char(10) DEFAULT NULL,
  `mailer_host` char(255) DEFAULT NULL,
  `mailer_smtpport` smallint(6) DEFAULT NULL,
  `mailer_login` char(255) DEFAULT NULL,
  `mailer_password` char(255) DEFAULT NULL,
  PRIMARY KEY (`generalsettings_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `generalsettings`
--

LOCK TABLES `generalsettings` WRITE;
/*!40000 ALTER TABLE `generalsettings` DISABLE KEYS */;
INSERT INTO `generalsettings` VALUES (1,'172.19.17.16',55555,'R14099','132465','portal.tfoms','1','1','1','1',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `generalsettings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glaukuchet`
--

DROP TABLE IF EXISTS `glaukuchet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glaukuchet` (
  `glaukuchet_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `glaukuchet_uchetbegin` date NOT NULL COMMENT 'Дата постановки на учет',
  `glaukuchet_detect` tinyint(1) unsigned NOT NULL COMMENT 'Вид выявления заболевания',
  `glaukuchet_deregdate` date DEFAULT NULL COMMENT 'Дата снятия с учета',
  `glaukuchet_deregreason` tinyint(1) unsigned DEFAULT NULL COMMENT 'Причина снятия с учета',
  `glaukuchet_stage` tinyint(1) unsigned NOT NULL COMMENT 'Стадия глаукомы',
  `glaukuchet_operdate` date DEFAULT NULL COMMENT 'Дата последнего оперативного лечения',
  `glaukuchet_invalid` tinyint(1) unsigned DEFAULT NULL COMMENT 'Группа инвалидности',
  `glaukuchet_lastvisit` date NOT NULL COMMENT 'Дата последней явки на прием',
  `glaukuchet_lastmetabol` date DEFAULT NULL COMMENT 'Дата последнего курса метоболической терапии',
  `id_patient` mediumint(8) unsigned NOT NULL COMMENT 'Пациент',
  `id_employee` smallint(5) unsigned NOT NULL COMMENT 'Врач',
  `id_class_mkb` int(11) NOT NULL COMMENT 'Диагноз',
  `glaukuchet_comment` varchar(512) DEFAULT NULL COMMENT 'Заметка',
  `glaukuchet_username` char(128) NOT NULL COMMENT 'Пользователь изменивший запись',
  `glaukuchet_lastchange` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата изменения записи',
  PRIMARY KEY (`glaukuchet_id`),
  KEY `fk_glaukuchet_patient1_idx` (`id_patient`),
  KEY `fk_glaukuchet_employee1_idx` (`id_employee`),
  KEY `fk_glaukuchet_class_mkb1_idx` (`id_class_mkb`),
  CONSTRAINT `fk_glaukuchet_class_mkb1` FOREIGN KEY (`id_class_mkb`) REFERENCES `class_mkb` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_glaukuchet_employee1` FOREIGN KEY (`id_employee`) REFERENCES `employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_glaukuchet_patient1` FOREIGN KEY (`id_patient`) REFERENCES `patient` (`patient_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glaukuchet`
--

LOCK TABLES `glaukuchet` WRITE;
/*!40000 ALTER TABLE `glaukuchet` DISABLE KEYS */;
/*!40000 ALTER TABLE `glaukuchet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glprep`
--

DROP TABLE IF EXISTS `glprep`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glprep` (
  `glprep_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_glaukuchet` mediumint(8) unsigned NOT NULL COMMENT 'Карта глаукомного больного',
  `id_preparat` smallint(5) unsigned NOT NULL COMMENT 'Препарат',
  `glprep_rlocat` tinyint(1) unsigned DEFAULT NULL COMMENT 'Категория льготного лекарственного обеспечения',
  PRIMARY KEY (`glprep_id`),
  KEY `fk_glprep_glaukuchet1_idx` (`id_glaukuchet`),
  KEY `fk_glprep_preparat1_idx` (`id_preparat`),
  CONSTRAINT `fk_glprep_glaukuchet1` FOREIGN KEY (`id_glaukuchet`) REFERENCES `glaukuchet` (`glaukuchet_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_glprep_preparat1` FOREIGN KEY (`id_preparat`) REFERENCES `preparat` (`preparat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glprep`
--

LOCK TABLES `glprep` WRITE;
/*!40000 ALTER TABLE `glprep` DISABLE KEYS */;
/*!40000 ALTER TABLE `glprep` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groupldap`
--

DROP TABLE IF EXISTS `groupldap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groupldap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(128) NOT NULL COMMENT 'Имя группы',
  `fullname` varchar(128) NOT NULL COMMENT 'Описание',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groupldap`
--

LOCK TABLES `groupldap` WRITE;
/*!40000 ALTER TABLE `groupldap` DISABLE KEYS */;
/*!40000 ALTER TABLE `groupldap` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupa`
--

DROP TABLE IF EXISTS `grupa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupa` (
  `grupa_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `grupa_name` char(255) NOT NULL,
  PRIMARY KEY (`grupa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupa`
--

LOCK TABLES `grupa` WRITE;
/*!40000 ALTER TABLE `grupa` DISABLE KEYS */;
/*!40000 ALTER TABLE `grupa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupavid`
--

DROP TABLE IF EXISTS `grupavid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupavid` (
  `grupavid_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `grupavid_main` tinyint(1) NOT NULL DEFAULT '0',
  `id_grupa` smallint(6) NOT NULL,
  `id_matvid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`grupavid_id`),
  KEY `fk_grupavid_grupa1_idx` (`id_grupa`),
  KEY `fk_grupavid_matvid1_idx` (`id_matvid`),
  CONSTRAINT `fk_grupavid_grupa1` FOREIGN KEY (`id_grupa`) REFERENCES `grupa` (`grupa_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_grupavid_matvid1` FOREIGN KEY (`id_matvid`) REFERENCES `matvid` (`matvid_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupavid`
--

LOCK TABLES `grupavid` WRITE;
/*!40000 ALTER TABLE `grupavid` DISABLE KEYS */;
/*!40000 ALTER TABLE `grupavid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `impemployee`
--

DROP TABLE IF EXISTS `impemployee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `impemployee` (
  `impemployee_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_importemployee` smallint(5) unsigned NOT NULL,
  `id_employee` smallint(5) unsigned NOT NULL COMMENT 'Сотрудник',
  PRIMARY KEY (`impemployee_id`),
  KEY `fk_impemployee_importemployee1_idx` (`id_importemployee`),
  KEY `fk_impemployee_employee1_idx` (`id_employee`),
  CONSTRAINT `fk_impemployee_employee1` FOREIGN KEY (`id_employee`) REFERENCES `employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_impemployee_importemployee1` FOREIGN KEY (`id_importemployee`) REFERENCES `importemployee` (`importemployee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `impemployee`
--

LOCK TABLES `impemployee` WRITE;
/*!40000 ALTER TABLE `impemployee` DISABLE KEYS */;
/*!40000 ALTER TABLE `impemployee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `importconfig`
--

DROP TABLE IF EXISTS `importconfig`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `importconfig` (
  `importconfig_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `os_filename` char(255) NOT NULL COMMENT 'Имя файла основных средств (.xls в директории "imp")',
  `os_startrow` smallint(6) NOT NULL DEFAULT '1' COMMENT 'Номер строки файла Excel, с которой считываются основные средства',
  `os_material_1c` char(5) NOT NULL COMMENT 'Позиция колонки "Код 1С" основных средств',
  `os_mattraffic_date` char(5) NOT NULL COMMENT 'Позиция колонки "Период" основных средств',
  `os_material_inv` char(5) NOT NULL COMMENT 'Позиция колонки "Инвентарный номер" основных средств',
  `os_material_name1c` char(5) NOT NULL COMMENT 'Позиция колонки "Наименование" основных средств',
  `os_material_price` char(5) NOT NULL COMMENT 'Позиция колонки "Цена" основных средств',
  `os_employee_fio` char(5) NOT NULL COMMENT 'Позиция колонки "ФИО Материально-ответственного лица" основных средств',
  `os_dolzh_name` char(5) NOT NULL COMMENT 'Позиция колонки "Должность Материально-ответственного лица" основных средств',
  `os_podraz_name` char(5) NOT NULL COMMENT 'Позиция колонки "Подразделение Материально-ответственного лица" основных средств',
  `os_material_serial` char(5) NOT NULL COMMENT 'Позиция колонки "Серийный номер" основных средств',
  `os_material_release` char(5) NOT NULL COMMENT 'Позиция колонки "Дата выпуска" основных средств',
  `os_material_status` char(5) NOT NULL COMMENT 'Позиция колонки "Состояние" основных средств',
  `os_material_schetuchet_kod` char(5) NOT NULL,
  `os_material_schetuchet_name` char(5) NOT NULL,
  `mat_filename` char(255) NOT NULL COMMENT 'Имя файла материалов (.xls в директории "imp")',
  `mat_startrow` smallint(6) NOT NULL DEFAULT '1' COMMENT 'Номер строки файла Excel, с которой считываются материалы',
  `mat_material_1c` char(5) NOT NULL COMMENT 'Позиция колонки "Код 1С" материалов',
  `mat_material_inv` char(5) NOT NULL COMMENT 'Позиция колонки "Инвентарный номер" материалов',
  `mat_material_name1c` char(5) NOT NULL COMMENT 'Позиция колонки "Наименование" материалов',
  `mat_material_number` char(5) NOT NULL COMMENT 'Позиция колонки "Количество" материалов',
  `mat_izmer_name` char(5) NOT NULL COMMENT 'Позиция колонки "Единица измерения" материалов',
  `mat_material_price` char(5) NOT NULL COMMENT 'Позиция колонки "Стоимость" материалов',
  `mat_employee_fio` char(5) NOT NULL COMMENT 'Позиция колонки "ФИО Материально-ответственного лица" материалов',
  `mat_dolzh_name` char(5) NOT NULL COMMENT 'Позиция колонки "Должность Материально-ответственного лица" материалов',
  `mat_podraz_name` char(5) NOT NULL COMMENT 'Позиция колонки "Подразделение Материально-ответственного лица" материалов',
  `mat_material_tip_nomenklaturi` char(5) NOT NULL COMMENT 'Позиция колонки "Тип номенклатуры" материалов',
  `mat_material_schetuchet_kod` char(5) NOT NULL,
  `mat_material_schetuchet_name` char(5) NOT NULL,
  `mat_material_izmer_kod_okei` char(5) NOT NULL,
  `logreport_reportcount` smallint(6) NOT NULL COMMENT 'Количество хранящихся отчетов импорта',
  `emp_filename` char(255) NOT NULL COMMENT 'Имя файла сотрудников (.txt в директории "imp")',
  `max_execution_time` smallint(6) NOT NULL COMMENT 'Максимальное время выполнения загрузки файлов импорта (в секундах)',
  `memory_limit` bigint(20) NOT NULL COMMENT 'Максимальное потребление оперативной памяти при импорте (в Байтах)',
  `gu_material_1c` char(5) NOT NULL COMMENT 'Позиция колонки "Код 1С" группового учета основных средств',
  `gu_material_inv` char(5) NOT NULL COMMENT 'Позиция колонки "Инвентарный номер" группового учета основных средств',
  `gu_material_name1c` char(5) NOT NULL COMMENT 'Позиция колонки "Наименование" группового учета основных средств',
  `gu_material_serial` char(5) NOT NULL COMMENT 'Позиция колонки "Серийный номер" группового учета основных средств',
  `gu_material_release` char(5) NOT NULL COMMENT 'Позиция колонки "Дата выпуска" группового учета основных средств',
  `gu_material_number` char(5) NOT NULL COMMENT 'Позиция колонки "Количество" группового учета основных средств',
  `gu_material_price` char(5) NOT NULL COMMENT 'Позиция колонки "Цена" группового учета основных средств',
  `gu_podraz_name` char(5) NOT NULL COMMENT 'Позиция колонки "Подразделение Материально-ответственного лица" группового учета основных средств',
  `gu_employee_fio` char(5) NOT NULL COMMENT 'Позиция колонки "ФИО Материально-ответственного лица" группового учета основных средств',
  `gu_dolzh_name` char(5) NOT NULL COMMENT 'Позиция колонки "Должность Материально-ответственного лица" группового учета основных средств',
  `gu_filename` char(255) NOT NULL COMMENT 'Имя файла группового учета основных средств (.xlsx в директории "imp")',
  `gu_startrow` smallint(6) NOT NULL COMMENT 'Номер строки файла Excel, с которой считывается групповой учет основных средств',
  `importconfig_do` tinyint(1) NOT NULL COMMENT 'Включить импорт файлов из 1С',
  `gu_material_schetuchet_kod` char(5) NOT NULL,
  `gu_material_schetuchet_name` char(5) NOT NULL,
  PRIMARY KEY (`importconfig_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `importconfig`
--

LOCK TABLES `importconfig` WRITE;
/*!40000 ALTER TABLE `importconfig` DISABLE KEYS */;
INSERT INTO `importconfig` VALUES (1,'Upload_BGU_OC',2,'B','A','C','D','E','I','J','K','G','H','F','L','M','Upload_BGU_M3',2,'B','C','D','F','E','G','H','I','J','A','K','L','M',100,'Upload_Kamin',7200,3000000000,'A','B','C','D','E','F','G','H','I','J','Upload_BGU_OCgr',2,1,'K','L');
/*!40000 ALTER TABLE `importconfig` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `importemployee`
--

DROP TABLE IF EXISTS `importemployee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `importemployee` (
  `importemployee_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `importemployee_combination` char(255) NOT NULL COMMENT 'Словосочетание',
  `id_build` tinyint(3) unsigned DEFAULT NULL COMMENT 'Здание',
  `id_podraz` smallint(5) unsigned DEFAULT NULL COMMENT 'Подразделение',
  PRIMARY KEY (`importemployee_id`),
  KEY `fk_importemployee_build1_idx` (`id_build`),
  KEY `fk_importemployee_podraz1_idx` (`id_podraz`),
  CONSTRAINT `fk_importemployee_build1` FOREIGN KEY (`id_build`) REFERENCES `build` (`build_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_importemployee_podraz1` FOREIGN KEY (`id_podraz`) REFERENCES `podraz` (`podraz_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `importemployee`
--

LOCK TABLES `importemployee` WRITE;
/*!40000 ALTER TABLE `importemployee` DISABLE KEYS */;
/*!40000 ALTER TABLE `importemployee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `importmaterial`
--

DROP TABLE IF EXISTS `importmaterial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `importmaterial` (
  `importmaterial_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `importmaterial_combination` char(255) NOT NULL COMMENT 'Словосочетание',
  `id_matvid` smallint(5) unsigned NOT NULL COMMENT 'Вид',
  PRIMARY KEY (`importmaterial_id`),
  KEY `fk_importmaterial_matvid1_idx` (`id_matvid`),
  CONSTRAINT `fk_importmaterial_matvid1` FOREIGN KEY (`id_matvid`) REFERENCES `matvid` (`matvid_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1284 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `importmaterial`
--

LOCK TABLES `importmaterial` WRITE;
/*!40000 ALTER TABLE `importmaterial` DISABLE KEYS */;
/*!40000 ALTER TABLE `importmaterial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `installakt`
--

DROP TABLE IF EXISTS `installakt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `installakt` (
  `installakt_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `installakt_date` date NOT NULL,
  `id_installer` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`installakt_id`),
  KEY `fk_installakt_employee1_idx` (`id_installer`),
  CONSTRAINT `fk_installakt_employee1` FOREIGN KEY (`id_installer`) REFERENCES `employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `installakt`
--

LOCK TABLES `installakt` WRITE;
/*!40000 ALTER TABLE `installakt` DISABLE KEYS */;
/*!40000 ALTER TABLE `installakt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `islist`
--

DROP TABLE IF EXISTS `islist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `islist` (
  `islist_id` int(11) NOT NULL AUTO_INCREMENT,
  `islist_title` varchar(130) NOT NULL COMMENT 'Имя системы',
  `islist_text` text COMMENT 'Краткое описание',
  `islist_ico` varchar(150) DEFAULT '' COMMENT 'Иконка',
  `islist_targeturl` varchar(255) NOT NULL COMMENT 'Ссылка',
  `islist_x1` int(11) DEFAULT NULL,
  `islist_y1` int(11) DEFAULT NULL,
  `islist_w` int(11) DEFAULT NULL,
  `islist_h` int(11) DEFAULT NULL,
  `islist_dim_w` int(11) DEFAULT NULL,
  `islist_dim_h` int(11) DEFAULT NULL,
  PRIMARY KEY (`islist_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `islist`
--

LOCK TABLES `islist` WRITE;
/*!40000 ALTER TABLE `islist` DISABLE KEYS */;
/*!40000 ALTER TABLE `islist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `izmer`
--

DROP TABLE IF EXISTS `izmer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `izmer` (
  `izmer_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `izmer_name` char(255) NOT NULL COMMENT 'Единица измерения',
  `izmer_kod_okei` char(10) DEFAULT NULL COMMENT 'Код ОКЕИ',
  PRIMARY KEY (`izmer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `izmer`
--

LOCK TABLES `izmer` WRITE;
/*!40000 ALTER TABLE `izmer` DISABLE KEYS */;
/*!40000 ALTER TABLE `izmer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logreport`
--

DROP TABLE IF EXISTS `logreport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logreport` (
  `logreport_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logreport_date` date NOT NULL COMMENT 'Дата импорта',
  `logreport_errors` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Количество ошибок',
  `logreport_updates` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Записей изменено',
  `logreport_additions` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Записей добавлено',
  `logreport_amount` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Количество записей',
  `logreport_missed` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Записей пропущено',
  `logreport_executetime` time DEFAULT NULL COMMENT 'Время выполнения импорта',
  `logreport_employeelastdate` datetime DEFAULT NULL COMMENT 'Дата последнего изменения файла для импорта сотрудников',
  `logreport_oslastdate` datetime DEFAULT NULL COMMENT 'Дата последнего изменения файла для импорта основных средств',
  `logreport_matlastdate` datetime DEFAULT NULL COMMENT 'Дата последнего изменения файла для импорта материалов',
  `logreport_gulastdate` datetime DEFAULT NULL COMMENT 'Дата последнего изменения файла для импорта группового учета основных средств',
  `logreport_memoryused` bigint(20) unsigned DEFAULT '0' COMMENT 'Выделено памяти',
  PRIMARY KEY (`logreport_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logreport`
--

LOCK TABLES `logreport` WRITE;
/*!40000 ALTER TABLE `logreport` DISABLE KEYS */;
/*!40000 ALTER TABLE `logreport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `material`
--

DROP TABLE IF EXISTS `material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `material` (
  `material_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `material_name` varchar(500) DEFAULT NULL COMMENT 'Наименование',
  `material_name1c` varchar(500) NOT NULL COMMENT 'Наименование (Из 1С)',
  `material_1c` char(20) DEFAULT NULL COMMENT 'Код 1С',
  `material_inv` char(50) NOT NULL COMMENT 'Инвентарный номер',
  `material_serial` char(255) DEFAULT NULL COMMENT 'Серийный номер',
  `material_release` date DEFAULT NULL COMMENT 'Дата выпуска',
  `material_number` decimal(12,3) unsigned NOT NULL DEFAULT '1.000' COMMENT 'Количество',
  `material_price` decimal(12,2) unsigned DEFAULT NULL COMMENT 'Стоимость',
  `material_tip` tinyint(1) unsigned NOT NULL COMMENT 'Тип',
  `material_writeoff` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Списан',
  `id_matvid` smallint(5) unsigned NOT NULL COMMENT 'Вид',
  `id_izmer` tinyint(3) unsigned NOT NULL COMMENT 'Единица измерения',
  `material_username` char(128) NOT NULL COMMENT 'Пользователь изменивший запись',
  `material_lastchange` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата изменения записи',
  `material_importdo` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Запись изменяема при импортировании',
  `id_schetuchet` smallint(5) unsigned DEFAULT NULL COMMENT '╤ўхЄ єўхЄр',
  `material_comment` varchar(512) DEFAULT NULL COMMENT '╨Ч╨░╨╝╨╡╤В╨║╨░',
  PRIMARY KEY (`material_id`),
  KEY `fk_material_matvid1_idx` (`id_matvid`),
  KEY `fk_material_izmer1_idx` (`id_izmer`),
  KEY `fk_material_schetuchet1_idx` (`id_schetuchet`),
  CONSTRAINT `fk_material_izmer1` FOREIGN KEY (`id_izmer`) REFERENCES `izmer` (`izmer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_material_matvid1` FOREIGN KEY (`id_matvid`) REFERENCES `matvid` (`matvid_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_material_schetuchet1` FOREIGN KEY (`id_schetuchet`) REFERENCES `schetuchet` (`schetuchet_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `material`
--

LOCK TABLES `material` WRITE;
/*!40000 ALTER TABLE `material` DISABLE KEYS */;
/*!40000 ALTER TABLE `material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matlog`
--

DROP TABLE IF EXISTS `matlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matlog` (
  `matlog_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_logreport` int(10) unsigned NOT NULL,
  `matlog_filename` char(255) NOT NULL COMMENT 'Имя файла',
  `matlog_filelastdate` datetime DEFAULT NULL COMMENT 'Дата изменения файла',
  `matlog_rownum` mediumint(8) unsigned NOT NULL COMMENT 'Номер строки',
  `matlog_type` tinyint(1) NOT NULL COMMENT 'Тип сообщения',
  `matlog_message` varchar(1000) NOT NULL COMMENT 'Сообщение',
  `material_name1c` varchar(400) DEFAULT NULL COMMENT 'Наименование (Из 1С)',
  `material_1c` char(255) DEFAULT NULL COMMENT 'Код 1С',
  `material_inv` char(255) DEFAULT NULL COMMENT 'Инвентарный номер',
  `material_serial` char(255) DEFAULT NULL COMMENT 'Серийный номер',
  `material_release` char(255) DEFAULT NULL COMMENT 'Дата выпуска',
  `material_number` char(255) DEFAULT NULL COMMENT 'Количество',
  `material_price` char(255) DEFAULT NULL COMMENT 'Цена',
  `material_tip` char(255) DEFAULT NULL COMMENT 'Тип',
  `material_writeoff` char(255) DEFAULT NULL COMMENT 'Статус списания',
  `izmer_name` char(255) DEFAULT NULL COMMENT 'Единица измерения',
  `izmer_kod_okei` char(255) DEFAULT NULL COMMENT '╨Ъ╨╛╨┤ ╨Ю╨Ъ╨Х╨Ш ╨╡╨┤╨╕╨╜╨╕╤Ж╤Л ╨╕╨╖╨╝╨╡╤А╨╡╨╜╨╕╤П',
  `matvid_name` char(255) DEFAULT NULL COMMENT 'Вид',
  `schetuchet_kod` char(255) DEFAULT NULL COMMENT '╨Ъ╨╛╨┤ ╤Б╤З╨╡╤В╨░ ╤Г╤З╨╡╤В╨░',
  `schetuchet_name` char(255) DEFAULT NULL COMMENT '╨Э╨░╨╕╨╝╨╡╨╜╨╛╨▓╨░╨╜╨╕╨╡ ╤Б╤З╨╡╤В╨░ ╤Г╤З╨╡╤В╨░',
  PRIMARY KEY (`matlog_id`),
  KEY `fk_matlog_logreport1_idx` (`id_logreport`),
  CONSTRAINT `fk_matlog_logreport1` FOREIGN KEY (`id_logreport`) REFERENCES `logreport` (`logreport_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matlog`
--

LOCK TABLES `matlog` WRITE;
/*!40000 ALTER TABLE `matlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `matlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mattraffic`
--

DROP TABLE IF EXISTS `mattraffic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mattraffic` (
  `mattraffic_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `mattraffic_date` date NOT NULL COMMENT 'Дата операции',
  `mattraffic_number` decimal(12,3) unsigned NOT NULL DEFAULT '1.000' COMMENT 'Количество (Задействованное в операции)',
  `id_material` mediumint(8) unsigned NOT NULL COMMENT 'Материальная ценность',
  `id_mol` smallint(5) unsigned NOT NULL COMMENT 'Материально-ответственное лицо',
  `mattraffic_username` char(128) NOT NULL COMMENT 'Пользователь изменивший запись',
  `mattraffic_lastchange` datetime NOT NULL COMMENT 'Дата изменения записи',
  `mattraffic_tip` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Тип операции',
  `mattraffic_forimport` tinyint(1) unsigned DEFAULT NULL COMMENT 'Используется при импорте материальных ценностей',
  PRIMARY KEY (`mattraffic_id`),
  KEY `fk_mattraffic_material1_idx` (`id_material`),
  KEY `fk_mattraffic_employee1_idx` (`id_mol`),
  CONSTRAINT `fk_mattraffic_employee1` FOREIGN KEY (`id_mol`) REFERENCES `employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mattraffic_material1` FOREIGN KEY (`id_material`) REFERENCES `material` (`material_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mattraffic`
--

LOCK TABLES `mattraffic` WRITE;
/*!40000 ALTER TABLE `mattraffic` DISABLE KEYS */;
/*!40000 ALTER TABLE `mattraffic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matvid`
--

DROP TABLE IF EXISTS `matvid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matvid` (
  `matvid_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `matvid_name` char(255) NOT NULL COMMENT 'Вид',
  PRIMARY KEY (`matvid_id`)
) ENGINE=InnoDB AUTO_INCREMENT=849 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matvid`
--

LOCK TABLES `matvid` WRITE;
/*!40000 ALTER TABLE `matvid` DISABLE KEYS */;
/*!40000 ALTER TABLE `matvid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `naklad`
--

DROP TABLE IF EXISTS `naklad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `naklad` (
  `naklad_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '╨Ш╨Ф ╨в╤А╨╡╨▒╨╛╨▓╨░╨╜╨╕╤П-╨╜╨░╨║╨╗╨░╨┤╨╜╨╛╨╣',
  `naklad_date` date NOT NULL COMMENT '╨Ф╨░╤В╨░',
  `id_mol_release` smallint(5) unsigned NOT NULL COMMENT '╨Ь╨Ю╨Ы, ╨║╤В╨╛ ╨╛╤В╨┐╤Г╤Б╤В╨╕╨╗',
  `id_mol_got` smallint(5) unsigned NOT NULL COMMENT '╨Ь╨Ю╨Ы, ╨║╤В╨╛ ╨╖╨░╤В╤А╨╡╨▒╨╛╨▓╨░╨╗',
  PRIMARY KEY (`naklad_id`),
  KEY `fk_naklad_employee1_idx` (`id_mol_release`),
  KEY `fk_naklad_employee2_idx` (`id_mol_got`),
  CONSTRAINT `fk_naklad_employee1` FOREIGN KEY (`id_mol_release`) REFERENCES `employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_naklad_employee2` FOREIGN KEY (`id_mol_got`) REFERENCES `employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `naklad`
--

LOCK TABLES `naklad` WRITE;
/*!40000 ALTER TABLE `naklad` DISABLE KEYS */;
/*!40000 ALTER TABLE `naklad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nakladmaterials`
--

DROP TABLE IF EXISTS `nakladmaterials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nakladmaterials` (
  `nakladmaterials_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '╨Ш╨Ф',
  `id_naklad` mediumint(8) unsigned NOT NULL COMMENT '╨в╤А╨╡╨▒╨╛╨▓╨░╨╜╨╕╨╡-╨╜╨░╨║╨╗╨░╨┤╨╜╨░╤П',
  `id_mattraffic` mediumint(8) unsigned NOT NULL COMMENT '╨Ь╨░╤В╨╡╤А╨╕╨░╨╗╤М╨╜╨░╤П ╤Ж╨╡╨╜╨╜╨╛╤Б╤В╤М',
  `nakladmaterials_number` decimal(12,3) NOT NULL COMMENT '╨Ъ╨╛╨╗╨╕╤З╨╡╤Б╤В╨▓╨╛',
  PRIMARY KEY (`nakladmaterials_id`),
  KEY `fk_nakladmaterials_naklad1_idx` (`id_naklad`),
  KEY `fk_nakladmaterials_mattraffic1_idx` (`id_mattraffic`),
  CONSTRAINT `fk_nakladmaterials_mattraffic1` FOREIGN KEY (`id_mattraffic`) REFERENCES `mattraffic` (`mattraffic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_nakladmaterials_naklad1` FOREIGN KEY (`id_naklad`) REFERENCES `naklad` (`naklad_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nakladmaterials`
--

LOCK TABLES `nakladmaterials` WRITE;
/*!40000 ALTER TABLE `nakladmaterials` DISABLE KEYS */;
/*!40000 ALTER TABLE `nakladmaterials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organ`
--

DROP TABLE IF EXISTS `organ`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organ` (
  `organ_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `organ_name` char(255) NOT NULL COMMENT 'Организация',
  `organ_email` char(255) DEFAULT NULL COMMENT 'Электронная почта организации',
  `organ_phones` char(255) DEFAULT NULL COMMENT 'Телефоны организации',
  PRIMARY KEY (`organ_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organ`
--

LOCK TABLES `organ` WRITE;
/*!40000 ALTER TABLE `organ` DISABLE KEYS */;
/*!40000 ALTER TABLE `organ` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `osmotrakt`
--

DROP TABLE IF EXISTS `osmotrakt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `osmotrakt` (
  `osmotrakt_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Номер акта осмотра',
  `osmotrakt_comment` varchar(400) DEFAULT NULL COMMENT 'Описание причины неисправности',
  `id_reason` smallint(5) unsigned DEFAULT NULL COMMENT 'Причина неисправности',
  `id_user` smallint(5) unsigned NOT NULL COMMENT 'Эксплуататор материальной ценности',
  `id_master` smallint(5) unsigned NOT NULL COMMENT 'Составитель акта',
  `osmotrakt_date` date NOT NULL COMMENT 'Дата осмотра материальной ценности',
  `id_tr_osnov` mediumint(8) unsigned NOT NULL COMMENT 'Материальная ценность',
  PRIMARY KEY (`osmotrakt_id`),
  KEY `fk_osmotrakt_reason1_idx` (`id_reason`),
  KEY `fk_osmotrakt_employee1_idx` (`id_user`),
  KEY `fk_osmotrakt_employee2_idx` (`id_master`),
  KEY `fk_osmotrakt_tr_osnov1_idx` (`id_tr_osnov`),
  CONSTRAINT `fk_osmotrakt_employee1` FOREIGN KEY (`id_user`) REFERENCES `employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_osmotrakt_employee2` FOREIGN KEY (`id_master`) REFERENCES `employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_osmotrakt_reason1` FOREIGN KEY (`id_reason`) REFERENCES `reason` (`reason_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_osmotrakt_tr_osnov1` FOREIGN KEY (`id_tr_osnov`) REFERENCES `tr_osnov` (`tr_osnov_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `osmotrakt`
--

LOCK TABLES `osmotrakt` WRITE;
/*!40000 ALTER TABLE `osmotrakt` DISABLE KEYS */;
/*!40000 ALTER TABLE `osmotrakt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `osmotraktmat`
--

DROP TABLE IF EXISTS `osmotraktmat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `osmotraktmat` (
  `osmotraktmat_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Номер акта осмотра',
  `osmotraktmat_date` date NOT NULL COMMENT 'Дата осмотра материала',
  `id_master` smallint(5) unsigned NOT NULL COMMENT 'Составитель акта',
  PRIMARY KEY (`osmotraktmat_id`),
  KEY `fk_osmotraktmat_employee1_idx` (`id_master`),
  CONSTRAINT `fk_osmotraktmat_employee1` FOREIGN KEY (`id_master`) REFERENCES `employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `osmotraktmat`
--

LOCK TABLES `osmotraktmat` WRITE;
/*!40000 ALTER TABLE `osmotraktmat` DISABLE KEYS */;
/*!40000 ALTER TABLE `osmotraktmat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient` (
  `patient_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `patient_fam` char(255) NOT NULL COMMENT 'Фамилия',
  `patient_im` char(255) NOT NULL COMMENT 'Имя',
  `patient_ot` char(255) DEFAULT NULL COMMENT 'Отчество',
  `patient_dr` date NOT NULL COMMENT 'Дата рождения',
  `patient_pol` tinyint(1) unsigned NOT NULL COMMENT 'Пол',
  `id_fias` char(36) DEFAULT NULL COMMENT 'Адрес',
  `patient_dom` char(10) DEFAULT NULL COMMENT 'Дом',
  `patient_korp` char(10) DEFAULT NULL COMMENT 'Корпус',
  `patient_kvartira` char(10) DEFAULT NULL COMMENT 'Квартира',
  `patient_username` char(128) NOT NULL COMMENT 'Пользователь изменивший запись',
  `patient_lastchange` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата изменения записи',
  PRIMARY KEY (`patient_id`),
  KEY `fk_patient_fias1_idx` (`id_fias`),
  CONSTRAINT `fk_patient_fias1` FOREIGN KEY (`id_fias`) REFERENCES `fias` (`AOGUID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient`
--

LOCK TABLES `patient` WRITE;
/*!40000 ALTER TABLE `patient` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `podraz`
--

DROP TABLE IF EXISTS `podraz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `podraz` (
  `podraz_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `podraz_name` char(255) NOT NULL COMMENT 'Подразделение',
  PRIMARY KEY (`podraz_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `podraz`
--

LOCK TABLES `podraz` WRITE;
/*!40000 ALTER TABLE `podraz` DISABLE KEYS */;
/*!40000 ALTER TABLE `podraz` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preparat`
--

DROP TABLE IF EXISTS `preparat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preparat` (
  `preparat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `preparat_name` char(255) NOT NULL COMMENT 'Наименование препарата',
  PRIMARY KEY (`preparat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preparat`
--

LOCK TABLES `preparat` WRITE;
/*!40000 ALTER TABLE `preparat` DISABLE KEYS */;
/*!40000 ALTER TABLE `preparat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile` (
  `profile_id` smallint(5) unsigned NOT NULL,
  `profile_inn` char(12) DEFAULT NULL COMMENT 'ИНН',
  `profile_dr` date DEFAULT NULL COMMENT 'Дата рождения',
  `profile_pol` tinyint(1) DEFAULT NULL COMMENT 'Пол',
  `profile_address` varchar(400) DEFAULT NULL COMMENT 'Адрес',
  `profile_snils` char(11) DEFAULT NULL COMMENT 'СНИЛС',
  PRIMARY KEY (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile`
--

LOCK TABLES `profile` WRITE;
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reason`
--

DROP TABLE IF EXISTS `reason`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reason` (
  `reason_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `reason_text` varchar(400) NOT NULL COMMENT 'Причина неисправности',
  PRIMARY KEY (`reason_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reason`
--

LOCK TABLES `reason` WRITE;
/*!40000 ALTER TABLE `reason` DISABLE KEYS */;
/*!40000 ALTER TABLE `reason` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recoveryrecieveakt`
--

DROP TABLE IF EXISTS `recoveryrecieveakt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recoveryrecieveakt` (
  `recoveryrecieveakt_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_osmotrakt` mediumint(8) unsigned NOT NULL COMMENT 'Акт осмотра',
  `id_recoverysendakt` smallint(5) unsigned NOT NULL COMMENT 'Акт отправки на восстановление',
  `recoveryrecieveakt_result` char(255) DEFAULT NULL COMMENT 'Результат восстановления',
  `recoveryrecieveakt_repaired` tinyint(1) DEFAULT NULL COMMENT 'Подлежит восстановлению',
  `recoveryrecieveakt_date` date DEFAULT NULL COMMENT 'Дата получения',
  PRIMARY KEY (`recoveryrecieveakt_id`),
  KEY `fk_matrecovery_osmotrakt1_idx` (`id_osmotrakt`),
  KEY `fk_matrecovery_recoverysendakt1_idx` (`id_recoverysendakt`),
  CONSTRAINT `fk_matrecovery_osmotrakt1` FOREIGN KEY (`id_osmotrakt`) REFERENCES `osmotrakt` (`osmotrakt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_matrecovery_recoverysendakt1` FOREIGN KEY (`id_recoverysendakt`) REFERENCES `recoverysendakt` (`recoverysendakt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recoveryrecieveakt`
--

LOCK TABLES `recoveryrecieveakt` WRITE;
/*!40000 ALTER TABLE `recoveryrecieveakt` DISABLE KEYS */;
/*!40000 ALTER TABLE `recoveryrecieveakt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recoveryrecieveaktmat`
--

DROP TABLE IF EXISTS `recoveryrecieveaktmat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recoveryrecieveaktmat` (
  `recoveryrecieveaktmat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `recoveryrecieveaktmat_result` char(255) DEFAULT NULL COMMENT 'Результат восстановления',
  `recoveryrecieveaktmat_repaired` tinyint(1) DEFAULT NULL COMMENT 'Подлежит восстановлению',
  `recoveryrecieveaktmat_date` date DEFAULT NULL COMMENT 'Дата получения',
  `id_recoverysendakt` smallint(5) unsigned NOT NULL COMMENT 'Акт отправки на восстановление',
  `id_tr_mat_osmotr` mediumint(8) unsigned NOT NULL COMMENT 'Акт осмотра материала',
  PRIMARY KEY (`recoveryrecieveaktmat_id`),
  KEY `fk_recoveryrecieveaktmat_recoverysendakt1_idx` (`id_recoverysendakt`),
  KEY `fk_recoveryrecieveaktmat_tr_mat_osmotr1_idx` (`id_tr_mat_osmotr`),
  CONSTRAINT `fk_recoveryrecieveaktmat_recoverysendakt1` FOREIGN KEY (`id_recoverysendakt`) REFERENCES `recoverysendakt` (`recoverysendakt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_recoveryrecieveaktmat_tr_mat_osmotr1` FOREIGN KEY (`id_tr_mat_osmotr`) REFERENCES `tr_mat_osmotr` (`tr_mat_osmotr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recoveryrecieveaktmat`
--

LOCK TABLES `recoveryrecieveaktmat` WRITE;
/*!40000 ALTER TABLE `recoveryrecieveaktmat` DISABLE KEYS */;
/*!40000 ALTER TABLE `recoveryrecieveaktmat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recoverysendakt`
--

DROP TABLE IF EXISTS `recoverysendakt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recoverysendakt` (
  `recoverysendakt_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Номер акта отправки',
  `recoverysendakt_date` date NOT NULL COMMENT 'Дата отправки',
  `id_organ` smallint(5) unsigned NOT NULL COMMENT 'Организация',
  PRIMARY KEY (`recoverysendakt_id`),
  KEY `fk_recoverysendakt_organ1_idx` (`id_organ`),
  CONSTRAINT `fk_recoverysendakt_organ1` FOREIGN KEY (`id_organ`) REFERENCES `organ` (`organ_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recoverysendakt`
--

LOCK TABLES `recoverysendakt` WRITE;
/*!40000 ALTER TABLE `recoverysendakt` DISABLE KEYS */;
/*!40000 ALTER TABLE `recoverysendakt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `removeakt`
--

DROP TABLE IF EXISTS `removeakt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `removeakt` (
  `removeakt_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `removeakt_date` date NOT NULL COMMENT 'Дата снятия материала',
  `id_remover` smallint(5) unsigned NOT NULL COMMENT 'Демонтировщик',
  PRIMARY KEY (`removeakt_id`),
  KEY `fk_removeakt_employee1_idx` (`id_remover`),
  CONSTRAINT `fk_removeakt_employee1` FOREIGN KEY (`id_remover`) REFERENCES `employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `removeakt`
--

LOCK TABLES `removeakt` WRITE;
/*!40000 ALTER TABLE `removeakt` DISABLE KEYS */;
/*!40000 ALTER TABLE `removeakt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rra_docfiles`
--

DROP TABLE IF EXISTS `rra_docfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rra_docfiles` (
  `rra_docfiles_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_docfiles` mediumint(8) unsigned NOT NULL,
  `id_recoveryrecieveakt` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`rra_docfiles_id`),
  KEY `fk_rra_docfiles_docfiles1_idx` (`id_docfiles`),
  KEY `fk_rra_docfiles_recoveryrecieveakt1_idx` (`id_recoveryrecieveakt`),
  CONSTRAINT `fk_rra_docfiles_docfiles1` FOREIGN KEY (`id_docfiles`) REFERENCES `docfiles` (`docfiles_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rra_docfiles_recoveryrecieveakt1` FOREIGN KEY (`id_recoveryrecieveakt`) REFERENCES `recoveryrecieveakt` (`recoveryrecieveakt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rra_docfiles`
--

LOCK TABLES `rra_docfiles` WRITE;
/*!40000 ALTER TABLE `rra_docfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `rra_docfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rramat_docfiles`
--

DROP TABLE IF EXISTS `rramat_docfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rramat_docfiles` (
  `rramat_docfiles_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_docfiles` mediumint(8) unsigned NOT NULL,
  `id_recoveryrecieveaktmat` int(10) unsigned NOT NULL,
  PRIMARY KEY (`rramat_docfiles_id`),
  KEY `fk_rramat_docfiles_docfiles1_idx` (`id_docfiles`),
  KEY `fk_rramat_docfiles_recoveryrecieveaktmat1_idx` (`id_recoveryrecieveaktmat`),
  CONSTRAINT `fk_rramat_docfiles_docfiles1` FOREIGN KEY (`id_docfiles`) REFERENCES `docfiles` (`docfiles_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rramat_docfiles_recoveryrecieveaktmat1` FOREIGN KEY (`id_recoveryrecieveaktmat`) REFERENCES `recoveryrecieveaktmat` (`recoveryrecieveaktmat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rramat_docfiles`
--

LOCK TABLES `rramat_docfiles` WRITE;
/*!40000 ALTER TABLE `rramat_docfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `rramat_docfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schetuchet`
--

DROP TABLE IF EXISTS `schetuchet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schetuchet` (
  `schetuchet_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `schetuchet_kod` char(50) NOT NULL COMMENT '╨б╤З╨╡╤В ╤Г╤З╨╡╤В╨░',
  `schetuchet_name` char(255) NOT NULL COMMENT '╨а╨░╤Б╤И╨╕╤Д╤А╨╛╨▓╨║╨░ ╤Б╤З╨╡╤В╨░ ╤Г╤З╨╡╤В╨░',
  PRIMARY KEY (`schetuchet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schetuchet`
--

LOCK TABLES `schetuchet` WRITE;
/*!40000 ALTER TABLE `schetuchet` DISABLE KEYS */;
/*!40000 ALTER TABLE `schetuchet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spisosnovakt`
--

DROP TABLE IF EXISTS `spisosnovakt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spisosnovakt` (
  `spisosnovakt_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '╨Э╨╛╨╝╨╡╤А ╨╖╨░╤П╨▓╨║╨╕ ╨╜╨░ ╤Б╨┐╨╕╤Б╨░╨╜╨╕╤П ╨╛╤Б╨╜╨╛╨▓╨╜╤Л╤Е ╤Б╤А╨╡╨┤╤Б╤В╨▓',
  `spisosnovakt_date` date NOT NULL COMMENT '╨Ф╨░╤В╨░ ╨╖╨░╤П╨▓╨║╨╕',
  `id_schetuchet` smallint(5) unsigned NOT NULL COMMENT '╨б╤З╨╡╤В ╤Г╤З╨╡╤В╨░',
  `id_mol` smallint(5) unsigned NOT NULL COMMENT '╨Ь╨░╤В╨╡╤А╨╕╨░╨╗╤М╨╜╨╛-╨╛╤В╨▓╨╡╤В╤Б╤В╨▓╨╡╨╜╨╜╨╛╨╡ ╨╗╨╕╤Ж╨╛',
  `id_employee` smallint(5) unsigned DEFAULT NULL COMMENT '╨Ш╨╜╨╛╨╡ ╨╛╤В╨▓╨╡╤В╤Б╤В╨▓╨╡╨╜╨╜╨╛╨╡ ╨╗╨╕╤Ж╨╛',
  PRIMARY KEY (`spisosnovakt_id`),
  KEY `fk_spisosnovakt_schetuchet1_idx` (`id_schetuchet`),
  KEY `fk_spisosnovakt_employee1_idx` (`id_mol`),
  KEY `fk_spisosnovakt_employee2_idx` (`id_employee`),
  CONSTRAINT `fk_spisosnovakt_employee1` FOREIGN KEY (`id_mol`) REFERENCES `employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_spisosnovakt_employee2` FOREIGN KEY (`id_employee`) REFERENCES `employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_spisosnovakt_schetuchet1` FOREIGN KEY (`id_schetuchet`) REFERENCES `schetuchet` (`schetuchet_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spisosnovakt`
--

LOCK TABLES `spisosnovakt` WRITE;
/*!40000 ALTER TABLE `spisosnovakt` DISABLE KEYS */;
/*!40000 ALTER TABLE `spisosnovakt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spisosnovmaterials`
--

DROP TABLE IF EXISTS `spisosnovmaterials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spisosnovmaterials` (
  `spisosnovmaterials_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_mattraffic` mediumint(8) unsigned NOT NULL COMMENT '╨Ь╨░╤В╨╡╤А╨╕╨░╨╗╤М╨╜╨░╤П ╤Ж╨╡╨╜╨╜╨╛╤Б╤В╤М',
  `id_spisosnovakt` mediumint(8) unsigned NOT NULL COMMENT '╨Ч╨░╤П╨▓╨║╨░ ╤Б╨┐╨╕╤Б╨░╨╜╨╕╤П ╨╛╤Б╨╜╨╛╨▓╨╜╤Л╤Е ╤Б╤А╨╡╨┤╤Б╤В╨▓',
  `spisosnovmaterials_number` decimal(12,3) NOT NULL COMMENT '╨Ъ╨╛╨╗╨╕╤З╨╡╤Б╤В╨▓╨╛ ╨╜╨░ ╤Б╨┐╨╕╤Б╨░╨╜╨╕╨╡',
  PRIMARY KEY (`spisosnovmaterials_id`),
  KEY `fk_spisosnovmaterials_mattraffic1_idx` (`id_mattraffic`),
  KEY `fk_spisosnovmaterials_spisosnovakt1_idx` (`id_spisosnovakt`),
  CONSTRAINT `fk_spisosnovmaterials_mattraffic1` FOREIGN KEY (`id_mattraffic`) REFERENCES `mattraffic` (`mattraffic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_spisosnovmaterials_spisosnovakt1` FOREIGN KEY (`id_spisosnovakt`) REFERENCES `spisosnovakt` (`spisosnovakt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spisosnovmaterials`
--

LOCK TABLES `spisosnovmaterials` WRITE;
/*!40000 ALTER TABLE `spisosnovmaterials` DISABLE KEYS */;
/*!40000 ALTER TABLE `spisosnovmaterials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_user`
--

DROP TABLE IF EXISTS `tbl_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL COMMENT 'Логин',
  `fullname` varchar(128) NOT NULL COMMENT 'Полное имя',
  `password` varchar(256) NOT NULL COMMENT 'Введите пароль',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_user`
--

LOCK TABLES `tbl_user` WRITE;
/*!40000 ALTER TABLE `tbl_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_mat`
--

DROP TABLE IF EXISTS `tr_mat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_mat` (
  `tr_mat_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_installakt` mediumint(8) unsigned NOT NULL,
  `id_mattraffic` mediumint(8) unsigned NOT NULL,
  `id_parent` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`tr_mat_id`),
  KEY `fk_tr_mat_installakt1_idx` (`id_installakt`),
  KEY `fk_tr_mat_mattraffic1_idx` (`id_mattraffic`),
  KEY `fk_tr_mat_mattraffic2_idx` (`id_parent`),
  CONSTRAINT `fk_tr_mat_installakt1` FOREIGN KEY (`id_installakt`) REFERENCES `installakt` (`installakt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tr_mat_mattraffic1` FOREIGN KEY (`id_mattraffic`) REFERENCES `mattraffic` (`mattraffic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tr_mat_mattraffic2` FOREIGN KEY (`id_parent`) REFERENCES `mattraffic` (`mattraffic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_mat`
--

LOCK TABLES `tr_mat` WRITE;
/*!40000 ALTER TABLE `tr_mat` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_mat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_mat_osmotr`
--

DROP TABLE IF EXISTS `tr_mat_osmotr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_mat_osmotr` (
  `tr_mat_osmotr_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_tr_mat` mediumint(8) unsigned NOT NULL COMMENT 'Материал',
  `id_osmotraktmat` mediumint(8) unsigned NOT NULL COMMENT 'Акт осмотра материала',
  `tr_mat_osmotr_comment` varchar(400) DEFAULT NULL COMMENT 'Описание причины неисправности',
  `id_reason` smallint(5) unsigned DEFAULT NULL COMMENT 'Причина неисправности',
  `tr_mat_osmotr_number` decimal(12,3) unsigned NOT NULL DEFAULT '1.000' COMMENT 'Количество осмотренного материала',
  PRIMARY KEY (`tr_mat_osmotr_id`),
  KEY `fk_tr_mat_osmotr_tr_mat1_idx` (`id_tr_mat`),
  KEY `fk_tr_mat_osmotr_osmotraktmat1_idx` (`id_osmotraktmat`),
  KEY `fk_tr_mat_osmotr_reason1_idx` (`id_reason`),
  CONSTRAINT `fk_tr_mat_osmotr_osmotraktmat1` FOREIGN KEY (`id_osmotraktmat`) REFERENCES `osmotraktmat` (`osmotraktmat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tr_mat_osmotr_reason1` FOREIGN KEY (`id_reason`) REFERENCES `reason` (`reason_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tr_mat_osmotr_tr_mat1` FOREIGN KEY (`id_tr_mat`) REFERENCES `tr_mat` (`tr_mat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_mat_osmotr`
--

LOCK TABLES `tr_mat_osmotr` WRITE;
/*!40000 ALTER TABLE `tr_mat_osmotr` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_mat_osmotr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_osnov`
--

DROP TABLE IF EXISTS `tr_osnov`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_osnov` (
  `tr_osnov_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `tr_osnov_kab` char(255) NOT NULL,
  `id_installakt` mediumint(8) unsigned NOT NULL,
  `id_mattraffic` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`tr_osnov_id`),
  KEY `fk_tr_osnov_installakt1_idx` (`id_installakt`),
  KEY `fk_tr_osnov_mattraffic1_idx` (`id_mattraffic`),
  CONSTRAINT `fk_tr_osnov_installakt1` FOREIGN KEY (`id_installakt`) REFERENCES `installakt` (`installakt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tr_osnov_mattraffic1` FOREIGN KEY (`id_mattraffic`) REFERENCES `mattraffic` (`mattraffic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_osnov`
--

LOCK TABLES `tr_osnov` WRITE;
/*!40000 ALTER TABLE `tr_osnov` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_osnov` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_rm_mat`
--

DROP TABLE IF EXISTS `tr_rm_mat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_rm_mat` (
  `tr_rm_mat_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_removeakt` mediumint(8) unsigned NOT NULL COMMENT 'Акт демонтирования материальной ценности',
  `id_tr_mat` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`tr_rm_mat_id`),
  KEY `fk_tr_rm_mat_removeakt1_idx` (`id_removeakt`),
  KEY `fk_tr_rm_mat_tr_mat1_idx` (`id_tr_mat`),
  CONSTRAINT `fk_tr_rm_mat_removeakt1` FOREIGN KEY (`id_removeakt`) REFERENCES `removeakt` (`removeakt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tr_rm_mat_tr_mat1` FOREIGN KEY (`id_tr_mat`) REFERENCES `tr_mat` (`tr_mat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_rm_mat`
--

LOCK TABLES `tr_rm_mat` WRITE;
/*!40000 ALTER TABLE `tr_rm_mat` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_rm_mat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `traflog`
--

DROP TABLE IF EXISTS `traflog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `traflog` (
  `traflog_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_logreport` int(10) unsigned NOT NULL,
  `traflog_filename` char(255) NOT NULL COMMENT 'Имя файла',
  `traflog_rownum` mediumint(9) NOT NULL COMMENT 'Номер строки',
  `traflog_type` tinyint(1) NOT NULL COMMENT 'Тип сообщения',
  `traflog_message` varchar(1000) NOT NULL COMMENT 'Сообщение',
  `mattraffic_number` decimal(12,3) DEFAULT NULL COMMENT 'Количество (Задействованное в операции)',
  `id_matlog` int(10) unsigned NOT NULL,
  `id_employeelog` int(10) unsigned NOT NULL,
  PRIMARY KEY (`traflog_id`),
  KEY `fk_traflog_logreport1_idx` (`id_logreport`),
  KEY `fk_traflog_matlog1_idx` (`id_matlog`),
  KEY `fk_traflog_employeelog1_idx` (`id_employeelog`),
  CONSTRAINT `fk_traflog_employeelog1` FOREIGN KEY (`id_employeelog`) REFERENCES `employeelog` (`employeelog_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_traflog_logreport1` FOREIGN KEY (`id_logreport`) REFERENCES `logreport` (`logreport_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_traflog_matlog1` FOREIGN KEY (`id_matlog`) REFERENCES `matlog` (`matlog_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `traflog`
--

LOCK TABLES `traflog` WRITE;
/*!40000 ALTER TABLE `traflog` DISABLE KEYS */;
/*!40000 ALTER TABLE `traflog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `writeoffakt`
--

DROP TABLE IF EXISTS `writeoffakt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `writeoffakt` (
  `writeoffakt_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_mattraffic` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`writeoffakt_id`),
  KEY `fk_writeoffakt_mattraffic1_idx` (`id_mattraffic`),
  CONSTRAINT `fk_writeoffakt_mattraffic1` FOREIGN KEY (`id_mattraffic`) REFERENCES `mattraffic` (`mattraffic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `writeoffakt`
--

LOCK TABLES `writeoffakt` WRITE;
/*!40000 ALTER TABLE `writeoffakt` DISABLE KEYS */;
/*!40000 ALTER TABLE `writeoffakt` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-12-08 11:42:12
