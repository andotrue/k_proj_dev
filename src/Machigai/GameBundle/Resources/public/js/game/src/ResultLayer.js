var ResultLayer = cc.Layer.extend({
    //以下は固定値
    SHOP_LINK_PATH: null,
    RANKING_PATH: null,
    MESSAGE_FOR_USER: "おめでとう！ショップやランキングをチェック",
    MESSAGE_FOR_GUEST_1: "おめでとう！他の問題にもチャレンジ！",
    MESSAGE_FOR_GUEST_2: "XXXXXX",
    //以下は変数から当てはめる
    isGuest: null,
    acquiredPoint: null,
    clearTime: null,
    currentPoint: null,


    ctor:function (isGuest, clearTime, acquiredPoint, currentPoint ) {
      cc.log("ResultLayer.init()");
        self = this;
        this._super();

        this.isGuest = isGuest;
        this.acquiredPoint = clearTime;
        this.clearTime = clearTime;
        this.currentPoint = currentPoint;

        this.init();
    },

    init:function () {
      cc.log("ResultLayer.init()");
        var bRet = false;
        if (this._super()) {
            this.setAnchorPoint(cc.p(0, 0));
            this.setPosition(360,704);

            //バックグランド
            var bg = cc.Sprite.create( gsDir + "background/top_bg.png" );
            bg.setPosition(360, 720 );
            this.addChild(bg);

          cc.log("ResultLayer.init()");
            this.initClearTime();
//            this.initCurrentPoint();
//            this.initAcquiredPoint();

            if(this.isGuest === true){
//                this.initMenuForGuest();
            }else{
//                this.initMenuForUser();
            }

            bRet = true;
        }
        return bRet;
    },
 
    initClearTime:function(){

    },
    initMenuForUser:function () {
        cc.log("initMenuForUser");
        this.state = "SAVE";
        path = gsDir + "popup/save.png";
        var popup = cc.Sprite.create(path);
        this.addChild(popup);
        popup.setPosition(360,this.MIDDLE_Y );

        var yes = this.createShopButton(360,677);
        var no = this.createRankingButton(360,619);
        //仕様書通りにボタンを追加


        var menu = cc.Menu.create([yes,no]);
        menu.setPosition(0,0);
        this.addChild(menu);

    },
    initMenuForGuest:function () {
        cc.log("initMenu");
        this.state = "SAVE";
        path = gsDir + "popup/save.png";
        var popup = cc.Sprite.create(path);
        this.addChild(popup);
        popup.setPosition(360,this.MIDDLE_Y );

        var yes = this.createShopButton(360,677);
        var no = this.createRankingButton(360,619);
        //仕様書通りにボタンを追加

        var menu = cc.Menu.create([yes,no]);
        menu.setPosition(0,0);
        this.addChild(menu);

    },

    initClearTime:function(){
        cc.log("PopupLayer.hint");

    },
    initCurrentPoint:function(){
        cc.log("PopupLayer.hint");

    },
    initAcquiredPoint:function(){
        cc.log("PopupLayer.hint");

    },


    playOtherGames:function () {
        cc.log("PopupLayer.hint");
        window.location="../select";
    },

    toShop:function () {
        cc.log("PopupLayer.save");
        cc.log(document.location);
        window.location="../../shop";

    },

    toRanking:function () {
        cc.log("PopupLayer.giveup");

        window.location="../../ranking";
    },

    toTop:function(){
        cc.log("PopupLayer.giveup");

        window.location="../../top";
    },
/* 
    tryAnother:function(){
        cc.log("PopupLayer.result");

        window.location="../../game/select";
    },

    retry:function(){
        cc.log("PopupLayer.result");

        window.location="../../game/play/156";
        //question_id指定
    },
    
    toTop:function(){
        cc.log("PopupLayer.result");

        window.location="../../top";
    },
*/

    menuCallBack:function (sender) {
        cc.log('PopupLayer.menuCallBack');
        switch(sender.name){
            case 'Shop':
                cc.log('Shop');
                this.removeFromParent();
                break;
            case 'Ranking':
                cc.log('Ranking');
                this.removeFromParent();
                break;
            case 'TryAnother':
                cc.log('TryAnother');
                this.removeFromParent();
                break;
            case 'ToTop':
                cc.log('ToTop');
                this.removeFromParent();
                break;
            case 'Retry':
                cc.log('Retry');
                this.removeFromParent();
                break;
            default:
                cc.log('default');
        }
    },
    createShopButton:function (x,y) {
        var shop = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_yes.png",
            bd+"res/game_scene/button/button_yes_off.png",
            this.menuCallBack.bind(this)
        );
        shop.setPosition(x, y);
        shop.name = "Shop";
        return shop;
    },
    createRankingButton:function (x,y) {
        var ranking = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_no.png",
            bd+"res/game_scene/button/button_no_off.png",
            this.menuCallBack.bind(this)
        );
        ranking.setPosition(x, y);
        ranking.name = "Ranking";
        return ranking;
    },
    createTryAnotherButton:function (x,y) {
        var tryAnother = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_other_select.png",
            bd+"res/game_scene/button/button_other_select_off.png",
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