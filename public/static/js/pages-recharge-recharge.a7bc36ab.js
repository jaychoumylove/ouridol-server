(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-recharge-recharge"],{"0f83":function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"container"},[e("v-uni-view",{staticClass:"user-container"},[e("v-uni-image",{attrs:{src:t.userInfo.avatarurl,mode:"widthFix"}}),e("v-uni-view",{staticClass:"nickname"},[t._v(t._s(t.userInfo.nickname))])],1),e("v-uni-view",{staticClass:"row"},[e("v-uni-image",{staticClass:"bg",attrs:{src:"/static/image/recharge/top-title.png",mode:"widthFix"}}),e("v-uni-view",{},[t._v("能量充值")])],1),e("v-uni-view",{staticClass:"count-wrap"},[e("v-uni-view",{staticClass:"top-title"},[t._v("我的能量："+t._s(t.userCurrency.coin))]),e("v-uni-view",{staticClass:"top-title"},[t._v("我的灵丹："+t._s(t.userCurrency.stone))])],1),e("v-uni-view",{staticClass:"btn-wrapper"},t._l(t.rechargeList,function(a,n){return e("v-uni-view",{key:n,staticClass:"btn",on:{click:function(e){e=t.$handleEvent(e),t.payment(a.id)}}},[e("v-uni-image",{staticClass:"icon",attrs:{src:a.item.icon,mode:"widthFix"}}),e("v-uni-view",{staticClass:"line one flex-set"},[e("v-uni-image",{staticClass:"sicon",attrs:{src:"/static/image/user/b1.png",mode:"widthFix"}}),t._v(t._s(a.item.count))],1),e("v-uni-view",{staticClass:"name flex-set"},[t._v(t._s(a.item.name))]),e("v-uni-view",{staticClass:"line two flex-set"},[e("v-uni-image",{staticClass:"sicon",attrs:{src:"/static/image/user/b2.png",mode:"widthFix"}}),t._v("+"+t._s(a.stone))],1),e("v-uni-view",{staticClass:"fee flex-set"},[t._v("￥"+t._s(a.fee))])],1)}),1)],1)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})},2779:function(t,a,e){"use strict";e.r(a);var n=e("618e"),i=e.n(n);for(var r in n)"default"!==r&&function(t){e.d(a,t,function(){return n[t]})}(r);a["default"]=i.a},5012:function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".button[data-v-8163c7b2]{color:#6b4a39;-webkit-transition:.3s;-o-transition:.3s;transition:.3s}.button.scale[data-v-8163c7b2]{-webkit-transform:scale(.7);-ms-transform:scale(.7);transform:scale(.7)}.button.default[data-v-8163c7b2]{background:url(http://wx2.sinaimg.cn/large/0060lm7Tly1g2coigwh4sg303s01p741.gif) 50% no-repeat/100% 100%}.button.big[data-v-8163c7b2]{background:url(http://wx4.sinaimg.cn/large/0060lm7Tly1g2dxu4igebg303v02cgld.gif) 50% no-repeat/100% 100%}.button.success[data-v-8163c7b2]{background:url(http://wx4.sinaimg.cn/large/0060lm7Tly1g2ey5oz2oag303s01p741.gif) 50% no-repeat/100% 100%;color:#fff}.button.disable[data-v-8163c7b2]{background:url(http://wx1.sinaimg.cn/large/0060lm7Tly1g2ey5nkm77g303s01p741.gif) 50% no-repeat/100% 100%}.button.fangde[data-v-8163c7b2]{background:url(http://wx2.sinaimg.cn/large/0060lm7Tly1g2jwmn4sshg305v05vwea.gif) 50% no-repeat/100% 100%}.button.css[data-v-8163c7b2]{background-color:#ffd1b2;-webkit-border-radius:%?60?%;border-radius:%?60?%;-webkit-box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3)}",""])},"5f55":function(t,a,e){"use strict";var n=e("aa1b"),i=e.n(n);i.a},"618e":function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n,i=r(e("cbb7"));function r(t){return t&&t.__esModule?t:{default:t}}var s={components:{btnComponent:i.default},data:function(){return{requestCount:1,userInfo:{avatarurl:this.$app.getData("userInfo")["avatarurl"]||this.$app.AVATAR,nickname:this.$app.getData("userInfo")["nickname"]||this.$app.NICKNAME,id:this.$app.getData("userInfo")["id"]||null},userCurrency:this.$app.getData("userCurrency")||{coin:0,stone:0,trumpet:0},rechargeList:this.$app.getData("goodsList")||[]}},onLoad:function(){var t=this;this.getGoodsList();setInterval(function(){t.$app.getData("userInfo").nickname&&(t.userInfo=t.$app.getData("userInfo"),t.userCurrency=t.$app.getData("userCurrency"))},300)},onUnload:function(){clearInterval(n)},methods:{payment:function(t){var a=this;this.$app.request(this.$app.API.PAY_ORDER,{goods_id:t},function(t){WeixinJSBridge.invoke("getBrandWCPayRequest",{appId:t.data.appId,timeStamp:t.data.timeStamp,nonceStr:t.data.nonceStr,package:t.data.package,signType:t.data.signType,paySign:t.data.paySign},function(t){console.log(t),"get_brand_wcpay_request:ok"==t.err_msg&&(a.$app.toast("支付成功","success"),a.$app.request(a.$app.API.USER_CURRENCY,{},function(t){a.$app.setData("userCurrency",t.data),a.userCurrency=a.$app.getData("userCurrency"),a.modal=""}))})})},getGoodsList:function(){var t=this;this.$app.request(this.$app.API.PAY_GOODS,{},function(a){var e=[],n=!0,i=!1,r=void 0;try{for(var s,o=a.data[Symbol.iterator]();!(n=(s=o.next()).done);n=!0){var c=s.value;e.push({id:c.id,coin:c.coin,stone:c.stone,fee:c.fee,item:c.item})}}catch(u){i=!0,r=u}finally{try{n||null==o.return||o.return()}finally{if(i)throw r}}t.rechargeList=e,t.$app.setData("goodsList",t.rechargeList),t.$app.closeLoading(t)})}}};a.default=s},"6e85":function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".container[data-v-09fab32a]{padding-top:%?100?%}.container .user-container[data-v-09fab32a]{position:absolute;height:%?60?%;top:%?40?%;left:%?40?%;background-color:hsla(0,0%,100%,.3);display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-border-radius:%?30?%;border-radius:%?30?%}.container .user-container uni-image[data-v-09fab32a]{width:%?60?%;-webkit-border-radius:50%;border-radius:50%;margin-right:%?20?%}.container .user-container .nickname[data-v-09fab32a]{font-size:%?32?%;margin-right:%?30?%}.container .row[data-v-09fab32a]{position:relative;height:%?115?%;margin:0 %?40?%;margin-top:%?50?%;text-align:center;line-height:%?115?%;font-size:%?40?%;font-weight:700}.container .row .bg[data-v-09fab32a]{position:absolute;z-index:-1;left:0;top:0}.container .count-wrap[data-v-09fab32a]{background-color:#fac7cc;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around;margin:0 %?40?%;line-height:%?100?%}.container .btn-wrapper[data-v-09fab32a]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;background-color:#fff;margin:0 %?40?%;margin-bottom:%?20?%;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;padding:%?8?%}.container .btn-wrapper .btn[data-v-09fab32a]{background-color:#fac7cc;width:%?200?%;height:%?320?%;margin:%?8?%;position:relative;padding:%?8?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-border-radius:%?10?%;border-radius:%?10?%}.container .btn-wrapper .btn .name[data-v-09fab32a]{width:%?125?%;color:#fa5e86;border-bottom:%?2?% solid #eee}.container .btn-wrapper .btn .icon[data-v-09fab32a]{width:%?125?%;height:%?125?%}.container .btn-wrapper .btn .line .sicon[data-v-09fab32a]{width:%?30?%}.container .btn-wrapper .btn .line.one[data-v-09fab32a]{position:absolute;right:%?30?%;top:%?120?%;-webkit-border-radius:%?20?%;border-radius:%?20?%;background-color:hsla(0,0%,100%,.3);font-size:%?24?%;color:#666}.container .btn-wrapper .btn .line.one .sicon[data-v-09fab32a]{width:%?25?%}.container .btn-wrapper .btn .fee[data-v-09fab32a]{width:%?125?%;background-color:#fff;-webkit-border-radius:%?5?%;border-radius:%?5?%}",""])},8710:function(t,a,e){var n=e("5012");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("1ce53c5f",n,!0,{sourceMap:!1,shadowMode:!1})},a26f:function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"button flex-set",class:[t.type,t.scale],on:{touchstart:function(a){a=t.$handleEvent(a),t.scale="scale"},touchend:function(a){a=t.$handleEvent(a),t.scale=""}}},[t._t("default")],2)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})},aa1b:function(t,a,e){var n=e("6e85");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("f74309f2",n,!0,{sourceMap:!1,shadowMode:!1})},cbb7:function(t,a,e){"use strict";e.r(a);var n=e("a26f"),i=e("d29f");for(var r in i)"default"!==r&&function(t){e.d(a,t,function(){return i[t]})}(r);e("ce25");var s=e("2877"),o=Object(s["a"])(i["default"],n["a"],n["b"],!1,null,"8163c7b2",null);a["default"]=o.exports},ce25:function(t,a,e){"use strict";var n=e("8710"),i=e.n(n);i.a},d29f:function(t,a,e){"use strict";e.r(a);var n=e("f7e0"),i=e.n(n);for(var r in n)"default"!==r&&function(t){e.d(a,t,function(){return n[t]})}(r);a["default"]=i.a},ee2e:function(t,a,e){"use strict";e.r(a);var n=e("0f83"),i=e("2779");for(var r in i)"default"!==r&&function(t){e.d(a,t,function(){return i[t]})}(r);e("5f55");var s=e("2877"),o=Object(s["a"])(i["default"],n["a"],n["b"],!1,null,"09fab32a",null);a["default"]=o.exports},f7e0:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{scale:""}},props:{type:{default:""}}};a.default=n}}]);