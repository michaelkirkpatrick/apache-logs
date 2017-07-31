# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
# ************************************************************


# Dump of table access_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `access_log`;

CREATE TABLE `access_log` (
  `id` varchar(36) NOT NULL DEFAULT '',
  `ipAddress` varchar(45) DEFAULT NULL,
  `ipv4` tinyint(1) DEFAULT NULL,
  `ipv6` tinyint(1) DEFAULT NULL,
  `rfc1413` varchar(255) DEFAULT NULL,
  `httpAuthUser` varchar(255) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `method` varchar(7) DEFAULT NULL,
  `resource` text,
  `protocol` varchar(100) DEFAULT NULL,
  `httpStatus` smallint(3) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `referer` text,
  `userAgent` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;