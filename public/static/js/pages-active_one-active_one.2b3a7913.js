(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-active_one-active_one"],{"0111":function(t,e,a){var i=a("24fb");e=i(!1),e.push([t.i,".button[data-v-40190e39]{color:#6b4a39;-webkit-transition:.3s;transition:.3s;border-radius:%?40?%}.button.scale[data-v-40190e39]{-webkit-transform:scale(.7);transform:scale(.7)}.button.default[data-v-40190e39]{color:#fff;background:-webkit-linear-gradient(left top,#ff3a8a 20%,#fa6c9f 82%,#ffe140);background:linear-gradient(to right bottom,#ff3a8a 20%,#fa6c9f 82%,#ffe140);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3)}.button.big[data-v-40190e39]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2dxu4igebg303v02cgld.gif) 50% no-repeat/100% 100%}.button.success[data-v-40190e39]{color:#fff;background:-webkit-linear-gradient(left top,#44ea76 0,#39fad7 82%,#39fad7);background:linear-gradient(to right bottom,#44ea76 0,#39fad7 82%,#39fad7);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3)}.button.disable[data-v-40190e39]{color:#fff;background:-webkit-linear-gradient(left top,#aaa,#666);background:linear-gradient(to right bottom,#aaa,#666);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3)}.button.fangde[data-v-40190e39]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2jwmn4sshg305v05vwea.gif) 50% no-repeat/100% 100%}.button.css[data-v-40190e39]{color:#fff;background:-webkit-linear-gradient(left top,#ff3a8a 20%,#fa6c9f 82%,#ffe140);background:linear-gradient(to right bottom,#ff3a8a 20%,#fa6c9f 82%,#ffe140);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3)}.button.palePink[data-v-40190e39]{color:#fff;background:url(https://mmbiz.qpic.cn/mmbiz_png/CbJC0icY3EzYDK6IibJZCjKRgkicZiapsgltvnGHD42bLH7zibMs071OjaqnRicjpLucbuGyYXnxVW5Ro99ppCvPvp5w/0) 50%/100% 100% no-repeat}.button.pink[data-v-40190e39]{color:#fff;background:url(https://mmbiz.qpic.cn/mmbiz_png/CbJC0icY3EzZkic73fibNnibpIvllj1icjrN7gGdaQiaejibgHicJ6hBDTmQcprH8ibdbskrzvoqaBkbX8cpR9WZfDicRDfA/0) 50%/100% 100% no-repeat}.button.golden[data-v-40190e39]{color:#fff;background:url(https://mmbiz.qpic.cn/mmbiz_png/CbJC0icY3EzZkic73fibNnibpIvllj1icjrN7gdbcBHONe18HPVfJTuhBpDBqlcTYloxiblEdhzLDlZlfLuF5xjicQ4uw/0) 50%/100% 100% no-repeat}.button.color[data-v-40190e39]{background-color:#efccc8;border-radius:%?60?%;box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3)}",""]),t.exports=e},"02fd":function(t,e,a){"use strict";var i,n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"active_one-container"},[t.activeInfo.bg_img?a("v-uni-view",{staticClass:"top-container"},[a("v-uni-image",{staticClass:"bg",attrs:{src:t.activeInfo.bg_img,mode:"aspectFill"}})],1):t._e(),a("v-uni-view",{staticClass:"active-center-container"},[a("v-uni-view",{staticClass:"top-wrap"},[a("v-uni-view",{staticClass:"left"},[a("v-uni-view",{staticClass:"left-1"},[t._v("为爱解锁")]),a("v-uni-view",{staticClass:"left-2"},[t._v("剩余："+t._s(t.activeInfo.left_time||""))])],1),t.activeInfo.self.is_card_today?a("v-uni-view",{staticClass:"right",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.card()}}},[a("v-uni-image",{attrs:{src:"/static/image/guild/card-c.png",mode:""}}),a("v-uni-view",{staticClass:"text"},[a("v-uni-view",{staticClass:"t"},[t._v("已打卡")]),a("v-uni-view",{staticClass:"t",staticStyle:{"font-size":"24upx"}},[t._v(t._s(t.activeInfo.self.total_clocks||0)+"/"+t._s(t.activeInfo.min_days||0)+"天")])],1)],1):a("v-uni-view",{staticClass:"right",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.card()}}},[a("v-uni-image",{attrs:{src:"/static/image/guild/card.png",mode:""}}),a("v-uni-view",{staticClass:"text"},[a("v-uni-view",{staticClass:"t"},[t._v("打卡")]),a("v-uni-view",{staticClass:"t",staticStyle:{"font-size":"24upx"}},[t._v(t._s(t.activeInfo.self.total_clocks||0)+"/"+t._s(t.activeInfo.min_days||0)+"天")])],1)],1)],1),a("v-uni-view",{staticClass:"progress-wrap"},[a("v-uni-view",{staticClass:"title"},[t._v(t._s(t.activeInfo.title))]),a("v-uni-view",{staticClass:"progress"},[a("v-uni-progress",{attrs:{activeColor:"#e02d2d","stroke-width":"10",backgroundColor:"#f9f9f9",percent:t.activeInfo.progress.join_people/t.activeInfo.target_people*100}}),a("v-uni-text",[t._v("参与人数"+t._s(t.activeInfo.progress.join_people||0))])],1),a("v-uni-view",{staticClass:"progress"},[a("v-uni-progress",{attrs:{activeColor:"#962de0","stroke-width":"10",backgroundColor:"#f9f9f9",percent:t.activeInfo.progress.complete_people/t.activeInfo.target_people*100}}),a("v-uni-text",[t._v("完成人数"+t._s(t.activeInfo.progress.complete_people||0))])],1),a("v-uni-view",{staticClass:"bottom-text"},[a("v-uni-view",[t._v("目标人数："),a("v-uni-text",[t._v(t._s(t.activeInfo.target_people))])],1),a("v-uni-view",[t._v("已完成人数："),a("v-uni-text",[t._v(t._s(t.activeInfo.progress.complete_people||0))])],1),a("v-uni-view",[t._v("还需人数："),a("v-uni-text",[t._v(t._s(t.activeInfo.progress.complete_people?t.activeInfo.target_people-t.activeInfo.progress.complete_people:t.activeInfo.target_people))])],1)],1)],1),a("v-uni-view",{staticClass:"notice-container"},[a("v-uni-view",{staticClass:"article-name"},[t._v("为爱解锁活动说明")]),t._l(t.activeInfo.notice,(function(e,i){return[a("v-uni-view",{key:i+"_0",staticClass:"article-group"},[e.title?a("v-uni-view",{staticClass:"article-title"},[t._v(t._s(e.title))]):t._e(),t._l(e.content,(function(i,n){return e.content.length>0?a("v-uni-text",{key:n,staticClass:"article-content",attrs:{decode:!0}},[t._v(t._s(i))]):t._e()}))],2),e.image?a("v-uni-image",{key:i+"_1",staticClass:"article-image",attrs:{src:e.image,mode:"widthFix"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.preImg(e.image)}}}):t._e()]}))],2)],1),a("v-uni-view",{staticClass:"rank-list-container"},[a("v-uni-view",{staticClass:"title"},[t._v("粉丝应援榜")]),a("v-uni-view",{staticClass:"scroll-view"},[t._l(t.userRank,(function(e,i){return a("v-uni-view",{key:i,staticClass:"item-wrap"},[a("v-uni-image",{staticClass:"avatar",attrs:{src:e.user.avatarurl,mode:"aspectFill"}}),a("v-uni-view",{staticClass:"text-wrap"},[a("v-uni-view",{staticClass:"name"},[t._v(t._s(e.user.nickname))]),a("v-uni-view",{staticClass:"card"},[t._v("累计打卡天数："+t._s(e.total_clocks)+"天")])],1),a("v-uni-view",{staticClass:"rank flex-set"},[t._v(t._s(i+1))])],1)})),0==t.userRank.length?a("v-uni-view",{staticClass:"item-wrap flex-set"},[t._v("还没有人来打卡")]):t._e()],2)],1),"cardOver"==t.modal?a("modalComponent",{attrs:{title:" ",headimg:"false"},on:{closeModal:function(e){arguments[0]=e=t.$handleEvent(e),t.modal=""}}},[a("v-uni-view",{staticClass:"modal-container flex-set"},[a("v-uni-view",{staticClass:"top-wrap"},[a("v-uni-image",{staticClass:"avatar",attrs:{src:t.star.avatar,mode:"aspectFill"}}),a("v-uni-view",[t._v("为爱解锁应援金活动")]),t.activeInfo.progress.join_people<t.activeInfo.target_people?[a("v-uni-view",{},[t._v("已有"),a("v-uni-text",{staticStyle:{color:"#F00"}},[t._v(t._s(t.activeInfo.progress.join_people))]),t._v("人参与")],1)]:[a("v-uni-view",{},[t._v("已有"),a("v-uni-text",{staticStyle:{color:"#007EFF"}},[t._v(t._s(t.activeInfo.progress.complete_people))]),t._v("人完成,还差"),a("v-uni-text",{staticStyle:{color:"#F00"}},[t._v(t._s(t.activeInfo.target_people-t.activeInfo.progress.complete_people))]),t._v("人完成")],1)]],2),a("v-uni-view",{staticClass:"progress-wrap"},[a("v-uni-view",{staticClass:"progress"},[a("v-uni-progress",{attrs:{activeColor:"#e02d2d","stroke-width":"10",backgroundColor:"#f9f9f9",percent:t.activeInfo.progress.join_people/t.activeInfo.target_people*100}}),a("v-uni-text",[t._v("参与人数"+t._s(t.activeInfo.progress.join_people||0))])],1),a("v-uni-view",{staticClass:"progress"},[a("v-uni-progress",{attrs:{activeColor:"#962de0","stroke-width":"10",backgroundColor:"#f9f9f9",percent:t.activeInfo.progress.complete_people/t.activeInfo.target_people*100}}),a("v-uni-text",[t._v("完成人数"+t._s(t.activeInfo.progress.complete_people||0))])],1),a("v-uni-view",{staticClass:"bottom-text"},[a("v-uni-view",[t._v("目标人数："),a("v-uni-text",[t._v(t._s(t.activeInfo.target_people))])],1),a("v-uni-view",[t._v("已完成人数："),a("v-uni-text",[t._v(t._s(t.activeInfo.progress.complete_people||0))])],1),a("v-uni-view",[t._v("还需人数："),a("v-uni-text",[t._v(t._s(t.activeInfo.progress.complete_people?t.activeInfo.target_people-t.activeInfo.progress.complete_people:t.activeInfo.target_people))])],1)],1)],1),a("v-uni-view",{staticClass:"btn-wrap"},["MP-WEIXIN"==t.$app.getData("platform")?a("v-uni-button",{staticClass:"fsend-btn flex-set",attrs:{"open-type":"share"}},[a("v-uni-image",{attrs:{src:"/static/image/wxq.png",mode:"widthFix"}}),a("v-uni-view",[t._v("微信群")])],1):t._e(),"MP-QQ"==t.$app.getData("platform")?a("v-uni-button",{staticClass:"fsend-btn flex-set",attrs:{"open-type":"share"}},[a("v-uni-image",{attrs:{src:"/static/image/qq.png",mode:"widthFix"}}),a("v-uni-view",[t._v("QQ群")])],1):t._e(),a("v-uni-view",{staticClass:"fsend-btn flex-set",attrs:{"open-type":"share"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.modal="otherShareW"}}},[a("v-uni-image",{attrs:{src:"/static/image/weibo.png",mode:"widthFix"}}),a("v-uni-view",[t._v("微博")])],1),"1"==t.$app.getData("config").pyq_switch?a("v-uni-view",{staticClass:"fsend-btn flex-set",attrs:{"open-type":"share"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.drawCanvas(),t.modal="otherShareP"}}},[a("v-uni-image",{attrs:{src:"/static/image/pyq.png",mode:"widthFix"}}),a("v-uni-view",[t._v("朋友圈")])],1):t._e()],1)],1)],1):t._e(),a("v-uni-canvas",{staticClass:"canvas",attrs:{"canvas-id":"mycanvas"}}),"otherShareW"==t.modal?a("v-uni-view",{staticClass:"canvas-container flex-set"},[a("v-uni-view",{staticClass:"close-btn flex-set iconfont iconclose",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.modal=""}}}),a("v-uni-view",{staticClass:"wrapper flex-set"},[a("v-uni-image",{attrs:{src:"http://mmbiz.qpic.cn/mmbiz_gif/iaPhFibaNbpLSV7UadegJZuSRW9g4rKDYZjDICZhLmouhT16m4TNPagic3McRuLQ797d3m16iafI3OXjm1JOKC4OaA/0",mode:"scaleToFill"}}),a("v-uni-view",{staticClass:"btn flex-set",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.getShareText(2)}}},[t._v("点击复制微博格式")])],1)],1):t._e(),"otherShareP"==t.modal?a("v-uni-view",{staticClass:"canvas-container flex-set"},[a("v-uni-view",{staticClass:"close-btn flex-set iconfont iconclose",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.modal=""}}}),a("v-uni-view",{staticClass:"wrapper flex-set"},[a("v-uni-image",{attrs:{src:"http://mmbiz.qpic.cn/mmbiz_gif/iaPhFibaNbpLSV7UadegJZuSRW9g4rKDYZ1O2agUjUWuKibTick4mXTql7LkXf6AcsPeSlz5jEibu16QgPOJUZFgwXw/0",mode:"scaleToFill"}}),a("v-uni-view",{staticClass:"btn flex-set",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.getShareText(3),t.saveCanvas()}}},[t._v("复制文字，保存图片到相册，发朋友圈")])],1)],1):t._e()],1)},o=[];a.d(e,"b",(function(){return n})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return i}))},"0386":function(t,e,a){var i=a("24fb");e=i(!1),e.push([t.i,".container[data-v-a9ec2604]{position:fixed;top:0;left:0;right:0;bottom:0;z-index:99;background-color:rgba(0,0,0,.8);-webkit-transition:.1s;transition:.1s;opacity:0;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column}.container .modal-container.send[data-v-a9ec2604]{background-color:#f7e8f1}.container .modal-container[data-v-a9ec2604]{margin-top:%?90?%;width:%?600?%;min-height:%?730?%;box-shadow:0 1px 2px rgba(0,0,0,.3);border-radius:%?20?%;background-color:#fff;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column}.container .modal-container .top-wrapper[data-v-a9ec2604]{width:100%;height:%?95?%;position:relative}.container .modal-container .top-wrapper .title-bg[data-v-a9ec2604]{position:absolute;height:100%;width:100%;border-top-left-radius:%?20?%;border-top-right-radius:%?20?%}.container .modal-container .top-wrapper .title-bg.linear[data-v-a9ec2604]{background:-webkit-linear-gradient(top,#e5b4b0,#f6e3df);background:linear-gradient(180deg,#e5b4b0,#f6e3df)}.container .modal-container .top-wrapper .title[data-v-a9ec2604]{font-size:%?34?%;position:absolute;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%);left:%?30?%}.container .modal-container .top-wrapper .center-img[data-v-a9ec2604]{width:%?100?%;height:%?100?%;position:absolute;left:50%;-webkit-transform:translateX(-50%);transform:translateX(-50%);top:-30%}.container .modal-container .content[data-v-a9ec2604]{width:100%;-webkit-box-flex:1;-webkit-flex:1;flex:1;position:relative}.container .close-btn[data-v-a9ec2604]{width:%?80?%;height:%?80?%;margin-top:%?10?%;z-index:10;border-radius:50%;background-color:rgba(0,0,0,.3);color:#fff;font-size:%?45?%}.container.show[data-v-a9ec2604]{opacity:1}.container.show .modal-container[data-v-a9ec2604]{-webkit-animation:popIn-data-v-a9ec2604 .4s ease-in-out .2s;animation:popIn-data-v-a9ec2604 .3s ease-out}@-webkit-keyframes popIn-data-v-a9ec2604{0%{-webkit-transform:scale3d(0,0,0);transform:scale3d(.5,.5,.5);opacity:0}50%{-webkit-animation-timing-function:cubic-bezier(.47,0,.745,.715);animation-timing-function:cubic-bezier(.47,0,.745,.715)}100%{-webkit-transform:scaleX(1);transform:scaleX(1);-webkit-animation-timing-function:cubic-bezier(.25,.46,.45,.94);animation-timing-function:cubic-bezier(.25,.46,.45,.94);opacity:1}}@keyframes popIn-data-v-a9ec2604{0%{-webkit-transform:scale3d(0,0,0);transform:scale3d(.5,.5,.5);opacity:0}50%{-webkit-animation-timing-function:cubic-bezier(.47,0,.745,.715);animation-timing-function:cubic-bezier(.47,0,.745,.715)}100%{-webkit-transform:scaleX(1);transform:scaleX(1);-webkit-animation-timing-function:cubic-bezier(.25,.46,.45,.94);animation-timing-function:cubic-bezier(.25,.46,.45,.94);opacity:1}}",""]),t.exports=e},"1c2e":function(t,e,a){"use strict";a.r(e);var i=a("a39e"),n=a.n(i);for(var o in i)"default"!==o&&function(t){a.d(e,t,(function(){return i[t]}))}(o);e["default"]=n.a},"1e85":function(t,e,a){"use strict";var i=a("4a2f"),n=a.n(i);n.a},2159:function(t,e,a){"use strict";a.r(e);var i=a("5f68"),n=a.n(i);for(var o in i)"default"!==o&&function(t){a.d(e,t,(function(){return i[t]}))}(o);e["default"]=n.a},2278:function(t,e,a){"use strict";var i=a("d4d1"),n=a.n(i);n.a},4347:function(t,e,a){var i=a("4f01");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("0732d23a",i,!0,{sourceMap:!1,shadowMode:!1})},"4a2f":function(t,e,a){var i=a("0111");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("0096f158",i,!0,{sourceMap:!1,shadowMode:!1})},"4f01":function(t,e,a){var i=a("24fb");e=i(!1),e.push([t.i,".active_one-container[data-v-8e9c1b8c]{padding:%?20?%}.active_one-container .progress-wrap[data-v-8e9c1b8c]{padding:%?30?%;color:#666}.active_one-container .progress-wrap .title[data-v-8e9c1b8c]{font-size:%?30?%;font-weight:600;padding:%?10?% 0}.active_one-container .progress-wrap .progress[data-v-8e9c1b8c]{margin:%?14?% 0;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.active_one-container .progress-wrap .progress uni-progress[data-v-8e9c1b8c]{-webkit-box-flex:1;-webkit-flex:1;flex:1;border-radius:%?60?%;overflow:hidden;margin-right:%?20?%}.active_one-container .progress-wrap .progress uni-text[data-v-8e9c1b8c]{border-radius:%?30?%;padding:0 %?10?%;width:%?190?%;text-align:center}.active_one-container .progress-wrap .bottom-text[data-v-8e9c1b8c]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.active_one-container .progress-wrap.small[data-v-8e9c1b8c]{-webkit-transform:scale(.8);transform:scale(.8)}.active_one-container .top-container[data-v-8e9c1b8c]{height:%?300?%;margin-bottom:%?20?%}.active_one-container .top-container .bg[data-v-8e9c1b8c]{border-radius:%?20?%}.active_one-container .cardday[data-v-8e9c1b8c]{text-align:center;margin:%?20?%}.active_one-container .cardday uni-text[data-v-8e9c1b8c]{background-color:#ce797c;border-radius:%?10?%;color:#fff;width:%?40?%;display:inline-block;margin:0 %?2?%}.active_one-container .active-center-container[data-v-8e9c1b8c]{border-radius:%?30?%;overflow:hidden;box-shadow:0 %?2?% %?16?% hsla(0,0%,60%,.3)}.active_one-container .active-center-container .top-wrap[data-v-8e9c1b8c]{background:-webkit-linear-gradient(left,#ff665e,#fdb673);background:linear-gradient(90deg,#ff665e,#fdb673);height:%?90?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;color:#333}.active_one-container .active-center-container .top-wrap .left[data-v-8e9c1b8c]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.active_one-container .active-center-container .top-wrap .left .left-1[data-v-8e9c1b8c]{padding-left:%?40?%;padding-right:%?20?%;font-weight:700}.active_one-container .active-center-container .top-wrap .left .left-2[data-v-8e9c1b8c]{padding:%?20?%;font-size:%?24?%}.active_one-container .active-center-container .top-wrap .right[data-v-8e9c1b8c]{color:#ce797c;background-color:#ffd1b2;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;border-top-left-radius:%?50?%;border-bottom-left-radius:%?50?%}.active_one-container .active-center-container .top-wrap .right uni-image[data-v-8e9c1b8c]{height:%?90?%;width:%?90?%}.active_one-container .active-center-container .top-wrap .right .text[data-v-8e9c1b8c]{padding:0 %?20?%;text-align:center}.active_one-container .active-center-container .notice-container[data-v-8e9c1b8c]{color:#fff;background-color:#f1b3b0;padding:%?10?% %?20?%}.active_one-container .active-center-container .notice-container .article-name[data-v-8e9c1b8c]{text-align:center;font-size:%?32?%;font-weight:700;text-shadow:0 %?4?% %?6?% rgba(0,0,0,.3);padding:%?5?% %?10?%}.active_one-container .active-center-container .notice-container .article-group[data-v-8e9c1b8c]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:baseline;-webkit-align-items:baseline;align-items:baseline;padding:%?5?% %?10?%}.active_one-container .active-center-container .notice-container .article-group .article-title[data-v-8e9c1b8c]{border:none;font-size:%?28?%;font-weight:300;padding:0;margin:0;width:%?150?%}.active_one-container .active-center-container .notice-container .article-group .article-content[data-v-8e9c1b8c]{-webkit-box-flex:1;-webkit-flex:1;flex:1;text-indent:0;font-size:%?28?%;font-weight:300;margin:0}.active_one-container .rank-list-container[data-v-8e9c1b8c]{margin-top:%?40?%}.active_one-container .rank-list-container .title[data-v-8e9c1b8c]{border-top-left-radius:%?30?%;border-top-right-radius:%?30?%;height:%?90?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;background-color:#ff8195;font-weight:700;padding:0 %?40?%;color:#fff}.active_one-container .rank-list-container .scroll-view .item-wrap[data-v-8e9c1b8c]{min-height:%?80?%;display:-webkit-box;display:-webkit-flex;display:flex;position:relative;background-color:#fbdedb;padding:%?10?% %?40?%}.active_one-container .rank-list-container .scroll-view .item-wrap .avatar[data-v-8e9c1b8c]{width:%?100?%;height:%?100?%;border-radius:50%}.active_one-container .rank-list-container .scroll-view .item-wrap .text-wrap[data-v-8e9c1b8c]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-justify-content:space-around;justify-content:space-around;padding:0 %?40?%;width:%?350?%}.active_one-container .rank-list-container .scroll-view .item-wrap .text-wrap .card[data-v-8e9c1b8c]{color:#db7979;font-size:%?24?%}.active_one-container .rank-list-container .scroll-view .item-wrap .text-wrap .progress[data-v-8e9c1b8c]{border-radius:%?30?%;overflow:hidden}.active_one-container .rank-list-container .scroll-view .item-wrap .rank[data-v-8e9c1b8c]{width:%?70?%;height:%?70?%;position:absolute;right:%?40?%;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%);border-radius:50%;color:#fff;background-color:#b90504;font-size:%?32?%}.active_one-container .modal-container[data-v-8e9c1b8c]{-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;height:100%}.active_one-container .modal-container .progress-wrap[data-v-8e9c1b8c]{background-color:initial;border-radius:%?10?%}.active_one-container .modal-container .complete[data-v-8e9c1b8c]{width:%?120?%;height:%?120?%}.active_one-container .modal-container .title[data-v-8e9c1b8c]{font-weight:700;margin-top:%?30?%;font-size:%?34?%}.active_one-container .modal-container .text[data-v-8e9c1b8c]{padding:%?60?%;font-size:%?32?%;line-height:1.6}.active_one-container .modal-container[data-v-8e9c1b8c]{border-top-left-radius:%?20?%;border-top-right-radius:%?20?%;overflow:hidden;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start;height:100%}.active_one-container .modal-container .top-wrap[data-v-8e9c1b8c]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center;width:100%;line-height:2;font-size:%?32?%}.active_one-container .modal-container .top-wrap .avatar[data-v-8e9c1b8c]{width:%?140?%;height:%?140?%;border-radius:50%;margin:%?30?% 0}.active_one-container .modal-container .milestone-container[data-v-8e9c1b8c]{padding:0 %?20?%;width:100%;-webkit-transform:scale(.8);transform:scale(.8)}.active_one-container .modal-container .user-list[data-v-8e9c1b8c]{width:100%}.active_one-container .modal-container .user-list .user-list-avatar[data-v-8e9c1b8c]{width:%?80?%;height:%?80?%;border-radius:50%;margin:%?20?%}.active_one-container .modal-container .btn-wrap[data-v-8e9c1b8c]{margin-top:%?40?%;margin-bottom:%?40?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-justify-content:space-around;justify-content:space-around;width:100%;padding:0 %?60?%}.active_one-container .modal-container .btn-wrap .fsend-btn[data-v-8e9c1b8c]{color:#333;padding:0 %?20?%;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column}.active_one-container .modal-container .btn-wrap .fsend-btn uni-image[data-v-8e9c1b8c]{width:%?80?%;height:%?80?%}.active_one-container .modal-container .btn-wrap .save-btn[data-v-8e9c1b8c]{background-color:#ff7e00;border-radius:%?10?%;font-size:%?32?%;color:#fff;padding:0 %?20?%}.active_one-container .canvas-container[data-v-8e9c1b8c]{position:fixed;top:0;left:0;right:0;bottom:0;z-index:9;background-color:rgba(0,0,0,.9);-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column}.active_one-container .canvas-container .canvas[data-v-8e9c1b8c]{width:%?480?%;height:%?854?%}.active_one-container .canvas-container .close-btn[data-v-8e9c1b8c]{position:absolute;width:%?80?%;height:%?80?%;z-index:99;border-radius:50%;color:#fff;font-size:%?45?%;right:%?20?%;top:%?20?%}.active_one-container .canvas-container .wrapper[data-v-8e9c1b8c]{width:%?540?%;height:%?960?%;padding:%?40?%;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;background-color:#fff}.active_one-container .canvas-container .wrapper uni-image[data-v-8e9c1b8c]{width:100%;-webkit-box-flex:1;-webkit-flex:1;flex:1}.active_one-container .canvas-container .wrapper .btn[data-v-8e9c1b8c]{margin-top:%?40?%;width:100%;height:%?150?%;text-align:center;padding:%?20?%;font-size:%?40?%;color:#fff;box-shadow:0 1px 2px rgba(0,0,0,.6);background-color:#ff648c;border-radius:%?20?%}.active_one-container .canvas-container .btn-wrap[data-v-8e9c1b8c]{margin-top:%?20?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-justify-content:space-around;justify-content:space-around;width:100%;padding:0 %?60?%}.active_one-container .canvas-container .btn-wrap .fsend-btn[data-v-8e9c1b8c]{font-size:%?32?%;color:#fff;padding:0 %?20?%;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column}.active_one-container .canvas-container .btn-wrap .fsend-btn uni-image[data-v-8e9c1b8c]{width:%?80?%;height:%?80?%}.active_one-container .canvas-container .btn-wrap .save-btn[data-v-8e9c1b8c]{background-color:#ff7e00;border-radius:%?10?%;font-size:%?32?%;color:#fff;padding:0 %?20?%}.active_one-container .canvas[data-v-8e9c1b8c]{width:%?480?%;height:%?854?%;position:fixed;z-index:-1;left:-100%}",""]),t.exports=e},5475:function(t,e,a){"use strict";var i=a("4347"),n=a.n(i);n.a},5691:function(t,e,a){"use strict";a.r(e);var i=a("5c94"),n=a.n(i);for(var o in i)"default"!==o&&function(t){a.d(e,t,(function(){return i[t]}))}(o);e["default"]=n.a},"5c94":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={data:function(){return{scale:""}},props:{type:{default:""}}};e.default=i},"5f68":function(t,e,a){"use strict";var i=a("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=i(a("8b74")),o={components:{btnComponent:n.default},data:function(){return{show:!1}},props:{title:{default:"提示"},headimg:{default:"/static/image/guild/hart.png"},type:{default:"default"}},created:function(){this.show=!0},mounted:function(){},methods:{closeModal:function(){var t=this;this.show=!1,setTimeout((function(){t.$emit("closeModal")}),200)}}};e.default=o},7965:function(t,e,a){"use strict";var i,n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"container flex-set",class:{show:t.show},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closeModal.apply(void 0,arguments)}}},[a("v-uni-view",{staticClass:"modal-container",class:[t.type],on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e)}}},["false"!=t.headimg?a("v-uni-view",{staticClass:"top-wrapper"},["send"==t.type?a("v-uni-image",{staticClass:"title-bg",attrs:{src:"/static/image/guild/send-modal-bg-1_01.png",mode:"widthFix"}}):t._e(),"default"==t.type?a("v-uni-view",{staticClass:"title-bg linear"}):t._e(),a("v-uni-view",{staticClass:"title"},[t._v(t._s(t.title))]),a("v-uni-image",{staticClass:"center-img",attrs:{src:t.headimg,mode:""}})],1):t._e(),a("v-uni-view",{staticClass:"content"},[t._t("default")],2)],1),a("v-uni-view",{staticClass:"close-btn flex-set iconfont iconclose",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closeModal.apply(void 0,arguments)}}})],1)},o=[];a.d(e,"b",(function(){return n})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return i}))},"8b74":function(t,e,a){"use strict";a.r(e);var i=a("cc0d"),n=a("5691");for(var o in n)"default"!==o&&function(t){a.d(e,t,(function(){return n[t]}))}(o);a("1e85");var c,r=a("f0c5"),s=Object(r["a"])(n["default"],i["b"],i["c"],!1,null,"40190e39",null,!1,i["a"],c);e["default"]=s.exports},a39e:function(t,e,a){"use strict";var i=a("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,a("a481");var n=i(a("b2f3")),o=i(a("8b74")),c={components:{modalComponent:n.default,btnComponent:o.default},data:function(){return{star:{},activeInfo:{},userRank:[],modal:"",cardOverContent:"",article:this.$app.getData("config").active_notice}},onShareAppMessage:function(t){t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(5)},onLoad:function(t){this.id=t.id},onShow:function(t){this.$app.openInterstitialAd(),this.starid=this.$app.getData("userStar").id,this.getActiveInfo(),this.getStarInfo(),this.getActiveUserRank()},methods:{preImg:function(t){this.$app.preImg(t)},getLocalImg:function(t,e){~t.indexOf("http")?uni.getImageInfo({src:t,success:function(t){e&&e(t.path)}}):e&&e(t)},getShareText:function(t){var e=this;this.$app.request(this.$app.API.EXT_SHARETEXT,{type:t},(function(t){e.modal="",e.$app.copy(t.data.share_text)}))},drawCanvas:function(){var t=this,e=this.$app.getData("sysInfo").windowWidth/375/2,a=uni.createCanvasContext("mycanvas",this),i=function(){a.setFillStyle("#000000"),a.setFontSize(18),a.setTextAlign("center"),a.fillText(this.star.name,140*e,535*e),a.setFontSize(10),a.fillText("榜单排名:NO.".concat(this.star.weekRank),335*e,515*e),a.fillText("人气值:".concat(this.star.weekHot),335*e,545*e),a.setTextAlign("left"),a.fillText("我是".concat(this.$app.getData("userInfo").nickname),140*e,670*e),a.fillText("一起pick".concat(this.star.name),140*e,700*e)}.bind(this);uni.showLoading({title:"生成海报中"}),this.getLocalImg("/static/image/canvas-bg.jpg",(function(n){a.drawImage(n,0,0,480*e,854*e),t.getLocalImg(t.star.share_img||t.star.avatar,(function(n){a.drawImage(n,48*e,176*e,382*e,300*e),t.getLocalImg(t.$app.getData("userInfo").avatarurl||t.$app.getData("AVATAR"),(function(n){a.save(),a.beginPath(),a.arc(85*e,675*e,40*e,0,2*Math.PI,!1),a.clip(),a.drawImage(n,45*e,635*e,80*e,80*e),a.restore(),t.$app.getData("config").version==t.$app.getData("VERSION")&&t.$app.setData("qrcode","/static/image/def.jpg"),t.getLocalImg(t.$app.getData("qrcode")||t.$app.QRCODE,(function(n){a.save(),a.beginPath(),a.arc(380*e,675*e,50*e,0,2*Math.PI,!1),a.clip(),a.drawImage(n,330*e,625*e,100*e,100*e),a.restore(),i(),a.draw(!1,(function(){uni.canvasToTempFilePath({canvasId:"mycanvas",success:function(e){t.canvasImg=e.tempFilePath,console.log(t.canvasImg),t.saveCanvas()}},t)})),uni.hideLoading()}))}))}))}))},saveCanvas:function(){var t=this;uni.saveImageToPhotosAlbum({filePath:this.canvasImg,success:function(){t.$app.toast("保存成功","success")},fail:function(t){console.log(t)}})},getStarInfo:function(){var t=this;this.$app.request(this.$app.API.STAR_INFO,{starid:this.starid},(function(e){var a=e.data;t.star={id:a.id,avatar:a.head_img_s?a.head_img_s:a.head_img_l,name:a.name,weekHot:t.$app.formatNumberRgx(a.star_rank.week_hot),weekRank:a.star_rank.week_hot_rank,share_img:a.share_img},t.$app.closeLoading(t)}))},card:function(){var t=this;this.$app.getData("userStar").id==this.starid?this.activeInfo.self.is_card_today?(this.modal="cardOver",this.$app.toast("今日已打卡")):this.$app.openVideoAd((function(){t.$app.request(t.$app.API.EXT_ACTIVECARD,{starid:t.starid,active_id:t.id},(function(e){t.modal="cardOver",t.getActiveInfo(),t.getActiveUserRank(),t.$app.toast("今日打卡成功","success")}),"POST",!0)}),0):this.$app.toast("你怎么能为别的爱豆打卡呢")},getActiveUserRank:function(){var t=this;this.$app.request(this.$app.API.EXT_ACTIVE_USERRANK,{starid:this.starid,active_id:this.id},(function(e){t.userRank=e.data}))},getActiveInfo:function(){var t=this;this.$app.request(this.$app.API.EXT_ACTIVEINFO,{starid:this.starid,id:this.id},(function(e){t.canvas_title=e.data.canvas_title;var a=t.$app.timeGethms(e.data.active_end);e.data.left_time=a.day+"天"+a.hour+"小时"+a.min+"分",e.data.title=e.data.title.replace("STARNAME",t.$app.getData("userStar").name),t.activeInfo=e.data}))}}};e.default=c},a926:function(t,e,a){"use strict";a.r(e);var i=a("02fd"),n=a("1c2e");for(var o in n)"default"!==o&&function(t){a.d(e,t,(function(){return n[t]}))}(o);a("5475");var c,r=a("f0c5"),s=Object(r["a"])(n["default"],i["b"],i["c"],!1,null,"8e9c1b8c",null,!1,i["a"],c);e["default"]=s.exports},b2f3:function(t,e,a){"use strict";a.r(e);var i=a("7965"),n=a("2159");for(var o in n)"default"!==o&&function(t){a.d(e,t,(function(){return n[t]}))}(o);a("2278");var c,r=a("f0c5"),s=Object(r["a"])(n["default"],i["b"],i["c"],!1,null,"a9ec2604",null,!1,i["a"],c);e["default"]=s.exports},cc0d:function(t,e,a){"use strict";var i,n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"button flex-set",class:[t.type,t.scale],on:{touchstart:function(e){arguments[0]=e=t.$handleEvent(e),t.scale="scale"},touchend:function(e){arguments[0]=e=t.$handleEvent(e),t.scale=""}}},[t._t("default")],2)},o=[];a.d(e,"b",(function(){return n})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return i}))},d4d1:function(t,e,a){var i=a("0386");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("40effe12",i,!0,{sourceMap:!1,shadowMode:!1})}}]);