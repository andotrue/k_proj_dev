var PopupLayer = cc.Layer.extend({
    state: null,
    clock: null,
    baseLayer: null,
    MIDDLE_Y: 704,
    playInfo: null,
    ctor:function (type,scene) {
        cc.log("PopupLayer.ctor");
        this._super();
        this.scene = scene;
        this.baseLayer = scene.baseLayer;
        this.clock = scene.clock;
        this.playInfo = this.baseLayer.playInfo;
        this.init(type);
    },
    init:function (type) {
        cc.log("PopupLayer.init");
        var bRet = false;
        if (this._super()) {
            cc.log("loging");
//            this.setTouchEnabled(true);
//            this.setTouchPriority(100);
            cc.log("setTouch");

            switch(type){
                case 'GIVEUP':
                    this.popupGiveup();
                    break;
                case 'SAVE':
                    this.popupSave();
                    break;
                case 'HINT':
                    this.popupHint();
                    break;
                case 'PLAY':
                    this.popupPlay();
                    break;
                case 'GAMEOVER_SUCCESS':
                    this.popupGameoverSuccess();
                    break;
                case 'GAMEOVER_FAIL':
                    this.popupGameoverFail();
                    break;
                case 'LINKURL':
                    this.popupLinkUrl();
                    break;
				case 'MAIN_MENU':
					this.popupMainMenu();
					break;
                default:
            }

            bRet = true;
        }
        cc.log("PopupLayer.init Finished");
        return bRet;
    },

    initSelf:function(){

/*        this.setContentSize(cc.size(720,1280));
        this.setStartColor(cc.c3b(0,128,0));
        this.setEndColor(cc.c3b(0,0,128));
        this.setStartOpacity(64);
        this.setEndOpacity(64);
        var blend =  new cc.BlendFunc();
        blend.src = cc.GL_SRC_ALPHA;
        blend.dst = cc.GL_ONE_MINUS_SRC_ALPHA;
        this.setBlendFunc(blend);
*/
        this.setPosition(360,this.MIDDLE_Y );
    },


    onUpdate:function (delta){
        cc.log("PopupLayer.onUpdate");
    },
   /**
     * default implements are used to call script callback if exist<br/>
     * you must override these touch functions if you wish to utilize them
     * @param {cc.Touch} touch
     * @param {event} event
     * @return {Boolean}
     */
    onTouchBegan:function (touch, event) {
        cc.log("Popup.onTouchBegan");
		
		if(this.slider){
			var slideicon = this.slider.slideicon;
			var touched = touch.getLocation();

			if(this.baseLayer.isInside(slideicon,touched)){
				cc.log("Inside the slideicon area!");
				this.canMoveSlider = true;
			}
		}
        return true;
    },

    /**
     * callback when a touch event moved
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchMoved:function (touch, event) {
        cc.log("Popup.onTouchMoved");
        if (this.canMoveSlider === true){
	        cc.log("can move slider");
            this.slider.move(touch);
            var ratio = this.slider.getRatio();
            this.baseLayer.illusts.scaleIllusts(ratio);
            return true;
        }
        return true;
    },

    /**
     * callback when a touch event finished
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchEnded:function (touch, event) {
        cc.log("Popup.onTouchEnded");
        switch (this.state){
            case "PLAY":
                cc.log("Popup.onTouchEnded: PLAY. now removing self. start the timer.");

                var baseLayer = this.baseLayer;

				if(baseLayer.playInfo._playData.isSaved()){
					// 表示更新
					baseLayer.dispSaveData();
					var illust1 = baseLayer.illusts.frames[0];
					var illust2 = baseLayer.illusts.frames[1];
					illust1.setImage(illust1.MODE_SCALE);
					illust2.setImage(illust2.MODE_SCALE);
				}

                this.baseLayer.clock.startTimer();
                this.removeFromParent();
                break;
            case "GAMEOVER_SUCCESS":
                this.gameoverSuccess();
                break;
            case "GAMEOVER_FAIL":
                this.gameoverFail();
                break;
        }
        return true;
    },

    popupLoading:function () {
        this.state = "LOADING";
        var PopupQuestiondownload = cc.Sprite.create( gsDir + "popup/questiondownload.png" );
        PopupQuestiondownload.setPosition(360,this.MIDDLE_Y );
        this.addChild(PopupQuestiondownload);
    },

    popupMiss:function () {
        this.state = "MISS";
        var label = cc.Sprite.create( gsDir + "label/miss.png" );
        this.addChild(label);
		var popupY = this.getPopupPosAndScrollTop();

		popup.setPosition(360, popupY );
    },

    popupClear:function () {
        this.state = "CLEAR";
        var label = cc.Sprite.create( gsDir + "label/clear.png" );
        this.addChild(label);
		var popupY = this.getPopupPosAndScrollTop();

		popup.setPosition(360, popupY );
    },

    popupPlay:function () {
        this.state = "PLAY";
        var ss = ["popup/popup_gamestart.png", "popup/popup_gamestart_first.png", "popup/popup_gamestart_guest.png","popup/popup_gamestart_notfirst.png"];
        var i =this.game_status = 1;
        if (this.playInfo.isGuest()){
            i = 2;
        }else{
            var status = this.playInfo._playData._gameStatus;
            switch(status){
                case 1:
                    i = 1;
                    break;
                case 2:
                    i = 3;
                    break;
                case 3:
                case 4:
                    i = 0;
                    break;
            }
        }
        var popup = cc.Sprite.create( gsDir + ss[i] );
        this.addChild(popup);
        popup.setPosition(360,this.getContentSize().height - 500);
    },
	popupMainMenu:function() {
		this.playInfo.clock.interruptTimer();
        this.state = "MAIN_MENU";

        var popup = cc.Sprite.create( gsDir + "popup/mainmanu.png" );
        this.addChild(popup);

		var popupY = this.getPopupPosAndScrollTop();
        popup.setPosition(360, popupY);

		this.initMenu();

		this.slider = new Slider(1.0, 3.0);
		this.addChild(this.slider,18);
		
		this.dispTitle();

        var cancel = this.createCancelButton(640,popupY + 170);
		var menu = cc.Menu.create([cancel]);
		menu.setPosition(0,0);
		this.addChild(menu);
	},
    initMenu:function(){
		
        var popupHint = cc.MenuItemImage.create(
            bd+"res/game_scene/button/game_icon_hint.png",
            bd+"res/game_scene/button/game_icon_hint.png",
            this.popupHint.bind(this)
        );
//        popupHint.setPosition(506, 50);
        popupHint.setPosition(185, 849);
		popupHint.setDisabledImage(
				cc.Sprite.create(bd+"res/game_scene/button/game_icon_hint_gray.png"));
        popupHint.name = "HINT";
        if( this.playInfo._playData._isHintUsed)
            popupHint.setEnabled(false);
		
		// ヒントボタンを無効に
		if(this.baseLayer.getHint){
			this.popupHint.setEnabled(false);
		}
		
        var popupSave = cc.MenuItemImage.create(
            bd+"res/game_scene/button/game_icon_save.png",
            bd+"res/game_scene/button/game_icon_save.png",
            this.popupSave.bind(this)
        );
        popupSave.setPosition(360, 849);
        popupSave.name = "SAVE";

        var popupGiveup = cc.MenuItemImage.create(
            bd+"res/game_scene/button/game_icon_giveup.png",
            bd+"res/game_scene/button/game_icon_giveup.png",
            this.popupGiveup.bind(this)
        );
        popupGiveup.setPosition(535, 849);
        popupGiveup.name = "GIVEUP";

        var qcode = this.playInfo.QCODE;
        var level = this.playInfo.LEVEL;

        var copyrightImage = cc.Sprite.create( bd+"../../../../../sync/game/file/"+level+"/"+qcode+"/copyright");
        copyrightImage.setPosition(436, 750);
        copyrightImage.name = "COPYRIGHT";

        var popupLinkUrl = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_game_link_url.png",
            bd+"res/game_scene/button/button_game_link_url.png",
            this.popupLinkUrl.bind(this)
        );
        popupLinkUrl.setPosition(601, 750);
        popupLinkUrl.name = "LINKURL";
        //copyrightImageがあるかどうかでlinkURLを表示するかどうかを判断する
        var thisbl = this;

        var a = setInterval(function(){
            var isCopyrightImage = ( copyrightImage._contentSize._height !== 0 );
            var menu = null;
            if (isCopyrightImage){
                menu = cc.Menu.create([popupHint,popupSave,popupGiveup,popupLinkUrl]);
            }else{
                menu = cc.Menu.create([popupHint,popupSave,popupGiveup]);
            }

            menu.setPosition(0,0);
            thisbl.addChild(menu);
            thisbl.addChild(copyrightImage);
        }, 500);
    },
	dispTitle: function(){

		// タイトルのマーキー表示

		var labelWidth = 140;
		var labelHeight = 40;
		var labelX = 285;
		var labelY = 750;
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

	},
	popupHint:function () {
//        cc.unregisterTouchDelegate(this);
		
		this.slider.setVisible(false);
		
		this.playInfo.clock.interruptTimer();
        this.state = "HINT";

        var popup = cc.Sprite.create( gsDir + "popup/popup_game_hint.png" );
        this.addChild(popup);

		var popupY = this.getPopupPosAndScrollTop();

        popup.setPosition(360, popupY);
		var yes = this.createYesButton(360,popupY - 84);
		var no = this.createNoButton(360,popupY - 169);
        var cancel = this.createCancelButton(640,popupY + 170);
		var menu = cc.Menu.create([yes,no, cancel]);
		menu.setPosition(0,0);
		this.addChild(menu);
    },
    hint:function () {
		this.playInfo.clock.resumeTimer();
        cc.log("PopupLayer.hint");
		if(this.baseLayer.getHint == false){
			this.baseLayer.dispHint();
            this.baseLayer.playInfo._playData._isHintUsed = true;
			cc.log("PopupLayer.hint:give hint");
		}else{
			cc.log("PopupLayer.hint:already given hint");
		}
    },
    popupSave:function () {

		this.slider.setVisible(false);

		this.playInfo.clock.interruptTimer();
        this.state = "SAVE";

		if(this.playInfo.isGuest()){
	        path = gsDir + "popup/popup_game_save_guest.png";
		} else {
	        path = gsDir + "popup/popup_game_save.png";
		}
//        path = gsDir + this.is_guest ? "popup/save.png" : "popup/save_guest.png";
        var popup = cc.Sprite.create(path);
        this.addChild(popup);

		var popupY = this.getPopupPosAndScrollTop();
		popup.setPosition(360,popupY );

        var yes = null;
		if(this.playInfo.isGuest()){
			yes = this.createGrayYesButton(360,popupY + 65);
		} else {
			yes = this.createYesButton(360,popupY + 65);
		}
        var no = this.createNoButton(360,popupY - 25);
        var cancel = this.createCancelButton(640,popupY + 170);
        var menu = cc.Menu.create([yes,no, cancel]);
        menu.setPosition(0,0);
        this.addChild(menu);

    },
    save:function () {
        this.questionId = this.playInfo.QUESTION_ID;
        var MyForm = document.createElement("FORM");
            document.body.appendChild(MyForm);

        with(MyForm) {
            method = 'post';
            action = '../saveGameData';

			var status = this.playInfo._playData._gameStatus;
			// 中断した時はランキング対象にならない
			if(status == 1){
				status = 2;
			}

            var gameStatus = document.createElement('input');
                gameStatus.setAttribute('name', 'gameStatus');
                gameStatus.setAttribute('value', status);
                MyForm.appendChild(gameStatus);

            var questionId = document.createElement('input');
                questionId.setAttribute('name', 'questionId');
                questionId.setAttribute('value', this.questionId);
                MyForm.appendChild(questionId);

            var isSavedGame = document.createElement('input');
                isSavedGame.setAttribute('name', 'isSavedGame');
                isSavedGame.setAttribute('value', 'true');
                MyForm.appendChild(isSavedGame);

			var playInfoData = {};
			playInfoData["clockData"] = this.playInfo._playData._clockData;
			playInfoData["touchData"] = this.playInfo._playData._touchData;
            playInfoData["isHintUsed"] = this.playInfo._playData._isHintUsed;

			var pidTxt = JSON.stringify(playInfoData);

			var playInfo = document.createElement('input');
			playInfo.setAttribute('name', 'playInfo');
			playInfo.setAttribute('value', pidTxt);
			MyForm.appendChild(playInfo);

			MyForm.submit();
        }
    },
    popupGiveup:function () {
		
		this.slider.setVisible(false);
		
		this.playInfo.clock.interruptTimer();
        this.state = "GIVEUP";
        var popup = cc.Sprite.create( gsDir + "popup/popup_game_giveup.png" );
        this.addChild(popup);

		var popupY = this.getPopupPosAndScrollTop();
        popup.setPosition(360,popupY );

        var yes = this.createYesButton(360,popupY + 4);
        var no = this.createNoButton(360,popupY - 100);
        var cancel = this.createCancelButton(640,popupY + 170);
        var menu = cc.Menu.create([yes,no, cancel]);
        menu.setPosition(0,0);
        this.addChild(menu);

    },
    giveup:function () {
        this.questionId = this.playInfo.QUESTION_ID;

		cc.log("PopupLayer.giveup");
        cc.log(document.location);

		var MyForm = document.createElement("FORM");
            document.body.appendChild(MyForm);

        with(MyForm) {
			method = 'post';
			action = '../saveGameData';

			var status = this.playInfo._playData._gameStatus;
			if( status == 1){
				status = 2;
			}

            var gameStatus = document.createElement('input');
                gameStatus.setAttribute('name', 'gameStatus');
                gameStatus.setAttribute('value', status);
                MyForm.appendChild(gameStatus);

			var questionId = document.createElement('input');
				questionId.setAttribute('name', 'questionId');
				questionId.setAttribute('value', this.questionId);
				MyForm.appendChild(questionId);

			var isSavedGame = document.createElement('input');
				isSavedGame.setAttribute('name', 'isSavedGame');
				isSavedGame.setAttribute('value', 'false');
				MyForm.appendChild(isSavedGame);

			var playInfoData = {};
			playInfoData["clockData"] = this.playInfo._playData._clockData;
			playInfoData["touchData"] = this.playInfo._playData._touchData;

			var pidTxt = JSON.stringify(playInfoData);

			var playInfo = document.createElement('input');
			playInfo.setAttribute('name', 'playInfo');
			playInfo.setAttribute('value', pidTxt);
			MyForm.appendChild(playInfo);

			MyForm.submit();
		}
    },
    popupGameoverSuccess:function(){
        this.state = "GAMEOVER_SUCCESS";
        var popup = cc.Sprite.create( gsDir + "label/popup_game_clear.png" );
        this.addChild(popup);
        popup.setPosition(360,this.MIDDLE_Y );
    },
    gameoverSuccess:function(){
        cc.log("gameoverSuccess");
        this.state = "GAMEOVER_SUCCESS";
        var nextScene = new ResultScene(this.playInfo);
        cc.Director.getInstance().replaceScene(cc.TransitionFade.create(0.5, nextScene, cc.c3b(255,255,255)));
        this.removeFromParent();
//        this._parent.removeFromParent();
    },
    popupGameoverFail:function(){
        cc.log("gameoverFail");
        this.state = "GAMEOVER_FAIL";
        var popup = cc.Sprite.create( gsDir + "label/popup_game_miss.png" );
        this.addChild(popup);
        popup.setPosition(360,this.MIDDLE_Y );
    },
    gameoverFail:function(){
        cc.log("gameoverfail()");
        var nextScene = new ResultScene(this.scene.playInfo);
        cc.Director.getInstance().replaceScene(cc.TransitionFade.create(0.5, nextScene, cc.c3b(255,255,255)));
        this.removeFromParent();
    },
    popupLinkUrl:function () {
        cc.log("popupLinkUrl()");
        this.playInfo.clock.interruptTimer();
        this.state = "LINKURL";

        var popup = cc.Sprite.create( gsDir + "popup/common.png" );
        this.addChild(popup);

        var popupY = this.getPopupPosAndScrollTop();

        popup.setPosition(360, popupY);
        var str = this.createToTopString(360,popupY + 25);
        var yes = this.createYesButton(360,popupY - 54);
        var no = this.createNoButton(360,popupY - 139);
        var cancel = this.createCancelButton(640,popupY + 125);
        var menu = cc.Menu.create([yes,no, cancel]);
        menu.setPosition(0,0);
        this.addChild(menu);
        this.addChild(str);
    },
    linkUrl:function () {
        cc.log("linkUrl()");
        this.questionId = this.playInfo.QUESTION_ID;
        window.location = "/sync/copyright?id="+this.questionId;
    },
    menuCallBack:function (sender) {
        cc.log('PopupLayer.menuCallBack');
        switch(sender.name){
            case 'YES':
                cc.log('YES');
                this.removeFromParent();
                switch(this.state){
                    case 'HINT':
                        this.hint();
                        break;
                    case 'SAVE':
                        this.save();
                        break;
                    case 'GIVEUP':
                        this.giveup();
                        break;
                    case 'LINKURL':
                        this.linkUrl();
                        break;
                }
                break;
            case 'NO':
            case 'CANCEL':
                cc.log('NO');
				this.playInfo.clock.resumeTimer();

                this.removeFromParent();
                break;
			case 'GRAY_YES':
				break;
            default:
                cc.log('default');
        }
    },
	createGrayYesButton:function (x,y) {
        var yes = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_yes_gray.png",
            bd+"res/game_scene/button/button_yes_gray_off.png",
            this.menuCallBack.bind(this)
        );
        yes.setPosition(x, y);
        yes.name = "GRAY_YES";
        return yes;
    },
    createYesButton:function (x,y) {
        var yes = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_yes.png",
            bd+"res/game_scene/button/button_yes_off.png",
            this.menuCallBack.bind(this)
        );
        yes.setPosition(x, y);
        yes.name = "YES";
        return yes;
    },
    createNoButton:function (x,y) {
        var no = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_no.png",
            bd+"res/game_scene/button/button_no_off.png",
            this.menuCallBack.bind(this)
        );
        no.setPosition(x, y);
        no.name = "NO";
        return no;
    },

    createCancelButton: function(x,y){
        var cancel = cc.MenuItemImage.create(
            bd+"res/game_scene/button/popup_icon_cancel.png",
            bd+"res/game_scene/button/popup_icon_cancel.png",
            this.menuCallBack.bind(this)
        );
        cancel.setOpacity(0);
        cancel.setPosition(x, y);
        cancel.name = "CANCEL";
        return cancel;
    },
    createToTopString:function(x,y){
        var toTop = cc.LabelTTF.create("著作権を確認しますか？", "Arial", 35);
        toTop.setPosition(x, y);
        toTop.name = "TOTOP";
        return toTop;
    },
    onEnter:function () {
        cc.log("PopupLayer.onEnter");
       if(sys.platform == "browser")
            cc.registerTargetedDelegate(1, true, this);
        else
            cc.registerTargettedDelegate(1,true,this);
        this._super();
    },
    onExit:function () {
        cc.log("PopupLayer.onExit");
        cc.unregisterTouchDelegate(this);
        this.clock = null;
        this._super();
    },
	getPopupPosAndScrollTop:function(){
		var diff = this.baseLayer.HEIGHT - this.getContentSize().height;
		this.baseLayer.setPositionY(-diff);
		var popupY = diff + this.getContentSize().height - 500;
		return popupY;
	}
});