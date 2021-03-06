var ResultLayer = cc.Layer.extend({
    //以下は固定値
    DEFAULT_FONT_SIZE: 24,
    SHOP_LINK_PATH: null,
    RANKING_PATH: null,
//    MESSAGE_FOR_USER_SUCCESS: "おめでとう！ショップやランキングをチェック",
//    MESSAGE_FOR_GUEST_1_SUCCESS: "おめでとう！他の問題にもチャレンジ！",
//    MESSAGE_FOR_GUEST_2_SUCCESS: "XXXXXX",
//    MESSAGE_FOR_USER_FAIL: "ユーザ失敗",
//    MESSAGE_FOR_GUEST_FAIL: "ゲスト失敗",
    //以下は変数から当てはめる
    acquiredPoint: null,
    clearTime: null,
    currentPoint: null,
    playInfo: null,


    ctor:function (playInfo, isGuest,  clearTime, acquiredPoint, currentPoint ) {
      cc.log("ResultLayer.ctor");
        this.playInfo = playInfo;
        this._super();

        this.acquiredPoint = acquiredPoint;
        this.clearTime = clearTime;
        this.currentPoint = currentPoint;
        this.questionId = 152;
        this.isGuest = isGuest;
        this.questionId= this.playInfo.QUESTION_ID;
        this.isCleared = this.playInfo.isSucceed();

        this.init();
    },

    init:function () {
      cc.log("ResultLayer.init");
        var bRet = false;
        if (this._super()) {
            this.setAnchorPoint(cc.p(0, 0));
            this.setPosition(0,0);

            //バックグランド
            var bg = cc.Sprite.create( gsDir + "background/top_bg.png" );
            bg.setPosition(360, 720 );
            this.addChild(bg);

            if(this.playInfo.isUser()){
//                var acquiredPointSprite = this.createPointSprite(this.acquiredPoint + "pt",360,250,50,0,0,0);
//                var currentPointSprite = this.createPointSprite(this.currentPoint + "pt",360,100,60,0,0,0);
            }
            if (this.playInfo.isSucceed === true){
//               var clearTimeSprite = this.clearTime( Math.Floor(this.clearTime / 1000 )+ "秒",360,350,60,0,0,0);
            }

            if(this.isGuest === true){
                if( this.isCleared === true ){
                    var clearTime = this.playInfo.getClearTime();
                    window.location = "/game/resultGuestClear?clearTime="+clearTime;
                }else{
                    qId = this.playInfo.QUESTION_ID;
                    window.location = "/game/resultGuestFalse?questionId="+qId;
                }
            }else{
                if( this.isCleared === true ) {

                    this.questionId = this.playInfo.QUESTION_ID;
                    var data = this.playInfo._playData.getTouchData();
                    var clearTime = this.playInfo.getClearTime();
                    var MyForm = document.createElement("FORM");
                    document.body.appendChild(MyForm);

                    with(MyForm) {
                        method = 'get';
                        action = '../resultUserClear';
                        var gameStatus = document.createElement('input');
                            gameStatus.setAttribute('name', 'clearTime');
                            gameStatus.setAttribute('value', clearTime);
                            MyForm.appendChild(gameStatus);

                        var questionId = document.createElement('input');
                            questionId.setAttribute('name', 'questionId');
                            questionId.setAttribute('value', this.questionId);
                            MyForm.appendChild(questionId);

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


//                    window.location = "/kanahei/app_dev.php/game/resultUserClear?questionId="+qId+"&clearTime="+clearTime+"&playInfoData="+playInfoData;
                }else{
//                    qId = this.playInfo.QUESTION_ID;
//                    window.location = "/game/resultUserFalse?questionId="+qId;

                    this.questionId = this.playInfo.QUESTION_ID;
                    var data = this.playInfo._playData.getTouchData();

                    var MyForm = document.createElement("FORM");
                    document.body.appendChild(MyForm);

                    with(MyForm) {
                        method = 'get';
                        action = '../resultUserFalse';

                        var questionId = document.createElement('input');
                            questionId.setAttribute('name', 'questionId');
                            questionId.setAttribute('value', this.questionId);
                            MyForm.appendChild(questionId);

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
                }
            }

            bRet = true;
        }
        return bRet;
    },

    initMenuForUserSuccess:function () {
        cc.log("initMenuForUser");
        this.state = "SAVE";
        var path = gsDir + "popup/2-01_3_web_bg_.png";
        var popup = cc.Sprite.create(path);
        this.addChild(popup);
        popup.setPosition(360,380 );

        var acquiredPointSprite = this.createPointSprite(this.acquiredPoint + "pt",360,610,60,0,0,0);
        var currentPointSprite = this.createPointSprite(this.currentPoint + "pt",360,430,60,0,0,0);

        var tryAnother = this.createTryAnotherButton(360,190);
//        var toTop = this.createToTopButton(360,600);
        var toShop = this.createShopButton(360,120);
        var toRanking = this.createRankingButton(360,50);

        tryAnother.setScale(0.7);
        toShop.setScale(0.7);
        toRanking.setScale(0.7);

        var MSG1 = this.createStringSprite(this.MESSAGE_FOR_USER_SUCCESS,360,1000,30,255,255,255);
        var menu = cc.Menu.create([tryAnother,toShop,toRanking]);
        menu.setPosition(0,0);
        this.addChild(menu);

    },
    initMenuForGuestSuccess:function () {
        cc.log("ResultLayer.initMenuForGuest");
        this.state = "SAVE";
        var path = gsDir + "popup/2-01_3_web_bg.png";
        var popup = cc.Sprite.create(path);
        this.addChild(popup);
        popup.setPosition(360,380 );

        var MSG1 = this.createStringSprite(this.MESSAGE_FOR_GUEST_1_SUCCESS,360,1000,30,255,255,255);
        var MSG2 = this.createStringSprite(this.MESSAGE_FOR_GUEST_2_SUCCESS,360,800,30,255,255,255);

        var acquiredPointSprite = this.createPointSprite(this.acquiredPoint + "pt",360,610,60,0,0,0);
        var currentPointSprite = this.createPointSprite(this.currentPoint + "pt",360,430,60,0,0,0);

        var tryAnother = this.createTryAnotherButton(360,180)
        var toTop = this.createToTopButton(360,0);
        tryAnother.setScale(0.7);
        toTop.setScale(0.7);

        var menu = cc.Menu.create([tryAnother,toTop]);
        menu.setPosition(0,0);
        this.addChild(menu);

    },

    initMenuForUserFail:function () {
        cc.log("initMenuForUser");
        this.state = "SAVE";
        var path = gsDir + "popup/2-01_4_web_bg.png";
        var popup = cc.Sprite.create(path);
        this.addChild(popup);
        popup.setPosition(360,470 );

        var MSG1 = this.createStringSprite(this.MESSAGE_FOR_USER_FAIL,360,1000,30,255,255,255);

        var acquiredPointSprite = this.createPointSprite(this.acquiredPoint + "pt",360,700,60,0,0,0);
        var currentPointSprite = this.createPointSprite(this.currentPoint + "pt",360,520,60,0,0,0);

        var retry = this.createRetryButton(360,300);
        var tryAnother = this.createTryAnotherButton(360,230);
        var toShop = this.createShopButton(360,160);
        var toRanking = this.createRankingButton(360,90);

        retry.setScale(0.7);
        tryAnother.setScale(0.7);
        toShop.setScale(0.7);
        toRanking.setScale(0.7);

        var menu = cc.Menu.create([retry,tryAnother,toShop,toRanking]);
        menu.setPosition(0,0);
        this.addChild(menu);

    },
    initMenuForGuestFail:function () {
        cc.log("ResultLayer.initMenuForGuest");
        this.state = "SAVE";
        var path = gsDir + "popup/2-01_4_web_bg_.png";
        var popup = cc.Sprite.create(path);
        this.addChild(popup);
        popup.setPosition(360,470 );

        var MSG1 = this.createStringSprite(this.MESSAGE_FOR_GUEST_FAIL,360,1000,30,255,255,255);

        var acquiredPointSprite = this.createPointSprite(this.acquiredPoint + "pt",360,700,60,0,0,0);
        var currentPointSprite = this.createPointSprite(this.currentPoint + "pt",360,520,60,0,0,0);

        var retry = this.createRetryButton(360,290);
        var tryAnother = this.createTryAnotherButton(360,220);
        var toTop = this.createToTopButton(360,150);
        retry.setScale(0.7);
        tryAnother.setScale(0.7);
        toTop.setScale(0.7);

        var menu = cc.Menu.create([retry,tryAnother,toTop]);
        menu.setPosition(0,0);
        this.addChild(menu);

    },
    initClearTime:function(){
        cc.log("ResultLayer.initClearTime");

    },
    initCurrentPoint:function(){
        cc.log("ResultLayer.initCurrentPoint");

    },
    initAcquiredPoint:function(){
        cc.log("ResultLayer.initAcquiredPoint");

    },

    playOtherGames:function () {
        cc.log("ResultLayer.playOtherGames");
        window.location="../select";
    },

    retry:function (){
        cc.log("ResultLayer.retry");
        window.location="../../game/play/"+this.questionId;
    },
    toShop:function () {
        cc.log("ResultLayer.toShop");
        cc.log(document.location);
        window.location="../../shop";

    },

    toRanking:function () {
        cc.log("ResultLayer.toRanking");

        window.location="../../ranking";
    },

    toTop:function(){
        cc.log("ResultLayer.toTop");

        window.location="../../top";
    },

    tryAnother:function(){
        cc.log("PopupLayer.result");

        window.location="../../game/select";
    },

    toTop:function(){
        cc.log("PopupLayer.result");

        window.location="../../top";
    },


    menuCallBack:function (sender) {
        cc.log('ResultLayer.menuCallBack');
        switch(sender.name){
            case 'Shop':
                cc.log('Shop');
                this.toShop();
                this.removeFromParent();
                break;
            case 'Ranking':
                cc.log('Ranking');
                this.toRanking();
                this.removeFromParent();
                break;
            case 'TryAnother':
                cc.log('TryAnother');
                this.tryAnother();
                this.removeFromParent();
                break;
            case 'ToTop':
                cc.log('ToTop');
                this.toTop();
                this.removeFromParent();
                break;
            case 'Retry':
                cc.log('Retry');
                this.retry();
                this.removeFromParent();
                break;
            default:
                cc.log('default');
        }
    },
    createPointSprite:function(string,x,y,size,r,g,b){
        return this.createStringSprite(string,x,y,size);
    },
    createStringSprite:function(string,x,y,size,r,g,b){
        if (size === undefined || size === null){
            size = this.DEFAULT_FONT_SIZE;
        }
        var label = cc.LabelTTF.create(string, "Arial", size);
        this.addChild(label, 100);
        label.setPosition(cc.p(x, y));
        label.setColor(cc.c3b(r, g, b));                     //テキストに色をセット（白）
        return label;
    },
    createShopButton:function (x,y) {
        var shop = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_resultshop.png",
            bd+"res/game_scene/button/button_resultshop_off.png",
            this.menuCallBack.bind(this)
        );
        shop.setPosition(x, y);
        shop.name = "Shop";
        return shop;
    },
    createRankingButton:function (x,y) {
        var ranking = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_resultranking.png",
            bd+"res/game_scene/button/button_resultranking_off.png",
            this.menuCallBack.bind(this)
        );
        ranking.setPosition(x, y);
        ranking.name = "Ranking";
        return ranking;
    },
    createTryAnotherButton:function (x,y) {
        var tryAnother = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_otherselect.png",
            bd+"res/game_scene/button/button_otherselect_off.png",
            this.menuCallBack.bind(this)
        );
        tryAnother.setPosition(x, y);
        tryAnother.name = "TryAnother";
        return tryAnother;
    },
    createToTopButton:function (x,y) {
        var toTop = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_returnto_top.png",
            bd+"res/game_scene/button/button_returnto_top_off.png",
            this.menuCallBack.bind(this)
        );
        toTop.setPosition(x, y);
        toTop.name = "ToTop";
        return toTop;
    },
    createRetryButton:function (x,y) {
        var retry = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_retry.png",
            bd+"res/game_scene/button/button_retry_off.png",
            this.menuCallBack.bind(this)
        );
        retry.setPosition(x, y);
        retry.name = "Retry";
        return retry;
    },
});