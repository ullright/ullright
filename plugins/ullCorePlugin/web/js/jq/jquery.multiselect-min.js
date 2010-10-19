/*
 * jQuery MultiSelect UI Widget 1.5
 * Copyright (c) 2010 Eric Hynds
 *
 * http://www.erichynds.com/jquery/jquery-ui-multiselect-widget/
 *
 * Depends:
 *   - jQuery 1.4.2
 *   - jQuery UI 1.8 (widget factory and effects if you want to use them)
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
*/
(function(e){var o=0;e.widget("ech.multiselect",{options:{header:true,height:175,minWidth:225,classes:"",checkAllText:"Check all",uncheckAllText:"Uncheck all",noneSelectedText:"Select options",selectedText:"# selected",selectedList:0,show:"",hide:"",autoOpen:false,multiple:true,position:{}},_create:function(){var c=this.element,a=this.options,b=[],d=[],g=c.attr("title"),f=c.id||o++;this.speed=e.fx.speeds._default;this._isOpen=false;b.push('<button type="button" class="ui-multiselect ui-widget ui-state-default ui-corner-all'); a.classes.length&&b.push(" "+a.classes);b.push('"');g.length&&b.push(' title="'+g+'"');b.push('><span class="ui-icon ui-icon-triangle-2-n-s"></span><span>'+a.noneSelectedText+"</span></button>");b.push('<div class="ui-multiselect-menu ui-widget ui-widget-content ui-corner-all '+(a.classes.length?a.classes:"")+'">');b.push('<div class="ui-widget-header ui-corner-all ui-multiselect-header ui-helper-clearfix">');b.push('<ul class="ui-helper-reset">');if(a.header===true&&a.multiple){b.push('<li><a class="ui-multiselect-all" href="#"><span class="ui-icon ui-icon-check"></span><span>'+ a.checkAllText+"</span></a></li>");b.push('<li><a class="ui-multiselect-none" href="#"><span class="ui-icon ui-icon-closethick"></span><span>'+a.uncheckAllText+"</span></a></li>")}else typeof a.header==="string"&&b.push("<li>"+a.header+"</li>");b.push('<li class="ui-multiselect-close"><a href="#" class="ui-multiselect-close"><span class="ui-icon ui-icon-circle-close"></span></a></li>');b.push("</ul>");b.push("</div>");b.push('<ul class="ui-multiselect-checkboxes ui-helper-reset">');c.find("option").each(function(h){var i= e(this),l=i.html(),m=this.value;h=this.id||"ui-multiselect-"+f+"-option-"+h;var j=i.parent(),k=i.is(":disabled"),n=["ui-corner-all"];if(j.is("optgroup")){j=j.attr("label");if(e.inArray(j,d)===-1){b.push('<li class="ui-multiselect-optgroup-label"><a href="#">'+j+"</a></li>");d.push(j)}}if(m.length>0){k&&n.push("ui-state-disabled");b.push('<li class="'+(k?"ui-multiselect-disabled":"")+'">');b.push('<label for="'+h+'" class="'+n.join(" ")+'"><input id="'+h+'" type="'+(a.multiple?"checkbox":"radio")+ '" value="'+m+'" title="'+l+'"');i.is(":selected")&&b.push(' checked="checked"');k&&b.push(' disabled="disabled"');b.push(" />"+l+"</label></li>")}});b.push("</ul></div>");this.button=c.hide().after(b.join("")).next("button");this.menu=this.button.next("div.ui-multiselect-menu");this.labels=this.menu.find("label");this.buttonlabel=this.button.find("span").eq(-1);if(!a.multiple)this.radios=this.menu.find(":radio");this._setButtonWidth();this._setMenuWidth();this._bindEvents();this.button[0].defaultValue= this.update()},_init:function(){this.options.header||this.menu.find("div.ui-multiselect-header").hide();this.options.autoOpen&&this.open();this.element.is(":disabled")&&this.disable()},_bindEvents:function(){function c(){a[a._isOpen?"close":"open"]();return false}var a=this,b=this.button;b.find("span").bind("click.multiselect",c);b.bind({click:c,keypress:function(d){switch(d.keyCode){case 27:case 38:case 37:a.close();break;case 39:case 40:a.open()}},mouseenter:function(){b.hasClass("ui-state-disabled")|| e(this).addClass("ui-state-hover")},mouseleave:function(){e(this).removeClass("ui-state-hover")},focus:function(){b.hasClass("ui-state-disabled")||e(this).addClass("ui-state-focus")},blur:function(){e(this).removeClass("ui-state-focus")}});this.menu.find("div.ui-multiselect-header a").bind("click.multiselect",function(d){e(this).hasClass("ui-multiselect-close")?a.close():a[e(this).hasClass("ui-multiselect-all")?"checkAll":"uncheckAll"]();d.preventDefault()}).end().find("li.ui-multiselect-optgroup-label a").bind("click.multiselect", function(d){var g=e(this),f=g.parent().nextUntil("li.ui-multiselect-optgroup-label").find("input:visible");a._toggleChecked(f.filter(":checked").length!==f.length,f);a._trigger("optgrouptoggle",d,{inputs:f.get(),label:g.parent().text(),checked:f[0].checked});d.preventDefault()}).end().delegate("label","mouseenter",function(){if(!e(this).hasClass("ui-state-disabled")){a.labels.removeClass("ui-state-hover");e(this).addClass("ui-state-hover").find("input").focus()}}).delegate("label","keydown",function(d){switch(d.keyCode){case 9:case 27:a.close(); break;case 38:case 40:case 37:case 39:a._traverse(d.keyCode,this);break;case 13:d.preventDefault();e(this).find("input").trigger("click")}}).delegate(":checkbox, :radio","click",function(d){var g=this.value,f=this.checked;if(e(this).is(":disabled")||a._trigger("click",d,{value:this.value,text:this.title,checked:f})===false)d.preventDefault();else{a.options.multiple||a.radios.not(this).removeAttr("checked");a.element.find("option").filter(function(){return this.value===g}).attr("selected",f?"selected": "");a.update(!d.originalEvent?f?-1:1:0)}});e(document).bind("click.multiselect",function(d){d=e(d.target);a._isOpen&&!d.closest("div.ui-multiselect-menu").length&&!d.is("button.ui-multiselect")&&a.close()})},_setButtonWidth:function(){var c=this.element.outerWidth(),a=this.options;if(/\d/.test(a.minWidth)&&c<a.minWidth)c=a.minWidth;this.button.width(c)},_setMenuWidth:function(){var c=this.menu,a=this.button.outerWidth()-parseInt(c.css("padding-left"),10)-parseInt(c.css("padding-right"),10)-parseInt(c.css("border-right-width"), 10)-parseInt(c.css("border-left-width"),10);c.width(a||this.button.outerWidth())},_traverse:function(c,a){var b=e(a),d=c===38||c===37?true:false;b=b.parent()[d?"prevAll":"nextAll"]("li:not(.ui-multiselect-disabled, .ui-multiselect-optgroup-label)")[d?"last":"first"]();if(b.length)b.find("label").trigger("mouseover");else{b=this.menu.find("ul:last");this.menu.find("label")[d?"last":"first"]().trigger("mouseover");b.scrollTop(d?b.height():0)}},_toggleChecked:function(c,a){var b=a&&a.length?a:this.labels.find("input"); if(!this.options.multiple&&c)b=b.eq(0);b.not(":disabled").attr("checked",c?"checked":"");this.update();this.element.find("option").not(":disabled").attr("selected",c?"selected":"")},_toggleDisabled:function(c){this.button.attr("disabled",c?"disabled":"")[c?"addClass":"removeClass"]("ui-state-disabled");this.menu.find("input").attr("disabled",c?"disabled":"").parent()[c?"addClass":"removeClass"]("ui-state-disabled");this.element.attr("disabled",c?"disabled":"")},update:function(){var c=this.options, a=this.labels.find("input"),b=a.filter(":checked"),d=b.length;c=d===0?c.noneSelectedText:e.isFunction(c.selectedText)?c.selectedText.call(this,d,a.length,b.get()):/\d/.test(c.selectedList)&&c.selectedList>0&&d<=c.selectedList?b.map(function(){return this.title}).get().join(", "):c.selectedText.replace("#",d).replace("#",a.length);this.buttonlabel.html(c);return c},open:function(){var c=this.button,a=this.menu,b=this.speed,d=this.options;if(!(this._trigger("beforeopen")===false||c.hasClass("ui-state-disabled")|| this._isOpen)){e(":ech-multiselect").not(this.element).each(function(){var i=e(this);i.multiselect("isOpen")&&i.multiselect("close")});var g=a.find("ul:last"),f=d.show,h=c.position();if(e.isArray(d.show)){f=d.show[0];b=d.show[1]||this.speed}g.scrollTop(0).height(d.height);if(e.ui.position&&!e.isEmptyObject(d.position)){d.position.of=d.position.of||c;a.show().position(d.position).hide().show(f,b)}else a.css({top:h.top+c.outerHeight(),left:h.left}).show(f,b);this.labels.eq(0).trigger("mouseover").trigger("mouseenter").find("input").trigger("focus"); c.addClass("ui-state-active");this._isOpen=true;this._trigger("open")}},close:function(){if(this._trigger("beforeclose")!==false){var c=this.options,a=c.hide,b=this.speed;if(e.isArray(c.hide)){a=c.hide[0];b=c.hide[1]||this.speed}this.menu.hide(a,b);this.button.removeClass("ui-state-active").trigger("blur").trigger("mouseleave");this._trigger("close");this._isOpen=false}},enable:function(){this._toggleDisabled(false)},disable:function(){this._toggleDisabled(true)},checkAll:function(){this._toggleChecked(true); this._trigger("checkAll")},uncheckAll:function(){this._toggleChecked(false);this._trigger("uncheckAll")},getChecked:function(){return this.menu.find("input").filter(":checked")},destroy:function(){e.Widget.prototype.destroy.call(this);this.button.remove();this.menu.remove();this.element.show();return this},isOpen:function(){return this._isOpen},widget:function(){return this.menu},_setOption:function(c,a){var b=this.menu;switch(c){case "header":b.find("div.ui-multiselect-header")[a?"show":"hide"](); break;case "checkAllText":b.find("a.ui-multiselect-all span").eq(-1).text(a);break;case "uncheckAllText":b.find("a.ui-multiselect-none span").eq(-1).text(a);break;case "height":b.find("ul:last").height(parseInt(a,10));break;case "minWidth":this.options[c]=parseInt(a,10);this._setButtonWidth();this._setMenuWidth();break;case "selectedText":case "selectedList":case "noneSelectedText":this.options[c]=a;this.update();break;case "classes":b.add(this.button).removeClass(this.options.classes).addClass(a)}e.Widget.prototype._setOption.apply(this, arguments)}})})(jQuery);