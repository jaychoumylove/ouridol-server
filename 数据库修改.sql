ALTER TABLE `f_user_item`
MODIFY COLUMN `count`  int(11) UNSIGNED NULL DEFAULT 0 COMMENT '数量' AFTER `uid`;

INSERT INTO `f_cfg_rec_type` (`content`) VALUES ('礼物兑换能量');