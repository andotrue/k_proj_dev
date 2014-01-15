var PopupLayer = cc.Layer.extend({

    ctor:function () {
        this._super();
        this.init();
    },
    init:function () {
        var bRet = false;
        if (this._super()) {

            var PopupQuestiondownload = cc.Sprite.create( gsDir + "popup/questiondownload.png" );
            PopupQuestiondownload.setPosition(360,640);
            this.addChild(PopupQuestiondownload);

            bRet = true;
        }
        return bRet;
    },
    popupMiss:function () {
        var label = cc.Sprite.create( gsDir + "label/miss.png" );
        this.addChild(label);
        popup.setPosition(360,640);

    },
    popupClear:function () {
        var label = cc.Sprite.create( gsDir + "label/clear.png" );
        this.addChild(label);
        popup.setPosition(360,640);
    },

    popupPlay:function () {
        var ss = ["popup/gamestart.png", "popup/gamestart_first.png", "popup/gamestart_guest.png","popup/gamestart_notfirst.png"];
        var i =this.game_status = 1;
        var popup = cc.Sprite.create( gsDir + ss[i] );
        this.addChild(popup);
        popup.setPosition(360,540);
    },
    popupHint:function () {
        var popup = cc.Sprite.create( gsDir + "popup/hint.png" );
        this.addChild(popup);
        popup.setPosition(360,640);
    },
    popupSave:function () {
        path = gsDir + this.is_guest ? "popup/save.png" : "popup/save_guest.png";
        var popup = cc.Sprite.create(path);
        this.addChild(popup);
        popup.setPosition(360,640);
    },
    popupGiveup:function () {
        var PopupGiveup = cc.Sprite.create( gsDir + "popup/giveup.png" );
        this.addChild(popup);
        popup.setPosition(360,640);

    },

    menuCallBack:function(sender){
//        gSharedEngine.playEffect(EFFECT_BUTTON_CHICK);
        //gGameMode = eGameMode.Challenge;
  //      gGameMode = eGameMode.Timer;
        var nextScene = cc.Scene.create();
//        var nextLayer = new PatternMatrix;
//        nextScene.addChild(nextLayer);
//        cc.Director.getInstance().replaceScene(cc.TransitionSlideInT.create(0.4, nextScene));
    }
});