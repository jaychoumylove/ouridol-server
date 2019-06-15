UPDATE `f_cfg_rec_type` SET `content`='购买礼物【$0】' WHERE (`id`='8');

INSERT INTO `f_cfg_rec_type` (`id`, `content`) VALUES ('15', '送给爱豆礼物【$0】');

ALTER TABLE `f_user_ext`
ADD COLUMN `steal_time`  int NULL DEFAULT 0 COMMENT '偷取时间' AFTER `steal_count`;