var MyGameScene = cc.Scene.extend({
    onEnter:function () {
        this._super();

//        gScoreData.initData();

//        var spriteFrameCache = cc.SpriteFrameCache.getInstance();
//        spriteFrameCache.addSpriteFrames("res/baseResource.plist","res/baseResource.png");

        var baseLayer = new BaseLayer();
        this.addChild(baseLayer,10);


        var popupLayer = new PopupLayer();
        popupLayer.init("PLAY");
        this.addChild(popupLayer,20);


//        gSharedEngine.setMusicVolume(1);
//        gSharedEngine.setEffectsVolume(1);
//        gSharedEngine.playMusic(MUSIC_BACKGROUND,true);
    }
});