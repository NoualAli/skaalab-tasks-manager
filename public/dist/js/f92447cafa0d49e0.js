"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[911],{1911:(e,r,n)=>{n.r(r),n.d(r,{default:()=>t});var a=n(821),t={name:"AmBreadcrumbs",props:{showCurrentCrumb:{type:Boolean,default:!0}}};const l={key:0,class:"am-breadcrumbs"},c={class:"am-breadcrumbs__list"},s={key:0,class:"am-breadcrumbs__item"},m={class:"am-breadcrumbs__link-wrapper"},o={key:0,class:"am-breadcrumbs__link am-breadcrumbs__link_current"},d=(0,a.createElementVNode)("div",{class:"am-breadcrumbs__separator"}," / ",-1);!function(e,r){void 0===r&&(r={});var n=r.insertAt;if(e&&"undefined"!=typeof document){var a=document.head||document.getElementsByTagName("head")[0],t=document.createElement("style");t.type="text/css","top"===n&&a.firstChild?a.insertBefore(t,a.firstChild):a.appendChild(t),t.styleSheet?t.styleSheet.cssText=e:t.appendChild(document.createTextNode(e))}}("\n.am-breadcrumbs {\n  display: flex;\n  margin: 16px 16px;\n}\n.am-breadcrumbs__list {\n  display: block;\n  padding: 0;\n  margin: 0;\n  text-align: left;\n}\n.am-breadcrumbs__item {\n  display: inline;\n}\n.am-breadcrumbs__separator {\n  display: inline;\n  padding: 0 4px;\n  color: rgb(150, 159, 175);\n}\n.am-breadcrumbs__link-wrapper {\n  display: inline;\n}\n.am-breadcrumbs__link {\n  display: inline;\n  color: #2c3e50;\n  text-decoration: none;\n  transition: color 0.2s;\n}\n.am-breadcrumbs__link:hover {\n  color: #3eaf7c;\n}\n.am-breadcrumbs__link.am-breadcrumbs__link_current {\n  color: rgb(150, 159, 175);\n}\n.am-breadcrumbs__item:last-child .am-breadcrumbs__separator {\n  display: none;\n}\n"),t.render=function(e,r,n,t,i,u){const b=(0,a.resolveComponent)("router-link");return e.$breadcrumbs&&e.$breadcrumbs.value&&e.$breadcrumbs.value.length&&(n.showCurrentCrumb||!e.$breadcrumbs.value[0].current)?((0,a.openBlock)(),(0,a.createElementBlock)("nav",l,[(0,a.createElementVNode)("ol",c,[((0,a.openBlock)(!0),(0,a.createElementBlock)(a.Fragment,null,(0,a.renderList)(e.$breadcrumbs.value,((r,t)=>((0,a.openBlock)(),(0,a.createElementBlock)(a.Fragment,null,[n.showCurrentCrumb||!r.current?((0,a.openBlock)(),(0,a.createElementBlock)("li",s,[(0,a.renderSlot)(e.$slots,"crumb",{crumb:r},(()=>[(0,a.createElementVNode)("div",m,[r.current?((0,a.openBlock)(),(0,a.createElementBlock)("span",o,(0,a.toDisplayString)(r.label),1)):((0,a.openBlock)(),(0,a.createBlock)(b,{key:1,class:"am-breadcrumbs__link",to:r.link},{default:(0,a.withCtx)((()=>[(0,a.createTextVNode)((0,a.toDisplayString)(r.label),1)])),_:2},1032,["to"]))]),d]))])):(0,a.createCommentVNode)("v-if",!0)],64)))),256))])])):(0,a.createCommentVNode)("v-if",!0)},t.__file="src/AmBreadcrumbs.vue"}}]);
//# sourceMappingURL=f92447cafa0d49e0.js.map