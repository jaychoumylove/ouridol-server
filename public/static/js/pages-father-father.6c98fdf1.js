(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-father-father"],{"110d":function(t,a,e){"use strict";e.r(a);var n=e("3d21"),i=e("7773");for(var r in i)"default"!==r&&function(t){e.d(a,t,function(){return i[t]})}(r);e("e658");var s=e("2877"),o=Object(s["a"])(i["default"],n["a"],n["b"],!1,null,"095b898a",null);a["default"]=o.exports},"3d21":function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"father-container"},[e("v-uni-view",{staticClass:"top-container"},[e("v-uni-image",{staticClass:"avatar",attrs:{src:t.userInfo.avatarurl,mode:"aspectFill"}}),e("v-uni-view",{staticClass:"text-wrapper text-overflow left"},[e("v-uni-view",{staticClass:"row-wrapper flex-set"},[t._v(t._s(t.userInfo.nickname))]),e("v-uni-view",{staticClass:"row-wrapper flex-set s"},[e("v-uni-image",{attrs:{src:"/static/image/guild/ft-1.png",mode:"widthFix"}}),t._v("徒弟人数："+t._s(t.sonTotal))],1),e("v-uni-view",{staticClass:"row-wrapper flex-set s"},[e("v-uni-image",{attrs:{src:"/static/image/guild/ft-2.png",mode:"widthFix"}}),t._v("今日收益："+t._s(t.todayTotal))],1)],1),e("v-uni-view",{staticClass:"text-wrapper btn"},[e("v-uni-button",{attrs:{"open-type":"share","data-share":"3"}},[e("v-uni-image",{staticClass:"big",attrs:{src:"/static/image/guild/ft-3.png",mode:"widthFix"}}),e("v-uni-view",{staticClass:"text"},[t._v("收徒")])],1)],1)],1),e("v-uni-view",{staticClass:"explain-container flex-set"},[e("v-uni-image",{attrs:{src:t.$app.getData("config").father_tips_img,mode:"widthFix"}})],1),t.sonList&&t.sonList.length>0?e("v-uni-view",{staticClass:"rank-list-container"},t._l(t.sonList,function(a,n){return e("v-uni-view",{key:n,staticClass:"item"},[e("v-uni-view",{staticClass:"rank-num"},[n<3?e("v-uni-image",{attrs:{src:"/static/image/guild/"+(n+1)+".png",mode:"widthFix"}}):e("v-uni-view",[t._v(t._s(n+1))])],1),e("v-uni-view",{staticClass:"avatar"},[e("v-uni-image",{attrs:{src:a.avatarurl,mode:"aspectFill"}})],1),e("v-uni-view",{staticClass:"text-container"},[e("v-uni-view",{staticClass:"star-name text-overflow"},[t._v(t._s(a.nickname))]),e("v-uni-view",{staticClass:"bottom-text"},[a.cur_contribute?e("v-uni-view",{staticClass:"hot-count"},[t._v("贡献"+t._s(a.cur_contribute))]):t._e()],1)],1),a.earn?e("v-uni-view",{staticClass:"add-count flex-set"},[e("v-uni-text",[t._v("+"+t._s(a.earn))]),e("v-uni-image",{attrs:{src:"/static/image/user/b1.png",mode:"widthFix"}})],1):t._e(),e("v-uni-view",{staticClass:"btn",on:{click:function(e){e=t.$handleEvent(e),t.getSonEarn(a.uid,a.earn,n)}}},[e("btnComponent",{attrs:{type:"default"}},[e("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v("领取")])],1)],1)],1)}),1):e("v-uni-view",{staticClass:"nodata flex-set"},[e("v-uni-view",{staticClass:"top"},[t._v("你还没有徒弟")]),e("v-uni-button",{attrs:{"open-type":"share","data-share":"3"}},[e("v-uni-view",{staticClass:"bottom"},[t._v("收一个徒弟>")])],1)],1)],1)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})},5012:function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".button[data-v-8163c7b2]{color:#6b4a39;-webkit-transition:.3s;-o-transition:.3s;transition:.3s}.button.scale[data-v-8163c7b2]{-webkit-transform:scale(.7);-ms-transform:scale(.7);transform:scale(.7)}.button.default[data-v-8163c7b2]{background:url(http://wx2.sinaimg.cn/large/0060lm7Tly1g2coigwh4sg303s01p741.gif) 50% no-repeat/100% 100%}.button.big[data-v-8163c7b2]{background:url(http://wx4.sinaimg.cn/large/0060lm7Tly1g2dxu4igebg303v02cgld.gif) 50% no-repeat/100% 100%}.button.success[data-v-8163c7b2]{background:url(http://wx4.sinaimg.cn/large/0060lm7Tly1g2ey5oz2oag303s01p741.gif) 50% no-repeat/100% 100%;color:#fff}.button.disable[data-v-8163c7b2]{background:url(http://wx1.sinaimg.cn/large/0060lm7Tly1g2ey5nkm77g303s01p741.gif) 50% no-repeat/100% 100%}.button.fangde[data-v-8163c7b2]{background:url(http://wx2.sinaimg.cn/large/0060lm7Tly1g2jwmn4sshg305v05vwea.gif) 50% no-repeat/100% 100%}.button.css[data-v-8163c7b2]{background-color:#ffd1b2;border-radius:%?60?%;-webkit-box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3)}",""])},7773:function(t,a,e){"use strict";e.r(a);var n=e("d442"),i=e.n(n);for(var r in n)"default"!==r&&function(t){e.d(a,t,function(){return n[t]})}(r);a["default"]=i.a},8710:function(t,a,e){var n=e("5012");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("1ce53c5f",n,!0,{sourceMap:!1,shadowMode:!1})},a26f:function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"button flex-set",class:[t.type,t.scale],on:{touchstart:function(a){a=t.$handleEvent(a),t.scale="scale"},touchend:function(a){a=t.$handleEvent(a),t.scale=""}}},[t._t("default")],2)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})},a870:function(t,a,e){var n=e("d682");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("477150a0",n,!0,{sourceMap:!1,shadowMode:!1})},cbb7:function(t,a,e){"use strict";e.r(a);var n=e("a26f"),i=e("d29f");for(var r in i)"default"!==r&&function(t){e.d(a,t,function(){return i[t]})}(r);e("ce25");var s=e("2877"),o=Object(s["a"])(i["default"],n["a"],n["b"],!1,null,"8163c7b2",null);a["default"]=o.exports},ce25:function(t,a,e){"use strict";var n=e("8710"),i=e.n(n);i.a},d29f:function(t,a,e){"use strict";e.r(a);var n=e("f7e0"),i=e.n(n);for(var r in n)"default"!==r&&function(t){e.d(a,t,function(){return n[t]})}(r);a["default"]=i.a},d442:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n=i(e("cbb7"));function i(t){return t&&t.__esModule?t:{default:t}}var r={components:{btnComponent:n.default},data:function(){return{requestCount:1,sonTotal:0,todayTotal:0,sonList:[],userInfo:{avatarurl:this.$app.getData("userInfo")["avatarurl"]||this.$app.AVATAR,nickname:this.$app.getData("userInfo")["nickname"]||this.$app.NICKNAME,id:this.$app.getData("userInfo")["id"]||null}}},onLoad:function(){this.getFatherInfo()},onShareAppMessage:function(t){var a=t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(a)},methods:{getSonEarn:function(t,a,e){var n=this;a?this.$app.request(this.$app.API.USER_SONEARN,{user_id:t},function(t){n.sonList[e].earn=0,n.$app.toast("获得收益+"+t.data+"能量"),n.getFatherInfo(),n.$app.request(n.$app.API.USER_CURRENCY,{},function(t){n.$app.setData("userCurrency",t.data)})},"POST",!0):this.$app.toast("TA的收益太少了")},getFatherInfo:function(){var t=this;this.$app.request(this.$app.API.USER_FATHER,{type:2},function(a){var e=[],n=0,i=!0,r=!1,s=void 0;try{for(var o,c=a.data[Symbol.iterator]();!(i=(o=c.next()).done);i=!0){var l=o.value;n+=l.has_earn_count,e.push({uid:l.user&&l.user.id,avatarurl:l.user&&l.user.avatarurl||t.$app.AVATAR,nickname:l.user&&l.user.nickname||t.$app.NICKNAME,cur_contribute:l.cur_contribute,earn:l.user_earn})}}catch(u){r=!0,s=u}finally{try{i||null==c.return||c.return()}finally{if(r)throw s}}t.todayTotal=n,t.sonTotal=e.length,t.sonList=e,t.$app.closeLoading(t)})}}};a.default=r},d682:function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".father-container .top-container[data-v-095b898a]{padding:%?40?% %?60?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.father-container .top-container .avatar[data-v-095b898a]{position:relative;overflow:hidden;border-radius:50%;width:%?150?%;height:%?150?%}.father-container .top-container .text-wrapper[data-v-095b898a]{line-height:1.5}.father-container .top-container .text-wrapper .row-wrapper[data-v-095b898a]{-webkit-box-pack:start;-webkit-justify-content:flex-start;-ms-flex-pack:start;justify-content:flex-start;font-size:%?34?%}.father-container .top-container .text-wrapper .row-wrapper uni-image[data-v-095b898a]{width:%?30?%;margin-right:%?10?%}.father-container .top-container .text-wrapper .row-wrapper.s[data-v-095b898a]{font-size:%?26?%}.father-container .top-container .text-wrapper.left[data-v-095b898a]{margin-left:%?-50?%}.father-container .top-container .text-wrapper.btn[data-v-095b898a]{position:relative}.father-container .top-container .text-wrapper.btn uni-button[data-v-095b898a]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.father-container .top-container .text-wrapper.btn uni-image[data-v-095b898a]{width:%?80?%}.father-container .top-container .text-wrapper.btn .text[data-v-095b898a]{font-weight:700;font-size:%?40?%;padding-left:%?10?%}.father-container .explain-container[data-v-095b898a]{background-color:#e5b4b0;font-size:%?24?%}.father-container .rank-list-container[data-v-095b898a]{padding:%?10?% 0}.father-container .rank-list-container .item[data-v-095b898a]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;padding:%?15?% 0;background-color:hsla(0,0%,100%,.3);margin:%?10?% 0}.father-container .rank-list-container .item .rank-num[data-v-095b898a]{width:%?90?%;text-align:center}.father-container .rank-list-container .item .rank-num uni-image[data-v-095b898a]{width:%?40?%}.father-container .rank-list-container .item .avatar uni-image[data-v-095b898a]{width:%?100?%;height:%?100?%;border-radius:50%}.father-container .rank-list-container .item .text-container[data-v-095b898a]{margin-left:%?20?%;line-height:1.5;width:%?200?%}.father-container .rank-list-container .item .text-container .bottom-text[data-v-095b898a]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.father-container .rank-list-container .item .text-container .bottom-text .hot-count[data-v-095b898a]{color:#ce797c;margin-right:%?4?%;font-size:%?24?%}.father-container .rank-list-container .item .add-count uni-image[data-v-095b898a]{width:%?36?%}.father-container .rank-list-container .item .btn[data-v-095b898a]{font-size:%?26?%;position:absolute;right:%?40?%}.father-container .nodata[data-v-095b898a]{height:%?400?%;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column}.father-container .nodata .bottom[data-v-095b898a]{color:#ce797c;font-size:%?40?%}",""])},e658:function(t,a,e){"use strict";var n=e("a870"),i=e.n(n);i.a},f7e0:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{scale:""}},props:{type:{default:""}}};a.default=n}}]);