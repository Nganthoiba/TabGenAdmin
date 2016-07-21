<!DOCTYPE html>
<html>
	<?php 
	
	?>
	<title>More About a Tab</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.css">
	<!--<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">-->
	<!--Toast package-->
	<link href="css/toast.css" rel="stylesheet" media="screen">
	<script type="text/javascript" src="js/toast.js"></script>
	<!-- ********************************************** -->
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<!--<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>-->
	
	<script>
	/* jquery.form.min.js */
	(function(e){"use strict";if(typeof define==="function"&&define.amd){define(["jquery"],e)}else{e(typeof jQuery!="undefined"?jQuery:window.Zepto)}})(function(e){"use strict";function r(t){var n=t.data;if(!t.isDefaultPrevented()){t.preventDefault();e(t.target).ajaxSubmit(n)}}function i(t){var n=t.target;var r=e(n);if(!r.is("[type=submit],[type=image]")){var i=r.closest("[type=submit]");if(i.length===0){return}n=i[0]}var s=this;s.clk=n;if(n.type=="image"){if(t.offsetX!==undefined){s.clk_x=t.offsetX;s.clk_y=t.offsetY}else if(typeof e.fn.offset=="function"){var o=r.offset();s.clk_x=t.pageX-o.left;s.clk_y=t.pageY-o.top}else{s.clk_x=t.pageX-n.offsetLeft;s.clk_y=t.pageY-n.offsetTop}}setTimeout(function(){s.clk=s.clk_x=s.clk_y=null},100)}function s(){if(!e.fn.ajaxSubmit.debug){return}var t="[jquery.form] "+Array.prototype.join.call(arguments,"");if(window.console&&window.console.log){window.console.log(t)}else if(window.opera&&window.opera.postError){window.opera.postError(t)}}var t={};t.fileapi=e("<input type='file'/>").get(0).files!==undefined;t.formdata=window.FormData!==undefined;var n=!!e.fn.prop;e.fn.attr2=function(){if(!n){return this.attr.apply(this,arguments)}var e=this.prop.apply(this,arguments);if(e&&e.jquery||typeof e==="string"){return e}return this.attr.apply(this,arguments)};e.fn.ajaxSubmit=function(r){function k(t){var n=e.param(t,r.traditional).split("&");var i=n.length;var s=[];var o,u;for(o=0;o<i;o++){n[o]=n[o].replace(/\+/g," ");u=n[o].split("=");s.push([decodeURIComponent(u[0]),decodeURIComponent(u[1])])}return s}function L(t){var n=new FormData;for(var s=0;s<t.length;s++){n.append(t[s].name,t[s].value)}if(r.extraData){var o=k(r.extraData);for(s=0;s<o.length;s++){if(o[s]){n.append(o[s][0],o[s][1])}}}r.data=null;var u=e.extend(true,{},e.ajaxSettings,r,{contentType:false,processData:false,cache:false,type:i||"POST"});if(r.uploadProgress){u.xhr=function(){var t=e.ajaxSettings.xhr();if(t.upload){t.upload.addEventListener("progress",function(e){var t=0;var n=e.loaded||e.position;var i=e.total;if(e.lengthComputable){t=Math.ceil(n/i*100)}r.uploadProgress(e,n,i,t)},false)}return t}}u.data=null;var a=u.beforeSend;u.beforeSend=function(e,t){if(r.formData){t.data=r.formData}else{t.data=n}if(a){a.call(this,e,t)}};return e.ajax(u)}function A(t){function T(e){var t=null;try{if(e.contentWindow){t=e.contentWindow.document}}catch(n){s("cannot get iframe.contentWindow document: "+n)}if(t){return t}try{t=e.contentDocument?e.contentDocument:e.document}catch(n){s("cannot get iframe.contentDocument: "+n);t=e.document}return t}function k(){function f(){try{var e=T(v).readyState;s("state = "+e);if(e&&e.toLowerCase()=="uninitialized"){setTimeout(f,50)}}catch(t){s("Server abort: ",t," (",t.name,")");_(x);if(w){clearTimeout(w)}w=undefined}}var t=a.attr2("target"),n=a.attr2("action"),r="multipart/form-data",u=a.attr("enctype")||a.attr("encoding")||r;o.setAttribute("target",p);if(!i||/post/i.test(i)){o.setAttribute("method","POST")}if(n!=l.url){o.setAttribute("action",l.url)}if(!l.skipEncodingOverride&&(!i||/post/i.test(i))){a.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"})}if(l.timeout){w=setTimeout(function(){b=true;_(S)},l.timeout)}var c=[];try{if(l.extraData){for(var h in l.extraData){if(l.extraData.hasOwnProperty(h)){if(e.isPlainObject(l.extraData[h])&&l.extraData[h].hasOwnProperty("name")&&l.extraData[h].hasOwnProperty("value")){c.push(e('<input type="hidden" name="'+l.extraData[h].name+'">').val(l.extraData[h].value).appendTo(o)[0])}else{c.push(e('<input type="hidden" name="'+h+'">').val(l.extraData[h]).appendTo(o)[0])}}}}if(!l.iframeTarget){d.appendTo("body")}if(v.attachEvent){v.attachEvent("onload",_)}else{v.addEventListener("load",_,false)}setTimeout(f,15);try{o.submit()}catch(m){var g=document.createElement("form").submit;g.apply(o)}}finally{o.setAttribute("action",n);o.setAttribute("enctype",u);if(t){o.setAttribute("target",t)}else{a.removeAttr("target")}e(c).remove()}}function _(t){if(m.aborted||M){return}A=T(v);if(!A){s("cannot access response document");t=x}if(t===S&&m){m.abort("timeout");E.reject(m,"timeout");return}else if(t==x&&m){m.abort("server abort");E.reject(m,"error","server abort");return}if(!A||A.location.href==l.iframeSrc){if(!b){return}}if(v.detachEvent){v.detachEvent("onload",_)}else{v.removeEventListener("load",_,false)}var n="success",r;try{if(b){throw"timeout"}var i=l.dataType=="xml"||A.XMLDocument||e.isXMLDoc(A);s("isXml="+i);if(!i&&window.opera&&(A.body===null||!A.body.innerHTML)){if(--O){s("requeing onLoad callback, DOM not available");setTimeout(_,250);return}}var o=A.body?A.body:A.documentElement;m.responseText=o?o.innerHTML:null;m.responseXML=A.XMLDocument?A.XMLDocument:A;if(i){l.dataType="xml"}m.getResponseHeader=function(e){var t={"content-type":l.dataType};return t[e.toLowerCase()]};if(o){m.status=Number(o.getAttribute("status"))||m.status;m.statusText=o.getAttribute("statusText")||m.statusText}var u=(l.dataType||"").toLowerCase();var a=/(json|script|text)/.test(u);if(a||l.textarea){var f=A.getElementsByTagName("textarea")[0];if(f){m.responseText=f.value;m.status=Number(f.getAttribute("status"))||m.status;m.statusText=f.getAttribute("statusText")||m.statusText}else if(a){var c=A.getElementsByTagName("pre")[0];var p=A.getElementsByTagName("body")[0];if(c){m.responseText=c.textContent?c.textContent:c.innerText}else if(p){m.responseText=p.textContent?p.textContent:p.innerText}}}else if(u=="xml"&&!m.responseXML&&m.responseText){m.responseXML=D(m.responseText)}try{L=H(m,u,l)}catch(g){n="parsererror";m.error=r=g||n}}catch(g){s("error caught: ",g);n="error";m.error=r=g||n}if(m.aborted){s("upload aborted");n=null}if(m.status){n=m.status>=200&&m.status<300||m.status===304?"success":"error"}if(n==="success"){if(l.success){l.success.call(l.context,L,"success",m)}E.resolve(m.responseText,"success",m);if(h){e.event.trigger("ajaxSuccess",[m,l])}}else if(n){if(r===undefined){r=m.statusText}if(l.error){l.error.call(l.context,m,n,r)}E.reject(m,"error",r);if(h){e.event.trigger("ajaxError",[m,l,r])}}if(h){e.event.trigger("ajaxComplete",[m,l])}if(h&&!--e.active){e.event.trigger("ajaxStop")}if(l.complete){l.complete.call(l.context,m,n)}M=true;if(l.timeout){clearTimeout(w)}setTimeout(function(){if(!l.iframeTarget){d.remove()}else{d.attr("src",l.iframeSrc)}m.responseXML=null},100)}var o=a[0],u,f,l,h,p,d,v,m,g,y,b,w;var E=e.Deferred();E.abort=function(e){m.abort(e)};if(t){for(f=0;f<c.length;f++){u=e(c[f]);if(n){u.prop("disabled",false)}else{u.removeAttr("disabled")}}}l=e.extend(true,{},e.ajaxSettings,r);l.context=l.context||l;p="jqFormIO"+(new Date).getTime();if(l.iframeTarget){d=e(l.iframeTarget);y=d.attr2("name");if(!y){d.attr2("name",p)}else{p=y}}else{d=e('<iframe name="'+p+'" src="'+l.iframeSrc+'" />');d.css({position:"absolute",top:"-1000px",left:"-1000px"})}v=d[0];m={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(t){var n=t==="timeout"?"timeout":"aborted";s("aborting upload... "+n);this.aborted=1;try{if(v.contentWindow.document.execCommand){v.contentWindow.document.execCommand("Stop")}}catch(r){}d.attr("src",l.iframeSrc);m.error=n;if(l.error){l.error.call(l.context,m,n,t)}if(h){e.event.trigger("ajaxError",[m,l,n])}if(l.complete){l.complete.call(l.context,m,n)}}};h=l.global;if(h&&0===e.active++){e.event.trigger("ajaxStart")}if(h){e.event.trigger("ajaxSend",[m,l])}if(l.beforeSend&&l.beforeSend.call(l.context,m,l)===false){if(l.global){e.active--}E.reject();return E}if(m.aborted){E.reject();return E}g=o.clk;if(g){y=g.name;if(y&&!g.disabled){l.extraData=l.extraData||{};l.extraData[y]=g.value;if(g.type=="image"){l.extraData[y+".x"]=o.clk_x;l.extraData[y+".y"]=o.clk_y}}}var S=1;var x=2;var N=e("meta[name=csrf-token]").attr("content");var C=e("meta[name=csrf-param]").attr("content");if(C&&N){l.extraData=l.extraData||{};l.extraData[C]=N}if(l.forceSync){k()}else{setTimeout(k,10)}var L,A,O=50,M;var D=e.parseXML||function(e,t){if(window.ActiveXObject){t=new ActiveXObject("Microsoft.XMLDOM");t.async="false";t.loadXML(e)}else{t=(new DOMParser).parseFromString(e,"text/xml")}return t&&t.documentElement&&t.documentElement.nodeName!="parsererror"?t:null};var P=e.parseJSON||function(e){return window["eval"]("("+e+")")};var H=function(t,n,r){var i=t.getResponseHeader("content-type")||"",s=n==="xml"||!n&&i.indexOf("xml")>=0,o=s?t.responseXML:t.responseText;if(s&&o.documentElement.nodeName==="parsererror"){if(e.error){e.error("parsererror")}}if(r&&r.dataFilter){o=r.dataFilter(o,n)}if(typeof o==="string"){if(n==="json"||!n&&i.indexOf("json")>=0){o=P(o)}else if(n==="script"||!n&&i.indexOf("javascript")>=0){e.globalEval(o)}}return o};return E}if(!this.length){s("ajaxSubmit: skipping submit process - no element selected");return this}var i,o,u,a=this;if(typeof r=="function"){r={success:r}}else if(r===undefined){r={}}i=r.type||this.attr2("method");o=r.url||this.attr2("action");u=typeof o==="string"?e.trim(o):"";u=u||window.location.href||"";if(u){u=(u.match(/^([^#]+)/)||[])[1]}r=e.extend(true,{url:u,success:e.ajaxSettings.success,type:i||e.ajaxSettings.type,iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},r);var f={};this.trigger("form-pre-serialize",[this,r,f]);if(f.veto){s("ajaxSubmit: submit vetoed via form-pre-serialize trigger");return this}if(r.beforeSerialize&&r.beforeSerialize(this,r)===false){s("ajaxSubmit: submit aborted via beforeSerialize callback");return this}var l=r.traditional;if(l===undefined){l=e.ajaxSettings.traditional}var c=[];var h,p=this.formToArray(r.semantic,c);if(r.data){r.extraData=r.data;h=e.param(r.data,l)}if(r.beforeSubmit&&r.beforeSubmit(p,this,r)===false){s("ajaxSubmit: submit aborted via beforeSubmit callback");return this}this.trigger("form-submit-validate",[p,this,r,f]);if(f.veto){s("ajaxSubmit: submit vetoed via form-submit-validate trigger");return this}var d=e.param(p,l);if(h){d=d?d+"&"+h:h}if(r.type.toUpperCase()=="GET"){r.url+=(r.url.indexOf("?")>=0?"&":"?")+d;r.data=null}else{r.data=d}var v=[];if(r.resetForm){v.push(function(){a.resetForm()})}if(r.clearForm){v.push(function(){a.clearForm(r.includeHidden)})}if(!r.dataType&&r.target){var m=r.success||function(){};v.push(function(t){var n=r.replaceTarget?"replaceWith":"html";e(r.target)[n](t).each(m,arguments)})}else if(r.success){v.push(r.success)}r.success=function(e,t,n){var i=r.context||this;for(var s=0,o=v.length;s<o;s++){v[s].apply(i,[e,t,n||a,a])}};if(r.error){var g=r.error;r.error=function(e,t,n){var i=r.context||this;g.apply(i,[e,t,n,a])}}if(r.complete){var y=r.complete;r.complete=function(e,t){var n=r.context||this;y.apply(n,[e,t,a])}}var b=e("input[type=file]:enabled",this).filter(function(){return e(this).val()!==""});var w=b.length>0;var E="multipart/form-data";var S=a.attr("enctype")==E||a.attr("encoding")==E;var x=t.fileapi&&t.formdata;s("fileAPI :"+x);var T=(w||S)&&!x;var N;if(r.iframe!==false&&(r.iframe||T)){if(r.closeKeepAlive){e.get(r.closeKeepAlive,function(){N=A(p)})}else{N=A(p)}}else if((w||S)&&x){N=L(p)}else{N=e.ajax(r)}a.removeData("jqxhr").data("jqxhr",N);for(var C=0;C<c.length;C++){c[C]=null}this.trigger("form-submit-notify",[this,r]);return this};e.fn.ajaxForm=function(t){t=t||{};t.delegation=t.delegation&&e.isFunction(e.fn.on);if(!t.delegation&&this.length===0){var n={s:this.selector,c:this.context};if(!e.isReady&&n.s){s("DOM not ready, queuing ajaxForm");e(function(){e(n.s,n.c).ajaxForm(t)});return this}s("terminating; zero elements found by selector"+(e.isReady?"":" (DOM not ready)"));return this}if(t.delegation){e(document).off("submit.form-plugin",this.selector,r).off("click.form-plugin",this.selector,i).on("submit.form-plugin",this.selector,t,r).on("click.form-plugin",this.selector,t,i);return this}return this.ajaxFormUnbind().bind("submit.form-plugin",t,r).bind("click.form-plugin",t,i)};e.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")};e.fn.formToArray=function(n,r){var i=[];if(this.length===0){return i}var s=this[0];var o=this.attr("id");var u=n?s.getElementsByTagName("*"):s.elements;var a;if(u&&!/MSIE [678]/.test(navigator.userAgent)){u=e(u).get()}if(o){a=e(':input[form="'+o+'"]').get();if(a.length){u=(u||[]).concat(a)}}if(!u||!u.length){return i}var f,l,c,h,p,d,v;for(f=0,d=u.length;f<d;f++){p=u[f];c=p.name;if(!c||p.disabled){continue}if(n&&s.clk&&p.type=="image"){if(s.clk==p){i.push({name:c,value:e(p).val(),type:p.type});i.push({name:c+".x",value:s.clk_x},{name:c+".y",value:s.clk_y})}continue}h=e.fieldValue(p,true);if(h&&h.constructor==Array){if(r){r.push(p)}for(l=0,v=h.length;l<v;l++){i.push({name:c,value:h[l]})}}else if(t.fileapi&&p.type=="file"){if(r){r.push(p)}var m=p.files;if(m.length){for(l=0;l<m.length;l++){i.push({name:c,value:m[l],type:p.type})}}else{i.push({name:c,value:"",type:p.type})}}else if(h!==null&&typeof h!="undefined"){if(r){r.push(p)}i.push({name:c,value:h,type:p.type,required:p.required})}}if(!n&&s.clk){var g=e(s.clk),y=g[0];c=y.name;if(c&&!y.disabled&&y.type=="image"){i.push({name:c,value:g.val()});i.push({name:c+".x",value:s.clk_x},{name:c+".y",value:s.clk_y})}}return i};e.fn.formSerialize=function(t){return e.param(this.formToArray(t))};e.fn.fieldSerialize=function(t){var n=[];this.each(function(){var r=this.name;if(!r){return}var i=e.fieldValue(this,t);if(i&&i.constructor==Array){for(var s=0,o=i.length;s<o;s++){n.push({name:r,value:i[s]})}}else if(i!==null&&typeof i!="undefined"){n.push({name:this.name,value:i})}});return e.param(n)};e.fn.fieldValue=function(t){for(var n=[],r=0,i=this.length;r<i;r++){var s=this[r];var o=e.fieldValue(s,t);if(o===null||typeof o=="undefined"||o.constructor==Array&&!o.length){continue}if(o.constructor==Array){e.merge(n,o)}else{n.push(o)}}return n};e.fieldValue=function(t,n){var r=t.name,i=t.type,s=t.tagName.toLowerCase();if(n===undefined){n=true}if(n&&(!r||t.disabled||i=="reset"||i=="button"||(i=="checkbox"||i=="radio")&&!t.checked||(i=="submit"||i=="image")&&t.form&&t.form.clk!=t||s=="select"&&t.selectedIndex==-1)){return null}if(s=="select"){var o=t.selectedIndex;if(o<0){return null}var u=[],a=t.options;var f=i=="select-one";var l=f?o+1:a.length;for(var c=f?o:0;c<l;c++){var h=a[c];if(h.selected){var p=h.value;if(!p){p=h.attributes&&h.attributes.value&&!h.attributes.value.specified?h.text:h.value}if(f){return p}u.push(p)}}return u}return e(t).val()};e.fn.clearForm=function(t){return this.each(function(){e("input,select,textarea",this).clearFields(t)})};e.fn.clearFields=e.fn.clearInputs=function(t){var n=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var r=this.type,i=this.tagName.toLowerCase();if(n.test(r)||i=="textarea"){this.value=""}else if(r=="checkbox"||r=="radio"){this.checked=false}else if(i=="select"){this.selectedIndex=-1}else if(r=="file"){if(/MSIE/.test(navigator.userAgent)){e(this).replaceWith(e(this).clone(true))}else{e(this).val("")}}else if(t){if(t===true&&/hidden/.test(r)||typeof t=="string"&&e(this).is(t)){this.value=""}}})};e.fn.resetForm=function(){return this.each(function(){if(typeof this.reset=="function"||typeof this.reset=="object"&&!this.reset.nodeType){this.reset()}})};e.fn.enable=function(e){if(e===undefined){e=true}return this.each(function(){this.disabled=!e})};e.fn.selected=function(t){if(t===undefined){t=true}return this.each(function(){var n=this.type;if(n=="checkbox"||n=="radio"){this.checked=t}else if(this.tagName.toLowerCase()=="option"){var r=e(this).parent("select");if(t&&r[0]&&r[0].type=="select-one"){r.find("option").selected(false)}this.selected=t}})};e.fn.ajaxSubmit.debug=false})
	</script>
	
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/simple-sidebar.css">
	<link rel="stylesheet" type="text/css" href="css/my_custom_style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"></meta>
	
	<!-- This is for sweet alert -->
	<script src="dist/sweetalert-dev.js"></script>
	<link rel="stylesheet" href="dist/sweetalert.css">
	<!--.......................-->
	<script type="text/javascript">
	$(document).ready(function(){
		$("#menu-toggle").click(function(e) {
			e.preventDefault();
			$("#wrapper").toggleClass("toggled");
		});
	});
	function toggle(){
		$("#wrapper").toggleClass("toggled");
	}
	
	</script>
	<!-- text editing features -->
	<script src="tinymce/js/tinymce/tinymce.min.js"></script>
	<script src="tinymce/js/tinymce/jquery.tinymce.min.js"></script>
	<!--<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>-->
	<script>
		
		tinymce.init({ 
			selector:'textarea',
			height: 200,
			toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		});
		/*tinymce.init({
		  selector: 'textarea',
		  height: 200,
		  plugins: [
			'advlist autolink lists link image charmap print preview anchor',
			'searchreplace visualblocks code fullscreen',
			'insertdatetime media table contextmenu paste code'
		  ],
		  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		  
		});*/
		/*
		  content_css: [
			'//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
			'//www.tinymce.com/css/codepen.min.css'
		  ]
		 */
	</script>
	
	<!--
	<script type="text/javascript" src="nicEdit/nicEdit.js"></script>
	<script type="text/javascript">
		bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
	</script>
	-->
	
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/npm.js"></script>
	<script src="homepage.js"></script>
	<script type="text/JavaScript">
		
		function getSession(){
			document.getElementById("user_detail_section").innerHTML=user_session.username;
			setInterval(
				function(){
						//alert("Hello"); 
						$.ajax({
						url: "getUserSession.php",
						type: "GET",
						success:function(data){
							if(data.trim()=="null"){
								//user_session=null;
								//window.location.assign("index.html");
							}
							else{
								//user_session=JSON.parse(data);
							}
						},
						error:function(error_data,y,z){
							//user_session=null;
							//alert(error_data+" "+y+" "+z);
						}
					});
				}, 30000);	
		}
	</script>
	<body onload='getSession();'>
		<nav class="navbar navbar-inverse navbar-fixed-top">
		  <div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" id="menu-toggle"
				data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  
				<a class="navbar-brand" onclick="toggle();" id="menu-toggle">
					<span class="glyphicon glyphicon-align-justify"></span>
				</a>
			</div>
			
			<script type="text/JavaScript">
					var output;//contains list of articles
					var files_path=[];
					
					var queryString = new Array();
					if (window.location.search.split('?').length > 1) {
						var params = window.location.search.split('?')[1].split('&');
						for (var i = 0; i < params.length; i++) {
							var key = params[i].split('=')[0];
							var value = decodeURIComponent(params[i].split('=')[1]);
							queryString[key] = value;
							//alert(key);
						}
					}	
					var tab_id=queryString['tab_id'];
					getNewsArticles(tab_id);
					
					function getFiles(i){
						var file_list=files_path[i];
						var files_layout="";
										
						var j=0;
						for(var j=0;j<file_list.length;j++){
							var files=file_list[j].file_name;
							files_layout+=(files==null || files=="")?"":"<div class='col-sm-4'><a href='"+files+
												"' target='_blank'>"+
												"<img src='"+file_list[j].file_icon+"' height='50px' width='50px' alt='No Icon'/><br/>"+
												extractFileName(files)+"</a></div>";
						}
						files_layout=j>0?"<div><h5>Attached files:</h5></div>"+files_layout:"";
						return files_layout;
					}
					
					function displayArticleImage(i,img_src){
						var layout="";
						if(img_src=="" || img_src==null){
							layout="<div id='image_layout"+i+"'>"+
								"<center><div id='image_upload_layout"+i+"'>This article contains no picture,<br/>"+
									" <button class='btn' onclick='upload_news_image(\""+i+"\",\""+img_src+"\");'>Put an image</button></div></center></div>";
						}
						else{
							layout="<div id='image_layout"+i+"'><center><img class='img-thumbnail' src='"+img_src+"' alt='No Image' "+
										"height='350px' width='80%' class=''/><br/>"+
									"<div id='image_upload_layout"+i+"'>"+
									"<div class='btn-group'>"+
									"<button class='btn'"+
									" onclick='upload_news_image(\""+i+"\",\""+img_src+"\");'>Replace the image</button>"+
									"</div></div></center></div>";
						}
						return layout;
					}
					
					function upload_news_image(i,img_src){
						var article_id = document.getElementById("article_id"+i).value;
						var image_upload_layout="<center>"+
							"<div class='select_file_bg'>"+
							"<form id='uploadForm"+i+"' action='upload.php' method='post'>"+
								"<div id='targetLayer"+i+"'></div>"+
								"<button type='button' class='close' "+
									"onclick='closeImageUpload(\""+i+"\",\""+img_src+"\");'>&times;</button>"+
									"<label>Upload an image:</label>"+
									"<table><tr>"+
										"<td>"+
											"<input name='news_image' id='news_image"+i+"' type='file' class='demoInputBox' />"+
											"<input name='article_id' type='hidden' value='"+article_id+"'/>"+
										"</td>"+
										"<td>"+
											"<input type='submit' id='SubmitPhoto"+i+"' value='Upload' class='btnSubmit'/>"+
										"</td>"+
									"</tr></table>"+	
								"<div class='progress-div' style='display:none;' id='image_progress_div"+i+"'>"+
									"<div class='progress-bar' id='image_progress_bar"+i+"'></div>"+
								"</div>"+
							"</form>"+
							"<center><div id='image_loader_icon"+i+"' style='display:none;'><img src='img/loading.gif'/></div></center>"+
							"</div></center>";
						document.getElementById("image_upload_layout"+i).innerHTML=image_upload_layout;
						$("#uploadForm"+i).submit(function(e) {	
							var img_path = $("#news_image"+i).val();
							var Extension = img_path.substring(img_path.lastIndexOf('.') + 1).toLowerCase();
							var path_length = $('#news_image'+i).val().trim().length;
							
							if(img_path==null || path_length==0){
								$("#image_upload_layout"+i).html("<center><div style='width:55.55%' class='alert alert-danger'>"+
									"Please select an image."+
									"<button type='button' class='close' "+
									"onclick='upload_news_image(\""+i+"\",\""+img_src+"\");'>&times;</button>"+
									"</div></center>");
								return false;
							}
							else if(!(Extension == "gif" || Extension == "png" || Extension == "bmp" || Extension == "jpeg" || Extension == "jpg")){
								$("#image_upload_layout"+i).html("<center><div style='width:55.55%' class='alert alert-danger'>"+
									"Not a valid image file.."+
									"<button type='button' class='close' "+
									"onclick='upload_news_image(\""+i+"\",\""+img_src+"\");'>&times;</button>"+
									"</div></center>");
								return false;
							}
							else{
								e.preventDefault();
								$("#image_loader_icon"+i).show();
								$("#image_progress_div"+i).show();
								$(this).ajaxSubmit({
									url: "upload.php", 
									beforeSubmit: function() {
									  $("#progress-bar"+i).width('0%');
									},
									uploadProgress: function (event, position, total, percentComplete){	
										$("#image_progress_bar"+i).width(percentComplete + '%');
										$("#image_progress_bar"+i).html('<div id="image_progress_status'+i+'">' + percentComplete +' %</div>');
									},
									success:function (resp){
										$("#image_loader_icon"+i).hide();
										var json_resp = JSON.parse(resp);
										//alert(json_resp.image_path);
										if(json_resp.status==true){
											closeImageUpload(i,json_resp.image_path);
										}
										else{
											$("#image_upload_layout"+i).html("<center><div class='alert alert-danger'>"+
											json_resp.message+
											"<button type='button' class='close' "+
											"onclick='upload_news_image(\""+i+"\",\""+img_src+"\");'>&times;</button>"+
											"</div></center>");
										}
									},
									error: function(){
										$("#image_upload_layout"+i).html("<center><div style='width:55.55%' class='alert alert-danger'>"+
										"Failed to upload image, please check your connection or unable to reach server."+
										"<button type='button' class='close' "+
										"onclick='upload_news_image(\""+i+"\",\""+img_src+"\");'>&times;</button>"+
										"</div></center>");
									},
									resetForm: true 
								}); 
								return false; 
							}
						});
					}
					function closeImageUpload(i,img_src){
						document.getElementById("image_content"+i).innerHTML=displayArticleImage(i,img_src);
					}
					
					//This function is to display an edit layout of the news details description
					function edit_content(i){
						var edit_layout="<textarea class='form-control' id='news_details_id"+i+"'>"+output[i].Details+"</textarea>"+
						"<div class='pull-right'>"+
						"<button onclick='cancel_edit_content(\""+i+"\");' class='btn btn-default'>Cancel</button>&nbsp;"+
						"<button onclick='save_edit_content(\""+i+"\");' class='btn btn-default'>Save</button>"+
						"</div>";
						document.getElementById("textual_content_layout"+i).innerHTML=edit_layout;
						/*"#news_details_id"+i*/
						tinymce.init({ 
							selector:'textarea',
							height: 200,
							toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
						});
						//var news_details = tinyMCE.get('news_details_id'+i).getContent();
					}
					
					//js function to cancel edit and hide the edit layout
					function cancel_edit_content(i){
						var layout="<div id='textual_content"+i+"'>"+output[i].Details+"</div>"+
													"<div class='pull-right'>"+
														"<button class='btn btn-link' "+
														"onclick='edit_content(\""+i+"\");'"+
														"id='edit_content"+i+"'>"+
														"Edit Content</button>"+
													"</div>";
						$("#textual_content_layout"+i).html(layout);
					}
					
					//js function to save the new edited contents of news details
					function save_edit_content(i){
						var news_details = tinyMCE.get('news_details_id'+i).getContent();
						var article_id = document.getElementById("article_id"+i).value;
						$.ajax({
							url: "update_article.php",
							type:"POST",
							data:{"article_id":article_id,"news_details":news_details},
							success: function(resp){
								//alert(resp);
								var json_resp = JSON.parse(resp);
								if(json_resp.status==true){
									swal("Update Successful!", json_resp.message, "success");
									var layout="<div id='textual_content"+i+"'>"+news_details+"</div>"+
													"<div class='pull-right'>"+
														"<button class='btn btn-link' "+
														"onclick='edit_content(\""+i+"\");'"+
														"id='edit_content"+i+"'>"+
														"Edit Content</button>"+
													"</div>";
									$("#textual_content_layout"+i).html(layout);
									output[i].Details=news_details;
								}
								else{
									swal("Update Failed!", json_resp.message, "error");
								}
							},
							error: function(){
								swal("Update Failed!", "Unable to reach server. Please check your connection or try again later.", "error");
							}
						});
					}
					function getNewsArticles(tab_id){
						//alert(tab_id);
						$.ajax({
							url: "getNewsArticle.php?tab_id="+tab_id,
							type: "GET",
							success: function(resp){
								//alert(resp);
								var result = JSON.parse(resp);
								if(result.state==true){
									output = result.output;
									if(output==null){
										document.getElementById("tab_contents").innerHTML="<center>No news article found, create a new one.</center>";
										return false;
									}
									var article_layout="";
									
									for(var i=0;i<output.length;i++){
										files_path[i]=output[i].Attachments;
										/*"onclick='edit_content(\""+i+"\",\""+output[i].Details+"\");' "+*/
										article_layout+=""+
											"<div class='news_article'>"+
												"<div class='headLine' id='article_title"+i+"'>"+
													"<h2>"+output[i].title+"</h2>"+
													"<input type='hidden' id='article_id"+i+"' value='"+output[i].Id+"'/>"+	
												"</div>"+
												"<div class='heading' id='article_title"+i+"'>"+
													output[i].headline+	
												"</div><br/>"+
												"<div id='image_content"+i+"'>"+displayArticleImage(i,output[i].Image)+"</div>"+
												"<div id='textual_content_layout"+i+"' style='padding:10px'>"+
													"<div id='textual_content"+i+"'>"+output[i].Details+"</div>"+
													"<div class='pull-right'>"+
														"<button class='btn btn-link' "+
														"onclick='edit_content(\""+i+"\");'"+
														"id='edit_content"+i+"'>"+
														"Edit Content</button>"+
													"</div>"+
												"</div>"+
												"<div style='width:98%;overflow:hidden;overflow-x:auto;padding-left:10px' "+
														"id='files_content"+i+"'>"+getFiles(i)+"</div>"+
												"<br/><hr/>"+
												"<div id='file_attachment_layout"+i+"'></div>"+
												"<button class='btn btn-success' style='padding:5px;"+
													"float:right' onclick='attachFile(\""+i+"\");'>"+
													"<span class='glyphicon glyphicon-paperclip'></span>&nbsp;Attach a file"+
												"</button>"+	
											"</div>";
										document.getElementById("tab_contents").innerHTML=article_layout;
									}
								}
								else{
									document.getElementById("tab_contents").innerHTML="<center>"+result.message+"</center>";
								}
							}
						});
					}
					
					/*js for uploading any file*/
					function attachFile(i){
						var article_id = document.getElementById("article_id"+i).value;
						var file_upload_layout="<center>"+
							"<div class='select_file_bg'>"+
								"<form id='uploadFileForm"+i+"' action='upload.php' method='post'>"+
									"<div id='FileAttachtargetLayer"+i+"'></div>"+
									"<button type='button' class='close' "+
										"onclick='closeFileUpload(\""+i+"\");'>&times;</button>"+
										"<center>"+
										"<table><tr>"+
											"<td><label>Attach a file:</label></td>"+
											"<td>"+
												"<input name='userFile' id='userFile"+i+"' type='file' class='demoInputBox' />"+
												"<input name='article_id' type='hidden' value='"+article_id+"'/>"+
											"</td>"+
											"<td>"+
												"<input type='submit' id='SubmitFile"+i+"' value='Upload' class='btnSubmit'/>"+
											"</td>"+
										"</tr></table>"+
										"</center>"+	
									"<div class='progress-div' style='display:none;' id='file_progress-div"+i+"'>"+
										"<div class='progress-bar' id='file_progress-bar"+i+"'></div>"+
									"</div>"+
								"</form>"+
								"<center><div id='file_loader-icon"+i+"' style='display:none;'>"+
									"<img src='img/loading.gif' /></div>"+
								"</center>"+
							"</div></center>";
						document.getElementById("file_attachment_layout"+i).innerHTML=file_upload_layout;
						//document.getElementById("files_content"+i).innerHTML=file_upload_layout;
						$("#uploadFileForm"+i).submit(function(e) {	
							var path = $("#userFile"+i).val();
							//var Extension = img_path.substring(img_path.lastIndexOf('.') + 1).toLowerCase();
							var path_length = $('#userFile'+i).val().trim().length;
							
							if(path==null || path_length==0){
								$("#file_attachment_layout"+i).html("<center><br/><div class='select_file_bg'>"+
									"No file has been selected. Please select one."+
									"<button type='button' class='close' "+
									"onclick='attachFile(\""+i+"\");'>&times;</button>"+
									"</div></center>");
								return false;
							}
							else{
								e.preventDefault();
								$("#file_loader-icon"+i).show();
								$("#file_progress-div"+i).show();
								$(this).ajaxSubmit({ 
									beforeSubmit: function() {
									  $("#file_progress-bar"+i).width('0%');
									},
									uploadProgress: function (event, position, total, percentComplete){	
										$("#file_progress-bar"+i).width(percentComplete + '%');
										$("#file_progress-bar"+i).html('<div id="file_progress-status'+i+'">' + percentComplete +' %</div>');
									},
									success:function (resp){
										$("#file_loader-icon"+i).hide();
										var json_resp = JSON.parse(resp);
										if(json_resp.status==true){
											files_path[i]=json_resp.files_storage_path;
											refreshFileLayout(i);
										}
										else{
											$("#file_attachment_layout"+i).html("<center><br/><div class=''>"+
												json_resp.message+
												"<button type='button' class='close' "+
													"onclick='attachFile(\""+i+"\");'>&times;</button>"+
													"</div></center>");
										}
									},
									resetForm: true 
								}); 
								return false; 
							}
						});
					}
					function closeFileUpload(i){
						document.getElementById("file_attachment_layout"+i).innerHTML="";
					}
					
					function refreshFileLayout(i){						
						document.getElementById("files_content"+i).innerHTML=getFiles(i);
						document.getElementById("file_attachment_layout"+i).innerHTML="";
					}
					/*js for extracting file name from a given file path with file name*/
					function extractFileName(path){
						var index=0;
						var filename="";
						for(var i=0;i<path.length;i++){
							if(path[i]=='/'){
								index=i;
							}
						}
						var i=index==0?0:index+1;
						for(;i<path.length;i++){
							filename=filename+path[i];
						}
						return filename;
					}
			</script>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			  <ul class="nav navbar-nav">  
				<li class="active">
					<a href="#">
					<?php 
						include('connect_db.php');
						include('tabgen_php_functions.php');
						$tab_id = $_GET['tab_id'];
						if($conn){
							$tab_details = getTabWithTemplate($conn,$tab_id);
							echo $tab_details['Name'];
						}
						else{
							echo "Failed to connect Database";
						}
						
					?> <span class="sr-only">(current)</span></a>
				</li>	
			  </ul>
			</div>
		  </div>
		</nav>
	<div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
			<center>
				<li class="active"><a href="#"><span class="sr-only">(current)</span></a></li>
			</center>
			
            <ul class="sidebar-nav">
				<br/>
				<li>
					<a href="home.php" ><!--onclick="window.history.back();"-->
						Back to home
					</a>
				</li>
				<li>
					<a href="#" data-toggle="modal" data-target="#post_a_news"
					 onclick="reset_news_form();">
						Add a news
					</a>
				</li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
			<div class="container-fluid">
            <div class="" id="tab_contents">	
				
			</div>
        </div>
        <!-- /#page-content-wrapper -->
        <script>
		/* jquery.form.min.js */
		(function(e){
			"use strict";
			if(typeof define==="function"&&define.amd)
			{
				define(["jquery"],e)
			}
			else{
				e(typeof jQuery!="undefined"?jQuery:window.Zepto)
			}
		})(function(e){"use strict";function r(t){var n=t.data;if(!t.isDefaultPrevented()){t.preventDefault();e(t.target).ajaxSubmit(n)}}function i(t){var n=t.target;var r=e(n);if(!r.is("[type=submit],[type=image]")){var i=r.closest("[type=submit]");if(i.length===0){return}n=i[0]}var s=this;s.clk=n;if(n.type=="image"){if(t.offsetX!==undefined){s.clk_x=t.offsetX;s.clk_y=t.offsetY}else if(typeof e.fn.offset=="function"){var o=r.offset();s.clk_x=t.pageX-o.left;s.clk_y=t.pageY-o.top}else{s.clk_x=t.pageX-n.offsetLeft;s.clk_y=t.pageY-n.offsetTop}}setTimeout(function(){s.clk=s.clk_x=s.clk_y=null},100)}function s(){if(!e.fn.ajaxSubmit.debug){return}var t="[jquery.form] "+Array.prototype.join.call(arguments,"");if(window.console&&window.console.log){window.console.log(t)}else if(window.opera&&window.opera.postError){window.opera.postError(t)}}var t={};t.fileapi=e("<input type='file'/>").get(0).files!==undefined;t.formdata=window.FormData!==undefined;var n=!!e.fn.prop;e.fn.attr2=function(){if(!n){return this.attr.apply(this,arguments)}var e=this.prop.apply(this,arguments);if(e&&e.jquery||typeof e==="string"){return e}return this.attr.apply(this,arguments)};e.fn.ajaxSubmit=function(r){function k(t){var n=e.param(t,r.traditional).split("&");var i=n.length;var s=[];var o,u;for(o=0;o<i;o++){n[o]=n[o].replace(/\+/g," ");u=n[o].split("=");s.push([decodeURIComponent(u[0]),decodeURIComponent(u[1])])}return s}function L(t){var n=new FormData;for(var s=0;s<t.length;s++){n.append(t[s].name,t[s].value)}if(r.extraData){var o=k(r.extraData);for(s=0;s<o.length;s++){if(o[s]){n.append(o[s][0],o[s][1])}}}r.data=null;var u=e.extend(true,{},e.ajaxSettings,r,{contentType:false,processData:false,cache:false,type:i||"POST"});if(r.uploadProgress){u.xhr=function(){var t=e.ajaxSettings.xhr();if(t.upload){t.upload.addEventListener("progress",function(e){var t=0;var n=e.loaded||e.position;var i=e.total;if(e.lengthComputable){t=Math.ceil(n/i*100)}r.uploadProgress(e,n,i,t)},false)}return t}}u.data=null;var a=u.beforeSend;u.beforeSend=function(e,t){if(r.formData){t.data=r.formData}else{t.data=n}if(a){a.call(this,e,t)}};return e.ajax(u)}function A(t){function T(e){var t=null;try{if(e.contentWindow){t=e.contentWindow.document}}catch(n){s("cannot get iframe.contentWindow document: "+n)}if(t){return t}try{t=e.contentDocument?e.contentDocument:e.document}catch(n){s("cannot get iframe.contentDocument: "+n);t=e.document}return t}function k(){function f(){try{var e=T(v).readyState;s("state = "+e);if(e&&e.toLowerCase()=="uninitialized"){setTimeout(f,50)}}catch(t){s("Server abort: ",t," (",t.name,")");_(x);if(w){clearTimeout(w)}w=undefined}}var t=a.attr2("target"),n=a.attr2("action"),r="multipart/form-data",u=a.attr("enctype")||a.attr("encoding")||r;o.setAttribute("target",p);if(!i||/post/i.test(i)){o.setAttribute("method","POST")}if(n!=l.url){o.setAttribute("action",l.url)}if(!l.skipEncodingOverride&&(!i||/post/i.test(i))){a.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"})}if(l.timeout){w=setTimeout(function(){b=true;_(S)},l.timeout)}var c=[];try{if(l.extraData){for(var h in l.extraData){if(l.extraData.hasOwnProperty(h)){if(e.isPlainObject(l.extraData[h])&&l.extraData[h].hasOwnProperty("name")&&l.extraData[h].hasOwnProperty("value")){c.push(e('<input type="hidden" name="'+l.extraData[h].name+'">').val(l.extraData[h].value).appendTo(o)[0])}else{c.push(e('<input type="hidden" name="'+h+'">').val(l.extraData[h]).appendTo(o)[0])}}}}if(!l.iframeTarget){d.appendTo("body")}if(v.attachEvent){v.attachEvent("onload",_)}else{v.addEventListener("load",_,false)}setTimeout(f,15);try{o.submit()}catch(m){var g=document.createElement("form").submit;g.apply(o)}}finally{o.setAttribute("action",n);o.setAttribute("enctype",u);if(t){o.setAttribute("target",t)}else{a.removeAttr("target")}e(c).remove()}}function _(t){if(m.aborted||M){return}A=T(v);if(!A){s("cannot access response document");t=x}if(t===S&&m){m.abort("timeout");E.reject(m,"timeout");return}else if(t==x&&m){m.abort("server abort");E.reject(m,"error","server abort");return}if(!A||A.location.href==l.iframeSrc){if(!b){return}}if(v.detachEvent){v.detachEvent("onload",_)}else{v.removeEventListener("load",_,false)}var n="success",r;try{if(b){throw"timeout"}var i=l.dataType=="xml"||A.XMLDocument||e.isXMLDoc(A);s("isXml="+i);if(!i&&window.opera&&(A.body===null||!A.body.innerHTML)){if(--O){s("requeing onLoad callback, DOM not available");setTimeout(_,250);return}}var o=A.body?A.body:A.documentElement;m.responseText=o?o.innerHTML:null;m.responseXML=A.XMLDocument?A.XMLDocument:A;if(i){l.dataType="xml"}m.getResponseHeader=function(e){var t={"content-type":l.dataType};return t[e.toLowerCase()]};if(o){m.status=Number(o.getAttribute("status"))||m.status;m.statusText=o.getAttribute("statusText")||m.statusText}var u=(l.dataType||"").toLowerCase();var a=/(json|script|text)/.test(u);if(a||l.textarea){var f=A.getElementsByTagName("textarea")[0];if(f){m.responseText=f.value;m.status=Number(f.getAttribute("status"))||m.status;m.statusText=f.getAttribute("statusText")||m.statusText}else if(a){var c=A.getElementsByTagName("pre")[0];var p=A.getElementsByTagName("body")[0];if(c){m.responseText=c.textContent?c.textContent:c.innerText}else if(p){m.responseText=p.textContent?p.textContent:p.innerText}}}else if(u=="xml"&&!m.responseXML&&m.responseText){m.responseXML=D(m.responseText)}try{L=H(m,u,l)}catch(g){n="parsererror";m.error=r=g||n}}catch(g){s("error caught: ",g);n="error";m.error=r=g||n}if(m.aborted){s("upload aborted");n=null}if(m.status){n=m.status>=200&&m.status<300||m.status===304?"success":"error"}if(n==="success"){if(l.success){l.success.call(l.context,L,"success",m)}E.resolve(m.responseText,"success",m);if(h){e.event.trigger("ajaxSuccess",[m,l])}}else if(n){if(r===undefined){r=m.statusText}if(l.error){l.error.call(l.context,m,n,r)}E.reject(m,"error",r);if(h){e.event.trigger("ajaxError",[m,l,r])}}if(h){e.event.trigger("ajaxComplete",[m,l])}if(h&&!--e.active){e.event.trigger("ajaxStop")}if(l.complete){l.complete.call(l.context,m,n)}M=true;if(l.timeout){clearTimeout(w)}setTimeout(function(){if(!l.iframeTarget){d.remove()}else{d.attr("src",l.iframeSrc)}m.responseXML=null},100)}var o=a[0],u,f,l,h,p,d,v,m,g,y,b,w;var E=e.Deferred();E.abort=function(e){m.abort(e)};if(t){for(f=0;f<c.length;f++){u=e(c[f]);if(n){u.prop("disabled",false)}else{u.removeAttr("disabled")}}}l=e.extend(true,{},e.ajaxSettings,r);l.context=l.context||l;p="jqFormIO"+(new Date).getTime();if(l.iframeTarget){d=e(l.iframeTarget);y=d.attr2("name");if(!y){d.attr2("name",p)}else{p=y}}else{d=e('<iframe name="'+p+'" src="'+l.iframeSrc+'" />');d.css({position:"absolute",top:"-1000px",left:"-1000px"})}v=d[0];m={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(t){var n=t==="timeout"?"timeout":"aborted";s("aborting upload... "+n);this.aborted=1;try{if(v.contentWindow.document.execCommand){v.contentWindow.document.execCommand("Stop")}}catch(r){}d.attr("src",l.iframeSrc);m.error=n;if(l.error){l.error.call(l.context,m,n,t)}if(h){e.event.trigger("ajaxError",[m,l,n])}if(l.complete){l.complete.call(l.context,m,n)}}};h=l.global;if(h&&0===e.active++){e.event.trigger("ajaxStart")}if(h){e.event.trigger("ajaxSend",[m,l])}if(l.beforeSend&&l.beforeSend.call(l.context,m,l)===false){if(l.global){e.active--}E.reject();return E}if(m.aborted){E.reject();return E}g=o.clk;if(g){y=g.name;if(y&&!g.disabled){l.extraData=l.extraData||{};l.extraData[y]=g.value;if(g.type=="image"){l.extraData[y+".x"]=o.clk_x;l.extraData[y+".y"]=o.clk_y}}}var S=1;var x=2;var N=e("meta[name=csrf-token]").attr("content");var C=e("meta[name=csrf-param]").attr("content");if(C&&N){l.extraData=l.extraData||{};l.extraData[C]=N}if(l.forceSync){k()}else{setTimeout(k,10)}var L,A,O=50,M;var D=e.parseXML||function(e,t){if(window.ActiveXObject){t=new ActiveXObject("Microsoft.XMLDOM");t.async="false";t.loadXML(e)}else{t=(new DOMParser).parseFromString(e,"text/xml")}return t&&t.documentElement&&t.documentElement.nodeName!="parsererror"?t:null};var P=e.parseJSON||function(e){return window["eval"]("("+e+")")};var H=function(t,n,r){var i=t.getResponseHeader("content-type")||"",s=n==="xml"||!n&&i.indexOf("xml")>=0,o=s?t.responseXML:t.responseText;if(s&&o.documentElement.nodeName==="parsererror"){if(e.error){e.error("parsererror")}}if(r&&r.dataFilter){o=r.dataFilter(o,n)}if(typeof o==="string"){if(n==="json"||!n&&i.indexOf("json")>=0){o=P(o)}else if(n==="script"||!n&&i.indexOf("javascript")>=0){e.globalEval(o)}}return o};return E}if(!this.length){s("ajaxSubmit: skipping submit process - no element selected");return this}var i,o,u,a=this;if(typeof r=="function"){r={success:r}}else if(r===undefined){r={}}i=r.type||this.attr2("method");o=r.url||this.attr2("action");u=typeof o==="string"?e.trim(o):"";u=u||window.location.href||"";if(u){u=(u.match(/^([^#]+)/)||[])[1]}r=e.extend(true,{url:u,success:e.ajaxSettings.success,type:i||e.ajaxSettings.type,iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},r);var f={};this.trigger("form-pre-serialize",[this,r,f]);if(f.veto){s("ajaxSubmit: submit vetoed via form-pre-serialize trigger");return this}if(r.beforeSerialize&&r.beforeSerialize(this,r)===false){s("ajaxSubmit: submit aborted via beforeSerialize callback");return this}var l=r.traditional;if(l===undefined){l=e.ajaxSettings.traditional}var c=[];var h,p=this.formToArray(r.semantic,c);if(r.data){r.extraData=r.data;h=e.param(r.data,l)}if(r.beforeSubmit&&r.beforeSubmit(p,this,r)===false){s("ajaxSubmit: submit aborted via beforeSubmit callback");return this}this.trigger("form-submit-validate",[p,this,r,f]);if(f.veto){s("ajaxSubmit: submit vetoed via form-submit-validate trigger");return this}var d=e.param(p,l);if(h){d=d?d+"&"+h:h}if(r.type.toUpperCase()=="GET"){r.url+=(r.url.indexOf("?")>=0?"&":"?")+d;r.data=null}else{r.data=d}var v=[];if(r.resetForm){v.push(function(){a.resetForm()})}if(r.clearForm){v.push(function(){a.clearForm(r.includeHidden)})}if(!r.dataType&&r.target){var m=r.success||function(){};v.push(function(t){var n=r.replaceTarget?"replaceWith":"html";e(r.target)[n](t).each(m,arguments)})}else if(r.success){v.push(r.success)}r.success=function(e,t,n){var i=r.context||this;for(var s=0,o=v.length;s<o;s++){v[s].apply(i,[e,t,n||a,a])}};if(r.error){var g=r.error;r.error=function(e,t,n){var i=r.context||this;g.apply(i,[e,t,n,a])}}if(r.complete){var y=r.complete;r.complete=function(e,t){var n=r.context||this;y.apply(n,[e,t,a])}}var b=e("input[type=file]:enabled",this).filter(function(){return e(this).val()!==""});var w=b.length>0;var E="multipart/form-data";var S=a.attr("enctype")==E||a.attr("encoding")==E;var x=t.fileapi&&t.formdata;s("fileAPI :"+x);var T=(w||S)&&!x;var N;if(r.iframe!==false&&(r.iframe||T)){if(r.closeKeepAlive){e.get(r.closeKeepAlive,function(){N=A(p)})}else{N=A(p)}}else if((w||S)&&x){N=L(p)}else{N=e.ajax(r)}a.removeData("jqxhr").data("jqxhr",N);for(var C=0;C<c.length;C++){c[C]=null}this.trigger("form-submit-notify",[this,r]);return this};e.fn.ajaxForm=function(t){t=t||{};t.delegation=t.delegation&&e.isFunction(e.fn.on);if(!t.delegation&&this.length===0){var n={s:this.selector,c:this.context};if(!e.isReady&&n.s){s("DOM not ready, queuing ajaxForm");e(function(){e(n.s,n.c).ajaxForm(t)});return this}s("terminating; zero elements found by selector"+(e.isReady?"":" (DOM not ready)"));return this}if(t.delegation){e(document).off("submit.form-plugin",this.selector,r).off("click.form-plugin",this.selector,i).on("submit.form-plugin",this.selector,t,r).on("click.form-plugin",this.selector,t,i);return this}return this.ajaxFormUnbind().bind("submit.form-plugin",t,r).bind("click.form-plugin",t,i)};e.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")};e.fn.formToArray=function(n,r){var i=[];if(this.length===0){return i}var s=this[0];var o=this.attr("id");var u=n?s.getElementsByTagName("*"):s.elements;var a;if(u&&!/MSIE [678]/.test(navigator.userAgent)){u=e(u).get()}if(o){a=e(':input[form="'+o+'"]').get();if(a.length){u=(u||[]).concat(a)}}if(!u||!u.length){return i}var f,l,c,h,p,d,v;for(f=0,d=u.length;f<d;f++){p=u[f];c=p.name;if(!c||p.disabled){continue}if(n&&s.clk&&p.type=="image"){if(s.clk==p){i.push({name:c,value:e(p).val(),type:p.type});i.push({name:c+".x",value:s.clk_x},{name:c+".y",value:s.clk_y})}continue}h=e.fieldValue(p,true);if(h&&h.constructor==Array){if(r){r.push(p)}for(l=0,v=h.length;l<v;l++){i.push({name:c,value:h[l]})}}else if(t.fileapi&&p.type=="file"){if(r){r.push(p)}var m=p.files;if(m.length){for(l=0;l<m.length;l++){i.push({name:c,value:m[l],type:p.type})}}else{i.push({name:c,value:"",type:p.type})}}else if(h!==null&&typeof h!="undefined"){if(r){r.push(p)}i.push({name:c,value:h,type:p.type,required:p.required})}}if(!n&&s.clk){var g=e(s.clk),y=g[0];c=y.name;if(c&&!y.disabled&&y.type=="image"){i.push({name:c,value:g.val()});i.push({name:c+".x",value:s.clk_x},{name:c+".y",value:s.clk_y})}}return i};e.fn.formSerialize=function(t){return e.param(this.formToArray(t))};e.fn.fieldSerialize=function(t){var n=[];this.each(function(){var r=this.name;if(!r){return}var i=e.fieldValue(this,t);if(i&&i.constructor==Array){for(var s=0,o=i.length;s<o;s++){n.push({name:r,value:i[s]})}}else if(i!==null&&typeof i!="undefined"){n.push({name:this.name,value:i})}});return e.param(n)};e.fn.fieldValue=function(t){for(var n=[],r=0,i=this.length;r<i;r++){var s=this[r];var o=e.fieldValue(s,t);if(o===null||typeof o=="undefined"||o.constructor==Array&&!o.length){continue}if(o.constructor==Array){e.merge(n,o)}else{n.push(o)}}return n};e.fieldValue=function(t,n){var r=t.name,i=t.type,s=t.tagName.toLowerCase();if(n===undefined){n=true}if(n&&(!r||t.disabled||i=="reset"||i=="button"||(i=="checkbox"||i=="radio")&&!t.checked||(i=="submit"||i=="image")&&t.form&&t.form.clk!=t||s=="select"&&t.selectedIndex==-1)){return null}if(s=="select"){var o=t.selectedIndex;if(o<0){return null}var u=[],a=t.options;var f=i=="select-one";var l=f?o+1:a.length;for(var c=f?o:0;c<l;c++){var h=a[c];if(h.selected){var p=h.value;if(!p){p=h.attributes&&h.attributes.value&&!h.attributes.value.specified?h.text:h.value}if(f){return p}u.push(p)}}return u}return e(t).val()};e.fn.clearForm=function(t){return this.each(function(){e("input,select,textarea",this).clearFields(t)})};e.fn.clearFields=e.fn.clearInputs=function(t){var n=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var r=this.type,i=this.tagName.toLowerCase();if(n.test(r)||i=="textarea"){this.value=""}else if(r=="checkbox"||r=="radio"){this.checked=false}else if(i=="select"){this.selectedIndex=-1}else if(r=="file"){if(/MSIE/.test(navigator.userAgent)){e(this).replaceWith(e(this).clone(true))}else{e(this).val("")}}else if(t){if(t===true&&/hidden/.test(r)||typeof t=="string"&&e(this).is(t)){this.value=""}}})};e.fn.resetForm=function(){return this.each(function(){if(typeof this.reset=="function"||typeof this.reset=="object"&&!this.reset.nodeType){this.reset()}})};e.fn.enable=function(e){if(e===undefined){e=true}return this.each(function(){this.disabled=!e})};e.fn.selected=function(t){if(t===undefined){t=true}return this.each(function(){var n=this.type;if(n=="checkbox"||n=="radio"){this.checked=t}else if(this.tagName.toLowerCase()=="option"){var r=e(this).parent("select");if(t&&r[0]&&r[0].type=="select-one"){r.find("option").selected(false)}this.selected=t}})};e.fn.ajaxSubmit.debug=false})
		</script>			
    </div>
    
	<!-- Modal for Creating News (a simple design)-->
	<div class="modal fade" id="post_a_news" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
					<h4 class="modal-title" id="myModalLabel">Publish a news</h4>
				</div>
				<div class="modal-body">	
					<div class="panel panel-default clearfix">
						<form class="form-horizontal" id="publish_a_news">
							<div class="panel-body">							
								<div class="form-group">
									<label class="col-sm-2  control-label" for="news_title">News Title:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="news_title" 
											maxlength="40"
											required="true" placeholder="Write news title here"/>
										<span id="title_validate"></span>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-2  control-label" for="news_headline">News Headline:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="news_headline" required="true"
										maxlength="100"
										placeholder="Write news headline"/>
										<span id="headline_validate"></span>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-2  control-label" for="news_details">News Details:</label>
									<div class="col-sm-10">
										<div id="sample">
											<textarea class="form-control" name="news_details" id="news_details" 
											placeholder="Write news detail here..."
											style="resize:vertical;overflow:auto;"
											required="true"
											rows="5" cols="20"></textarea>
										</div>
										<span id="details_validate"></span>
									</div>
								</div>
							</div>
								<div class="panel-footer clearfix">
									<div class="pull-right">
										<Button type="button" class="btn btn-info" id="publishNews"
											onclick="publish(); return false;">Publish</Button>
										<script type="text/JavaScript">
											function reset_news_form(){
												document.getElementById('publish_a_news').reset();
												$('#publishNewsResponse').html("");
												$('#title_validate').html("");
												$('#headline_validate').html("");
												$('#details_validate').html("");
											}
											
											function publish(){
												var news_title = document.getElementById("news_title").value;
												var news_headline = document.getElementById("news_headline").value;
												var news_details = tinyMCE.get('news_details').getContent();
												//alert(news_details);
												if(validateNews()){
													$("#publishNewsResponse").css('color', 'black');
													$('#publishNewsResponse').html("<center><strong>Wait Please...</strong></center>");
													
													$.ajax({
														url: "createNews.php",
														type: "POST",
														data:{"news_title":news_title,
															"news_headline":news_headline,
															"news_details":news_details,
															"tab_id":tab_id},
														success: function(resp){
															var j_resp=JSON.parse(resp);
															$('#publishNewsResponse').html("<center><strong>"+
															j_resp.message+"</strong></center>");
															if(j_resp.status==true){
																$("#publishNewsResponse").css('color', 'green');
																getNewsArticles(tab_id);
															}
															else{
																$("#publishNewsResponse").css('color', 'red');
															}
														},
														error: function(){
															$('#publishNewsResponse').html("<center><strong>Unable to get response from server, try again later.</strong></center>");
														}
													});
												}
												
											}
											
											function validateNews(){
												var news_title = document.getElementById("news_title").value;
												var news_headline = document.getElementById("news_headline").value;
												var news_details = tinyMCE.get('news_details').getContent();
												var flag=true;
												if(isEmpty(news_title.trim())){
													$('#title_validate').html("<strong>Put title of the news.</strong>");
													$("#title_validate").css('color', 'red');
													flag=false;
												}
												else{
													$('#title_validate').html("");
												}
												
												if(isEmpty(news_headline.trim())){
													$('#headline_validate').html("<strong>Put headline of the news.</strong>");
													$("#headline_validate").css('color', 'red');
													flag=false;
												}
												else{
													$('#headline_validate').html("");
												}
												
												if(isEmpty(news_details.trim())){
													$('#details_validate').html("<strong>Put details of the news.</strong>");
													$("#details_validate").css('color', 'red');
													flag=false;
												}
												else{
													$('#details_validate').html("");
												}
												return flag;
											}
											function isEmpty(str){
												if(str.length==0)
													return true;
												else
													return false;
											}
											
										</script>
									</div>
									<span id="publishNewsResponse"></span>
								</div>
									
						</form>
						<div>
							<!--
							<div class="pull-left">
								<form id="uploadForm" action="createNews.php" method="post">
									<div class="fileUpload btn">
										<span class="glyphicon glyphicon-paperclip"></span>
										<input name="attachFile" id="attachFile" type="file" class="upload"/>
									</div>
									<input type="submit" value="Upload" onclick="submitFile();return false;"/>
								</form>
							</div>
							<br/>
							<div class="progress-div" id="progress-div">
								<div id="progress-bar"></div>
							</div>
							<div id="targetLayer"></div>
							-->
							
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>
		
	</body>
	
</html>

