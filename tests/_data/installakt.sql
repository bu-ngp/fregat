INSERT INTO `matvid` VALUES (851,'ШКАФ'),(852,'СТОЛ'),(853,'КАРТРИДЖ');
INSERT INTO `podraz` VALUES (40,'ТЕРАПЕВТИЧЕСКОЕ'),(41,'АУП');
INSERT INTO `schetuchet` VALUES (1,'101.34','НОВЫЙ СЧЕТ');
INSERT INTO `izmer` VALUES (1,'шт', '796');
INSERT INTO `build` VALUES (8,'ПОЛИКЛИНИКА 1'),(9,'ПОЛИКЛИНИКА 2');
INSERT INTO `dolzh` VALUES (165,'ТЕРАПЕВТ'),(166,'ПРОГРАММИСТ'),(167,'НЕВРОЛОГ');
INSERT INTO `auth_user` VALUES (1196,'ИВАНОВ ИВАН ИВАНОВИЧ','IvanovII','$2y$13$H.bwEoPlfWDVZUCSn0vOju8Ejp0lgw78UG7KvgOoKfZki3m/GLM5S'),(1197,'ПЕТРОВ ПЕТР ПЕТРОВИЧ','PetrovPP','$2y$13$7Tzlr290.eomuM7XeG8utuzDSsiFnGAbhWXJ.WFiW07yrR23Lw6uK'),(1198,'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ','FedotovFF','$2y$13$wj1bw.JqvF45QxsMYtHSbu3QaRWMlOuzL1P.WMw/uBkeHxCYULwTa'),(1199,'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ','SidorovEA','$2y$13$XN0D.IjamZeTLdCGMqSkvegKi.Fhz1oQkMXATsYKEo8BnNElBScxW');
INSERT INTO `employee` VALUES (1175,165,40,8,1196,'admin','2016-11-17 13:33:17',NULL,NULL,1),(1176,166,41,8,1197,'admin','2016-11-22 08:37:08',NULL,NULL,1),(1177,165,40,NULL,1198,'admin','2016-11-22 09:24:59',NULL,NULL,1),(1178,167,40,9,1199,'admin','2016-11-22 13:31:59',NULL,NULL,1);
INSERT INTO `material` VALUES (34,'Шкаф для одежды','Шкаф для одежды',NULL,'1000001','ABCD123','2005-01-01',1.000,1200.15,1,0,851,1,'admin','2016-11-21 22:57:39',1,1,NULL),(35,'Кухонный стол','Кухонный стол',NULL,'1000002','','2010-05-01',1.000,15000.00,1,0,852,1,'admin','2016-11-21 22:59:37',1,1,NULL),(36,'Шкаф для медикаментов','Шкаф для медикаментов',NULL,'1000003','',NULL,1.000,5000.00,1,0,851,1,'admin','2016-11-21 23:27:38',1,1,NULL),(37,'Картридж А12','Картридж А12',NULL,'1000004','',NULL,5.000,900.00,2,0,853,1,'admin','2016-11-22 08:32:36',1,1,NULL),(38,'Картридж 36A','Картридж 36A',NULL,'1000005','',NULL,4.000,1500.00,2,0,853,1,'admin','2016-11-22 08:32:20',1,1,NULL);
INSERT INTO `mattraffic` VALUES (1,'2016-11-22',1.000,34,1175,'admin','2016-11-22 08:57:48',1,NULL),(2,'2016-11-22',1.000,35,1176,'admin','2016-11-22 08:59:45',1,NULL),(3,'2016-11-22',1.000,36,1177,'admin','2016-11-22 09:27:38',1,NULL),(4,'2016-11-22',5.000,37,1175,'admin','2016-11-22 13:32:36',1,NULL),(5,'2016-11-22',4.000,38,1178,'admin','2016-11-22 13:32:25',1,NULL);