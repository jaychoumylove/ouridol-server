(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-signin-group"],{"04c8":function(t,a,e){"use strict";var n=e("288e");Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i=n(e("cbb7")),o={components:{btnComponent:i.default},data:function(){return{dynamic:null,dynamic_translate:"translateY(0)",groupRank:[],reback_open:!1,daycount_reback:0}},onShow:function(){this.loadData()},onShareAppMessage:function(t){var a=t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(a)},methods:{getReback:function(){var t=this;this.$app.request("share/group/groupDayReback",{},function(a){t.$app.toast("能量+"+a.data.reback),t.loadData()},"POST",!0)},loadData:function(){var t=this;this.$app.request("page/wxgroup",{},function(a){if(!t.dynamic){t.dynamic=a.data.dynamic;var e=0,n=t.dynamic.length;clearInterval(t.timeId_dynamic),t.timeId_dynamic=setInterval(function(){t.dynamic_translate="translateY(-"+e/n*100+"%)",++e>=n-2&&clearInterval(t.timeId_dynamic)},5e3)}t.groupRank=a.data.groupList,t.$app.getTime()>t.$app.getData("config").groupmass.reback_open[0]&&t.$app.getTime()<t.$app.getData("config").groupmass.reback_open[1]&&(t.reback_open=!0,t.daycount_reback=a.data.reback)})}}};a.default=o},1209:function(t,a,e){"use strict";var n=e("aae4"),i=e.n(n);i.a},"2e61":function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".button[data-v-93b698e6]{color:#6b4a39;-webkit-transition:.3s;-o-transition:.3s;transition:.3s;border-radius:%?20?%}.button.scale[data-v-93b698e6]{-webkit-transform:scale(.7);-ms-transform:scale(.7);transform:scale(.7)}.button.default[data-v-93b698e6]{color:#fff;background:-webkit-gradient(linear,left top,right bottom,from(#f8648a),to(red));background:-o-linear-gradient(left top,#f8648a,red);background:linear-gradient(to right bottom,#f8648a,red);-webkit-box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3)}.button.big[data-v-93b698e6]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2dxu4igebg303v02cgld.gif) 50% no-repeat/100% 100%}.button.success[data-v-93b698e6]{color:#fff;-webkit-box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3);background:-webkit-gradient(linear,left top,right bottom,from(#28a745),to(#70c183));background:-o-linear-gradient(left top,#28a745,#70c183);background:linear-gradient(to right bottom,#28a745,#70c183)}.button.disable[data-v-93b698e6]{color:#fff;background:-webkit-gradient(linear,left top,right bottom,from(#aaa),to(#666));background:-o-linear-gradient(left top,#aaa,#666);background:linear-gradient(to right bottom,#aaa,#666);-webkit-box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3)}.button.fangde[data-v-93b698e6]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2jwmn4sshg305v05vwea.gif) 50% no-repeat/100% 100%}.button.css[data-v-93b698e6]{color:#fff;background:-webkit-gradient(linear,left top,right bottom,from(#f8648a),to(red));background:-o-linear-gradient(left top,#f8648a,red);background:linear-gradient(to right bottom,#f8648a,red);-webkit-box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3)}.button.color[data-v-93b698e6]{background-color:#efccc8;border-radius:%?60?%;-webkit-box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3)}",""])},3735:function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".group-container .top-container[data-v-512d16f4]{margin:%?30?%;background-color:#fff;border-radius:%?10?%;padding:%?20?% %?30?%;-webkit-box-shadow:0 %?2?% %?16?% hsla(0,0%,40%,.3);box-shadow:0 %?2?% %?16?% hsla(0,0%,40%,.3);position:relative;z-index:1}.group-container .top-container .top-wrap[data-v-512d16f4]{font-size:%?32?%;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between}.group-container .top-container .scroll-wrap[data-v-512d16f4]{height:%?150?%;overflow:hidden;position:relative}.group-container .top-container .scroll-wrap .scroll[data-v-512d16f4]{position:absolute;-webkit-transition:.8s;-o-transition:.8s;transition:.8s}.group-container .top-container .scroll-wrap .scroll .scroll-item[data-v-512d16f4]{margin:%?10?% 0}.group-container .top-container .scroll-wrap .scroll .scroll-item .item[data-v-512d16f4]{font-size:%?22?%;padding:%?5?% %?10?%;display:inline-block;background-color:#f5f5f5;border-radius:%?20?%;color:#676767}.group-container .top-container .scroll-wrap .scroll .scroll-item .item uni-image[data-v-512d16f4]{width:%?24?%;height:%?24?%}.group-container .top-container .scroll-wrap .scroll .scroll-item .item .name[data-v-512d16f4]{color:#fe5d63}.group-container .mid-container[data-v-512d16f4]{padding:%?20?% %?30?%;background-color:#fff;padding-top:%?80?%;margin-top:%?-80?%}.group-container .mid-container .top-wrap[data-v-512d16f4]{-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between}.group-container .mid-container .top-wrap .left .t[data-v-512d16f4]{font-size:%?32?%}.group-container .mid-container .top-wrap .left .b[data-v-512d16f4]{font-size:%?22?%;color:#868686}.group-container .mid-container .list-container .item[data-v-512d16f4]{padding:%?20?% 0;border-bottom:%?1?% solid #eee}.group-container .mid-container .list-container .item .top[data-v-512d16f4]{font-size:%?32?%;margin:%?10?%;-webkit-box-pack:start;-webkit-justify-content:flex-start;-ms-flex-pack:start;justify-content:flex-start}.group-container .mid-container .list-container .item .top .icon[data-v-512d16f4]{width:%?36?%;height:%?36?%;margin-right:%?10?%}.group-container .mid-container .list-container .item .bottom[data-v-512d16f4]{-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between}.group-container .mid-container .list-container .item .bottom .left uni-image[data-v-512d16f4]{width:%?50?%;height:%?50?%;border-radius:50%}.group-container .mid-container .list-container .item .bottom .left .ava-1[data-v-512d16f4]{z-index:5}.group-container .mid-container .list-container .item .bottom .left .ava-2[data-v-512d16f4]{z-index:4;margin-left:%?-20?%}.group-container .mid-container .list-container .item .bottom .left .ava-3[data-v-512d16f4]{z-index:3;margin-left:%?-20?%}.group-container .mid-container .list-container .item .bottom .left .ava-4[data-v-512d16f4]{z-index:2;margin-left:%?-20?%}.group-container .mid-container .list-container .item .bottom .left .ava-5[data-v-512d16f4]{z-index:1;margin-left:%?-20?%}.group-container .mid-container .list-container .item .bottom .left .b[data-v-512d16f4]{margin-left:%?10?%;color:#868686}",""])},"3e18":function(t,a,e){var n=e("2e61");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("ed7455d0",n,!0,{sourceMap:!1,shadowMode:!1})},"5eac":function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{scale:""}},props:{type:{default:""}}};a.default=n},"5f68":function(t,a,e){"use strict";e.r(a);var n=e("04c8"),i=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(a,t,function(){return n[t]})}(o);a["default"]=i.a},"87f6":function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"button flex-set",class:[t.type,t.scale],on:{touchstart:function(a){a=t.$handleEvent(a),t.scale="scale"},touchend:function(a){a=t.$handleEvent(a),t.scale=""}}},[t._t("default")],2)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})},"98e6":function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"group-container"},[e("v-uni-view",{staticClass:"top-container"},[e("v-uni-view",{staticClass:"top-wrap flex-set"},[e("v-uni-view",{staticClass:"title"},[t._v("群集结动态")]),e("btnComponent",{attrs:{type:"css"}},[e("v-uni-button",{attrs:{"open-type":"share","data-share":"7"}},[e("v-uni-view",{staticStyle:{padding:"10upx 20upx"}},[t._v("发起群集结")])],1)],1)],1),e("v-uni-view",{staticClass:"scroll-wrap"},[e("v-uni-view",{staticClass:"scroll",style:{transform:t.dynamic_translate}},t._l(t.dynamic,function(a,n){return e("v-uni-view",{key:n,staticClass:"scroll-item"},[e("v-uni-view",{staticClass:"item"},[e("v-uni-image",{attrs:{src:"/static/image/user/b3.png",mode:""}}),e("v-uni-text",{staticClass:"name"},[t._v(t._s(a.user_name))]),e("v-uni-text",[t._v(t._s(a.text))])],1)],1)}),1)],1)],1),e("v-uni-view",{staticClass:"mid-container"},[e("v-uni-view",{staticClass:"top-wrap flex-set"},[e("v-uni-view",{staticClass:"left"},[e("v-uni-view",{staticClass:"t title"},[t._v("群贡献日榜")]),e("v-uni-view",{staticClass:"b"},[t._v("粉丝群每日打榜贡献前10名")])],1),t.reback_open?e("v-uni-view",{staticClass:"right"},[t.daycount_reback?e("btnComponent",{attrs:{type:"css"}},[e("v-uni-view",{staticStyle:{padding:"10upx 20upx"},on:{click:function(a){a=t.$handleEvent(a),t.getReback(a)}}},[t._v("领取昨日贡献奖励")])],1):e("btnComponent",{attrs:{type:"disable"}},[e("v-uni-view",{staticStyle:{padding:"10upx 20upx"}},[t._v("领取昨日贡献奖励")])],1)],1):t._e()],1),e("v-uni-view",{staticClass:"list-container"},t._l(t.groupRank,function(a,n){return e("v-uni-view",{key:n,staticClass:"item"},[e("v-uni-view",{staticClass:"top flex-set"},[0==n?e("v-uni-image",{staticClass:"icon",attrs:{src:"/static/image/guild/u1.png",mode:""}}):1==n?e("v-uni-image",{staticClass:"icon",attrs:{src:"/static/image/guild/u2.png",mode:""}}):2==n?e("v-uni-image",{staticClass:"icon",attrs:{src:"/static/image/guild/u3.png",mode:""}}):e("v-uni-view",{staticClass:"icon flex-set"},[t._v(t._s(n+1))]),t._v(t._s(a.star.name)+"的粉丝群"+t._s(a.id)+"群")],1),e("v-uni-view",{staticClass:"bottom flex-set"},[e("v-uni-view",{staticClass:"left flex-set"},[e("v-uni-image",{staticClass:"ava-1",attrs:{src:a.userRank[0].user.avatarurl,mode:""}}),e("v-uni-image",{staticClass:"ava-2",attrs:{src:a.userRank[1].user.avatarurl,mode:""}}),e("v-uni-image",{staticClass:"ava-3",attrs:{src:a.userRank[2].user.avatarurl,mode:""}}),e("v-uni-image",{staticClass:"ava-4",attrs:{src:a.userRank[3].user.avatarurl,mode:""}}),e("v-uni-image",{staticClass:"ava-5",attrs:{src:a.userRank[4].user.avatarurl,mode:""}}),e("v-uni-view",{staticClass:"b"},[t._v("共"+t._s(a.member_count)+"位")])],1),e("v-uni-view",{},[t._v("贡献"+t._s(a.thisday_count)+"能量")])],1)],1)}),1)],1)],1)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})},aae4:function(t,a,e){var n=e("3735");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("9de275de",n,!0,{sourceMap:!1,shadowMode:!1})},c2079:function(t,a,e){"use strict";var n=e("3e18"),i=e.n(n);i.a},cbb7:function(t,a,e){"use strict";e.r(a);var n=e("87f6"),i=e("d29f");for(var o in i)"default"!==o&&function(t){e.d(a,t,function(){return i[t]})}(o);e("c2079");var r=e("2877"),s=Object(r["a"])(i["default"],n["a"],n["b"],!1,null,"93b698e6",null);a["default"]=s.exports},d29f:function(t,a,e){"use strict";e.r(a);var n=e("5eac"),i=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(a,t,function(){return n[t]})}(o);a["default"]=i.a},e742:function(t,a,e){"use strict";e.r(a);var n=e("98e6"),i=e("5f68");for(var o in i)"default"!==o&&function(t){e.d(a,t,function(){return i[t]})}(o);e("1209");var r=e("2877"),s=Object(r["a"])(i["default"],n["a"],n["b"],!1,null,"512d16f4",null);a["default"]=s.exports}}]);