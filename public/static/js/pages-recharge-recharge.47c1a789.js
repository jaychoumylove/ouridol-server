(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-recharge-recharge"],{"3cf3":function(e,t,a){"use strict";var i=a("ee27");a("ac1f"),a("466d"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var n,r=i(a("54f8")),o=i(a("d285")),s=i(a("0240")),c=i(a("211b")),l={components:{btnComponent:o.default,badgeComponent:s.default,modalComponent:c.default},data:function(){return{modal:"",requestCount:1,userInfo:{avatarurl:this.$app.getData("userInfo")["avatarurl"]||this.$app.getData("AVATAR"),nickname:this.$app.getData("userInfo")["nickname"]||this.$app.getData("NICKNAME"),id:this.$app.getData("userInfo")["id"]||null},userCurrency:this.$app.getData("userCurrency")||{coin:0,stone:0,trumpet:0},rechargeList:this.$app.getData("goodsList")||[],handShow:!1,giftNum:0,currentUser:{avatarurl:this.$app.getData("AVATAR")},currentUserid:"",item_double:!1,pay_type:"",pay_switch:!1,go_browser_modal:!1}},onLoad:function(){var e=this;this.getGoodsList();var t=setInterval((function(){e.$app.getData("userInfo").nickname&&(clearInterval(t),e.userInfo=e.$app.getData("userInfo"),e.userCurrency=e.$app.getData("userCurrency")),"ali_pay"==e.pay_type&&clearInterval(t)}),300),a=this.isWechat();a?(this.pay_type="wechat_pay",this.pay_switch=!0):this.pay_type="ali_pay",this.$app.request("page/gift_num",{},(function(t){e.giftNum=t.data||0}))},onUnload:function(){clearInterval(n)},methods:{isWechat:function(){var e=navigator.userAgent.toLowerCase();return"micromessenger"==e.match(/MicroMessenger/i)},loadGoBrowser:function(e){this.go_browser_modal=e},kickBack:function(){setTimeout((function(){window.scrollTo(0,document.body.scrollTop+1),document.body.scrollTop>=1&&window.scrollTo(0,document.body.scrollTop-1)}),10)},confirm:function(){this.currentUser.nickname?(this.userInfo=this.currentUser,this.modal="",this.getGoodsList()):this.$app.toast("请先查找用户")},searchUser:function(){var e=this;if(this.currentUserid){var t=this.currentUserid/1234;this.$app.request("user/info",{user_id:t},(function(t){t.data.nickname?e.currentUser=t.data:e.$app.toast("未找到用户")}),"POST",!0)}},payment:function(e){var t=this;this.$app.request(this.$app.API.PAY_ORDER,{goods_id:e,user_id:this.userInfo.id,pay_type:this.pay_type},(function(e){"wechat_pay"==t.pay_type&&WeixinJSBridge.invoke("getBrandWCPayRequest",{appId:e.data.appId,timeStamp:e.data.timeStamp,nonceStr:e.data.nonceStr,package:e.data.package,signType:e.data.signType,paySign:e.data.paySign},(function(e){console.log(e),"get_brand_wcpay_request:ok"==e.err_msg&&(t.$app.toast("支付成功","success"),t.$app.request("page/gift_num",{},(function(e){t.giftNum=e.data||0})),t.$app.request(t.$app.API.USER_CURRENCY,{},(function(e){t.$app.setData("userCurrency",e.data),t.userCurrency=t.$app.getData("userCurrency"),t.modal=""})))})),"ali_pay"==t.pay_type&&(document.getElementById("alipay").innerHTML=e.data,setTimeout((function(){document.forms["alipaysubmit"].submit()}),50))}),"POST",!0)},getGoodsList:function(){var e=this,t={};"ali_pay"==this.pay_type&&this.userInfo&&(t.user_id=this.userInfo.id),this.$app.request(this.$app.API.PAY_GOODS,t,(function(t){var a,i=[],n=(0,r.default)(t.data.list);try{for(n.s();!(a=n.n()).done;){var o=a.value;i.push({id:o.id,coin:o.coin,stone:o.stone,fee:o.fee,off_fee:o.off_fee,item:o.item})}}catch(s){n.e(s)}finally{n.f()}e.rechargeList=i,e.item_double=t.data.item_double,e.$app.setData("goodsList",e.rechargeList)}))}}};t.default=l},"51de":function(e,t,a){"use strict";var i=a("f5af"),n=a.n(i);n.a},"54f8":function(e,t,a){"use strict";a.r(t);a("a4d3"),a("e01a"),a("d28b"),a("e260"),a("d3b7"),a("3ca3"),a("ddb0"),a("a630"),a("fb6a"),a("25f0");function i(e,t){(null==t||t>e.length)&&(t=e.length);for(var a=0,i=new Array(t);a<t;a++)i[a]=e[a];return i}function n(e,t){if(e){if("string"===typeof e)return i(e,t);var a=Object.prototype.toString.call(e).slice(8,-1);return"Object"===a&&e.constructor&&(a=e.constructor.name),"Map"===a||"Set"===a?Array.from(a):"Arguments"===a||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a)?i(e,t):void 0}}function r(e){if("undefined"===typeof Symbol||null==e[Symbol.iterator]){if(Array.isArray(e)||(e=n(e))){var t=0,a=function(){};return{s:a,n:function(){return t>=e.length?{done:!0}:{done:!1,value:e[t++]}},e:function(e){throw e},f:a}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,r,o=!0,s=!1;return{s:function(){i=e[Symbol.iterator]()},n:function(){var e=i.next();return o=e.done,e},e:function(e){s=!0,r=e},f:function(){try{o||null==i["return"]||i["return"]()}finally{if(s)throw r}}}}a.d(t,"default",(function(){return r}))},"5db3":function(e,t,a){"use strict";a.r(t);var i=a("6a36"),n=a("b918");for(var r in n)"default"!==r&&function(e){a.d(t,e,(function(){return n[e]}))}(r);a("51de");var o,s=a("f0c5"),c=Object(s["a"])(n["default"],i["b"],i["c"],!1,null,"01ee4035",null,!1,i["a"],o);t["default"]=c.exports},"6a36":function(e,t,a){"use strict";var i,n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("v-uni-view",{staticClass:"recharge-container"},[e.go_browser_modal?a("v-uni-view",{staticClass:"tips-container",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.loadGoBrowser(!1)}}},[a("v-uni-image",{attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EMGfE6hy6KPqPPAqcOK7rch5JSpAmq86caKtnXkRibO52P0Ce7NAWEL9plPwWQMSjJbNib2NNoLuBw/0",mode:"widthFix"}})],1):e._e(),a("v-uni-view",{staticClass:"top-row"},[a("v-uni-view",{staticClass:"user-container"},[a("v-uni-image",{attrs:{src:e.userInfo.avatarurl,mode:"widthFix"}}),a("v-uni-view",{staticClass:"nickname"},[e._v(e._s(e.userInfo.nickname))])],1),a("v-uni-view",{staticClass:"right-btn"},[e.pay_switch?a("v-uni-view",{staticClass:"ali-pay-btn flex-set",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.loadGoBrowser(!0)}}},[e._v("支付宝充值")]):e._e(),a("btnComponent",{attrs:{type:"css"}},[a("v-uni-view",{staticClass:"proxy flex-set",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.modal="proxyRecharge"}}},[a("v-uni-image",{attrs:{src:"/static/image/recharge/proxy.png",mode:""}}),a("v-uni-text",[e._v(e._s("ali_pay"==e.pay_type?"搜索ID充值":"代充值"))])],1)],1)],1)],1),a("v-uni-view",{staticClass:"count-wrap"},[a("v-uni-view",{staticClass:"top-title"},[e._v("我的能量："+e._s(e.userCurrency.coin))]),a("v-uni-view",{staticClass:"top-title"},[e._v("我的灵丹："+e._s(e.userCurrency.stone))]),a("v-uni-view",{staticClass:"top-title flex-set",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.$app.goPage("/pages/gift_package/gift_package")}}},[a("v-uni-view",{staticStyle:{"text-decoration":"underline"}},[e._v("礼物背包")]),a("v-uni-view",{staticClass:"badge-wrap"},[e._v(e._s(e.giftNum))])],1),e.handShow?a("v-uni-image",{staticClass:"hand",attrs:{src:"/static/image/pet/hand.png",mode:"widthFix"}}):e._e()],1),a("v-uni-view",{staticClass:"count-wrap tips"},[a("v-uni-view",[e._v("购买的礼物不清零")]),a("v-uni-view",[e._v("礼物可点击“打榜”直接送出，增加爱豆能量")])],1),a("v-uni-view",{staticClass:"count-wrap middle"},[e._v(e._s(e.$app.getData("config").recharge_title))]),a("v-uni-view",{staticClass:"btn-wrapper"},e._l(e.rechargeList,(function(t,i){return a("v-uni-view",{key:i,staticClass:"btn",on:{click:function(a){arguments[0]=a=e.$handleEvent(a),e.payment(t.id)}}},[a("v-uni-view",{staticClass:"icon-wrap"},[a("v-uni-image",{staticClass:"icon",attrs:{src:t.item.icon,mode:"widthFix"}}),e.item_double?a("v-uni-view",{staticClass:"icon-top flex-set"},[e._v("生日翻倍")]):e._e(),a("v-uni-view",{staticClass:"line one flex-set"},[a("v-uni-image",{staticClass:"sicon",attrs:{src:"/static/image/user/b1.png",mode:"widthFix"}}),e._v(e._s(t.item.count))],1),a("v-uni-view",{staticClass:"name flex-set"},[e._v(e._s(t.item.name))])],1),t.coin?a("v-uni-view",{staticClass:"line two flex-set"},[a("v-uni-image",{staticClass:"sicon",attrs:{src:"/static/image/user/b1.png",mode:"widthFix"}}),e._v("+"+e._s(t.coin))],1):e._e(),t.stone?a("v-uni-view",{staticClass:"line two flex-set"},[a("v-uni-image",{staticClass:"sicon",attrs:{src:"/static/image/user/b2.png",mode:"widthFix"}}),e._v("+"+e._s(t.stone))],1):e._e(),a("v-uni-view",{staticClass:"fee flex-set"},[t.off_fee?a("v-uni-text",{staticClass:"off-fee"},[e._v("￥"+e._s(t.off_fee))]):e._e(),a("v-uni-text",{staticClass:"cur-fee"},[e._v("￥"+e._s(t.fee))])],1)],1)})),1),"proxyRecharge"==e.modal?a("modalComponent",{attrs:{title:"代充值"},on:{closeModal:function(t){arguments[0]=t=e.$handleEvent(t),e.modal=""}}},[a("v-uni-view",{staticClass:"userinfo-modal-container"},[a("v-uni-view",{staticClass:"top"},[a("v-uni-image",{staticClass:"avatar",attrs:{src:e.currentUser.avatarurl,mode:"scaleToFill"}}),a("v-uni-view",{staticClass:"nickname"},[e._v(e._s(e.currentUser.nickname))])],1),a("v-uni-view",{staticClass:"send-input"},[a("v-uni-input",{attrs:{type:"number","confirm-type":"search",value:e.currentUserid,placeholder:"请输入对方的ID"},on:{blur:function(t){arguments[0]=t=e.$handleEvent(t),e.kickBack()},confirm:function(t){arguments[0]=t=e.$handleEvent(t),e.searchUser()},input:function(t){arguments[0]=t=e.$handleEvent(t),e.currentUserid=t.detail.value}}})],1),a("btnComponent",{attrs:{type:"css"}},[a("v-uni-view",{staticClass:"btn flex-set",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.searchUser()}}},[e._v("查找用户")])],1),a("btnComponent",{attrs:{type:"css"}},[a("v-uni-view",{staticClass:"btn flex-set",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.confirm()}}},[e._v("为TA充值")])],1)],1)],1):e._e(),"ali_pay"==e.pay_type?a("v-uni-view",{staticStyle:{display:"none"},attrs:{id:"alipay"}}):e._e()],1)},r=[];a.d(t,"b",(function(){return n})),a.d(t,"c",(function(){return r})),a.d(t,"a",(function(){return i}))},b325:function(e,t,a){var i=a("24fb");t=i(!1),t.push([e.i,".recharge-container[data-v-01ee4035]{padding:%?40?% 0}.recharge-container .tips-container[data-v-01ee4035]{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.8);z-index:6}.recharge-container .tips-container uni-image[data-v-01ee4035]{width:100%}.recharge-container .top-row[data-v-01ee4035]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;margin-bottom:%?40?%;padding:0 %?40?%}.recharge-container .top-row .user-container[data-v-01ee4035]{height:%?70?%;background-color:hsla(0,0%,100%,.3);display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;border-radius:%?30?%}.recharge-container .top-row .user-container uni-image[data-v-01ee4035]{width:%?70?%;height:%?70?%;border-radius:50%;margin-right:%?20?%}.recharge-container .top-row .user-container .nickname[data-v-01ee4035]{font-size:%?32?%;margin-right:%?30?%;color:#5f6176}.recharge-container .top-row .right-btn[data-v-01ee4035]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.recharge-container .top-row .ali-pay-btn[data-v-01ee4035]{background-color:#08f;color:#fff;border-radius:%?40?%;margin:0 %?10?%;padding:%?10?% %?20?%;font-size:%?30?%}.recharge-container .top-row .proxy[data-v-01ee4035]{padding:%?10?% %?20?%;height:%?70?%}.recharge-container .top-row .proxy uni-image[data-v-01ee4035]{width:%?50?%;height:%?50?%;margin:0 %?10?%}.recharge-container .row[data-v-01ee4035]{position:relative;height:%?115?%;text-align:center;line-height:%?115?%;font-size:%?40?%;font-weight:700}.recharge-container .row .bg[data-v-01ee4035]{position:absolute;z-index:-1;left:0;top:0}.recharge-container .count-wrap[data-v-01ee4035]{background-color:#ffd3dc;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-justify-content:space-around;justify-content:space-around;-webkit-box-align:center;-webkit-align-items:center;align-items:center;line-height:%?100?%;position:relative;margin:0 %?40?%}.recharge-container .count-wrap .top-title[data-v-01ee4035]{position:relative}.recharge-container .count-wrap .top-title .badge-wrap[data-v-01ee4035]{background-color:red;border-radius:%?30?%;line-height:1.5;padding:%?5?% %?10?%;color:#fff;font-size:%?18?%}.recharge-container .count-wrap .hand[data-v-01ee4035]{width:%?60?%;height:%?60?%;-webkit-animation:scale-data-v-01ee4035 1s linear infinite;animation:scale-data-v-01ee4035 1s linear infinite;position:absolute;right:%?40?%;top:%?60?%}@-webkit-keyframes scale-data-v-01ee4035{0%,\n  100%{-webkit-transform:scale(.9);transform:scale(.9)}50%{-webkit-transform:scale(1.1);transform:scale(1.1)}}@keyframes scale-data-v-01ee4035{0%,\n  100%{-webkit-transform:scale(.9);transform:scale(.9)}50%{-webkit-transform:scale(1.1);transform:scale(1.1)}}.recharge-container .count-wrap.tips[data-v-01ee4035]{border-top:1px dashed #eee;line-height:1.6;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;border-bottom:1px dashed #eee;margin:0 %?40?%}.recharge-container .count-wrap.middle[data-v-01ee4035]{color:red;font-weight:700;font-size:%?46?%;text-shadow:0 %?2?% %?4?% rgba(0,0,0,.6)}.recharge-container .btn-wrapper[data-v-01ee4035]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-flex-wrap:wrap;flex-wrap:wrap;background-color:#fff;margin:0 %?30?%}.recharge-container .btn-wrapper .btn[data-v-01ee4035]{background-color:#ffd3dc;width:%?200?%;height:%?320?%;margin:%?15?%;position:relative;padding:%?8?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-justify-content:space-around;justify-content:space-around;-webkit-box-align:center;-webkit-align-items:center;align-items:center;border-radius:%?10?%}.recharge-container .btn-wrapper .btn .name[data-v-01ee4035]{width:%?125?%;color:#fa5e86;border-bottom:%?2?% solid #eee}.recharge-container .btn-wrapper .btn .icon-wrap[data-v-01ee4035]{background-color:hsla(0,0%,100%,.3);border-radius:%?20?%;padding:0 %?20?%}.recharge-container .btn-wrapper .btn .icon[data-v-01ee4035]{width:%?125?%;height:%?125?%}.recharge-container .btn-wrapper .btn .icon-top[data-v-01ee4035]{position:absolute;background-color:red;border-radius:%?20?%;top:%?20?%;right:%?30?%;font-size:%?24?%;color:#fff;padding:0 %?8?%}.recharge-container .btn-wrapper .btn .line .sicon[data-v-01ee4035]{width:%?30?%}.recharge-container .btn-wrapper .btn .line.one[data-v-01ee4035]{position:absolute;right:%?30?%;top:%?120?%;border-radius:%?20?%;background-color:hsla(0,0%,100%,.3);font-size:%?24?%;padding:0 %?8?%;color:#666}.recharge-container .btn-wrapper .btn .line.one .sicon[data-v-01ee4035]{width:%?25?%}.recharge-container .btn-wrapper .btn .fee[data-v-01ee4035]{width:%?160?%;background-color:#fff;border-radius:%?5?%}.recharge-container .btn-wrapper .btn .fee .off-fee[data-v-01ee4035]{text-decoration:line-through;font-size:%?24?%;color:#666}.recharge-container .btn-wrapper .btn .fee .cur-fee[data-v-01ee4035]{color:#5f6176}.recharge-container .userinfo-modal-container[data-v-01ee4035]{height:%?640?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-justify-content:space-around;justify-content:space-around;padding:%?40?%}.recharge-container .userinfo-modal-container .top[data-v-01ee4035]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center;line-height:1.6}.recharge-container .userinfo-modal-container .top .avatar[data-v-01ee4035]{width:%?160?%;height:%?160?%;border-radius:50%}.recharge-container .userinfo-modal-container .top .nickname[data-v-01ee4035]{font-size:%?36?%;font-weight:600;height:%?80?%}.recharge-container .userinfo-modal-container .btn-list[data-v-01ee4035]{width:100%;-webkit-justify-content:space-around;justify-content:space-around}.recharge-container .userinfo-modal-container .btn-list .btn-item[data-v-01ee4035]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.recharge-container .userinfo-modal-container .btn-list .btn-item .bg[data-v-01ee4035]{background-color:#fff;border-radius:%?20?%;width:%?100?%;height:%?100?%}.recharge-container .userinfo-modal-container .btn-list .btn-item .bg uni-image[data-v-01ee4035]{width:%?60?%;height:%?60?%}.recharge-container .userinfo-modal-container .btn-list .btn-item .text[data-v-01ee4035]{margin-top:%?10?%}.recharge-container .userinfo-modal-container .content[data-v-01ee4035]{line-height:1.6}.recharge-container .userinfo-modal-container .btn[data-v-01ee4035]{font-size:%?32?%;font-weight:700;width:%?300?%;height:%?80?%}.recharge-container .userinfo-modal-container .row[data-v-01ee4035]{width:100%;-webkit-justify-content:space-around;justify-content:space-around}.recharge-container .userinfo-modal-container .row .btn[data-v-01ee4035]{width:%?200?%}.recharge-container .userinfo-modal-container .send-input[data-v-01ee4035]{position:relative}.recharge-container .userinfo-modal-container .send-input uni-input[data-v-01ee4035]{background-color:#eee;border-radius:%?60?%;text-align:center;width:%?300?%;height:%?80?%;font-size:%?32?%;font-weight:700}.recharge-container .userinfo-modal-container .send-input uni-image[data-v-01ee4035]{position:absolute;width:%?50?%;height:%?50?%;right:%?20?%;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}",""]),e.exports=t},b918:function(e,t,a){"use strict";a.r(t);var i=a("3cf3"),n=a.n(i);for(var r in i)"default"!==r&&function(e){a.d(t,e,(function(){return i[e]}))}(r);t["default"]=n.a},f5af:function(e,t,a){var i=a("b325");"string"===typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);var n=a("4f06").default;n("2144f87c",i,!0,{sourceMap:!1,shadowMode:!1})}}]);