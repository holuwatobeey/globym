/*!
 LivelyCart Pro
 version: 3.x
 --------------------------------------------------------------------- 
 Build Timestamp: 1569677944
 Build Date: Sat Sep 28 2019 19:09:04 GMT+0530 (India Standard Time)
--------------------------------------------------------------------- */
!function(e){"function"==typeof define&&define.amd?define(["jquery","./jquery.fancytree.ui-deps"],e):"object"==typeof module&&module.exports?(require("./jquery.fancytree.ui-deps"),module.exports=e(require("jquery"))):e(jQuery)}(function(e){"use strict";function t(t,n){t||(n=n?": "+n:"",e.error("Fancytree assertion failed"+n))}function n(e,t){var n,i,r=window.console?window.console[e]:null;if(r)try{r.apply(window.console,t)}catch(e){for(i="",n=0;n<t.length;n++)i+=t[n];r(i)}}function i(){var t,n,r,o,s,a=arguments[0]||{},l=1,d=arguments.length;if("object"==typeof a||e.isFunction(a)||(a={}),l===d)throw"need at least two args";for(;l<d;l++)if(null!=(t=arguments[l]))for(n in t)r=a[n],a!==(o=t[n])&&(o&&e.isPlainObject(o)?(s=r&&e.isPlainObject(r)?r:{},a[n]=i(s,o)):void 0!==o&&(a[n]=o));return a}function r(e,t,n,i,r){return function(){var n=t[e],o=i[e],s=t.ext[r],a=function(){return n.apply(t,arguments)},l=function(e){return n.apply(t,e)};return function(){var e=t._local,n=t._super,i=t._superApply;try{return t._local=s,t._super=a,t._superApply=l,o.apply(t,arguments)}finally{t._local=e,t._super=n,t._superApply=i}}}()}function o(t,n,i,o){for(var s in i)"function"==typeof i[s]?"function"==typeof t[s]?t[s]=r(s,t,0,i,o):"_"===s.charAt(0)?t.ext[o][s]=r(s,t,0,i,o):e.error("Could not override tree."+s+". Use prefix '_' to create tree."+o+"._"+s):"options"!==s&&(t.ext[o][s]=i[s])}function s(t,n){return void 0===t?e.Deferred(function(){this.resolve()}).promise():e.Deferred(function(){this.resolveWith(t,n)}).promise()}function a(t,n){return void 0===t?e.Deferred(function(){this.reject()}).promise():e.Deferred(function(){this.rejectWith(t,n)}).promise()}function l(e,t){return function(){e.resolveWith(t)}}function d(t){var n=e.extend({},t.data()),i=n.json;return delete n.fancytree,delete n.uiFancytree,i&&(delete n.json,n=e.extend(n,i)),n}function c(e){return(""+e).replace(m,function(e){return k[e]})}function u(e){return e=e.toLowerCase(),function(t){return t.title.toLowerCase().indexOf(e)>=0}}function h(n,i){var r,o,s,a;for(this.parent=n,this.tree=n.tree,this.ul=null,this.li=null,this.statusNodeType=null,this._isLoading=!1,this._error=null,this.data={},r=0,o=L.length;r<o;r++)this[s=L[r]]=i[s];null==this.unselectableIgnore&&null==this.unselectableStatus||(this.unselectable=!0),i.hideCheckbox&&e.error("'hideCheckbox' node option was removed in v2.23.0: use 'checkbox: false'"),i.data&&e.extend(this.data,i.data);for(s in i)A[s]||e.isFunction(i[s])||F[s]||(this.data[s]=i[s]);null==this.key?this.tree.options.defaultKey?(this.key=this.tree.options.defaultKey(this),t(this.key,"defaultKey() must return a unique key")):this.key="_"+v._nextNodeKey++:this.key=""+this.key,i.active&&(t(null===this.tree.activeNode,"only one active node allowed"),this.tree.activeNode=this),i.selected&&(this.tree.lastSelectedNode=this),(a=i.children)?a.length?this._setChildren(a):this.children=this.lazy?[]:null:this.children=null,this.tree._callHook("treeRegisterNode",this.tree,!0,this)}function f(t){this.widget=t,this.$div=t.element,this.options=t.options,this.options&&(e.isFunction(this.options.lazyload)&&!e.isFunction(this.options.lazyLoad)&&(this.options.lazyLoad=function(){return v.warn("The 'lazyload' event is deprecated since 2014-02-25. Use 'lazyLoad' (with uppercase L) instead."),t.options.lazyload.apply(this,arguments)}),e.isFunction(this.options.loaderror)&&e.error("The 'loaderror' event was renamed since 2014-07-03. Use 'loadError' (with uppercase E) instead."),void 0!==this.options.fx&&v.warn("The 'fx' option was replaced by 'toggleEffect' since 2014-11-30."),void 0!==this.options.removeNode&&e.error("The 'removeNode' event was replaced by 'modifyChild' since 2.20 (2016-09-10).")),this.ext={},this.types={},this.columns={},this.data=d(this.$div),this._id=e.ui.fancytree._nextId++,this._ns=".fancytree-"+this._id,this.activeNode=null,this.focusNode=null,this._hasFocus=null,this._tempCache={},this._lastMousedownNode=null,this._enableUpdate=!0,this.lastSelectedNode=null,this.systemFocusElement=null,this.lastQuicksearchTerm="",this.lastQuicksearchTime=0,this.statusClassPropName="span",this.ariaPropName="li",this.nodeContainerAttrName="li",this.$div.find(">ul.fancytree-container").remove();var n,i={tree:this};this.rootNode=new h(i,{title:"root",key:"root_"+this._id,children:null,expanded:!0}),this.rootNode.parent=null,n=e("<ul>",{class:"ui-fancytree fancytree-container fancytree-plain"}).appendTo(this.$div),this.$container=n,this.rootNode.ul=n[0],null==this.options.debugLevel&&(this.options.debugLevel=v.debugLevel)}{if(!e.ui||!e.ui.fancytree){var p,g,v=null,y=new RegExp(/\.|\//),b=/[&<>"'\/]/g,m=/[<>"'\/]/g,x="$recursive_request",k={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;","/":"&#x2F;"},N={16:!0,17:!0,18:!0},_={8:"backspace",9:"tab",10:"return",13:"return",19:"pause",20:"capslock",27:"esc",32:"space",33:"pageup",34:"pagedown",35:"end",36:"home",37:"left",38:"up",39:"right",40:"down",45:"insert",46:"del",59:";",61:"=",96:"0",97:"1",98:"2",99:"3",100:"4",101:"5",102:"6",103:"7",104:"8",105:"9",106:"*",107:"+",109:"-",110:".",111:"/",112:"f1",113:"f2",114:"f3",115:"f4",116:"f5",117:"f6",118:"f7",119:"f8",120:"f9",121:"f10",122:"f11",123:"f12",144:"numlock",145:"scroll",173:"-",186:";",187:"=",188:",",189:"-",190:".",191:"/",192:"`",219:"[",220:"\\",221:"]",222:"'"},C={16:"shift",17:"ctrl",18:"alt",91:"meta",93:"meta"},w={0:"",1:"left",2:"middle",3:"right"},S="active expanded focus folder lazy radiogroup selected unselectable unselectableIgnore".split(" "),E={},T="columns types".split(" "),L="checkbox expanded extraClasses folder icon iconTooltip key lazy partsel radiogroup refKey selected statusNodeType title tooltip type unselectable unselectableIgnore unselectableStatus".split(" "),A={},P={},F={active:!0,children:!0,data:!0,focus:!0};for(p=0;p<S.length;p++)E[S[p]]=!0;for(p=0;p<L.length;p++)g=L[p],A[g]=!0,g!==g.toLowerCase()&&(P[g.toLowerCase()]=g);return t(e.ui,"Fancytree requires jQuery UI (http://jqueryui.com)"),Date.now||(Date.now=function(){return(new Date).getTime()}),h.prototype={_findDirectChild:function(e){var t,n,i=this.children;if(i)if("string"==typeof e){for(t=0,n=i.length;t<n;t++)if(i[t].key===e)return i[t]}else{if("number"==typeof e)return this.children[e];if(e.parent===this)return e}return null},_setChildren:function(e){t(e&&(!this.children||0===this.children.length),"only init supported"),this.children=[];for(var n=0,i=e.length;n<i;n++)this.children.push(new h(this,e[n]))},addChildren:function(n,i){var r,o,s,a=this.getFirstChild(),l=this.getLastChild(),d=null,c=[];for(e.isPlainObject(n)&&(n=[n]),this.children||(this.children=[]),r=0,o=n.length;r<o;r++)c.push(new h(this,n[r]));if(d=c[0],null==i?this.children=this.children.concat(c):(i=this._findDirectChild(i),t((s=e.inArray(i,this.children))>=0,"insertBefore must be an existing child"),this.children.splice.apply(this.children,[s,0].concat(c))),a&&!i){for(r=0,o=c.length;r<o;r++)c[r].render();a!==this.getFirstChild()&&a.renderStatus(),l!==this.getLastChild()&&l.renderStatus()}else(!this.parent||this.parent.ul||this.tr)&&this.render();return 3===this.tree.options.selectMode&&this.fixSelection3FromEndNodes(),this.triggerModifyChild("add",1===c.length?c[0]:null),d},addClass:function(e){return this.toggleClass(e,!0)},addNode:function(e,n){switch(void 0!==n&&"over"!==n||(n="child"),n){case"after":return this.getParent().addChildren(e,this.getNextSibling());case"before":return this.getParent().addChildren(e,this);case"firstChild":var i=this.children?this.children[0]:null;return this.addChildren(e,i);case"child":case"over":return this.addChildren(e)}t(!1,"Invalid mode: "+n)},addPagingNode:function(t,n){var i,r;n=n||"child";{if(!1!==t)return t=e.extend({title:this.tree.options.strings.moreData,statusNodeType:"paging",icon:!1},t),this.partload=!0,this.addNode(t,n);for(i=this.children.length-1;i>=0;i--)"paging"===(r=this.children[i]).statusNodeType&&this.removeChild(r);this.partload=!1}},appendSibling:function(e){return this.addNode(e,"after")},applyPatch:function(t){if(null===t)return this.remove(),s(this);var n,i,r={children:!0,expanded:!0,parent:!0};for(n in t)i=t[n],r[n]||e.isFunction(i)||(A[n]?this[n]=i:this.data[n]=i);return t.hasOwnProperty("children")&&(this.removeChildren(),t.children&&this._setChildren(t.children)),this.isVisible()&&(this.renderTitle(),this.renderStatus()),t.hasOwnProperty("expanded")?this.setExpanded(t.expanded):s(this)},collapseSiblings:function(){return this.tree._callHook("nodeCollapseSiblings",this)},copyTo:function(e,t,n){return e.addNode(this.toDict(!0,n),t)},countChildren:function(e){var t,n,i,r=this.children;if(!r)return 0;if(i=r.length,!1!==e)for(t=0,n=i;t<n;t++)i+=r[t].countChildren();return i},debug:function(e){this.tree.options.debugLevel>=4&&(Array.prototype.unshift.call(arguments,this.toString()),n("log",arguments))},discard:function(){return this.warn("FancytreeNode.discard() is deprecated since 2014-02-16. Use .resetLazy() instead."),this.resetLazy()},discardMarkup:function(e){var t=e?"nodeRemoveMarkup":"nodeRemoveChildMarkup";this.tree._callHook(t,this)},error:function(e){this.options.debugLevel>=1&&(Array.prototype.unshift.call(arguments,this.toString()),n("error",arguments))},findAll:function(t){t=e.isFunction(t)?t:u(t);var n=[];return this.visit(function(e){t(e)&&n.push(e)}),n},findFirst:function(t){t=e.isFunction(t)?t:u(t);var n=null;return this.visit(function(e){if(t(e))return n=e,!1}),n},_changeSelectStatusAttrs:function(e){var n=!1,i=this.tree.options,r=v.evalOption("unselectable",this,this,i,!1),o=v.evalOption("unselectableStatus",this,this,i,void 0);switch(r&&null!=o&&(e=o),e){case!1:n=this.selected||this.partsel,this.selected=!1,this.partsel=!1;break;case!0:n=!this.selected||!this.partsel,this.selected=!0,this.partsel=!0;break;case void 0:n=this.selected||!this.partsel,this.selected=!1,this.partsel=!0;break;default:t(!1,"invalid state: "+e)}return n&&this.renderStatus(),n},fixSelection3AfterClick:function(e){var t=this.isSelected();this.visit(function(e){e._changeSelectStatusAttrs(t)}),this.fixSelection3FromEndNodes(e)},fixSelection3FromEndNodes:function(e){function n(e){var t,r,o,s,a,l,d,c,u=e.children;if(u&&u.length){for(l=!0,d=!1,t=0,r=u.length;t<r;t++)s=n(o=u[t]),v.evalOption("unselectableIgnore",o,o,i,!1)||(!1!==s&&(d=!0),!0!==s&&(l=!1));a=!!l||!!d&&void 0}else a=null==(c=v.evalOption("unselectableStatus",e,e,i,void 0))?!!e.selected:!!c;return e._changeSelectStatusAttrs(a),a}var i=this.tree.options;t(3===i.selectMode,"expected selectMode 3"),n(this),this.visitParents(function(e){var t,n,r,o,s,a=e.children,l=!0,d=!1;for(t=0,n=a.length;t<n;t++)r=a[t],v.evalOption("unselectableIgnore",r,r,i,!1)||(((o=null==(s=v.evalOption("unselectableStatus",r,r,i,void 0))?!!r.selected:!!s)||r.partsel)&&(d=!0),o||(l=!1));o=!!l||!!d&&void 0,e._changeSelectStatusAttrs(o)})},fromDict:function(t){for(var n in t)A[n]?this[n]=t[n]:"data"===n?e.extend(this.data,t.data):e.isFunction(t[n])||F[n]||(this.data[n]=t[n]);t.children&&(this.removeChildren(),this.addChildren(t.children)),this.renderTitle()},getChildren:function(){if(void 0!==this.hasChildren())return this.children},getFirstChild:function(){return this.children?this.children[0]:null},getIndex:function(){return e.inArray(this,this.parent.children)},getIndexHier:function(t,n){t=t||".";var i,r=[];return e.each(this.getParentList(!1,!0),function(e,t){i=""+(t.getIndex()+1),n&&(i=("0000000"+i).substr(-n)),r.push(i)}),r.join(t)},getKeyPath:function(e){var t=[],n=this.tree.options.keyPathSeparator;return this.visitParents(function(e){e.parent&&t.unshift(e.key)},!e),n+t.join(n)},getLastChild:function(){return this.children?this.children[this.children.length-1]:null},getLevel:function(){for(var e=0,t=this.parent;t;)e++,t=t.parent;return e},getNextSibling:function(){if(this.parent){var e,t,n=this.parent.children;for(e=0,t=n.length-1;e<t;e++)if(n[e]===this)return n[e+1]}return null},getParent:function(){return this.parent},getParentList:function(e,t){for(var n=[],i=t?this:this.parent;i;)(e||i.parent)&&n.unshift(i),i=i.parent;return n},getPrevSibling:function(){if(this.parent){var e,t,n=this.parent.children;for(e=1,t=n.length;e<t;e++)if(n[e]===this)return n[e-1]}return null},getSelectedNodes:function(e){var t=[];return this.visit(function(n){if(n.selected&&(t.push(n),!0===e))return"skip"}),t},hasChildren:function(){if(this.lazy){if(null==this.children)return;if(0===this.children.length)return!1;if(1===this.children.length&&this.children[0].isStatusNode())return;return!0}return!(!this.children||!this.children.length)},hasFocus:function(){return this.tree.hasFocus()&&this.tree.focusNode===this},info:function(e){this.tree.options.debugLevel>=3&&(Array.prototype.unshift.call(arguments,this.toString()),n("info",arguments))},isActive:function(){return this.tree.activeNode===this},isBelowOf:function(e){return this.getIndexHier(".",5)>e.getIndexHier(".",5)},isChildOf:function(e){return this.parent&&this.parent===e},isDescendantOf:function(t){if(!t||t.tree!==this.tree)return!1;for(var n=this.parent;n;){if(n===t)return!0;n===n.parent&&e.error("Recursive parent link: "+n),n=n.parent}return!1},isExpanded:function(){return!!this.expanded},isFirstSibling:function(){var e=this.parent;return!e||e.children[0]===this},isFolder:function(){return!!this.folder},isLastSibling:function(){var e=this.parent;return!e||e.children[e.children.length-1]===this},isLazy:function(){return!!this.lazy},isLoaded:function(){return!this.lazy||void 0!==this.hasChildren()},isLoading:function(){return!!this._isLoading},isRoot:function(){return this.isRootNode()},isPartsel:function(){return!this.selected&&!!this.partsel},isPartload:function(){return!!this.partload},isRootNode:function(){return this.tree.rootNode===this},isSelected:function(){return!!this.selected},isStatusNode:function(){return!!this.statusNodeType},isPagingNode:function(){return"paging"===this.statusNodeType},isTopLevel:function(){return this.tree.rootNode===this.parent},isUndefined:function(){return void 0===this.hasChildren()},isVisible:function(){var e,t,n=this.getParentList(!1,!1);for(e=0,t=n.length;e<t;e++)if(!n[e].expanded)return!1;return!0},lazyLoad:function(e){return this.warn("FancytreeNode.lazyLoad() is deprecated since 2014-02-16. Use .load() instead."),this.load(e)},load:function(e){var n,i,r=this,o=this.isExpanded();return t(this.isLazy(),"load() requires a lazy node"),e||this.isUndefined()?(this.isLoaded()&&this.resetLazy(),!1===(i=this.tree._triggerNodeEvent("lazyLoad",this))?s(this):(t("boolean"!=typeof i,"lazyLoad event must return source in data.result"),n=this.tree._callHook("nodeLoadChildren",this,i),o?(this.expanded=!0,n.always(function(){r.render()})):n.always(function(){r.renderStatus()}),n)):s(this)},makeVisible:function(t){var n,i=this,r=[],o=new e.Deferred,s=this.getParentList(!1,!1),a=s.length,l=!(t&&!0===t.noAnimation),d=!(t&&!1===t.scrollIntoView);for(n=a-1;n>=0;n--)r.push(s[n].setExpanded(!0,t));return e.when.apply(e,r).done(function(){d?i.scrollIntoView(l).done(function(){o.resolve()}):o.resolve()}),o.promise()},moveTo:function(n,i,r){void 0===i||"over"===i?i="child":"firstChild"===i&&(n.children&&n.children.length?(i="before",n=n.children[0]):i="child");var o,s=this.parent,a="child"===i?n:n.parent;if(this!==n){if(this.parent?a.isDescendantOf(this)&&e.error("Cannot move a node to its own descendant"):e.error("Cannot move system root"),a!==s&&s.triggerModifyChild("remove",this),1===this.parent.children.length){if(this.parent===a)return;this.parent.children=this.parent.lazy?[]:null,this.parent.expanded=!1}else t((o=e.inArray(this,this.parent.children))>=0,"invalid source parent"),this.parent.children.splice(o,1);if(this.parent=a,a.hasChildren())switch(i){case"child":a.children.push(this);break;case"before":t((o=e.inArray(n,a.children))>=0,"invalid target parent"),a.children.splice(o,0,this);break;case"after":t((o=e.inArray(n,a.children))>=0,"invalid target parent"),a.children.splice(o+1,0,this);break;default:e.error("Invalid mode "+i)}else a.children=[this];r&&n.visit(r,!0),a===s?a.triggerModifyChild("move",this):a.triggerModifyChild("add",this),this.tree!==n.tree&&(this.warn("Cross-tree moveTo is experimantal!"),this.visit(function(e){e.tree=n.tree},!0)),s.isDescendantOf(a)||s.render(),a.isDescendantOf(s)||a===s||a.render()}},navigate:function(t,n){function i(i){if(i){try{i.makeVisible({scrollIntoView:!1})}catch(e){}return e(i.span).is(":visible")?!1===n?i.setFocus():i.setActive():(i.debug("Navigate: skipping hidden node"),void i.navigate(t,n))}}var r,o,a,l=e.ui.keyCode,d=null;switch(t){case l.BACKSPACE:this.parent&&this.parent.parent&&(a=i(this.parent));break;case l.HOME:this.tree.visit(function(t){if(e(t.span).is(":visible"))return a=i(t),!1});break;case l.END:this.tree.visit(function(t){e(t.span).is(":visible")&&(a=t)}),a&&(a=i(a));break;case l.LEFT:this.expanded?(this.setExpanded(!1),a=i(this)):this.parent&&this.parent.parent&&(a=i(this.parent));break;case l.RIGHT:this.expanded||!this.children&&!this.lazy?this.children&&this.children.length&&(a=i(this.children[0])):(this.setExpanded(),a=i(this));break;case l.UP:for(d=this.getPrevSibling();d&&!e(d.span).is(":visible");)d=d.getPrevSibling();for(;d&&d.expanded&&d.children&&d.children.length;)d=d.children[d.children.length-1];!d&&this.parent&&this.parent.parent&&(d=this.parent),a=i(d);break;case l.DOWN:if(this.expanded&&this.children&&this.children.length)d=this.children[0];else for(r=(o=this.getParentList(!1,!0)).length-1;r>=0;r--){for(d=o[r].getNextSibling();d&&!e(d.span).is(":visible");)d=d.getNextSibling();if(d)break}a=i(d);break;default:!1}return a||s()},remove:function(){return this.parent.removeChild(this)},removeChild:function(e){return this.tree._callHook("nodeRemoveChild",this,e)},removeChildren:function(){return this.tree._callHook("nodeRemoveChildren",this)},removeClass:function(e){return this.toggleClass(e,!1)},render:function(e,t){return this.tree._callHook("nodeRender",this,e,t)},renderTitle:function(){return this.tree._callHook("nodeRenderTitle",this)},renderStatus:function(){return this.tree._callHook("nodeRenderStatus",this)},replaceWith:function(n){var i,r=this.parent,o=e.inArray(this,r.children),s=this;return t(this.isPagingNode(),"replaceWith() currently requires a paging status node"),(i=this.tree._callHook("nodeLoadChildren",this,n)).done(function(e){var t=s.children;for(p=0;p<t.length;p++)t[p].parent=r;r.children.splice.apply(r.children,[o+1,0].concat(t)),s.children=null,s.remove(),r.render()}).fail(function(){s.setExpanded()}),i},resetLazy:function(){this.removeChildren(),this.expanded=!1,this.lazy=!0,this.children=void 0,this.renderStatus()},scheduleAction:function(t,n){this.tree.timer&&(clearTimeout(this.tree.timer),this.tree.debug("clearTimeout(%o)",this.tree.timer)),this.tree.timer=null;var i=this;switch(t){case"cancel":break;case"expand":this.tree.timer=setTimeout(function(){i.tree.debug("setTimeout: trigger expand"),i.setExpanded(!0)},n);break;case"activate":this.tree.timer=setTimeout(function(){i.tree.debug("setTimeout: trigger activate"),i.setActive(!0)},n);break;default:e.error("Invalid mode "+t)}},scrollIntoView:function(n,i){if(void 0!==i&&function(e){return!(!e.tree||void 0===e.statusNodeType)}(i))throw"scrollIntoView() with 'topNode' option is deprecated since 2014-05-08. Use 'options.topNode' instead.";var r=e.extend({effects:!0===n?{duration:200,queue:!1}:n,scrollOfs:this.tree.options.scrollOfs,scrollParent:this.tree.options.scrollParent,topNode:null},i),o=r.scrollParent;o?o.jquery||(o=e(o)):o=this.tree.tbody?this.tree.$container.scrollParent():this.tree.$container,o[0]!==document&&o[0]!==document.body||(this.debug("scrollIntoView(): normalizing scrollParent to 'window':",o[0]),o=e(window));var a,l,d,c=new e.Deferred,u=this,h=e(this.span).height(),f=r.scrollOfs.top||0,p=r.scrollOfs.bottom||0,g=o.height(),v=o.scrollTop(),y=o,b=o[0]===window,m=r.topNode||null,x=null;return e(this.span).is(":visible")?(b?(l=e(this.span).offset().top,a=m&&m.span?e(m.span).offset().top:0,y=e("html,body")):(t(o[0]!==document&&o[0]!==document.body,"scrollParent should be a simple element or `window`, not document or body."),d=o.offset().top,l=e(this.span).offset().top-d+v,a=m?e(m.span).offset().top-d+v:0,g-=Math.max(0,o.innerHeight()-o[0].clientHeight)),l<v+f?x=l-f:l+h>v+g-p&&(x=l+h-g+p,m&&(t(m.isRootNode()||e(m.span).is(":visible"),"topNode must be visible"),a<x&&(x=a-f))),null!==x?r.effects?(r.effects.complete=function(){c.resolveWith(u)},y.stop(!0).animate({scrollTop:x},r.effects)):(y[0].scrollTop=x,c.resolveWith(this)):c.resolveWith(this),c.promise()):(this.warn("scrollIntoView(): node is invisible."),s())},setActive:function(e,t){return this.tree._callHook("nodeSetActive",this,e,t)},setExpanded:function(e,t){return this.tree._callHook("nodeSetExpanded",this,e,t)},setFocus:function(e){return this.tree._callHook("nodeSetFocus",this,e)},setSelected:function(e,t){return this.tree._callHook("nodeSetSelected",this,e,t)},setStatus:function(e,t,n){return this.tree._callHook("nodeSetStatus",this,e,t,n)},setTitle:function(e){this.title=e,this.renderTitle(),this.triggerModify("rename")},sortChildren:function(e,t){var n,i,r=this.children;if(r){if(e=e||function(e,t){var n=e.title.toLowerCase(),i=t.title.toLowerCase();return n===i?0:n>i?1:-1},r.sort(e),t)for(n=0,i=r.length;n<i;n++)r[n].children&&r[n].sortChildren(e,"$norender$");"$norender$"!==t&&this.render(),this.triggerModifyChild("sort")}},toDict:function(t,n){var i,r,o,s={},a=this;if(e.each(L,function(e,t){(a[t]||!1===a[t])&&(s[t]=a[t])}),e.isEmptyObject(this.data)||(s.data=e.extend({},this.data),e.isEmptyObject(s.data)&&delete s.data),n&&n(s,a),t&&this.hasChildren())for(s.children=[],i=0,r=this.children.length;i<r;i++)(o=this.children[i]).isStatusNode()||s.children.push(o.toDict(!0,n));return s},toggleClass:function(t,n){var i,r,o=t.match(/\S+/g)||[],s=0,a=!1,l=this[this.tree.statusClassPropName],d=" "+(this.extraClasses||"")+" ";for(l&&e(l).toggleClass(t,n);i=o[s++];)if(r=d.indexOf(" "+i+" ")>=0,n=void 0===n?!r:!!n)r||(d+=i+" ",a=!0);else for(;d.indexOf(" "+i+" ")>-1;)d=d.replace(" "+i+" "," ");return this.extraClasses=e.trim(d),a},toggleExpanded:function(){return this.tree._callHook("nodeToggleExpanded",this)},toggleSelected:function(){return this.tree._callHook("nodeToggleSelected",this)},toString:function(){return"FancytreeNode@"+this.key+"[title='"+this.title+"']"},triggerModifyChild:function(t,n,i){var r,o=this.tree.options.modifyChild;o&&(n&&n.parent!==this&&e.error("childNode "+n+" is not a child of "+this),r={node:this,tree:this.tree,operation:t,childNode:n||null},i&&e.extend(r,i),o({type:"modifyChild"},r))},triggerModify:function(e,t){this.parent.triggerModifyChild(e,this,t)},visit:function(e,t){var n,i,r=!0,o=this.children;if(!0===t&&(!1===(r=e(this))||"skip"===r))return r;if(o)for(n=0,i=o.length;n<i&&!1!==(r=o[n].visit(e,!0));n++);return r},visitAndLoad:function(t,n,i){var r,o,a,l=this;return!t||!0!==n||!1!==(o=t(l))&&"skip"!==o?l.children||l.lazy?(r=new e.Deferred,a=[],l.load().done(function(){for(var n=0,i=l.children.length;n<i;n++){if(!1===(o=l.children[n].visitAndLoad(t,!0,!0))){r.reject();break}"skip"!==o&&a.push(o)}e.when.apply(this,a).then(function(){r.resolve()})}),r.promise()):s():i?o:s()},visitParents:function(e,t){if(t&&!1===e(this))return!1;for(var n=this.parent;n;){if(!1===e(n))return!1;n=n.parent}return!0},visitSiblings:function(e,t){var n,i,r,o=this.parent.children;for(n=0,i=o.length;n<i;n++)if(r=o[n],(t||r!==this)&&!1===e(r))return!1;return!0},warn:function(e){this.tree.options.debugLevel>=2&&(Array.prototype.unshift.call(arguments,this.toString()),n("warn",arguments))}},f.prototype={_makeHookContext:function(t,n,i){var r,o;return void 0!==t.node?(n&&t.originalEvent!==n&&e.error("invalid args"),r=t):t.tree?r={node:t,tree:o=t.tree,widget:o.widget,options:o.widget.options,originalEvent:n,typeInfo:o.types[t.type]||{}}:t.widget?r={node:null,tree:t,widget:t.widget,options:t.widget.options,originalEvent:n}:e.error("invalid args"),i&&e.extend(r,i),r},_callHook:function(t,n,i){var r=this._makeHookContext(n),o=this[t],s=Array.prototype.slice.call(arguments,2);return e.isFunction(o)||e.error("_callHook('"+t+"') is not a function"),s.unshift(r),o.apply(this,s)},_setExpiringValue:function(e,t,n){this._tempCache[e]={value:t,expire:Date.now()+(+n||50)}},_getExpiringValue:function(e){var t=this._tempCache[e];return t&&t.expire>Date.now()?t.value:(delete this._tempCache[e],null)},_requireExtension:function(n,i,r,o){null!=r&&(r=!!r);var s=this._local.name,a=this.options.extensions,l=e.inArray(n,a)<e.inArray(s,a),d=i&&null==this.ext[n],c=!d&&null!=r&&r!==l;return t(s&&s!==n,"invalid or same name"),!d&&!c||(o||(d||i?(o="'"+s+"' extension requires '"+n+"'",c&&(o+=" to be registered "+(r?"before":"after")+" itself")):o="If used together, `"+n+"` must be registered "+(r?"before":"after")+" `"+s+"`"),e.error(o),!1)},activateKey:function(e,t){var n=this.getNodeByKey(e);return n?n.setActive(!0,t):this.activeNode&&this.activeNode.setActive(!1,t),n},addPagingNode:function(e,t){return this.rootNode.addPagingNode(e,t)},applyPatch:function(n){var i,r,o,s,a,d,c=n.length,u=[];for(r=0;r<c;r++)t(2===(o=n[r]).length,"patchList must be an array of length-2-arrays"),s=o[0],a=o[1],(d=null===s?this.rootNode:this.getNodeByKey(s))?(i=new e.Deferred,u.push(i),d.applyPatch(a).always(l(i,d))):this.warn("could not find node with key '"+s+"'");return e.when.apply(e,u).promise()},clear:function(e){this._callHook("treeClear",this)},count:function(){return this.rootNode.countChildren()},debug:function(e){this.options.debugLevel>=4&&(Array.prototype.unshift.call(arguments,this.toString()),n("log",arguments))},enable:function(e){!1===e?this.widget.disable():this.widget.enable()},enableUpdate:function(e){return e=!1!==e,!!this._enableUpdate==!!e?e:(this._enableUpdate=e,e?(this.debug("enableUpdate(true): redraw "),this.render()):this.debug("enableUpdate(false)..."),!e)},expandAll:function(e,t){e=!1!==e,this.visit(function(n){!1!==n.hasChildren()&&n.isExpanded()!==e&&n.setExpanded(e,t)})},findAll:function(e){return this.rootNode.findAll(e)},findFirst:function(e){return this.rootNode.findFirst(e)},findNextNode:function(t,n,i){t="string"==typeof t?function(e){var t=new RegExp("^"+e,"i");return function(e){return t.test(e.title)}}(t):t;var r=null,o=(n=n||this.getFirstChild()).parent.children,s=null,a=function(e,t,n){var i,r,o=e.children,s=o.length,l=o[t];if(l&&!1===n(l))return!1;if(l&&l.children&&l.expanded&&!1===a(l,0,n))return!1;for(i=t+1;i<s;i++)if(!1===a(e,i,n))return!1;return(r=e.parent)?a(r,r.children.indexOf(e)+1,n):a(e,0,n)};return a(n.parent,o.indexOf(n),function(i){if(i===r)return!1;r=r||i;{if(e(i.span).is(":visible"))return(!t(i)||(s=i)===n)&&void 0;i.debug("quicksearch: skipping hidden node")}}),s},generateFormElements:function(t,n,i){function r(t){d.append(e("<input>",{type:"checkbox",name:s,value:t.key,checked:!0}))}i=i||{};var o,s="string"==typeof t?t:"ft_"+this._id+"[]",a="string"==typeof n?n:"ft_"+this._id+"_active",l="fancytree_result_"+this._id,d=e("#"+l),c=3===this.options.selectMode&&!1!==i.stopOnParents;d.length?d.empty():d=e("<div>",{id:l}).hide().insertAfter(this.$container),!1!==n&&this.activeNode&&d.append(e("<input>",{type:"radio",name:a,value:this.activeNode.key,checked:!0})),i.filter?this.visit(function(e){var t=i.filter(e);if("skip"===t)return t;!1!==t&&r(e)}):!1!==t&&(o=this.getSelectedNodes(c),e.each(o,function(e,t){r(t)}))},getActiveNode:function(){return this.activeNode},getFirstChild:function(){return this.rootNode.getFirstChild()},getFocusNode:function(){return this.focusNode},getOption:function(e){return this.widget.option(e)},getNodeByKey:function(e,t){var n,i;return!t&&(n=document.getElementById(this.options.idPrefix+e))?n.ftnode?n.ftnode:null:(t=t||this.rootNode,i=null,t.visit(function(t){if(t.key===e)return i=t,!1},!0),i)},getRootNode:function(){return this.rootNode},getSelectedNodes:function(e){return this.rootNode.getSelectedNodes(e)},hasFocus:function(){return!!this._hasFocus},info:function(e){this.options.debugLevel>=3&&(Array.prototype.unshift.call(arguments,this.toString()),n("info",arguments))},loadKeyPath:function(t,n){var i,r,o,s=this,a=new e.Deferred,l=this.getRootNode(),d=this.options.keyPathSeparator,c=[],u=e.extend({},n);for("function"==typeof n?i=n:n&&n.callback&&(i=n.callback),u.callback=function(e,t,n){i&&i.call(e,t,n),a.notifyWith(e,[{node:t,status:n}])},null==u.matchKey&&(u.matchKey=function(e,t){return e.key===t}),e.isArray(t)||(t=[t]),r=0;r<t.length;r++)(o=t[r]).charAt(0)===d&&(o=o.substr(1)),c.push(o.split(d));return setTimeout(function(){s._loadKeyPathImpl(a,u,l,c).done(function(){a.resolve()})},0),a.promise()},_loadKeyPathImpl:function(t,n,i,r){function o(e,t){var i,r,o=e.children;if(o)for(i=0,r=o.length;i<r;i++)if(n.matchKey(o[i],t))return o[i];return null}function s(e,t,i){n.callback(v,t,"loading"),t.load().done(function(){v._loadKeyPathImpl.call(v,e,n,t,i).always(l(e,v))}).fail(function(i){v.warn("loadKeyPath: error loading lazy "+t),n.callback(v,u,"error"),e.rejectWith(v)})}var a,d,c,u,h,f,p,g,v=this;for(h={},d=0;d<r.length;d++)for(p=r[d],f=i;p.length;){if(c=p.shift(),!(u=o(f,c))){this.warn("loadKeyPath: key not found: "+c+" (parent: "+f+")"),n.callback(this,c,"error");break}if(0===p.length){n.callback(this,u,"ok");break}if(u.lazy&&void 0===u.hasChildren()){n.callback(this,u,"loaded"),h[c=u.key]?h[c].pathSegList.push(p):h[c]={parent:u,pathSegList:[p]};break}n.callback(this,u,"loaded"),f=u}a=[];for(var y in h){var b=h[y];g=new e.Deferred,a.push(g),s(g,b.parent,b.pathSegList)}return e.when.apply(e,a).promise()},reactivate:function(e){var t,n=this.activeNode;return n?(this.activeNode=null,t=n.setActive(!0,{noFocus:!0}),e&&n.setFocus(),t):s()},reload:function(e){return this._callHook("treeClear",this),this._callHook("treeLoad",this,e)},render:function(e,t){return this.rootNode.render(e,t)},selectAll:function(e){this.visit(function(t){t.setSelected(e)})},setFocus:function(e){return this._callHook("treeSetFocus",this,e)},setOption:function(e,t){return this.widget.option(e,t)},toDict:function(e,t){var n=this.rootNode.toDict(!0,t);return e?n:n.children},toString:function(){return"Fancytree@"+this._id},_triggerNodeEvent:function(e,t,n,i){var r=this._makeHookContext(t,n,i),o=this.widget._trigger(e,n,r);return!1!==o&&void 0!==r.result?r.result:o},_triggerTreeEvent:function(e,t,n){var i=this._makeHookContext(this,t,n),r=this.widget._trigger(e,t,i);return!1!==r&&void 0!==i.result?i.result:r},visit:function(e){return this.rootNode.visit(e,!1)},visitRows:function(e,t){if(t&&t.reverse)return delete t.reverse,this._visitRowsUp(e,t);var n,i,r,o=0,s=!1===t.includeSelf,a=!!t.includeHidden,l=t.start||this.rootNode.children[0];for(i=l.parent;i;){for(n=(r=i.children).indexOf(l)+o;n<r.length;n++){if(l=r[n],!s&&!1===e(l))return!1;if(s=!1,l.children&&l.children.length&&(a||l.expanded)&&!1===l.visit(function(t){return!1!==e(t)&&(a||!t.children||t.expanded?void 0:"skip")},!1))return!1}l=i,i=i.parent,o=1}return!0},_visitRowsUp:function(t,n){for(var i,r,o=!!n.includeHidden,s=n.start||this.rootNode.children[0];;){if(r=s.parent,(i=r.children)[0]===s)s=r,i=r.children;else for(s=i[i.indexOf(s)-1];(o||s.expanded)&&s.children&&s.children.length;)r=s,s=(i=s.children)[i.length-1];if((o||e(s.span).is(":visible"))&&!1===t(s))return!1}},warn:function(e){this.options.debugLevel>=2&&(Array.prototype.unshift.call(arguments,this.toString()),n("warn",arguments))}},e.extend(f.prototype,{nodeClick:function(e){var t,n,i=e.targetType,r=e.node;if("expander"===i){if(r.isLoading())return void r.debug("Got 2nd click while loading: ignored");this._callHook("nodeToggleExpanded",e)}else if("checkbox"===i)this._callHook("nodeToggleSelected",e),e.options.focusOnSelect&&this._callHook("nodeSetFocus",e,!0);else{if(n=!1,t=!0,r.folder)switch(e.options.clickFolderMode){case 2:n=!0,t=!1;break;case 3:t=!0,n=!0}t&&(this.nodeSetFocus(e),this._callHook("nodeSetActive",e,!0)),n&&this._callHook("nodeToggleExpanded",e)}},nodeCollapseSiblings:function(e,t){var n,i,r,o=e.node;if(o.parent)for(i=0,r=(n=o.parent.children).length;i<r;i++)n[i]!==o&&n[i].expanded&&this._callHook("nodeSetExpanded",n[i],!1,t)},nodeDblclick:function(e){"title"===e.targetType&&4===e.options.clickFolderMode&&this._callHook("nodeToggleExpanded",e),"title"===e.targetType&&e.originalEvent.preventDefault()},nodeKeydown:function(t){var n,i,r,o=t.originalEvent,s=t.node,a=t.tree,l=t.options,d=o.which,c=o.key||String.fromCharCode(d),u=!C[d]&&!_[d],h=e(o.target),f=!0,p=!(o.ctrlKey||!l.autoActivate);if(s||(r=this.getActiveNode()||this.getFirstChild())&&(r.setFocus(),(s=t.node=this.focusNode).debug("Keydown force focus on active node")),l.quicksearch&&u&&!h.is(":input:enabled"))return(i=Date.now())-a.lastQuicksearchTime>500&&(a.lastQuicksearchTerm=""),a.lastQuicksearchTime=i,a.lastQuicksearchTerm+=c,(n=a.findNextNode(a.lastQuicksearchTerm,a.getActiveNode()))&&n.setActive(),void o.preventDefault();switch(v.eventToString(o)){case"+":case"=":a.nodeSetExpanded(t,!0);break;case"-":a.nodeSetExpanded(t,!1);break;case"space":s.isPagingNode()?a._triggerNodeEvent("clickPaging",t,o):v.evalOption("checkbox",s,s,l,!1)?a.nodeToggleSelected(t):a.nodeSetActive(t,!0);break;case"return":a.nodeSetActive(t,!0);break;case"home":case"end":case"backspace":case"left":case"right":case"up":case"down":s.navigate(o.which,p);break;default:f=!1}f&&o.preventDefault()},nodeLoadChildren:function(n,i){var r,o,s,a=n.tree,l=n.node,d=Date.now();if(e.isFunction(i)&&(i=i.call(a,{type:"source"},n),t(!e.isFunction(i),"source callback must not return another function")),i.url&&(l._requestId&&l.warn("Recursive load request #"+d+" while #"+l._requestId+" is pending."),r=e.extend({},n.options.ajax,i),l._requestId=d,r.debugDelay?(o=r.debugDelay,delete r.debugDelay,e.isArray(o)&&(o=o[0]+Math.random()*(o[1]-o[0])),l.warn("nodeLoadChildren waiting debugDelay "+Math.round(o)+" ms ..."),s=e.Deferred(function(t){setTimeout(function(){e.ajax(r).done(function(){t.resolveWith(this,arguments)}).fail(function(){t.rejectWith(this,arguments)})},o)})):s=e.ajax(r),i=new e.Deferred,s.done(function(t,r,o){var s,c;if("json"!==this.dataType&&"jsonp"!==this.dataType||"string"!=typeof t||e.error("Ajax request returned a string (did you get the JSON dataType wrong?)."),l._requestId&&l._requestId>d)i.rejectWith(this,[x]);else{if(n.options.postProcess){try{c=a._triggerNodeEvent("postProcess",n,n.originalEvent,{response:t,error:null,dataType:this.dataType})}catch(e){c={error:e,message:""+e,details:"postProcess failed"}}if(c.error)return s=e.isPlainObject(c.error)?c.error:{message:c.error},s=a._makeHookContext(l,null,s),void i.rejectWith(this,[s]);(e.isArray(c)||e.isPlainObject(c)&&e.isArray(c.children))&&(t=c)}else t&&t.hasOwnProperty("d")&&n.options.enableAspx&&(t="string"==typeof t.d?e.parseJSON(t.d):t.d);i.resolveWith(this,[t])}}).fail(function(e,t,n){var r=a._makeHookContext(l,null,{error:e,args:Array.prototype.slice.call(arguments),message:n,details:e.status+": "+n});i.rejectWith(this,[r])})),e.isFunction(i.then)&&e.isFunction(i.catch)&&(s=i,i=new e.Deferred,s.then(function(e){i.resolve(e)},function(e){i.reject(e)})),e.isFunction(i.promise))a.nodeSetStatus(n,"loading"),i.done(function(e){a.nodeSetStatus(n,"ok"),l._requestId=null}).fail(function(e){var t;e!==x?(e.node&&e.error&&e.message?t=e:"[object Object]"===(t=a._makeHookContext(l,null,{error:e,args:Array.prototype.slice.call(arguments),message:e?e.message||e.toString():""})).message&&(t.message=""),l.warn("Load children failed ("+t.message+")",t),!1!==a._triggerNodeEvent("loadError",t,null)&&a.nodeSetStatus(n,"error",t.message,t.details)):l.warn("Ignored response for obsolete load request #"+d+" (expected #"+l._requestId+")")});else if(n.options.postProcess){var c=a._triggerNodeEvent("postProcess",n,n.originalEvent,{response:i,error:null,dataType:typeof i});(e.isArray(c)||e.isPlainObject(c)&&e.isArray(c.children))&&(i=c)}return e.when(i).done(function(i){var r,o;e.isPlainObject(i)&&(t(l.isRootNode(),"source may only be an object for root nodes (expecting an array of child objects otherwise)"),t(e.isArray(i.children),"if an object is passed as source, it must contain a 'children' array (all other properties are added to 'tree.data')"),r=i,i=i.children,delete r.children,e.each(T,function(e,t){void 0!==r[t]&&(a[t]=r[t],delete r[t])}),e.extend(a.data,r)),t(e.isArray(i),"expected array of children"),l._setChildren(i),a.options.nodata&&0===i.length&&(e.isFunction(a.options.nodata)?o=a.options.nodata.call(a,{type:"nodata"},n):!0===a.options.nodata&&l.isRootNode()?o=a.options.strings.nodata:"string"==typeof a.options.nodata&&l.isRootNode()&&(o=a.options.nodata),o&&l.setStatus("nodata",o)),a._triggerNodeEvent("loadChildren",l)})},nodeLoadKeyPath:function(e,t){},nodeRemoveChild:function(n,i){var r,o=n.node,s=e.extend({},n,{node:i}),a=o.children;if(1===a.length)return t(i===a[0],"invalid single child"),this.nodeRemoveChildren(n);this.activeNode&&(i===this.activeNode||this.activeNode.isDescendantOf(i))&&this.activeNode.setActive(!1),this.focusNode&&(i===this.focusNode||this.focusNode.isDescendantOf(i))&&(this.focusNode=null),this.nodeRemoveMarkup(s),this.nodeRemoveChildren(s),t((r=e.inArray(i,a))>=0,"invalid child"),o.triggerModifyChild("remove",i),i.visit(function(e){e.parent=null},!0),this._callHook("treeRegisterNode",this,!1,i),a.splice(r,1)},nodeRemoveChildMarkup:function(t){var n=t.node;n.ul&&(n.isRootNode()?e(n.ul).empty():(e(n.ul).remove(),n.ul=null),n.visit(function(e){e.li=e.ul=null}))},nodeRemoveChildren:function(t){var n=t.tree,i=t.node;i.children&&(this.activeNode&&this.activeNode.isDescendantOf(i)&&this.activeNode.setActive(!1),this.focusNode&&this.focusNode.isDescendantOf(i)&&(this.focusNode=null),this.nodeRemoveChildMarkup(t),e.extend({},t),i.triggerModifyChild("remove",null),i.visit(function(e){e.parent=null,n._callHook("treeRegisterNode",n,!1,e)}),i.lazy?i.children=[]:i.children=null,i.isRootNode()||(i.expanded=!1),this.nodeRenderStatus(t))},nodeRemoveMarkup:function(t){var n=t.node;n.li&&(e(n.li).remove(),n.li=null),this.nodeRemoveChildMarkup(t)},nodeRender:function(n,i,r,o,s){var a,l,d,c,u,h,f,p=n.node,g=n.tree,v=n.options,y=v.aria,b=!1,m=p.parent,x=!m,k=p.children,N=null;if(!1!==g._enableUpdate&&(x||m.ul)){if(t(x||m.ul,"parent UL must exist"),x||(p.li&&(i||p.li.parentNode!==p.parent.ul)&&(p.li.parentNode===p.parent.ul?N=p.li.nextSibling:this.debug("Unlinking "+p+" (must be child of "+p.parent+")"),this.nodeRemoveMarkup(n)),p.li?this.nodeRenderStatus(n):(b=!0,p.li=document.createElement("li"),p.li.ftnode=p,p.key&&v.generateIds&&(p.li.id=v.idPrefix+p.key),p.span=document.createElement("span"),p.span.className="fancytree-node",y&&!p.tr&&e(p.li).attr("role","treeitem"),p.li.appendChild(p.span),this.nodeRenderTitle(n),v.createNode&&v.createNode.call(g,{type:"createNode"},n)),v.renderNode&&v.renderNode.call(g,{type:"renderNode"},n)),k){if(x||p.expanded||!0===r){for(p.ul||(p.ul=document.createElement("ul"),(!0!==o||s)&&p.expanded||(p.ul.style.display="none"),y&&e(p.ul).attr("role","group"),p.li?p.li.appendChild(p.ul):p.tree.$div.append(p.ul)),c=0,u=k.length;c<u;c++)f=e.extend({},n,{node:k[c]}),this.nodeRender(f,i,r,!1,!0);for(a=p.ul.firstChild;a;)(d=a.ftnode)&&d.parent!==p?(p.debug("_fixParent: remove missing "+d,a),h=a.nextSibling,a.parentNode.removeChild(a),a=h):a=a.nextSibling;for(a=p.ul.firstChild,c=0,u=k.length-1;c<u;c++)(l=k[c])!==(d=a.ftnode)?p.ul.insertBefore(l.li,d.li):a=a.nextSibling}}else p.ul&&(this.warn("remove child markup for "+p),this.nodeRemoveChildMarkup(n));x||b&&m.ul.insertBefore(p.li,N)}},nodeRenderTitle:function(t,n){var i,r,o,s,a,l,d,u=t.node,h=t.tree,f=t.options,p=f.aria,g=u.getLevel(),b=[];void 0!==n&&(u.title=n),u.span&&!1!==h._enableUpdate&&(a=p&&!1!==u.hasChildren()?" role='button'":"",g<f.minExpandLevel?(u.lazy||(u.expanded=!0),g>1&&b.push("<span "+a+" class='fancytree-expander fancytree-expander-fixed'></span>")):b.push("<span "+a+" class='fancytree-expander'></span>"),(i=v.evalOption("checkbox",u,u,f,!1))&&!u.isStatusNode()&&(a=p?" role='checkbox'":"",r="fancytree-checkbox",("radio"===i||u.parent&&u.parent.radiogroup)&&(r+=" fancytree-radio"),b.push("<span "+a+" class='"+r+"'></span>")),void 0!==u.data.iconClass&&(u.icon?e.error("'iconClass' node option is deprecated since v2.14.0: use 'icon' only instead"):(u.warn("'iconClass' node option is deprecated since v2.14.0: use 'icon' instead"),u.icon=u.data.iconClass)),!1!==(o=v.evalOption("icon",u,u,f,!0))&&(a=p?" role='presentation'":"",d=(d=v.evalOption("iconTooltip",u,u,f,null))?" title='"+c(d)+"'":"","string"==typeof o?y.test(o)?(o="/"===o.charAt(0)?o:(f.imagePath||"")+o,b.push("<img src='"+o+"' class='fancytree-icon'"+d+" alt='' />")):b.push("<span "+a+" class='fancytree-custom-icon "+o+"'"+d+"></span>"):o.text?b.push("<span "+a+" class='fancytree-custom-icon "+(o.addClass||"")+"'"+d+">"+v.escapeHtml(o.text)+"</span>"):o.html?b.push("<span "+a+" class='fancytree-custom-icon "+(o.addClass||"")+"'"+d+">"+o.html+"</span>"):b.push("<span "+a+" class='fancytree-icon'"+d+"></span>")),s="",f.renderTitle&&(s=f.renderTitle.call(h,{type:"renderTitle"},t)||""),s||(!0===(l=v.evalOption("tooltip",u,u,f,null))&&(l=u.title),s="<span class='fancytree-title'"+(l=l?" title='"+c(l)+"'":"")+(f.titlesTabbable?" tabindex='0'":"")+">"+(f.escapeTitles?v.escapeHtml(u.title):u.title)+"</span>"),b.push(s),u.span.innerHTML=b.join(""),this.nodeRenderStatus(t),f.enhanceTitle&&(t.$title=e(">span.fancytree-title",u.span),s=f.enhanceTitle.call(h,{type:"enhanceTitle"},t)||""))},nodeRenderStatus:function(t){var n,i=t.node,r=t.tree,o=t.options,s=i.hasChildren(),a=i.isLastSibling(),l=o.aria,d=o._classNames,c=[],u=i[r.statusClassPropName];u&&!1!==r._enableUpdate&&(l&&(n=e(i.tr||i.li)),c.push(d.node),r.activeNode===i&&c.push(d.active),r.focusNode===i&&c.push(d.focused),i.expanded&&c.push(d.expanded),l&&(!1!==s?n.attr("aria-expanded",Boolean(i.expanded)):n.removeAttr("aria-expanded")),i.folder&&c.push(d.folder),!1!==s&&c.push(d.hasChildren),a&&c.push(d.lastsib),i.lazy&&null==i.children&&c.push(d.lazy),i.partload&&c.push(d.partload),i.partsel&&c.push(d.partsel),v.evalOption("unselectable",i,i,o,!1)&&c.push(d.unselectable),i._isLoading&&c.push(d.loading),i._error&&c.push(d.error),i.statusNodeType&&c.push(d.statusNodePrefix+i.statusNodeType),i.selected?(c.push(d.selected),l&&n.attr("aria-selected",!0)):l&&n.attr("aria-selected",!1),i.extraClasses&&c.push(i.extraClasses),!1===s?c.push(d.combinedExpanderPrefix+"n"+(a?"l":"")):c.push(d.combinedExpanderPrefix+(i.expanded?"e":"c")+(i.lazy&&null==i.children?"d":"")+(a?"l":"")),c.push(d.combinedIconPrefix+(i.expanded?"e":"c")+(i.folder?"f":"")),u.className=c.join(" "),i.li&&e(i.li).toggleClass(d.lastsib,a))},nodeSetActive:function(n,i,r){r=r||{};var o,l=n.node,d=n.tree,c=n.options,u=!0===r.noEvents,h=!0===r.noFocus,f=!1!==r.scrollIntoView;return i=!1!==i,l===d.activeNode===i?s(l):i&&!u&&!1===this._triggerNodeEvent("beforeActivate",l,n.originalEvent)?a(l,["rejected"]):(i?(d.activeNode&&(t(d.activeNode!==l,"node was active (inconsistency)"),o=e.extend({},n,{node:d.activeNode}),d.nodeSetActive(o,!1),t(null===d.activeNode,"deactivate was out of sync?")),c.activeVisible&&l.makeVisible({scrollIntoView:f}),d.activeNode=l,d.nodeRenderStatus(n),h||d.nodeSetFocus(n),u||d._triggerNodeEvent("activate",l,n.originalEvent)):(t(d.activeNode===l,"node was not active (inconsistency)"),d.activeNode=null,this.nodeRenderStatus(n),u||n.tree._triggerNodeEvent("deactivate",l,n.originalEvent)),s(l))},nodeSetExpanded:function(t,n,i){i=i||{};var r,o,l,d,c,u,h=t.node,f=t.tree,p=t.options,g=!0===i.noAnimation,v=!0===i.noEvents;if(n=!1!==n,h.expanded&&n||!h.expanded&&!n)return s(h);if(n&&!h.lazy&&!h.hasChildren())return s(h);if(!n&&h.getLevel()<p.minExpandLevel)return a(h,["locked"]);if(!v&&!1===this._triggerNodeEvent("beforeExpand",h,t.originalEvent))return a(h,["rejected"]);if(g||h.isVisible()||(g=i.noAnimation=!0),o=new e.Deferred,n&&!h.expanded&&p.autoCollapse){c=h.getParentList(!1,!0),u=p.autoCollapse;try{for(p.autoCollapse=!1,l=0,d=c.length;l<d;l++)this._callHook("nodeCollapseSiblings",c[l],i)}finally{p.autoCollapse=u}}return o.done(function(){var e=h.getLastChild();n&&p.autoScroll&&!g&&e?e.scrollIntoView(!0,{topNode:h}).always(function(){v||t.tree._triggerNodeEvent(n?"expand":"collapse",t)}):v||t.tree._triggerNodeEvent(n?"expand":"collapse",t)}),r=function(i){var r,o,s=p._classNames,a=p.toggleEffect;if(h.expanded=n,f._callHook("nodeRender",t,!1,!1,!0),h.ul)if(r="none"!==h.ul.style.display,o=!!h.expanded,r===o)h.warn("nodeSetExpanded: UL.style.display already set");else{if(a&&!g)return e(h.li).addClass(s.animating),void(e.isFunction(e(h.ul)[a.effect])?(f.debug("use jquery."+a.effect+" method"),e(h.ul)[a.effect]({duration:a.duration,always:function(){e(this).removeClass(s.animating),e(h.li).removeClass(s.animating),i()}})):(e(h.ul).stop(!0,!0),e(h.ul).parent().find(".ui-effects-placeholder").remove(),e(h.ul).toggle(a.effect,a.options,a.duration,function(){e(this).removeClass(s.animating),e(h.li).removeClass(s.animating),i()})));h.ul.style.display=h.expanded||!parent?"":"none"}i()},n&&h.lazy&&void 0===h.hasChildren()?h.load().done(function(){o.notifyWith&&o.notifyWith(h,["loaded"]),r(function(){o.resolveWith(h)})}).fail(function(e){r(function(){o.rejectWith(h,["load failed ("+e+")"])})}):r(function(){o.resolveWith(h)}),o.promise()},nodeSetFocus:function(t,n){var i,r=t.tree,o=t.node,s=r.options,a=!!t.originalEvent&&e(t.originalEvent.target).is(":input");if(n=!1!==n,r.focusNode){if(r.focusNode===o&&n)return;i=e.extend({},t,{node:r.focusNode}),r.focusNode=null,this._triggerNodeEvent("blur",i),this._callHook("nodeRenderStatus",i)}n&&(this.hasFocus()||(o.debug("nodeSetFocus: forcing container focus"),this._callHook("treeSetFocus",t,!0,{calledByNode:!0})),o.makeVisible({scrollIntoView:!1}),r.focusNode=o,s.titlesTabbable?a||e(o.span).find(".fancytree-title").focus():0===e(document.activeElement).closest(".fancytree-container").length&&e(r.$container).focus(),s.aria&&e(r.$container).attr("aria-activedescendant",e(o.tr||o.li).uniqueId().attr("id")),this._triggerNodeEvent("focus",t),s.autoScroll&&o.scrollIntoView(),this._callHook("nodeRenderStatus",t))},nodeSetSelected:function(e,t,n){n=n||{};var i=e.node,r=e.tree,o=e.options,s=!0===n.noEvents,a=i.parent;if(t=!1!==t,!v.evalOption("unselectable",i,i,o,!1)){if(i._lastSelectIntent=t,!!i.selected===t&&(3!==o.selectMode||!i.partsel||t))return t;if(!s&&!1===this._triggerNodeEvent("beforeSelect",i,e.originalEvent))return!!i.selected;t&&1===o.selectMode?(r.lastSelectedNode&&r.lastSelectedNode.setSelected(!1),i.selected=t):3!==o.selectMode||!a||a.radiogroup||i.radiogroup?a&&a.radiogroup?i.visitSiblings(function(e){e._changeSelectStatusAttrs(t&&e===i)},!0):i.selected=t:(i.selected=t,i.fixSelection3AfterClick(n)),this.nodeRenderStatus(e),r.lastSelectedNode=t?i:null,s||r._triggerNodeEvent("select",e)}},nodeSetStatus:function(t,n,i,r){function o(t,n){var i=s.children?s.children[0]:null;return i&&i.isStatusNode()?(e.extend(i,t),i.statusNodeType=n,a._callHook("nodeRenderTitle",i)):(s._setChildren([t]),s.children[0].statusNodeType=n,a.render()),s.children[0]}var s=t.node,a=t.tree;switch(n){case"ok":!function(){var e=s.children?s.children[0]:null;if(e&&e.isStatusNode()){try{s.ul&&(s.ul.removeChild(e.li),e.li=null)}catch(e){}1===s.children.length?s.children=[]:s.children.shift()}}(),s._isLoading=!1,s._error=null,s.renderStatus();break;case"loading":s.parent||o({title:a.options.strings.loading+(i?" ("+i+")":""),checkbox:!1,tooltip:r},n),s._isLoading=!0,s._error=null,s.renderStatus();break;case"error":o({title:a.options.strings.loadError+(i?" ("+i+")":""),checkbox:!1,tooltip:r},n),s._isLoading=!1,s._error={message:i,details:r},s.renderStatus();break;case"nodata":o({title:i||a.options.strings.noData,checkbox:!1,tooltip:r},n),s._isLoading=!1,s._error=null,s.renderStatus();break;default:e.error("invalid node status "+n)}},nodeToggleExpanded:function(e){return this.nodeSetExpanded(e,!e.node.expanded)},nodeToggleSelected:function(e){var t=e.node,n=!t.selected;return t.partsel&&!t.selected&&!0===t._lastSelectIntent&&(n=!1,t.selected=!0),t._lastSelectIntent=n,this.nodeSetSelected(e,n)},treeClear:function(e){var t=e.tree;t.activeNode=null,t.focusNode=null,t.$div.find(">ul.fancytree-container").empty(),t.rootNode.children=null},treeCreate:function(e){},treeDestroy:function(e){this.$div.find(">ul.fancytree-container").remove(),this.$source&&this.$source.removeClass("fancytree-helper-hidden")},treeInit:function(t){var n=t.tree,i=n.options;n.$container.attr("tabindex",i.tabindex),e.each(T,function(e,t){void 0!==i[t]&&(n.info("Move option "+t+" to tree"),n[t]=i[t],delete i[t])}),i.rtl?n.$container.attr("DIR","RTL").addClass("fancytree-rtl"):n.$container.removeAttr("DIR").removeClass("fancytree-rtl"),i.aria&&(n.$container.attr("role","tree"),1!==i.selectMode&&n.$container.attr("aria-multiselectable",!0)),this.treeLoad(t)},treeLoad:function(n,i){var r,o,s,a=n.tree,l=n.widget.element,c=e.extend({},n,{node:this.rootNode});if(a.rootNode.children&&this.treeClear(n),i=i||this.options.source)"string"==typeof i&&e.error("Not implemented");else switch(o=l.data("type")||"html"){case"html":(s=l.find(">ul:first")).addClass("ui-fancytree-source fancytree-helper-hidden"),i=e.ui.fancytree.parseHtml(s),this.data=e.extend(this.data,d(s));break;case"json":i=e.parseJSON(l.text()),l.contents().filter(function(){return 3===this.nodeType}).remove(),e.isPlainObject(i)&&(t(e.isArray(i.children),"if an object is passed as source, it must contain a 'children' array (all other properties are added to 'tree.data')"),r=i,i=i.children,delete r.children,e.each(T,function(e,t){void 0!==r[t]&&(a[t]=r[t],delete r[t])}),e.extend(a.data,r));break;default:e.error("Invalid data-type: "+o)}return this.nodeLoadChildren(c,i).done(function(){a.render(),3===n.options.selectMode&&a.rootNode.fixSelection3FromEndNodes(),a.activeNode&&a.options.activeVisible&&a.activeNode.makeVisible(),a._triggerTreeEvent("init",null,{status:!0})}).fail(function(){a.render(),a._triggerTreeEvent("init",null,{status:!1})})},treeRegisterNode:function(e,t,n){},treeSetFocus:function(t,n,i){var r;(n=!1!==n)!==this.hasFocus()&&(this._hasFocus=n,!n&&this.focusNode?this.focusNode.setFocus(!1):!n||i&&i.calledByNode||e(this.$container).focus(),this.$container.toggleClass("fancytree-treefocus",n),this._triggerTreeEvent(n?"focusTree":"blurTree"),n&&!this.activeNode&&(r=this._lastMousedownNode||this.getFirstChild())&&r.setFocus())},treeSetOption:function(t,n,i){var r=t.tree,o=!0,s=!1,a=!1;switch(n){case"aria":case"checkbox":case"icon":case"minExpandLevel":case"tabindex":s=!0,a=!0;break;case"escapeTitles":case"tooltip":a=!0;break;case"rtl":!1===i?r.$container.removeAttr("DIR").removeClass("fancytree-rtl"):r.$container.attr("DIR","RTL").addClass("fancytree-rtl"),a=!0;break;case"source":o=!1,r._callHook("treeLoad",r,i),a=!0}r.debug("set option "+n+"="+i+" <"+typeof i+">"),o&&(this.widget._super?this.widget._super.call(this.widget,n,i):e.Widget.prototype._setOption.call(this.widget,n,i)),s&&r._callHook("treeCreate",r),a&&r.render(!0,!1)}}),e.widget("ui.fancytree",{options:{activeVisible:!0,ajax:{type:"GET",cache:!1,dataType:"json"},aria:!0,autoActivate:!0,autoCollapse:!1,autoScroll:!1,checkbox:!1,clickFolderMode:4,debugLevel:null,disabled:!1,enableAspx:!0,escapeTitles:!1,extensions:[],toggleEffect:{effect:"slideToggle",duration:200},generateIds:!1,icon:!0,idPrefix:"ft_",focusOnSelect:!1,keyboard:!0,keyPathSeparator:"/",minExpandLevel:1,nodata:!0,quicksearch:!1,rtl:!1,scrollOfs:{top:0,bottom:0},scrollParent:null,selectMode:2,strings:{loading:"Loading...",loadError:"Load error!",moreData:"More...",noData:"No data."},tabindex:"0",titlesTabbable:!1,tooltip:!1,_classNames:{node:"fancytree-node",folder:"fancytree-folder",animating:"fancytree-animating",combinedExpanderPrefix:"fancytree-exp-",combinedIconPrefix:"fancytree-ico-",hasChildren:"fancytree-has-children",active:"fancytree-active",selected:"fancytree-selected",expanded:"fancytree-expanded",lazy:"fancytree-lazy",focused:"fancytree-focused",partload:"fancytree-partload",partsel:"fancytree-partsel",radio:"fancytree-radio",unselectable:"fancytree-unselectable",lastsib:"fancytree-lastsib",loading:"fancytree-loading",error:"fancytree-error",statusNodePrefix:"fancytree-statusnode-"},lazyLoad:null,postProcess:null},_create:function(){this.tree=new f(this),this.$source=this.source||"json"===this.element.data("type")?this.element:this.element.find(">ul:first");var n,r,s,a=this.options,l=a.extensions;this.tree;for(s=0;s<l.length;s++)r=l[s],(n=e.ui.fancytree._extensions[r])||e.error("Could not apply extension '"+r+"' (it is not registered, did you forget to include it?)"),this.tree.options[r]=i({},n.options,this.tree.options[r]),t(void 0===this.tree.ext[r],"Extension name must not exist as Fancytree.ext attribute: '"+r+"'"),this.tree.ext[r]={},o(this.tree,0,n,r),n;void 0!==a.icons&&(!0!==a.icon?e.error("'icons' tree option is deprecated since v2.14.0: use 'icon' only instead"):(this.tree.warn("'icons' tree option is deprecated since v2.14.0: use 'icon' instead"),a.icon=a.icons)),void 0!==a.iconClass&&(a.icon?e.error("'iconClass' tree option is deprecated since v2.14.0: use 'icon' only instead"):(this.tree.warn("'iconClass' tree option is deprecated since v2.14.0: use 'icon' instead"),a.icon=a.iconClass)),void 0!==a.tabbable&&(a.tabindex=a.tabbable?"0":"-1",this.tree.warn("'tabbable' tree option is deprecated since v2.17.0: use 'tabindex='"+a.tabindex+"' instead")),this.tree._callHook("treeCreate",this.tree)},_init:function(){this.tree._callHook("treeInit",this.tree),this._bind()},_setOption:function(e,t){return this.tree._callHook("treeSetOption",this.tree,e,t)},destroy:function(){this._unbind(),this.tree._callHook("treeDestroy",this.tree),e.Widget.prototype.destroy.call(this)},_unbind:function(){var t=this.tree._ns;this.element.off(t),this.tree.$container.off(t),e(document).off(t)},_bind:function(){var t=this,n=this.options,i=this.tree,r=i._ns;this._unbind(),i.$container.on("focusin"+r+" focusout"+r,function(t){var n=v.getNode(t),r="focusin"===t.type;if(!r&&n&&e(t.target).is("a"))n.debug("Ignored focusout on embedded <a> element.");else{if(r){if(i._getExpiringValue("focusin"))return void i.debug("Ignored double focusin.");i._setExpiringValue("focusin",!0,50),n||(n=i._getExpiringValue("mouseDownNode"))&&i.debug("Reconstruct mouse target for focusin from recent event.")}n?i._callHook("nodeSetFocus",i._makeHookContext(n,t),r):i.tbody&&e(t.target).parents("table.fancytree-container > thead").length?i.debug("Ignore focus event outside table body.",t):i._callHook("treeSetFocus",i,r)}}).on("selectstart"+r,"span.fancytree-title",function(e){e.preventDefault()}).on("keydown"+r,function(e){if(n.disabled||!1===n.keyboard)return!0;var t,r=i.focusNode,o=i._makeHookContext(r||i,e),s=i.phase;try{return i.phase="userEvent","preventNav"===(t=r?i._triggerNodeEvent("keydown",r,e):i._triggerTreeEvent("keydown",e))?t=!0:!1!==t&&(t=i._callHook("nodeKeydown",o)),t}finally{i.phase=s}}).on("mousedown"+r,function(e){var t=v.getEventTarget(e);i._lastMousedownNode=t?t.node:null,i._setExpiringValue("mouseDownNode",i._lastMousedownNode)}).on("click"+r+" dblclick"+r,function(e){if(n.disabled)return!0;var i,r=v.getEventTarget(e),o=r.node,s=t.tree,a=s.phase;if(!o)return!0;i=s._makeHookContext(o,e);try{switch(s.phase="userEvent",e.type){case"click":return i.targetType=r.type,o.isPagingNode()?!0===s._triggerNodeEvent("clickPaging",i,e):!1!==s._triggerNodeEvent("click",i,e)&&s._callHook("nodeClick",i);case"dblclick":return i.targetType=r.type,!1!==s._triggerNodeEvent("dblclick",i,e)&&s._callHook("nodeDblclick",i)}}finally{s.phase=a}})},getActiveNode:function(){return this.tree.activeNode},getNodeByKey:function(e){return this.tree.getNodeByKey(e)},getRootNode:function(){return this.tree.rootNode},getTree:function(){return this.tree}}),v=e.ui.fancytree,e.extend(e.ui.fancytree,{version:"2.30.1",buildType: "production",debugLevel: 3,_nextId:1,_nextNodeKey:1,_extensions:{},_FancytreeClass:f,_FancytreeNodeClass:h,jquerySupports:{positionMyOfs:function(t,n,i,r){var o,s,a,l=e.map(e.trim(t).split("."),function(e){return parseInt(e,10)}),d=e.map(Array.prototype.slice.call(arguments,1),function(e){return parseInt(e,10)});for(o=0;o<d.length;o++)if(s=l[o]||0,a=d[o]||0,s!==a)return s>a;return!0}(e.ui.version,1,9)},assert:function(e,n){return t(e,n)},createTree:function(t,n){return e(t).fancytree(n).fancytree("getTree")},debounce:function(e,t,n,i){var r;return 3===arguments.length&&"boolean"!=typeof n&&(i=n,n=!1),function(){var o=arguments;i=i||this,n&&!r&&t.apply(i,o),clearTimeout(r),r=setTimeout(function(){n||t.apply(i,o),r=null},e)}},debug:function(t){e.ui.fancytree.debugLevel>=4&&n("log",arguments)},error:function(t){e.ui.fancytree.debugLevel>=1&&n("error",arguments)},escapeHtml:function(e){return(""+e).replace(b,function(e){return k[e]})},fixPositionOptions:function(t){if((t.offset||(""+t.my+t.at).indexOf("%")>=0)&&e.error("expected new position syntax (but '%' is not supported)"),!e.ui.fancytree.jquerySupports.positionMyOfs){var n=/(\w+)([+-]?\d+)?\s+(\w+)([+-]?\d+)?/.exec(t.my),i=/(\w+)([+-]?\d+)?\s+(\w+)([+-]?\d+)?/.exec(t.at),r=(n[2]?+n[2]:0)+(i[2]?+i[2]:0),o=(n[4]?+n[4]:0)+(i[4]?+i[4]:0);t=e.extend({},t,{my:n[1]+" "+n[3],at:i[1]+" "+i[3]}),(r||o)&&(t.offset=r+" "+o)}return t},getEventTarget:function(t){var n,i=t&&t.target?t.target.className:"",r={node:this.getNode(t.target),type:void 0};return/\bfancytree-title\b/.test(i)?r.type="title":/\bfancytree-expander\b/.test(i)?r.type=!1===r.node.hasChildren()?"prefix":"expander":/\bfancytree-checkbox\b/.test(i)?r.type="checkbox":/\bfancytree(-custom)?-icon\b/.test(i)?r.type="icon":/\bfancytree-node\b/.test(i)?r.type="title":t&&t.target&&((n=e(t.target)).is("ul[role=group]")?((r.node&&r.node.tree||v).debug("Ignoring click on outer UL."),r.node=null):n.closest(".fancytree-title").length?r.type="title":n.closest(".fancytree-checkbox").length?r.type="checkbox":n.closest(".fancytree-expander").length&&(r.type="expander")),r},getEventTargetType:function(e){return this.getEventTarget(e).type},getNode:function(t){if(t instanceof h)return t;for(t instanceof e?t=t[0]:void 0!==t.originalEvent&&(t=t.target);t;){if(t.ftnode)return t.ftnode;t=t.parentNode}return null},getTree:function(t){var n;return t instanceof f?t:(void 0===t&&(t=0),"number"==typeof t?t=e(".fancytree-container").eq(t):"string"==typeof t?t=e(t).eq(0):void 0!==t.selector?t=t.eq(0):void 0!==t.originalEvent&&(t=e(t.target)),t=t.closest(":ui-fancytree"),(n=t.data("ui-fancytree")||t.data("fancytree"))?n.tree:null)},evalOption:function(t,n,i,r,o){var s,a,l=n.tree,d=r[t],c=i[t];return e.isFunction(d)?(s={node:n,tree:l,widget:l.widget,options:l.widget.options,typeInfo:l.types[n.type]||{}},null==(a=d.call(l,{type:t},s))&&(a=c)):a=null!=c?c:d,null==a&&(a=o),a},setSpanIcon:function(t,n,i){var r=e(t);"string"==typeof i?r.attr("class",n+" "+i):(i.text?r.text(""+i.text):i.html&&(t.innerHTML=i.html),r.attr("class",n+" "+(i.addClass||"")))},eventToString:function(e){var t=e.which,n=e.type,i=[];return e.altKey&&i.push("alt"),e.ctrlKey&&i.push("ctrl"),e.metaKey&&i.push("meta"),e.shiftKey&&i.push("shift"),"click"===n||"dblclick"===n?i.push(w[e.button]+n):N[t]||i.push(_[t]||String.fromCharCode(t).toLowerCase()),i.join("+")},info:function(t){e.ui.fancytree.debugLevel>=3&&n("info",arguments)},keyEventToString:function(e){return this.warn("keyEventToString() is deprecated: use eventToString()"),this.eventToString(e)},overrideMethod:function(t,n,i,r){var o,s=t[n]||e.noop;r=r||this,t[n]=function(){try{return o=r._super,r._super=s,i.apply(r,arguments)}finally{r._super=o}}},parseHtml:function(t){var n,i,r,o,s,a,l,c,u=[];return t.find(">li").each(function(){var h,f,p=e(this),g=p.find(">span:first",this),v=g.length?null:p.find(">a:first"),y={tooltip:null,data:{}};for(g.length?y.title=g.html():v&&v.length?(y.title=v.html(),y.data.href=v.attr("href"),y.data.target=v.attr("target"),y.tooltip=v.attr("title")):(y.title=p.html(),(s=y.title.search(/<ul/i))>=0&&(y.title=y.title.substring(0,s))),y.title=e.trim(y.title),o=0,a=S.length;o<a;o++)y[S[o]]=void 0;for(n=this.className.split(" "),r=[],o=0,a=n.length;o<a;o++)i=n[o],E[i]?y[i]=!0:r.push(i);if(y.extraClasses=r.join(" "),(l=p.attr("title"))&&(y.tooltip=l),(l=p.attr("id"))&&(y.key=l),p.attr("hideCheckbox")&&(y.checkbox=!1),(h=d(p))&&!e.isEmptyObject(h)){for(f in P)h.hasOwnProperty(f)&&(h[P[f]]=h[f],delete h[f]);for(o=0,a=L.length;o<a;o++)l=L[o],null!=(c=h[l])&&(delete h[l],y[l]=c);e.extend(y.data,h)}(t=p.find(">ul:first")).length?y.children=e.ui.fancytree.parseHtml(t):y.children=y.lazy?void 0:null,u.push(y)}),u},registerExtension:function(n){t(null!=n.name,"extensions must have a `name` property."),t(null!=n.version,"extensions must have a `version` property."),e.ui.fancytree._extensions[n.name]=n},unescapeHtml:function(e){var t=document.createElement("div");return t.innerHTML=e,0===t.childNodes.length?"":t.childNodes[0].nodeValue},warn:function(t){e.ui.fancytree.debugLevel>=2&&n("warn",arguments)}}),e.ui.fancytree}e.ui.fancytree.warn("Fancytree: ignored duplicate include")}});;
/*!
 * jquery.fancytree.glyph.js
 *
 * Use glyph-fonts, ligature-fonts, or SVG icons instead of icon sprites.
 * (Extension module for jquery.fancytree.js: https://github.com/mar10/fancytree/)
 *
 * Copyright (c) 2008-2018, Martin Wendt (http://wwWendt.de)
 *
 * Released under the MIT license
 * https://github.com/mar10/fancytree/wiki/LicenseInfo
 *
 * @version 2.30.1
 * @date 2018-11-13T18:58:18Z
 */

(function(factory) {
	if (typeof define === "function" && define.amd) {
		// AMD. Register as an anonymous module.
		define(["jquery", "./jquery.fancytree"], factory);
	} else if (typeof module === "object" && module.exports) {
		// Node/CommonJS
		require("./jquery.fancytree");
		module.exports = factory(require("jquery"));
	} else {
		// Browser globals
		factory(jQuery);
	}
})(function($) {
	"use strict";

	/******************************************************************************
	 * Private functions and variables
	 */

	var FT = $.ui.fancytree,
		PRESETS = {
			awesome3: {
				// Outdated!
				_addClass: "",
				checkbox: "icon-check-empty",
				checkboxSelected: "icon-check",
				checkboxUnknown: "icon-check icon-muted",
				dragHelper: "icon-caret-right",
				dropMarker: "icon-caret-right",
				error: "icon-exclamation-sign",
				expanderClosed: "icon-caret-right",
				expanderLazy: "icon-angle-right",
				expanderOpen: "icon-caret-down",
				loading: "icon-refresh icon-spin",
				nodata: "icon-meh",
				noExpander: "",
				radio: "icon-circle-blank",
				radioSelected: "icon-circle",
				// radioUnknown: "icon-circle icon-muted",
				// Default node icons.
				// (Use tree.options.icon callback to define custom icons based on node data)
				doc: "icon-file-alt",
				docOpen: "icon-file-alt",
				folder: "icon-folder-close-alt",
				folderOpen: "icon-folder-open-alt",
			},
			awesome4: {
				_addClass: "fa",
				checkbox: "fa-square-o",
				checkboxSelected: "fa-check-square-o",
				checkboxUnknown: "fa-square fancytree-helper-indeterminate-cb",
				dragHelper: "fa-arrow-right",
				dropMarker: "fa-long-arrow-right",
				error: "fa-warning",
				expanderClosed: "fa-caret-right",
				expanderLazy: "fa-angle-right",
				expanderOpen: "fa-caret-down",
				// We may prevent wobbling rotations on FF by creating a separate sub element:
				loading: { html: "<span class='fa fa-spinner fa-pulse' />" },
				nodata: "fa-meh-o",
				noExpander: "",
				radio: "fa-circle-thin", // "fa-circle-o"
				radioSelected: "fa-circle",
				// radioUnknown: "fa-dot-circle-o",
				// Default node icons.
				// (Use tree.options.icon callback to define custom icons based on node data)
				doc: "fa-file-o",
				docOpen: "fa-file-o",
				folder: "fa-folder-o",
				folderOpen: "fa-folder-open-o",
			},
			awesome5: {
				// fontawesome 5 have several different base classes
				// "far, fas, fal and fab" The rendered svg puts that prefix
				// in a different location so we have to keep them separate here
				_addClass: "",
				checkbox: "far fa-square",
				checkboxSelected: "far fa-check-square",
				// checkboxUnknown: "far fa-window-close",
				checkboxUnknown:
					"fas fa-square fancytree-helper-indeterminate-cb",
				radio: "far fa-circle",
				radioSelected: "fas fa-circle",
				radioUnknown: "far fa-dot-circle",
				dragHelper: "fas fa-arrow-right",
				dropMarker: "fas fa-long-arrow-right",
				error: "fas fa-exclamation-triangle",
				expanderClosed: "fas fa-caret-right",
				expanderLazy: "fas fa-angle-right",
				expanderOpen: "fas fa-caret-down",
				loading: "fas fa-spinner fa-pulse",
				nodata: "far fa-meh",
				noExpander: "",
				// Default node icons.
				// (Use tree.options.icon callback to define custom icons based on node data)
				doc: "far fa-file",
				docOpen: "far fa-file",
				folder: "far fa-folder",
				folderOpen: "far fa-folder-open",
			},
			bootstrap3: {
				_addClass: "glyphicon",
				checkbox: "glyphicon-unchecked",
				checkboxSelected: "glyphicon-check",
				checkboxUnknown:
					"glyphicon-expand fancytree-helper-indeterminate-cb", // "glyphicon-share",
				dragHelper: "glyphicon-play",
				dropMarker: "glyphicon-arrow-right",
				error: "glyphicon-warning-sign",
				expanderClosed: "glyphicon-menu-right", // glyphicon-plus-sign
				expanderLazy: "glyphicon-menu-right", // glyphicon-plus-sign
				expanderOpen: "glyphicon-menu-down", // glyphicon-minus-sign
				loading: "glyphicon-refresh fancytree-helper-spin",
				nodata: "glyphicon-info-sign",
				noExpander: "",
				radio: "glyphicon-remove-circle", // "glyphicon-unchecked",
				radioSelected: "glyphicon-ok-circle", // "glyphicon-check",
				// radioUnknown: "glyphicon-ban-circle",
				// Default node icons.
				// (Use tree.options.icon callback to define custom icons based on node data)
				doc: "glyphicon-file",
				docOpen: "glyphicon-file",
				folder: "glyphicon-folder-close",
				folderOpen: "glyphicon-folder-open",
			},
			material: {
				_addClass: "material-icons",
				checkbox: { text: "check_box_outline_blank" },
				checkboxSelected: { text: "check_box" },
				checkboxUnknown: { text: "indeterminate_check_box" },
				dragHelper: { text: "play_arrow" },
				dropMarker: { text: "arrow-forward" },
				error: { text: "warning" },
				expanderClosed: { text: "chevron_right" },
				expanderLazy: { text: "last_page" },
				expanderOpen: { text: "expand_more" },
				loading: {
					text: "autorenew",
					addClass: "fancytree-helper-spin",
				},
				nodata: { text: "info" },
				noExpander: { text: "" },
				radio: { text: "radio_button_unchecked" },
				radioSelected: { text: "radio_button_checked" },
				// Default node icons.
				// (Use tree.options.icon callback to define custom icons based on node data)
				doc: { text: "insert_drive_file" },
				docOpen: { text: "insert_drive_file" },
				folder: { text: "folder" },
				folderOpen: { text: "folder_open" },
			},
		};

	function setIcon(span, baseClass, opts, type) {
		var map = opts.map,
			icon = map[type],
			$span = $(span),
			setClass = baseClass + " " + (map._addClass || "");

		if (typeof icon === "string") {
			// #883: remove inner html that may be added by prev. mode
			span.innerHTML = "";
			$span.attr("class", setClass + " " + icon);
		} else if (icon) {
			if (icon.text) {
				span.textContent = "" + icon.text;
			} else if (icon.html) {
				span.innerHTML = icon.html;
			} else {
				span.innerHTML = "";
			}
			$span.attr("class", setClass + " " + (icon.addClass || ""));
		}
	}

	$.ui.fancytree.registerExtension({
		name: "glyph",
		version: "2.30.1",
		// Default options for this extension.
		options: {
			preset: null, // 'awesome3', 'awesome4', 'bootstrap3', 'material'
			map: {},
		},

		treeInit: function(ctx) {
			var tree = ctx.tree,
				opts = ctx.options.glyph;

			if (opts.preset) {
				FT.assert(
					!!PRESETS[opts.preset],
					"Invalid value for `options.glyph.preset`: " + opts.preset
				);
				opts.map = $.extend({}, PRESETS[opts.preset], opts.map);
			} else {
				tree.warn("ext-glyph: missing `preset` option.");
			}
			this._superApply(arguments);
			tree.$container.addClass("fancytree-ext-glyph");
		},
		nodeRenderStatus: function(ctx) {
			var checkbox,
				icon,
				res,
				span,
				node = ctx.node,
				$span = $(node.span),
				opts = ctx.options.glyph;

			res = this._super(ctx);

			if (node.isRoot()) {
				return res;
			}
			span = $span.children("span.fancytree-expander").get(0);
			if (span) {
				// if( node.isLoading() ){
				// icon = "loading";
				if (node.expanded && node.hasChildren()) {
					icon = "expanderOpen";
				} else if (node.isUndefined()) {
					icon = "expanderLazy";
				} else if (node.hasChildren()) {
					icon = "expanderClosed";
				} else {
					icon = "noExpander";
				}
				// span.className = "fancytree-expander " + map[icon];
				setIcon(span, "fancytree-expander", opts, icon);
			}

			if (node.tr) {
				span = $("td", node.tr)
					.find("span.fancytree-checkbox")
					.get(0);
			} else {
				span = $span.children("span.fancytree-checkbox").get(0);
			}
			if (span) {
				checkbox = FT.evalOption("checkbox", node, node, opts, false);
				if (
					(node.parent && node.parent.radiogroup) ||
					checkbox === "radio"
				) {
					icon = node.selected ? "radioSelected" : "radio";
					setIcon(
						span,
						"fancytree-checkbox fancytree-radio",
						opts,
						icon
					);
				} else {
					icon = node.selected
						? "checkboxSelected"
						: node.partsel
							? "checkboxUnknown"
							: "checkbox";
					// span.className = "fancytree-checkbox " + map[icon];
					setIcon(span, "fancytree-checkbox", opts, icon);
				}
			}

			// Standard icon (note that this does not match .fancytree-custom-icon,
			// that might be set by opts.icon callbacks)
			span = $span.children("span.fancytree-icon").get(0);
			if (span) {
				if (node.statusNodeType) {
					icon = node.statusNodeType; // loading, error
				} else if (node.folder) {
					icon =
						node.expanded && node.hasChildren()
							? "folderOpen"
							: "folder";
				} else {
					icon = node.expanded ? "docOpen" : "doc";
				}
				setIcon(span, "fancytree-icon", opts, icon);
			}
			return res;
		},
		nodeSetStatus: function(ctx, status, message, details) {
			var res,
				span,
				opts = ctx.options.glyph,
				node = ctx.node;

			res = this._superApply(arguments);

			if (
				status === "error" ||
				status === "loading" ||
				status === "nodata"
			) {
				if (node.parent) {
					span = $("span.fancytree-expander", node.span).get(0);
					if (span) {
						setIcon(span, "fancytree-expander", opts, status);
					}
				} else {
					//
					span = $(
						".fancytree-statusnode-" + status,
						node[this.nodeContainerAttrName]
					)
						.find("span.fancytree-icon")
						.get(0);
					if (span) {
						setIcon(span, "fancytree-icon", opts, status);
					}
				}
			}
			return res;
		},
	});
	// Value returned by `require('jquery.fancytree..')`
	return $.ui.fancytree;
}); // End of closure
;
/*!
 * jquery.fancytree.dnd.js
 *
 * Drag-and-drop support (jQuery UI draggable/droppable).
 * (Extension module for jquery.fancytree.js: https://github.com/mar10/fancytree/)
 *
 * Copyright (c) 2008-2018, Martin Wendt (http://wwWendt.de)
 *
 * Released under the MIT license
 * https://github.com/mar10/fancytree/wiki/LicenseInfo
 *
 * @version 2.30.1
 * @date 2018-11-13T18:58:18Z
 */

(function(factory) {
	if (typeof define === "function" && define.amd) {
		// AMD. Register as an anonymous module.
		define([
			"jquery",
			"jquery-ui/ui/widgets/draggable",
			"jquery-ui/ui/widgets/droppable",
			"./jquery.fancytree",
		], factory);
	} else if (typeof module === "object" && module.exports) {
		// Node/CommonJS
		require("./jquery.fancytree");
		module.exports = factory(require("jquery"));
	} else {
		// Browser globals
		factory(jQuery);
	}
})(function($) {
	"use strict";

	/******************************************************************************
	 * Private functions and variables
	 */
	var didRegisterDnd = false,
		classDropAccept = "fancytree-drop-accept",
		classDropAfter = "fancytree-drop-after",
		classDropBefore = "fancytree-drop-before",
		classDropOver = "fancytree-drop-over",
		classDropReject = "fancytree-drop-reject",
		classDropTarget = "fancytree-drop-target";

	/* Convert number to string and prepend +/-; return empty string for 0.*/
	function offsetString(n) {
		return n === 0 ? "" : n > 0 ? "+" + n : "" + n;
	}

	//--- Extend ui.draggable event handling --------------------------------------

	function _registerDnd() {
		if (didRegisterDnd) {
			return;
		}

		// Register proxy-functions for draggable.start/drag/stop

		$.ui.plugin.add("draggable", "connectToFancytree", {
			start: function(event, ui) {
				// 'draggable' was renamed to 'ui-draggable' since jQueryUI 1.10
				var draggable =
						$(this).data("ui-draggable") ||
						$(this).data("draggable"),
					sourceNode = ui.helper.data("ftSourceNode") || null;

				if (sourceNode) {
					// Adjust helper offset, so cursor is slightly outside top/left corner
					draggable.offset.click.top = -2;
					draggable.offset.click.left = +16;
					// Trigger dragStart event
					// TODO: when called as connectTo..., the return value is ignored(?)
					return sourceNode.tree.ext.dnd._onDragEvent(
						"start",
						sourceNode,
						null,
						event,
						ui,
						draggable
					);
				}
			},
			drag: function(event, ui) {
				var ctx,
					isHelper,
					logObject,
					// 'draggable' was renamed to 'ui-draggable' since jQueryUI 1.10
					draggable =
						$(this).data("ui-draggable") ||
						$(this).data("draggable"),
					sourceNode = ui.helper.data("ftSourceNode") || null,
					prevTargetNode = ui.helper.data("ftTargetNode") || null,
					targetNode = $.ui.fancytree.getNode(event.target),
					dndOpts = sourceNode && sourceNode.tree.options.dnd;

				// logObject = sourceNode || prevTargetNode || $.ui.fancytree;
				// logObject.debug("Drag event:", event, event.shiftKey);
				if (event.target && !targetNode) {
					// We got a drag event, but the targetNode could not be found
					// at the event location. This may happen,
					// 1. if the mouse jumped over the drag helper,
					// 2. or if a non-fancytree element is dragged
					// We ignore it:
					isHelper =
						$(event.target).closest(
							"div.fancytree-drag-helper,#fancytree-drop-marker"
						).length > 0;
					if (isHelper) {
						logObject =
							sourceNode || prevTargetNode || $.ui.fancytree;
						logObject.debug("Drag event over helper: ignored.");
						return;
					}
				}
				ui.helper.data("ftTargetNode", targetNode);

				if (dndOpts && dndOpts.updateHelper) {
					ctx = sourceNode.tree._makeHookContext(sourceNode, event, {
						otherNode: targetNode,
						ui: ui,
						draggable: draggable,
						dropMarker: $("#fancytree-drop-marker"),
					});
					dndOpts.updateHelper.call(sourceNode.tree, sourceNode, ctx);
				}

				// Leaving a tree node
				if (prevTargetNode && prevTargetNode !== targetNode) {
					prevTargetNode.tree.ext.dnd._onDragEvent(
						"leave",
						prevTargetNode,
						sourceNode,
						event,
						ui,
						draggable
					);
				}
				if (targetNode) {
					if (!targetNode.tree.options.dnd.dragDrop) {
						// not enabled as drop target
					} else if (targetNode === prevTargetNode) {
						// Moving over same node
						targetNode.tree.ext.dnd._onDragEvent(
							"over",
							targetNode,
							sourceNode,
							event,
							ui,
							draggable
						);
					} else {
						// Entering this node first time
						targetNode.tree.ext.dnd._onDragEvent(
							"enter",
							targetNode,
							sourceNode,
							event,
							ui,
							draggable
						);
						targetNode.tree.ext.dnd._onDragEvent(
							"over",
							targetNode,
							sourceNode,
							event,
							ui,
							draggable
						);
					}
				}
				// else go ahead with standard event handling
			},
			stop: function(event, ui) {
				var logObject,
					// 'draggable' was renamed to 'ui-draggable' since jQueryUI 1.10:
					draggable =
						$(this).data("ui-draggable") ||
						$(this).data("draggable"),
					sourceNode = ui.helper.data("ftSourceNode") || null,
					targetNode = ui.helper.data("ftTargetNode") || null,
					dropped = event.type === "mouseup" && event.which === 1;

				if (!dropped) {
					logObject = sourceNode || targetNode || $.ui.fancytree;
					logObject.debug("Drag was cancelled");
				}
				if (targetNode) {
					if (dropped) {
						targetNode.tree.ext.dnd._onDragEvent(
							"drop",
							targetNode,
							sourceNode,
							event,
							ui,
							draggable
						);
					}
					targetNode.tree.ext.dnd._onDragEvent(
						"leave",
						targetNode,
						sourceNode,
						event,
						ui,
						draggable
					);
				}
				if (sourceNode) {
					sourceNode.tree.ext.dnd._onDragEvent(
						"stop",
						sourceNode,
						null,
						event,
						ui,
						draggable
					);
				}
			},
		});

		didRegisterDnd = true;
	}

	/******************************************************************************
	 * Drag and drop support
	 */
	function _initDragAndDrop(tree) {
		var dnd = tree.options.dnd || null,
			glyph = tree.options.glyph || null;

		// Register 'connectToFancytree' option with ui.draggable
		if (dnd) {
			_registerDnd();
		}
		// Attach ui.draggable to this Fancytree instance
		if (dnd && dnd.dragStart) {
			tree.widget.element.draggable(
				$.extend(
					{
						addClasses: false,
						// DT issue 244: helper should be child of scrollParent:
						appendTo: tree.$container,
						//			appendTo: "body",
						containment: false,
						//			containment: "parent",
						delay: 0,
						distance: 4,
						revert: false,
						scroll: true, // to disable, also set css 'position: inherit' on ul.fancytree-container
						scrollSpeed: 7,
						scrollSensitivity: 10,
						// Delegate draggable.start, drag, and stop events to our handler
						connectToFancytree: true,
						// Let source tree create the helper element
						helper: function(event) {
							var $helper,
								$nodeTag,
								opts,
								sourceNode = $.ui.fancytree.getNode(
									event.target
								);

							if (!sourceNode) {
								// #405, DT issue 211: might happen, if dragging a table *header*
								return "<div>ERROR?: helper requested but sourceNode not found</div>";
							}
							opts = sourceNode.tree.options.dnd;
							$nodeTag = $(sourceNode.span);
							// Only event and node argument is available
							$helper = $(
								"<div class='fancytree-drag-helper'><span class='fancytree-drag-helper-img' /></div>"
							)
								.css({ zIndex: 3, position: "relative" }) // so it appears above ext-wide selection bar
								.append(
									$nodeTag
										.find("span.fancytree-title")
										.clone()
								);

							// Attach node reference to helper object
							$helper.data("ftSourceNode", sourceNode);

							// Support glyph symbols instead of icons
							if (glyph) {
								$helper
									.find(".fancytree-drag-helper-img")
									.addClass(
										glyph.map._addClass +
											" " +
											glyph.map.dragHelper
									);
							}
							// Allow to modify the helper, e.g. to add multi-node-drag feedback
							if (opts.initHelper) {
								opts.initHelper.call(
									sourceNode.tree,
									sourceNode,
									{
										node: sourceNode,
										tree: sourceNode.tree,
										originalEvent: event,
										ui: { helper: $helper },
									}
								);
							}
							// We return an unconnected element, so `draggable` will add this
							// to the parent specified as `appendTo` option
							return $helper;
						},
						start: function(event, ui) {
							var sourceNode = ui.helper.data("ftSourceNode");
							return !!sourceNode; // Abort dragging if no node could be found
						},
					},
					tree.options.dnd.draggable
				)
			);
		}
		// Attach ui.droppable to this Fancytree instance
		if (dnd && dnd.dragDrop) {
			tree.widget.element.droppable(
				$.extend(
					{
						addClasses: false,
						tolerance: "intersect",
						greedy: false,
						/*
			activate: function(event, ui) {
				tree.debug("droppable - activate", event, ui, this);
			},
			create: function(event, ui) {
				tree.debug("droppable - create", event, ui);
			},
			deactivate: function(event, ui) {
				tree.debug("droppable - deactivate", event, ui);
			},
			drop: function(event, ui) {
				tree.debug("droppable - drop", event, ui);
			},
			out: function(event, ui) {
				tree.debug("droppable - out", event, ui);
			},
			over: function(event, ui) {
				tree.debug("droppable - over", event, ui);
			}
*/
					},
					tree.options.dnd.droppable
				)
			);
		}
	}

	/******************************************************************************
	 *
	 */

	$.ui.fancytree.registerExtension({
		name: "dnd",
		version: "2.30.1",
		// Default options for this extension.
		options: {
			// Make tree nodes accept draggables
			autoExpandMS: 1000, // Expand nodes after n milliseconds of hovering.
			draggable: null, // Additional options passed to jQuery draggable
			droppable: null, // Additional options passed to jQuery droppable
			focusOnClick: false, // Focus, although draggable cancels mousedown event (#270)
			preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
			preventRecursiveMoves: true, // Prevent dropping nodes on own descendants
			smartRevert: true, // set draggable.revert = true if drop was rejected
			dropMarkerOffsetX: -24, // absolute position offset for .fancytree-drop-marker relatively to ..fancytree-title (icon/img near a node accepting drop)
			dropMarkerInsertOffsetX: -16, // additional offset for drop-marker with hitMode = "before"/"after"
			// Events (drag support)
			dragStart: null, // Callback(sourceNode, data), return true, to enable dnd
			dragStop: null, // Callback(sourceNode, data)
			initHelper: null, // Callback(sourceNode, data)
			updateHelper: null, // Callback(sourceNode, data)
			// Events (drop support)
			dragEnter: null, // Callback(targetNode, data)
			dragOver: null, // Callback(targetNode, data)
			dragExpand: null, // Callback(targetNode, data), return false to prevent autoExpand
			dragDrop: null, // Callback(targetNode, data)
			dragLeave: null, // Callback(targetNode, data)
		},

		treeInit: function(ctx) {
			var tree = ctx.tree;
			this._superApply(arguments);
			// issue #270: draggable eats mousedown events
			if (tree.options.dnd.dragStart) {
				tree.$container.on("mousedown", function(event) {
					//				if( !tree.hasFocus() && ctx.options.dnd.focusOnClick ) {
					if (ctx.options.dnd.focusOnClick) {
						// #270
						var node = $.ui.fancytree.getNode(event);
						if (node) {
							node.debug(
								"Re-enable focus that was prevented by jQuery UI draggable."
							);
							// node.setFocus();
							// $(node.span).closest(":tabbable").focus();
							// $(event.target).trigger("focus");
							// $(event.target).closest(":tabbable").trigger("focus");
						}
						setTimeout(function() {
							// #300
							$(event.target)
								.closest(":tabbable")
								.focus();
						}, 10);
					}
				});
			}
			_initDragAndDrop(tree);
		},
		/* Display drop marker according to hitMode ('after', 'before', 'over'). */
		_setDndStatus: function(
			sourceNode,
			targetNode,
			helper,
			hitMode,
			accept
		) {
			var markerOffsetX,
				pos,
				markerAt = "center",
				instData = this._local,
				dndOpt = this.options.dnd,
				glyphOpt = this.options.glyph,
				$source = sourceNode ? $(sourceNode.span) : null,
				$target = $(targetNode.span),
				$targetTitle = $target.find("span.fancytree-title");

			if (!instData.$dropMarker) {
				instData.$dropMarker = $(
					"<div id='fancytree-drop-marker'></div>"
				)
					.hide()
					.css({ "z-index": 1000 })
					.prependTo($(this.$div).parent());
				//                .prependTo("body");

				if (glyphOpt) {
					instData.$dropMarker.addClass(
						glyphOpt.map._addClass + " " + glyphOpt.map.dropMarker
					);
				}
			}
			if (
				hitMode === "after" ||
				hitMode === "before" ||
				hitMode === "over"
			) {
				markerOffsetX = dndOpt.dropMarkerOffsetX || 0;
				switch (hitMode) {
					case "before":
						markerAt = "top";
						markerOffsetX += dndOpt.dropMarkerInsertOffsetX || 0;
						break;
					case "after":
						markerAt = "bottom";
						markerOffsetX += dndOpt.dropMarkerInsertOffsetX || 0;
						break;
				}

				pos = {
					my: "left" + offsetString(markerOffsetX) + " center",
					at: "left " + markerAt,
					of: $targetTitle,
				};
				if (this.options.rtl) {
					pos.my = "right" + offsetString(-markerOffsetX) + " center";
					pos.at = "right " + markerAt;
				}
				instData.$dropMarker
					.toggleClass(classDropAfter, hitMode === "after")
					.toggleClass(classDropOver, hitMode === "over")
					.toggleClass(classDropBefore, hitMode === "before")
					.toggleClass("fancytree-rtl", !!this.options.rtl)
					.show()
					.position($.ui.fancytree.fixPositionOptions(pos));
			} else {
				instData.$dropMarker.hide();
			}
			if ($source) {
				$source
					.toggleClass(classDropAccept, accept === true)
					.toggleClass(classDropReject, accept === false);
			}
			$target
				.toggleClass(
					classDropTarget,
					hitMode === "after" ||
						hitMode === "before" ||
						hitMode === "over"
				)
				.toggleClass(classDropAfter, hitMode === "after")
				.toggleClass(classDropBefore, hitMode === "before")
				.toggleClass(classDropAccept, accept === true)
				.toggleClass(classDropReject, accept === false);

			helper
				.toggleClass(classDropAccept, accept === true)
				.toggleClass(classDropReject, accept === false);
		},

		/*
	 * Handles drag'n'drop functionality.
	 *
	 * A standard jQuery drag-and-drop process may generate these calls:
	 *
	 * start:
	 *     _onDragEvent("start", sourceNode, null, event, ui, draggable);
	 * drag:
	 *     _onDragEvent("leave", prevTargetNode, sourceNode, event, ui, draggable);
	 *     _onDragEvent("over", targetNode, sourceNode, event, ui, draggable);
	 *     _onDragEvent("enter", targetNode, sourceNode, event, ui, draggable);
	 * stop:
	 *     _onDragEvent("drop", targetNode, sourceNode, event, ui, draggable);
	 *     _onDragEvent("leave", targetNode, sourceNode, event, ui, draggable);
	 *     _onDragEvent("stop", sourceNode, null, event, ui, draggable);
	 */
		_onDragEvent: function(
			eventName,
			node,
			otherNode,
			event,
			ui,
			draggable
		) {
			// if(eventName !== "over"){
			// 	this.debug("tree.ext.dnd._onDragEvent(%s, %o, %o) - %o", eventName, node, otherNode, this);
			// }
			var accept,
				nodeOfs,
				parentRect,
				rect,
				relPos,
				relPos2,
				enterResponse,
				hitMode,
				r,
				opts = this.options,
				dnd = opts.dnd,
				ctx = this._makeHookContext(node, event, {
					otherNode: otherNode,
					ui: ui,
					draggable: draggable,
				}),
				res = null,
				that = this,
				$nodeTag = $(node.span);

			if (dnd.smartRevert) {
				draggable.options.revert = "invalid";
			}

			switch (eventName) {
				case "start":
					if (node.isStatusNode()) {
						res = false;
					} else if (dnd.dragStart) {
						res = dnd.dragStart(node, ctx);
					}
					if (res === false) {
						this.debug("tree.dragStart() cancelled");
						//draggable._clear();
						// NOTE: the return value seems to be ignored (drag is not cancelled, when false is returned)
						// TODO: call this._cancelDrag()?
						ui.helper.trigger("mouseup").hide();
					} else {
						if (dnd.smartRevert) {
							// #567, #593: fix revert position
							// rect = node.li.getBoundingClientRect();
							rect = node[
								ctx.tree.nodeContainerAttrName
							].getBoundingClientRect();
							parentRect = $(
								draggable.options.appendTo
							)[0].getBoundingClientRect();
							draggable.originalPosition.left = Math.max(
								0,
								rect.left - parentRect.left
							);
							draggable.originalPosition.top = Math.max(
								0,
								rect.top - parentRect.top
							);
						}
						$nodeTag.addClass("fancytree-drag-source");
						// Register global handlers to allow cancel
						$(document).on(
							"keydown.fancytree-dnd,mousedown.fancytree-dnd",
							function(event) {
								// node.tree.debug("dnd global event", event.type, event.which);
								if (
									event.type === "keydown" &&
									event.which === $.ui.keyCode.ESCAPE
								) {
									that.ext.dnd._cancelDrag();
								} else if (event.type === "mousedown") {
									that.ext.dnd._cancelDrag();
								}
							}
						);
					}
					break;

				case "enter":
					if (
						dnd.preventRecursiveMoves &&
						node.isDescendantOf(otherNode)
					) {
						r = false;
					} else {
						r = dnd.dragEnter ? dnd.dragEnter(node, ctx) : null;
					}
					if (!r) {
						// convert null, undefined, false to false
						res = false;
					} else if ($.isArray(r)) {
						// TODO: also accept passing an object of this format directly
						res = {
							over: $.inArray("over", r) >= 0,
							before: $.inArray("before", r) >= 0,
							after: $.inArray("after", r) >= 0,
						};
					} else {
						res = {
							over: r === true || r === "over",
							before: r === true || r === "before",
							after: r === true || r === "after",
						};
					}
					ui.helper.data("enterResponse", res);
					// this.debug("helper.enterResponse: %o", res);
					break;

				case "over":
					enterResponse = ui.helper.data("enterResponse");
					hitMode = null;
					if (enterResponse === false) {
						// Don't call dragOver if onEnter returned false.
						//                break;
					} else if (typeof enterResponse === "string") {
						// Use hitMode from onEnter if provided.
						hitMode = enterResponse;
					} else {
						// Calculate hitMode from relative cursor position.
						nodeOfs = $nodeTag.offset();
						relPos = {
							x: event.pageX - nodeOfs.left,
							y: event.pageY - nodeOfs.top,
						};
						relPos2 = {
							x: relPos.x / $nodeTag.width(),
							y: relPos.y / $nodeTag.height(),
						};

						if (enterResponse.after && relPos2.y > 0.75) {
							hitMode = "after";
						} else if (
							!enterResponse.over &&
							enterResponse.after &&
							relPos2.y > 0.5
						) {
							hitMode = "after";
						} else if (enterResponse.before && relPos2.y <= 0.25) {
							hitMode = "before";
						} else if (
							!enterResponse.over &&
							enterResponse.before &&
							relPos2.y <= 0.5
						) {
							hitMode = "before";
						} else if (enterResponse.over) {
							hitMode = "over";
						}
						// Prevent no-ops like 'before source node'
						// TODO: these are no-ops when moving nodes, but not in copy mode
						if (dnd.preventVoidMoves) {
							if (node === otherNode) {
								this.debug(
									"    drop over source node prevented"
								);
								hitMode = null;
							} else if (
								hitMode === "before" &&
								otherNode &&
								node === otherNode.getNextSibling()
							) {
								this.debug(
									"    drop after source node prevented"
								);
								hitMode = null;
							} else if (
								hitMode === "after" &&
								otherNode &&
								node === otherNode.getPrevSibling()
							) {
								this.debug(
									"    drop before source node prevented"
								);
								hitMode = null;
							} else if (
								hitMode === "over" &&
								otherNode &&
								otherNode.parent === node &&
								otherNode.isLastSibling()
							) {
								this.debug(
									"    drop last child over own parent prevented"
								);
								hitMode = null;
							}
						}
						//                this.debug("hitMode: %s - %s - %s", hitMode, (node.parent === otherNode), node.isLastSibling());
						ui.helper.data("hitMode", hitMode);
					}
					// Auto-expand node (only when 'over' the node, not 'before', or 'after')
					if (
						hitMode !== "before" &&
						hitMode !== "after" &&
						dnd.autoExpandMS &&
						node.hasChildren() !== false &&
						!node.expanded &&
						(!dnd.dragExpand || dnd.dragExpand(node, ctx) !== false)
					) {
						node.scheduleAction("expand", dnd.autoExpandMS);
					}
					if (hitMode && dnd.dragOver) {
						// TODO: http://code.google.com/p/dynatree/source/detail?r=625
						ctx.hitMode = hitMode;
						res = dnd.dragOver(node, ctx);
					}
					accept = res !== false && hitMode !== null;
					if (dnd.smartRevert) {
						draggable.options.revert = !accept;
					}
					this._local._setDndStatus(
						otherNode,
						node,
						ui.helper,
						hitMode,
						accept
					);
					break;

				case "drop":
					hitMode = ui.helper.data("hitMode");
					if (hitMode && dnd.dragDrop) {
						ctx.hitMode = hitMode;
						dnd.dragDrop(node, ctx);
					}
					break;

				case "leave":
					// Cancel pending expand request
					node.scheduleAction("cancel");
					ui.helper.data("enterResponse", null);
					ui.helper.data("hitMode", null);
					this._local._setDndStatus(
						otherNode,
						node,
						ui.helper,
						"out",
						undefined
					);
					if (dnd.dragLeave) {
						dnd.dragLeave(node, ctx);
					}
					break;

				case "stop":
					$nodeTag.removeClass("fancytree-drag-source");
					$(document).off(".fancytree-dnd");
					if (dnd.dragStop) {
						dnd.dragStop(node, ctx);
					}
					break;

				default:
					$.error("Unsupported drag event: " + eventName);
			}
			return res;
		},

		_cancelDrag: function() {
			var dd = $.ui.ddmanager.current;
			if (dd) {
				dd.cancel();
			}
		},
	});
	// Value returned by `require('jquery.fancytree..')`
	return $.ui.fancytree;
}); // End of closure
