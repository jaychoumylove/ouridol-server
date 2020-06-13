ALTER TABLE `f_user_star`
ADD COLUMN `active_subscribe`  tinyint NULL DEFAULT 0 COMMENT '活动消息订阅' AFTER `fanclub_id`;

ALTER TABLE `f_user_star`
ADD COLUMN `active_newbie_cards`  int UNSIGNED NULL DEFAULT 0 COMMENT '我拉的新人助力我解锁打卡的次数' AFTER `active_subscribe`;

INSERT INTO `f_cfg` (`id`, `description`, `key`, `value`, `show`, `create_time`, `update_time`, `delete_time`) VALUES ('39', '应援活动结束日期', 'active_end', '1564502400', '0', '2019-07-05 14:34:50', '2019-07-05 14:34:50', NULL);
INSERT INTO `f_cfg` (`id`, `description`, `key`, `value`, `show`, `create_time`, `update_time`, `delete_time`) VALUES ('40', '微信朋友圈分享文本', 'pyq_share_text', '这是微信朋友圈分享内容', '0', '2019-07-05 20:52:47', '2019-07-05 20:52:47', NULL);

DROP TABLE IF EXISTS `f_rec_active`;
CREATE TABLE `f_rec_active` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '解锁次数',
  `card_count` int(11) DEFAULT NULL COMMENT '今日解锁次数',
  `card_time` int(11) DEFAULT NULL COMMENT '打卡date',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`card_time`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='记录-解锁应援金打卡';

ALTER TABLE `f_user_star`
MODIFY COLUMN `fanclub_id`  int(10) UNSIGNED NULL DEFAULT 0 COMMENT '加入的后援会id' AFTER `create_time`,
MODIFY COLUMN `active_subscribe`  tinyint(4) NULL DEFAULT 0 COMMENT '活动消息订阅' AFTER `fanclub_id`;

ALTER TABLE `f_star_rank`
ADD COLUMN `active_finished_fee`  float UNSIGNED NULL DEFAULT 0 COMMENT '活动已达成金额' AFTER `month_hot`;

ALTER TABLE `f_user_star`
MODIFY COLUMN `active_subscribe`  tinyint(4) NULL DEFAULT 0 COMMENT '活动消息订阅 0未订阅 1已取消订阅 2已订阅 ' AFTER `fanclub_id`;

--  2020年06月10日14:17:40 新增对道具上下架的控制
ALTER TABLE f_prop ADD status enum('OFF', 'ON') DEFAULT 'ON' NOT NULL COMMENT 'ON 上架中
OFF 已下架';
ALTER TABLE f_prop
  MODIFY COLUMN status enum('OFF', 'ON') NOT NULL DEFAULT 'ON' COMMENT 'ON 上架中
OFF 已下架' AFTER fee;
CREATE INDEX f_prop_status_index ON f_prop (status);
-- end

-- 2020-06-10 17:56:35 新增道具灵丹兑换
ALTER TABLE f_prop ADD stone int(11) DEFAULT 0 NOT NULL COMMENT '花费灵丹';
ALTER TABLE f_prop
  MODIFY COLUMN stone int(11) NOT NULL DEFAULT 0 COMMENT '花费灵丹' AFTER fee;
-- END

-- 2020-06-11 15:46:49 新增灵丹兑换type
INSERT INTO `f_cfg_rec_type`(`id`, `content`, `create_time`, `update_time`, `delete_time`) VALUES (39, '兑换【$0】个【$1】', '2020-06-11 09:54:52', '2020-06-11 10:57:35', NULL);
 -- 新增两个道具
 INSERT INTO `f_prop`(`id`, `title`, `name`, `img`, `fee`, `stone`, `status`, `desc`, `remain`, `create_time`, `update_time`, `delete_time`) VALUES (5, '道具购买', '偷取能量双倍卡', NULL, 2, 40, 'OFF', '使用后，一小时内，偷取能量双倍获得，冷却时间缩短到40秒', 100, '2020-06-09 14:52:40', '2020-06-11 09:57:03', NULL);
INSERT INTO `f_prop`(`id`, `title`, `name`, `img`, `fee`, `stone`, `status`, `desc`, `remain`, `create_time`, `update_time`, `delete_time`) VALUES (6, '道具购买', '偷取能量三倍卡', NULL, 4, 80, 'OFF', '使用后，一小时内，偷取能量三倍获得，冷却时间缩短到10秒', 100, '2020-06-09 14:53:31', '2020-06-11 09:57:03', NULL);
-- wnd

-- 2020-06-12 10:40:14 新增解禁时间
ALTER TABLE f_user_ext ADD forbidden_time int(11) NULL COMMENT '发言喊话解禁时间';
ALTER TABLE f_user_ext
  MODIFY COLUMN forbidden_time int(11) COMMENT '发言喊话解禁时间' AFTER gzh_signin_time;
-- end
--- 2020-06-12 11:43:16 新增禁言配置
INSERT INTO `f_cfg`(`id`, `description`, `key`, `value`, `show`, `create_time`, `update_time`, `delete_time`) VALUES (69, '禁言时间', 'forbidden_time', '[{\"key\": \"1hours\", \"value\": \"禁言1小时\"},{\"key\": \"3hours\", \"value\": \"禁言3小时\"},{\"key\": \"1day\", \"value\": \"禁言24小时\"},{\"key\": \"1week\", \"value\": \"禁言7天\"},{\"key\": \"1year\", \"value\": \"禁言365天\"}]', 1, '2020-06-12 11:41:23', '2020-06-12 11:42:13', NULL);
-- end

-- 2020-06-12 13:58:17 新增举报记录表

CREATE TABLE `f_rec_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '举报用户id',
  `report_id` int(11) NOT NULL COMMENT '被举报者用户id',
  `create_time` timestamp DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp DEFAULT CURRENT_TIMESTAMP,
  `delete_time` timestamp DEFAULT null,
  PRIMARY KEY (`id`),
  UNIQUE KEY `report_id_uindex` (`id`),
  KEY `f_rec_report_report_index` (`report_id`),
  KEY `f_rec_report_report_create_index` (`report_id`,`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='举报记录表';

ALTER TABLE f_rec_report ADD report_type int NOT NULL COMMENT '被举报原因类型';
ALTER TABLE f_rec_report ADD extend varchar(255) NULL COMMENT '举报携带数据';
ALTER TABLE f_rec_report CHANGE report_type type int(11) NOT NULL COMMENT '被举报原因类型';
ALTER TABLE f_rec_report
  MODIFY COLUMN type int(11) NOT NULL COMMENT '被举报原因类型' AFTER report_id,
  MODIFY COLUMN extend varchar(255) COMMENT '举报携带数据' AFTER type;

-- end

-- 2020-06-12 15:36:02
ALTER TABLE f_rec_star_chart ADD type enum('WORLD', 'MESSAGE') DEFAULT 'MESSAGE' NULL COMMENT '发言类型
world 世界喊话
message 普通发言';
ALTER TABLE f_rec_star_chart
  MODIFY COLUMN type enum('WORLD', 'MESSAGE') DEFAULT 'MESSAGE' COMMENT '发言类型
world 世界喊话
message 普通发言' AFTER content;
-- end

-- 2020-06-12 15:58:28  新增举报原因配置信息
INSERT INTO `f_cfg`(`id`, `description`, `key`, `value`, `show`, `create_time`, `update_time`, `delete_time`) VALUES (70, '举报类别列表', 'report_reason', '[{\"value\":\"该用户存在赌博行为\"},{\"value\":\"该用户存在欺诈骗钱行为\"},{\"value\":\"该用户发布不适当信息对我进行骚扰\"},{\"value\":\"该用户传播谣言信息\"}]', 1, '2020-06-12 15:57:17', '2020-06-12 15:58:06', NULL);
-- end

-- 2020-06-13 16:23:59  新增618活动领取type
INSERT INTO `f_cfg_rec_type`(`id`, `content`, `create_time`, `update_time`, `delete_time`) VALUES (40, '\"618\"活动-领取【怦然心动】', '2020-06-13 16:21:31', '2020-06-13 16:21:53', NULL);
-- end
