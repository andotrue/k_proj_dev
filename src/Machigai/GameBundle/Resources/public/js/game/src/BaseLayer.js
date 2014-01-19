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
    ctor:function (parent, playInfo) {
        cc.log("BaseLayer.ctor");
        this._super();
        this.playInfo = playInfo;
        this.init(parent);

        var objs = this.playInfo.getClickPointsData();
        if (objs === null){
            this.objs = {};
        }else{
            this.objs = objs;
        }

    },
    init:function (parent) {
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

//            var title = cc.LabelTTF("test", "Marker Felt",10);
//            var title = new cc.LabelBMFont();
 //           title.setAnchorPoint(1,1);

            this.initStarsAndHearts();
           
            //Layerの子要素に。
            this.addChild(LabelOtetsuki);
            this.addChild(LabelMachigai);
            this.addChild(LabelTimelimit);

            LabelMachigai.setPosition(350, this.MACHIGAI_Y);
            LabelOtetsuki.setPosition(350, this.OTETSUKI_Y);
            LabelTimelimit.setPosition(100,1385);

            this.ng = cc.Sprite.create( gsDir + "other/ng.png" );
            this.ok = cc.Sprite.create( gsDir + "other/ok.png" );
            this.addChild(this.ng);
            this.addChild(this.ok);

            this.clock = this.parent.clock;
            this.addChild(this.clock,15);

            this.slider = new Slider(1.0, 3.0);
            this.addChild(this.slider,18);

            this.initMenu();

            bRet = true;
        }
        return bRet;
    },
    _initSelf:function(){
        cc.log("BaseLayer._initSelf()");
        this.setAnchorPoint(0,0);
        this.setPosition(0,0);
    },
    initStarsAndHearts:function(){
        self = this;
        this.stars = new Stars(self,this.playInfo.MACHIGAI_POINT);
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
        popupHint.name = "HINT";

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
		
		if(point0.x >= 0 && point0.x <= ill0R.width && point0.y >= 0 && point0.y <= ill0R.height) {
			
			this.onIllust0 = true;
			
		} else if (point1.x >= 0 && point1.x <= ill1R.width && point1.y >= 0 && point1.y <= ill1R.height) {

			this.onIllust1 = true;
		}
		
		if( this.onIllust0 || this.onIllust ){
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
        if (this.canMoveIllust === true){
            this.touchedTo = touch.getLocation();
            var dx = this.touchedTo.x - this.touchedFrom.x;
            var dy = this.touchedTo.y - this.touchedFrom.y;
            this.illusts.move(dx, dy,touch);
            this.touchedFrom = this.touchedTo;
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
		
		var margin = 20;
		
		// ポイントを取得
        var deviceLocation = touch.getLocation();
        cc.log(" touched point in device location: ( " + deviceLocation.x +  "," + deviceLocation.y + ")" );
		var point = this.illusts.frames[0].illust.convertToNodeSpace(deviceLocation);
		if( point.x <= 0){
			point = this.illusts.frames[1].illust.convertToNodeSpace(deviceLocation);
		}
		cc.log(" touched point in  iilust frame: ( " + point.x + ", " + point.y + ")");

		// 解答群の取得
/*		for( var i in this.objs ){
			var ap = this.objs[i];

		var point = this.illusts.frames[0].illust.convertToNodeSpace(touch.getLocation);
		if(this.onIllust1){
			point = this.illusts.frames[1].illust.convertToNodeSpace(touch.getLocation);
		 }
*/	
		// 画像の四角の取得
		var rect = this.illusts.frames[0].illust.getTextureRect();
		
		// 正解ポイントの取得
		var objs = this.playInfo.MACHIGAI_POINT_DATA;
		
		cc.log( objs );
		
		for( var i in objs ){
			var ap = objs[i];

			var apx = parseInt(ap.x);
			var apy = parseInt(ap.y);
			var px  = point.x;
			var py  = rect.height - point.y;	// 座標系の変換

			cc.log(apx + " " + apy + " " + px + " " + py);
			
			if( apx - margin < px && apx + margin > px &&
				apy - margin < py && apy + margin > py ){

               return this.runOK();
                cc.log(" touch OK ! ");
			}
		}

		return this.runNG();
		cc.log(" touch  ! ");
    },
    runOK:function () {
//        cc.runAction();
        this.ok.setPosition(touched.x, touched.y);
//        this.stars.increment();
        this.hearts.decrement();
    },
    runNG:function () {
        this.ng.setPosition(touched.x, touched.y);
        this.stars.increment();
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
        var popup = new PopupLayer("GAMEOVER_SUCCESS",this.parent);
        popup.init("GAMEOVER_SUCCESS");
        this.addChild(popup);
    },
    gameoverFail:function(){
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
    }
});