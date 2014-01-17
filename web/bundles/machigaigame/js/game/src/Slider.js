var Slider = cc.Layer.extend({
    ctor:function () {
        cc.log("Slider.ctor");
        this._super();
        this.init();
    },
    init:function () {
        cc.log("Slider.init");
        var bRet = false;
        if (this._super()) {
            this.initSelf();

            this.setAnchorPoint(0,0);
            var slidebar = cc.Sprite.create( gsDir + "other/game_slidebar.png" );
            var slideicon = cc.Sprite.create( gsDir + "other/game_slideicon.png" );
            this.addChild(slidebar);
            this.addChild(slideicon);
            slidebar.setPosition(250,125);
            slidebar.setScaleX(0.75);
            slideicon.setPosition(250,125);
            slideicon.setScaleX(0.75);
        }
        return bRet;
    },
    initSelf:function(){
        this.setPosition(0,0);
        cc.log("slider.initSelf");
    },
  
    onTouchBegan:function (touch, event) {
        cc.log("Slider.onTouchBegan: ( " + touch.getLocation().x + ", " + touch.getLocation().y + " )");
        this.touchedFrom = touch.getLocation();
        return false;
    },

    /**
     * callback when a touch event moved
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchMoved:function (touch, event) {
        cc.log("Slider.onTouchMoved ( " + touch.getLocation().x + ", " + touch.getLocation().y + " )");
        this.touchedTo = touch.getLocation();
        var dx = this.touchedTo.x - this.touchedFrom.x;
        var dy = this.touchedTo.y - this.touchedFrom.y;
        this.illusts.move(dx, dy);
        this.touchedFrom = this.touchedTo;
        return false;
    },

    /**
     * callback when a touch event finished
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchEnded:function (touch, event) {
        cc.log("Slider.onTouchEnded ( " + touch.getLocation().x + ", " + touch.getLocation().y + " )");
        return false;
    },

    onEnter:function () {
        cc.log("Slider.onEnter");
       if(sys.platform == "browser")
            cc.registerTargetedDelegate(1, true, this);
        else
            cc.registerTargettedDelegate(1,true,this);
        this._super();
    },
    onExit:function () {
        cc.log("Slider.onExit");
        cc.unregisterTouchDelegate(this);
        this._super();
    }
});