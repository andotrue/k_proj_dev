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
        return true;
    },

    /**
     * callback when a touch event moved
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchMoved:function (touch, event) {
        cc.log("Popup.onTouchMoved");
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
        var popup = cc.Sprite.create( gsDir + ss[i] );
        this.addChild(popup);
        popup.setPosition(360,this.getContentSize().height - 500);
    },
    popupHint:function () {
//        cc.unregisterTouchDelegate(this);
		this.playInfo.clock.interruptTimer();
        this.state = "HINT";

        var popup = cc.Sprite.create( gsDir + "popup/popup_game_hint.png" );
        this.addChild(popup);

		var popupY = this.getPopupPosAndScrollTop();

        popup.setPosition(360, popupY);
		var yes = this.createYesButton(360,popupY - 84);
		var no = this.createNoButton(360,popupY - 169);
		var menu = cc.Menu.create([yes,no]);
		menu.setPosition(0,0);
		this.addChild(menu);
    },
    hint:function () {
		this.playInfo.clock.resumeTimer();
        cc.log("PopupLayer.hint");
		if(this.baseLayer.getHint == false){
			this.baseLayer.dispHint();
			cc.log("PopupLayer.hint:give hint");
		}else{
			cc.log("PopupLayer.hint:already given hint");
		}
    },
    popupSave:function () {

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
        var menu = cc.Menu.create([yes,no]);
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

			var pidTxt = JSON.stringify(playInfoData);

			var playInfo = document.createElement('input');
			playInfo.setAttribute('name', 'playInfo');
			playInfo.setAttribute('value', pidTxt);
			MyForm.appendChild(playInfo);

			MyForm.submit();
        }
    },
    popupGiveup:function () {
		this.playInfo.clock.interruptTimer();
        this.state = "GIVEUP";
        var popup = cc.Sprite.create( gsDir + "popup/popup_game_giveup.png" );
        this.addChild(popup);

		var popupY = this.getPopupPosAndScrollTop();
        popup.setPosition(360,popupY );

        var yes = this.createYesButton(360,popupY + 4);
        var no = this.createNoButton(360,popupY - 100);
        var menu = cc.Menu.create([yes,no]);
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
        var menu = cc.Menu.create([yes,no]);
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