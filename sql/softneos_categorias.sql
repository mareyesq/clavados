DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `cod_categoria` char(2) NOT NULL,
  `categoria` varchar(30) NOT NULL,
  `edad_desde` tinyint(4) DEFAULT NULL,
  `edad_hasta` tinyint(4) DEFAULT NULL,
  `verifica_edad` tinyint(1) DEFAULT NULL,
  `tipo_categoria` char(1) DEFAULT NULL,
  `individual` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`cod_categoria`),
  UNIQUE KEY `cod_categoria` (`cod_categoria`),
  UNIQUE KEY `descripcion` (`categoria`),
  UNIQUE KEY `categoria` (`categoria`),
  UNIQUE KEY `categoria_2` (`categoria`),
  FULLTEXT KEY `descripcion_2` (`categoria`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` (`cod_categoria`, `categoria`, `edad_desde`, `edad_hasta`, `verifica_edad`, `tipo_categoria`, `individual`) VALUES ('10','10 años',10,10,1,'N',1),('11','11 años',11,11,1,'N',1),('12','12 años',12,12,1,'N',1),('13','13 años',13,13,1,'N',1),('AB','Abierta',14,99,0,'F',1),('DE','Desarrollo',NULL,NULL,0,'L',1),('GA','Grupo A',16,18,1,'F',1),('GB','Grupo B',14,15,1,'F',1),('GC','Grupo C',12,13,1,'F',1),('GD','Grupo D',10,11,1,'F',1),('MY','Mayores',19,99,1,'N',1),('NA','Novatos A',9,9,1,'N',1),('NB','Novatos B',8,8,1,'N',1),('NC','Novatos C',0,7,1,'N',1),('NV','Novatos General',0,10,1,'L',1),('PD','PreDesarrollo',NULL,NULL,0,'L',1),('SA','Sincronizado Abierto',14,99,0,'F',0),('SI','Sincronizado Infantil',10,14,1,'F',0),('SJ','Sincronizado Juvenil',14,18,1,'F',0);
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

