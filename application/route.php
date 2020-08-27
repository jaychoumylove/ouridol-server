<?php

use think\Route;

// Test
Route::rule('test', 'api/Test/index');
Route::rule('getToken', 'api/Test/getToken');
Route::rule('getUid', 'api/Test/getUid');
// H5
Route::rule('h5/star', 'api/v1.H5/star');

// Clearner
Route::rule('api/:version/clean', 'api/v1.Cleaner/index');
Route::rule('api/:version/createMenu', 'api/v1.Notify/createMenu');

// AutoRun
Route::rule('api/:version/auto', 'api/v1.AutoRun/index');// 定时任务
//Route::rule('api/:version/auto/d', 'api/v1.AutoRun/dayHandle');// 每日定期执行
//Route::rule('api/:version/auto/w', 'api/v1.AutoRun/weekHandle');// 每周定期执行
//Route::rule('api/:version/auto/m', 'api/v1.AutoRun/monthHandle');// 每月定期执行
Route::rule('api/:version/auto/i', 'api/v1.AutoRun/minuteHandle');// 每分钟定期执行

Route::rule('api/:version/auto/clear', 'api/v1.AutoRun/clearDb');// 清除数据表


Route::rule('api/:version/auto/sendTmp', 'api/v1.AutoRun/sendTmp');// 打卡消息推送

// Notify
Route::rule('api/:version/notify/receive', 'api/v1.Notify/receive');// 客服消息推送
Route::rule('api/:version/notify/auth', 'api/v1.Notify/getAuth');// 

// Page 
Route::rule('api/:version/page/app', 'api/v1.Page/app');
Route::rule('api/:version/page/group', 'api/v1.Page/group');
Route::rule('api/:version/page/prop', 'api/v1.Page/prop');
Route::rule('api/:version/page/myprop', 'api/v1.Page/myprop');
Route::rule('api/:version/page/game', 'api/v1.Page/game');
Route::rule('api/:version/page/groupMass', 'api/v1.Page/groupMass');
Route::rule('api/:version/page/wxgroup', 'api/v1.Page/wxgroup');// 
Route::rule('api/:version/page/hongbao', 'api/v1.Page/hongbao');// 新春红包
Route::rule('api/:version/page/sendHongbao', 'api/v1.Page/sendHongbao');// 新春红包发
Route::rule('api/:version/page/getBox', 'api/v1.Page/getBox');// 新春红包发
Route::rule('api/:version/page/getHongbaoDouble', 'api/v1.Page/getHongbaoDouble');// 新春红包双倍

Route::rule('api/:version/page/fudai', 'api/v1.Page/fudai');// 我的福袋列表
Route::rule('api/:version/page/sendFudai', 'api/v1.Page/sendFudai');// 送福袋
Route::rule('api/:version/page/getFudai', 'api/v1.Page/getFudai');// 开福袋
Route::rule('api/:version/page/getFudaiDouble', 'api/v1.Page/getFudaiDouble');// 开福袋双倍

// Remote
Route::rule('api/:version/remote/zuimei', 'api/v1.Remote/zuimei');

// Star
Route::rule('api/:version/star/info', 'api/v1.Star/getInfo');// 获取单个明星信息
Route::rule('api/:version/star/chart', 'api/v1.Star/getChart');// 获取明星圈子聊天内容
Route::rule('api/:version/star/joinchart', 'api/v1.Star/joinChart');// 加入明星聊天室socket
Route::rule('api/:version/star/leavechart', 'api/v1.Star/leaveChart');// 离开明星聊天室socket
Route::rule('api/:version/star/sendmsg', 'api/v1.Star/sendMsg');// 在圈子中发言
Route::rule('api/:version/star/sendhot', 'api/v1.Star/sendHot');// 给明星贡献人气
Route::rule('api/:version/star/follow', 'api/v1.Star/follow');// 加入明星圈子
Route::rule('api/:version/star/steal', 'api/v1.Star/steal');// 偷花
Route::rule('api/:version/star/automaticSteal', 'api/v1.Star/automaticSteal');// 开启自动偷花
Route::rule('api/:version/star/dynamic', 'api/v1.Star/dynamic');// 动态

// StarRank
Route::rule('api/:version/star/rank', 'api/v1.StarRank/getRankList');// 明星排名
Route::rule('api/:version/star/rank/history', 'api/v1.StarRank/getRankHistory');// 明星排名历史

// Banner
Route::rule('api/:version/banner/list', 'api/v1.Banner/getList');// 获取轮播图列表

// User
Route::rule('api/:version/user/login', 'api/v1.User/login');// 登录

Route::rule('api/:version/user/saveinfo', 'api/v1.User/saveInfo');// 保存用户详细信息
Route::rule('api/:version/user/savephone', 'api/v1.User/savePhone');// 保存用户手机号
Route::rule('api/:version/user/info', 'api/v1.User/getInfo');// 获取用户详细信息
Route::rule('api/:version/user/currency', 'api/v1.User/getCurrency');// 获取用户货币
Route::rule('api/:version/user/item', 'api/v1.User/getItem');// 获取用户道具

Route::rule('api/:version/user/star', 'api/v1.User/getStar');// 用户加入的爱豆
Route::rule('api/:version/user/invitlist', 'api/v1.User/invitList');// 用户邀请列表
Route::rule('api/:version/user/invitaward', 'api/v1.User/invitAward');// 用户邀请奖励
Route::rule('api/:version/user/steal/time', 'api/v1.User/stealTime');// 用户偷花倒计时
Route::rule('api/:version/user/rank', 'api/v1.UserRank/getRank');// 用户贡献排行

Route::rule('api/:version/user/father', 'api/v1.Share/father');// 师徒关系
Route::rule('api/:version/user/sonearn', 'api/v1.Share/sonEarn');// 领取徒弟收益
Route::rule('api/:version/user/checkearn', 'api/v1.Share/checkEarn');// 检查是否有徒弟收益
Route::rule('api/:version/user/breakFather', 'api/v1.Share/breakFather');// 脱离师傅
Route::rule('api/:version/user/reset/father', 'api/v1.UserFather/resetFather');// 反出师门
Route::rule('api/:version/user/reset/son', 'api/v1.UserFather/resetSon');// 逐出师门
Route::rule('api/:version/user/fromFather', 'api/v1.UserFather/fromFather');// 拜师
Route::rule('api/:version/user/acceptSon', 'api/v1.UserFather/acceptSon');// 收徒
Route::rule('api/:version/user/fatherRank', 'api/v1.UserFather/fatherRank');// 师傅排行及未拜师用户排行
Route::rule('api/:version/user/applyList', 'api/v1.UserFather/applyList');// 师徒申请列表
Route::rule('api/:version/user/applyDeal', 'api/v1.UserFather/applyDeal');// 师徒申请处理

Route::rule('api/:version/user/sayworld', 'api/v1.User/sayworld');// 世界喊话
Route::rule('api/:version/user/report', 'api/v1.User/report');// 举报世界喊话
Route::rule('api/:version/user/bind', 'api/v1.User/bindClientId');// 绑定client_id

Route::rule('api/:version/user/saveformid', 'api/v1.Ext/saveFormId');// 保存formId

Route::rule('api/:version/user/exit', 'api/v1.User/exit');// 退出偶像圈
Route::rule('api/:version/user/exitInfo', 'api/v1.User/exitInfo');// 退团信息
Route::rule('api/:version/user/signin', 'api/v1.User/signin');// 连续签到

Route::rule('api/:version/user/recharge', 'api/v1.User/recharge');// 礼物兑换能量

Route::rule('api/:version/user/addFriend', 'api/v1.User/addFriend');// 加好友
Route::rule('api/:version/user/delFriend', 'api/v1.User/delFriend');// 删好友
Route::rule('api/:version/user/sendStoneToOther', 'api/v1.User/sendStoneToOther');// 送灵丹给别人
Route::rule('api/:version/user/sendItemToOther', 'api/v1.User/sendItemToOther');// 送礼物给他人
Route::rule('api/:version/user/forbidden', 'api/v1.User/forbidden');// 禁言
Route::rule('api/:version/user/level', 'api/v1.User/level');// 用户等级

// Share
Route::rule('api/:version/share/mass', 'api/v1.Share/mass');// 分享集结
Route::rule('api/:version/share/start', 'api/v1.Share/massStart');// 分享集结开始
Route::rule('api/:version/share/joinmass', 'api/v1.Share/massJoin');// 分享集结加入
Route::rule('api/:version/share/settlemass', 'api/v1.Share/massSettle');// 集结结算
Route::rule('api/:version/share/group_award', 'api/v1.Share/groupAward');// 群奖励
Route::rule('api/:version/share/group/add', 'api/v1.Share/groupAdd');// 新增群信息
Route::rule('api/:version/share/group/join', 'api/v1.Share/groupMassJoin');// 加入群集结
Route::rule('api/:version/share/group/settle', 'api/v1.Share/groupMassSettle');// 群集结结算
Route::rule('api/:version/share/group/groupDayReback', 'api/v1.Share/groupDayReback');// 群贡献奖励

Route::rule('api/:version/page/sendSms', 'api/v1.Page/sendSms');//发送验证码

// Sprite
Route::rule('api/:version/sprite', 'api/v1.UserSprite/info');// 精灵信息
Route::rule('api/:version/sprite/settle', 'api/v1.UserSprite/settle');// 精灵收益结算
Route::rule('api/:version/sprite/upgrade', 'api/v1.UserSprite/upgrade');// 精灵升级
Route::rule('api/:version/sprite/skill', 'api/v1.UserSprite/skill');// 精灵技能
Route::rule('api/:version/sprite/shortEarn', 'api/v1.UserSprite/shortEarn');// 使用精灵加速卡
Route::rule('api/:version/sprite/rank', 'api/v1.UserSprite/rankList');// 精灵产量排行
Route::rule('api/:version/sprite/zanGod', 'api/v1.UserSprite/zanGod');//膜拜大神
Route::rule('api/:version/sprite/getHandBook', 'api/v1.UserSprite/getHandBook');// 精灵图鉴
Route::rule('api/:version/sprite/switchImage', 'api/v1.UserSprite/switchImage');// 精灵换肤

//精灵背景
Route::rule('api/:version/sprite/sprite_bg_list', 'api/v1.UserSprite/sprite_bg_list');//精灵背景列表
Route::rule('api/:version/sprite/sprite_bg_use', 'api/v1.UserSprite/sprite_bg_use');// 精灵背景使用
Route::rule('api/:version/sprite/sprite_bg_buy', 'api/v1.UserSprite/sprite_bg_buy');// 精灵背景购买
Route::rule('api/:version/sprite/sprite_bg_unlock', 'api/v1.UserSprite/sprite_bg_unlock');// 精灵背景解锁
Route::rule('api/:version/sprite/sprite_bg_upload_img', 'api/v1.UserSprite/sprite_bg_upload_img');// 精灵背景上传头像

// Pay
Route::rule('api/:version/pay/order', 'api/v1.Payment/order');// 支付下单
Route::rule('api/:version/pay/notify', 'api/v1.Payment/notify');// 支付通知
Route::rule('api/:version/pay/goods', 'api/v1.Payment/goods');// 商品列表

// Props
Route::rule('api/:version/prop/exchange', 'api/v1.Prop/exchange');// 灵丹兑换道具

// Task
Route::rule('api/:version/task', 'api/v1.Task/index');// 任务
Route::rule('api/:version/task/settle', 'api/v1.Task/settle');// 任务领取
Route::rule('api/:version/task/weibo', 'api/v1.Task/weibo');// 提交微博链接
Route::rule('api/:version/sharetext', 'api/v1.Task/sharetext');// 分享文字 
Route::rule('api/:version/badge/use', 'api/v1.Task/badgeUse');// 徽章使用

// Ext
Route::rule('api/:version/config', 'api/v1.Ext/config');// 配置信息

Route::rule('api/:version/active/info', 'api/v1.Ext/getActiveInfo');// 活动信息
Route::rule('api/:version/active/card', 'api/v1.Ext/setCard');// 打卡
Route::rule('api/:version/active/list', 'api/v1.Ext/activeList');// 活动列表
Route::rule('api/:version/active/userrank', 'api/v1.Ext/userRank');// 用户打卡排名

Route::rule('api/:version/yingyuan/fix', 'api/v1.Ext/yingYuanFix');// 打卡
Route::rule('api/:version/yingyuan/card', 'api/v1.Ext/setYingYuanCard');// 打卡
Route::rule('api/:version/yingyuan/info', 'api/v1.Ext/getYingyuan');// 打卡详情
Route::rule('api/:version/yingyuan/list', 'api/v1.Ext/getYingyuanList');// 打卡列表

Route::rule('api/:version/ext/log', 'api/v1.Ext/log');// 用户日志

//signin
Route::rule('api/:version/rank/getSignin', 'api/v1.Signin/getSignin');// 获取签到信息
Route::rule('api/:version/rank/signin', 'api/v1.Signin/joinSignin');// 签到



// 
Route::rule('api/:version/uploadIndex', 'api/v1.Ext/uploadIndex');// 文件上传
Route::rule('api/:version/upload', 'api/v1.Ext/upload');// 文件上传

Route::rule('api/:version/Fanclub/join', 'api/v1.Ext/FanclubJoin');// 后援会
Route::rule('api/:version/ext/fanclubList', 'api/v1.Ext/fanclubList');// 后援会列表
Route::rule('api/:version/ext/joinFanclub', 'api/v1.Ext/joinFanclub');// 加入后援会
Route::rule('api/:version/ext/exitFanclub', 'api/v1.Ext/exitFanclub');// 退出后援会

// Treasure
Route::rule('api/:version/treasure/settle', 'api/v1.Treasure/settle');// 寻宝结算
Route::rule('api/:version/treasure', 'api/v1.Treasure/index');// 
Route::rule('api/:version/lottery/start', 'api/v1.Treasure/start');// 

// Article
Route::rule('api/:version/article/formart', 'api/v1.Article/formart');// 文章格式化
Route::rule('api/:version/article', 'api/v1.Article/getArticle');// 获取文章
Route::rule('api/:version/article/list', 'api/v1.Article/getList');// 获取文章列表

//gift
Route::rule('api/:version/page/gift_package', 'api/v1.Page/giftPackage');// 礼物背包
Route::rule('api/:version/page/gift_num', 'api/v1.Page/giftCount');// 礼物数量

Route::rule('api/:version/subscribe', 'api/v1.Subscribe/index');// 订阅消息

// Prop
Route::rule('api/:version/prop/use', 'api/v1.Prop/use');// 使用道具

// Open 
Route::rule('api/:version/open/upload', 'api/v1.Open/upload');// 上传开屏
Route::rule('api/:version/open/select', 'api/v1.Open/select');// 开屏图列表
Route::rule('api/:version/open/settle', 'api/v1.Open/settle');// 开屏图数据结算
Route::rule('api/:version/open/today', 'api/v1.Open/today');// 今日当前开屏

// Android
Route::rule('api/:version/android/createView', 'api/v1.Android/createView'); // 
Route::rule('api/:version/android/create', 'api/v1.Android/create');// 新建一个机器人用户
Route::rule('api/:version/android/sendHotView', 'api/v1.Android/sendHotView'); // 
Route::rule('api/:version/android/sendHot', 'api/v1.Android/sendHot');// 让一个机器人打榜
Route::rule('api/:version/android/infoView', 'api/v1.Android/infoView'); // 
Route::rule('api/:version/android/addHot', 'api/v1.Android/addHot'); // 

Route::rule('api/:version/page/redress', 'api/v1.Page/redress'); // 补偿

// TreasureBox
Route::rule('api/:version/treasureBox/index', 'api/v1.TreasureBox/index');// 宝箱列表信息
Route::rule('api/:version/treasureBox/info', 'api/v1.TreasureBox/info');// 宝箱信息
Route::rule('api/:version/treasureBox/open', 'api/v1.TreasureBox/open'); // 打开宝箱
Route::rule('api/:version/treasureBox/openOther', 'api/v1.TreasureBox/openOther'); // 打开其他人宝箱，好友列表帮助开宝箱
Route::rule('api/:version/treasureBox/log', 'api/v1.TreasureBox/log'); // 宝箱记录
Route::rule('api/:version/treasureBox/getOpenBoxRank', 'api/v1.TreasureBox/getOpenBoxRank'); // 全服开箱排行榜

Route::rule('api/:version/active/invite_new_info', 'api/v1.ActiveInvite/invite_new_info');// 用户拉新活动信息
Route::rule('api/:version/active/invite_steps_reward', 'api/v1.ActiveInvite/invite_steps_reward');// 点击领取拉新奖励
Route::rule('api/:version/active/get_invit_energy', 'api/v1.ActiveInvite/get_invit_energy');// 点击领取拉新电量
Route::rule('api/:version/active/invite_group_invite_rank', 'api/v1.ActiveInvite/groupInviteRank');// 圈子拉新排名
Route::rule('api/:version/active/invite_reward_log', 'api/v1.ActiveInvite/invite_reward_log');// 领取拉新奖励记录
Route::rule('api/:version/active/invite_user_log', 'api/v1.ActiveInvite/invite_user_log');// 拉新人员记录

//守护
Route::rule('api/:version/active/guardian_info', 'api/v1.ActivityGuardian/getList');// 守护列表信息
Route::rule('api/:version/active/guardian_star', 'api/v1.ActivityGuardian/guardian');// 开始守护
Route::rule('api/:version/active/guardian_rank', 'api/v1.ActivityGuardian/rankList');// 守护排行