"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[34],{3034:(e,o,t)=>{t.r(o),t.d(o,{default:()=>i});var r=t(821),a=(0,r.createElementVNode)("h2",null,"Changez votre mot de passe",-1);var n=t(5714),s=t(3907),l=t(7565);const d={layout:"MainLayout",metaInfo:function(){return{title:"Profil"}},data:function(){return{infoForm:new n.ZP({username:"",email:"",first_name:"",last_name:""}),passwordForm:new n.ZP({password:"",current_password:"",password_confirmation:""}),columns:[{label:"Appareil",field:"device"},{label:"Navigateur",field:"browser"},{label:"Version",field:"browser_version"},{label:"Système d'exploitation",field:"platform"},{label:"Version",field:"platform_version"},{label:"Adresse IP",field:"ip_address"},{label:"Dernière connexion",field:"last_activity"}]}},computed:(0,s.Se)({user:"auth/user"}),created:function(){var e=this;this.$store.dispatch("settings/updatePageLoading",!0),this.infoForm.keys().forEach((function(o){e.infoForm[o]=e.user[o]}))},methods:{updateProfile:function(){var e=this;this.infoForm.patch("settings/profile/"+(0,l.EA)().id).then((function(o){o.data.status?e.$swal.toast_success(o.data.message):e.$swal.alert_error(o.data.message)})).catch((function(e){console.log(e)}))},updatePassword:function(){var e=this;this.passwordForm.patch("settings/password/"+(0,l.EA)().id).then((function(o){o.data.status?(e.$swal.toast_success(o.data.message),e.passwordForm.reset()):e.$swal.alert_error(o.data.message)})).catch((function(e){console.log(e)}))}}};const i=(0,t(3744).Z)(d,[["render",function(e,o,t,n,s,l){var d=this,i=(0,r.resolveComponent)("NLColumn"),u=(0,r.resolveComponent)("NLInput"),c=(0,r.resolveComponent)("NLButton"),m=(0,r.resolveComponent)("NLFlex"),f=(0,r.resolveComponent)("NLForm"),p=(0,r.resolveComponent)("NLDatatable"),w=(0,r.resolveComponent)("NLGrid");return(0,r.openBlock)(),(0,r.createBlock)(w,null,{default:(0,r.withCtx)((function(){return[(0,r.createVNode)(i,null,{default:(0,r.withCtx)((function(){return[(0,r.createVNode)(f,{action:l.updatePassword,form:s.passwordForm},{default:(0,r.withCtx)((function(){return[(0,r.createVNode)(i,null,{default:(0,r.withCtx)((function(){return[a]})),_:1}),(0,r.createVNode)(i,{lg:"4",md:"4"},{default:(0,r.withCtx)((function(){return[(0,r.createVNode)(u,{modelValue:s.passwordForm.current_password,"onUpdate:modelValue":o[0]||(o[0]=function(e){return s.passwordForm.current_password=e}),form:s.passwordForm,label:"Mot de passe actuel",name:"current_password",type:"password","label-required":""},null,8,["modelValue","form"])]})),_:1}),(0,r.createVNode)(i,{lg:"4",md:"4"},{default:(0,r.withCtx)((function(){return[(0,r.createVNode)(u,{modelValue:s.passwordForm.password,"onUpdate:modelValue":o[1]||(o[1]=function(e){return s.passwordForm.password=e}),form:s.passwordForm,label:"Mot de passe",name:"password",type:"password","label-required":""},null,8,["modelValue","form"])]})),_:1}),(0,r.createVNode)(i,{lg:"4",md:"4"},{default:(0,r.withCtx)((function(){return[(0,r.createVNode)(u,{modelValue:s.passwordForm.password_confirmation,"onUpdate:modelValue":o[2]||(o[2]=function(e){return s.passwordForm.password_confirmation=e}),form:s.passwordForm,label:"Confirmation mot de passe",name:"password_confirmation",type:"password","label-required":""},null,8,["modelValue","form"])]})),_:1}),(0,r.createVNode)(i,null,{default:(0,r.withCtx)((function(){return[(0,r.createVNode)(m,{lgJustifyContent:"end"},{default:(0,r.withCtx)((function(){return[(0,r.createVNode)(c,{loading:s.infoForm.busy,label:"Mettre à jour",onClick:(0,r.withModifiers)(l.updatePassword,["prevent"])},null,8,["loading","onClick"])]})),_:1})]})),_:1})]})),_:1},8,["action","form"])]})),_:1}),(0,r.createVNode)(i,null,{default:(0,r.withCtx)((function(){return[(0,r.createVNode)(p,{columns:s.columns,title:"Historique de connexion",urlPrefix:"users/logins/history",onDataLoaded:o[3]||(o[3]=function(){return d.$store.dispatch("settings/updatePageLoading",!1)})},null,8,["columns"])]})),_:1})]})),_:1})}]])}}]);
//# sourceMappingURL=91a71de17b004113.js.map