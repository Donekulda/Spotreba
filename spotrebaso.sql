-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `data`;
CREATE TABLE `data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datum` int(10) unsigned NOT NULL,
  `PL1` double NOT NULL,
  `PL2` double DEFAULT 0,
  `PL3` double DEFAULT 0,
  `FA1` double DEFAULT 0,
  `FA2` double DEFAULT 0,
  `FA3` double DEFAULT 0,
  `SP1` double DEFAULT 0,
  `SP2` double DEFAULT 0,
  `SP3` double DEFAULT 0,
  `NT` tinyint(4) DEFAULT 0,
  `ip_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_ibfk_1` (`ip_id`),
  CONSTRAINT `data_ibfk_1` FOREIGN KEY (`ip_id`) REFERENCES `ip_adresy` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `data_mesice`;
CREATE TABLE `data_mesice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rok` smallint(5) unsigned NOT NULL,
  `mesic` tinyint(3) unsigned NOT NULL,
  `PL1SUM` double DEFAULT 0,
  `PL2SUM` double DEFAULT 0,
  `PL3SUM` double DEFAULT 0,
  `FA1SUM` double DEFAULT 0,
  `FA2SUM` double DEFAULT 0,
  `FA3SUM` double DEFAULT 0,
  `ip_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Dokumenty`;
CREATE TABLE `Dokumenty` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nazev` varchar(20) COLLATE utf8_bin NOT NULL,
  `file_name` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `Dokumenty` (`id`, `Nazev`, `file_name`) VALUES
(1,	'GDPR',	'Zásady-ochrany-osobníc-údajů.pdf'),
(2,	'Podmínky použití',	'docsPodmínky používání webových stránek.pdf'),
(3,	'Help',	'docsHelp.pdf'),
(4,	'Kontakt',	'docsKontaktní údaje na provozovatele stránek.pdf');

DROP TABLE IF EXISTS `Faze`;
CREATE TABLE `Faze` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `Nazev` varchar(20) COLLATE utf8_bin NOT NULL,
  `delic` smallint(6) NOT NULL,
  `pocet` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `Faze` (`id`, `Nazev`, `delic`, `pocet`) VALUES
(1,	'1x1',	1,	1),
(2,	'1x2',	1,	1),
(3,	'1x3',	1,	1),
(4,	'3x1',	3,	1),
(5,	'3x2',	3,	2),
(6,	'3x3',	3,	3);

DROP TABLE IF EXISTS `ip_adresy`;
CREATE TABLE `ip_adresy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Ip_Nazev` varchar(35) COLLATE utf8_czech_ci DEFAULT NULL,
  `druh` varchar(45) COLLATE utf8_czech_ci DEFAULT NULL,
  `Nazev_Faze1` varchar(45) COLLATE utf8_czech_ci DEFAULT NULL,
  `Nazev_Faze2` varchar(45) COLLATE utf8_czech_ci DEFAULT NULL,
  `Nazev_Faze3` varchar(45) COLLATE utf8_czech_ci DEFAULT NULL,
  `Vykon` float DEFAULT NULL,
  `Vykon_Faze1` float DEFAULT NULL,
  `Vykon_Faze2` float DEFAULT NULL,
  `Vykon_Faze3` float DEFAULT NULL,
  `Jednotka` tinyint(4) DEFAULT 0,
  `Jednotka_Faze1` tinyint(4) DEFAULT 0,
  `Jednotka_Faze2` tinyint(4) DEFAULT 0,
  `Jednotka_Faze3` tinyint(4) DEFAULT 0,
  `Faze_id` tinyint(4) DEFAULT NULL,
  `ip` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `port` int(11) NOT NULL,
  `wattrouter_type` tinyint(4) DEFAULT NULL,
  `typ_id` int(4) NOT NULL DEFAULT 1,
  `Deactivated` tinyint(4) DEFAULT NULL,
  `Secret_key_ip` varchar(75) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Nazev` (`Ip_Nazev`),
  KEY `typ_id` (`typ_id`),
  KEY `Jednotka_Jednotka_Faze1_Jednotka_Faze2_Jednotka_Faze3` (`Jednotka`,`Jednotka_Faze1`,`Jednotka_Faze2`,`Jednotka_Faze3`),
  KEY `Jednotka_Faze1` (`Jednotka_Faze1`),
  KEY `Jednotka_Faze3` (`Jednotka_Faze3`),
  KEY `Jednotka_Faze2` (`Jednotka_Faze2`),
  CONSTRAINT `ip_adresy_ibfk_4` FOREIGN KEY (`typ_id`) REFERENCES `typy` (`id`),
  CONSTRAINT `ip_adresy_ibfk_5` FOREIGN KEY (`Jednotka`) REFERENCES `jednotky` (`mocnina`),
  CONSTRAINT `ip_adresy_ibfk_6` FOREIGN KEY (`Jednotka_Faze1`) REFERENCES `jednotky` (`mocnina`),
  CONSTRAINT `ip_adresy_ibfk_7` FOREIGN KEY (`Jednotka_Faze3`) REFERENCES `jednotky` (`mocnina`),
  CONSTRAINT `ip_adresy_ibfk_8` FOREIGN KEY (`Jednotka_Faze2`) REFERENCES `jednotky` (`mocnina`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `ip_adresy` (`id`, `Ip_Nazev`, `druh`, `Nazev_Faze1`, `Nazev_Faze2`, `Nazev_Faze3`, `Vykon`, `Vykon_Faze1`, `Vykon_Faze2`, `Vykon_Faze3`, `Jednotka`, `Jednotka_Faze1`, `Jednotka_Faze2`, `Jednotka_Faze3`, `Faze_id`, `ip`, `port`, `wattrouter_type`, `typ_id`, `Deactivated`, `Secret_key_ip`) VALUES
(1,	'Špitálka',	'2xGoodwe DS4600, 1x Sunway AT3600',	'Chinaland 250',	'NexPower 140',	'Evergreen 180',	15,	NULL,	NULL,	NULL,	3,	0,	0,	0,	3,	'192.168.17.105',	8080,	1,	1,	0,	'gstH0MNz0JMUXnkGlX29pzju21nbxW9We2lj@gHuVD9GBaVxK@AGhPn1OaVnejnsmW@PBdnut');

DROP TABLE IF EXISTS `jednotky`;
CREATE TABLE `jednotky` (
  `mocnina` tinyint(4) NOT NULL,
  `Jednotka_nazev` varchar(4) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`mocnina`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `jednotky` (`mocnina`, `Jednotka_nazev`) VALUES
(0,	'Wp'),
(3,	'kWp');

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `Log_Nazev` int(11) NOT NULL,
  `uzivatelId` int(25) NOT NULL,
  `ovlivnenyId` int(11) DEFAULT NULL,
  `opravneni` int(11) NOT NULL,
  `druh_logu` int(11) NOT NULL,
  `cas` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `ovlivnenyId` (`ovlivnenyId`),
  KEY `uzivatelId` (`uzivatelId`),
  KEY `druh-logu` (`druh_logu`),
  KEY `uzivatelId_2` (`uzivatelId`),
  CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`uzivatelId`) REFERENCES `uzivatele` (`id`),
  CONSTRAINT `logs_ibfk_2` FOREIGN KEY (`ovlivnenyId`) REFERENCES `uzivatele` (`id`),
  CONSTRAINT `logs_ibfk_3` FOREIGN KEY (`druh_logu`) REFERENCES `predlohy` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `month_update`;
CREATE TABLE `month_update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `active` tinyint(1) NOT NULL,
  `uzivatel_id` int(11) NOT NULL,
  `ip_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `Opravneni`;
CREATE TABLE `Opravneni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Opravneni_Nazev` varchar(25) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `Opravneni` (`id`, `Opravneni_Nazev`) VALUES
(0,	'Guest'),
(1,	'Uzivatel'),
(2,	'Admin'),
(3,	'Hl.Admin');

DROP TABLE IF EXISTS `predlohy`;
CREATE TABLE `predlohy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nazev` varchar(255) COLLATE utf8_bin NOT NULL,
  `Obsah` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `predlohy` (`id`, `Nazev`, `Obsah`) VALUES
(1,	'Registrace do systému monitorování spotřeby {%nick} - {%jmeno, %prijmeni}',	'<h1><strong>Přihla&scaron;ovac&iacute; &uacute;daje do syst&eacute;mu monitoringu Wattroutru</strong></h1>\r\n\r\n<p>Byl založen nov&yacute; uživatel se jm&eacute;nem <strong>%jmeno %prijmeni</strong>.<br />\r\nToto je automatizovan&yacute; mail. Pros&iacute;me v&aacute;s o neodpov&iacute;d&aacute;n&iacute; na tento mail děkujeme.</p>\r\n\r\n<hr />\r\n<p><strong>str&aacute;nka</strong>: <a href=\"http://spotreba.solarnivyroba.cz\" target=\"_blank\">http://spotreba.solarnivyroba.cz</a></p>\r\n\r\n<p><strong>uživatel: %nick</strong><br />\r\n<strong>heslo: %heslo</strong></p>\r\n\r\n<hr />\r\n<p>Heslo můžete změnit v menu, kter&eacute; se rozbal&iacute; po kliknut&iacute; na jm&eacute;no uživatele&nbsp;<strong>%jmeno %prijmeni</strong> - <em>Upravit Profil</em>.</p>\r\n\r\n<p>S pozdravem</p>\r\n\r\n<p>Automatizovan&yacute; e-mailov&yacute; syst&eacute;m</p>\r\n\r\n<p>Aeko s.r.o.</br>\r\n&Scaron;pit&aacute;lka 461/21a, 602 00 Brno</br>\r\nemail&nbsp;&nbsp; info@aeko.cz</br>\r\ntel. +420 539 03 03 03</p>\r\n'),
(2,	'@log Smazán účet {%nick, %jmeno, %prijmeni,%nazev, %mazatel, %jmenoMaz, %prijmeniMaz}',	'<p>&Uacute;čet uživatele  <strong>%</strong><strong>nick</strong> (&nbsp;%<strong>jmeno </strong>%<strong>prijmeni </strong>-&gt;<strong> </strong>%<strong>nazev</strong>) byl smaz&aacute;n uživatelem %<strong>nick</strong> (<strong>%jmenoMaz</strong> <strong>%prijmeniMaz</strong>).</p>\r\n'),
(3,	'Zapomenuté heslo 1.cast {%url, %jmeno, %prijmeni, %nick}',	'<p>Zdrav&iacute;m <strong>%jmeno %prijmeni</strong>,<br />\r\ntoto je automatizovan&yacute; email, ž&aacute;d&aacute;me v&aacute;s abyste na něj neodpov&iacute;dali děkujeme.</p>\r\n\r\n<hr />\r\n<p>Zde je odkaz pro zresetov&aacute;n&iacute; va&scaron;eho hesla: %url<br />\r\nVa&scaron;e přihla&scaron;ovac&iacute; jm&eacute;no je: <strong>%nick</strong></p>\r\n\r\n<hr />\r\n<p>Po kliknut&iacute; odkazu v&aacute;m bude na email odesl&aacute;no nov&eacute; přihla&scaron;ovac&iacute; heslo, <strong>doporučujeme v&aacute;m si ho změnit</strong>.</p>\r\n\r\n<p>Přeji v&aacute;m hezk&yacute; den. S pozdravem automatizovan&yacute; e-mailov&yacute; syst&eacute;m společnosti Aeko s.r.o.</p>\r\n'),
(4,	'Zapomenuté heslo 2.cast {%heslo, %jmeno, %prijmeni, %nick}',	'<p>Zdrav&iacute;m <strong>%jmeno %prijmeni</strong>,<br />\r\ntoto je automatizovan&yacute; email, ž&aacute;d&aacute;me v&aacute;s abyste na něj neodpov&iacute;dali děkujeme.</p>\r\n\r\n<hr />\r\n<p>Va&scaron;e nově vygenerovann&eacute; heslo: <strong>%heslo</strong><br />\r\nVa&scaron;e přihla&scaron;ovac&iacute; jm&eacute;no je: <strong>%nick</strong></p>\r\n\r\n<hr />\r\n<p>Přeji v&aacute;m hezk&yacute; den. S pozdravem automatizovan&yacute; e-mailov&yacute; syst&eacute;m společnosti Aeko s.r.o.</p>\r\n'),
(5,	'@log Upravené informace {[puvodni data] [nová data]}',	'<p>Data u &uacute;čtu<strong> </strong>%<strong>nick (</strong>%<strong>jmeno </strong>%<strong>prijmeni) </strong>byla pozměněna:</p>\r\n\r\n<hr />\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Původn&iacute;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pozměněn&eacute;</strong></p>\r\n\r\n<p>Nick: $nick&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %nick2</p>\r\n\r\n<p>Email: $email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %email2</p>\r\n\r\n<p>Telefon: $tel &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %tel2</p>\r\n\r\n<p>Jm&eacute;no: $jmeno &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %jmeno2</p>\r\n\r\n<p>Př&iacute;jmen&iacute;: $prijmeni &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %prijmeni2</p>\r\n\r\n<p>Adresa: $adresa &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %adresa2</p>\r\n\r\n<p>Město: $mesto &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %mesto2</p>\r\n\r\n<p>Druh zař&iacute;zen&iacute;: $druh &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %druh2</p>\r\n\r\n<p>N&aacute;zev zař&iacute;zen&iacute;: $nazev &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%nazev2</p>\r\n\r\n<p>url: $url &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %url2</p>\r\n\r\n<p>V&yacute;kon: $vykon &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %vykon2</p>\r\n\r\n<p>Opr&aacute;vněn&iacute;: $opravneni &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %opravneni2</p>\r\n\r\n<p>&nbsp;</p>\r\n'),
(6,	'Error-databaze {%cas, %error}',	'<h1><strong>Nastala chyba</strong></h1>\r\n\r\n<p><strong>V datab&aacute;zi n&aacute;m nastala chyba v %cas !</strong></p>\r\n\r\n<p><strong>error: </strong>%error</p>\r\n'),
(7,	'Admin->Vytvoření uživatele {%nick, %heslo, %jmeno, %prijmeni}',	'<p>Ahoj,<br />\r\nuživatel: %nick<br />\r\ns jm&eacute;nem: %jmeno %prijmeni<br />\r\nbyl vytvořen s heslem: %heslo</p>\r\n\r\n<p>S pozdravem,<br />\r\nAutomatizovan&yacute; syst&eacute;m služby Spotřeba-&gt;Sol&aacute;rn&iacute; v&yacute;roba</p>\r\n'),
(8,	'Test->Zkoužka fungování mailu',	'<p>Ahoj,</p>\r\n\r\n<p>toto je testovac&iacute; mail.</p>\r\n\r\n<p>Sbohem.</p>\r\n');

DROP TABLE IF EXISTS `server_check`;
CREATE TABLE `server_check` (
  `cas` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `errors` int(11) unsigned DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `typy`;
CREATE TABLE `typy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Typ_název` varchar(25) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `typy` (`id`, `Typ_název`) VALUES
(1,	'Wattmeter'),
(2,	'SolarLog');

DROP TABLE IF EXISTS `uzivatele`;
CREATE TABLE `uzivatele` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(25) COLLATE utf8_bin NOT NULL,
  `heslo` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(50) COLLATE utf8_bin NOT NULL,
  `telefon` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `Jmeno` varchar(25) COLLATE utf8_bin NOT NULL,
  `Prijmeni` varchar(25) COLLATE utf8_bin NOT NULL,
  `Adresa` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `PSC` int(11) DEFAULT NULL,
  `Mesto` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `ip_id` int(11) DEFAULT 1,
  `opravneni` int(11) NOT NULL,
  `img` int(11) DEFAULT 0,
  `Secret_key` varchar(80) COLLATE utf8_bin NOT NULL,
  `recovery_kod` varchar(55) COLLATE utf8_bin DEFAULT NULL,
  `Vytvoren` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`),
  UNIQUE KEY `email` (`email`),
  KEY `opravneni` (`opravneni`),
  KEY `ip` (`ip_id`),
  CONSTRAINT `uzivatele_ibfk_1` FOREIGN KEY (`opravneni`) REFERENCES `Opravneni` (`id`),
  CONSTRAINT `uzivatele_ibfk_2` FOREIGN KEY (`ip_id`) REFERENCES `ip_adresy` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `uzivatele` (`id`, `nick`, `heslo`, `email`, `telefon`, `Jmeno`, `Prijmeni`, `Adresa`, `PSC`, `Mesto`, `ip_id`, `opravneni`, `img`, `Secret_key`, `recovery_kod`, `Vytvoren`) VALUES
(0,	'Admin',	'$2y$10$lXF.1e1kibLnysMJ1dNc4.dcHKJicqK6U6bkYW4ZQRU.AsHlISj2O',	'admin@email.cz',	'+420 608 000 000',	'Admin',	'Admin',	'Bydliště',	0,	'Brno',	1,	3,	0,	'adakgfajkcnknvjnjnsjk1564ad5vnnjoiojioajnjIHSJfhjkHFSjhjfb545',	'MMt@NlTVGFNmhmuTPa1v7ks4H',	'2020-04-09 12:48:32'),
(1,	'Demo',	'$2y$10$fyEvXg9whbhKmEPuTXRDfeYDxZ9mMMSFKEqD9fykaFdMZUkB.o3MO',	'info@email.cz',	'',	'Demo',	'Demo',	'',	0,	'',	1,	0,	0,	'jN2oNmG4ROzq0vEf7k65XdnHt5oZYypmFL!JuAJQwHb4TPa9pMj2SYPLtFi1g5snP!mPyPcpi',	NULL,	'2020-02-27 12:00:54');
DROP TABLE IF EXISTS `wattrouter_types`;
CREATE TABLE `wattrouter_types` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Nazev` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `PL1` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  `PL2` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  `PL3` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  `FA1` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  `FA2` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  `FA3` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  `ILT` varchar(10) COLLATE utf8_czech_ci NOT NULL COMMENT 'NT',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `wattrouter_types` (`id`, `Nazev`, `PL1`, `PL2`, `PL3`, `FA1`, `FA2`, `FA3`, `ILT`) VALUES
(1,	'Wattrouter M',	'PL1',	'PL2',	'PL3',	'FA1',	'FA2',	'FA3',	'ILT'),
(2,	'Wattrouter MX',	'I1[P]',	'I2[P]',	'I3[P]',	'I4[P]',	'I5[P]',	'I6[P]',	'ILT');

DROP TABLE IF EXISTS `_data`;
CREATE TABLE `_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datum` int(10) unsigned NOT NULL,
  `PL1` double NOT NULL,
  `PL2` double DEFAULT NULL,
  `PL3` double DEFAULT NULL,
  `FA1` double NOT NULL,
  `FA2` double DEFAULT NULL,
  `FA3` double DEFAULT NULL,
  `SP1` double DEFAULT NULL,
  `SP2` double DEFAULT NULL,
  `SP3` double DEFAULT NULL,
  `NT` tinyint(4) DEFAULT NULL,
  `IP` varchar(25) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- 2020-04-09 12:51:21