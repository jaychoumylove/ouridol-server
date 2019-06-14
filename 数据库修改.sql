ALTER TABLE `f_user_star`
ADD COLUMN `qrcode`  varchar(255) NULL COMMENT '二维码' AFTER `captain`;

INSERT INTO `f_cfg_rec_type` (`id`, `content`) VALUES ('14', '助力好友集结');

DROP TABLE IF EXISTS `f_cfg_user_level`;
CREATE TABLE `f_cfg_user_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total` int(11) DEFAULT NULL COMMENT '贡献度',
  `level` int(11) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COMMENT='配置-粉丝贡献等级';

-- ----------------------------
-- Records of f_cfg_user_level
-- ----------------------------
INSERT INTO `f_cfg_user_level` VALUES ('1', '0', '1', '2019-06-10 18:29:00', '2019-06-10 18:29:20', null);
INSERT INTO `f_cfg_user_level` VALUES ('2', '100', '2', '2019-06-10 18:29:36', '2019-06-10 18:30:18', null);
INSERT INTO `f_cfg_user_level` VALUES ('3', '200', '3', '2019-06-10 18:29:37', '2019-06-10 18:30:18', null);
INSERT INTO `f_cfg_user_level` VALUES ('4', '300', '4', '2019-06-10 18:29:37', '2019-06-10 18:30:18', null);
INSERT INTO `f_cfg_user_level` VALUES ('5', '400', '5', '2019-06-10 18:29:38', '2019-06-10 18:30:18', null);
INSERT INTO `f_cfg_user_level` VALUES ('6', '500', '6', '2019-06-10 18:29:38', '2019-06-10 18:30:18', null);
INSERT INTO `f_cfg_user_level` VALUES ('7', '600', '7', '2019-06-10 18:29:38', '2019-06-10 18:30:18', null);
INSERT INTO `f_cfg_user_level` VALUES ('8', '700', '8', '2019-06-10 18:29:38', '2019-06-10 18:30:18', null);
INSERT INTO `f_cfg_user_level` VALUES ('9', '800', '9', '2019-06-10 18:29:39', '2019-06-10 18:30:18', null);
INSERT INTO `f_cfg_user_level` VALUES ('10', '900', '10', '2019-06-10 18:29:51', '2019-06-10 18:30:18', null);
INSERT INTO `f_cfg_user_level` VALUES ('11', '1000', '11', '2019-06-10 18:29:53', '2019-06-10 18:30:18', null);
INSERT INTO `f_cfg_user_level` VALUES ('12', '1100', '12', '2019-06-10 18:29:57', '2019-06-10 18:30:19', null);
INSERT INTO `f_cfg_user_level` VALUES ('13', '1200', '13', '2019-06-10 18:29:59', '2019-06-10 18:30:19', null);
INSERT INTO `f_cfg_user_level` VALUES ('14', '1300', '14', '2019-06-10 18:30:00', '2019-06-10 18:30:19', null);
INSERT INTO `f_cfg_user_level` VALUES ('15', '1400', '15', '2019-06-10 18:30:02', '2019-06-10 18:30:19', null);
INSERT INTO `f_cfg_user_level` VALUES ('16', '1500', '16', '2019-06-10 18:30:04', '2019-06-10 18:30:19', null);
INSERT INTO `f_cfg_user_level` VALUES ('17', '1600', '17', '2019-06-10 18:30:05', '2019-06-10 18:30:19', null);
INSERT INTO `f_cfg_user_level` VALUES ('18', '1700', '18', '2019-06-10 18:30:08', '2019-06-10 18:30:19', null);
INSERT INTO `f_cfg_user_level` VALUES ('19', '1800', '19', '2019-06-10 18:30:10', '2019-06-10 18:30:19', null);
INSERT INTO `f_cfg_user_level` VALUES ('20', '1900', '20', '2019-06-10 18:30:13', '2019-06-10 18:30:19', null);

ALTER TABLE `f_user_ext`
ADD COLUMN `steal_count`  int NULL DEFAULT 0 COMMENT '今日偷能量数额' AFTER `steal_times`;

INSERT INTO `f_cfg` (`description`, `key`, `value`) VALUES ('每天偷能量最大数额', 'steal_count_limit', '10000');
INSERT INTO `f_cfg` (`id`,`description`, `key`, `value`) VALUES (35, 'canvas分享图片标题', 'canvas_title', '["为爱豆打榜，领取应援金","新浪微博送花"]');
UPDATE `f_cfg` SET `show`='1' WHERE (`id`='35');

ALTER TABLE `f_pay_goods`
ADD COLUMN `item_id`  int NULL COMMENT '道具id' AFTER `fee`;


ALTER TABLE `f_user_item`
CHANGE COLUMN `coin` `count`  int(11) NULL DEFAULT 0 COMMENT '数量' AFTER `uid`,
ADD COLUMN `item_id`  int NULL DEFAULT 0 COMMENT '道具Id' AFTER `count`;

ALTER TABLE `f_rec_pay_order`
ADD COLUMN `item_id`  int NULL DEFAULT 0 COMMENT '道具id' AFTER `total_fee`
