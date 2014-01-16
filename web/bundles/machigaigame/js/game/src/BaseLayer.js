var BaseLayer = cc.Layer.extend({

    _parent: null,
    ctor:function () {
        this._super();
        this.init();
    },
    setParent:function(parent){
        this._parent = parent;
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
           
            //Layerの子要素に。
            this.addChild(LabelOtetsuki);
            this.addChild(LabelMachigai);
            this.addChild(LabelTimelimit);

            LabelOtetsuki.setPosition(350,1256);
            LabelMachigai.setPosition(350,1216);
            LabelTimelimit.setPosition(100,1260);


            var clock = new ClockLayer();
            this.addChild(clock,15);


            var popupHint = cc.MenuItemImage.create(
                bd+"res/game_scene/button/icon_hint.png",
                bd+"res/game_scene/button/icon_hint.png",
                this.menuCallBack.bind(this)
            );
            popupHint.setPosition(506, 50);
            popupHint.name = "HINT";

            var popupSave = cc.MenuItemImage.create(
                bd+"res/game_scene/button/icon_save.png",
                bd+"res/game_scene/button/icon_save.png",
                this.menuCallBack.bind(this)
            );
            popupSave.setPosition(667, 50);
            popupSave.name = "SAVE";

            var popupGiveup = cc.MenuItemImage.create(
                bd+"res/game_scene/button/icon_giveup.png",
                bd+"res/game_scene/button/icon_giveup.png",
                this.menuCallBack.bind(this)
            );
            popupGiveup.setPosition(586, 50);
            popupGiveup.name = "GIVEUP";

            var menu = cc.Menu.create([popupHint,popupSave,popupGiveup]);
            menu.setPosition(0,0);
            this.addChild(menu);
            bRet = true;
        }
        return bRet;
    },
    menuCallBack:function(sender){
        var popup = new PopupLayer();
        popup.init(sender.name);
        this.addChild(popup);

//        cc.Director.getInstance().replaceScene(cc.TransitionSlideInT.create(0.4, nextScene));
    },
    onTouchBegan:function (touch, event) {
        cc.log("Base.onTouchBegan event should be handled.");
        return true;
    },

    /**
     * callback when a touch event moved
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchMoved:function (touch, event) {
        cc.log("Base.onTouchMoved event should be handled.");
        return true;
    },

    /**
     * callback when a touch event finished
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchEnded:function (touch, event) {
        cc.log("Base.onTouchEnded event should be handled.");
        cc.log("Delegate Event to objects.");

        return true;
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
        this._parent = null;
        this._super();
    }
});