
ALTER TABLE `f_user_ext`
ADD COLUMN `steal_times`  int NULL DEFAULT 0 COMMENT '今日偷能量次数' AFTER `left_time`;

INSERT INTO `f_cfg` (`description`, `key`, `value`) VALUES ('每天偷能量次数', 'steal_limit', '2000');

UPDATE `f_task` SET `delete_time`=NULL WHERE (`id`='4');

UPDATE `f_task` SET `delete_time`=NULL WHERE (`id`='5');