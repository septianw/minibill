-- MySQL dump 10.10
--
-- Host: localhost    Database: minibill_dev
-- ------------------------------------------------------
-- Server version	5.0.22
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `mod_shipping`
--

DROP TABLE IF EXISTS `mod_shipping`;
CREATE TABLE `mod_shipping` (
  `uniq_id` char(64) NOT NULL default '',
  `user_id` int(10) unsigned NOT NULL default '0',
  `firstname` char(255) NOT NULL default '',
  `lastname` char(255) NOT NULL default '',
  `address1` char(255) NOT NULL default '',
  `address2` char(255) NOT NULL default '',
  `city` char(255) NOT NULL default '',
  `state` char(255) NOT NULL default '',
  `zipcode` char(255) NOT NULL default '',
  `country` char(255) NOT NULL default '',
  `phone` char(255) NOT NULL default '',
  `fax` char(255) NOT NULL default '',
  `cell` char(255) NOT NULL default '',
  PRIMARY KEY  (`uniq_id`),
  KEY `uniq_id` (`uniq_id`)
);
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- MySQL dump 10.10
--
-- Host: localhost    Database: minibill_dev
-- ------------------------------------------------------
-- Server version	5.0.22
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `mod_shipping_data`
--

DROP TABLE IF EXISTS `mod_shipping_data`;
CREATE TABLE `mod_shipping_data` (
  `ship_id` int(10) unsigned NOT NULL,
  `uniq_id` char(20) NOT NULL default '',
  `user_id` int(10) unsigned NOT NULL default '0',
  `shipper` char(255) NOT NULL default '',
  `tracking_no` char(255) NOT NULL default '',
  `packaged` datetime NOT NULL default '0000-00-00 00:00:00',
  `shipped` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ship_id`),
  UNIQUE KEY `tracking_no` (`tracking_no`),
  KEY `uniq_id` (`uniq_id`)
);
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- MySQL dump 10.9
--
-- Host: localhost    Database: minibill_dev
-- ------------------------------------------------------
-- Server version	4.1.20

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `mod_shipping`
--

DROP TABLE IF EXISTS `mod_shipping`;
CREATE TABLE `mod_shipping` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `firstname` char(255) NOT NULL default '',
  `lastname` char(255) NOT NULL default '',
  `address1` char(255) NOT NULL default '',
  `address2` char(255) NOT NULL default '',
  `city` char(255) NOT NULL default '',
  `state` char(255) NOT NULL default '',
  `zipcode` char(255) NOT NULL default '',
  `country` char(255) NOT NULL default '',
  `phone` char(255) NOT NULL default '',
  `fax` char(255) NOT NULL default '',
  `cell` char(255) NOT NULL default '',
  `uniq_id` char(64) NOT NULL default '',
  PRIMARY KEY  (`uniq_id`),
  KEY `uniq_id` (`uniq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- MySQL dump 10.9
--
-- Host: localhost    Database: minibill_dev
-- ------------------------------------------------------
-- Server version	4.1.20

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `mod_shipping_data`
--

DROP TABLE IF EXISTS `mod_shipping_data`;
CREATE TABLE `mod_shipping_data` (
  `ship_id` int(10) unsigned NOT NULL auto_increment,
  `uniq_id` char(20) NOT NULL default '',
  `user_id` int(10) unsigned NOT NULL default '0',
  `shipper` char(255) NOT NULL default '',
  `tracking_no` char(255) NOT NULL default '',
  `packaged` datetime NOT NULL default '0000-00-00 00:00:00',
  `shipped` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ship_id`),
  KEY `uniq_id` (`uniq_id`),
  KEY `tracking_no` (`tracking_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- MySQL dump 10.9
--
-- Host: localhost    Database: minibill_dev
-- ------------------------------------------------------
-- Server version	4.1.20

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `mod_shipping`
--

DROP TABLE IF EXISTS `mod_shipping`;
CREATE TABLE `mod_shipping` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `firstname` char(255) NOT NULL default '',
  `lastname` char(255) NOT NULL default '',
  `address1` char(255) NOT NULL default '',
  `address2` char(255) NOT NULL default '',
  `city` char(255) NOT NULL default '',
  `state` char(255) NOT NULL default '',
  `zipcode` char(255) NOT NULL default '',
  `country` char(255) NOT NULL default '',
  `phone` char(255) NOT NULL default '',
  `fax` char(255) NOT NULL default '',
  `cell` char(255) NOT NULL default '',
  `uniq_id` char(64) NOT NULL default '',
  PRIMARY KEY  (`uniq_id`),
  KEY `uniq_id` (`uniq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- MySQL dump 10.9
--
-- Host: localhost    Database: minibill_dev
-- ------------------------------------------------------
-- Server version	4.1.20

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `mod_shipping_data`
--

DROP TABLE IF EXISTS `mod_shipping_data`;
CREATE TABLE `mod_shipping_data` (
  `ship_id` int(10) unsigned NOT NULL auto_increment,
  `uniq_id` char(20) NOT NULL default '',
  `user_id` int(10) unsigned NOT NULL default '0',
  `shipper` char(255) NOT NULL default '',
  `tracking_no` char(255) NOT NULL default '',
  `packaged` datetime NOT NULL default '0000-00-00 00:00:00',
  `shipped` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ship_id`),
  KEY `uniq_id` (`uniq_id`),
  KEY `tracking_no` (`tracking_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- MySQL dump 10.10
--
-- Host: localhost    Database: minibill_dev
-- ------------------------------------------------------
-- Server version	5.0.27-log

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
-- Table structure for table `mod_shipping`
--

DROP TABLE IF EXISTS `mod_shipping`;
CREATE TABLE `mod_shipping` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `firstname` char(255) NOT NULL default '',
  `lastname` char(255) NOT NULL default '',
  `address1` char(255) NOT NULL default '',
  `address2` char(255) NOT NULL default '',
  `city` char(255) NOT NULL default '',
  `state` char(255) NOT NULL default '',
  `zipcode` char(255) NOT NULL default '',
  `country` char(255) NOT NULL default '',
  `phone` char(255) NOT NULL default '',
  `fax` char(255) NOT NULL default '',
  `cell` char(255) NOT NULL default '',
  `uniq_id` char(64) NOT NULL default '',
  PRIMARY KEY  (`uniq_id`),
  KEY `uniq_id` (`uniq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-10-04  4:34:23
-- MySQL dump 10.10
--
-- Host: localhost    Database: minibill_dev
-- ------------------------------------------------------
-- Server version	5.0.27-log

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
-- Table structure for table `mod_shipping_data`
--

DROP TABLE IF EXISTS `mod_shipping_data`;
CREATE TABLE `mod_shipping_data` (
  `ship_id` int(10) unsigned NOT NULL auto_increment,
  `uniq_id` char(20) NOT NULL default '',
  `user_id` int(10) unsigned NOT NULL default '0',
  `shipper` char(255) NOT NULL default '',
  `tracking_no` char(255) NOT NULL default '',
  `packaged` datetime NOT NULL default '0000-00-00 00:00:00',
  `shipped` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ship_id`),
  KEY `uniq_id` (`uniq_id`),
  KEY `tracking_no` (`tracking_no`)
) ENGINE=MyISAM AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-10-04  4:34:23
-- MySQL dump 10.10
--
-- Host: localhost    Database: minibill_dev
-- ------------------------------------------------------
-- Server version	5.0.27-log

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
-- Table structure for table `mod_shipping`
--

DROP TABLE IF EXISTS `mod_shipping`;
CREATE TABLE `mod_shipping` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `firstname` char(255) NOT NULL default '',
  `lastname` char(255) NOT NULL default '',
  `address1` char(255) NOT NULL default '',
  `address2` char(255) NOT NULL default '',
  `city` char(255) NOT NULL default '',
  `state` char(255) NOT NULL default '',
  `zipcode` char(255) NOT NULL default '',
  `country` char(255) NOT NULL default '',
  `phone` char(255) NOT NULL default '',
  `fax` char(255) NOT NULL default '',
  `cell` char(255) NOT NULL default '',
  `uniq_id` char(64) NOT NULL default '',
  PRIMARY KEY  (`uniq_id`),
  KEY `uniq_id` (`uniq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-10-04  4:36:01
-- MySQL dump 10.10
--
-- Host: localhost    Database: minibill_dev
-- ------------------------------------------------------
-- Server version	5.0.27-log

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
-- Table structure for table `mod_shipping_data`
--

DROP TABLE IF EXISTS `mod_shipping_data`;
CREATE TABLE `mod_shipping_data` (
  `ship_id` int(10) unsigned NOT NULL auto_increment,
  `uniq_id` char(20) NOT NULL default '',
  `user_id` int(10) unsigned NOT NULL default '0',
  `shipper` char(255) NOT NULL default '',
  `tracking_no` char(255) NOT NULL default '',
  `packaged` datetime NOT NULL default '0000-00-00 00:00:00',
  `shipped` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ship_id`),
  KEY `uniq_id` (`uniq_id`),
  KEY `tracking_no` (`tracking_no`)
) ENGINE=MyISAM AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-10-04  4:36:01
-- MySQL dump 10.10
--
-- Host: localhost    Database: minibill_dev
-- ------------------------------------------------------
-- Server version	5.0.27-log

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
-- Table structure for table `mod_shipping`
--

DROP TABLE IF EXISTS `mod_shipping`;
CREATE TABLE `mod_shipping` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `firstname` char(255) NOT NULL default '',
  `lastname` char(255) NOT NULL default '',
  `address1` char(255) NOT NULL default '',
  `address2` char(255) NOT NULL default '',
  `city` char(255) NOT NULL default '',
  `state` char(255) NOT NULL default '',
  `zipcode` char(255) NOT NULL default '',
  `country` char(255) NOT NULL default '',
  `phone` char(255) NOT NULL default '',
  `fax` char(255) NOT NULL default '',
  `cell` char(255) NOT NULL default '',
  `uniq_id` char(64) NOT NULL default '',
  PRIMARY KEY  (`uniq_id`),
  KEY `uniq_id` (`uniq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-10-10  4:58:50
-- MySQL dump 10.10
--
-- Host: localhost    Database: minibill_dev
-- ------------------------------------------------------
-- Server version	5.0.27-log

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
-- Table structure for table `mod_shipping_data`
--

DROP TABLE IF EXISTS `mod_shipping_data`;
CREATE TABLE `mod_shipping_data` (
  `ship_id` int(10) unsigned NOT NULL auto_increment,
  `uniq_id` char(20) NOT NULL default '',
  `user_id` int(10) unsigned NOT NULL default '0',
  `shipper` char(255) NOT NULL default '',
  `tracking_no` char(255) NOT NULL default '',
  `packaged` datetime NOT NULL default '0000-00-00 00:00:00',
  `shipped` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ship_id`),
  KEY `uniq_id` (`uniq_id`),
  KEY `tracking_no` (`tracking_no`)
) ENGINE=MyISAM AUTO_INCREMENT=118 DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-10-10  4:58:50
-- MySQL dump 10.10
--
-- Host: localhost    Database: minibill_dev
-- ------------------------------------------------------
-- Server version	5.0.27-log

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
-- Table structure for table `mod_shipping`
--

DROP TABLE IF EXISTS `mod_shipping`;
CREATE TABLE `mod_shipping` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `firstname` char(255) NOT NULL default '',
  `lastname` char(255) NOT NULL default '',
  `address1` char(255) NOT NULL default '',
  `address2` char(255) NOT NULL default '',
  `city` char(255) NOT NULL default '',
  `state` char(255) NOT NULL default '',
  `zipcode` char(255) NOT NULL default '',
  `country` char(255) NOT NULL default '',
  `phone` char(255) NOT NULL default '',
  `fax` char(255) NOT NULL default '',
  `cell` char(255) NOT NULL default '',
  `uniq_id` char(64) NOT NULL default '',
  PRIMARY KEY  (`uniq_id`),
  KEY `uniq_id` (`uniq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-10-10  4:59:31
-- MySQL dump 10.10
--
-- Host: localhost    Database: minibill_dev
-- ------------------------------------------------------
-- Server version	5.0.27-log

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
-- Table structure for table `mod_shipping_data`
--

DROP TABLE IF EXISTS `mod_shipping_data`;
CREATE TABLE `mod_shipping_data` (
  `ship_id` int(10) unsigned NOT NULL auto_increment,
  `uniq_id` char(20) NOT NULL default '',
  `user_id` int(10) unsigned NOT NULL default '0',
  `shipper` char(255) NOT NULL default '',
  `tracking_no` char(255) NOT NULL default '',
  `packaged` datetime NOT NULL default '0000-00-00 00:00:00',
  `shipped` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ship_id`),
  KEY `uniq_id` (`uniq_id`),
  KEY `tracking_no` (`tracking_no`)
) ENGINE=MyISAM AUTO_INCREMENT=118 DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-10-10  4:59:31
