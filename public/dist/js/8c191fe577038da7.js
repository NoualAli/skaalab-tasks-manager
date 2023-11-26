"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[381],{7381:(e,t,r)=>{r.r(t),r.d(t,{default:()=>m});var o=r(821),n=(0,o.createElementVNode)("img",{src:"/app/images/brand.svg",class:"auth-brand"},null,-1),a=(0,o.createElementVNode)("span",{class:"auth-box__title"},[(0,o.createTextVNode)(" 1"),(0,o.createElementVNode)("sup",null,"ère"),(0,o.createTextVNode)(" Connexion "),(0,o.createElementVNode)("br"),(0,o.createTextVNode)(" Nouveau mot de passe ")],-1);var u=r(5714),s=r(3907),l=r(4289);function c(e){return c="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},c(e)}function i(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,o)}return r}function d(e,t,r){return(t=function(e){var t=function(e,t){if("object"!==c(e)||null===e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var o=r.call(e,t||"default");if("object"!==c(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"===c(t)?t:String(t)}(t))in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}const f={layout:"auth",metaInfo:function(){return{title:"Réinitialisation du mot de passe"}},computed:function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?i(Object(r),!0).forEach((function(t){d(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):i(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},(0,s.Se)({user:"auth/user"})),data:function(){return{form:new u.ZP({current_password:null,password:null,password_confirmation:null,mustChangePassword:!0}),showForm:!0}},methods:{renew:function(){var e=this,t=JSON.parse((0,l.Ih)("user"));this.form.patch("settings/password/"+(null==t?void 0:t.id)).then((function(t){t.data.status?(e.showForm=!1,e.$swal.toast_success(t.data.message),e.form.reset(),e.$store.dispatch("auth/fetchUser").then((function(){return e.$router.push({name:"home"})}))):(e.showForm=!0,e.$swal.alert_error(t.data.message))})).catch((function(t){var r;e.$swal.alert_error(null==t||null===(r=t.data)||void 0===r?void 0:r.message),console.log(t),e.showForm=!1}))}}};const m=(0,r(3744).Z)(f,[["render",function(e,t,r,u,s,l){var c=(0,o.resolveComponent)("NLColumn"),i=(0,o.resolveComponent)("NLInput"),d=(0,o.resolveComponent)("NLGrid"),f=(0,o.resolveComponent)("NLButton"),m=(0,o.resolveComponent)("NLFlex");return(0,o.openBlock)(),(0,o.createBlock)(d,{class:"box auth-box",gap:"6"},{default:(0,o.withCtx)((function(){return[(0,o.createVNode)(c,{class:"auth-box__header"},{default:(0,o.withCtx)((function(){return[n,a]})),_:1}),(0,o.createVNode)(c,{class:"form-container container"},{default:(0,o.withCtx)((function(){return[e.showForm?((0,o.openBlock)(),(0,o.createElementBlock)("form",{key:0,method:"POST",onSubmit:t[3]||(t[3]=(0,o.withModifiers)((function(){return l.renew&&l.renew.apply(l,arguments)}),["prevent"])),onKeydown:t[4]||(t[4]=function(t){return e.form.onKeydown(t)})},[(0,o.createVNode)(d,{gap:"2",class:"my-2"},{default:(0,o.withCtx)((function(){return[(0,o.createVNode)(c,null,{default:(0,o.withCtx)((function(){return[(0,o.createVNode)(i,{modelValue:e.form.current_password,"onUpdate:modelValue":t[0]||(t[0]=function(t){return e.form.current_password=t}),name:"current_password",class:"is-for-auth",placeholder:"Mot de passe actuel",form:e.form,type:"password"},null,8,["modelValue","form"])]})),_:1}),(0,o.createVNode)(c,null,{default:(0,o.withCtx)((function(){return[(0,o.createVNode)(i,{modelValue:e.form.password,"onUpdate:modelValue":t[1]||(t[1]=function(t){return e.form.password=t}),name:"password",class:"is-for-auth",placeholder:"Mot de passe",form:e.form,type:"password"},null,8,["modelValue","form"])]})),_:1}),(0,o.createVNode)(c,null,{default:(0,o.withCtx)((function(){return[(0,o.createVNode)(i,{modelValue:e.form.password_confirmation,"onUpdate:modelValue":t[2]||(t[2]=function(t){return e.form.password_confirmation=t}),name:"password_confirmation",class:"is-for-auth",placeholder:"Confirmation mot de passe",form:e.form,type:"password"},null,8,["modelValue","form"])]})),_:1})]})),_:1}),(0,o.createVNode)(m,{lgJustifyContent:"center"},{default:(0,o.withCtx)((function(){return[(0,o.createVNode)(f,{loading:e.form.busy,label:"Continuer",class:"d-block w-100"},null,8,["loading"])]})),_:1})],32)):(0,o.createCommentVNode)("",!0)]})),_:1}),(0,o.createVNode)(c,{class:"text-center d-block d-lg-none"},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" © "+(0,o.toDisplayString)(e.currentYear)+" - Tous droits réservés - NLDEV ",1)]})),_:1})]})),_:1})}]])}}]);
//# sourceMappingURL=8c191fe577038da7.js.map