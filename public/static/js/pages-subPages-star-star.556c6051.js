(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-subPages-star-star"],{2570:function(t,a,n){"use strict";var e,i=function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("v-uni-view",{staticClass:"star-container"},[n("guildComponent",{ref:"guildComponent"}),n("v-uni-button",{attrs:{"open-type":"getUserInfo"},on:{getuserinfo:function(a){arguments[0]=a=t.$handleEvent(a),t.getUserInfo.apply(void 0,arguments)}}},[t.tips?n("v-uni-view",{staticClass:"tips-container"},[n("v-uni-image",{attrs:{src:"/static/image/star/blank-3.png",mode:"widthFix"}})],1):t._e()],1)],1)},r=[];n.d(a,"b",(function(){return i})),n.d(a,"c",(function(){return r})),n.d(a,"a",(function(){return e}))},"54b9":function(t,a,n){"use strict";n.r(a);var e=n("c54c"),i=n.n(e);for(var r in e)"default"!==r&&function(t){n.d(a,t,(function(){return e[t]}))}(r);a["default"]=i.a},5615:function(t,a,n){"use strict";n.r(a);var e=n("2570"),i=n("54b9");for(var r in i)"default"!==r&&function(t){n.d(a,t,(function(){return i[t]}))}(r);n("9439");var s,o=n("f0c5"),u=Object(o["a"])(i["default"],e["b"],e["c"],!1,null,"4f3d8077",null,!1,e["a"],s);a["default"]=u.exports},9439:function(t,a,n){"use strict";var e=n("dfd3"),i=n.n(e);i.a},c54c:function(t,a,n){"use strict";var e=n("4ea4");Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i=e(n("480e")),r={components:{guildComponent:i.default},data:function(){return{tips:!1}},onShareAppMessage:function(){return this.$app.commonShareAppMessage()},onLoad:function(t){this.starid=t.starid,this.starid==this.$app.getData("userStar").id&&this.$app.goPage("/pages/group/group")},onReady:function(){},onShow:function(){this.$app.getData("userStar").id||(this.tips=!0),this.$refs.guildComponent.load(this.starid)},onUnload:function(){this.$refs.guildComponent.unLoad(this.starid),this.$refs.guildComponent.modal=""},methods:{getUserInfo:function(t){var a=this,n=t.detail.userInfo;n&&(this.tips=!1,this.$app.getData("userInfo").nickname||this.$app.request(this.$app.API.USER_SAVEINFO,{iv:t.detail.iv,encryptedData:t.detail.encryptedData},(function(t){t.data.token&&(a.$app.token=t.data.token),a.$app.request("page/app",{},(function(t){a.$app.setData("userCurrency",t.data.userCurrency),a.$app.setData("userStar",t.data.userStar),a.$app.setData("userExt",t.data.userExt),a.$app.setData("userInfo",t.data.userInfo),a.$app.setData("config",t.data.config)}))}),"POST",!0))}}};a.default=r},dfd3:function(t,a,n){var e=n("fdc2");"string"===typeof e&&(e=[[t.i,e,""]]),e.locals&&(t.exports=e.locals);var i=n("4f06").default;i("44beff4c",e,!0,{sourceMap:!1,shadowMode:!1})},fdc2:function(t,a,n){var e=n("24fb");a=e(!1),a.push([t.i,".star-container[data-v-4f3d8077]{position:relative;height:100%}.star-container .tips-container[data-v-4f3d8077]{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.8);z-index:3}.star-container .tips-container uni-image[data-v-4f3d8077]{width:100%}",""]),t.exports=a}}]);