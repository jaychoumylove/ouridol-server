(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-signin-group"],{"0111":function(t,a,e){var n=e("24fb");a=n(!1),a.push([t.i,".button[data-v-40190e39]{color:#6b4a39;-webkit-transition:.3s;transition:.3s;border-radius:%?40?%}.button.scale[data-v-40190e39]{-webkit-transform:scale(.7);transform:scale(.7)}.button.default[data-v-40190e39]{color:#fff;background:-webkit-linear-gradient(left top,#ff3a8a 20%,#fa6c9f 82%,#ffe140);background:linear-gradient(to right bottom,#ff3a8a 20%,#fa6c9f 82%,#ffe140);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3)}.button.big[data-v-40190e39]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2dxu4igebg303v02cgld.gif) 50% no-repeat/100% 100%}.button.success[data-v-40190e39]{color:#fff;background:-webkit-linear-gradient(left top,#44ea76 0,#39fad7 82%,#39fad7);background:linear-gradient(to right bottom,#44ea76 0,#39fad7 82%,#39fad7);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3)}.button.disable[data-v-40190e39]{color:#fff;background:-webkit-linear-gradient(left top,#aaa,#666);background:linear-gradient(to right bottom,#aaa,#666);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3)}.button.fangde[data-v-40190e39]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2jwmn4sshg305v05vwea.gif) 50% no-repeat/100% 100%}.button.css[data-v-40190e39]{color:#fff;background:-webkit-linear-gradient(left top,#ff3a8a 20%,#fa6c9f 82%,#ffe140);background:linear-gradient(to right bottom,#ff3a8a 20%,#fa6c9f 82%,#ffe140);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3)}.button.palePink[data-v-40190e39]{color:#fff;background:url(https://mmbiz.qpic.cn/mmbiz_png/CbJC0icY3EzYDK6IibJZCjKRgkicZiapsgltvnGHD42bLH7zibMs071OjaqnRicjpLucbuGyYXnxVW5Ro99ppCvPvp5w/0) 50%/100% 100% no-repeat}.button.pink[data-v-40190e39]{color:#fff;background:url(https://mmbiz.qpic.cn/mmbiz_png/CbJC0icY3EzZkic73fibNnibpIvllj1icjrN7gGdaQiaejibgHicJ6hBDTmQcprH8ibdbskrzvoqaBkbX8cpR9WZfDicRDfA/0) 50%/100% 100% no-repeat}.button.golden[data-v-40190e39]{color:#fff;background:url(https://mmbiz.qpic.cn/mmbiz_png/CbJC0icY3EzZkic73fibNnibpIvllj1icjrN7gdbcBHONe18HPVfJTuhBpDBqlcTYloxiblEdhzLDlZlfLuF5xjicQ4uw/0) 50%/100% 100% no-repeat}.button.color[data-v-40190e39]{background-color:#efccc8;border-radius:%?60?%;box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3)}",""]),t.exports=a},"1e85":function(t,a,e){"use strict";var n=e("4a2f"),i=e.n(n);i.a},"26b6":function(t,a,e){var n=e("c693");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("0ae6f292",n,!0,{sourceMap:!1,shadowMode:!1})},"4a2f":function(t,a,e){var n=e("0111");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("0096f158",n,!0,{sourceMap:!1,shadowMode:!1})},5691:function(t,a,e){"use strict";e.r(a);var n=e("5c94"),i=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(a,t,(function(){return n[t]}))}(o);a["default"]=i.a},"5c94":function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{scale:""}},props:{type:{default:""}}};a.default=n},"5ce6":function(t,a,e){"use strict";e.r(a);var n=e("5fd8"),i=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(a,t,(function(){return n[t]}))}(o);a["default"]=i.a},"5fd8":function(t,a,e){"use strict";var n=e("4ea4");Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i=n(e("8b74")),o={components:{btnComponent:i.default},data:function(){return{dynamic:null,dynamic_translate:"translateY(0)",groupRank:[],reback_open:!1,daycount_reback:0}},onShow:function(){this.loadData()},onShareAppMessage:function(t){var a=t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(a)},methods:{getReback:function(){var t=this;this.$app.request("share/group/groupDayReback",{},(function(a){t.$app.toast("能量+"+a.data.reback),t.loadData()}),"POST",!0)},loadData:function(){var t=this;this.$app.request("page/wxgroup",{},(function(a){if(!t.dynamic){t.dynamic=a.data.dynamic;var e=0,n=t.dynamic.length;clearInterval(t.timeId_dynamic),t.timeId_dynamic=setInterval((function(){t.dynamic_translate="translateY(-"+e/n*100+"%)",++e>=n-2&&clearInterval(t.timeId_dynamic)}),5e3)}t.groupRank=a.data.groupList,t.$app.getTime()>t.$app.getData("config").groupmass.reback_open[0]&&t.$app.getTime()<t.$app.getData("config").groupmass.reback_open[1]&&(t.reback_open=!0,t.daycount_reback=a.data.reback)}))}}};a.default=o},"6e22":function(t,a,e){"use strict";var n,i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"group-container"},[e("v-uni-view",{staticClass:"top-container"},[e("v-uni-view",{staticClass:"top-wrap flex-set"},[e("v-uni-view",{staticClass:"title"},[t._v("群集结动态")]),e("btnComponent",{attrs:{type:"default"}},[e("v-uni-button",{attrs:{"open-type":"share","data-share":"7"}},[e("v-uni-view",{staticStyle:{padding:"10upx 30upx"}},[t._v("发起群集结")])],1)],1)],1),e("v-uni-view",{staticClass:"scroll-wrap"},[e("v-uni-view",{staticClass:"scroll",style:{transform:t.dynamic_translate}},t._l(t.dynamic,(function(a,n){return e("v-uni-view",{key:n,staticClass:"scroll-item"},[e("v-uni-view",{staticClass:"item"},[e("v-uni-image",{attrs:{src:"/static/image/user/b3.png",mode:""}}),e("v-uni-text",{staticClass:"name"},[t._v(t._s(a.user_name))]),e("v-uni-text",[t._v(t._s(a.text))])],1)],1)})),1)],1)],1),e("v-uni-view",{staticClass:"mid-container"},[e("v-uni-view",{staticClass:"top-wrap flex-set"},[e("v-uni-view",{staticClass:"left"},[e("v-uni-view",{staticClass:"t title"},[t._v("群贡献日榜")]),e("v-uni-view",{staticClass:"b"},[t._v("粉丝群每日打榜贡献前10名")])],1),t.reback_open?e("v-uni-view",{staticClass:"right"},[t.daycount_reback?e("btnComponent",{attrs:{type:"css"}},[e("v-uni-view",{staticStyle:{padding:"10upx 20upx"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.getReback.apply(void 0,arguments)}}},[t._v("领取昨日贡献奖励")])],1):e("btnComponent",{attrs:{type:"disable"}},[e("v-uni-view",{staticStyle:{padding:"10upx 20upx"}},[t._v("领取昨日贡献奖励")])],1)],1):t._e()],1),e("v-uni-view",{staticClass:"list-container"},t._l(t.groupRank,(function(a,n){return e("v-uni-view",{key:n,staticClass:"item"},[e("v-uni-view",{staticClass:"top flex-set"},[0==n?e("v-uni-image",{staticClass:"icon",attrs:{src:"/static/image/guild/u1.png",mode:""}}):1==n?e("v-uni-image",{staticClass:"icon",attrs:{src:"/static/image/guild/u2.png",mode:""}}):2==n?e("v-uni-image",{staticClass:"icon",attrs:{src:"/static/image/guild/u3.png",mode:""}}):e("v-uni-view",{staticClass:"icon flex-set"},[t._v(t._s(n+1))]),t._v(t._s(a.star.name)+"的粉丝群"+t._s(a.id)+"群")],1),e("v-uni-view",{staticClass:"bottom flex-set"},[e("v-uni-view",{staticClass:"left flex-set"},[e("v-uni-image",{staticClass:"ava-1",attrs:{src:a.userRank[0].user.avatarurl,mode:""}}),e("v-uni-image",{staticClass:"ava-2",attrs:{src:a.userRank[1].user.avatarurl,mode:""}}),e("v-uni-image",{staticClass:"ava-3",attrs:{src:a.userRank[2].user.avatarurl,mode:""}}),e("v-uni-image",{staticClass:"ava-4",attrs:{src:a.userRank[3].user.avatarurl,mode:""}}),e("v-uni-image",{staticClass:"ava-5",attrs:{src:a.userRank[4].user.avatarurl,mode:""}}),e("v-uni-view",{staticClass:"b"},[t._v("共"+t._s(a.member_count)+"位")])],1),e("v-uni-view",{},[t._v("贡献"+t._s(a.thisday_count)+"能量")])],1)],1)})),1)],1)],1)},o=[];e.d(a,"b",(function(){return i})),e.d(a,"c",(function(){return o})),e.d(a,"a",(function(){return n}))},"8b74":function(t,a,e){"use strict";e.r(a);var n=e("cc0d"),i=e("5691");for(var o in i)"default"!==o&&function(t){e.d(a,t,(function(){return i[t]}))}(o);e("1e85");var r,c=e("f0c5"),s=Object(c["a"])(i["default"],n["b"],n["c"],!1,null,"40190e39",null,!1,n["a"],r);a["default"]=s.exports},c693:function(t,a,e){var n=e("24fb");a=n(!1),a.push([t.i,".group-container .top-container[data-v-dde5f932]{margin:%?30?%;background-color:#fff;border-radius:%?10?%;padding:%?20?% %?30?%;box-shadow:0 %?2?% %?16?% hsla(0,0%,40%,.3);position:relative;z-index:1}.group-container .top-container .top-wrap[data-v-dde5f932]{font-size:%?32?%;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.group-container .top-container .scroll-wrap[data-v-dde5f932]{height:%?150?%;overflow:hidden;position:relative}.group-container .top-container .scroll-wrap .scroll[data-v-dde5f932]{position:absolute;-webkit-transition:.8s;transition:.8s}.group-container .top-container .scroll-wrap .scroll .scroll-item[data-v-dde5f932]{margin:%?10?% 0}.group-container .top-container .scroll-wrap .scroll .scroll-item .item[data-v-dde5f932]{font-size:%?22?%;padding:%?5?% %?10?%;display:inline-block;background-color:#f5f5f5;border-radius:%?20?%;color:#676767}.group-container .top-container .scroll-wrap .scroll .scroll-item .item uni-image[data-v-dde5f932]{width:%?24?%;height:%?24?%}.group-container .top-container .scroll-wrap .scroll .scroll-item .item .name[data-v-dde5f932]{color:#fe5d63}.group-container .mid-container[data-v-dde5f932]{padding:%?20?% %?30?%;background-color:#fff;padding-top:%?80?%;margin-top:%?-80?%}.group-container .mid-container .top-wrap[data-v-dde5f932]{-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.group-container .mid-container .top-wrap .left .t[data-v-dde5f932]{font-size:%?32?%}.group-container .mid-container .top-wrap .left .b[data-v-dde5f932]{font-size:%?22?%;color:#868686}.group-container .mid-container .list-container .item[data-v-dde5f932]{padding:%?20?% 0;border-bottom:%?1?% solid #eee}.group-container .mid-container .list-container .item .top[data-v-dde5f932]{font-size:%?32?%;margin:%?10?%;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start}.group-container .mid-container .list-container .item .top .icon[data-v-dde5f932]{width:%?36?%;height:%?36?%;margin-right:%?10?%}.group-container .mid-container .list-container .item .bottom[data-v-dde5f932]{-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.group-container .mid-container .list-container .item .bottom .left uni-image[data-v-dde5f932]{width:%?50?%;height:%?50?%;border-radius:50%}.group-container .mid-container .list-container .item .bottom .left .ava-1[data-v-dde5f932]{z-index:5}.group-container .mid-container .list-container .item .bottom .left .ava-2[data-v-dde5f932]{z-index:4;margin-left:%?-20?%}.group-container .mid-container .list-container .item .bottom .left .ava-3[data-v-dde5f932]{z-index:3;margin-left:%?-20?%}.group-container .mid-container .list-container .item .bottom .left .ava-4[data-v-dde5f932]{z-index:2;margin-left:%?-20?%}.group-container .mid-container .list-container .item .bottom .left .ava-5[data-v-dde5f932]{z-index:1;margin-left:%?-20?%}.group-container .mid-container .list-container .item .bottom .left .b[data-v-dde5f932]{margin-left:%?10?%;color:#868686}",""]),t.exports=a},cc0d:function(t,a,e){"use strict";var n,i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"button flex-set",class:[t.type,t.scale],on:{touchstart:function(a){arguments[0]=a=t.$handleEvent(a),t.scale="scale"},touchend:function(a){arguments[0]=a=t.$handleEvent(a),t.scale=""}}},[t._t("default")],2)},o=[];e.d(a,"b",(function(){return i})),e.d(a,"c",(function(){return o})),e.d(a,"a",(function(){return n}))},e62d:function(t,a,e){"use strict";e.r(a);var n=e("6e22"),i=e("5ce6");for(var o in i)"default"!==o&&function(t){e.d(a,t,(function(){return i[t]}))}(o);e("f40e");var r,c=e("f0c5"),s=Object(c["a"])(i["default"],n["b"],n["c"],!1,null,"dde5f932",null,!1,n["a"],r);a["default"]=s.exports},f40e:function(t,a,e){"use strict";var n=e("26b6"),i=e.n(n);i.a}}]);