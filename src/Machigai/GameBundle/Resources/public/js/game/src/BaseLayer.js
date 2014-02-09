var BaseLayer = cc.Layer.extend({
    HEIGHT: 1408,
    MIDDLE_Y: 704,
    MACHIGAI_Y: 1370,
    OTETSUKI_Y: 1316,
    illusts: null,
    slider: null,
    parent: null,
    playInfo: null,
    clock: null,
    objs: null, // これまで押した点 {'x', 'y'}の記録
    isOK: null,
    answeredPoints: [],
	getHint: false,
	title: null,
	//コンストラクタ
    ctor:function (parent, playInfo) {
        cc.log("BaseLayer.ctor");
        this._super();
        this.playInfo = playInfo;
        this.init(parent);

        //正答を初期化
        for(var i=0; i< this.playInfo.MACHIGAI_LIMIT; i++){
            this.answeredPoints.push(false);
        }

        var objs = this.playInfo.getTouchData();
        if (objs === null){
            this.objs = {};
        }else{
            this.objs = objs;
        }
    },
    init:function (parent) {
		
		this.onIllust0 = false;
		this.onIllust1 = false;
		
        cc.log("BaseLayer.init");
        var bRet = false;
        if (this._super()) {
            this.parent = parent;
            this._initSelf();

            var bg  = cc.Sprite.create( gsDir + "background/game_bg_web.png");

            cc.log("");
            //イラスト描画エリア設定
//            cc.log("IllustLayer.setIllustFullTargetRect(" + 0 + ", " + Footer._rect.height + ", " + width + ", " + height + ")");
            this.illusts = new IllustLayer(cc.rect(0,0,0,0), this.playInfo.LEVEL, this.playInfo.QCODE, this.playInfo.MACHIGAI_POINT_DATA );
            this.addChild(this.illusts,0);
            this.addChild(bg, -100 );
            bg.setAnchorPoint(0, 0);
            bg.setPosition(0, 0 );


            var LabelOtetsuki = cc.Sprite.create( gsDir + "label/game_otetsuki.png" );
            var LabelMachigai = cc.Sprite.create( gsDir + "label/game_machigai.png" );
            var LabelTimelimit = cc.Sprite.create( gsDir + "label/game_timelimit.png" );
			
            this.initStarsAndHearts();
           
            //Layerの子要素に。
            this.addChild(LabelOtetsuki);
            this.addChild(LabelMachigai);
            this.addChild(LabelTimelimit);

			//TODO: Copyright

            LabelMachigai.setPosition(350, this.MACHIGAI_Y);
            LabelOtetsuki.setPosition(350, this.OTETSUKI_Y);
            LabelTimelimit.setPosition(100,1385);

            this.clock = this.playInfo.clock;
            this.addChild(this.clock,15);

            this.slider = new Slider(1.0, 3.0);
            this.addChild(this.slider,18);

            this.initMenu();

			var thr = this.HEIGHT - this.getContentSize().height;
			this.setPositionY(-thr);

            bRet = true;

			// マーキーの表示
			this.dispTitle();
        }
        return bRet;
    },
	dispSaveData:function(){
		
		if(this.dispSaveDataCallFlg)return;
		
		this.dispSaveDataCallFlg = true;
		
		if(this.playInfo._playData._touchData){
			for(var i in this.playInfo._playData._touchData){
				var data = this.playInfo._playData._touchData[i];

				var objs = this.playInfo.MACHIGAI_POINT_DATA;
				var trueFlg = false;
				
				for( var i in objs ){
					var ap = objs[i];

					var apx = parseInt(ap.x);
					var apy = parseInt(ap.y);

					if( apx == data.x && apy == data.y ){

						this.answeredPoints[i] = true;
						this.stars.increment();
						trueFlg = true;
					}
				}
				
				if(!trueFlg){
			        this.hearts.decrement();
				}
			}
		}
	},
    _initSelf:function(){
        cc.log("BaseLayer._initSelf()");
        this.setAnchorPoint(0,0);
        this.setPosition(0,0);
    },
    initStarsAndHearts:function(){
        self = this;
        this.stars = new Stars(self,this.playInfo.MACHIGAI_LIMIT);
        this.hearts = new Hearts(self, this.playInfo.FAIL_LIMIT);
    },
    initMenu:function(){
        var popupHint = cc.MenuItemImage.create(
            bd+"res/game_scene/button/game_icon_hint.png",
            bd+"res/game_scene/button/game_icon_hint.png",
            this.menuCallBack.bind(this)
        );
//        popupHint.setPosition(506, 50);
        popupHint.setPosition(185, 259);
		popupHint.setDisabledImage(
				cc.Sprite.create(bd+"res/game_scene/button/game_icon_hint_gray.png"));
        popupHint.name = "HINT";
		this.popupHint = popupHint;

        var popupSave = cc.MenuItemImage.create(
            bd+"res/game_scene/button/game_icon_save.png",
            bd+"res/game_scene/button/game_icon_save.png",
            this.menuCallBack.bind(this)
        );
        popupSave.setPosition(360, 259);
        popupSave.name = "SAVE";

        var popupGiveup = cc.MenuItemImage.create(
            bd+"res/game_scene/button/game_icon_giveup.png",
            bd+"res/game_scene/button/game_icon_giveup.png",
            this.menuCallBack.bind(this)
        );
        popupGiveup.setPosition(535, 259);
        popupGiveup.name = "GIVEUP";

        var menu = cc.Menu.create([popupHint,popupSave,popupGiveup]);
        menu.setPosition(0,0);
        this.addChild(menu);
    },
    onTouchesBegan: function(touches, event){
        cc.log('onTouchesBegan:' + touches.length);
        this.onTouchBegan(touches[0], event);
    },
    onTouchesMoved: function(touches, event){
        cc.log('onTouchesMoved' + touches.length);
        this.onTouchMoved(touches[0], event);
    },
    onTouchesEnded: function(touches, event){
        cc.log('onTouchesMoved' + touches.length);
        this.onTouchEnded(touches[0], event);
    },
    onTouchBegan:function (touch, event) {
        cc.log("Base.onTouchBegan: ( " + touch.getLocation().tox + ", " + touch.getLocation().y + " )");

        var touched = this.touchedFrom = touch.getLocation();
        var slidebar = this.slider.slidebar;
        var slideicon = this.slider.slideicon;

        if(this.isInside(slidebar,touched)){
            cc.log("Inside the slidebar area!");
        }

        if(this.isInside(slideicon,touched)){
            cc.log("Inside the slideicon area!");
            this.canMoveSlider = true;
        }
		// ２つのイラスト上にポイントがあるかをチェック
		var ill0 = this.illusts.frames[0].illust;
		var point0 = ill0.convertToNodeSpace(touch.getLocation());
		var ill0R  = ill0.getTextureRect();
		var ill1 = this.illusts.frames[1].illust;
		var ill1R  = ill1.getTextureRect();
		var point1 = ill1.convertToNodeSpace(touch.getLocation());
		
		this.onIllust0 = false;
		this.onIllust1 = false;
		
		if(point0.x >= 0 && point0.x <= ill0R.width && point0.y >= 0 && point0.y <= ill0R.height) {
			
			this.onIllust0 = true;
			
		} else if (point1.x >= 0 && point1.x <= ill1R.width && point1.y >= 0 && point1.y <= ill1R.height) {

			this.onIllust1 = true;
		}
		
		if( this.onIllust0 || this.onIllust1 ){
			this.canMoveIllust = true;
            this.isIllustTouched = true;
            cc.log("Inside the slideicon area!");
        }

        return true;
    },

    /**
     * callback when a touch event moved
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchMoved:function (touch, event) {
//        cc.log("Base.onTouchMoved ( " + touch.getLocation().x + ", " + touch.getLocation().y + " )");
        if (this.canMoveSlider === true){
            this.slider.move(touch);
            var ratio = this.slider.getRatio();
            this.illusts.scaleIllusts(ratio);
            return true;
        }
		this.touchedTo = touch.getLocation();
		var dx = this.touchedTo.x - this.touchedFrom.x;
		var dy = this.touchedTo.y - this.touchedFrom.y;
		
		if (this.canMoveIllust === true &&
				(this.onIllust0 || this.onIllust1) ){
            this.illusts.move(dx, dy,touch);
            this.touchedFrom = this.touchedTo;
        } else {
			
			//cc.log("rect " + this.getRect().height);
			
			
			var thr = this.HEIGHT - this.getContentSize().height;
			var posy = this.getPositionY() + dy;

			if( -thr < posy && posy < 0 ){
				this.setPositionY(posy);
			} else if (-thr >= posy ) {
				this.setPositionY(-thr);
			} else {
				this.setPositionY(0);
			}
		}
        if (this.isIllustTouched === true){
            this.isIllustTouched = false;
        }
        return true;
    },

    /**
     * callback when a touch event finished
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchEnded:function (touch, event) {
        cc.log("Base.onTouchEnded ( " + touch.getLocation().x + ", " + touch.getLocation().y + " )");
        cc.log("Delegate Event to objects.");

        touched = touch.getLocation();

        if( this.isIllustTouched === true ){
            this.checkAnswer(touch);
        }

        this.canMoveIllust = null;
        this.canMoveSlider = null;
        this.isIllustTouched = null;

        this.checkGameOver();

        return true;
    },
    checkAnswer:function (touch){
		
		var margin = 50;
		
		// ポイントを取得
        var deviceLocation = touch.getLocation();
        cc.log(" touched point in device location: ( " + deviceLocation.x +  "," + deviceLocation.y + ")" );
		var illustF = this.illusts.frames[0];
		
		var point = this.illusts.frames[0].illust.convertToNodeSpace(deviceLocation);
		if( this.onIllust1 ){
			point = this.illusts.frames[1].illust.convertToNodeSpace(deviceLocation);
		}
		cc.log(" touched point in  iilust frame: ( " + point.x + ", " + point.y + ")");

		var makeX = point.x + illustF.currentX;
		var makeY = (illustF.illust.getContentSize().height - point.y) + illustF.currentY;
		cc.log(" imageX imageY : ( " + makeX + ", " + makeY + ")");

		// 正解ポイントの取得
		var objs = this.playInfo.MACHIGAI_POINT_DATA;
		
        var trueFlag = false;
		for( var i in objs ){
            var ap = objs[i];

            var apx = parseInt(ap.x);
            var apy = parseInt(ap.y);
            var px  = makeX;
            var py  = makeY;    // 座標系の変換

            cc.log(apx + " " + apy + " " + px + " " + py);
            
            if( apx - margin < px && apx + margin > px &&
                apy - margin < py && apy + margin > py ){
                if( !this.answeredPoints[i] ){
                       cc.log(" touch OK ! ");
                       this.answeredPoints[i] = true;
                       var date = new Date();
                       var touchDatum = {x: apx, y:apy, result:true , touchedAt: date };
                       this.playInfo._playData.setTouchData(touchDatum);

                       return this.runOK();
                }else{
                    trueFlag = true;
                }
            }
        }

        if( trueFlag === false ){
            var date = new Date();
            touchDatum = {x: px, y:py, result:false , touchedAt: date };
            this.playInfo._playData.setTouchData(touchDatum);
			
			   return this.runNG();
	           cc.log(" touch  ! ");            
        }
    },
    runOK:function () {

		var illust = this.illusts.frames[0];
		illust.setImage(illust.MODE_SCALE);
		this.stars.increment();
    },
    runNG:function () {
		var upperPos = this.getUpperPos();
		
		//        cc.runAction();
		
		var upperNg = cc.Sprite.create( gsDir + "other/ng.png" );
		var lowerNg = cc.Sprite.create( gsDir + "other/ng.png" );
		this.illusts.frames[0].illust.addChild(upperNg);
		this.illusts.frames[1].illust.addChild(lowerNg);
		
		upperNg.setPosition(upperPos.x, upperPos.y);
		lowerNg.setPosition(upperPos.x, upperPos.y);

		setTimeout(function(){
			upperNg.removeFromParent();
			lowerNg.removeFromParent();
		}, 3000);
		
        this.hearts.decrement();
    },
	getUpperPos: function() {
		
		var illust = this.illusts.frames[0].illust;
		if( this.onIllust1 ){
			illust = this.illusts.frames[1].illust;
		}
		var point = illust.convertToNodeSpace(touched);

	   return point;
	},
    checkGameOver:function (){
        cc.log("checkGameOver : " + this.stars.count() + ", " + this.hearts.count());
        if(this.stars.count() == this.stars._MAX){
            this.gameoverSuccess();
        }else if(this.hearts.count() == this.hearts._MIN){
            this.gameoverFail();
        }else{

        }

    },
    gameoverSuccess:function(){
        this.playInfo.setSucceed();
        var popup = new PopupLayer("GAMEOVER_SUCCESS",this.parent);
        popup.init("GAMEOVER_SUCCESS");
        this.addChild(popup);
    },
    gameoverFail:function(){
        this.playInfo.setFail();
        var popup = new PopupLayer("GAMEOVER_FAIL",this.parent);
        popup.init("GAMEOVER_FAIL");
        this.addChild(popup);
    },
    menuCallBack:function(sender){
        var popup = new PopupLayer(this.parent,this.parent);
        popup.init(sender.name);
        this.addChild(popup);
    },
    onEnter:function () {
        cc.log("Base.onEnter");
       if(sys.platform == "browser")
            cc.registerTargetedDelegate(2, true, this);
        else
            cc.registerTargettedDelegate(2,true,this);
        this._super();
    },
    onExit:function () {
        cc.log("Base.onExit");
        cc.unregisterTouchDelegate(this);
        this.parent = null;
        this._super();
    },
    isInside:function(node,position){
        var bounding = node.getBoundingBoxToWorld();
        return ( (position.x > bounding.x ) && (position.x < bounding.x + bounding.width)
            && (position.y > bounding.y ) && (position.y < bounding.y + bounding.height));
    },
	dispHint:function(){
		this.getHint = true;

		// ヒントボタンを無効に
		this.popupHint.setEnabled(false);

		// 既に正解した答えの番号を取得
		var alreadyAnsweredIndex = new Array();
		var j = 0;
		for( var i in this.answeredPoints){
			if(this.answeredPoints[i] !== true){
				alreadyAnsweredIndex[j] = i;
				cc.log("未正解ポイントのインデックス: ( " + i + ")");
				cc.log("未正解ポイントの数: ( " + j + ")");
				j++;
			}
		}
		// 正解ポイントの取得
		var apObj = this.playInfo.MACHIGAI_POINT_DATA;
		// ヒントになる座標を取得
		var pointHint = apObj[alreadyAnsweredIndex[parseInt(Math.random()*j)]];

		var upperPict = this.illusts.frames[0];
		var lowerPict = this.illusts.frames[1];
		upperPict.scale = 1.0;
		lowerPict.scale = 1.0;
		upperPict.setImage(upperPict.MODE_SCALE);
		lowerPict.setImage(lowerPict.MODE_SCALE);

		var upperPictIllust = upperPict.illust;
		var lowerPictIllust = lowerPict.illust;
		
		var apx = parseInt(pointHint.x);
		var apy = parseInt(pointHint.y);

		// 座標系の変換
		apy = upperPictIllust.getContentSize().height - apy;

		apx = apx - upperPict.currentX;
		apy = apy + upperPict.currentY;

		var upperHint = cc.Sprite.create( gsDir + "other/game_hint.png" );
		var lowerHint = cc.Sprite.create( gsDir + "other/game_hint.png" );

		upperPictIllust.addChild(upperHint);
		lowerPictIllust.addChild(lowerHint);

		upperHint.setPosition(apx, apy);
		lowerHint.setPosition(apx, apy);
		
		var opa = 255;
		var minus = 20;
		
		var eventId = setInterval(
			function(){
				if(opa - minus <= 0){
					upperPictIllust.removeChild(upperHint);
					lowerPictIllust.removeChild(lowerHint);
					clearInterval(eventId);
					opa = 0;
				} else {
					opa -= minus;
					upperHint.setOpacity(opa);
					lowerHint.setOpacity(opa);
				}
			}, 100
		);
		
//		setTimeout( function(){
//			dis.upperHint.setVisible(false);
//			dis.lowerHint.setVisible(false);
//		}, 3000);
		
		return true;
	},
	updateAnswerMark: function(new_scale){
		
		var objs = this.playInfo.MACHIGAI_POINT_DATA;
		
		for( var i in objs ){
            var ap = objs[i];
            var apx = parseInt(ap.x);
            var apy = parseInt(ap.y);

			if( this.answeredPoints[i] ){
				
				var upperPict = this.illusts.frames[0];
				var upperPictIllust = this.illusts.frames[0].illust;
				var lowerPictIllust = this.illusts.frames[1].illust;

				if(upperPict.currentX <= apx &&
				   upperPict.currentX + upperPict.currentWidth >= apx &&
				   upperPict.currentY <= apy &&
				   upperPict.currentY + upperPict.currentHeight >= apy){

					var upperOk = cc.Sprite.create( gsDir + "other/ok.png" );
					var lowerOk = cc.Sprite.create( gsDir + "other/ok.png" );

					var okSize		= upperOk.getContentSize();
					var okWidth		= okSize.width;
					var okHeight	= okSize.height;
					var okLeft		= 0;
					var okTop		= 0;
					
					var leftAp		= apx - (okWidth / 2);
					var topAp		= apy - (okHeight / 2);

					var curRight	= upperPict.currentX + upperPict.currentWidth;
					var right		= leftAp + okWidth;
					var curBottom	= upperPict.currentY + upperPict.currentHeight;
					var bottom		= topAp + okHeight;
					
					if( upperPict.currentX > leftAp ){
						//左を切る
						okLeft	= upperPict.currentX - leftAp;
						okWidth = okWidth - okLeft;
						apx		= apx + (okLeft / 2);
					} else if( curRight < right ){
						//右を切る
						okWidth	= okWidth - (right - curRight);
						okLeft	= curRight - right;
						apx		= apx + (okLeft / 2);
					}
					if( upperPict.currentY > topAp ){
						//上を切る
						okTop	= upperPict.currentY - topAp;
						okHeight = okHeight - okTop;
						apy		= apy + (okTop / 2);
					} else if( curBottom < bottom ){
						//下を切る
						okHeight= okHeight - (bottom - curBottom);
						okTop	= curBottom - bottom;
						apy		= apy + (okTop / 2);
					}
					
					
					// 座標系の変換
					apy = upperPictIllust.getContentSize().height - apy;

					apx = apx - upperPict.currentX;
					apy = apy + upperPict.currentY;

					if(okLeft < 0)okLeft = 0;
					if(okTop < 0)okTop = 0;

					var rect = cc.rect(okLeft,okTop,okWidth,okHeight);
					upperOk.setTextureRect(rect);
					lowerOk.setTextureRect(rect);
					
					upperOk.setPosition(apx, apy);
					lowerOk.setPosition(apx, apy);

					upperPictIllust.addChild(upperOk);
					lowerPictIllust.addChild(lowerOk);				
				}
			}
		}				
	},
	dispTitle: function(){
		
		// タイトルのマーキー表示
		
		var labelWidth = 140;
		var labelHeight = 40;
		var labelX = 195;
		var labelY = 141;
		var title  = this.playInfo.TITLE;
		var MIN_LENGTH = 500;
		var length = title.length * 40;
		
		if( length < MIN_LENGTH){
			length = MIN_LENGTH;
		}

		var tencil = cc.DrawNodeCanvas.create();
		tencil.drawPoly(
			[
			cc.p(-labelWidth,labelHeight),
			cc.p(-labelWidth,-labelHeight), 
			cc.p(labelWidth, -labelHeight),
			cc.p(labelWidth,labelHeight)
			],
			new cc.Color4F(0,0,0,0),
			0,
			new cc.Color4F(0,0,0,0));
		
		tencil.setPosition(cc.p(labelX,labelY));

		var titleLabel = cc.LabelTTF.create(title, "Arial", 38);
		titleLabel.setPosition(cc.p(labelX + labelWidth,labelY));
		titleLabel.setColor(new cc.Color4F(0,0,0,0));
		titleLabel.setHorizontalAlignment(cc.TEXT_ALIGNMENT_LEFT);
		
		var clipNode = cc.ClippingNode.create(tencil);
		
		clipNode.addChild(titleLabel);
		this.addChild(clipNode);

		var moveQ = length + (labelWidth * 2);

        titleLabel.runAction(cc.MoveBy.create(0, cc.p(labelWidth * 2, 0)));

       	var go = cc.MoveBy.create(10, cc.p(-moveQ, 0));       	
        var goBack = cc.MoveBy.create(0, cc.p(moveQ, 0));        
        var seq = cc.Sequence.create(go, goBack, null);
        titleLabel.runAction((cc.RepeatForever.create(seq) ));
		
	}
});