
//------------------------------------------------------------------------------------------
// smartpass.view.janken

smartpass.global.addPackage("view.janken");

(function() {
	
	var MultipleLoader = smartpass.base.MultipleLoader;
	var EventDispatcher = smartpass.base.EventDispatcher;
	var MainBase = smartpass.base.MainBase;
	var ViewBase = smartpass.base.ViewBase;
	var Sprite = smartpass.base.Sprite;
	var Tween = smartpass.base.Tween;
	var TextNumber = smartpass.base.TextNumber;
	var loadXML = smartpass.base.loadXML;
	
	
	/**********
	 ********** 
	 ********** タイトル画面系
	 **********
	 **********/
	 
	
	/*******************************************************************
	 * タイトル画面 ビュークラス
	 ******************************************************************/
	this.JankenTitle = function(baseScale) {
		
		this.container = "div.titleContainer";
		
		var scope = this;
		
		//タイトルとキャラ画像のリサイズ
		$("div.titleContainer h1").css("background-size", (370 * baseScale) + "px " + (431 * baseScale) + "px");
		
		//スタートボタンのイベントセット
		$("div.titleContainer p.btnStart a").bind("click", function() {
			scope.dispatchEvent({type:"clickStart"});
		});
	};
	
	extend(this.JankenTitle, ViewBase);
	
	/*
	 * プリロード
	 */
	this.JankenTitle.prototype.loading = function(cb_func) {
		if (this.isLoaded) {
			cb_func();
		} else {
			
			var scope = this;
			var images = [
				"bg_title.png",
				"howto.png"
			];
			
			var loader = new MultipleLoader();
			
			for (var i = 0, ln = images.length; i < ln; i++) {
				loader.addImageRequest("/bundles/machigaigame/GameJanken/images/" + images[i]);
			}
			
			loader.addEventListener("complete", function(args) {
				scope.isLoaded = true;
				scope.loadImages = loader.imageRequests;
				cb_func();
			});
			loader.load();
		}
	};
	
	
	/**********
	 ********** 
	 ********** ゲーム画面系
	 **********
	 **********/
	 
	
	/*******************************************************************
	 * インジケーター
	 ******************************************************************/
	 
	this.Indicator = function(baseScale) {
		
		var scope = this;
		
		//勝利したカウント数
		this.count = 0;
		
		$("div.gameContainer p.btnStop a").bind("click", function() {
			scope.dispatchEvent({type:"clickStop"});
		});
		
		this.textNumber = new TextNumber("div.scoreContainer p.number", 0, 0);
	};
	
	extend(this.Indicator, EventDispatcher);
	
	/*
	 * やめるボタンを隠す
	 */
	this.Indicator.prototype.hideStopBtn = function() {
		$("div.gameContainer p.btnStop").css("display", "none");
	};
	
	/*
	 * カウントアップ
	 */
	this.Indicator.prototype.countUp = function() {
		
		this.count++;
		this.updateCount();
	};
	
	/*
	 * カウント表示を更新
	 */
	this.Indicator.prototype.updateCount = function() {
		
		this.textNumber.number(this.count);
		
		/*
		var sx = 0;
		var sy = -2;
		
		switch (this.count) {
			case 0:
				sx = -208;
				break;
			case 1:
				sx = -234;
				break;
			case 2:
				sx = -260;
				break;
			case 3:
				sx = -286;
				break;
			default:break;
		}
		
		$("div.scoreContainer p.number").css("background-position", sx + "px " + sy + "px");
		*/
	};
	
	/*
	 * 初期化(数値を０にする)
	 */
	this.Indicator.prototype.reset = function() {
		
		this.count = 0;
		this.updateCount();
		$("div.gameContainer p.btnStop").css("display", "block");
	};
	 
	
	/*******************************************************************
	 * じゃんけんUI
	 ******************************************************************/
	 
	this.JankenNavi = function(baseScale) {
		
		this.elmName = "div.gameContainer div.jankenContainer";
		
		var scope = this;
		
		var func = function(selIndex) {
			
			$(scope.elmName + " p").each(function(index, element) {
				scope.enabled(false);
				if (index == selIndex) {
					$($(element).find("span")).css("opacity", 1);
				}
			});
			
			scope.dispatchEvent({type:"select", args:{type:selIndex}});
		};
		
		$(this.elmName + " p.iconGoo a").bind("click", function(evt) {
			func(0);
		});
		
		$(this.elmName + " p.iconChoki a").bind("click", function(evt) {
			func(1);
		});
		
		$(this.elmName + " p.iconPar a").bind("click", function(evt) {
			func(2);
		});
	};
	
	extend(this.JankenNavi, EventDispatcher);
	
	/*
	 * ボタン機能オン・オフ切替
	 */
	this.JankenNavi.prototype.enabled = function(flag) {
		$(this.elmName + " p a").css("display", (flag) ? "block" : "none");
		$(this.elmName + " p span").css("display", (flag) ? "none" : "block");
	};
	
	/*
	 * 初期化
	 */
	this.JankenNavi.prototype.reset = function() {
		this.enabled(false);
		$(this.elmName + " p span").css("opacity", 0.3);
	};
	
	/*
	 * 表示する
	 */
	this.JankenNavi.prototype.show = function() {
		$(this.elmName).css("display", "block");
	};
	
	/*
	 * 表示を隠す
	 */
	this.JankenNavi.prototype.hide = function() {
		$(this.elmName).css("display", "none");
	};
	
	
	/*******************************************************************
	 * キャラクタ制御
	 ******************************************************************/
	 
	this.Character = function(baseScale) {
		
		this.elmName = "div.gameContainer div.charaContainer p";
		this.chara = new Sprite(this.elmName);
		
		//GameViewのプリロード後にセットされる
		this.charaImages1 = [];
		this.charaImages2 = [];
		this.charaImages3 = [];
		
		this.reset();
	};
	
	extend(this.Character, EventDispatcher);
	
	/*
	 * 指定キャラを表示
	 * index:0 - 2 (何回戦か）
	 */
	this.Character.prototype.show = function(index, cb_func) {
		
		var charaString = "";
		
		/*switch (index) {
			case 0 :
				var rnd = Math.round(Math.random() * 2) + 1;
				charaString = "chara1_" + rnd;
				break;
			case 1 :
				var rnd = Math.round(Math.random() * 2) + 1;
				charaString = "chara2_" + rnd;
				break;
			case 2 :
				var rnd = Math.round(Math.random() * 3) + 1;
				charaString = "chara3_" + rnd;
				break;
			default:break;
		}*/
		
		var rnd = Math.random();
		
		switch (index) {
			case 0 :
				charaString = this.charaImages1[Math.floor(rnd * this.charaImages1.length)];
				break;
			case 1 :
				charaString = this.charaImages2[Math.floor(rnd * this.charaImages2.length)];
				break;
			case 2 :
				charaString = this.charaImages3[Math.floor(rnd * this.charaImages3.length)];
				break;
			default:break;
		}
		
		var chara = this.chara;
		
		//chara.element.attr("class", charaString);
		chara.element.css("background", "url(/bundles/machigaigame/GameJanken/images/" + charaString + ") no-repeat center top");
		chara.element.css("background-size", "442px 567px");
		
		chara.alpha = 0;
		chara.update();
		chara.visible(true);
		
		Tween.fadeIn(chara, 0.1, function() {
			cb_func();
		});
	};
	
	/*
	 * 表示を隠す
	 */
	this.Character.prototype.hide = function() {
		
	};
	
	/*
	 * 初期化
	 */
	this.Character.prototype.reset = function() {
		this.chara.visible(false);
	};
	
	
	/*******************************************************************
	 * 敵のじゃんけんパネルクラス
	 ******************************************************************/
	 
	this.JankenPanel = function(baseScale) {
		
		this.elmName = "div.gameContainer div.selectIconContainer";
		
		this.element = new Sprite(this.elmName + " div.selectIcon");
		this.reset();
	};
	
	extend(this.JankenPanel, EventDispatcher);
	
	/*
	 * じゃんけん出す
	 * type:0(グー）、1(チョキ）、2(パー）
	 */
	this.JankenPanel.prototype.show = function(enemyIndex, cb_func) {
		
		var inner = $(this.elmName + " div.selectIcon p");
		
		var cl;
		
		switch(enemyIndex) {
			case 0:
				cl = "goo";
				break;
			case 1:
				cl = "choki";
				break;
			case 2:
				cl = "par";
				break;
			default:break;
		}
		
		inner.attr("class", cl);
		this.element.visible(true);
		
		this.element.y = window.innerHeight;
		this.element.update();
		
		Tween.motion(this.element, [ "y" ], [ 0 ], 0.035, 0, 0.1, function() {
			cb_func();
		});
	};
	
	/*
	 * 初期化
	 */
	this.JankenPanel.prototype.reset = function() {
		
		this.element.visible(false);
	};
	
	
	/*******************************************************************
	 * テキスト制御クラス
	 ******************************************************************/
	 
	this.TextEffect = function(baseScale) {
		
		this.baseScale = baseScale;
		this.elmName = "div.gameContainer div.textContainer";
		
		this.textFirst = new Sprite($(this.elmName + " p.textFirst"), { alpha : 0 });
		this.textSecond = new Sprite($(this.elmName + " p.textSecond"), { alpha : 0 });
		this.textFinal = new Sprite($(this.elmName + " p.textFinal"), { alpha : 0 });
		
		this.textJan = new Sprite($(this.elmName + " p.textJan"), { alpha : 0 });
		this.textKen = new Sprite($(this.elmName + " p.textKen"), { alpha : 0 });
		this.textPon = new Sprite($(this.elmName + " p.textPon"), { alpha : 0 });
		
		this.textWin = new Sprite($(this.elmName + " p.textWin"), { alpha : 0 });
		this.textLose = new Sprite($(this.elmName + " p.textLose"), { alpha : 0 });
		this.textAiko = new Sprite($(this.elmName + " p.textAiko"), { alpha : 0 });
		
		this.allText = [
			this.textFirst,
			this.textSecond,
			this.textFinal,
			this.textJan,
			this.textKen,
			this.textPon,
			this.textWin,
			this.textLose,
			this.textAiko
		];
				
		this.reset();
	};
	
	extend(this.TextEffect, EventDispatcher);
	
	/*
	 * じゃんけん前のエフェクト開始
	 */
	this.TextEffect.prototype.startAnim = function(index, cb_func) {
		
		var scope = this;
		var text0;
		
		switch(index) {
			case 0:
				text0 = this.textFirst;
				break;
			case 1:
				text0 = this.textSecond;
				break;
			case 2:
				text0 = this.textFinal;
				break;
			default:break;
		}
		
		text0.visible(true);
		
		$(this.elmName).css("padding-top", 249);
		
		//〇回戦
		Tween.fadeIn(text0, 0.1, function() {
			Tween.delay(text0, 160, function() {
				Tween.fadeOut(text0, 0.1, function() {
					
					//じゃん・けん
					text0.visible(false);
					
					$(scope.elmName).css("padding-top", 200);
					
					var textJan = scope.textJan;
					var textKen = scope.textKen;
					
					textJan.visible(true);
					textKen.visible(true);
					textJan.scale = 2;
					textJan.update();
					textKen.scale = 2;
					textKen.update();
					
					//spt, prop_array, end_array, sp, cb_func, delay, threshold
					Tween.motion(textJan, [ "alpha", "scale" ], [ 1, 1 ], 0.012, 0, 0.002);
					Tween.motion(textKen, [ "alpha", "scale" ], [ 1, 1 ], 0.012, 30, 0.002, function() {
						
						cb_func();
					});
				});
			});
		});
	};
	
	/*
	 * じゃんけん後の結果テキスト開始
	 * result : 0:あいこ、1:勝ち、2:負け
	 */
	this.TextEffect.prototype.showJudge = function(result, cb_func) {
		
		var scope = this;
		this.textJan.visible(false);
		this.textKen.visible(false);
		this.textAiko.visible(false);
		
		$(scope.elmName).css("padding-top", 129);
		
		var textPon = scope.textPon;
		textPon.visible(true);
		textPon.scale = 2;
		textPon.update();
		
		Tween.motion(textPon, [ "alpha", "scale" ], [ 1, 1 ], 0.012, 0, 0.002, function() {
			Tween.delay(textPon, 60, function() {
				
				textPon.visible(false);
				
				//勝敗を表示
				var text0;
				
				switch(result) {
					case 0:
						text0 = scope.textAiko;
						break;
					case 1:
						text0 = scope.textWin;
						break;
					case 2:
						text0 = scope.textLose;
						break;
					default:break;
				}
				
				text0.visible(true);
				
				if (result == 0) {
					//あいこの場合は再戦の画面へ
					
					text0.alpha = 0;
					text0.scale = 2;
					text0.update();
					
					$(scope.elmName).css("padding-top", 249);
					Tween.motion(text0, [ "alpha", "scale" ], [ 1, 1 ], 0.012, 0, 0.002);
					
					cb_func();
					
				} else {
					//勝ち・負け
					
					text0.y = -30;
					text0.alpha = 0;
					text0.update();
					
					Tween.motion(text0, [ "alpha", "y" ], [ 1, 0 ], 0.003, 0, 0.001, function() {
						Tween.delay(text0, 100, function() {
							Tween.fadeOut(text0, 0.1, function() {
								cb_func();
							});
						});
					});
				}
			});
		});
	};
	
	/*
	 * 初期化
	 */
	this.TextEffect.prototype.reset = function() {
		$(this.elmName + " p").css("display", "none");
		
		for (var i = 0, ln = this.allText.length; i < ln; i++) {
			var text = this.allText[i];
			text.deleteEnterFrame();
			text.alpha = 0;
			text.x = 0;
			text.y = 0;
			text.scale = 1;
			text.update();
			text.visible(false);
		}
		
		$(this.elmName).css("padding-top", 249);
	};
	
	
	var Indicator = this.Indicator;
	var JankenNavi = this.JankenNavi;
	var JankenPanel = this.JankenPanel;
	var Character = this.Character;
	var TextEffect = this.TextEffect;
	
	
	/*******************************************************************
	 * ゲーム画面 ビュークラス
	 ******************************************************************/
	 
	this.JankenGame = function(baseScale) {
		
		this.container = "div.gameContainer";
		
		//背景キャラの画像パス(config.xmlから定義される)
		this.charasSrc1 = [];
		this.charasSrc2 = [];
		this.charasSrc3 = [];
		
		//じゃんけんUIの位置調整(下付け)
		var h = window.innerHeight / baseScale;
		$("div.jankenContainer").css("top", h - 117);
		
		this.settingFinalButtonContainer(baseScale);
		
		//ゲームオーバーテキスト
		var textGameOver = new Sprite($("p.textGameOver"));
		textGameOver.setAnchor(0, 0);
		textGameOver.scale = baseScale;
		textGameOver.update();
		textGameOver.element.css("top", 253 * baseScale);
		textGameOver.element.css("left", (window.innerWidth - (344 * baseScale)) / 2);
		
		//何回戦かのインデックス(0～2)
		this.currentBattleIndex;
		
		//UIクラス
		this.indicator = new Indicator(baseScale);
		this.jankenNavi = new JankenNavi(baseScale);
		this.jankenPanel = new JankenPanel(baseScale);
		this.chara = new Character(baseScale);
		this.textEffect = new TextEffect(baseScale);
		
		var scope = this;
		
		//イベントセット
		this.jankenNavi.addEventListener("select", function(args) {
			scope.selectJanken(args.type);
		});
		
		this.indicator.addEventListener("clickStop", function(args) {
			scope.dispatchEvent({type:"backTop"});
		});
		
		$("div.clearContainer p.btnGetStamp a").bind("click", function() {
			//スタンプ画面を表示
			$("div.stampContainer").css("display", "block");
			$("div.commonBgContainer").css("display", "block");
			$("div.stampHideContainer").css("display", "none");
			scope.finalButtonContainerVisible(false);
		});
		
		$("div.resizeContainer div.stampContainer p.btnBack").bind("click", function() {
			//スタンプ画面を非表示
			$("div.stampContainer").css("display", "none");
			$("div.commonBgContainer").css("display", "none");
			$("div.stampHideContainer").css("display", "block");
			scope.finalButtonContainerVisible(true);
		});
	};
	
	extend(this.JankenGame, ViewBase);
	
	/*
	 * 表示するスタンプをランダム選出
	 */
	this.JankenGame.prototype.shuffleShowStamps = function() {
		
		var rnd = Math.random();
		var val = (this.stamps.length * rnd) >> 0;
		//trace("shuffleShowStamps " + rnd + " " + val);
		
		for (var i = 0, ln = this.stamps.length; i < ln; i++) {
			$(this.stamps[i]).css("display", (i == val) ? "block" : "none");
		}
	};
	
	/*
	 * 初期化
	 */
	this.JankenGame.prototype.reset = function() {
		
		$(this.container).css("display", "block");
		this.currentBattleIndex = 0;
		
		$("div.clearContainer").css("display", "none");
		$("div.gameOverContainer").css("display", "none");
		$("div.stampContainer").css("display", "none");
		this.finalButtonContainerVisible(false);
	};
	
	/*
	 * プリロード
	 */
	this.JankenGame.prototype.loading = function(cb_func) {
		if (this.isLoaded) {
			cb_func();
		} else {
			
			var scope = this;
			var images = [
				/*"chara1_1.png",
				"chara1_2.png",
				"chara1_3.png",
				"chara2_1.png",
				"chara2_2.png",
				"chara2_3.png",
				"chara3_1.png",
				"chara3_2.png",
				"chara3_3.png",
				"chara3_4.png",*/
				"parts_game.png"
			];
			
			for (var i = 0, ln = this.charasSrc1.length; i < ln; i++) {
				images.push(this.charasSrc1[i]);
			}
			
			for (var i = 0, ln = this.charasSrc1.length; i < ln; i++) {
				images.push(this.charasSrc1[i]);
			}
			
			for (var i = 0, ln = this.charasSrc1.length; i < ln; i++) {
				images.push(this.charasSrc1[i]);
			}
			
			var loader = new MultipleLoader();
			
			for (var i = 0, ln = images.length; i < ln; i++) {
				loader.addImageRequest("/bundles/machigaigame/GameJanken/images/" + images[i]);
			}
			
			loader.addEventListener("complete", function(args) {
				scope.chara.charaImages1 = scope.charasSrc1;
				scope.chara.charaImages2 = scope.charasSrc2;
				scope.chara.charaImages3 = scope.charasSrc3;
				
				scope.isLoaded = true;
				scope.loadImages = loader.imageRequests;
				cb_func();
			});
			loader.load();
		}
	};
	
	/*
	 * ゲーム開始
	 */
	this.JankenGame.prototype.show = function() {
		this.reset();
		this.startJanken(0);
	};
	
	/*
	 * コンテンツ隠す(処理の停止)
	 */
	this.JankenGame.prototype.hide = function(cb_func) {
		
		this.indicator.reset();
		this.jankenNavi.reset();
		this.jankenPanel.reset();
		this.chara.reset();
		this.textEffect.reset();
		
		this.stopShineAnim();
		
		$(this.container).css("display", "none");
		cb_func();
	};
	
	/*
	 * じゃんけん開始
	 * index : 0 - 2 (1回戦～3回戦）
	 */
	this.JankenGame.prototype.startJanken = function(index) {
		
		var scope = this;
		
		this.chara.show(index, function() {
			scope.textEffect.startAnim(index, function() {
				scope.jankenNavi.enabled(true);
			});
		});
	};
	
	/*
	 * じゃんけん選択時(ポン！)
	 * type : 0 - 2 (グー・チョキ・パー）
	 */
	this.JankenGame.prototype.selectJanken = function(type) {
		
		var scope = this;
		
		var enemyIndex = Math.round(Math.random() * 2);
		
		//テスト用で敵はずっとグーを出す
		//enemyIndex = 0;
		
		//結果（0:あいこ、1:勝ち、2:負け）
		//var result = 0;
		
		if (type == enemyIndex) {
			//あいこ
			result = 0;
			
		} else if (((type == 0) && (enemyIndex == 1)) ||	//グー　(敵)チョキ
							 ((type == 1) && (enemyIndex == 2)) ||	//チョキ　(敵)パー
							 ((type == 2) && (enemyIndex == 0))) {	//パー　(敵)グー
			//勝ち
			result = 1;
			
		} else {
			//負け
			result = 2;
		}
		
		this.textEffect.showJudge(result, function() {
			
			scope.jankenPanel.reset();
			scope.jankenNavi.reset();
			
			switch(result) {
				case 0:
					//あいこ
					scope.jankenNavi.enabled(true);
					break;
				case 1:
					//勝ち
					scope.indicator.countUp();
					scope.textEffect.reset();
					
					if (++scope.currentBattleIndex >= 3) {
						//3連勝
						$("div.clearContainer").css("display", "block");
						scope.finalButtonContainerVisible(true);
						
						scope.startShineAnim();
						
						scope.indicator.hideStopBtn();
						
						scope.shuffleShowStamps();
					} else {
						//次の対戦へ
						scope.startJanken(scope.currentBattleIndex);
					}
					break;
				case 2:
					//負け
					$("div.gameOverContainer").css("display", "block");
					scope.finalButtonContainerVisible(true);
					scope.indicator.hideStopBtn();
					break;
				default:break;
			}
		});
		
		this.jankenPanel.show(enemyIndex, function() {
			
		});
	};
	
	var JankenTitle = this.JankenTitle;
	var JankenGame = this.JankenGame;
	
	
	
	/**********
	 ********** 
	 ********** 共通処理系
	 **********
	 **********/
	
	/*******************************************************************
	 * メインクラス
	 ******************************************************************/
	 
	this.JankenMain = function(baseScale) {
		
		this.__super__(567);
		
		//背景画像
		this.fitScreenImage($("body"), 360, 567);
		
		//遊びかたの初期化
		this.initializeHelp();
		
		//ビュークラス
		this.titleView = new JankenTitle(this.baseScale);
		this.gameView = new JankenGame(this.baseScale);
		
		var scope = this;
		
		this.titleView.addEventListener("clickStart", function(args) {
			scope.changeView(scope.gameView);
		});
		
		this.gameView.addEventListener("backTop", function(args) {
			scope.changeView(scope.titleView);
		});
		
		this.currentView = this.titleView;
		
		this.stamps = [];
		
		var scope = this;
		
		loadXML("/bundles/machigaigame/GameJanken/config.xml", function(xml) {
			
			var stamps = $(xml).find("stamp");
			
			for (var i = 0, ln = stamps.length; i < ln; i++) {
				var stamp_url = $(stamps[i]).attr("src");
				
				//スタンプ画像のリサイズ
				var img = scope.gameView.resizeStampImage(stamp_url, 110, scope.baseScale);
				$(img).css("display", "none");
				scope.stamps.push(img);
			}
			
			scope.gameView.stamps = scope.stamps;
			
			scope.hideLoading(function() {
				scope.currentView.show();
			});
			
			var charas1 = $(xml).find("battle1").find("chara");
			var charas2 = $(xml).find("battle2").find("chara");
			var charas3 = $(xml).find("battle3").find("chara");
			
			var chara_src1 = [];
			var chara_src2 = [];
			var chara_src3 = [];
			
			for (var i = 0, ln = charas1.length; i < ln; i++) {
				chara_src1.push($(charas1[i]).attr("src"));
			}
			
			for (var i = 0, ln = charas2.length; i < ln; i++) {
				chara_src2.push($(charas2[i]).attr("src"));
			}
			
			for (var i = 0, ln = charas3.length; i < ln; i++) {
				chara_src3.push($(charas3[i]).attr("src"));
			}
			
			scope.gameView.charasSrc1 = chara_src1;
			scope.gameView.charasSrc2 = chara_src2;
			scope.gameView.charasSrc3 = chara_src3;
		});
		
		/*this.hideLoading(function() {
			scope.currentView.show();
		});*/
	};
	
	extend(this.JankenMain, MainBase);
	
}).apply(smartpass.view.janken);


smartpass.complete = function() {
	new smartpass.view.janken.JankenMain();
};

