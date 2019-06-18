DROP TABLE IF EXISTS `f_rec_item`;
CREATE TABLE `f_rec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `star_id` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT '1' COMMENT '数量',
  `valueof` int(11) DEFAULT '0' COMMENT '价值多少能量',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='记录-送出礼物';