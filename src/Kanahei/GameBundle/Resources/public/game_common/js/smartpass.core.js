
/*
 * smartpass package
 * core functions with jQuery
 */
 
var smartpass = (function() {
	return {
		initialize : function() {
			
			smartpass.global.showDebugger();
			
			//横向きの場合のアラート表示処理
			
			var initialized = false;
			
			var showAlert = function() {
				$("div.cautionContainer").css("display", "block");
			};
			
			var hideAlert = function() {
				$("div.cautionContainer").css("display", "none");
			};
			
			//共通イベント
			$(window).on("orientationchange", function() {
				//trace("orientationchange " + window.orientation);
				
				if (Math.abs( window.orientation) == 90) {
					showAlert();
				} else {
					hideAlert();
					
					if (!initialized) {
						if (smartpass.complete) {
							smartpass.complete();
							smartpass.complete = null;
							initialized = true;
						}
					}
				}
			});
			
			if (smartpass.complete) {
				if (Math.abs( window.orientation) == 90) {
					//横方向なのでアラート出す
					showAlert();
				} else {
					smartpass.complete();
					smartpass.complete = null;
					initialized = true;
				}
			}
			
			trace("w = " + window.innerWidth);
		},
		
		global : {} 
	};
})();


//------------------------------------------------------------------------------------------
// smartpass.global

(function() {
	
	var scope = this;
	
	//デバッグモードフラグ
	var debugMode = true;
	
	//スタブモードフラグ
	var stubMode = true;
	
	//trace出力テキスト
	var traceText = "";
	
	//デバッガのインスタンス
	this.deb;
	
	this.stage = null;
	
	this.initDebugger = function() {
		if (debugMode) scope.deb = new smartpass.debug.Debugger();
	};
	
	this.showDebugger = function() {
		if (debugMode) {
			scope.deb.show();
		}
	};
	
	this.trace = function(value) {
		if (debugMode) {
			scope.deb.trace(value);
		}
	};
	
	this.clearTrace = function() {
		if (debugMode) {
			scope.deb.clear();
		}
	};
	
	this.isDebugger = function() {
		return debugMode;
	};
	
	this.isStub = function() {
		return stubMode;
	};
	
	var agent = navigator.userAgent;
	var appVersion = navigator.appVersion.toLowerCase();
	
	this.userAgent = {
		isFireFox : /FireFox/.test(agent),
		isChrome : /Chrome/.test(agent),
		isIPad : /iPad/.test(agent)
	};
	
	this.userAgent.isIOS = agent.indexOf('iPhone') > -1 || agent.indexOf('iPod')  > -1 || agent.indexOf('iPad')  > -1;
	this.userAgent.isAndroid = agent.indexOf('Android')  > -1;
	this.userAgent.isMobile = this.userAgent.isIOS || this.userAgent.isAndroid;
	
	//Android Tabletの判定追加
	if (this.userAgent.isAndroid) {
		this.userAgent.isAndroidTablet = agent.indexOf('Mobile')  < 0;
		this.userAgent.isAndroidMobile = !this.userAgent.isAndroidTablet;
	} else {
		this.userAgent.isAndroidTablet = false;
		this.userAgent.isAndroidMobile = false;
	}
	
	this.userAgent.isSmartPhone = false;
	
	if (this.userAgent.isMobile) {
		if ((agent.indexOf('iPhone') > -1) || (this.userAgent.isAndroid && !this.userAgent.isAndroidTablet)) {
			this.userAgent.isSmartPhone = true;
		}
	}
	
	this.userAgent.isSafari = /Safari/.test(agent) && !this.userAgent.isChrome;
	
	//requestAnimFrame使用フラグ
	this.useRequestAnimFrame = false;
	
	this.eventType = {};
	
	if (this.userAgent.isMobile || this.userAgent.isIPad) {
		this.eventType["down"] = "touchstart";
		this.eventType["move"] = "touchmove";
		this.eventType["up"] = "touchend";
	} else {
		this.eventType["down"] = "mousedown";
		this.eventType["move"] = "mousemove";
		this.eventType["up"] = "mouseup";
	}
	
	if (!window.addEventListener) {
		window.addEventListener = function(evt, func) {
			window.attachEvent("on" + evt, func);
		};
		
		window.removeEventListener = function(evt, func) {
			window.detachEvent("on" + evt, func);
		};
	}
	
	// setInterval高速化
	//////////////////////////////////////////////////////
	var si_CC = 0;
	var si_BD = 10;//(this.userAgent.isAndroid) ? 10 : 10;
	var si_FA = [];
	var si_DA = [];
	var si_IA = [];
	var gSetInterval = window.setInterval;
	var gClearInterval = window.clearInterval;
	
	gSetInterval(function() {
		si_CC++;
		for (var i = 0, l = si_IA.length; i < l; i++) {
			if (!((si_CC * si_BD) % si_DA[si_IA[i]]) && si_FA[si_IA[i]]) {
				si_FA[si_IA[i]]();
			}
		}
	}, si_BD);
	
	window.setInterval = function(func, delay) {
		if (delay < si_BD) delay = si_BD;
		var id = si_FA.length;
		var _fn = delay % si_BD;
		var _in = delay / si_BD >> 0;
		
		si_FA.push(func);
		si_DA.push((_in + Math.round(_fn/si_BD)) * si_BD);
		si_IA.push(id);
		
		return id;
	}
	
	window.clearInterval = function(id) {
		var tmp = si_IA.slice(0);
		
		si_IA = [];
		si_FA[id] = undefined;
		si_DA[id] = undefined;
		
		for (var i = 0, l = tmp.length; i < l; i++) if (tmp[i] != id) si_IA.push(tmp[i]);
	}
	
	this.gSetInterval = gSetInterval;
	this.gClearInterval = gClearInterval;
	
	//////////////////////////////////////////////////////
	
	
	window.requestAnimFrame = (function()
	{
		if (scope.userAgent.isSafari)
		{
			//SafariでwebkitRequestAnimationFrameがきかない為
			return function( callback ){
				window.setTimeout(callback, 1000 / 30);
			};
		}
		
		return  window.requestAnimationFrame       || 
						window.webkitRequestAnimationFrame || 
						window.mozRequestAnimationFrame    || 
						window.oRequestAnimationFrame      || 
						window.msRequestAnimationFrame     || 
						function( callback ){
							window.setTimeout(callback, 5);
						};
	})();
	
	
	this.debug = function(flag) {
		debugMode = flag;
	};
	
	this.stub = function(flag) {
		stubMode = flag;
	};
	
	this.addPackage = function(pkg) {
		if (!pkg) return;
		var pass = smartpass;
		var array = pkg.split(".");
		var n = 0;
		var ln = array.length;
		
		do {
			if (!pass[array[n]]) pass[array[n]] = {};
			pass = pass[array[n]];
		} while (++n < ln);
	};
	
	this.extend = function (subClass, superClass) {
		var Temp = new Function();
    Temp.prototype = superClass.prototype;
    subClass.prototype = new Temp;
    subClass.prototype.constructor = subClass;
    subClass.prototype.__super__ = function () {
			var originalSuper = this.__super__;
			this.__super__ = superClass.prototype.__super__ || null;
			
			superClass.apply(this, arguments);
			
			if (this.constructor == subClass) {
				delete this.__super__;
			} else {
				this.__super__ = originalSuper;
			}
    };
	};
	
}).apply(smartpass.global);

smartpass.global.addPackage("util");
smartpass.global.addPackage("base");
smartpass.global.addPackage("view");
smartpass.global.addPackage("debug");

//global function and property
var extend = smartpass.global.extend;
var trace = smartpass.global.trace;
var clearTrace = smartpass.global.clearTrace;
var userAgent = smartpass.global.userAgent;
var eventType = smartpass.global.eventType;



//------------------------------------------------------------------------------------------
// smartpass.base

(function() {
	
	/*******************************************
	 * EventDispatcherクラス
	 *******************************************/
	this.EventDispatcher = function() {
		
		this.listeners = [];
		this.enterFrameTimer;
		
		//デルタタイム使用する場合はtrue(コマ落ちしても速度落としたくない場合)
		this.useDeltaTime = false;
		//this.enterFrameFunc;
	};
		
	this.EventDispatcher.prototype.addEventListener = function(type, func) {
		if (!this.listeners) {
			this.listeners = [];
		}
		
		this.listeners.push({type:type, func:func});
	};

	this.EventDispatcher.prototype.removeEventListener = function(type, func) {
		var ls = this.listeners;
		var tmp = [];
		for (var i = 0, ln = ls.length; i < ln; i++) {
			var ob = ls[i];
			if (ob.type != type || ob.func != func) {
				tmp.push(ob);
			}
		}

		this.listeners = tmp;
	};

	this.EventDispatcher.prototype.dispatchEvent = function(evt) {
		var ls = this.listeners;
		if (!ls) return;
		for (var i = 0, ln = ls.length; i < ln; i++) {
			var ob = ls[i];
			if (ob.type == evt.type) {
				ob.func(evt.args);
			}
		}
	};
	
	
	if (smartpass.global.useRequestAnimFrame) {
		
		//requestAnimFrameを使ってenterFrame
		this.EventDispatcher.prototype.onEnterFrame = function(func) {
			
			if (this.enterFrameFunc) {
				this.deleteEnterFrame();
			}
			
			this.enterFrameFunc = func;
			var scope = this;
			
			if (this.useDeltaTime) {
				
				//デルタタイムあり
				var now = window.performance && (
					performance.now || 
					performance.mozNow || 
					performance.msNow || 
					performance.oNow || 
					performance.webkitNow );
				
				var getTime = function() {
					return ( now && now.call( performance ) ) || ( new Date().getTime() );
				};
				
				var tm = getTime();
				
				(function animloop() {
					if (!scope.enterFrameFunc) return;
					var tm2 = getTime();
					var deltaTime = tm2 - tm;
					if (deltaTime <= 0) {
						deltaTime = 1;
					}
					scope.enterFrameFunc(deltaTime);
					tm = tm2;
					requestAnimFrame(animloop);
				})();
				
			} else {
				
				//デルタタイムなし
				(function animloop() {
					if (!scope.enterFrameFunc) return;
					scope.enterFrameFunc(0);
					requestAnimFrame(animloop);
				})();
			}
		};
		
		this.EventDispatcher.prototype.deleteEnterFrame = function() {
			this.enterFrameFunc = null;
		};
	} else {
		
		//setTimeoutを使ってenterFrame
		this.EventDispatcher.prototype.onEnterFrame = function(func) {
			if (this.enterFrameTimer) {
				this.deleteEnterFrame();
			}
			
			if (this.useDeltaTime) {
				
				//デルタタイムあり
				var now = window.performance && (
					performance.now || 
					performance.mozNow || 
					performance.msNow || 
					performance.oNow || 
					performance.webkitNow );
				
				var getTime = function() {
					return ( now && now.call( performance ) ) || ( new Date().getTime() );
					//return ( new Date().getTime() );
				};
				
				var tm = getTime();
				
				this.enterFrameTimer = window.setInterval(function() {
					var tm2 = getTime();
					var deltaTime = tm2 - tm;
					if (deltaTime <= 0) {
						deltaTime = 1;
					}
					func(deltaTime);
					tm = tm2;
				}, 10);
			} else {
				
				//デルタタイムなし
				this.enterFrameTimer = window.setInterval(function() {
					func();
				}, 10);
			}
		};
		
		this.EventDispatcher.prototype.deleteEnterFrame = function() {
			window.clearInterval(this.enterFrameTimer);
			this.enterFrameTimer = null;
		};
	}
	
	this.EventDispatcher.prototype.onEnterFrame2 = function(func) {
		if (this.enterFrameTimer) {
			this.deleteEnterFrame2();
		}
		
		this.enterFrameTimer = window.setInterval(function() {
			func();
		}, 1000 / 60);
	};
	
	this.EventDispatcher.prototype.deleteEnterFrame2 = function() {
		window.clearInterval(this.enterFrameTimer);
		this.enterFrameTimer = null;
	};

	var EventDispatcher = smartpass.base.EventDispatcher;
	
	
	
	/*******************************************
	 * 複数画像ロードクラス
	 *******************************************/
	this.MultipleLoader = function() {
		this.percent = 0;
		this.loadIndex = 0;
		this.imageRequestNum = 0;
		this.imageRequests = {};
		this.imageRequestsArray = [];
	};
	
	extend(this.MultipleLoader, EventDispatcher);
	
	this.MultipleLoader.prototype.addImageRequest = function(url) {
		if (url != null && url != "") {
			this.imageRequests[url] = url;
			this.imageRequestsArray.push(url);
			this.imageRequestNum++;
		}
	};
	
	this.MultipleLoader.prototype.load = function() {
		if (this.imageRequestsArray.length > 0) {
			this.loadImage();
		}
	};
	
	this.MultipleLoader.prototype.loadImage = function() {
		var scope = this;
		var url = this.imageRequestsArray[this.loadIndex];
		var img = new Image();
		img.onload = function() {
			img.onload = null;
			scope.completeHandler(url, img);
		};
		img.src = url;// + "?" + (Math.random()*10000 >> 0);	//IE7,8でリロード後、正しくロードできないので乱数つける
	};
	
	this.MultipleLoader.prototype.completeHandler = function(url, obj) {
		this.imageRequests[url] = obj;
		
		++this.loadIndex;
		
		this.percent = this.loadIndex / this.imageRequestNum;
		
		if (this.loadIndex >= this.imageRequestNum) {
			this.dispatchEvent({type:"complete"});
		} else {
			this.loadImage();
		}
	};
	
	
	/*******************************************
	 * XMLロードクラス
	 *******************************************/
	this.loadXML = function(url, cb_func) {
		$(function(){
			$.ajax({
				url:url,
				type:'GET',
				dataType:'xml',
				timeout:2000,
				error:function() {
					trace("ロード失敗");
				},
				success:function(xml){
					cb_func(xml);
					/*
					$(xml).find("sample").each(function() {
						$("#sample ul").append('<li><a href="' + $(this).find('url').text() +  '" target="_blank">' + $(this).find('title').text() + '</a></li>');
					});
					*/
				}
			});
		});
	};
	
}).apply(smartpass.base);


	
//------------------------------------------------------------------------------------------
// smartpass.util

(function() {
	
	/*
	 * URLパラメータを取得
	 */
	this.getRequest = function() {
		if(location.search.length > 1) {
			var get = new Object();
			var ret = location.search.substr(1).split("&");
			for (var i = 0; i < ret.length; i++) {
				var r = ret[i].split("=");
				get[r[0]] = r[1];
			}
			
			return get;
		} else {
			return false;
		}
	};
	
	
	/*
	 * 指定領域いっぱいに画像が配置されるよう計算する
	 */
	this.getFlexibleRect = function(stageWidth, stageHeight, imgWidth, imgHeight) {
		var per = stageWidth / stageHeight;
		var per_hd = imgWidth / imgHeight;
		var sc;
		var width;
		var height;
		var pos_x;
		var pos_y;
		
		if (!isNaN(per)) {
			if (stageWidth == imgWidth && stageHeight == imgHeight) {
				//trace("フルHDジャストフィット");
				width = stageWidth;
				height = stageHeight;
				pos_x = 0;
				pos_y = 0;
			} else if (per > per_hd) {
				sc = stageWidth / imgWidth;
				width = sc * imgWidth;
				height = sc * imgHeight;
				//trace("横に長い（上下がはみ出る）", videoWidth, videoHeight);
				pos_x = 0;
				pos_y = -(height - stageHeight) / 2;
			} else {
				sc = stageHeight / imgHeight;
				//trace("縦に長い（左右がはみ出る）");
				width = sc * imgWidth;
				height = sc * imgHeight;
				pos_x = -(width - stageWidth) / 2;
				pos_y = 0;
			}
		}
		
		return { x : pos_x, y : pos_y, width : width, height : height };
	};
	
	
	if (userAgent.isMobile || userAgent.isIPad) {
		this.getMousePosition = function(e) {
			var obj = {};
			var touch = e.originalEvent.touches[0];
			obj.x = touch.pageX;
			obj.y = touch.pageY;
			return obj;
		};
	} else {
		this.getMousePosition = function(e) {
			var obj = {};
			// trace(e);
			// trace(e.pageX);
			if	(e) {
				if (e.pageX) {
					obj.x = e.pageX;
					obj.y = e.pageY;
				} else {
					
				}
			} else {
				obj.x = document.body.scrollLeft + event.clientX;
				obj.y = document.body.scrollTop + event.clientY;
			}
			 
			return obj;
		};
	}
	
	this.distance = function(v1, v2) {
		var xx = v1.x - v2.x;
		var yy = v1.y - v2.y;
		return Math.sqrt(xx * xx + yy * yy);
	};

}).apply(smartpass.util);

	
//------------------------------------------------------------------------------------------
// smartpass.debug

(function() {
	
	var _global = smartpass.global;
	
	/*******************************************
	 * デバッガクラス
	 *******************************************/
	this.Debugger = function() {
		this.containerId = "smartpass_DEBUG_CONTAINER";
		this.innerId = "smartpass_DEBUG_INNER";
		this.clearBtnId = "smartpass_DEBUG_CLEARBUTTON";
		this.tags = jQuery('<div id="' + this.containerId + '" style="display:none;"><p id="' + this.innerId + '">traceエリア</p><input id="' + this.clearBtnId + '" type="button" value="クリア" /></div>');
		this.traceText = "clear<br>";
	};
	
	this.Debugger.prototype.clear = function() {
		this.traceText = "clear";
	};
	
	this.Debugger.prototype.trace = function(value) {
		if (typeof value == "object") {
			for (var i in value) {
				this.traceText += i + " = " + value[i] + "<br>";
			}
		} else {
			this.traceText += value + "<br>";
		}
		
		if (this.inner){
			this.inner.html(this.traceText);
		}
		
		console.log(value);
	};
	
	this.Debugger.prototype.show = function() {
		jQuery("body").append(this.tags);
	
		var scope = this;
		var container = jQuery("#" + this.containerId);
		var clearBtn = jQuery("#" + this.clearBtnId);
		this.inner = jQuery("#" + this.innerId);
		
		container.css("position", "fixed");
		container.css("zIndex", "99999");
		container.css("right", 0);
		container.css("bottom", "0px");
		container.css("padding", "10px");
		container.css("background", "rgba(30, 30, 30, 0.75)");
		container.css("maxWidth", "500px");
		container.css("width", "150px");
		container.css("color", "rgb(255, 255, 255)");
		container.css("fontSize", "9px");
		container.css("display", "block");
		
		clearBtn.bind("click", function() {
			scope.traceText = "clear<br>";
			if (scope.inner) {
				scope.inner.html(scope.traceText);
			}
		});
	};
}).apply(smartpass.debug);


//ロード前の初期化
smartpass.global.debug(false);
smartpass.global.stub(false);
smartpass.global.initDebugger();

(function(func) {
	window.addEventListener("load", func, false);
})(function() {
	smartpass.initialize();
});

