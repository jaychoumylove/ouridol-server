(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-subPages-user-rank-rank"],{"09b5":function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0,e("ac6a");var i={data:function(){return{requestCount:1,starid:null,userRank:[],page:1,myInfo:{}}},onLoad:function(t){this.starid=t.starid,this.loadData()},onReachBottom:function(){this.$app.getData("userStar").id==this.starid&&++this.page<=10&&this.loadData()},methods:{loadData:function(){var t=this;this.$app.request(this.$app.API.USER_RANK,{starid:this.starid,page:this.page},(function(a){t.myInfo=a.data.my;var e=[];a.data.list.forEach((function(a,i){e.push({avatar:a.user&&a.user.avatarurl||t.$app.getData("AVATAR"),nickname:a.user&&a.user.nickname||t.$app.getData("NICKNAME"),hot:t.$app.formatNumberRgx(a.thisweek_count)})})),1==t.page?t.userRank=e:t.userRank=t.userRank.concat(e)}))}}};a.default=i},2858:function(t,a,e){"use strict";var i,n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"container"},[e("v-uni-view",{staticClass:"list-wrapper"},t._l(t.userRank,(function(a,i){return e("v-uni-view",{key:i,staticClass:"item",class:{one:0==i,two:1==i,three:2==i}},[e("v-uni-view",{staticClass:"rank-num"},[e("v-uni-view",[t._v(t._s(i+1))])],1),e("v-uni-view",{staticClass:"avatar"},[e("v-uni-image",{attrs:{src:a.avatar,mode:"aspectFill"}})],1),e("v-uni-view",{staticClass:"text-container"},[e("v-uni-view",{staticClass:"star-name text-overflow"},[t._v(t._s(a.nickname))])],1),e("v-uni-view",{staticClass:"count"},[t._v(t._s(a.hot))])],1)})),1),t.$app.getData("userStar").id==t.starid?e("v-uni-view",{staticClass:"my-wrap"},[e("v-uni-view",{staticClass:"rank-num"},[e("v-uni-view",[t._v(t._s(t.myInfo.rank))])],1),e("v-uni-view",{staticClass:"avatar"},[e("v-uni-image",{attrs:{src:t.$app.getData("userInfo").avatarurl,mode:"aspectFill"}})],1),e("v-uni-view",{staticClass:"text-container"},[e("v-uni-view",{staticClass:"star-name text-overflow"},[t._v(t._s(t.$app.getData("userInfo").nickname))])],1),e("v-uni-view",{staticClass:"count"},[t._v(t._s(t.myInfo.score))])],1):t._e()],1)},r=[];e.d(a,"b",(function(){return n})),e.d(a,"c",(function(){return r})),e.d(a,"a",(function(){return i}))},"2bfe":function(t,a,e){"use strict";e.r(a);var i=e("09b5"),n=e.n(i);for(var r in i)"default"!==r&&function(t){e.d(a,t,(function(){return i[t]}))}(r);a["default"]=n.a},"60fe":function(t,a,e){"use strict";e.r(a);var i=e("2858"),n=e("2bfe");for(var r in n)"default"!==r&&function(t){e.d(a,t,(function(){return n[t]}))}(r);e("99f6");var c,s=e("f0c5"),o=Object(s["a"])(n["default"],i["b"],i["c"],!1,null,"6ce36b4c",null,!1,i["a"],c);a["default"]=o.exports},"99f6":function(t,a,e){"use strict";var i=e("e9b1"),n=e.n(i);n.a},a1d7:function(t,a,e){var i=e("24fb");a=i(!1),a.push([t.i,".container .list-wrapper .item[data-v-6ce36b4c]{margin:%?20?% 0;height:%?130?%;background:url(https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Hf1Erx2q066sKAsgEzqUsxtR4IyxJscPr234VZVlMPb9Z2TSwvzWYBOMtribqMrLGIUAbJGtyERqA/0) 100% no-repeat/contain;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.container .list-wrapper .item .rank-num[data-v-6ce36b4c]{margin-left:%?106?%}.container .list-wrapper .item .avatar uni-image[data-v-6ce36b4c]{margin-left:%?60?%;width:%?100?%;height:%?100?%;border-radius:50%}.container .list-wrapper .item .text-container[data-v-6ce36b4c]{margin-left:%?30?%;width:%?250?%;line-height:%?44?%}.container .list-wrapper .item .text-container .bottom-text[data-v-6ce36b4c]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;color:#ce797c}.container .list-wrapper .item .count[data-v-6ce36b4c]{margin-left:%?30?%}.container .list-wrapper .item.one[data-v-6ce36b4c]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2enrl4pe1g30j703n0sk.gif) 100% no-repeat/contain}.container .list-wrapper .item.two[data-v-6ce36b4c]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2enrl5aerg30j703ndfn.gif) 100% no-repeat/contain}.container .list-wrapper .item.three[data-v-6ce36b4c]{background:url(http://tva1.sinaimg.cn/large/0060lm7Tly1g2enrl4zd6g30j703ngle.gif) 100% no-repeat/contain}.container .my-wrap[data-v-6ce36b4c]{position:fixed;bottom:0;width:100%;margin:%?20?% 0;height:%?130?%;display:-webkit-box;display:-webkit-flex;display:flex;background-color:#efccc8;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.container .my-wrap .rank-num[data-v-6ce36b4c]{margin-left:%?106?%}.container .my-wrap .avatar uni-image[data-v-6ce36b4c]{margin-left:%?60?%;width:%?100?%;height:%?100?%;border-radius:50%}.container .my-wrap .text-container[data-v-6ce36b4c]{margin-left:%?30?%;width:%?250?%;line-height:%?44?%}.container .my-wrap .text-container .bottom-text[data-v-6ce36b4c]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;color:#ce797c}.container .my-wrap .count[data-v-6ce36b4c]{margin-left:%?30?%}",""]),t.exports=a},e9b1:function(t,a,e){var i=e("a1d7");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=e("4f06").default;n("fecccb06",i,!0,{sourceMap:!1,shadowMode:!1})}}]);