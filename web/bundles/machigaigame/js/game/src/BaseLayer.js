var BaseLayer = cc.Layer.extend({
    illusts: null,
    slider: null,
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
            this.illusts = new IllustLayer();
            this.addChild(this.illusts,0);


            var Header = cc.Sprite.create( gsDir + "background/header.png");
            var Footer = cc.Sprite.create( gsDir + "background/footer.png");
            this.addChild(Header);
            this.addChild(Footer);
            Header.setAnchorPoint(1, 1);
            Header.setPosition(720,1280);
            Footer.setAnchorPoint(0.0,0.0);
            Footer.setPosition(0,0);

            var LabelOtetsuki = cc.Sprite.create( gsDir + "label/game_otetsuki.png" );
            var LabelMachigai = cc.Sprite.create( gsDir + "label/game_machigai.png" );
            var LabelTimelimit = cc.Sprite.create( gsDir + "label/game_timelimit.png" );

            var stars = [];
            var hearts = [];
            for (var i = 0; i < 10; i++) {
                var star  = cc.Sprite.create( gsDir + "other/game_star_off.png" );
                var heart = cc.Sprite.create( gsDir + "other/game_heart_on.png" );
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


            this.clock = new Clock();
            this.addChild(this.clock,15);

            this.slider = new Slider();
            this.addChild(this.slider,18);

            this.initMenu();

            bRet = true;
        }
        return bRet;
    },
    initMenu:function(){
        var popupHint = cc.MenuItemImage.create(
            bd+"res/game_scene/button/game_icon_hint.png",
            bd+"res/game_scene/button/game_icon_hint.png",
            this.menuCallBack.bind(this)
        );
        popupHint.setPosition(506, 50);
        popupHint.name = "HINT";

        var popupSave = cc.MenuItemImage.create(
            bd+"res/game_scene/button/game_icon_save.png",
            bd+"res/game_scene/button/game_icon_save.png",
            this.menuCallBack.bind(this)
        );
        popupSave.setPosition(667, 50);
        popupSave.name = "SAVE";

        var popupGiveup = cc.MenuItemImage.create(
            bd+"res/game_scene/button/game_icon_giveup.png",
            bd+"res/game_scene/button/game_icon_giveup.png",
            this.menuCallBack.bind(this)
        );
        popupGiveup.setPosition(586, 50);
        popupGiveup.name = "GIVEUP";

        var menu = cc.Menu.create([popupHint,popupSave,popupGiveup]);
        menu.setPosition(0,0);
        this.addChild(menu);
    },
    menuCallBack:function(sender){
        var popup = new PopupLayer();
        popup.init(sender.name);
        this.addChild(popup);

//        cc.Director.getInstance().replaceScene(cc.TransitionSlideInT.create(0.4, nextScene));
    },
    onTouchesBegan: function(touches, event){
        cc.log('onTouchesBegan:' + touches.length);
        this.onTouchBegan(touches[0], event);
    },
    onTouchesMoved: function(touches, event){
        cc.log('onTouchesMoved' + touches.length);
        this.onTouchMoved(touches[0], event);
    },
    onTouchesEnded: function(touches, event){
        cc.log('onTouchesMoved' + touches.length);
        this.onTouchEnded(touches[0], event);
    },
    onTouchBegan:function (touch, event) {
        cc.log("Base.onTouchBegan: ( " + touch.getLocation().x + ", " + touch.getLocation().y + " )");
        this.touchedFrom = touch.getLocation();
        return true;
    },

    /**
     * callback when a touch event moved
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchMoved:function (touch, event) {
        cc.log("Base.onTouchMoved ( " + touch.getLocation().x + ", " + touch.getLocation().y + " )");
        this.touchedTo = touch.getLocation();
        var dx = this.touchedTo.x - this.touchedFrom.x;
        var dy = this.touchedTo.y - this.touchedFrom.y;
        this.illusts.move(dx, dy);
        this.touchedFrom = this.touchedTo;
        return true;
    },

    /**
     * callback when a touch event finished
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchEnded:function (touch, event) {
        cc.log("Base.onTouchEnded ( " + touch.getLocation().x + ", " + touch.getLocation().y + " )");
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
    },
    checkInside:function(position){

    }
});