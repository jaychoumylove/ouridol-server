(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-notice-list-list"],{"018f":function(t,e,i){"use strict";var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"container"},[i("v-uni-view",{staticClass:"left-container flex-set"},[i("v-uni-view",{staticClass:"rank-num"},[t._v(t._s(t.rank))]),t.avatar?i("v-uni-image",{staticClass:"avatar",attrs:{src:t.avatar,mode:"aspectFill"}}):t._e(),t._t("left-container")],2),i("v-uni-view",{staticClass:"right-container"},[t._t("right-container")],2)],1)},n=[];i.d(e,"a",function(){return a}),i.d(e,"b",function(){return n})},"08dd":function(t,e,i){"use strict";var a=i("8c93"),n=i.n(a);n.a},"234f":function(t,e,i){var a=i("435d");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var n=i("4f06").default;n("a569fc24",a,!0,{sourceMap:!1,shadowMode:!1})},"435d":function(t,e,i){e=t.exports=i("2350")(!1),e.push([t.i,".container .item-wrapper[data-v-29500042]{background:url(http://wx4.sinaimg.cn/large/0060lm7Tly1g2qaiukofkg30kc02t744.gif) 0 no-repeat/contain;padding:%?15?% 0;margin:%?10?% 0;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;height:%?110?%}.container .item-wrapper .left[data-v-29500042]{margin-left:%?40?%;font-size:%?32?%;width:%?450?%}.container .item-wrapper .left uni-image[data-v-29500042]{width:%?50?%;margin-top:%?-15?%}.container .item-wrapper .right[data-v-29500042]{margin-right:%?30?%;margin-top:%?-36?%}.container .item-wrapper.top[data-v-29500042]{background:url(http://wx3.sinaimg.cn/large/0060lm7Tly1g2smboymitg30f002twea.gif) 0 no-repeat/contain}",""])},"471b":function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=n(i("f564"));function n(t){return t&&t.__esModule?t:{default:t}}var r={components:{listItemComponent:a.default},data:function(){return{requestCount:1,articleList:[]}},onLoad:function(){this.getArticleList()},methods:{goArticle:function(t){this.$app.goPage("/pages/notice/notice?id="+t)},getArticleList:function(){var t=this;this.$app.request(this.$app.API.ARTICLE_LIST,{},function(e){var i=[],a=!0,n=!1,r=void 0;try{for(var s,c=e.data[Symbol.iterator]();!(a=(s=c.next()).done);a=!0){var o=s.value;i.push({id:o.id,isTop:o.is_top,title:o.name,time:o.create_time.slice(0,11),isNew:Date.now()/1e3-t.$app.strToTime(o.create_time)<259200})}}catch(u){n=!0,r=u}finally{try{a||null==c.return||c.return()}finally{if(n)throw r}}t.articleList=i,t.$app.closeLoading(t)})}}};e.default=r},"48bd":function(t,e,i){"use strict";var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"container"},t._l(t.articleList,function(e,a){return i("v-uni-view",{key:a,staticClass:"item-wrapper",class:{top:e.isTop},on:{click:function(i){i=t.$handleEvent(i),t.goArticle(e.id)}}},[i("v-uni-view",{staticClass:"left text-overflow"},[t._v(t._s(e.title)+t._s(e.isTop?"[置顶]":"")),e.isNew?i("v-uni-image",{attrs:{src:"/static/image/user/new.png",mode:"widthFix"}}):t._e()],1),i("v-uni-view",{staticClass:"right"},[t._v(t._s(e.isTop?"":e.time))])],1)}),1)},n=[];i.d(e,"a",function(){return a}),i.d(e,"b",function(){return n})},"71e9":function(t,e,i){e=t.exports=i("2350")(!1),e.push([t.i,".container[data-v-40c13d7b]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;padding:%?15?% 0;background-color:hsla(0,0%,100%,.3);margin:%?10?% 0;width:100%}.container .left-container .rank-num[data-v-40c13d7b]{text-align:center;width:%?90?%}.container .left-container .avatar[data-v-40c13d7b]{width:%?110?%;height:%?110?%;border-radius:50%;margin-right:%?40?%}",""])},8133:function(t,e,i){"use strict";i.r(e);var a=i("48bd"),n=i("b0c0");for(var r in n)"default"!==r&&function(t){i.d(e,t,function(){return n[t]})}(r);i("fad9");var s=i("2877"),c=Object(s["a"])(n["default"],a["a"],a["b"],!1,null,"29500042",null);e["default"]=c.exports},"8c93":function(t,e,i){var a=i("71e9");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var n=i("4f06").default;n("07efe220",a,!0,{sourceMap:!1,shadowMode:!1})},b0c0:function(t,e,i){"use strict";i.r(e);var a=i("471b"),n=i.n(a);for(var r in a)"default"!==r&&function(t){i.d(e,t,function(){return a[t]})}(r);e["default"]=n.a},d1ef:function(t,e,i){"use strict";i.r(e);var a=i("d860"),n=i.n(a);for(var r in a)"default"!==r&&function(t){i.d(e,t,function(){return a[t]})}(r);e["default"]=n.a},d860:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a={data:function(){return{}},props:{rank:{default:""},avatar:{default:""}}};e.default=a},f564:function(t,e,i){"use strict";i.r(e);var a=i("018f"),n=i("d1ef");for(var r in n)"default"!==r&&function(t){i.d(e,t,function(){return n[t]})}(r);i("08dd");var s=i("2877"),c=Object(s["a"])(n["default"],a["a"],a["b"],!1,null,"40c13d7b",null);e["default"]=c.exports},fad9:function(t,e,i){"use strict";var a=i("234f"),n=i.n(a);n.a}}]);