var PopupLayer = cc.Layer.extend({

    ctor:function () {
        this._super();
        this.init();
    },
    init:function () {
        var bRet = false;
        if (this._super()) {

            var ResultMenu = cc.Sprite.create( gsDir + "background/result_menu.png" );
            var ResultMenuGuest = cc.Sprite.create( gsDir + "background/result_menu_guest.png" ); 

            var ButtonSave = cc.Sprite.create( gsDir + "button/save.png" );
            var ButtonSaveOff = cc.Sprite.create( gsDir + "button/save_off.png" );

            var LabelMiss = cc.Sprite.create( gsDir + "label/clear.png" );
            var LabelClear = cc.Sprite.create( gsDir + "label/clear.png" );
                      
            var PopupCommon = cc.Sprite.create( gsDir + "popup/common.png" );
            var PopupGamestart = cc.Sprite.create( gsDir + "popup/gamestart.png" );
            var PopupGamestartFirst = cc.Sprite.create( gsDir + "popup/gamestart_first.png" );
            var PopupGamestartGuest = cc.Sprite.create( gsDir + "popup/gamestart_guest.png" );
            var PopupGamestartNotfirst = cc.Sprite.create( gsDir + "popup/gamestart_notfirst.png" );
            var PopupGiveup = cc.Sprite.create( gsDir + "popup/giveup.png" );
            var PopupHint = cc.Sprite.create( gsDir + "popup/hint.png" );
            var PopupQuestiondownload = cc.Sprite.create( gsDir + "popup/questiondownload.png" );
            var PopupSave = cc.Sprite.create( gsDir + "popup/save.png" );
            var PopupSaveGuest = cc.Sprite.create( gsDir + "popup/save_guest.png" );

            //Layerの子要素に。
            this.addChild(ResultMenu);
            this.addChild(ResultMenuGuest);
            this.addChild(ButtonSave);
            this.addChild(ButtonSaveOff);
            this.addChild(LabelMiss);
            this.addChild(LabelClear);

            this.addChild(PopupCommon);
            this.addChild(PopupGamestart);
            this.addChild(PopupGamestartFirst);
            this.addChild(PopupGamestartGuest);
            this.addChild(PopupGamestartNotfirst);
            this.addChild(PopupGiveup);
            this.addChild(PopupHint);
            this.addChild(PopupQuestiondownload);
            this.addChild(PopupSave);
            this.addChild(PopupSaveGuest);

            //Positionの設定
            ResultMenu.setPosition(640,960);
            ResultMenuGuest.setPosition(640,960);

            ButtonSave.setPosition(640,960);
            ButtonSaveOff.setPosition(640,960);

            LabelMiss.setPosition(640,960);
            LabelMiss.setOpacity(0);
            LabelClear.setPosition(640,960);
            LabelClear.setOpacity(0);

            PopupCommon.setPosition(640,960);
            PopupGamestart.setPosition(640,960);
            PopupGamestartFirst.setPosition(640,960);
            PopupGamestartGuest.setPosition(640,960);
            PopupGamestartNotfirst.setPosition(640,960);
            PopupGiveup.setPosition(640,960);
            PopupHint.setPosition(640,960);
            PopupQuestiondownload.setPosition(640,960);
            PopupSave.setPosition(640,960);
            PopupSaveGuest.setPosition(640,960);
/*
            var popupGameStart = cc.MenuItemImage.create(
                bd+"res/game_scene/popup/gamestart.png",
                this.menuCallBack,
                this
            );
            popupGameStart.setPosition(640, 960);

            var menu = cc.Menu.create(popupGameStart);
            menu.setPosition(0,0);
            this.addChild(menu);
*/
            bRet = true;
        }
        return bRet;
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