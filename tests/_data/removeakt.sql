INSERT INTO `matvid` VALUES (851,'ШКАФ'),(853,'ВЕДРО'),(854,'ШВАБРА');
INSERT INTO `podraz` VALUES (40,'ТЕРАПЕВТИЧЕСКОЕ'),(41,'АУП');
INSERT INTO `schetuchet` VALUES (1,'101.34','НОВЫЙ СЧЕТ');
INSERT INTO `izmer` VALUES (1,'шт', '796');
INSERT INTO `build` VALUES (8,'ПОЛИКЛИНИКА 1'),(9,'ПОЛИКЛИНИКА 2');
INSERT INTO `dolzh` VALUES (165,'ТЕРАПЕВТ'),(166,'ПРОГРАММИСТ'),(167,'НЕВРОЛОГ');
INSERT INTO `auth_user` VALUES (1196,'ИВАНОВ ИВАН ИВАНОВИЧ','IvanovII','$2y$13$H.bwEoPlfWDVZUCSn0vOju8Ejp0lgw78UG7KvgOoKfZki3m/GLM5S'),(1197,'ПЕТРОВ ПЕТР ПЕТРОВИЧ','PetrovPP','$2y$13$7Tzlr290.eomuM7XeG8utuzDSsiFnGAbhWXJ.WFiW07yrR23Lw6uK'),(1198,'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ','FedotovFF','$2y$13$wj1bw.JqvF45QxsMYtHSbu3QaRWMlOuzL1P.WMw/uBkeHxCYULwTa'),(1199,'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ','SidorovEA','$2y$13$XN0D.IjamZeTLdCGMqSkvegKi.Fhz1oQkMXATsYKEo8BnNElBScxW');
INSERT INTO `employee` VALUES (1175,165,40,8,1196,'admin','2016-11-17 13:33:17',NULL,NULL,1),(1176,166,41,8,1197,'admin','2016-11-22 08:37:08',NULL,NULL,1),(1177,165,40,NULL,1198,'admin','2016-11-22 09:24:59',NULL,NULL,1),(1178,167,40,9,1199,'admin','2016-11-22 13:31:59',NULL,NULL,1);
INSERT INTO `material` VALUES (34,'Шкаф для инвентаря','Шкаф для инвентаря',NULL,'0001','',NULL,1.000,1.00,1,0,851,1,'admin','2016-12-08 09:56:31',1,1,''),(35,'Ведро пластиковое','Ведро пластиковое',NULL,'0002','',NULL,1.000,1.00,2,0,853,1,'admin','2016-12-08 09:57:49',1,1,''),(36,'Швабра деревянная','Швабра деревянная',NULL,'0003','',NULL,1.000,1.00,2,0,854,1,'admin','2016-12-08 09:58:54',1,1,'');
INSERT INTO `mattraffic` VALUES (1,'2016-12-08',1.000,34,1175,'admin','2016-12-08 14:56:38',1,NULL),(2,'2016-12-08',1.000,35,1176,'admin','2016-12-08 14:57:49',1,NULL),(3,'2016-12-08',1.000,36,1177,'admin','2016-12-08 14:58:54',1,NULL),(4,'2016-12-08',1.000,34,1175,'admin','2016-12-08 14:59:39',3,NULL),(5,'2016-12-08',1.000,36,1177,'admin','2016-12-08 15:00:13',4,NULL),(6,'2016-12-08',1.000,35,1176,'admin','2016-12-08 15:00:26',4,NULL);
INSERT INTO `installakt` VALUES (1,'2016-12-08',1178);
INSERT INTO `tr_osnov` VALUES (1,'101',1,4);
INSERT INTO `tr_mat` VALUES (1,1,5,4),(2,1,6,4);
ALTER TABLE `removeakt` AUTO_INCREMENT = 1;