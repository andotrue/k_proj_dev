var Slider = cc.LayerGradient.extend({
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

            var slidebar = cc.Sprite.create( gsDir + "other/slidebar.png" );
            var slideicon = cc.Sprite.create( gsDir + "other/slideicon.png" );
            this.addChild(slidebar);
            this.addChild(slideicon);
            slidebar.setPosition(50,50);
            slideicon.setPosition(50,50);
/*            Slidebar.setPosition(307,44);
            Slidebar.setScaleX(0.75);
            Slideicon.setPosition(360,44);
            Slideicon.setScaleX(0.90);
*/        }
        return bRet;
    },
    initSelf:function(){

		this.setContentSize(cc.size(240,50));
        this.setStartColor(cc.c3b(255,0,0));
        this.setEndColor(cc.c3b(255,0,255));
        this.setStartOpacity(255);
        this.setEndOpacity(255);
        var blend =  new cc.BlendFunc();
        blend.src = cc.GL_SRC_ALPHA;
        blend.dst = cc.GL_ONE_MINUS_SRC_ALPHA;
        this.setBlendFunc(blend);

        this.setPosition(360,160);
        cc.log("slider.initSelf");
    },
  
    onTouchBegan:function (touch, event) {
        cc.log("Slider.onTouchBegan event should be handled.");
        return true;
    },

    /**
     * callback when a touch event moved
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchMoved:function (touch, event) {
        cc.log("Slider.onTouchMoved event should be handled.");
        return true;
    },

    /**
     * callback when a touch event finished
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchEnded:function (touch, event) {
        cc.log("Slider.onTouchEnded event should be handled.");
        return true;
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