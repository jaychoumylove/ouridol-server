(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-index-index"],{"018f":function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"container"},[e("v-uni-view",{staticClass:"left-container flex-set"},[e("v-uni-view",{staticClass:"rank-num"},[t._v(t._s(t.rank))]),t.avatar?e("v-uni-image",{staticClass:"avatar",attrs:{src:t.avatar,mode:"aspectFill"}}):t._e(),t._t("left-container")],2),e("v-uni-view",{staticClass:"right-container"},[t._t("right-container")],2)],1)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})},"08dd":function(t,a,e){"use strict";var n=e("8399"),i=e.n(n);i.a},"0a0b":function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".banner-wrapper[data-v-9c09d664]{border-radius:%?10?%;overflow:hidden}.banner-wrapper .banner-item-img[data-v-9c09d664]{border-radius:%?10?%}",""])},"1f1f":function(t,a,e){var n=e("0a0b");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("315667b4",n,!0,{sourceMap:!1,shadowMode:!1})},"244b":function(t,a,e){"use strict";var n=e("1f1f"),i=e.n(n);i.a},4746:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{}},props:{rank:{default:""},avatar:{default:""}}};a.default=n},5012:function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".button[data-v-8163c7b2]{color:#6b4a39;-webkit-transition:.3s;-o-transition:.3s;transition:.3s}.button.scale[data-v-8163c7b2]{-webkit-transform:scale(.7);-ms-transform:scale(.7);transform:scale(.7)}.button.default[data-v-8163c7b2]{background:url(http://wx2.sinaimg.cn/large/0060lm7Tly1g2coigwh4sg303s01p741.gif) 50% no-repeat/100% 100%}.button.big[data-v-8163c7b2]{background:url(http://wx4.sinaimg.cn/large/0060lm7Tly1g2dxu4igebg303v02cgld.gif) 50% no-repeat/100% 100%}.button.success[data-v-8163c7b2]{background:url(http://wx4.sinaimg.cn/large/0060lm7Tly1g2ey5oz2oag303s01p741.gif) 50% no-repeat/100% 100%;color:#fff}.button.disable[data-v-8163c7b2]{background:url(http://wx1.sinaimg.cn/large/0060lm7Tly1g2ey5nkm77g303s01p741.gif) 50% no-repeat/100% 100%}.button.fangde[data-v-8163c7b2]{background:url(http://wx2.sinaimg.cn/large/0060lm7Tly1g2jwmn4sshg305v05vwea.gif) 50% no-repeat/100% 100%}.button.css[data-v-8163c7b2]{background-color:#ffd1b2;border-radius:%?60?%;-webkit-box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3),inset 0 0 %?4?% rgba(0,0,0,.3)}",""])},"521c":function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"index-container",style:{top:t.scrollTop}},[e("v-uni-view",{staticClass:"top-tab-container"},[e("v-uni-view",{staticClass:"left-tab-group"},[e("v-uni-view",{staticClass:"tab-item",class:{active:0==t.sign}},[t._v("周榜")])],1),e("v-uni-view",{staticClass:"right-search"},[e("v-uni-input",{class:{show:t.searchShow},attrs:{type:"text",value:t.keywords,placeholder:"搜索爱豆名字","placeholder-class":"placeholder-style","placeholder-style":"color:#FFF;"},on:{input:function(a){a=t.$handleEvent(a),t.searchInput(a)}}}),e("v-uni-view",{staticClass:"iconfont flex-set",class:[t.searchShow?"icon-icon-test1":"icon-sousuo"],on:{click:function(a){a=t.$handleEvent(a),t.searchToggle()}}})],1)],1),e("bannerComponent",{attrs:{bannerHeight:"280"}}),e("v-uni-view",{staticClass:"middle-text-container"},[e("v-uni-view",{on:{click:function(a){a=t.$handleEvent(a),t.$app.goPage("/pages/star/rank_history/rank_history")}}},[t._v("往期榜单")]),e("v-uni-view",{staticStyle:{"font-size":"24upx"}},[t._v("本期截止："+t._s(t.cutOffDate)+"23:59")]),e("v-uni-view",{staticClass:"rule",on:{click:function(a){a=t.$handleEvent(a),t.$app.goPage("/pages/notice/notice?id=1")}}},[t._v("打榜说明")])],1),t.keywords?t._e():e("v-uni-view",{staticClass:"topthree-container"},[e("v-uni-view",{staticClass:"content",on:{click:function(a){a=t.$handleEvent(a),t.goGroup(t.rankList[1]&&t.rankList[1].starid)}}},[e("v-uni-image",{staticClass:"crown",attrs:{src:"/static/image/index/ic_huangguang_2.png",mode:""}}),e("v-uni-view",{staticClass:"avatar",staticStyle:{"background-color":"#9E9095"}},[e("v-uni-image",{staticClass:"position-set",attrs:{src:t.rankList[1]&&t.rankList[1].avatar,mode:"aspectFill"}})],1),e("v-uni-view",{staticClass:"starname"},[t._v(t._s(t.rankList[1]&&t.rankList[1].name))]),e("v-uni-view",{staticClass:"hot"},[t._v(t._s(t.rankList[1]&&t.rankList[1].hot||0)),e("v-uni-image",{attrs:{src:"/static/image/index/ic_hot.png",mode:""}})],1),e("btnComponent",{attrs:{type:"default"}},[e("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v("打榜")])],1)],1),e("v-uni-view",{staticClass:"content",on:{click:function(a){a=t.$handleEvent(a),t.goGroup(t.rankList[0]&&t.rankList[0].starid)}}},[e("v-uni-image",{staticClass:"crown",attrs:{src:"/static/image/index/ic_huangguang_1.png",mode:""}}),e("v-uni-view",{staticClass:"avatar",staticStyle:{"background-color":"#FFC329"}},[e("v-uni-image",{staticClass:"position-set",attrs:{src:t.rankList[0]&&t.rankList[0].avatar,mode:"aspectFill"}})],1),e("v-uni-view",{staticClass:"starname"},[t._v(t._s(t.rankList[0]&&t.rankList[0].name))]),e("v-uni-view",{staticClass:"hot"},[t._v(t._s(t.rankList[0]&&t.rankList[0].hot||0)),e("v-uni-image",{attrs:{src:"/static/image/index/ic_hot.png",mode:""}})],1),e("btnComponent",{attrs:{type:"default"}},[e("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v("打榜")])],1)],1),e("v-uni-view",{staticClass:"content",on:{click:function(a){a=t.$handleEvent(a),t.goGroup(t.rankList[2]&&t.rankList[2].starid)}}},[e("v-uni-image",{staticClass:"crown",attrs:{src:"/static/image/index/ic_huangguang_3.png",mode:""}}),e("v-uni-view",{staticClass:"avatar",staticStyle:{"background-color":"#EC7E3D"}},[e("v-uni-image",{staticClass:"position-set",attrs:{src:t.rankList[2]&&t.rankList[2].avatar,mode:"aspectFill"}})],1),e("v-uni-view",{staticClass:"starname"},[t._v(t._s(t.rankList[2]&&t.rankList[2].name))]),e("v-uni-view",{staticClass:"hot"},[t._v(t._s(t.rankList[2]&&t.rankList[2].hot||0)),e("v-uni-image",{attrs:{src:"/static/image/index/ic_hot.png",mode:""}})],1),e("btnComponent",{attrs:{type:"default"}},[e("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v("打榜")])],1)],1)],1),e("v-uni-view",{staticClass:"rank-list-container"},[t._l(t.rankList,function(a,n){return t.keywords||n>2?e("v-uni-view",{key:n,staticClass:"rank-list-item",on:{click:function(e){e=t.$handleEvent(e),t.goGroup(a.starid)}}},[e("listItemComponent",{attrs:{rank:t.keywords?"":n+1,avatar:a.avatar},scopedSlots:t._u([{key:"left-container",fn:function(){return[e("v-uni-view",{staticClass:"left-container"},[e("v-uni-view",{staticClass:"star-name"},[t._v(t._s(a.name))]),e("v-uni-view",{staticClass:"bottom-text"},[e("v-uni-view",{staticClass:"hot-count"},[t._v(t._s(a.hot))]),e("v-uni-image",{staticClass:"icon-heart",attrs:{src:"/static/image/index/ic_hot.png",mode:""}})],1)],1)]},proxy:!0},{key:"right-container",fn:function(){return[e("v-uni-view",{staticClass:"right-container"},[e("btnComponent",{attrs:{type:"default"}},[e("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v("打榜")])],1)],1)]},proxy:!0}],null,!0)})],1):t._e()}),t.showBottomLoading?e("loadIconComponent"):t._e()],2),"indexBanner"==t.modal&&t.$app.getData("config").index_banner&&t.$app.getData("config").index_banner.img_url?e("v-uni-view",{staticClass:"open-ad-container flex-set"},[e("v-uni-image",{staticClass:"main",attrs:{src:t.$app.getData("config").index_banner.img_url,mode:""},on:{click:function(a){a=t.$handleEvent(a),t.modal="",t.$app.goPage(t.$app.getData("config").index_banner.gopage)}}}),e("v-uni-image",{staticClass:"close-btn",attrs:{src:"/static/image/index/close.png",mode:""},on:{click:function(a){a=t.$handleEvent(a),t.modal=""}}})],1):t._e()],1)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})},"550e":function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{bannerList:[]}},props:{bannerHeight:{default:"300"},bannerType:{default:"0"}},created:function(){var t=this;this.$app.request(this.$app.API.BANNER_LIST,{},function(a){var e=[],n=!0,i=!1,r=void 0;try{for(var s,o=a.data[Symbol.iterator]();!(n=(s=o.next()).done);n=!0){var c=s.value;e.push({img:c.img_url,url:c.gopage})}}catch(l){i=!0,r=l}finally{try{n||null==o.return||o.return()}finally{if(i)throw r}}t.bannerList=e})},computed:{bannerHeightComputed:function(){return uni.upx2px(this.bannerHeight)+"px"}},methods:{goPage:function(t){this.$app.goPage(t)}}};a.default=n},6644:function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-swiper",{staticClass:"banner-wrapper",style:{height:t.bannerHeightComputed},attrs:{circular:"true",autoplay:"true"}},t._l(t.bannerList,function(a,n){return e("v-uni-swiper-item",{key:n,staticClass:"banner-item",on:{click:function(e){e=t.$handleEvent(e),t.goPage(a.url)}}},[e("v-uni-image",{staticClass:"banner-item-img",attrs:{src:a.img,mode:"aspectFill"}})],1)}),1)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})},"6aa25":function(t,a,e){"use strict";e.r(a);var n=e("6644"),i=e("814c");for(var r in i)"default"!==r&&function(t){e.d(a,t,function(){return i[t]})}(r);e("244b");var s=e("2877"),o=Object(s["a"])(i["default"],n["a"],n["b"],!1,null,"9c09d664",null);a["default"]=o.exports},"814c":function(t,a,e){"use strict";e.r(a);var n=e("550e"),i=e.n(n);for(var r in n)"default"!==r&&function(t){e.d(a,t,function(){return n[t]})}(r);a["default"]=i.a},8399:function(t,a,e){var n=e("9b0e");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("740c1c37",n,!0,{sourceMap:!1,shadowMode:!1})},8710:function(t,a,e){var n=e("5012");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("1ce53c5f",n,!0,{sourceMap:!1,shadowMode:!1})},"9b0e":function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".container[data-v-40c13d7b]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;padding:%?15?% 0;background-color:hsla(0,0%,100%,.3);margin:%?10?% 0;width:100%}.container .left-container .rank-num[data-v-40c13d7b]{text-align:center;width:%?90?%}.container .left-container .avatar[data-v-40c13d7b]{width:%?110?%;height:%?110?%;border-radius:50%;margin-right:%?40?%}",""])},a26f:function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"button flex-set",class:[t.type,t.scale],on:{touchstart:function(a){a=t.$handleEvent(a),t.scale="scale"},touchend:function(a){a=t.$handleEvent(a),t.scale=""}}},[t._t("default")],2)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})},b7f5:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n=s(e("6aa25")),i=s(e("cbb7")),r=s(e("f564"));function s(t){return t&&t.__esModule?t:{default:t}}var o={components:{bannerComponent:n.default,btnComponent:i.default,listItemComponent:r.default},data:function(){return{modal:"indexBanner",showBottomLoading:!0,requestCount:1,cutOffDate:"",searchShow:!1,currentTab:0,scrollTop:0,page:1,keywords:"",rankField:"week_hot",sign:0,rankList:[]}},onLoad:function(t){t.starid&&this.goGroup(t.starid),this.getSunday()},onShow:function(){uni.pageScrollTo({scrollTop:0,duration:0}),this.page=1,this.keywords="",this.getRankList()},onShareAppMessage:function(){return this.$app.commonShareAppMessage()},onHide:function(){this.scrollTop=1},onPullDownRefresh:function(){this.page=1,this.keywords="",this.getRankList()},onReachBottom:function(){this.page++,this.getRankList()},methods:{getSunday:function(){var t=new Date,a=t.getDay()||7;t.setDate(t.getDate()-a+7),this.cutOffDate=t.getMonth()+1+"月"+t.getDate()+"日"},goGroup:function(t){this.$app.getData("userStar",!0)["id"]==t?this.$app.goPage("/pages/group/group"):this.$app.goPage("/pages/star/star?starid="+t)},changeSign:function(t){this.page=1,this.sign=t,this.keywords="",this.getRankList()},searchInput:function(t){this.keywords&&t.detail.value||(this.rankList=[]),this.page=1,this.sign=0,this.keywords=t.detail.value,this.getRankList()},searchToggle:function(){this.searchShow=!this.searchShow,this.keywords&&(this.keywords="",this.page=1,this.sign=0,this.getRankList())},getRankList:function(){var t=this;this.showBottomLoading=!0,this.$app.request(this.$app.API.STAR_RANK,{page:this.page,rankField:this.rankField,keywords:this.keywords,sign:this.sign},function(a){a.data.length<10&&(t.showBottomLoading=!1);var e=[];a.data.forEach(function(a){var n={};n.starid=a.star.id,n.name=a.star.name,n.avatar=a.star.head_img_s?a.star.head_img_s:a.star.head_img_l,n.hot=t.$app.formatNumberRgx(a[t.rankField]),e.push(n)}),1==t.page?t.rankList=e:t.rankList=t.rankList.concat(e),t.$app.closeLoading(t)})}}};a.default=o},bf56:function(t,a,e){var n=e("f4c3");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("b88f4bca",n,!0,{sourceMap:!1,shadowMode:!1})},cbb7:function(t,a,e){"use strict";e.r(a);var n=e("a26f"),i=e("d29f");for(var r in i)"default"!==r&&function(t){e.d(a,t,function(){return i[t]})}(r);e("ce25");var s=e("2877"),o=Object(s["a"])(i["default"],n["a"],n["b"],!1,null,"8163c7b2",null);a["default"]=o.exports},ce25:function(t,a,e){"use strict";var n=e("8710"),i=e.n(n);i.a},d1de:function(t,a,e){"use strict";var n=e("bf56"),i=e.n(n);i.a},d1ef:function(t,a,e){"use strict";e.r(a);var n=e("4746"),i=e.n(n);for(var r in n)"default"!==r&&function(t){e.d(a,t,function(){return n[t]})}(r);a["default"]=i.a},d29f:function(t,a,e){"use strict";e.r(a);var n=e("f7e0"),i=e.n(n);for(var r in n)"default"!==r&&function(t){e.d(a,t,function(){return n[t]})}(r);a["default"]=i.a},db12:function(t,a,e){"use strict";e.r(a);var n=e("b7f5"),i=e.n(n);for(var r in n)"default"!==r&&function(t){e.d(a,t,function(){return n[t]})}(r);a["default"]=i.a},e678:function(t,a,e){"use strict";e.r(a);var n=e("521c"),i=e("db12");for(var r in i)"default"!==r&&function(t){e.d(a,t,function(){return i[t]})}(r);e("d1de");var s=e("2877"),o=Object(s["a"])(i["default"],n["a"],n["b"],!1,null,"3da97058",null);a["default"]=o.exports},f4c3:function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,'.index-container[data-v-3da97058]{padding:%?90?% %?20?% 0;margin-bottom:%?100?%}.index-container .top-tab-container[data-v-3da97058]{height:%?70?%;color:#6b4a39;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;position:fixed;width:100%;left:0;margin-top:%?-90?%;z-index:6;padding:0 %?20?%;background-color:#efccc8}.index-container .top-tab-container .left-tab-group[data-v-3da97058]{font-size:%?26?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.index-container .top-tab-container .left-tab-group .tab-item[data-v-3da97058]{margin-left:%?10?%;margin-right:%?30?%;position:relative}.index-container .top-tab-container .left-tab-group .tab-item.active[data-v-3da97058]{font-size:%?28?%}.index-container .top-tab-container .left-tab-group .tab-item.active[data-v-3da97058]:before{position:absolute;content:"";height:%?8?%;width:%?50?%;border-radius:%?14?%;bottom:%?-10?%;left:50%;-webkit-transform:translateX(-50%);-ms-transform:translateX(-50%);transform:translateX(-50%);background-color:#6b4a39}.index-container .top-tab-container .right-search[data-v-3da97058]{margin:0 %?10?%;position:relative;overflow:hidden;border-radius:%?30?%;color:#fff}.index-container .top-tab-container .right-search uni-input[data-v-3da97058]{background-color:#e5b4b0;border-radius:%?30?%;width:%?300?%;height:%?54?%;padding:0 %?20?%;-webkit-transform:translateX(100%);-ms-transform:translateX(100%);transform:translateX(100%);-webkit-transition:-webkit-transform .3s ease;transition:-webkit-transform .3s ease;-o-transition:transform .3s ease;transition:transform .3s ease;transition:transform .3s ease,-webkit-transform .3s ease}.index-container .top-tab-container .right-search uni-input.show[data-v-3da97058]{-webkit-transform:translateX(0);-ms-transform:translateX(0);transform:translateX(0)}.index-container .top-tab-container .right-search .iconfont[data-v-3da97058]{width:%?54?%;height:%?54?%;font-size:%?40?%;position:absolute;top:50%;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);transform:translateY(-50%);right:0;background-color:#e5b4b0;z-index:1;border-radius:%?30?%}.index-container .middle-text-container[data-v-3da97058]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;color:#ce797c;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;height:%?96?%}.index-container .middle-text-container uni-view[data-v-3da97058]{padding:0 %?10?%}.index-container .middle-text-container .rule[data-v-3da97058]:after{content:"\\E64C";font-family:iconfont!important;padding-left:%?10?%}.index-container .topthree-container[data-v-3da97058]{height:%?440?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between}.index-container .topthree-container .content[data-v-3da97058]{width:%?224?%;height:100%;background-color:rgba(206,121,124,.3);border-radius:%?10?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.index-container .topthree-container .content .crown[data-v-3da97058]{width:%?82?%;height:%?66?%;margin-top:%?20?%}.index-container .topthree-container .content .avatar[data-v-3da97058]{width:%?160?%;height:%?160?%;border-radius:50%;margin-top:%?-10?%;position:relative}.index-container .topthree-container .content .avatar uni-image[data-v-3da97058]{border-radius:50%;width:%?140?%;height:%?140?%}.index-container .topthree-container .content .starname[data-v-3da97058]{margin-top:%?10?%}.index-container .topthree-container .content .hot[data-v-3da97058]{margin-top:%?10?%;margin-bottom:%?10?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;color:#6b4a39}.index-container .topthree-container .content .hot uni-image[data-v-3da97058]{width:%?30?%;height:%?30?%;margin-left:%?4?%}.index-container .topthree-container .content .button[data-v-3da97058]{margin-top:%?16?%;color:#6f3309;border-radius:%?10?%;width:%?136?%;height:%?68?%}.index-container .rank-list-container[data-v-3da97058]{margin-top:%?20?%;margin-left:%?-20?%;margin-right:%?-20?%}.index-container .rank-list-container .rank-list-item .left-container[data-v-3da97058]{line-height:%?44?%}.index-container .rank-list-container .rank-list-item .left-container .bottom-text[data-v-3da97058]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.index-container .rank-list-container .rank-list-item .left-container .bottom-text .hot-count[data-v-3da97058]{color:#ce797c;margin-right:%?4?%}.index-container .rank-list-container .rank-list-item .left-container .bottom-text .icon-heart[data-v-3da97058]{width:%?30?%;height:%?30?%}.index-container .rank-list-container .rank-list-item .right-container[data-v-3da97058]{margin-right:%?40?%}.index-container .open-ad-container[data-v-3da97058]{position:fixed;top:0;left:0;right:0;bottom:0;z-index:99;background-color:rgba(0,0,0,.5);-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column}.index-container .open-ad-container .main[data-v-3da97058]{width:%?532?%;height:%?942?%;border-radius:%?20?%}.index-container .open-ad-container .close-btn[data-v-3da97058]{width:%?80?%;height:%?80?%;margin-top:%?10?%}',""])},f564:function(t,a,e){"use strict";e.r(a);var n=e("018f"),i=e("d1ef");for(var r in i)"default"!==r&&function(t){e.d(a,t,function(){return i[t]})}(r);e("08dd");var s=e("2877"),o=Object(s["a"])(i["default"],n["a"],n["b"],!1,null,"40c13d7b",null);a["default"]=o.exports},f7e0:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{scale:""}},props:{type:{default:""}}};a.default=n}}]);