(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-group-dynamic-dynamic"],{"422d":function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"container"},t._l(t.list,function(e,i){return a("v-uni-view",{key:i,staticClass:"item",class:{send:2==e.type}},[a("v-uni-image",{staticClass:"avatar",attrs:{src:e.avatarurl,mode:"aspectFill"}}),a("v-uni-view",{staticClass:"content"},[a("v-uni-view",{staticClass:"left-content"},[a("v-uni-view",{staticClass:"name"},[a("v-uni-view",{staticClass:"nickname"},[t._v(t._s(e.nickname))]),a("v-uni-view",{staticClass:"starname"},[t._v(t._s(e.star))])],1),1==e.type?a("v-uni-view",{staticClass:"bottom"},[t._v("偷偷盗走"),a("v-uni-text",[t._v(t._s(t.starname))]),t._v("能量")],1):t._e(),2==e.type?a("v-uni-view",{staticClass:"bottom"},[t._v("给"),a("v-uni-text",[t._v(t._s(t.starname))]),t._v("赠送能量")],1):t._e()],1),a("v-uni-view",{staticClass:"right-content"},[a("v-uni-view",{staticClass:"time"},[t._v(t._s(e.time))]),a("v-uni-view",{staticClass:"bottom flex-set"},[a("v-uni-image",{attrs:{src:"/static/image/user/b1.png",mode:"widthFix"}}),a("v-uni-view",{staticClass:"count"},[t._v(t._s(e.coin))])],1)],1)],1)],1)}),1)},n=[];a.d(e,"a",function(){return i}),a.d(e,"b",function(){return n})},"43b0":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={data:function(){return{requestCount:1,starid:null,starname:"",list:[]}},onLoad:function(t){this.starid=t.starid,this.starname=t.starname},methods:{getDynamic:function(){var t=this;this.$app.request(this.$app.API.STAR_DYNAMIC,{starid:this.starid},function(e){var a=[],i=!0,n=!1,s=void 0;try{for(var r,c=e.data[Symbol.iterator]();!(i=(r=c.next()).done);i=!0){var o=r.value;a.push({type:o.type,avatarurl:o.user&&o.user.avatarurl||t.$app.AVATAR,nickname:o.user&&o.user.nickname||t.$app.NICKNAME,star:o.user&&o.user.user_star&&o.user.user_star.star.name||"???",time:o.create_time.slice(11),coin:Math.abs(o.coin)})}}catch(l){n=!0,s=l}finally{try{i||null==c.return||c.return()}finally{if(n)throw s}}t.list=a,t.$app.closeLoading(t)})}}};e.default=i},"741b":function(t,e,a){"use strict";a.r(e);var i=a("43b0"),n=a.n(i);for(var s in i)"default"!==s&&function(t){a.d(e,t,function(){return i[t]})}(s);e["default"]=n.a},"771a":function(t,e,a){var i=a("7b6c");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("6590e956",i,!0,{sourceMap:!1,shadowMode:!1})},"7b6c":function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".container[data-v-0ecc554b]{font-size:%?26?%}.container .item[data-v-0ecc554b]{height:%?120?%;margin:%?20?% 0;background:url(http://wx1.sinaimg.cn/large/0060lm7Tly1g2h8p2voudg30k203d3ya.gif) 100% no-repeat/contain;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.container .item .avatar[data-v-0ecc554b]{width:%?90?%;height:%?90?%;-webkit-border-radius:50%;border-radius:50%;margin-left:%?100?%}.container .item .content[data-v-0ecc554b]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;padding-left:%?20?%;padding-right:%?30?%}.container .item .left-content .name[data-v-0ecc554b]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.container .item .left-content .name .starname[data-v-0ecc554b]{background-color:#d2781e;-webkit-border-radius:%?20?%;border-radius:%?20?%;color:#fff;font-size:%?24?%;padding:%?0?% %?8?%;margin-left:%?4?%}.container .item .left-content .bottom[data-v-0ecc554b]{color:#fff;font-size:%?24?%}.container .item .left-content .bottom uni-text[data-v-0ecc554b]{color:#703ba3;margin:0 %?4?%}.container .item .right-content[data-v-0ecc554b]{margin-left:%?150?%}.container .item .right-content .bottom[data-v-0ecc554b]{color:#348036}.container .item .right-content .bottom uni-image[data-v-0ecc554b]{width:%?26?%;margin-right:%?10?%}.container .item.send[data-v-0ecc554b]{background:url(http://wx3.sinaimg.cn/large/0060lm7Tly1g2hf1do6hwg30k203d3ya.gif) 100% no-repeat/contain}.container .item.send .left-content .starname[data-v-0ecc554b]{background-color:#c50083}.container .item.send .right-content .bottom[data-v-0ecc554b]{color:#c50083}",""])},c47c:function(t,e,a){"use strict";var i=a("771a"),n=a.n(i);n.a},fa91:function(t,e,a){"use strict";a.r(e);var i=a("422d"),n=a("741b");for(var s in n)"default"!==s&&function(t){a.d(e,t,function(){return n[t]})}(s);a("c47c");var r=a("2877"),c=Object(r["a"])(n["default"],i["a"],i["b"],!1,null,"0ecc554b",null);e["default"]=c.exports}}]);