(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-adver-adver"],{"32d2":function(n,t,e){"use strict";e.r(t);var o=e("9066"),d=e("fed7");for(var i in d)"default"!==i&&function(n){e.d(t,n,function(){return d[n]})}(i);var r=e("2877"),a=Object(r["a"])(d["default"],o["a"],o["b"],!1,null,"33aa1e0c",null);t["default"]=a.exports},9066:function(n,t,e){"use strict";var o=function(){var n=this,t=n.$createElement,e=n._self._c||t;return e("v-uni-view",[e("v-uni-button",{attrs:{type:"primary"},on:{click:function(t){t=n.$handleEvent(t),n.openAdver(t)}}},[n._v("看广告")])],1)},d=[];e.d(t,"a",function(){return o}),e.d(t,"b",function(){return d})},bb45:function(n,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o={data:function(){return{videoAd:null}},onLoad:function(){var n=this;this.videoAd=wx.createRewardedVideoAd({adUnitId:"adunit-f1aae5d7bd2ce0a4"}),this.videoAd.onClose(function(t){t&&t.isEnded||void 0===t?n.$app.toast("正常播放结束，可以下发游戏奖励"):n.$app.toast("播放中途退出，不下发游戏奖励")}),this.videoAd.onError(function(n){console.error("onError",n)})},methods:{openAdver:function(){var n=this;this.videoAd&&this.videoAd.show().catch(function(t){n.videoAd.load().then(function(){n.videoAd.show()})})}}};t.default=o},fed7:function(n,t,e){"use strict";e.r(t);var o=e("bb45"),d=e.n(o);for(var i in o)"default"!==i&&function(n){e.d(t,n,function(){return o[n]})}(i);t["default"]=d.a}}]);