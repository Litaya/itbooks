!function(e){"use strict";var t=function(t,s){this.options=s,this.$element=e(t),this.$container=e("<div/>",{"class":"ms-container"}),this.$selectableContainer=e("<div/>",{"class":"ms-selectable"}),this.$selectionContainer=e("<div/>",{"class":"ms-selection"}),this.$selectableUl=e("<ul/>",{"class":"ms-list",tabindex:"-1"}),this.$selectionUl=e("<ul/>",{"class":"ms-list",tabindex:"-1"}),this.scrollTo=0,this.elemsSelector="li:visible:not(.ms-optgroup-label,.ms-optgroup-container,."+s.disabledClass+")"};t.prototype={constructor:t,init:function(){var t=this,s=this.$element;if(0===s.next(".ms-container").length){s.css({position:"absolute",left:"-9999px"}),s.attr("id",s.attr("id")?s.attr("id"):Math.ceil(1e3*Math.random())+"multiselect"),this.$container.attr("id","ms-"+s.attr("id")),this.$container.addClass(t.options.cssClass),s.find("option").each(function(){t.generateLisFromOption(this)}),this.$selectionUl.find(".ms-optgroup-label").hide(),t.options.selectableHeader&&t.$selectableContainer.append(t.options.selectableHeader),t.$selectableContainer.append(t.$selectableUl),t.options.selectableFooter&&t.$selectableContainer.append(t.options.selectableFooter),t.options.selectionHeader&&t.$selectionContainer.append(t.options.selectionHeader),t.$selectionContainer.append(t.$selectionUl),t.options.selectionFooter&&t.$selectionContainer.append(t.options.selectionFooter),t.$container.append(t.$selectableContainer),t.$container.append(t.$selectionContainer),s.after(t.$container),t.activeMouse(t.$selectableUl),t.activeKeyboard(t.$selectableUl);var i=t.options.dblClick?"dblclick":"click";t.$selectableUl.on(i,".ms-elem-selectable",function(){t.select(e(this).data("ms-value"))}),t.$selectionUl.on(i,".ms-elem-selection",function(){t.deselect(e(this).data("ms-value"))}),t.activeMouse(t.$selectionUl),t.activeKeyboard(t.$selectionUl),s.on("focus",function(){t.$selectableUl.focus()})}var l=s.find("option:selected").map(function(){return e(this).val()}).get();t.select(l,"init"),"function"==typeof t.options.afterInit&&t.options.afterInit.call(this,this.$container)},generateLisFromOption:function(t,s,i){for(var l=this,n=l.$element,o="",a=e(t),r=0;r<t.attributes.length;r++){var c=t.attributes[r];"value"!==c.name&&"disabled"!==c.name&&(o+=c.name+'="'+c.value+'" ')}var h=e("<li "+o+"><span>"+l.escapeHTML(a.text())+"</span></li>"),d=h.clone(),u=a.val(),p=l.sanitize(u);h.data("ms-value",u).addClass("ms-elem-selectable").attr("id",p+"-selectable"),d.data("ms-value",u).addClass("ms-elem-selection").attr("id",p+"-selection").hide(),(a.prop("disabled")||n.prop("disabled"))&&(d.addClass(l.options.disabledClass),h.addClass(l.options.disabledClass));var f=a.parent("optgroup");if(f.length>0){var m=f.attr("label"),v=l.sanitize(m),g=l.$selectableUl.find("#optgroup-selectable-"+v),b=l.$selectionUl.find("#optgroup-selection-"+v);if(0===g.length){var $='<li class="ms-optgroup-container"></li>',C='<ul class="ms-optgroup"><li class="ms-optgroup-label"><span>'+m+"</span></li></ul>";g=e($),b=e($),g.attr("id","optgroup-selectable-"+v),b.attr("id","optgroup-selection-"+v),g.append(e(C)),b.append(e(C)),l.options.selectableOptgroup&&(g.find(".ms-optgroup-label").on("click",function(){var t=f.children(":not(:selected, :disabled)").map(function(){return e(this).val()}).get();l.select(t)}),b.find(".ms-optgroup-label").on("click",function(){var t=f.children(":selected:not(:disabled)").map(function(){return e(this).val()}).get();l.deselect(t)})),l.$selectableUl.append(g),l.$selectionUl.append(b)}s=void 0===s?g.find("ul").children().length:s+1,h.insertAt(s,g.children()),d.insertAt(s,b.children())}else s=void 0===s?l.$selectableUl.children().length:s,h.insertAt(s,l.$selectableUl),d.insertAt(s,l.$selectionUl)},addOption:function(t){var s=this;void 0!==t.value&&null!==t.value&&(t=[t]),e.each(t,function(t,i){if(void 0!==i.value&&null!==i.value&&0===s.$element.find("option[value='"+i.value+"']").length){var l=e('<option value="'+i.value+'">'+i.text+"</option>"),n=void 0===i.nested?s.$element:e("optgroup[label='"+i.nested+"']"),t=parseInt("undefined"==typeof i.index?n.children().length:i.index);i.optionClass&&l.addClass(i.optionClass),i.disabled&&l.prop("disabled",!0),l.insertAt(t,n),s.generateLisFromOption(l.get(0),t,i.nested)}})},escapeHTML:function(t){return e("<div>").text(t).html()},activeKeyboard:function(t){var s=this;t.on("focus",function(){e(this).addClass("ms-focus")}).on("blur",function(){e(this).removeClass("ms-focus")}).on("keydown",function(i){switch(i.which){case 40:case 38:return i.preventDefault(),i.stopPropagation(),void s.moveHighlight(e(this),38===i.which?-1:1);case 37:case 39:return i.preventDefault(),i.stopPropagation(),void s.switchList(t);case 9:if(s.$element.is("[tabindex]")){i.preventDefault();var l=parseInt(s.$element.attr("tabindex"),10);return l=i.shiftKey?l-1:l+1,void e('[tabindex="'+l+'"]').focus()}i.shiftKey&&s.$element.trigger("focus")}if(e.inArray(i.which,s.options.keySelect)>-1)return i.preventDefault(),i.stopPropagation(),void s.selectHighlighted(t)})},moveHighlight:function(e,t){var s=e.find(this.elemsSelector),i=s.filter(".ms-hover"),l=null,n=s.first().outerHeight(),o=e.height();"#"+this.$container.prop("id");if(s.removeClass("ms-hover"),1===t){if(l=i.nextAll(this.elemsSelector).first(),0===l.length){var a=i.parent();if(a.hasClass("ms-optgroup")){var r=a.parent(),c=r.next(":visible");l=c.length>0?c.find(this.elemsSelector).first():s.first()}else l=s.first()}}else if(t===-1&&(l=i.prevAll(this.elemsSelector).first(),0===l.length)){var a=i.parent();if(a.hasClass("ms-optgroup")){var r=a.parent(),h=r.prev(":visible");l=h.length>0?h.find(this.elemsSelector).last():s.last()}else l=s.last()}if(l.length>0){l.addClass("ms-hover");var d=e.scrollTop()+l.position().top-o/2+n/2;e.scrollTop(d)}},selectHighlighted:function(e){var t=e.find(this.elemsSelector),s=t.filter(".ms-hover").first();s.length>0&&(e.parent().hasClass("ms-selectable")?this.select(s.data("ms-value")):this.deselect(s.data("ms-value")),t.removeClass("ms-hover"))},switchList:function(e){e.blur(),this.$container.find(this.elemsSelector).removeClass("ms-hover"),e.parent().hasClass("ms-selectable")?this.$selectionUl.focus():this.$selectableUl.focus()},activeMouse:function(t){var s=this;this.$container.on("mouseenter",s.elemsSelector,function(){e(this).parents(".ms-container").find(s.elemsSelector).removeClass("ms-hover"),e(this).addClass("ms-hover")}),this.$container.on("mouseleave",s.elemsSelector,function(){e(this).parents(".ms-container").find(s.elemsSelector).removeClass("ms-hover")})},refresh:function(){this.destroy(),this.$element.multiSelect(this.options)},destroy:function(){e("#ms-"+this.$element.attr("id")).remove(),this.$element.off("focus"),this.$element.css("position","").css("left",""),this.$element.removeData("multiselect")},select:function(t,s){"string"==typeof t&&(t=[t]);var i=this,l=this.$element,n=e.map(t,function(e){return i.sanitize(e)}),o=this.$selectableUl.find("#"+n.join("-selectable, #")+"-selectable").filter(":not(."+i.options.disabledClass+")"),a=this.$selectionUl.find("#"+n.join("-selection, #")+"-selection").filter(":not(."+i.options.disabledClass+")"),r=l.find("option:not(:disabled)").filter(function(){return e.inArray(this.value,t)>-1});if("init"===s&&(o=this.$selectableUl.find("#"+n.join("-selectable, #")+"-selectable"),a=this.$selectionUl.find("#"+n.join("-selection, #")+"-selection")),o.length>0){o.addClass("ms-selected").hide(),a.addClass("ms-selected").show(),r.prop("selected",!0),i.$container.find(i.elemsSelector).removeClass("ms-hover");var c=i.$selectableUl.children(".ms-optgroup-container");if(c.length>0){c.each(function(){var t=e(this).find(".ms-elem-selectable");t.length===t.filter(".ms-selected").length&&e(this).find(".ms-optgroup-label").hide()});var h=i.$selectionUl.children(".ms-optgroup-container");h.each(function(){var t=e(this).find(".ms-elem-selection");t.filter(".ms-selected").length>0&&e(this).find(".ms-optgroup-label").show()})}else if(i.options.keepOrder&&"init"!==s){var d=i.$selectionUl.find(".ms-selected");d.length>1&&d.last().get(0)!=a.get(0)&&a.insertAfter(d.last())}"init"!==s&&(l.trigger("change"),"function"==typeof i.options.afterSelect&&i.options.afterSelect.call(this,t))}},deselect:function(t){"string"==typeof t&&(t=[t]);var s=this,i=this.$element,l=e.map(t,function(e){return s.sanitize(e)}),n=this.$selectableUl.find("#"+l.join("-selectable, #")+"-selectable"),o=this.$selectionUl.find("#"+l.join("-selection, #")+"-selection").filter(".ms-selected").filter(":not(."+s.options.disabledClass+")"),a=i.find("option").filter(function(){return e.inArray(this.value,t)>-1});if(o.length>0){n.removeClass("ms-selected").show(),o.removeClass("ms-selected").hide(),a.prop("selected",!1),s.$container.find(s.elemsSelector).removeClass("ms-hover");var r=s.$selectableUl.children(".ms-optgroup-container");if(r.length>0){r.each(function(){var t=e(this).find(".ms-elem-selectable");t.filter(":not(.ms-selected)").length>0&&e(this).find(".ms-optgroup-label").show()});var c=s.$selectionUl.children(".ms-optgroup-container");c.each(function(){var t=e(this).find(".ms-elem-selection");0===t.filter(".ms-selected").length&&e(this).find(".ms-optgroup-label").hide()})}i.trigger("change"),"function"==typeof s.options.afterDeselect&&s.options.afterDeselect.call(this,t)}},select_all:function(){var t=this.$element,s=t.val();if(t.find('option:not(":disabled")').prop("selected",!0),this.$selectableUl.find(".ms-elem-selectable").filter(":not(."+this.options.disabledClass+")").addClass("ms-selected").hide(),this.$selectionUl.find(".ms-optgroup-label").show(),this.$selectableUl.find(".ms-optgroup-label").hide(),this.$selectionUl.find(".ms-elem-selection").filter(":not(."+this.options.disabledClass+")").addClass("ms-selected").show(),this.$selectionUl.focus(),t.trigger("change"),"function"==typeof this.options.afterSelect){var i=e.grep(t.val(),function(t){return e.inArray(t,s)<0});this.options.afterSelect.call(this,i)}},deselect_all:function(){var e=this.$element,t=e.val();e.find("option").prop("selected",!1),this.$selectableUl.find(".ms-elem-selectable").removeClass("ms-selected").show(),this.$selectionUl.find(".ms-optgroup-label").hide(),this.$selectableUl.find(".ms-optgroup-label").show(),this.$selectionUl.find(".ms-elem-selection").removeClass("ms-selected").hide(),this.$selectableUl.focus(),e.trigger("change"),"function"==typeof this.options.afterDeselect&&this.options.afterDeselect.call(this,t)},sanitize:function(e){var t,s,i=0;if(0==e.length)return i;var l=0;for(t=0,l=e.length;t<l;t++)s=e.charCodeAt(t),i=(i<<5)-i+s,i|=0;return i}},e.fn.multiSelect=function(){var s=arguments[0],i=arguments;return this.each(function(){var l=e(this),n=l.data("multiselect"),o=e.extend({},e.fn.multiSelect.defaults,l.data(),"object"==typeof s&&s);n||l.data("multiselect",n=new t(this,o)),"string"==typeof s?n[s](i[1]):n.init()})},e.fn.multiSelect.defaults={keySelect:[32],selectableOptgroup:!1,disabledClass:"disabled",dblClick:!1,keepOrder:!1,cssClass:""},e.fn.multiSelect.Constructor=t,e.fn.insertAt=function(e,t){return this.each(function(){0===e?t.prepend(this):t.children().eq(e-1).after(this)})}}(window.jQuery),function(e,t,s,i){e.fn.quicksearch=function(s,i){var l,n,o,a,r="",c=this,h=e.extend({delay:100,selector:null,stripeRows:null,loader:null,noResults:"",matchedResultsCount:0,bind:"keyup",onBefore:function(){},onAfter:function(){},show:function(){this.style.display=""},hide:function(){this.style.display="none"},prepareQuery:function(e){return e.toLowerCase().split(" ")},testQuery:function(e,t,s){for(var i=0;i<e.length;i+=1)if(t.indexOf(e[i])===-1)return!1;return!0}},i);return this.go=function(){for(var e=0,t=0,s=!0,i=h.prepareQuery(r),l=0===r.replace(" ","").length,e=0,a=o.length;e<a;e++)l||h.testQuery(i,n[e],o[e])?(h.show.apply(o[e]),s=!1,t++):h.hide.apply(o[e]);return s?this.results(!1):(this.results(!0),this.stripe()),this.matchedResultsCount=t,this.loader(!1),h.onAfter(),this},this.search=function(e){r=e,c.trigger()},this.currentMatchedResults=function(){return this.matchedResultsCount},this.stripe=function(){if("object"==typeof h.stripeRows&&null!==h.stripeRows){var t=h.stripeRows.join(" "),s=h.stripeRows.length;a.not(":hidden").each(function(i){e(this).removeClass(t).addClass(h.stripeRows[i%s])})}return this},this.strip_html=function(t){var s=t.replace(new RegExp("<[^<]+>","g"),"");return s=e.trim(s.toLowerCase())},this.results=function(t){return"string"==typeof h.noResults&&""!==h.noResults&&(t?e(h.noResults).hide():e(h.noResults).show()),this},this.loader=function(t){return"string"==typeof h.loader&&""!==h.loader&&(t?e(h.loader).show():e(h.loader).hide()),this},this.cache=function(){a=e(s),"string"==typeof h.noResults&&""!==h.noResults&&(a=a.not(h.noResults));var t="string"==typeof h.selector?a.find(h.selector):e(s).not(h.noResults);return n=t.map(function(){return c.strip_html(this.innerHTML)}),o=a.map(function(){return this}),r=r||this.val()||"",this.go()},this.trigger=function(){return this.loader(!0),h.onBefore(),t.clearTimeout(l),l=t.setTimeout(function(){c.go()},h.delay),this},this.cache(),this.results(!0),this.stripe(),this.loader(!1),this.each(function(){e(this).on(h.bind,function(){r=e(this).val(),c.trigger()})})}}(jQuery,this,document);