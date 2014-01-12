var PlayLayer = cc.Layer.extend({

    ctor:function () {
        this._super();
        this.init();
    },
    init:function () {
        var bRet = false;
        if (this._super()) {
            var Header = cc.Sprite.create( gsDir + "background/header.png");
            var MondaiArea = cc.Sprite.create( gsDir + "background/mondaiarea.png");
            var Footer = cc.Sprite.create( gsDir + "background/footer.png");

/*            var ResultMenu = cc.Sprite.create( gsDir + "background/result_menu.png" );
            var ResultMenuGuest = cc.Sprite.create( gsDir + "background/result_menu_guest.png" ); */

            var IconGiveup = cc.Sprite.create( gsDir + "button/icon_giveup.png" );
            var IconHint = cc.Sprite.create( gsDir + "button/icon_hint.png" );
            var IconSave = cc.Sprite.create( gsDir + "button/icon_save.png" );
/*            var ButtonSave = cc.Sprite.create( gsDir + "button/save.png" );
            var ButtonSaveOff = cc.Sprite.create( gsDir + "button/save_off.png" );
*/            var LabelClear = cc.Sprite.create( gsDir + "label/clear.png" );
            var LabelMachigai = cc.Sprite.create( gsDir + "label/machigai.png" );
            var LabelMiss = cc.Sprite.create( gsDir + "label/miss.png" );
            var LabelOtetsuki = cc.Sprite.create( gsDir + "label/otetsuki.png" );
            var LabelTimelimit = cc.Sprite.create( gsDir + "label/timelimit.png" );
           
            var heartsOn = [];
            var heartsOff = [];
            for (var i = 0; i < 8; i++) {
                var on = cc.Sprite.create( gsDir + "other/heart_off.png" );
                var off = cc.Sprite.create( gsDir + "other/heart_on.png" );

//                heartsOn.push(on);
//                heartsOff.push(off);
                this.addChild(on);
                this.addChild(off);

                on.setPosition(700 + 30 * i ,1840);
                off.setPosition(700 + 30 * i, 1840);
//                off.setOpacity(0);
            };
           
            var Ng = cc.Sprite.create( gsDir + "other/ng.png" );
            var Ok = cc.Sprite.create( gsDir + "other/ok.png" );
            var Slidebar = cc.Sprite.create( gsDir + "other/slidebar.png" );
            var Slideicon = cc.Sprite.create( gsDir + "other/slideicon.png" );
            var StarOff = cc.Sprite.create( gsDir + "other/star_off.png" );
            var StarOn = cc.Sprite.create( gsDir + "other/star_on.png" );
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
            this.addChild(Header);
            this.addChild(MondaiArea);
            this.addChild(Footer);

/*            this.addChild(ResultMenu);
            this.addChild(ResultMenuGuest); */
            this.addChild(IconGiveup);
            this.addChild(IconHint);
            this.addChild(IconSave);
//            this.addChild(ButtonSave);
//            this.addChild(ButtonSaveOff);
            this.addChild(LabelClear);
            this.addChild(LabelMachigai);
            this.addChild(LabelMiss);
            this.addChild(LabelOtetsuki);
            this.addChild(LabelTimelimit);

            this.addChild(Ng);
            this.addChild(Ok);
            this.addChild(Slidebar);
            this.addChild(Slideicon);
            this.addChild(StarOff);
            this.addChild(StarOn);
 /*           this.addChild(PopupCommon);
            this.addChild(PopupGamestart);
            this.addChild(PopupGamestartFirst);
            this.addChild(PopupGamestartGuest);
            this.addChild(PopupGamestartNotfirst);
            this.addChild(PopupGiveup);
            this.addChild(PopupHint);
            this.addChild(PopupQuestiondownload);
            this.addChild(PopupSave);
            this.addChild(PopupSaveGuest);
*/
            //Positionの設定
            Header.setPosition(640,1863);
            MondaiArea.setPosition(640,960);
            MondaiArea.setScaleY(3.8);
            Footer.setPosition(640,57);

/*            ResultMenu.setPosition(640,960);
            ResultMenuGuest.setPosition(640,960); */

            IconGiveup.setPosition(760,66);
            IconHint.setPosition(880,66);
            IconSave.setPosition(1000,66);
            
/*            ButtonSave.setPosition(640,960);
/            ButtonSaveOff.setPosition(640,960); */

            LabelClear.setPosition(640,960);
            LabelClear.setOpacity(0);
            LabelMiss.setPosition(640,960);
            LabelMiss.setOpacity(0);

            LabelOtetsuki.setPosition(616,1890);
            StarOff.setPosition(700,1890);
            StarOn.setPosition(700,1890);
            LabelMachigai.setPosition(616,1840);

            LabelTimelimit.setPosition(100,100);


            Ng.setPosition(640,960);
            Ok.setPosition(640,960);
            Slidebar.setPosition(460,66);
            Slidebar.setScaleX(0.75);
            Slideicon.setPosition(460,63);
            Slideicon.setScaleX(0.90);
/*
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

var MyGameScene = cc.Scene.extend({
    onEnter:function () {
        this._super();

//        gScoreData.initData();

//        var spriteFrameCache = cc.SpriteFrameCache.getInstance();
//        spriteFrameCache.addSpriteFrames("res/baseResource.plist","res/baseResource.png");

        var layer = new PlayLayer;
        this.addChild(layer);

//        gSharedEngine.setMusicVolume(1);
//        gSharedEngine.setEffectsVolume(1);
//        gSharedEngine.playMusic(MUSIC_BACKGROUND,true);
    }
});