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
            this.addChild(Header);
            this.addChild(Footer);
            Header.setPosition(426,1242);
            Footer.setPosition(426,38);

            var IconGiveup = cc.Sprite.create( gsDir + "button/icon_giveup.png" );
            var IconHint = cc.Sprite.create( gsDir + "button/icon_hint.png" );
            var IconSave = cc.Sprite.create( gsDir + "button/icon_save.png" );

            var LabelOtetsuki = cc.Sprite.create( gsDir + "label/otetsuki.png" );
            var LabelMachigai = cc.Sprite.create( gsDir + "label/machigai.png" );
            var LabelTimelimit = cc.Sprite.create( gsDir + "label/timelimit.png" );

            var stars = [];
            var hearts = [];
            for (var i = 0; i < 10; i++) {
                var star  = cc.Sprite.create( gsDir + "other/star_off.png" );
                var heart = cc.Sprite.create( gsDir + "other/heart_on.png" );
                hearts.push(heart);
                stars.push(star);
                this.addChild(heart);
                this.addChild(star);

                star.setPosition(420 + 30 * i ,1256);
                heart.setPosition(420 + 30 * i, 1216);
//                off.setOpacity(0);
            }
           
            var Slidebar = cc.Sprite.create( gsDir + "other/slidebar.png" );
            var Slideicon = cc.Sprite.create( gsDir + "other/slideicon.png" );

            //Layerの子要素に。

            this.addChild(IconGiveup);
            this.addChild(IconHint);
            this.addChild(IconSave);

            this.addChild(LabelOtetsuki);
            this.addChild(LabelMachigai);
            this.addChild(LabelTimelimit);

            this.addChild(Slidebar);
            this.addChild(Slideicon);


            IconGiveup.setPosition(506,44);
            IconHint.setPosition(586,44);
            IconSave.setPosition(667,44);
            
            LabelOtetsuki.setPosition(350,1256);
            LabelMachigai.setPosition(350,1216);
            LabelTimelimit.setPosition(100,1260);

            Slidebar.setPosition(307,44);
            Slidebar.setScaleX(0.75);
            Slideicon.setPosition(360,44);
            Slideicon.setScaleX(0.90);

            var clock = new ClockLayer();
            this.addChild(clock,15);

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