(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-pet-other-other"],{"0748":function(t,e,a){"use strict";a.r(e);var i=a("bae6"),n=a("3221");for(var r in n)"default"!==r&&function(t){a.d(e,t,function(){return n[t]})}(r);a("8652");var s=a("2877"),o=Object(s["a"])(n["default"],i["a"],i["b"],!1,null,"51bf02fb",null);e["default"]=o.exports},3221:function(t,e,a){"use strict";a.r(e);var i=a("32ad"),n=a.n(i);for(var r in i)"default"!==r&&function(t){a.d(e,t,function(){return i[t]})}(r);e["default"]=n.a},"32ad":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=r(a("f372")),n=r(a("cbb7"));function r(t){return t&&t.__esModule?t:{default:t}}var s={components:{modalComponent:i.default,btnComponent:n.default},data:function(){return{requestCount:1,user_id:null,spriteInfo:{sprite_level:0},invitList:[],invitAward:"",modal:"",earnCuttime:1,skillShow:!1,off:!1,userInfo:{nickname:this.$app.NICKNAME}}},onShareAppMessage:function(){return this.$app.commonShareAppMessage()},onLoad:function(t){this.user_id=t.user_id||0,"true"==t.off&&(this.off=!0),this.getSpriteInfo(),this.getUserInfo()},methods:{getUserInfo:function(){var t=this;this.$app.request(this.$app.API.USER_INFO,{user_id:this.user_id},function(e){t.userInfo=e.data,t.userInfo={avatarurl:e.data["avatarurl"]||t.$app.AVATAR,nickname:e.data["nickname"]||t.$app.NICKNAME,id:e.data["id"]||null}})},settle:function(){var t=this;this.spriteInfo.earn<2?this.$app.toast("TA的能量太少了，稍后再来吧"):this.off?this.modal="tips_t":this.$app.request(this.$app.API.SPRITE_SETTLE,{user_id:this.user_id},function(e){t.getSpriteInfo(),t.$app.toast("为TA收集能量，你获得:"+e.data+"能量"),t.$app.request(t.$app.API.USER_CURRENCY,{},function(e){t.$app.setData("userCurrency",e.data)})},"POST",!0)},getSpriteInfo:function(){var t=this;this.$app.request(this.$app.API.SPRITE_INFO,{user_id:this.user_id},function(e){t.spriteInfo=e.data,t.$app.closeLoading(t)})}}};e.default=s},"360d":function(t,e,a){var i=a("a202");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("c4eedd64",i,!0,{sourceMap:!1,shadowMode:!1})},8652:function(t,e,a){"use strict";var i=a("360d"),n=a.n(i);n.a},a202:function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,'@charset "UTF-8";.container[data-v-51bf02fb]{height:100%;overflow:hidden;position:relative;background:url(http://wx4.sinaimg.cn/large/0060lm7Tly1g2h7ro3ehvj30kv15pdig.jpg) 50% no-repeat/cover}.container .user-container[data-v-51bf02fb]{position:absolute;height:%?60?%;top:%?40?%;left:%?40?%;background-color:hsla(0,0%,100%,.3);display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-border-radius:%?30?%;border-radius:%?30?%}.container .user-container uni-image[data-v-51bf02fb]{width:%?60?%;-webkit-border-radius:50%;border-radius:50%;margin-right:%?20?%}.container .user-container .nickname[data-v-51bf02fb]{font-size:%?32?%;margin-right:%?30?%}.container .sprite[data-v-51bf02fb]{position:absolute;left:50%;-webkit-transform:translateX(-50%);-ms-transform:translateX(-50%);transform:translateX(-50%);bottom:5%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}@-webkit-keyframes bounce-data-v-51bf02fb{0%,to{-webkit-transform:translateY(0);transform:translateY(0)}65%{-webkit-transform:translateY(-10%);transform:translateY(-10%)}}@keyframes bounce-data-v-51bf02fb{0%,to{-webkit-transform:translateY(0);transform:translateY(0)}65%{-webkit-transform:translateY(-10%);transform:translateY(-10%)}}.container .sprite .bounce-article[data-v-51bf02fb]{-webkit-animation:bounce-data-v-51bf02fb .8s ease-in-out infinite;animation:bounce-data-v-51bf02fb .8s ease-in-out infinite}.container .sprite .sprite-main[data-v-51bf02fb]{width:%?230?%}.container .sprite .sprite-level[data-v-51bf02fb]{top:%?-60?%;color:#fff;font-size:%?28?%}.container .sprite .sprite-level uni-image[data-v-51bf02fb]{width:%?160?%}.container .sprite .sprite-level .text[data-v-51bf02fb]{top:%?36?%}.container .sprite .shadow[data-v-51bf02fb]{height:%?40?%;width:%?140?%;height:%?10?%;margin:auto;-webkit-border-radius:50%;border-radius:50%;background:#37a45b;-webkit-box-shadow:0 0 %?5?% #37a45b;box-shadow:0 0 %?5?% #37a45b;-webkit-animation:shadow-data-v-51bf02fb .8s ease-in-out infinite;animation:shadow-data-v-51bf02fb .8s ease-in-out infinite}@-webkit-keyframes shadow-data-v-51bf02fb{0%,to{-webkit-transform:scaleX(1);transform:scaleX(1)}50%{-webkit-transform:scaleX(.9);transform:scaleX(.9)}}@keyframes shadow-data-v-51bf02fb{0%,to{-webkit-transform:scaleX(1);transform:scaleX(1)}50%{-webkit-transform:scaleX(.9);transform:scaleX(.9)}}.container .sprite .progress[data-v-51bf02fb]{height:%?40?%;z-index:1;margin-top:%?20?%;width:100%;-webkit-border-radius:%?20?%;border-radius:%?20?%;color:#fff;background-color:#97cee3;border:%?4?% solid #68478e;font-size:%?20?%;position:relative;overflow:hidden}.container .sprite .progress .progress-bar[data-v-51bf02fb]{content:"";position:absolute;top:0;left:0;height:100%;background-color:#c90186;z-index:-1}.container .sprite .skill-container[data-v-51bf02fb]{width:%?100?%;height:%?100?%;background-color:red;-webkit-transition:.3;-o-transition:.3;transition:.3;-webkit-transform:scale(0);-ms-transform:scale(0);transform:scale(0)}.container .sprite .skill-container.show[data-v-51bf02fb]{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}@-webkit-keyframes shine-data-v-51bf02fb{0%,to{-webkit-transform:scale(.8);transform:scale(.8)}50%{-webkit-transform:scale(1);transform:scale(1)}}@keyframes shine-data-v-51bf02fb{0%,to{-webkit-transform:scale(.8);transform:scale(.8)}50%{-webkit-transform:scale(1);transform:scale(1)}}.container .earn-container.online[data-v-51bf02fb]:before{content:"";position:absolute;z-index:1;width:%?70?%;height:%?90?%;top:%?28?%;-webkit-border-radius:50%;border-radius:50%;left:%?100?%;background-color:gold;-webkit-filter:blur(%?10?%);filter:blur(%?10?%);-webkit-animation:shine-data-v-51bf02fb 1.5s linear infinite;animation:shine-data-v-51bf02fb 1.5s linear infinite}.container .earn-container[data-v-51bf02fb]{position:absolute;right:%?0?%;bottom:22%}.container .earn-container .mountain[data-v-51bf02fb]{width:%?230?%}.container .earn-container .egg[data-v-51bf02fb]{width:%?90?%;position:absolute;top:%?34?%;left:%?90?%;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;z-index:2}.container .earn-container .egg .num-wrapper[data-v-51bf02fb]{z-index:3;top:%?32?%;font-size:%?24?%;color:#fff}.container .earn-container .egg uni-image[data-v-51bf02fb]{width:%?60?%}.container .earn-container .egg .progress[data-v-51bf02fb]{height:%?30?%;z-index:1;margin-top:%?-20?%;width:100%;-webkit-border-radius:%?20?%;border-radius:%?20?%;color:#fff;background-color:#97cee3;border:%?4?% solid #68478e;text-align:center;font-size:%?20?%;line-height:1;position:relative;overflow:hidden}.container .earn-container .egg .progress .progress-bar[data-v-51bf02fb]{content:"";position:absolute;top:0;left:0;right:0;height:100%;background-color:#c90186;z-index:-1}@-webkit-keyframes progAnime-data-v-51bf02fb{0%{-webkit-transform:translateX(-100%);transform:translateX(-100%)}to{-webkit-transform:translateX(0);transform:translateX(0)}}@keyframes progAnime-data-v-51bf02fb{0%{-webkit-transform:translateX(-100%);transform:translateX(-100%)}to{-webkit-transform:translateX(0);transform:translateX(0)}}.container .earn-container .hand-wrap[data-v-51bf02fb]{width:%?100?%;top:%?-4?%;left:%?134?%;z-index:2}.container .earn-container .hand-wrap .bubble[data-v-51bf02fb]:before{content:"\\53EF\\6536\\96C6";position:absolute;margin-top:%?-4?%;font-size:%?24?%;font-weight:700}.container .earn-container .hand-wrap .hand[data-v-51bf02fb]{width:%?80?%;position:absolute;right:%?-6?%;top:%?20?%;-webkit-animation:scale-data-v-51bf02fb 1s linear infinite;animation:scale-data-v-51bf02fb 1s linear infinite}@-webkit-keyframes scale-data-v-51bf02fb{0%,to{-webkit-transform:scale(.9);transform:scale(.9)}50%{-webkit-transform:scale(1.1);transform:scale(1.1)}}@keyframes scale-data-v-51bf02fb{0%,to{-webkit-transform:scale(.9);transform:scale(.9)}50%{-webkit-transform:scale(1.1);transform:scale(1.1)}}.container .invit-modal-container[data-v-51bf02fb]{width:100%;height:100%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column}.container .invit-modal-container .explain-wrapper[data-v-51bf02fb]{padding:%?10?% %?20?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.container .invit-modal-container .explain-wrapper uni-image[data-v-51bf02fb]{width:%?100?%;margin:%?10?% %?20?%}.container .invit-modal-container .list-wrapper[data-v-51bf02fb]{overflow-y:auto;height:100%}.container .invit-modal-container .list-wrapper .item[data-v-51bf02fb]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:start;-webkit-justify-content:flex-start;-ms-flex-pack:start;justify-content:flex-start;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;padding:%?10?% %?20?%;-webkit-border-radius:%?60?%;border-radius:%?60?%;background-color:hsla(0,0%,100%,.3);margin:%?10?%}.container .invit-modal-container .list-wrapper .item .rank-num[data-v-51bf02fb]{width:%?90?%;text-align:center}.container .invit-modal-container .list-wrapper .item .avatar uni-image[data-v-51bf02fb]{width:%?90?%;height:%?90?%;-webkit-border-radius:50%;border-radius:50%}.container .invit-modal-container .list-wrapper .item .text-container[data-v-51bf02fb]{width:%?300?%;padding:0 %?30?%;line-height:%?44?%}.container .invit-modal-container .list-wrapper .item .text-container .bottom-text[data-v-51bf02fb]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.container .invit-modal-container .list-wrapper .item .text-container .bottom-text .hot-count[data-v-51bf02fb]{color:#ce797c;margin-right:%?4?%}.container .invit-modal-container .list-wrapper .item .text-container .bottom-text .icon-heart[data-v-51bf02fb]{width:%?30?%;height:%?30?%}.container .tips-modal-container-s[data-v-51bf02fb]{height:100%;padding:%?20?% %?40?%;font-size:%?32?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column}.container .tips-modal-container-s .text-wrap[data-v-51bf02fb]{text-align:center}.container .tips-modal-container-s .text-wrap uni-image[data-v-51bf02fb]{width:%?120?%;height:%?120?%;margin:%?60?%}.container .tips-modal-container-s .text-wrap .text[data-v-51bf02fb]{font-size:%?32?%;font-weight:700}.container .tips-modal-container-s .btn[data-v-51bf02fb]{margin:auto}',""])},bae6:function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"container"},[a("v-uni-view",{staticClass:"user-container"},[a("v-uni-image",{attrs:{src:t.userInfo.avatarurl,mode:"widthFix"}}),a("v-uni-view",{staticClass:"nickname"},[t._v(t._s(t.userInfo.nickname))])],1),a("v-uni-view",{staticClass:"sprite"},[a("v-uni-view",{staticClass:"bounce-article"},[a("v-uni-view",{staticClass:"sprite-level position-set"},[a("v-uni-image",{attrs:{src:"/static/image/pet/fly.png",mode:"widthFix"}}),a("v-uni-view",{staticClass:"text position-set"},[t._v("LV"+t._s(t.spriteInfo.sprite_level))])],1),a("v-uni-image",{staticClass:"sprite-main",attrs:{src:"/static/image/pet/sprite.png",mode:"widthFix"}})],1),a("v-uni-view",{staticClass:"shadow"})],1),a("v-uni-view",{staticClass:"earn-container",class:{online:!t.off},on:{click:function(e){e=t.$handleEvent(e),t.settle(e)}}},[a("v-uni-image",{staticClass:"mountain",attrs:{src:"/static/image/pet/y2.png",mode:"widthFix"}}),a("v-uni-view",{staticClass:"egg flex-set"},[a("v-uni-view",{staticClass:"num-wrapper position-set"},[t._v(t._s(t.spriteInfo.earn))]),t.off?a("v-uni-image",{staticClass:"flex-set",attrs:{src:"/static/image/pet/y5-off.png",mode:"widthFix"}}):a("v-uni-image",{staticClass:"flex-set",attrs:{src:"/static/image/pet/y5.png",mode:"widthFix"}})],1),a("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:t.spriteInfo.earn>=2&&!t.off,expression:"spriteInfo.earn >= 2 && !off"}],staticClass:"hand-wrap position-set"},[a("v-uni-image",{staticClass:"bubble flex-set",attrs:{src:"/static/image/pet/bubble.png",mode:"widthFix"}}),a("v-uni-image",{staticClass:"hand",attrs:{src:"/static/image/pet/hand.png",mode:"widthFix"}})],1)],1),"tips_t"==t.modal?a("modalComponent",{attrs:{title:"提示"},on:{closeModal:function(e){e=t.$handleEvent(e),t.modal=""}}},[a("v-uni-view",{staticClass:"tips-modal-container-s"},[a("v-uni-view",{staticClass:"text-wrap"},[a("v-uni-image",{attrs:{src:"/static/image/user/blank.png",mode:"widthFix"}}),a("v-uni-view",{staticClass:"text"},[t._v("好友已经很久没有打榜了")]),a("v-uni-view",{staticClass:"text"},[t._v("提醒TA一起为偶像打榜")]),a("v-uni-view",{staticClass:"text"},[t._v("才能帮TA收取能量")])],1),a("btnComponent",{attrs:{type:"css"}},[a("v-uni-button",{attrs:{"open-type":"share"}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{padding:"20upx 40upx"}},[t._v("唤醒好友")])],1)],1)],1)],1):t._e()],1)},n=[];a.d(e,"a",function(){return i}),a.d(e,"b",function(){return n})}}]);