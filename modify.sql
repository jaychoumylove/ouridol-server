ALTER TABLE `f_user`
ADD COLUMN `type`  int NULL DEFAULT 0 COMMENT '0普通用户 1管理员' AFTER `ident_code`;

CREATE TABLE `f_other_lock` (
`id`  int NOT NULL AUTO_INCREMENT ,
`islock`  tinyint NULL DEFAULT 0 COMMENT '锁住 不能打榜' ,
`create_time`  timestamp NULL DEFAULT CURRENT_TIMESTAMP AFTER `islock`,
`update_time`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `create_time`,
`delete_time`  timestamp NULL DEFAULT NULL AFTER `update_time`
PRIMARY KEY (`id`)
)
COMMENT='其他-结榜锁';

