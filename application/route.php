<?php

use think\Route;

// Test
Route::rule('test', 'api/Test/index');
// Clearner
Route::rule('clean', 'api/v1.Cleaner/index');

// AutoRun 
Route::rule('api/:version/auto', 'api/AutoRun/index');// 每周执行

// Star
Route::rule('api/:version/star/info', 'api/v1.Star/getInfo');// 获取单个明星信息
Route::rule('api/:version/star/chart', 'api/v1.Star/getChart');// 获取明星圈子聊天内容
Route::rule('api/:version/star/joinchart', 'api/v1.Star/joinChart');// 加入明星聊天室socket
Route::rule('api/:version/star/leavechart', 'api/v1.Star/leaveChart');// 离开明星聊天室socket
Route::rule('api/:version/star/sendmsg', 'api/v1.Star/sendMsg');// 在圈子中发言
Route::rule('api/:version/star/sendhot', 'api/v1.Star/sendHot');// 给明星贡献人气
Route::rule('api/:version/star/follow', 'api/v1.Star/follow');// 加入明星圈子
Route::rule('api/:version/star/steal', 'api/v1.Star/steal');// 偷花
Route::rule('api/:version/star/dynamic', 'api/v1.Star/dynamic');// 动态

// StarRank
Route::rule('api/:version/star/rank', 'api/v1.StarRank/getRankList');// 明星排名
Route::rule('api/:version/star/rank/history', 'api/v1.StarRank/getRankHistory');// 明星排名历史

// Banner
Route::rule('api/:version/banner/list', 'api/v1.Banner/getList');// 获取轮播图列表

// User
Route::rule('api/:version/user/login', 'api/v1.User/login');// 登录

Route::rule('api/:version/user/saveinfo', 'api/v1.User/saveInfo');// 保存用户详细信息
Route::rule('api/:version/user/info', 'api/v1.User/getInfo');// 获取用户详细信息
Route::rule('api/:version/user/currency', 'api/v1.User/getCurrency');// 获取用户货币
Route::rule('api/:version/user/item', 'api/v1.User/getItem');// 获取用户道具

Route::rule('api/:version/user/star', 'api/v1.User/getStar');// 用户加入的爱豆
Route::rule('api/:version/user/invitlist', 'api/v1.User/invitList');// 用户邀请列表
Route::rule('api/:version/user/invitaward', 'api/v1.User/invitAward');// 用户邀请奖励
Route::rule('api/:version/user/steal/time', 'api/v1.User/stealTime');// 用户偷花倒计时
Route::rule('api/:version/user/rank', 'api/v1.UserRank/getRank');// 用户贡献排行

Route::rule('api/:version/user/father', 'api/v1.Share/father');// 师徒关系
Route::rule('api/:version/user/sonearn', 'api/v1.Share/sonEarn');// 获取徒弟收益
Route::rule('api/:version/user/checkearn', 'api/v1.Share/checkEarn');// 检查是否有土地收益

Route::rule('api/:version/user/sayworld', 'api/v1.User/sayworld');// 世界喊话
Route::rule('api/:version/user/bind', 'api/v1.User/bindClientId');// 绑定client_id

Route::rule('api/:version/user/saveformid', 'api/v1.Ext/saveFormId');// 保存formId

Route::rule('api/:version/user/exit', 'api/v1.User/exit');// 退出偶像圈

// Share
Route::rule('api/:version/share/mass', 'api/v1.Share/mass');// 分享集结
Route::rule('api/:version/share/start', 'api/v1.Share/massStart');// 分享集结开始
Route::rule('api/:version/share/joinmass', 'api/v1.Share/massJoin');// 分享集结加入
Route::rule('api/:version/share/settlemass', 'api/v1.Share/massSettle');// 集结结算


// Sprite
Route::rule('api/:version/sprite', 'api/v1.UserSprite/info');// 精灵信息
Route::rule('api/:version/sprite/settle', 'api/v1.UserSprite/settle');// 精灵收益结算
Route::rule('api/:version/sprite/upgrade', 'api/v1.UserSprite/upgrade');// 精灵升级
Route::rule('api/:version/sprite/skill', 'api/v1.UserSprite/skill');// 精灵技能

// Pay
Route::rule('api/:version/pay/order', 'api/v1.Payment/order');// 支付下单
Route::rule('api/:version/pay/notify', 'api/v1.Payment/notify');// 支付通知
Route::rule('api/:version/pay/goods', 'api/v1.Payment/goods');// 商品列表

// Task
Route::rule('api/:version/task', 'api/v1.Task/index');// 任务
Route::rule('api/:version/task/settle', 'api/v1.Task/settle');// 任务领取
Route::rule('api/:version/task/weibo', 'api/v1.Task/weibo');// 提交微博链接
Route::rule('api/:version/sharetext', 'api/v1.Task/sharetext');// 分享文字

// Ext
Route::rule('api/:version/config', 'api/v1.Ext/config');// 配置信息

// Treasure
Route::rule('api/:version/treasure/settle', 'api/v1.Treasure/settle');// 寻宝结算
Route::rule('api/:version/treasure', 'api/v1.Treasure/index');// 

// Article
Route::rule('api/:version/article', 'api/v1.Article/getArticle');// 获取文章
Route::rule('api/:version/article/list', 'api/v1.Article/getList');// 获取文章列表





