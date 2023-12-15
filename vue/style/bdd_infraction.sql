SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  `comprend`
-- ----------------------------
DROP TABLE IF EXISTS `COMPREND`;

CREATE TABLE `COMPREND` 
(
  `id_inf` varchar(5) NOT NULL,
  `id_delit` int(3) NOT NULL,

  PRIMARY KEY (`id_inf`,`id_delit`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  `conducteur`
-- ----------------------------
DROP TABLE IF EXISTS `CONDUCTEUR`;

CREATE TABLE `CONDUCTEUR` 
(
  `num_permis` varchar(4) NOT NULL,
  `date_permis` date NOT NULL,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `mot_de_passe` varchar(100) NOT NULL,

  PRIMARY KEY (`num_permis`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  `delit`
-- ----------------------------
DROP TABLE IF EXISTS `DELIT`;

CREATE TABLE `DELIT` 
(
  `id_delit` int(11) NOT NULL AUTO_INCREMENT,
  `nature` varchar(40) NOT NULL,
  `tarif` decimal(6,2) NOT NULL DEFAULT '0.00',

  PRIMARY KEY (`id_delit`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
--  `infraction`
-- ----------------------------
DROP TABLE IF EXISTS `INFRACTION`;

CREATE TABLE `INFRACTION` 
(
  `id_inf` int(11) NOT NULL,
  `date_inf` date NOT NULL,
  `num_immat` varchar(8) NOT NULL,
  `num_permis` varchar(4) DEFAULT NULL,

  PRIMARY KEY (`id_inf`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  `vehicule`
-- ----------------------------
DROP TABLE IF EXISTS `VEHICULE`;

CREATE TABLE `VEHICULE` 
(
  `num_immat` varchar(8) NOT NULL,
  `date_immat` date NOT NULL,
  `modele` varchar(20) NOT NULL,
  `marque` varchar(20) NOT NULL,
  `num_permis` varchar(4) NOT NULL,

  PRIMARY KEY (`num_immat`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  data 
-- ----------------------------
INSERT INTO `COMPREND` VALUES ('1','1'), ('11','3'), ('11','5'), ('12','3'), ('13','2'), ('2','1'), ('2','2'), ('3','3'), ('4','4'), ('4','5'), ('5','2'), ('5','4'), ('6','1'), ('6','2'), ('6','4'), ('6','6'), ('7','1'), ('7','2'), ('7','5'), ('8','1'), ('8','2'), ('8','6'), ('85','1'), ('85','5'), ('9','3'), ('9','6');
INSERT INTO `CONDUCTEUR` VALUES ('AZ67','2011-02-01','AIRPACH','Fabrice', '$2y$10$6cgWOyCg/oBNyUbcvaPEsecloqfQLYVcv7inGEnwAW6mPbPG.rrRu'), ('AZ69','2011-02-01','CAVALLI','Frédéric', '$2y$10$Bs1fQmH39YJhX.y1rVaaU.2QwetXls3aLon3uMvx/XyfmtIMgvWoy'), ('AZ71','2017-02-02','MANGONI','Joseph', '$2y$10$muSamvzfecIj8bwFqaYCGuD.tH7lhVNzeSWIXWT4D5XiicKFcl8li'), ('AZ81','1997-04-09','GAUDE','David', '$2y$10$eHpi0Fx2S3pONqr6Zal0luQgVtEBiJrb5DlSSxfzGJK7SxD3q4EaK'), ('AZ90','2000-05-04','KIEFFER','Claudine', '$2y$10$HLxYQ2G86.eYaMleB18hn.iKtHg7vj68azHl4zXNdvIigGEq2LxoK'), ('AZ92','2001-04-06','THEOBALD','Pascal', '$2y$10$UHc41cnnlvimlUjuWjC6tuDSAxHfqrZNwIR5tC6gKlBZ/QoDuOHVG'), ('AZ99','2003-09-06','CAMARA','Souleymane', '$2y$10$YpDpEJ84XsyRtAcJyqdeGOjnRNEwgAKITTBv7rTjCFI.L26FGSD2q');
INSERT INTO `DELIT` VALUES ('1','Excès de vitesse','220.00'), ('2','Outrage à agent','450.00'), ('3','Feu rouge grillé','270.00'), ('4','Conduite en état d ivresse','380.00'), ('5','Delit de fuite','400.00'), ('6','refus de priorité','280.00');
INSERT INTO `INFRACTION` VALUES ('1','2021-09-02','CA409BG','AZ67'), ('11','2020-05-14','AA643BB',''), ('12','2021-12-02','AA643BB','AZ99'), ('13','2021-08-11','AA643BB','AZ67'), ('2','2021-09-04','BE456AD','AZ69'), ('3','2021-09-04','AA643BB','AZ71'), ('4','2021-09-06','BF823NG','AZ81'), ('5','2021-09-08','5432YZ88','AZ90'), ('6','2021-09-11','AB308FG','AZ92'), ('7','2021-09-08','AB308FG',''), ('8','2020-06-05','AA643BB','AZ67'), ('85','2021-03-18','AA643BB',''), ('9','2020-10-01','CA409BG','AZ92');
INSERT INTO `VEHICULE` VALUES ('5432YZ88','2011-08-15','C3','Citroën','AZ90'), ('AA643BB','2016-01-07','Golf','Volkswagen','AZ71'), ('AB308FG','2017-03-27','309','Peugeot','AZ92'), ('BE456AD','2018-07-10','Kangoo','Renault','AZ69'), ('BF823NG','2018-09-10','C3','Citroën','AZ81'), ('CA409BG','2019-03-21','T-Roc','Volkswagen','AZ67');
