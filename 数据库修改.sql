

ALTER TABLE `f_fanclub`
ADD COLUMN `mem_count`  int UNSIGNED NULL DEFAULT 0 COMMENT '成员数量' AFTER `status`,
ADD COLUMN `week_count`  int UNSIGNED NULL DEFAULT 0 COMMENT '本周贡献' AFTER `mem_count`,
ADD COLUMN `month_count`  int UNSIGNED NULL DEFAULT 0 COMMENT '本月贡献' AFTER `week_count`;

ALTER TABLE `f_user_star`
ADD COLUMN `fanclub_id`  int UNSIGNED NULL DEFAULT 0 COMMENT '加入的后援会id' AFTER `delete_time`;



update `f_cfg` set `key` = 'fanclub_notice' where id = 26;

ALTER TABLE `f_rec_pay_order`
ADD COLUMN `friend_uid`  int UNSIGNED NULL DEFAULT 0 COMMENT '代充值uid' AFTER `pay_time`;

INSERT INTO `f_cfg` (`id`, `description`, `key`, `value`, `show`, `create_time`, `update_time`, `delete_time`) VALUES ('37', '好友上限', 'friend_max', '100', '0', '2019-07-02 18:07:42', '2019-07-02 18:07:42', NULL);

