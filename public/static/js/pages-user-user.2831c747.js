(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-user"],{"107b":function(t,e,i){"use strict";var n=i("55f3"),a=i.n(n);a.a},5425:function(t,e,i){"use strict";i.r(e);var n=i("8237"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,function(){return n[t]})}(o);e["default"]=a.a},"55f3":function(t,e,i){var n=i("e6fb");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("fa5b0896",n,!0,{sourceMap:!1,shadowMode:!1})},8237:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=o(i("8f02")),a=o(i("f372"));function o(t){return t&&t.__esModule?t:{default:t}}var s={components:{badgeComponent:n.default,modalComponent:a.default},data:function(){return{requestCount:0,userInfo:{},userCurrency:{},userStar:{},modal:"",rechargeList:[]}},onLoad:function(){},onShow:function(){this.userInfo={avatarurl:this.$app.getData("userInfo")["avatarurl"]||this.$app.AVATAR,nickname:this.$app.getData("userInfo")["nickname"]||this.$app.NICKNAME,id:this.$app.getData("userInfo")["id"]||null},this.userCurrency=this.$app.getData("userCurrency")||{coin:0,stone:0,trumpet:0},this.userStar=this.$app.getData("userStar")||{}},onShareAppMessage:function(){return this.$app.commonShareAppMessage()},methods:{exitGroup:function(){var t=this;this.$app.modal("只有一次机会\n并且会清除你的师徒关系\n是否退出".concat(this.$app.getData("userStar").name,"偶像圈？"),function(){t.$app.request(t.$app.API.USER_EXIT,{},function(e){t.$app.toast("退出成功"),t.$app.setData("userStar",{},!0),t.userStar={}})})},getUserInfo:function(t){var e=this,i=t.detail.userInfo;i&&this.$app.request(this.$app.API.USER_SAVEINFO,{iv:t.detail.iv,encryptedData:t.detail.encryptedData},function(t){e.$app.request(e.$app.API.USER_INFO,{},function(t){e.$app.setData("userInfo",t.data),e.userInfo=e.$app.getData("userInfo"),e.$app.toast("更新成功")})},"POST",!0)}}};e.default=s},e5cd:function(t,e,i){"use strict";i.r(e);var n=i("f5cb"),a=i("5425");for(var o in a)"default"!==o&&function(t){i.d(e,t,function(){return a[t]})}(o);i("107b");var s=i("2877"),r=Object(s["a"])(a["default"],n["a"],n["b"],!1,null,"097e771f",null);e["default"]=r.exports},e6fb:function(t,e,i){e=t.exports=i("2350")(!1),e.push([t.i,".container[data-v-097e771f]{position:fixed;width:100%;height:100%;padding:%?20?%}.container .top-content-container[data-v-097e771f]{border-radius:%?10?%;padding:%?40?% %?30?%}.container .top-content-container .row.userinfo[data-v-097e771f]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.container .top-content-container .row.userinfo .avatar[data-v-097e771f]{position:relative;overflow:hidden;border-radius:50%;width:%?140?%;height:%?140?%;z-index:1}.container .top-content-container .row.userinfo .avatar .tips[data-v-097e771f]{position:absolute;width:100%;background-color:rgba(0,0,0,.3);bottom:0;height:%?40?%;color:#fff;font-size:%?20?%;text-align:center;line-height:%?30?%}.container .top-content-container .row.userinfo .info-content[data-v-097e771f]{margin:%?4?% %?30?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column}.container .top-content-container .row.userinfo .info-content .item-line[data-v-097e771f]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.container .top-content-container .row.userinfo .info-content .item-line .username[data-v-097e771f]{font-weight:700;margin-right:%?8?%}.container .top-content-container .row.userinfo .info-content .item-line uni-image.vip[data-v-097e771f]{width:%?38?%;height:%?38?%;margin-top:%?4?%;margin-right:%?6?%}.container .top-content-container .row.userinfo .info-content .item-line .vip-expire[data-v-097e771f]{font-size:%?18?%;color:#999;margin-top:%?12?%}.container .top-content-container .row.userinfo .info-content .item-line .id-content[data-v-097e771f]{border-radius:%?20?%;font-size:%?24?%;background-color:#fdde2f;padding:0 %?10?%;color:#853e1d}.container .top-content-container .row.userinfo .info-content .item-line .mystar[data-v-097e771f]{margin-left:%?10?%;border-radius:%?20?%;font-size:%?24?%;background-color:#23aecf;padding:0 %?10?%;color:#fff}.container .top-content-container .row.userinfo .info-content .item-line .fan-level[data-v-097e771f]{font-size:%?24?%;position:relative;margin-left:%?10?%}.container .top-content-container .row.userinfo .info-content .item-line .fan-level uni-image[data-v-097e771f]{width:%?44?%;height:%?44?%;position:absolute;bottom:%?-2?%;left:0}.container .top-content-container .row.userinfo .info-content .item-line .fan-level .fan-text[data-v-097e771f]{background-color:#f1f1f1;color:#999;border-top-right-radius:%?20?%;border-bottom-right-radius:%?20?%;padding:0 %?36?%;margin-left:%?14?%}.container .top-content-container .row.userinfo .info-content .item-line.bottom[data-v-097e771f]{font-size:%?20?%}.container .row.currency[data-v-097e771f]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;background-color:rgba(0,0,0,.05);margin-left:%?-20?%;margin-right:%?-20?%;padding:%?30?% 0}.container .row.currency .item-content[data-v-097e771f]{width:100%;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column}.container .row.currency .item-content .item-content-top[data-v-097e771f]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;position:relative}.container .row.currency .item-content .item-content-top uni-image[data-v-097e771f]{width:%?50?%;height:%?50?%;margin-right:%?4?%}.container .row.currency .item-content .item-content-bottom[data-v-097e771f]{font-size:%?28?%;margin-top:%?4?%}.container .function-container-list[data-v-097e771f]{border-radius:%?10?%;margin-top:%?20?%;padding:%?14?%}.container .function-container-list .list-item[data-v-097e771f]{height:%?100?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;padding:%?14?% %?30?%}.container .function-container-list .list-item uni-image[data-v-097e771f]{width:%?50?%;min-height:%?50?%;margin-right:%?30?%}.container .function-container-list .list-item .badge-wrapper[data-v-097e771f]{position:relative;margin-left:%?320?%;margin-top:%?10?%;height:%?10?%;width:%?60?%}",""])},f5cb:function(t,e,i){"use strict";var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"container"},[i("v-uni-view",{staticClass:"top-content-container"},[i("v-uni-view",{staticClass:"row userinfo"},[i("v-uni-button",{attrs:{"open-type":"getUserInfo"},on:{getuserinfo:function(e){e=t.$handleEvent(e),t.getUserInfo(e)}}},[i("v-uni-view",{staticClass:"avatar"},[i("v-uni-image",{attrs:{src:t.userInfo.avatarurl,mode:"aspectFill"}}),i("v-uni-view",{staticClass:"tips"},[t._v("点击获取")])],1)],1),i("v-uni-view",{staticClass:"info-content"},[i("v-uni-view",{staticClass:"item-line top"},[i("v-uni-view",{staticClass:"username"},[t._v(t._s(t.userInfo.nickname))])],1),i("v-uni-view",{staticClass:"item-line middle"},[t.userInfo.id?i("v-uni-view",{staticClass:"id-content flex-set",on:{click:function(e){e=t.$handleEvent(e),t.$app.copy(1234*t.userInfo.id)}}},[t._v("ID:"+t._s(1234*t.userInfo.id))]):t._e(),t.userStar.id?i("v-uni-view",{staticClass:"mystar flex-set",on:{click:function(e){e=t.$handleEvent(e),t.$app.goPage("/pages/group/group")}}},[t._v(t._s(t.userStar.name)+"偶像圈")]):t._e(),0!=t.$app.getData("userInfo").type?i("v-uni-view",{staticClass:"mystar flex-set",staticStyle:{"background-color":"#67458F"}},[t._v("管理员")]):t._e()],1)],1)],1)],1),i("v-uni-view",{staticClass:"row currency"},[i("v-uni-view",{staticClass:"item-content flex-set"},[i("v-uni-view",{staticClass:"item-content-top"},[i("v-uni-image",{attrs:{src:"/static/image/user/b1.png",mode:"aspectFit"}}),i("v-uni-view",{staticClass:"num"},[t._v(t._s(t.userCurrency.coin))])],1),i("v-uni-view",{staticClass:"item-content-bottom"},[t._v("能量")])],1),i("v-uni-view",{staticClass:"item-content flex-set"},[i("v-uni-view",{staticClass:"item-content-top"},[i("v-uni-image",{attrs:{src:"/static/image/user/b2.png",mode:"aspectFit"}}),i("v-uni-view",{staticClass:"num"},[t._v(t._s(t.userCurrency.stone))])],1),i("v-uni-view",{staticClass:"item-content-bottom"},[t._v("灵丹")])],1),i("v-uni-view",{staticClass:"item-content flex-set"},[i("v-uni-view",{staticClass:"item-content-top"},[i("v-uni-image",{attrs:{src:"/static/image/user/b3.png",mode:"aspectFit"}}),i("v-uni-view",{staticClass:"num"},[t._v(t._s(t.userCurrency.trumpet))])],1),i("v-uni-view",{staticClass:"item-content-bottom"},[t._v("喇叭")])],1)],1),i("v-uni-view",{staticClass:"function-container-list"},[-1==t.$app.getData("sysInfo").system.indexOf("iOS")?i("v-uni-view",{staticClass:"list-item",on:{click:function(e){e=t.$handleEvent(e),t.$app.goPage("/pages/recharge/recharge")}}},[i("v-uni-image",{attrs:{src:"/static/image/user/r1.png",mode:"widthFix"}}),i("v-uni-view",{staticClass:"text"},[t._v("补充能量")]),i("v-uni-view",{staticClass:"badge-wrapper"},[i("badgeComponent")],1)],1):[1==t.$app.getData("config").ios_switch?i("v-uni-button",{attrs:{"open-type":"contact","show-message-card":""}},[i("v-uni-view",{staticClass:"list-item"},[i("v-uni-image",{attrs:{src:"/static/image/user/r1.png",mode:"widthFix"}}),i("v-uni-view",{staticClass:"text"},[t._v('补充能量 回复"1"')])],1)],1):t._e()],i("v-uni-view",{staticClass:"list-item",on:{click:function(e){e=t.$handleEvent(e),t.$app.goPage("/pages/task/task")}}},[i("v-uni-image",{attrs:{src:"/static/image/user/r2.png",mode:"widthFix"}}),i("v-uni-view",{staticClass:"text"},[t._v("每日任务")])],1),i("v-uni-button",{attrs:{"open-type":"contact","show-message-card":""}},[i("v-uni-view",{staticClass:"list-item"},[i("v-uni-image",{attrs:{src:"/static/image/user/r3.png",mode:"widthFix"}}),i("v-uni-view",{staticClass:"text"},[t._v("联系客服")])],1)],1),t.userStar.id?i("v-uni-view",{staticClass:"list-item",on:{click:function(e){e=t.$handleEvent(e),t.exitGroup(e)}}},[i("v-uni-image",{attrs:{src:"/static/image/user/r4.png",mode:"widthFix"}}),i("v-uni-view",{staticClass:"text"},[t._v("退出偶像圈")])],1):t._e()],2)],1)},a=[];i.d(e,"a",function(){return n}),i.d(e,"b",function(){return a})}}]);