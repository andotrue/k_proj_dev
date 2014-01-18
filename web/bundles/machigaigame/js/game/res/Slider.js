var Slider = cc.Layer.extend({
    slidebar: null,
    slideicon: null,
    MIDDLE_X: 360,
    MIDDLE_Y: 390,
    ctor:function (start,end) {
        cc.log("Slider.ctor");
        self = this;
        this._super();

        if (start === undefined )
            throw ("Silder: Not created with start,end Range.");
        this.init(start,end);
    },
    init:function (start,end) {
        cc.log("Slider.init");
        var bRet = false;
        if (this._super()) {
            this.initSelf();

            this.setRatioRange(start,end);

            var slidebar = cc.Sprite.create( gsDir + "other/game_slidebar.png" );
            var slideicon = cc.Sprite.create( gsDir + "other/game_slideicon.png" );
            this.addChild(slidebar);
            this.addChild(slideicon);
            slidebar.setPosition( self.MIDDLE_X, self.MIDDLE_Y);
            slidebar.setScale(1);
            slideicon.setPosition(self.MIDDLE_X, self.MIDDLE_Y);
            slideicon.setScale(1);
            this.slidebar = slidebar;
            this.slideicon = slideicon;
        }
        return bRet;
    },
    initSelf:function(){
        this.setAnchorPoint(0,0);
        this.setPosition(0,0);
        cc.log("slider.initSelf");
    },
    setRatioRange:function(start,end){
        this.startRatio = start;
        this.endRatio = end;
    },
    getRatio:function(){
        var ratio = 0;
        var box = this.slidebar.getBoundingBoxToWorld();
        var width  = box.width;
        var iconpos = this.slideicon.getPosition();
        ratio = this.startRatio + (iconpos.x - box.x) /width * (this.endRatio - this.startRatio);
        cc.log("slideicon.ratio");

        return ratio;
    },
    move:function(touch) {
        var touched = touch.getLocation();
        var bounding = this.slidebar.getBoundingBoxToWorld();
        if( touched.x > bounding.x && touched.x  < bounding.x + bounding.width )
            this.slideicon.setPosition(touched.x, this.slideicon.getPosition().y);
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