(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-task-task"],{"09f8":function(t,e,a){"use strict";var i=a("a04a"),n=a.n(i);n.a},"2d5d":function(t,e,a){var i=a("ff49");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("a60ff580",i,!0,{sourceMap:!1,shadowMode:!1})},3912:function(t,e,a){"use strict";a.r(e);var i=a("3a15"),n=a.n(i);for(var s in i)"default"!==s&&function(t){a.d(e,t,function(){return i[t]})}(s);e["default"]=n.a},"3a15":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=n(a("cbb7"));function n(t){return t&&t.__esModule?t:{default:t}}var s={components:{btnComponent:i.default},data:function(){return{show:!1}},props:{title:{default:"提示"},headimg:{default:"/static/image/guild/hart.png"}},created:function(){},mounted:function(){this.show=!0},methods:{closeModal:function(){var t=this;this.show=!1,setTimeout(function(){t.$emit("closeModal")},200)}}};e.default=s},"45e5":function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".container[data-v-e4a53ea2]{position:fixed;top:0;left:0;right:0;bottom:0;z-index:99;background-color:rgba(0,0,0,.5);-webkit-transition:.2s;-o-transition:.2s;transition:.2s;opacity:.1}.container .modal-container[data-v-e4a53ea2]{width:%?600?%;height:%?740?%;-webkit-box-shadow:0 1px 2px rgba(0,0,0,.3);box-shadow:0 1px 2px rgba(0,0,0,.3);border-radius:%?20?%;background:url(http://wx3.sinaimg.cn/large/0060lm7Tly1g2dpyg3vxng30gp0kk74c.gif) 50% no-repeat/cover;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-transition:.2s;-o-transition:.2s;transition:.2s;-webkit-transform:scale(.9);-ms-transform:scale(.9);transform:scale(.9)}.container .modal-container .top-wrapper[data-v-e4a53ea2]{width:100%;height:12.6%;position:relative}.container .modal-container .top-wrapper .title[data-v-e4a53ea2]{font-size:%?34?%;position:absolute;top:50%;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);transform:translateY(-50%);left:%?30?%;color:#fff}.container .modal-container .top-wrapper .center-img[data-v-e4a53ea2]{width:%?100?%;height:%?100?%;position:absolute;left:50%;-webkit-transform:translateX(-50%);-ms-transform:translateX(-50%);transform:translateX(-50%);top:-30%}.container .modal-container .top-wrapper .close-btn[data-v-e4a53ea2]{position:absolute;right:%?0?%;top:%?0?%}.container .modal-container .top-wrapper .close-btn uni-image[data-v-e4a53ea2]{width:%?56?%;height:%?56?%}.container .modal-container .content[data-v-e4a53ea2]{width:100%;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;position:relative}.container.show[data-v-e4a53ea2]{opacity:1}.container.show .modal-container[data-v-e4a53ea2]{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}",""])},5243:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=s(a("cbb7")),n=s(a("f372"));function s(t){return t&&t.__esModule?t:{default:t}}var o={components:{btnComponent:i.default,modalComponent:n.default},data:function(){return{requestCount:1,taskList:[],videoAd:null,modal:"",shareText:"",weiboUrl:""}},onShow:function(){this.getTaskList()},onLoad:function(){this.getShareText()},onShareAppMessage:function(t){var e=t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(e)},methods:{initVideoAd:function(){var t=this;wx.createRewardedVideoAd&&(this.videoAd=wx.createRewardedVideoAd({adUnitId:"adunit-9fa8b9c723fc27be"}),this.videoAd.onClose(function(e){if(e&&e.isEnded||void 0===e)for(var a in t.taskList){var i=t.taskList[a];7==i.type&&(t.taskList[a].status=1)}else t.$app.toast("观看完视频才有奖励哦")}),this.videoAd.onError(function(t){console.error("onError",t)}))},openAdver:function(){var t=this;this.videoAd&&this.videoAd.show().catch(function(e){t.videoAd.load().then(function(){t.videoAd.show()})})},clipboard:function(){var t=this;uni.setClipboardData({data:this.shareText,success:function(){t.$app.toast("复制成功","success")}})},weiboCommit:function(){var t=this;this.$app.request(this.$app.API.TASK_WEIBO,{weiboUrl:this.weiboUrl},function(e){t.$app.toast("提交成功","success"),t.modal="",t.getTaskList()})},doTask:function(t){var e=this;if(0==t.status){if(4==t.task_type.id&&~this.$app.getData("sysInfo").system.indexOf("iOS"))return;7==t.task_type.id?this.openAdver():8==t.task_type.id?this.modal="weibo":12==t.task_type.id?this.$app.request(this.$app.API.SHARE_STARMASS,{},function(t){}):t.task_type.gopage&&this.$app.goPage(t.task_type.gopage)}else 1==t.status&&this.$app.request(this.$app.API.TASK_SETTLE,{task_id:t.id},function(t){var a="领取成功";t.data.coin&&(a+="，能量+"+t.data.coin),t.data.stone&&(a+="，灵丹+"+t.data.stone),t.data.trumpet&&(a+="，喇叭+"+t.data.trumpet),e.$app.toast(a),e.getTaskList(),e.$app.request(e.$app.API.USER_CURRENCY,{},function(t){e.$app.setData("userCurrency",t.data)})},"POST",!0)},getShareText:function(){var t=this;this.$app.request(this.$app.API.EXT_SHARETEXT,{},function(e){t.shareText=e.data})},getTaskList:function(){var t=this;this.$app.request(this.$app.API.TASK,{},function(e){var a=[];t.$app.isTaskAllDone=!0;var i=!0,n=!1,s=void 0;try{for(var o,r=e.data[Symbol.iterator]();!(i=(o=r.next()).done);i=!0){var c=o.value;0==c.status&&(t.$app.isTaskAllDone=!1),a.push({id:c.id,coin:c.coin||0,stone:c.stone||0,trumpet:c.trumpet||0,status:c.status,name:c.name,doneTimes:c.doneTimes,times:c.times,type:c.type,task_type:{id:c.task_type.id,gopage:c.task_type.gopage,btn_text:c.task_type.btn_text,img:c.task_type.img}})}}catch(l){n=!0,s=l}finally{try{i||null==r.return||r.return()}finally{if(n)throw s}}t.taskList=a,t.$app.closeLoading(t)})}}};e.default=o},5579:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={data:function(){return{scale:""}},props:{type:{default:""}}};e.default=i},"7f21":function(t,e,a){"use strict";a.r(e);var i=a("9cee"),n=a("b149");for(var s in n)"default"!==s&&function(t){a.d(e,t,function(){return n[t]})}(s);a("95b5");var o=a("2877"),r=Object(o["a"])(n["default"],i["a"],i["b"],!1,null,"4d54fc5d",null);e["default"]=r.exports},"95b5":function(t,e,a){"use strict";var i=a("2d5d"),n=a.n(i);n.a},"9cee":function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"container"},[t._l(t.taskList,function(e,i){return 4==e.type&&~t.$app.getData("sysInfo").system.indexOf("iOS")&&0==t.$app.getData("config").ios_switch||7==e.type&&!t.videoAd?t._e():a("v-uni-view",{key:i,staticClass:"item"},[a("v-uni-view",{staticClass:"left-content"},[a("v-uni-image",{staticClass:"img",attrs:{src:e.task_type.img,mode:""}}),a("v-uni-view",{staticClass:"content "},[a("v-uni-view",{staticClass:"top text-overflow"},[t._v(t._s(e.name))]),e.times?a("v-uni-view",{staticClass:"bottom"},[t._v("已完成("+t._s(e.doneTimes)+"/"+t._s(e.times)+")")]):t._e()],1)],1),a("v-uni-view",{staticClass:"right-content"},[a("v-uni-view",{staticClass:"earn"},[e.coin?a("v-uni-view",{staticClass:"right-item"},[a("v-uni-image",{attrs:{src:"/static/image/user/b1.png",mode:"widthFix"}}),a("v-uni-view",{staticClass:"add-count"},[t._v("+"+t._s(e.coin))])],1):t._e(),e.stone?a("v-uni-view",{staticClass:"right-item"},[a("v-uni-image",{attrs:{src:"/static/image/user/b2.png",mode:"widthFix"}}),a("v-uni-view",{staticClass:"add-count"},[t._v("+"+t._s(e.stone))])],1):t._e(),e.trumpet?a("v-uni-view",{staticClass:"right-item"},[a("v-uni-image",{attrs:{src:"/static/image/user/b3.png",mode:"widthFix"}}),a("v-uni-view",{staticClass:"add-count"},[t._v("+"+t._s(e.trumpet))])],1):t._e()],1),a("v-uni-view",{staticClass:"btn",on:{click:function(a){a=t.$handleEvent(a),t.doTask(e)}}},[0==e.status?a("btnComponent",{attrs:{type:"default"}},[9==e.type?a("v-uni-button",{staticClass:"btn",attrs:{"open-type":"share"}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v(t._s(e.task_type.btn_text))])],1):12==e.type?a("v-uni-button",{staticClass:"btn",attrs:{"open-type":"share","data-share":"2"}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v(t._s(e.task_type.btn_text))])],1):4==e.type&&1==t.$app.getData("config").ios_switch&&~t.$app.getData("sysInfo").system.indexOf("iOS")?a("v-uni-button",{staticClass:"btn",attrs:{"open-type":"contact","show-message-card":""}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v('回复"1"')])],1):a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v(t._s(e.task_type.btn_text))])],1):t._e(),1==e.status?a("btnComponent",{attrs:{type:"success"}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v("可领取")])],1):t._e(),2==e.status?a("btnComponent",{attrs:{type:"disable"}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v("已完成")])],1):t._e()],1)],1)],1)}),"weibo"==t.modal?a("modalComponent",{attrs:{title:"提示"},on:{closeModal:function(e){e=t.$handleEvent(e),t.modal=""}}},[a("v-uni-view",{staticClass:"weibo-modal-container flex-set"},[a("v-uni-view",{staticClass:"line"},[t._v("第一步：复制微博格式")]),a("v-uni-view",{staticClass:"text-wrapper",on:{click:function(e){e=t.$handleEvent(e),t.clipboard(e)}}},[t._v(t._s(t.shareText)),a("v-uni-view",{staticClass:"text"},[t._v("点击复制")])],1),a("v-uni-view",{staticClass:"line"},[t._v("第二步：发布帖子，填写帖子链接")]),a("v-uni-input",{attrs:{type:"text",placeholder:"帖子链接"},on:{input:function(e){e=t.$handleEvent(e),t.weiboUrl=e.detail.value}}}),a("btnComponent",{attrs:{type:"default"}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"160upx",height:"80upx"},on:{click:function(e){e=t.$handleEvent(e),t.weiboCommit(e)}}},[t._v("提交")])],1)],1)],1):t._e()],2)},n=[];a.d(e,"a",function(){return i}),a.d(e,"b",function(){return n})},a04a:function(t,e,a){var i=a("b35a");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("6642ed8a",i,!0,{sourceMap:!1,shadowMode:!1})},a6e5:function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"button flex-set",class:[t.type,t.scale],on:{touchstart:function(e){e=t.$handleEvent(e),t.scale="scale"},touchend:function(e){e=t.$handleEvent(e),t.scale=""}}},[t._t("default")],2)},n=[];a.d(e,"a",function(){return i}),a.d(e,"b",function(){return n})},ac92:function(t,e,a){var i=a("45e5");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("4b45998c",i,!0,{sourceMap:!1,shadowMode:!1})},b087:function(t,e,a){"use strict";var i=a("ac92"),n=a.n(i);n.a},b149:function(t,e,a){"use strict";a.r(e);var i=a("5243"),n=a.n(i);for(var s in i)"default"!==s&&function(t){a.d(e,t,function(){return i[t]})}(s);e["default"]=n.a},b35a:function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".button[data-v-138c0c18]{color:#6b4a39;-webkit-transition:.3s;-o-transition:.3s;transition:.3s}.button.scale[data-v-138c0c18]{-webkit-transform:scale(.7);-ms-transform:scale(.7);transform:scale(.7)}.button.default[data-v-138c0c18]{background:url(http://wx2.sinaimg.cn/large/0060lm7Tly1g2coigwh4sg303s01p741.gif) 50% no-repeat/100% 100%}.button.big[data-v-138c0c18]{background:url(http://wx4.sinaimg.cn/large/0060lm7Tly1g2dxu4igebg303v02cgld.gif) 50% no-repeat/100% 100%}.button.success[data-v-138c0c18]{background:url(http://wx4.sinaimg.cn/large/0060lm7Tly1g2ey5oz2oag303s01p741.gif) 50% no-repeat/100% 100%;color:#fff}.button.disable[data-v-138c0c18]{background:url(http://wx1.sinaimg.cn/large/0060lm7Tly1g2ey5nkm77g303s01p741.gif) 50% no-repeat/100% 100%}.button.fangde[data-v-138c0c18]{background:url(http://wx2.sinaimg.cn/large/0060lm7Tly1g2jwmn4sshg305v05vwea.gif) 50% no-repeat/100% 100%}",""])},cbb7:function(t,e,a){"use strict";a.r(e);var i=a("a6e5"),n=a("d29f");for(var s in n)"default"!==s&&function(t){a.d(e,t,function(){return n[t]})}(s);a("09f8");var o=a("2877"),r=Object(o["a"])(n["default"],i["a"],i["b"],!1,null,"138c0c18",null);e["default"]=r.exports},d29f:function(t,e,a){"use strict";a.r(e);var i=a("5579"),n=a.n(i);for(var s in i)"default"!==s&&function(t){a.d(e,t,function(){return i[t]})}(s);e["default"]=n.a},dce7:function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"container flex-set",class:{show:t.show},on:{click:function(e){e=t.$handleEvent(e),t.closeModal(e)}}},[a("v-uni-view",{staticClass:"modal-container",on:{click:function(e){e.stopPropagation(),e=t.$handleEvent(e)}}},[a("v-uni-view",{staticClass:"top-wrapper"},[a("v-uni-view",{staticClass:"title"},[t._v(t._s(t.title))]),a("v-uni-image",{staticClass:"center-img",attrs:{src:t.headimg,mode:""}}),a("v-uni-view",{staticClass:"close-btn"},[a("btnComponent",[a("v-uni-image",{attrs:{src:"/static/image/guild/close-btn.png",mode:""},on:{click:function(e){e=t.$handleEvent(e),t.closeModal(e)}}})],1)],1)],1),a("v-uni-view",{staticClass:"content"},[t._t("default")],2)],1)],1)},n=[];a.d(e,"a",function(){return i}),a.d(e,"b",function(){return n})},f372:function(t,e,a){"use strict";a.r(e);var i=a("dce7"),n=a("3912");for(var s in n)"default"!==s&&function(t){a.d(e,t,function(){return n[t]})}(s);a("b087");var o=a("2877"),r=Object(o["a"])(n["default"],i["a"],i["b"],!1,null,"e4a53ea2",null);e["default"]=r.exports},ff49:function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".container .item[data-v-4d54fc5d]{margin:%?20?%;background-color:hsla(0,0%,100%,.3);display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;padding:%?20?% %?40?%;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;border-radius:%?60?%}.container .item .left-content[data-v-4d54fc5d]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.container .item .left-content .img[data-v-4d54fc5d]{width:%?80?%;height:%?80?%;border-radius:50%}.container .item .left-content .content[data-v-4d54fc5d]{margin-left:%?20?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around}.container .item .left-content .content .top[data-v-4d54fc5d]{max-width:%?250?%}.container .item .left-content .content .bottom[data-v-4d54fc5d]{font-size:%?24?%;color:#888}.container .item .right-content[data-v-4d54fc5d]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.container .item .right-content .earn[data-v-4d54fc5d]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around;-webkit-box-align:start;-webkit-align-items:flex-start;-ms-flex-align:start;align-items:flex-start;margin-right:%?30?%;width:%?100?%}.container .item .right-content .earn .right-item[data-v-4d54fc5d]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.container .item .right-content .earn .right-item uni-image[data-v-4d54fc5d]{width:%?40?%}.container .item .right-content .btn[data-v-4d54fc5d]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.container .weibo-modal-container[data-v-4d54fc5d]{height:100%;padding:%?20?%;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-box-pack:start;-webkit-justify-content:flex-start;-ms-flex-pack:start;justify-content:flex-start}.container .weibo-modal-container .text-wrapper[data-v-4d54fc5d]{margin-top:%?20?%;margin-bottom:%?20?%;width:100%;border:%?4?% solid red;padding:%?10?%;padding-bottom:%?40?%;position:relative}.container .weibo-modal-container .text-wrapper .text[data-v-4d54fc5d]{position:absolute;right:%?20?%;bottom:%?-20?%;background-color:#efccc8}.container .weibo-modal-container uni-input[data-v-4d54fc5d]{width:90%;background-color:#eee;border-radius:%?60?%;height:%?70?%;margin-top:%?20?%;margin-bottom:%?20?%;padding:0 %?20?%}",""])}}]);