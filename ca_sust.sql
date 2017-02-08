/*
SQLyog Ultimate v11.24 (32 bit)
MySQL - 5.5.40-log : Database - ca_sust
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`ca_sust` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `ca_sust`;

/*Table structure for table `app` */

DROP TABLE IF EXISTS `app`;

CREATE TABLE `app` (
  `appid` bigint(19) NOT NULL AUTO_INCREMENT,
  `categoryid` bigint(19) NOT NULL,
  `guid` char(36) NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` varchar(128) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `color` char(6) NOT NULL,
  `params` varchar(128) NOT NULL,
  `order` decimal(8,2) NOT NULL,
  `version` varchar(16) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '1: 启用; 2: 禁用',
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`appid`,`categoryid`),
  KEY `fk_app_appcategory` (`categoryid`),
  CONSTRAINT `fk_app_appcategory` FOREIGN KEY (`categoryid`) REFERENCES `appcategory` (`categoryid`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `app` */

insert  into `app`(`appid`,`categoryid`,`guid`,`name`,`description`,`type`,`color`,`params`,`order`,`version`,`status`,`createdate`) values (8,1,'5c59b0b5-6ed0-43c9-bfb7-c6370a3b3acd','商品激活管理','通过该应用申请激活密钥并激活平台提供软件; 查询密钥申请和激活相关信息',1,'098439','ActivationManager.exe','0.00','1.0.0.7',1,'2015-12-11 11:03:59');

/*Table structure for table `appcategory` */

DROP TABLE IF EXISTS `appcategory`;

CREATE TABLE `appcategory` (
  `categoryid` bigint(19) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`categoryid`),
  UNIQUE KEY `un_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `appcategory` */

insert  into `appcategory`(`categoryid`,`name`) values (1,'商品激活');

/*Table structure for table `autoassign` */

DROP TABLE IF EXISTS `autoassign`;

CREATE TABLE `autoassign` (
  `autoassignid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `note` varchar(256) DEFAULT NULL COMMENT '说明',
  `keyassign` text COMMENT '密钥分配字符串 例如: [{"keyid":16,"amount":1},{"keyid":18,"amount":2}]',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1.启用 2.禁用',
  PRIMARY KEY (`autoassignid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `autoassign` */

/*Table structure for table `autoassign__user` */

DROP TABLE IF EXISTS `autoassign__user`;

CREATE TABLE `autoassign__user` (
  `autoassignid` bigint(20) unsigned NOT NULL,
  `username` varchar(64) NOT NULL,
  PRIMARY KEY (`autoassignid`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `autoassign__user` */

/*Table structure for table `department` */

DROP TABLE IF EXISTS `department`;

CREATE TABLE `department` (
  `departmentid` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` bigint(19) unsigned DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`departmentid`),
  KEY `fk_department_department` (`parentid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `department` */

insert  into `department`(`departmentid`,`parentid`,`name`,`createdate`) values (1,NULL,'陕西科技大学','2015-12-11 11:03:59'),(2,1,'测试','2015-12-11 11:03:59');

/*Table structure for table `department__key` */

DROP TABLE IF EXISTS `department__key`;

CREATE TABLE `department__key` (
  `departmentkeyid` bigint(19) NOT NULL AUTO_INCREMENT,
  `departmentid` bigint(19) unsigned NOT NULL,
  `keyid` bigint(19) unsigned NOT NULL,
  `count` smallint(5) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: 正常, 2: 取消',
  `assigndate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`departmentkeyid`,`departmentid`,`keyid`),
  KEY `fk_department__key_key` (`keyid`),
  KEY `fk_department__key_department` (`departmentid`),
  CONSTRAINT `fk_department__key_department` FOREIGN KEY (`departmentid`) REFERENCES `department` (`departmentid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_department__key_key` FOREIGN KEY (`keyid`) REFERENCES `key` (`keyid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `department__key` */

insert  into `department__key`(`departmentkeyid`,`departmentid`,`keyid`,`count`,`status`,`assigndate`) values (1,2,3,32767,1,'2015-12-11 11:03:59'),(2,2,4,32767,1,'2015-12-11 11:03:59'),(3,2,5,32767,1,'2015-12-11 11:03:59');

/*Table structure for table `exchangecode` */

DROP TABLE IF EXISTS `exchangecode`;

CREATE TABLE `exchangecode` (
  `codeid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(38) NOT NULL,
  `keyid` bigint(20) unsigned NOT NULL,
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '1.未分配 2.已分配 3.已使用',
  `managerid` bigint(20) DEFAULT NULL COMMENT '分配管理员',
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `assigndate` timestamp NULL DEFAULT NULL COMMENT '分配时间',
  PRIMARY KEY (`codeid`,`keyid`),
  KEY `fk_code_key` (`keyid`),
  CONSTRAINT `fk_code_key` FOREIGN KEY (`keyid`) REFERENCES `key` (`keyid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `exchangecode` */

/*Table structure for table `help` */

DROP TABLE IF EXISTS `help`;

CREATE TABLE `help` (
  `helpid` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `managerid` bigint(19) unsigned NOT NULL,
  `categoryid` bigint(19) unsigned NOT NULL,
  `title` varchar(256) NOT NULL,
  `content` text,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedate` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1: 可用; 2: 禁用',
  PRIMARY KEY (`helpid`,`managerid`,`categoryid`),
  KEY `fk_help_helpcategory` (`categoryid`),
  KEY `fk_help_manager` (`managerid`),
  CONSTRAINT `fk_help_helpcategory` FOREIGN KEY (`categoryid`) REFERENCES `helpcategory` (`categoryid`) ON UPDATE NO ACTION,
  CONSTRAINT `fk_help_manager` FOREIGN KEY (`managerid`) REFERENCES `manager` (`managerid`) ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `help` */

/*Table structure for table `helpcategory` */

DROP TABLE IF EXISTS `helpcategory`;

CREATE TABLE `helpcategory` (
  `categoryid` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`categoryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `helpcategory` */

/*Table structure for table `key` */

DROP TABLE IF EXISTS `key`;

CREATE TABLE `key` (
  `keyid` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `productid` bigint(19) unsigned NOT NULL,
  `departmentid` bigint(19) unsigned NOT NULL,
  `key` varchar(64) DEFAULT NULL,
  `section` varchar(16) DEFAULT NULL,
  `count` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '可激活量',
  `name` varchar(64) NOT NULL,
  `server` varchar(128) DEFAULT NULL COMMENT '激活服务器, 分号开头为未直连本地服务器',
  `note` varchar(256) DEFAULT NULL COMMENT '注释',
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`keyid`,`productid`,`departmentid`),
  KEY `fk_key_product` (`productid`),
  KEY `fk_key_department` (`departmentid`),
  CONSTRAINT `fk_key_product` FOREIGN KEY (`productid`) REFERENCES `product` (`productid`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `key` */

insert  into `key`(`keyid`,`productid`,`departmentid`,`key`,`section`,`count`,`name`,`server`,`note`,`createdate`) values (3,20,2,'CS9Ws2AHi7A/H2BtzyCgb8qwSJ14S9G4GiPlb2H9y9I=','NG4HW',100000,'Windows 7/8 A',';kms.xzcit.cn','windows 7/8 test','2015-12-11 11:03:59'),(4,21,2,'9Wib+sA8VH7dGYO8BE6QA/7SXenhxL+QOrKf1rZjEXc=','YFKBB',100000,'office 2010/2013 KMS',';kms.xzcit.cn','','2015-12-11 11:03:59'),(5,43,2,'CS9Ws2AHi7A/H2BtzyCgb8qwSJ14S9G4GiPlb2H9y9I=','NG4HW',100000,'Windows 10 A',';kms.xzcit.cn','windows 10 test','2015-12-11 11:03:59');

/*Table structure for table `keyusage` */

DROP TABLE IF EXISTS `keyusage`;

CREATE TABLE `keyusage` (
  `usageid` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `userid` bigint(19) unsigned NOT NULL,
  `keyid` bigint(19) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1: 申请激活; 2: 激活成功; 3: 激活失败; 4: 激活重置; 5: 激活完成',
  `ip` int(10) unsigned NOT NULL COMMENT '考虑到ipv4，ipv6',
  `computerid` varchar(64) NOT NULL COMMENT '客户端计算机id',
  `errorcode` varchar(512) DEFAULT NULL COMMENT '激活返回错误码',
  `productname` varchar(64) DEFAULT NULL,
  `productversion` varchar(64) DEFAULT NULL,
  `productbit` tinyint(4) DEFAULT NULL COMMENT '1: 32位, 2: 64位',
  `begindate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enddate` datetime DEFAULT NULL,
  PRIMARY KEY (`usageid`,`userid`,`keyid`),
  UNIQUE KEY `un_uuid` (`uuid`),
  KEY `fk_keyusage_user` (`userid`),
  KEY `fk_keyusage_key` (`keyid`),
  CONSTRAINT `fk_keyusage_key` FOREIGN KEY (`keyid`) REFERENCES `key` (`keyid`) ON UPDATE NO ACTION,
  CONSTRAINT `fk_keyusage_user` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='密钥使用记录';

/*Data for the table `keyusage` */

/*Table structure for table `manager` */

DROP TABLE IF EXISTS `manager`;

CREATE TABLE `manager` (
  `managerid` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `departmentid` bigint(19) unsigned NOT NULL COMMENT '管理员隶属与该部门',
  `name` varchar(32) NOT NULL,
  `password` char(60) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: 正常; 2: 锁定',
  `role` varchar(512) NOT NULL COMMENT '管理员权限',
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`managerid`,`departmentid`),
  UNIQUE KEY `un_name` (`name`),
  KEY `fk_manager_department` (`departmentid`),
  CONSTRAINT `fk_manager_department` FOREIGN KEY (`departmentid`) REFERENCES `department` (`departmentid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `manager` */

insert  into `manager`(`managerid`,`departmentid`,`name`,`password`,`status`,`role`,`createdate`) values (1,1,'sust','$2y$08$Sg0NW1mw45xYNvdfX4HgD.f53M5VyzHyzl007xmVuj8RDuK20X7QG',1,'[manager],[department],[app],[user],[user.new],[autoassign],[key],[departmentkeyassign],[product],[keyusage],[exchangecode],[keyassign],[productpermission],[activationstatus],[chartkeyusage],[chartkeycount],[chartkeyassign],[chartproductactivate],[charterror],[chartuser]','2015-12-11 13:21:28');

/*Table structure for table `notification` */

DROP TABLE IF EXISTS `notification`;

CREATE TABLE `notification` (
  `notificationid` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(128) NOT NULL,
  `width` smallint(5) unsigned NOT NULL,
  `height` smallint(5) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: 不推送; 2: 推送',
  PRIMARY KEY (`notificationid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `notification` */

/*Table structure for table `notification__department` */

DROP TABLE IF EXISTS `notification__department`;

CREATE TABLE `notification__department` (
  `notificationid` bigint(19) unsigned NOT NULL,
  `departmentid` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`notificationid`,`departmentid`),
  KEY `fk_notification__department_department` (`departmentid`),
  KEY `fk_notification__department_notification` (`notificationid`),
  CONSTRAINT `fk_notification__department_department` FOREIGN KEY (`departmentid`) REFERENCES `department` (`departmentid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_notification__department_notification` FOREIGN KEY (`notificationid`) REFERENCES `notification` (`notificationid`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `notification__department` */

/*Table structure for table `notificationrecord` */

DROP TABLE IF EXISTS `notificationrecord`;

CREATE TABLE `notificationrecord` (
  `userid` bigint(19) unsigned NOT NULL,
  `notificationid` bigint(19) unsigned NOT NULL,
  `pushdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userid`,`notificationid`),
  KEY `fk_notificationrecord_notification` (`notificationid`),
  CONSTRAINT `fk_notificationrecord_notification` FOREIGN KEY (`notificationid`) REFERENCES `notification` (`notificationid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_notificationrecord_user` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `notificationrecord` */

/*Table structure for table `params` */

DROP TABLE IF EXISTS `params`;

CREATE TABLE `params` (
  `key` varchar(32) NOT NULL,
  `value` varchar(1024) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `params` */

insert  into `params`(`key`,`value`) values ('blockdownloadproducts','[\"adobe\", \"windows8.1\"]'),('canapplykey','1'),('changepassword','1'),('clientpublishversion','3'),('clientversion','2.0.0.0'),('clientversion3','3.0.2.8'),('companyname',''),('customername','陕西科技大学'),('register','1'),('retrievepassword','1'),('servicephone',''),('wsusserver','');

/*Table structure for table `permission` */

DROP TABLE IF EXISTS `permission`;

CREATE TABLE `permission` (
  `permissionid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `intro` varchar(32) NOT NULL,
  `group` varchar(8) NOT NULL,
  PRIMARY KEY (`permissionid`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

/*Data for the table `permission` */

insert  into `permission`(`permissionid`,`name`,`intro`,`group`) values (1,'manager','管理员管理','base'),(2,'department','部门管理','base'),(3,'key','密钥管理','key'),(4,'departmentkeyassign','部门激活分配','key'),(5,'product','商品管理','key'),(6,'app','应用管理','base'),(10,'user','用户管理','base'),(11,'user.new','添加用户','base'),(12,'keyusage','用户激活情况','key'),(14,'chartkeyusage','[图表]激活情况','chart'),(15,'chartkeycount','[图表]密钥总量','chart'),(16,'chartkeyassign','[图表]激活分配','chart'),(17,'chartproductactivate','[图表]产品激活','chart'),(18,'charterror','[图表]激活错误','chart'),(19,'chartuser','[图表]用户数量','chart'),(37,'exchangecode','激活码管理','key'),(21,'keyassign','用户激活分配','key'),(36,'autoassign','密钥自动分配管理','base'),(35,'productpermission','商品权限管理','key'),(34,'activationstatus','用户激活统计','key');

/*Table structure for table `product` */

DROP TABLE IF EXISTS `product`;

CREATE TABLE `product` (
  `productid` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `aliasname` varchar(16) NOT NULL,
  `cimname` varchar(64) NOT NULL,
  `type` varchar(16) NOT NULL,
  `intro` varchar(512) NOT NULL,
  `order` decimal(8,2) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: 可用 2: 不可用 3: 下线',
  PRIMARY KEY (`productid`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

/*Data for the table `product` */

insert  into `product`(`productid`,`name`,`aliasname`,`cimname`,`type`,`intro`,`order`,`status`) values (20,'Windows 7/8','windows','','定时激活','使用定时激活的 Windows 需要在180天后再次运行客户端激活，除非对系统组件进行重大修改。适用于具备稳定的网络连接的计算机。\n \n◆ 注意: 激活过程中 , 大概需要耗时2~5分钟, 请勿关闭程序, 否者将导致激活失败！','0.00',1),(21,'Office 2010/2013','office','','定时激活','使用定时激活的 Office 需要在180天后再次运行客户端激活，除非重新安装软件。适用于具备稳定的网络连接的计算机。\n \n◆ 注意: 激活过程中 , 大概需要耗时2~5分钟, 请勿关闭程序, 否者将导致激活失败！','0.00',1),(43,'Windows 10','windows','','定时激活','使用定时激活的 Windows 需要在180天后再次运行客户端激活，除非对系统组件进行重大修改。适用于具备稳定的网络连接的计算机。\n \n◆ 注意: 激活过程中 , 大概需要耗时2~5分钟, 请勿关闭程序, 否者将导致激活失败！','0.00',1);

/*Table structure for table `productpermission` */

DROP TABLE IF EXISTS `productpermission`;

CREATE TABLE `productpermission` (
  `permissionid` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `productid` bigint(19) unsigned NOT NULL,
  `type` tinyint(4) NOT NULL,
  PRIMARY KEY (`permissionid`,`productid`),
  UNIQUE KEY `un_productid_type` (`productid`,`type`),
  CONSTRAINT `fk_productpermission_product1` FOREIGN KEY (`productid`) REFERENCES `product` (`productid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='商品权限控制, 和用户type表关联';

/*Data for the table `productpermission` */

insert  into `productpermission`(`permissionid`,`productid`,`type`) values (1,20,1),(3,20,2),(5,20,3),(7,20,4),(2,21,1),(4,21,2),(6,21,3),(8,21,4),(9,43,1),(10,43,2),(11,43,3),(12,43,4);

/*Table structure for table `productpkg` */

DROP TABLE IF EXISTS `productpkg`;

CREATE TABLE `productpkg` (
  `pkgid` bigint(19) unsigned NOT NULL,
  `productids` varchar(128) NOT NULL COMMENT '包含哪些商?',
  `note` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`pkgid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `productpkg` */

/*Table structure for table `subkey` */

DROP TABLE IF EXISTS `subkey`;

CREATE TABLE `subkey` (
  `subkeyid` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `keyid` bigint(19) unsigned NOT NULL,
  `userid` bigint(19) unsigned DEFAULT NULL,
  `key` varchar(64) NOT NULL,
  `section` varchar(16) NOT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `outdate` datetime DEFAULT NULL,
  PRIMARY KEY (`subkeyid`,`keyid`),
  KEY `fk_keyset_key` (`keyid`),
  KEY `fk_keyset_user` (`userid`),
  CONSTRAINT `fk_keyset_key` FOREIGN KEY (`keyid`) REFERENCES `key` (`keyid`) ON UPDATE NO ACTION,
  CONSTRAINT `fk_keyset_user` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='密钥子集, 用于零售密钥';

/*Data for the table `subkey` */

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `userid` bigint(19) unsigned NOT NULL AUTO_INCREMENT COMMENT '1) 兑换用: 50000000',
  `departmentid` bigint(19) unsigned NOT NULL,
  `username` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `name` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: 未知 2: 本科生 3: 教职工 4: 研究生',
  `token` varchar(32) DEFAULT NULL COMMENT '客户端修改密钥用',
  `status` tinyint(4) NOT NULL DEFAULT '3' COMMENT '1: 正常 2: 锁定, 3: 待审核',
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userid`,`departmentid`),
  UNIQUE KEY `un_username` (`username`),
  UNIQUE KEY `un_email` (`email`),
  KEY `fk_user_department` (`departmentid`),
  CONSTRAINT `fk_user_department` FOREIGN KEY (`departmentid`) REFERENCES `department` (`departmentid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`userid`,`departmentid`,`username`,`email`,`name`,`password`,`type`,`token`,`status`,`createdate`) values (1,2,'piliang','piliang@edu.cn','批量','bbeb835490894d31b3dfd9dd802391b5',3,NULL,1,'2015-12-11 13:25:53'),(2,2,'test','test@edu.cn','测试','63499f0ac3a0ed7b71be2aa20b0fb752',3,NULL,1,'2015-12-11 13:27:15');

/*Table structure for table `user__productpkg` */

DROP TABLE IF EXISTS `user__productpkg`;

CREATE TABLE `user__productpkg` (
  `userid` bigint(19) unsigned NOT NULL,
  `pkgid` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`userid`,`pkgid`),
  KEY `fk_user__productpkg_productpkg` (`pkgid`),
  KEY `fk_user__productpkg_user` (`userid`),
  CONSTRAINT `fk_user__productpkg_productpkg` FOREIGN KEY (`pkgid`) REFERENCES `productpkg` (`pkgid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user__productpkg_user` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user__productpkg` */

/*Table structure for table `useraccesslog` */

DROP TABLE IF EXISTS `useraccesslog`;

CREATE TABLE `useraccesslog` (
  `accesslogid` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `userid` bigint(19) unsigned NOT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`accesslogid`,`userid`),
  KEY `fk_useraccesslog_user` (`userid`),
  CONSTRAINT `fk_useraccesslog_user` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `useraccesslog` */

insert  into `useraccesslog`(`accesslogid`,`userid`,`createdate`) values (1,1,'2015-12-11 13:36:00');

/*Table structure for table `userinfo1` */

DROP TABLE IF EXISTS `userinfo1`;

CREATE TABLE `userinfo1` (
  `infoid` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `id` varchar(64) NOT NULL COMMENT '工号',
  `college` varchar(128) NOT NULL COMMENT '所在学院部处',
  `grade` varchar(128) NOT NULL COMMENT '所在科室年级',
  PRIMARY KEY (`infoid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `userinfo1` */

/*Table structure for table `userkey` */

DROP TABLE IF EXISTS `userkey`;

CREATE TABLE `userkey` (
  `userkeyid` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `userid` bigint(19) unsigned NOT NULL COMMENT '发起请求用户',
  `productid` bigint(19) unsigned NOT NULL COMMENT '请求商品',
  `managerid` bigint(19) unsigned DEFAULT NULL COMMENT '分配者',
  `keyid` bigint(19) unsigned DEFAULT NULL COMMENT '由管理员进行分配',
  `requestcount` int(10) unsigned NOT NULL COMMENT '用户请求数量',
  `assigncount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分配数量',
  `reason` varchar(256) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: 未审批 2: 不同意 3: 同意',
  `requestdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '申请日期',
  `assigndate` datetime DEFAULT NULL COMMENT '分配日期',
  PRIMARY KEY (`userkeyid`,`userid`,`productid`),
  KEY `fk_userkey_key` (`keyid`),
  KEY `fk_userkey_user` (`userid`),
  KEY `fk_userkey_product` (`productid`),
  KEY `fk_userkey_manager` (`managerid`),
  CONSTRAINT `fk_request_key` FOREIGN KEY (`keyid`) REFERENCES `key` (`keyid`) ON UPDATE NO ACTION,
  CONSTRAINT `fk_request_user` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON UPDATE NO ACTION,
  CONSTRAINT `fk_userkey_manager` FOREIGN KEY (`managerid`) REFERENCES `manager` (`managerid`) ON UPDATE NO ACTION,
  CONSTRAINT `fk_userkey_product` FOREIGN KEY (`productid`) REFERENCES `product` (`productid`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `userkey` */

insert  into `userkey`(`userkeyid`,`userid`,`productid`,`managerid`,`keyid`,`requestcount`,`assigncount`,`reason`,`status`,`requestdate`,`assigndate`) values (1,1,20,1,3,1000,1000,'管理员分配',3,'2015-12-11 13:26:06','2015-12-11 13:26:06'),(2,1,21,1,4,1000,1000,'管理员分配',3,'2015-12-11 13:26:13','2015-12-11 13:26:13'),(3,1,43,1,5,1000,1000,'管理员分配',3,'2015-12-11 13:26:18','2015-12-11 13:26:18'),(4,2,20,1,3,10,10,'管理员分配',3,'2015-12-11 13:27:27','2015-12-11 13:27:27'),(5,2,21,1,4,10,10,'管理员分配',3,'2015-12-11 13:27:32','2015-12-11 13:27:32'),(6,2,43,1,5,10,10,'管理员分配',3,'2015-12-11 13:27:36','2015-12-11 13:27:36');

/*Table structure for table `userpassword` */

DROP TABLE IF EXISTS `userpassword`;

CREATE TABLE `userpassword` (
  `userid` bigint(19) unsigned NOT NULL,
  `password` blob NOT NULL,
  PRIMARY KEY (`userid`),
  CONSTRAINT `fk_userpassword_user` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `userpassword` */

insert  into `userpassword`(`userid`,`password`) values (1,'xm��+#da��`O�|'),(2,'�-O�K�5=��r\0�d');

/*Table structure for table `userthread` */

DROP TABLE IF EXISTS `userthread`;

CREATE TABLE `userthread` (
  `threadid` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `userid` bigint(19) unsigned NOT NULL,
  `connectionid` char(36) NOT NULL COMMENT 'socket 链接Id',
  `ip` int(10) unsigned NOT NULL,
  `logindate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logoutdate` datetime DEFAULT NULL,
  `clientversion` varchar(16) DEFAULT NULL,
  `windowsversion` varchar(64) DEFAULT NULL,
  `windowsactivated` tinyint(1) DEFAULT NULL,
  `officeversion` varchar(64) DEFAULT NULL,
  `officeactivated` tinyint(1) DEFAULT NULL,
  `computerid` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`threadid`,`userid`),
  KEY `fk_useraccessthread_user` (`userid`),
  CONSTRAINT `fk_useraccessthread_user` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `userthread` */

/*!50106 set global event_scheduler = 1*/;

/* Event structure for event `resetkeyusagestatus` */

/*!50106 DROP EVENT IF EXISTS `resetkeyusagestatus`*/;

DELIMITER $$

/*!50106 CREATE DEFINER=`root`@`183.221.12.36` EVENT `resetkeyusagestatus` ON SCHEDULE EVERY 1 MINUTE STARTS '2013-05-06 13:48:15' ON COMPLETION NOT PRESERVE ENABLE COMMENT '重置挂起激活' DO update keyusage set status = 4, enddate = now() where date_add(begindate,interval
5 minute) < now() and status in (1, 5) */$$
DELIMITER ;

/* Procedure structure for procedure `outsubkey` */

/*!50003 DROP PROCEDURE IF EXISTS  `outsubkey` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`10.10.10.%` PROCEDURE `outsubkey`(keyId BIGINT, userId BIGINT)
BEGIN
	SET autocommit = 0;
	#SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;
	
	START TRANSACTION;
	SELECT `subkeyid`, `key` FROM subkey WHERE userid IS NULL and keyid = _keyId LIMIT 1 INTO @subkeyid, @key FOR UPDATE;
	UPDATE subkey SET userid = _userId WHERE subkeyid = @subkeyid;
	COMMIT;
	SELECT @subkeyid AS subkeyid, @key AS `key`;
	SET autocommit = 1;
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
