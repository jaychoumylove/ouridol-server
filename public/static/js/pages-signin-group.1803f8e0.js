(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-signin-group"],{"1e5a":function(t,a,e){"use strict";var n=e("faec"),i=e.n(n);i.a},"238f":function(t,a,e){"use strict";e.r(a);var n=e("ac96"),i=e("51a7");for(var r in i)"default"!==r&&function(t){e.d(a,t,function(){return i[t]})}(r);e("a454");var o,s=e("f0c5"),c=Object(s["a"])(i["default"],n["b"],n["c"],!1,null,"641a1844",null,!1,n["a"],o);a["default"]=c.exports},2872:function(t,a,e){var n=e("5451");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("206b54cc",n,!0,{sourceMap:!1,shadowMode:!1})},3793:function(t,a,e){"use strict";var n,i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"group-container"},[e("v-uni-view",{staticClass:"top-container"},[e("v-uni-view",{staticClass:"top-wrap flex-set"},[e("v-uni-view",{staticClass:"title"},[t._v("群集结动态")]),e("btnComponent",{attrs:{type:"css"}},[e("v-uni-button",{attrs:{"open-type":"share","data-share":"7"}},[e("v-uni-view",{staticStyle:{padding:"10upx 20upx"}},[t._v("发起群集结")])],1)],1)],1),e("v-uni-view",{staticClass:"scroll-wrap"},[e("v-uni-view",{staticClass:"scroll",style:{transform:t.dynamic_translate}},t._l(t.dynamic,function(a,n){return e("v-uni-view",{key:n,staticClass:"scroll-item"},[e("v-uni-view",{staticClass:"item"},[e("v-uni-image",{attrs:{src:"/static/image/user/b3.png",mode:""}}),e("v-uni-text",{staticClass:"name"},[t._v(t._s(a.user_name))]),e("v-uni-text",[t._v(t._s(a.text))])],1)],1)}),1)],1)],1),e("v-uni-view",{staticClass:"mid-container"},[e("v-uni-view",{staticClass:"top-wrap flex-set"},[e("v-uni-view",{staticClass:"left"},[e("v-uni-view",{staticClass:"t title"},[t._v("群贡献日榜")]),e("v-uni-view",{staticClass:"b"},[t._v("粉丝群每日打榜贡献前10名")])],1),t.reback_open?e("v-uni-view",{staticClass:"right"},[t.daycount_reback?e("btnComponent",{attrs:{type:"css"}},[e("v-uni-view",{staticStyle:{padding:"10upx 20upx"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.getReback.apply(void 0,arguments)}}},[t._v("领取昨日贡献奖励")])],1):e("btnComponent",{attrs:{type:"disable"}},[e("v-uni-view",{staticStyle:{padding:"10upx 20upx"}},[t._v("领取昨日贡献奖励")])],1)],1):t._e()],1),e("v-uni-view",{staticClass:"list-container"},t._l(t.groupRank,function(a,n){return e("v-uni-view",{key:n,staticClass:"item"},[e("v-uni-view",{staticClass:"top flex-set"},[0==n?e("v-uni-image",{staticClass:"icon",attrs:{src:"/static/image/guild/u1.png",mode:""}}):1==n?e("v-uni-image",{staticClass:"icon",attrs:{src:"/static/image/guild/u2.png",mode:""}}):2==n?e("v-uni-image",{staticClass:"icon",attrs:{src:"/static/image/guild/u3.png",mode:""}}):e("v-uni-view",{staticClass:"icon flex-set"},[t._v(t._s(n+1))]),t._v(t._s(a.star.name)+"的粉丝群"+t._s(a.id)+"群")],1),e("v-uni-view",{staticClass:"bottom flex-set"},[e("v-uni-view",{staticClass:"left flex-set"},[e("v-uni-image",{staticClass:"ava-1",attrs:{src:a.userRank[0].user.avatarurl,mode:""}}),e("v-uni-image",{staticClass:"ava-2",attrs:{src:a.userRank[1].user.avatarurl,mode:""}}),e("v-uni-image",{staticClass:"ava-3",attrs:{src:a.userRank[2].user.avatarurl,mode:""}}),e("v-uni-image",{staticClass:"ava-4",attrs:{src:a.userRank[3].user.avatarurl,mode:""}}),e("v-uni-image",{staticClass:"ava-5",attrs:{src:a.userRank[4].user.avatarurl,mode:""}}),e("v-uni-view",{staticClass:"b"},[t._v("共"+t._s(a.member_count)+"位")])],1),e("v-uni-view",{},[t._v("贡献"+t._s(a.thisday_count)+"能量")])],1)],1)}),1)],1)],1)},r=[];e.d(a,"b",function(){return i}),e.d(a,"c",function(){return r}),e.d(a,"a",function(){return n})},3931:function(t,a,e){"use strict";var n=e("288e");Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i=n(e("238f")),r={components:{btnComponent:i.default},data:function(){return{dynamic:null,dynamic_translate:"translateY(0)",groupRank:[],reback_open:!1,daycount_reback:0}},onShow:function(){this.loadData()},onShareAppMessage:function(t){var a=t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(a)},methods:{getReback:function(){var t=this;this.$app.request("share/group/groupDayReback",{},function(a){t.$app.toast("能量+"+a.data.reback),t.loadData()},"POST",!0)},loadData:function(){var t=this;this.$app.request("page/wxgroup",{},function(a){if(!t.dynamic){t.dynamic=a.data.dynamic;var e=0,n=t.dynamic.length;clearInterval(t.timeId_dynamic),t.timeId_dynamic=setInterval(function(){t.dynamic_translate="translateY(-"+e/n*100+"%)",++e>=n-2&&clearInterval(t.timeId_dynamic)},5e3)}t.groupRank=a.data.groupList,t.$app.getTime()>t.$app.getData("config").groupmass.reback_open[0]&&t.$app.getTime()<t.$app.getData("config").groupmass.reback_open[1]&&(t.reback_open=!0,t.daycount_reback=a.data.reback)})}}};a.default=r},"51a7":function(t,a,e){"use strict";e.r(a);var n=e("8f5f"),i=e.n(n);for(var r in n)"default"!==r&&function(t){e.d(a,t,function(){return n[t]})}(r);a["default"]=i.a},5451:function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".button[data-v-641a1844]{color:#6b4a39;-webkit-transition:.3s;transition:.3s;border-radius:%?20?%}.button.scale[data-v-641a1844]{-webkit-transform:scale(.7);transform:scale(.7)}.button.default[data-v-641a1844]{color:#fff;background:-webkit-linear-gradient(left top,#f8648a,red);background:linear-gradient(to right bottom,#f8648a,red);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3)}.button.big[data-v-641a1844]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2dxu4igebg303v02cgld.gif) 50% no-repeat/100% 100%}.button.success[data-v-641a1844]{color:#fff;box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3);background:-webkit-linear-gradient(left top,#28a745,#70c183);background:linear-gradient(to right bottom,#28a745,#70c183)}.button.disable[data-v-641a1844]{color:#fff;background:-webkit-linear-gradient(left top,#aaa,#666);background:linear-gradient(to right bottom,#aaa,#666);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3)}.button.fangde[data-v-641a1844]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2jwmn4sshg305v05vwea.gif) 50% no-repeat/100% 100%}.button.css[data-v-641a1844]{color:#fff;background:-webkit-linear-gradient(left top,#f8648a,red);background:linear-gradient(to right bottom,#f8648a,red);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3)}.button.color[data-v-641a1844]{background-color:#efccc8;border-radius:%?60?%;box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3)}",""])},"5a6e":function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".group-container .top-container[data-v-7dbe7402]{margin:%?30?%;background-color:#fff;border-radius:%?10?%;padding:%?20?% %?30?%;box-shadow:0 %?2?% %?16?% hsla(0,0%,40%,.3);position:relative;z-index:1}.group-container .top-container .top-wrap[data-v-7dbe7402]{font-size:%?32?%;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.group-container .top-container .scroll-wrap[data-v-7dbe7402]{height:%?150?%;overflow:hidden;position:relative}.group-container .top-container .scroll-wrap .scroll[data-v-7dbe7402]{position:absolute;-webkit-transition:.8s;transition:.8s}.group-container .top-container .scroll-wrap .scroll .scroll-item[data-v-7dbe7402]{margin:%?10?% 0}.group-container .top-container .scroll-wrap .scroll .scroll-item .item[data-v-7dbe7402]{font-size:%?22?%;padding:%?5?% %?10?%;display:inline-block;background-color:#f5f5f5;border-radius:%?20?%;color:#676767}.group-container .top-container .scroll-wrap .scroll .scroll-item .item uni-image[data-v-7dbe7402]{width:%?24?%;height:%?24?%}.group-container .top-container .scroll-wrap .scroll .scroll-item .item .name[data-v-7dbe7402]{color:#fe5d63}.group-container .mid-container[data-v-7dbe7402]{padding:%?20?% %?30?%;background-color:#fff;padding-top:%?80?%;margin-top:%?-80?%}.group-container .mid-container .top-wrap[data-v-7dbe7402]{-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.group-container .mid-container .top-wrap .left .t[data-v-7dbe7402]{font-size:%?32?%}.group-container .mid-container .top-wrap .left .b[data-v-7dbe7402]{font-size:%?22?%;color:#868686}.group-container .mid-container .list-container .item[data-v-7dbe7402]{padding:%?20?% 0;border-bottom:%?1?% solid #eee}.group-container .mid-container .list-container .item .top[data-v-7dbe7402]{font-size:%?32?%;margin:%?10?%;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start}.group-container .mid-container .list-container .item .top .icon[data-v-7dbe7402]{width:%?36?%;height:%?36?%;margin-right:%?10?%}.group-container .mid-container .list-container .item .bottom[data-v-7dbe7402]{-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.group-container .mid-container .list-container .item .bottom .left uni-image[data-v-7dbe7402]{width:%?50?%;height:%?50?%;border-radius:50%}.group-container .mid-container .list-container .item .bottom .left .ava-1[data-v-7dbe7402]{z-index:5}.group-container .mid-container .list-container .item .bottom .left .ava-2[data-v-7dbe7402]{z-index:4;margin-left:%?-20?%}.group-container .mid-container .list-container .item .bottom .left .ava-3[data-v-7dbe7402]{z-index:3;margin-left:%?-20?%}.group-container .mid-container .list-container .item .bottom .left .ava-4[data-v-7dbe7402]{z-index:2;margin-left:%?-20?%}.group-container .mid-container .list-container .item .bottom .left .ava-5[data-v-7dbe7402]{z-index:1;margin-left:%?-20?%}.group-container .mid-container .list-container .item .bottom .left .b[data-v-7dbe7402]{margin-left:%?10?%;color:#868686}",""])},"8f5f":function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{scale:""}},props:{type:{default:""}}};a.default=n},"9e6b":function(t,a,e){"use strict";e.r(a);var n=e("3793"),i=e("fdf3");for(var r in i)"default"!==r&&function(t){e.d(a,t,function(){return i[t]})}(r);e("1e5a");var o,s=e("f0c5"),c=Object(s["a"])(i["default"],n["b"],n["c"],!1,null,"7dbe7402",null,!1,n["a"],o);a["default"]=c.exports},a454:function(t,a,e){"use strict";var n=e("2872"),i=e.n(n);i.a},ac96:function(t,a,e){"use strict";var n,i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"button flex-set",class:[t.type,t.scale],on:{touchstart:function(a){arguments[0]=a=t.$handleEvent(a),t.scale="scale"},touchend:function(a){arguments[0]=a=t.$handleEvent(a),t.scale=""}}},[t._t("default")],2)},r=[];e.d(a,"b",function(){return i}),e.d(a,"c",function(){return r}),e.d(a,"a",function(){return n})},faec:function(t,a,e){var n=e("5a6e");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("360a8b5a",n,!0,{sourceMap:!1,shadowMode:!1})},fdf3:function(t,a,e){"use strict";e.r(a);var n=e("3931"),i=e.n(n);for(var r in n)"default"!==r&&function(t){e.d(a,t,function(){return n[t]})}(r);a["default"]=i.a}}]);