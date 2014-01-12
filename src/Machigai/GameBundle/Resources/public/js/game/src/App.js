var MyGameScene = cc.Scene.extend({
    onEnter:function () {
        this._super();

//        gScoreData.initData();

//        var spriteFrameCache = cc.SpriteFrameCache.getInstance();
//        spriteFrameCache.addSpriteFrames("res/baseResource.plist","res/baseResource.png");

        var baseLayer = new BaseLayer();
        var illustLayer = new IllustLayer();
        var popupLayer = new PopupLayer();
        this.addChild(popupLayer,20);
        this.addChild(baseLayer,10);
        this.addChild(illustLayer,0);

//        gSharedEngine.setMusicVolume(1);
//        gSharedEngine.setEffectsVolume(1);
//        gSharedEngine.playMusic(MUSIC_BACKGROUND,true);
    }
});