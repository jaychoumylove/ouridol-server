(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-signin-signin"],{"1bfc":function(n,t,a){"use strict";var i=a("288e");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var e=i(a("cbb7")),o={components:{btnComponent:e.default},data:function(){return{siginList:[],signin_day:1,signin_coin:0}},onLoad:function(){this.getSignin()},methods:{getSignin:function(){var n=this;this.$app.request(this.$app.API.USER_SIGNIN,{},function(t){n.siginList=t.data.cfg,n.signin_day=t.data.signin_day,t.data.coin&&(n.signin_coin=t.data.coin,n.$app.request(n.$app.API.USER_CURRENCY,{},function(t){n.$app.setData("userCurrency",t.data)}))})}}};t.default=o},"27d8":function(n,t,a){"use strict";var i=function(){var n=this,t=n.$createElement,a=n._self._c||t;return a("v-uni-view",{staticClass:"signin-container"},[a("v-uni-view",{staticClass:"main-container"},[a("v-uni-view",{staticClass:"top-container flex-set"},[a("v-uni-image",{staticClass:"top-img",attrs:{src:"/static/image/guild/card-c.png",mode:""}}),a("v-uni-view",{staticClass:"text"},[n._v(n._s(n.signin_coin?"签到成功":"今日已签到"))]),n.signin_coin?a("v-uni-view",{staticClass:"coin flex-set"},[n._v("+"+n._s(n.signin_coin)),a("v-uni-image",{attrs:{src:"/static/image/user/b1.png",mode:"widthFix"}})],1):n._e()],1),a("v-uni-view",{staticClass:"section-container"},[a("v-uni-view",{staticClass:"row r-1"},n._l(n.siginList,function(t,i){return a("v-uni-view",{key:i},[n._v("+"+n._s(t.coin))])}),1),a("v-uni-view",{staticClass:"row line"},n._l(n.siginList,function(t,i){return a("v-uni-view",{key:i,staticClass:"ball",class:{active:i+1<=n.signin_day}})}),1),a("v-uni-view",{staticClass:"row r-3"},n._l(n.siginList,function(t,i){return a("v-uni-view",{key:i},[n._v(n._s(t.days)+"天")])}),1)],1),a("v-uni-view",{staticClass:"tips"},[n._v("您已累计连续签到"+n._s(n.signin_day)+"天，坚持累计签到可获得更多能量")]),a("btnComponent",{attrs:{type:"css"}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"160upx",height:"80upx"},on:{click:function(t){t=n.$handleEvent(t),n.$app.goPage("/pages/recharge/recharge")}}},[n._v("更多能量")])],1)],1)],1)},e=[];a.d(t,"a",function(){return i}),a.d(t,"b",function(){return e})},"40a6":function(n,t,a){"use strict";a.r(t);var i=a("27d8"),e=a("d460");for(var o in e)"default"!==o&&function(n){a.d(t,n,function(){return e[n]})}(o);a("c684");var r=a("2877"),c=Object(r["a"])(e["default"],i["a"],i["b"],!1,null,"407ca5aa",null);t["default"]=c.exports},"5eac":function(n,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var i={data:function(){return{scale:""}},props:{type:{default:""}}};t.default=i},7821:function(n,t,a){t=n.exports=a("2350")(!1),t.push([n.i,'.signin-container[data-v-407ca5aa]{height:100%;padding-top:%?100?%}.signin-container .main-container[data-v-407ca5aa]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;width:%?600?%;height:%?600?%;margin:auto;background-color:#fff;-webkit-box-shadow:0 %?10?% %?20?% rgba(0,0,0,.3);box-shadow:0 %?10?% %?20?% rgba(0,0,0,.3);border-radius:%?30?%;overflow:hidden}.signin-container .main-container .top-container[data-v-407ca5aa]{width:100%;height:%?260?%;background-color:rgba(255,209,178,.3);text-align:center;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around;padding:%?30?%}.signin-container .main-container .top-container .top-img[data-v-407ca5aa]{width:%?80?%;height:%?80?%}.signin-container .main-container .top-container .text[data-v-407ca5aa]{font-size:%?32?%;font-weight:700}.signin-container .main-container .top-container .coin uni-image[data-v-407ca5aa]{width:%?30?%;min-height:%?30?%}.signin-container .main-container .section-container[data-v-407ca5aa]{margin:%?20?% 0;width:100%}.signin-container .main-container .section-container .row[data-v-407ca5aa]{position:relative;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;margin:%?10?% %?60?%}.signin-container .main-container .section-container .row .ball[data-v-407ca5aa]{width:%?16?%;height:%?16?%;border-radius:50%;background-color:#ddd}.signin-container .main-container .section-container .row .ball.active[data-v-407ca5aa]{position:relative;background-color:#ece3e4;width:%?30?%;height:%?30?%}.signin-container .main-container .section-container .row .ball.active[data-v-407ca5aa]:before{content:"";position:absolute;border-radius:50%;background-color:#ff9700;top:50%;left:50%;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%);width:%?16?%;height:%?16?%}.signin-container .main-container .section-container .row.r-1[data-v-407ca5aa]{margin:%?10?% %?30?%}.signin-container .main-container .section-container .row.r-3[data-v-407ca5aa]{margin:%?10?% %?40?%}.signin-container .main-container .section-container .row.line[data-v-407ca5aa]:before{content:"";position:absolute;width:100%;border-top:1px solid #ddd;top:50%;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);transform:translateY(-50%)}.signin-container .main-container .tips[data-v-407ca5aa]{font-size:%?22?%;color:#888;margin-bottom:%?20?%}',""])},"7def":function(n,t,a){t=n.exports=a("2350")(!1),t.push([n.i,".button[data-v-15db1d56]{color:#6b4a39;-webkit-transition:.3s;-o-transition:.3s;transition:.3s}.button.scale[data-v-15db1d56]{-webkit-transform:scale(.7);-ms-transform:scale(.7);transform:scale(.7)}.button.default[data-v-15db1d56]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2coigwh4sg303s01p741.gif) 50% no-repeat/100% 100%}.button.big[data-v-15db1d56]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2dxu4igebg303v02cgld.gif) 50% no-repeat/100% 100%}.button.success[data-v-15db1d56]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2ey5oz2oag303s01p741.gif) 50% no-repeat/100% 100%;color:#fff}.button.disable[data-v-15db1d56]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2ey5nkm77g303s01p741.gif) 50% no-repeat/100% 100%}.button.fangde[data-v-15db1d56]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2jwmn4sshg305v05vwea.gif) 50% no-repeat/100% 100%}.button.css[data-v-15db1d56]{background-color:#ffd1b2;border-radius:%?60?%;-webkit-box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3)}.button.color[data-v-15db1d56]{background-color:#efccc8;border-radius:%?60?%;-webkit-box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3)}",""])},8404:function(n,t,a){"use strict";var i=a("c5cc"),e=a.n(i);e.a},c4c2:function(n,t,a){"use strict";var i=function(){var n=this,t=n.$createElement,a=n._self._c||t;return a("v-uni-view",{staticClass:"button flex-set",class:[n.type,n.scale],on:{touchstart:function(t){t=n.$handleEvent(t),n.scale="scale"},touchend:function(t){t=n.$handleEvent(t),n.scale=""}}},[n._t("default")],2)},e=[];a.d(t,"a",function(){return i}),a.d(t,"b",function(){return e})},c5cc:function(n,t,a){var i=a("7def");"string"===typeof i&&(i=[[n.i,i,""]]),i.locals&&(n.exports=i.locals);var e=a("4f06").default;e("715ca6c0",i,!0,{sourceMap:!1,shadowMode:!1})},c684:function(n,t,a){"use strict";var i=a("d5a7"),e=a.n(i);e.a},cbb7:function(n,t,a){"use strict";a.r(t);var i=a("c4c2"),e=a("d29f");for(var o in e)"default"!==o&&function(n){a.d(t,n,function(){return e[n]})}(o);a("8404");var r=a("2877"),c=Object(r["a"])(e["default"],i["a"],i["b"],!1,null,"15db1d56",null);t["default"]=c.exports},d29f:function(n,t,a){"use strict";a.r(t);var i=a("5eac"),e=a.n(i);for(var o in i)"default"!==o&&function(n){a.d(t,n,function(){return i[n]})}(o);t["default"]=e.a},d460:function(n,t,a){"use strict";a.r(t);var i=a("1bfc"),e=a.n(i);for(var o in i)"default"!==o&&function(n){a.d(t,n,function(){return i[n]})}(o);t["default"]=e.a},d5a7:function(n,t,a){var i=a("7821");"string"===typeof i&&(i=[[n.i,i,""]]),i.locals&&(n.exports=i.locals);var e=a("4f06").default;e("91e771a0",i,!0,{sourceMap:!1,shadowMode:!1})}}]);