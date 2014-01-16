var ResultLayer = cc.Layer.extend({

    ctor:function () {
        this._super();
        this.init();
    },
    init:function () {
        var bRet = false;
        if (this._super()) {
/*            var Header = cc.Sprite.create( gsDir + "background/header.png");

            //Layerの子要素に。
            this.addChild(Header);

            this.addChild(LabelOtetsuki);

            this.addChild(Slideicon);


            LabelTimelimit.setPosition(100,100);

            Slideicon.setPosition(460,63);
            Slideicon.setScaleX(0.90);
  */
		}
        return bRet;
    },
    menuCallBack:function(sender){
//        gSharedEngine.playEffect(EFFECT_BUTTON_CHICK);
//        var nextScene = cc.Scene.create();
//        var nextLayer = new PatternMatrix;
//        nextScene.addChild(nextLayer);
//        cc.Director.getInstance().replaceScene(cc.TransitionSlideInT.create(0.4, nextScene));
    }
});