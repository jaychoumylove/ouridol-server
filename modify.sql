DROP TABLE IF EXISTS `f_cfg_rec_type`;
CREATE TABLE `f_cfg_rec_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL COMMENT '日志类型描述',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COMMENT='配置-日志-类型';

-- ----------------------------
-- Records of f_cfg_rec_type
-- ----------------------------
INSERT INTO `f_cfg_rec_type` VALUES ('1', '偷能量', '2019-04-28 11:15:44', '2019-04-28 11:15:44', null);
INSERT INTO `f_cfg_rec_type` VALUES ('2', '给STAR打榜', '2019-04-28 11:50:52', '2019-05-25 11:26:36', null);
INSERT INTO `f_cfg_rec_type` VALUES ('3', '全服喊话', '2019-05-05 15:36:25', '2019-05-05 15:36:25', null);
INSERT INTO `f_cfg_rec_type` VALUES ('4', '对好友使用【助人为乐】', '2019-05-16 11:29:51', '2019-05-25 11:35:44', null);
INSERT INTO `f_cfg_rec_type` VALUES ('5', '领取徒弟收益', '2019-05-16 14:30:23', '2019-05-16 14:30:23', null);
INSERT INTO `f_cfg_rec_type` VALUES ('6', '成功集结', '2019-05-16 14:42:17', '2019-05-16 14:42:17', null);
INSERT INTO `f_cfg_rec_type` VALUES ('7', 'USER为我收集能量', '2019-05-25 10:55:34', '2019-05-25 11:24:21', null);
INSERT INTO `f_cfg_rec_type` VALUES ('8', '补充能量', '2019-05-25 11:32:26', '2019-05-25 11:32:26', null);
INSERT INTO `f_cfg_rec_type` VALUES ('9', '收集精灵能量', '2019-05-25 11:35:11', '2019-05-25 11:35:11', null);
INSERT INTO `f_cfg_rec_type` VALUES ('10', '公众号签到奖励', '2019-05-25 11:37:27', '2019-05-25 11:37:27', null);
INSERT INTO `f_cfg_rec_type` VALUES ('11', '升级精灵', '2019-05-25 15:55:05', '2019-05-25 15:55:05', null);
INSERT INTO `f_cfg_rec_type` VALUES ('12', '完成任务', '2019-05-25 15:58:40', '2019-05-25 15:58:40', null);

INSERT INTO `f_cfg` (`description`, `key`, `value`, `show`, `create_time`, `update_time`, `delete_time`) VALUES ('开屏图片', 'open_img', 'http://wx2.sinaimg.cn/large/0060lm7Tly1g3dgc60bvwj30kv15sah6.jpg', '0', '2019-05-25 12:04:10', '2019-05-25 12:31:24', NULL);

