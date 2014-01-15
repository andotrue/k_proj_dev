var BaseLayer = cc.Layer.extend({

    ctor:function () {
        this._super();
        this.init();
    },
    init:function () {
        var bRet = false;
        if (this._super()) {
            var Header = cc.Sprite.create( gsDir + "background/header.png");
            var Footer = cc.Sprite.create( gsDir + "background/footer.png");

            var IconGiveup = cc.Sprite.create( gsDir + "button/icon_giveup.png" );
            var IconHint = cc.Sprite.create( gsDir + "button/icon_hint.png" );
            var IconSave = cc.Sprite.create( gsDir + "button/icon_save.png" );

            var LabelOtetsuki = cc.Sprite.create( gsDir + "label/otetsuki.png" );
            var LabelMachigai = cc.Sprite.create( gsDir + "label/machigai.png" );
            var LabelTimelimit = cc.Sprite.create( gsDir + "label/timelimit.png" );
           
            var heartsOn = [];
            var heartsOff = [];
            for (var i = 0; i < 8; i++) {
                var on  = cc.Sprite.create( gsDir + "other/heart_off.png" );
                var off = cc.Sprite.create( gsDir + "other/heart_on.png" );
//                heartsOn.push(on);
//                heartsOff.push(off);
                this.addChild(on);
                this.addChild(off);

                on.setPosition(700 + 30 * i ,1840);
                off.setPosition(700 + 30 * i, 1840);
//                off.setOpacity(0);
            };
           
            var Slidebar = cc.Sprite.create( gsDir + "other/slidebar.png" );
            var Slideicon = cc.Sprite.create( gsDir + "other/slideicon.png" );

            //Layerの子要素に。
            this.addChild(Header);
            this.addChild(Footer);

            this.addChild(IconGiveup);
            this.addChild(IconHint);
            this.addChild(IconSave);

            this.addChild(LabelOtetsuki);
            this.addChild(LabelMachigai);
            this.addChild(LabelTimelimit);

            this.addChild(Slidebar);
            this.addChild(Slideicon);

            //Positionの設定
            Header.setPosition(640,1863);
            Footer.setPosition(640,57);

            IconGiveup.setPosition(760,66);
            IconHint.setPosition(880,66);
            IconSave.setPosition(1000,66);
            
            LabelOtetsuki.setPosition(616,1890);
            LabelMachigai.setPosition(616,1840);
            LabelTimelimit.setPosition(100,100);

            Slidebar.setPosition(460,66);
            Slidebar.setScaleX(0.75);
            Slideicon.setPosition(460,63);
            Slideicon.setScaleX(0.90);
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
            bRet = true;
  */      }
        return bRet;
    },
    menuCallBack:function(sender){
//        gSharedEngine.playEffect(EFFECT_BUTTON_CHICK);
        //gGameMode = eGameMode.Challenge;
  //      gGameMode = eGameMode.Timer;
//        var nextScene = cc.Scene.create();
//        var nextLayer = new PatternMatrix;
//        nextScene.addChild(nextLayer);
//        cc.Director.getInstance().replaceScene(cc.TransitionSlideInT.create(0.4, nextScene));
    }
});