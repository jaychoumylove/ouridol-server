(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-signin-signin"],{"40a6":function(n,t,i){"use strict";i.r(t);var a=i("9de7"),e=i("d460");for(var o in e)"default"!==o&&function(n){i.d(t,n,function(){return e[n]})}(o);i("9925");var c=i("2877"),r=Object(c["a"])(e["default"],a["a"],a["b"],!1,null,"2f9c98fc",null);t["default"]=r.exports},"456f":function(n,t,i){var a=i("c978");"string"===typeof a&&(a=[[n.i,a,""]]),a.locals&&(n.exports=a.locals);var e=i("4f06").default;e("7e5a180c",a,!0,{sourceMap:!1,shadowMode:!1})},5012:function(n,t,i){t=n.exports=i("2350")(!1),t.push([n.i,".button[data-v-8163c7b2]{color:#6b4a39;-webkit-transition:.3s;-o-transition:.3s;transition:.3s}.button.scale[data-v-8163c7b2]{-webkit-transform:scale(.7);-ms-transform:scale(.7);transform:scale(.7)}.button.default[data-v-8163c7b2]{background:url(http://wx2.sinaimg.cn/large/0060lm7Tly1g2coigwh4sg303s01p741.gif) 50% no-repeat/100% 100%}.button.big[data-v-8163c7b2]{background:url(http://wx4.sinaimg.cn/large/0060lm7Tly1g2dxu4igebg303v02cgld.gif) 50% no-repeat/100% 100%}.button.success[data-v-8163c7b2]{background:url(http://wx4.sinaimg.cn/large/0060lm7Tly1g2ey5oz2oag303s01p741.gif) 50% no-repeat/100% 100%;color:#fff}.button.disable[data-v-8163c7b2]{background:url(http://wx1.sinaimg.cn/large/0060lm7Tly1g2ey5nkm77g303s01p741.gif) 50% no-repeat/100% 100%}.button.fangde[data-v-8163c7b2]{background:url(http://wx2.sinaimg.cn/large/0060lm7Tly1g2jwmn4sshg305v05vwea.gif) 50% no-repeat/100% 100%}.button.css[data-v-8163c7b2]{background-color:#ffd1b2;border-radius:%?60?%;-webkit-box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3)}",""])},"728b":function(n,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var a=e(i("cbb7"));function e(n){return n&&n.__esModule?n:{default:n}}var o={components:{btnComponent:a.default},data:function(){return{siginList:[],signin_day:1,signin_coin:0}},onLoad:function(){var n=this,t=setInterval(function(){n.$app.getData("token")&&(clearInterval(t),n.getSignin())},300)},methods:{getSignin:function(){var n=this;this.$app.request(this.$app.API.USER_SIGNIN,{},function(t){n.siginList=t.data.cfg,n.signin_day=t.data.signin_day,t.data.coin&&(n.signin_coin=t.data.coin,n.$app.request(n.$app.API.USER_CURRENCY,{},function(t){n.$app.setData("userCurrency",t.data)}))})}}};t.default=o},8710:function(n,t,i){var a=i("5012");"string"===typeof a&&(a=[[n.i,a,""]]),a.locals&&(n.exports=a.locals);var e=i("4f06").default;e("1ce53c5f",a,!0,{sourceMap:!1,shadowMode:!1})},9925:function(n,t,i){"use strict";var a=i("456f"),e=i.n(a);e.a},"9de7":function(n,t,i){"use strict";var a=function(){var n=this,t=n.$createElement,i=n._self._c||t;return i("v-uni-view",{staticClass:"signin-container"},[i("v-uni-view",{staticClass:"main-container"},[i("v-uni-view",{staticClass:"top-container flex-set"},[i("v-uni-image",{staticClass:"top-img",attrs:{src:"/static/image/guild/card-c.png",mode:""}}),i("v-uni-view",{staticClass:"text"},[n._v(n._s(n.signin_coin?"签到成功":"今日已签到"))]),n.signin_coin?i("v-uni-view",{staticClass:"coin flex-set"},[n._v("+"+n._s(n.signin_coin)),i("v-uni-image",{attrs:{src:"/static/image/user/b1.png",mode:"widthFix"}})],1):n._e()],1),i("v-uni-view",{staticClass:"section-container"},[i("v-uni-view",{staticClass:"row r-1"},n._l(n.siginList,function(t,a){return i("v-uni-view",{key:a},[n._v("+"+n._s(t.coin))])}),1),i("v-uni-view",{staticClass:"row line"},n._l(n.siginList,function(t,a){return i("v-uni-view",{key:a,staticClass:"ball",class:{active:a+1<=n.signin_day}})}),1),i("v-uni-view",{staticClass:"row r-3"},n._l(n.siginList,function(t,a){return i("v-uni-view",{key:a},[n._v(n._s(t.days)+"天")])}),1)],1),i("v-uni-view",{staticClass:"tips"},[n._v("您已累计连续签到"+n._s(n.signin_day)+"天，坚持累计签到可获得更多能量")]),i("btnComponent",{attrs:{type:"css"}},[i("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"160upx",height:"80upx"},on:{click:function(t){t=n.$handleEvent(t),n.$app.goPage("/pages/recharge/recharge")}}},[n._v("更多能量")])],1)],1)],1)},e=[];i.d(t,"a",function(){return a}),i.d(t,"b",function(){return e})},a26f:function(n,t,i){"use strict";var a=function(){var n=this,t=n.$createElement,i=n._self._c||t;return i("v-uni-view",{staticClass:"button flex-set",class:[n.type,n.scale],on:{touchstart:function(t){t=n.$handleEvent(t),n.scale="scale"},touchend:function(t){t=n.$handleEvent(t),n.scale=""}}},[n._t("default")],2)},e=[];i.d(t,"a",function(){return a}),i.d(t,"b",function(){return e})},c978:function(n,t,i){t=n.exports=i("2350")(!1),t.push([n.i,'.signin-container[data-v-2f9c98fc]{height:100%;padding-top:%?100?%}.signin-container .main-container[data-v-2f9c98fc]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;width:%?600?%;height:%?600?%;margin:auto;background-color:#fff;-webkit-box-shadow:0 %?10?% %?20?% rgba(0,0,0,.3);box-shadow:0 %?10?% %?20?% rgba(0,0,0,.3);border-radius:%?30?%;overflow:hidden}.signin-container .main-container .top-container[data-v-2f9c98fc]{width:100%;height:%?260?%;background-color:rgba(255,209,178,.3);text-align:center;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around;padding:%?30?%}.signin-container .main-container .top-container .top-img[data-v-2f9c98fc]{width:%?80?%;height:%?80?%}.signin-container .main-container .top-container .text[data-v-2f9c98fc]{font-size:%?32?%;font-weight:700}.signin-container .main-container .top-container .coin uni-image[data-v-2f9c98fc]{width:%?30?%;min-height:%?30?%}.signin-container .main-container .section-container[data-v-2f9c98fc]{margin:%?20?% 0;width:100%}.signin-container .main-container .section-container .row[data-v-2f9c98fc]{position:relative;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;margin:%?10?% %?60?%}.signin-container .main-container .section-container .row .ball[data-v-2f9c98fc]{width:%?16?%;height:%?16?%;border-radius:50%;background-color:#ddd}.signin-container .main-container .section-container .row .ball.active[data-v-2f9c98fc]{position:relative;background-color:#ece3e4;width:%?30?%;height:%?30?%}.signin-container .main-container .section-container .row .ball.active[data-v-2f9c98fc]:before{content:"";position:absolute;border-radius:50%;background-color:#ff9700;top:50%;left:50%;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%);width:%?16?%;height:%?16?%}.signin-container .main-container .section-container .row.r-1[data-v-2f9c98fc]{margin:%?10?% %?30?%}.signin-container .main-container .section-container .row.r-3[data-v-2f9c98fc]{margin:%?10?% %?40?%}.signin-container .main-container .section-container .row.line[data-v-2f9c98fc]:before{content:"";position:absolute;width:100%;border-top:1px solid #ddd;top:50%;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);transform:translateY(-50%)}.signin-container .main-container .tips[data-v-2f9c98fc]{font-size:%?22?%;color:#888;margin-bottom:%?20?%}',""])},cbb7:function(n,t,i){"use strict";i.r(t);var a=i("a26f"),e=i("d29f");for(var o in e)"default"!==o&&function(n){i.d(t,n,function(){return e[n]})}(o);i("ce25");var c=i("2877"),r=Object(c["a"])(e["default"],a["a"],a["b"],!1,null,"8163c7b2",null);t["default"]=r.exports},ce25:function(n,t,i){"use strict";var a=i("8710"),e=i.n(a);e.a},d29f:function(n,t,i){"use strict";i.r(t);var a=i("f7e0"),e=i.n(a);for(var o in a)"default"!==o&&function(n){i.d(t,n,function(){return a[n]})}(o);t["default"]=e.a},d460:function(n,t,i){"use strict";i.r(t);var a=i("728b"),e=i.n(a);for(var o in a)"default"!==o&&function(n){i.d(t,n,function(){return a[n]})}(o);t["default"]=e.a},f7e0:function(n,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var a={data:function(){return{scale:""}},props:{type:{default:""}}};t.default=a}}]);