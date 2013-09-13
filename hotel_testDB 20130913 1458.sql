-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.51b-community-nt-log


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema hotel
--

CREATE DATABASE IF NOT EXISTS hotel;
USE hotel;

--
-- Definition of table `consume_record`
--

DROP TABLE IF EXISTS `consume_record`;
CREATE TABLE `consume_record` (
  `consumeRecord_id` int(10) unsigned NOT NULL auto_increment COMMENT '记录编号',
  `consumeRecord_item_id` int(10) unsigned NOT NULL COMMENT '消费物品id',
  `consumeRecord_quantity` int(10) unsigned NOT NULL COMMENT '消费数量',
  `consumeRecord_state` smallint(6) NOT NULL COMMENT '记录状态',
  `consumeRecord_stime` int(10) unsigned NOT NULL COMMENT '开始时间',
  `consumeRecord_etime` int(10) unsigned NOT NULL COMMENT '终止时间',
  `consumeRecord_confirmed` tinyint(1) NOT NULL default '0' COMMENT '确认收款',
  PRIMARY KEY  (`consumeRecord_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20000004 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='消费物品记录; InnoDB free: 4096 kB; InnoDB free: 3072 kB';

--
-- Dumping data for table `consume_record`
--

/*!40000 ALTER TABLE `consume_record` DISABLE KEYS */;
INSERT INTO `consume_record` (`consumeRecord_id`,`consumeRecord_item_id`,`consumeRecord_quantity`,`consumeRecord_state`,`consumeRecord_stime`,`consumeRecord_etime`,`consumeRecord_confirmed`) VALUES 
 (20000001,93001,2,1,4294967295,4294967295,1),
 (20000002,93002,1,0,4294967295,4294967295,1),
 (20000003,91001,1,0,1338480659,0,0);
/*!40000 ALTER TABLE `consume_record` ENABLE KEYS */;


--
-- Definition of table `damage_record`
--

DROP TABLE IF EXISTS `damage_record`;
CREATE TABLE `damage_record` (
  `damageRecord_id` int(10) unsigned NOT NULL auto_increment COMMENT '记录编号',
  `damageRecord_item_id` int(10) unsigned NOT NULL COMMENT '损坏物品id',
  `damageRecord_quantity` int(10) unsigned NOT NULL COMMENT '损坏数量',
  `damageRecord_state` smallint(6) NOT NULL COMMENT '记录状态',
  `damageRecord_stime` int(10) unsigned NOT NULL COMMENT '开始时间',
  `damageRecord_etime` int(10) unsigned NOT NULL COMMENT '终止时间',
  `damageRecord_confirmed` tinyint(1) NOT NULL COMMENT '确认收款',
  PRIMARY KEY  (`damageRecord_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30000003 DEFAULT CHARSET=utf8 COMMENT='损坏物品记录; InnoDB free: 3072 kB';

--
-- Dumping data for table `damage_record`
--

/*!40000 ALTER TABLE `damage_record` DISABLE KEYS */;
INSERT INTO `damage_record` (`damageRecord_id`,`damageRecord_item_id`,`damageRecord_quantity`,`damageRecord_state`,`damageRecord_stime`,`damageRecord_etime`,`damageRecord_confirmed`) VALUES 
 (30000001,91001,1,1,4294967295,4294967295,1),
 (30000002,50001,1,0,1338481017,0,0);
/*!40000 ALTER TABLE `damage_record` ENABLE KEYS */;


--
-- Definition of table `equipment`
--

DROP TABLE IF EXISTS `equipment`;
CREATE TABLE `equipment` (
  `equipment_id` int(10) unsigned NOT NULL auto_increment COMMENT '设备编号',
  `equipment_name` varchar(45) NOT NULL COMMENT '设备名称',
  `equipment_state` smallint(6) NOT NULL COMMENT '设备状态',
  `equipment_position` varchar(45) NOT NULL COMMENT '设备位置',
  `equipment_price` int(10) unsigned NOT NULL COMMENT '设备价格',
  PRIMARY KEY  (`equipment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4002002 DEFAULT CHARSET=utf8 COMMENT='设备清单';

--
-- Dumping data for table `equipment`
--

/*!40000 ALTER TABLE `equipment` DISABLE KEYS */;
INSERT INTO `equipment` (`equipment_id`,`equipment_name`,`equipment_state`,`equipment_position`,`equipment_price`) VALUES 
 (50001,'普通设备',-1,'走廊',500),
 (100101,'松下2千瓦空调',1,'501',2000),
 (100102,'松下2千瓦空调',1,'502',2000),
 (100103,'松下2千瓦空调',1,'504',2000),
 (100104,'松下2千瓦空调',1,'505',2000),
 (1002001,'松下立式3千瓦空调',1,'506',4000),
 (1003001,'松下1千5百瓦空调',-1,'503',1500),
 (2001001,'三星21寸电视机',1,'501',1500),
 (2001002,'三星21寸电视机',1,'502',1500),
 (2001003,'三星21寸电视机',1,'503',1500),
 (2001004,'三星21寸电视机',1,'504',1500),
 (2001005,'三星21寸电视机',1,'505',1500),
 (2002001,'三星32寸液晶电视机',1,'506',3500),
 (3001001,'联想台式电脑',1,'504',4000),
 (3001002,'联想台式电脑',-1,'506',4000),
 (3001003,'联想台式电脑',1,'505',4000),
 (4001001,'西门子电话机',1,'501',200),
 (4001002,'西门子电话机',-1,'502',200),
 (4001003,'西门子电话机',1,'503',200),
 (4001004,'西门子电话机',1,'504',200),
 (4001005,'西门子电话机',1,'505',200),
 (4002001,'西门子可移动电话机',1,'506',200);
/*!40000 ALTER TABLE `equipment` ENABLE KEYS */;


--
-- Definition of table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `item_id` int(10) unsigned NOT NULL auto_increment COMMENT '物品编号',
  `item_name` varchar(45) NOT NULL COMMENT '物品名称',
  `item_quantity` int(10) unsigned NOT NULL COMMENT '物品数量',
  `item_price` int(10) unsigned NOT NULL COMMENT '物品价格',
  `item_needAdd` tinyint(1) NOT NULL COMMENT '是否需要补货',
  PRIMARY KEY  USING BTREE (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=94005 DEFAULT CHARSET=utf8 COMMENT='物品清单';

--
-- Dumping data for table `item`
--

/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` (`item_id`,`item_name`,`item_quantity`,`item_price`,`item_needAdd`) VALUES 
 (91001,'床单',200,50,0),
 (91002,'毛巾',600,40,0),
 (91003,'被子',100,200,1),
 (92001,'一次性拖鞋',800,0,0),
 (92002,'一次性牙刷组合',100,0,1),
 (92003,'卷装卫生纸',800,0,0),
 (92004,'小包洗发水',1000,0,0),
 (92005,'小包沐浴露',1000,0,0),
 (93001,'百事可乐',500,5,0),
 (93002,'康师傅红烧牛肉面',50,10,1),
 (93003,'乐事薯片',100,8,0),
 (93004,'农夫山泉矿泉水',500,3,0),
 (93005,'恰恰香瓜子',30,10,1),
 (94001,'茶杯',30,20,0),
 (94002,'电热水壶',10,100,0),
 (94003,'吹风机',20,100,0),
 (94004,'台灯',10,200,0);
/*!40000 ALTER TABLE `item` ENABLE KEYS */;


--
-- Definition of table `repair_list`
--

DROP TABLE IF EXISTS `repair_list`;
CREATE TABLE `repair_list` (
  `repairList_id` int(10) unsigned NOT NULL auto_increment COMMENT '维修编号',
  `repairList_room_id` int(10) unsigned NOT NULL COMMENT '房间号',
  `repairList_equipment_id` int(10) unsigned NOT NULL COMMENT '设备号',
  `repairList_state` smallint(6) NOT NULL COMMENT '维修状态',
  `repairList_stime` int(10) unsigned NOT NULL COMMENT '报修时间',
  `repairList_etime` int(10) unsigned NOT NULL COMMENT '终止时间',
  `repairList_deadline` int(10) unsigned NOT NULL COMMENT '维修期限',
  `repairList_detail` varchar(45) default NULL COMMENT '备注说明',
  PRIMARY KEY  (`repairList_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000003 DEFAULT CHARSET=utf8 COMMENT='维修记录';

--
-- Dumping data for table `repair_list`
--

/*!40000 ALTER TABLE `repair_list` DISABLE KEYS */;
INSERT INTO `repair_list` (`repairList_id`,`repairList_room_id`,`repairList_equipment_id`,`repairList_state`,`repairList_stime`,`repairList_etime`,`repairList_deadline`,`repairList_detail`) VALUES 
 (10000001,503,1003001,0,1338114918,0,259200,'不出冷风'),
 (10000002,101,50001,1,1338211130,1338216755,259200,'aaaaa');
/*!40000 ALTER TABLE `repair_list` ENABLE KEYS */;


--
-- Definition of table `room`
--

DROP TABLE IF EXISTS `room`;
CREATE TABLE `room` (
  `room_id` int(10) unsigned NOT NULL COMMENT '房间号',
  `room_type` varchar(45) NOT NULL COMMENT '房间类型',
  `room_price` int(10) unsigned NOT NULL COMMENT '房间价格',
  `room_state` smallint(6) NOT NULL COMMENT '房间状态',
  PRIMARY KEY  USING BTREE (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客房房间信息';

--
-- Dumping data for table `room`
--

/*!40000 ALTER TABLE `room` DISABLE KEYS */;
INSERT INTO `room` (`room_id`,`room_type`,`room_price`,`room_state`) VALUES 
 (501,'大床房',200,0),
 (502,'标准房',220,1),
 (503,'单人房',150,-1),
 (504,'商务大床房',250,2),
 (505,'商务标准房',280,3),
 (506,'豪华套房',400,3),
 (601,'大床房',200,1),
 (602,'标准房',220,0),
 (603,'单人房',150,0),
 (604,'商务大床房',250,-1),
 (605,'商务标准房',280,1),
 (606,'豪华套房',400,1);
/*!40000 ALTER TABLE `room` ENABLE KEYS */;


--
-- Definition of table `storehouse_record`
--

DROP TABLE IF EXISTS `storehouse_record`;
CREATE TABLE `storehouse_record` (
  `storehouseRecord_id` int(10) unsigned NOT NULL auto_increment COMMENT '记录编号',
  `storehouseRecord_time` int(10) unsigned NOT NULL COMMENT '出入库时间',
  `storehouseRecord_type` tinyint(1) NOT NULL COMMENT '出库或入库',
  `storehouseRecord_list` varchar(200) NOT NULL COMMENT '物品清单',
  `storehouseRecord_user` varchar(45) NOT NULL COMMENT '物品接受方',
  PRIMARY KEY  (`storehouseRecord_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50000014 DEFAULT CHARSET=utf8 COMMENT='出入库记录';

--
-- Dumping data for table `storehouse_record`
--

/*!40000 ALTER TABLE `storehouse_record` DISABLE KEYS */;
INSERT INTO `storehouse_record` (`storehouseRecord_id`,`storehouseRecord_time`,`storehouseRecord_type`,`storehouseRecord_list`,`storehouseRecord_user`) VALUES 
 (40000001,1338114918,0,'一次性牙刷*30|包洗发水*20|小包沐浴露*20|卷装卫生纸*20|一次性拖鞋*30|百事可乐*8','楼层服务员1号'),
 (50000001,1338114918,1,'康师傅红烧牛肉面 *100','仓库管理员1号'),
 (50000002,1338114918,1,'被子*30','仓库管理员2号'),
 (50000007,1338418457,0,'毛巾*100|床单*100','2'),
 (50000008,1338418493,0,'毛巾*100|床单*100','2'),
 (50000009,1338418581,0,'毛巾*100|床单*100','2'),
 (50000010,1338456725,0,'毛巾*100|床单*100','2'),
 (50000011,1338456748,1,'毛巾*100|床单*100','fwf'),
 (50000012,1338471223,0,'毛巾*100|床单*100','2'),
 (50000013,1338471244,1,'毛巾*100|床单*100','3');
/*!40000 ALTER TABLE `storehouse_record` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
