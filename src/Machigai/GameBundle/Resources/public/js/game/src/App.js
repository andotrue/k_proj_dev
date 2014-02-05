var agent = navigator.userAgent;
var orientation = window.orientation;
var image = new Image();
image.src = "../warning.png";
document.body.appendChild(image);
image.style.display = "none";
image.style.position = "absolute";
image.style.top = "0%";
image.style.left = "0%";
image.style.width = "400px";
image.style.height = "300px";
if(agent.search(/iPhone/) != -1){
    window.onorientationchange = warning;
}
function warning(){
    if(orientation != 0){
        image.style.display = "block";
    }else{
        image.style.display = "none";  
    }
}

var MyGameScene = cc.Scene.extend({
    playInfo: null,
    baseLayer: null,
    clock: null,
    onEnter:function () {
        this._super();
        var self = this;
//        gScoreData.initData();

//        var spriteFrameCache = cc.SpriteFrameCache.getInstance();
//        spriteFrameCache.addSpriteFrames("res/baseResource.plist","res/baseResource.png");
        var paths = window.location.pathname.split("/");
        var questionId = paths[paths.length -1];
        cc.log("MyGameScene: questionId = " + questionId);
        this.playInfo = new PlayInfo(questionId);
        this.clock = new Clock(this);
        this.playInfo.setClock(this.clock);

        var baseLayer = new BaseLayer(self, this.playInfo);
        this.addChild(baseLayer,10);
        this.baseLayer = baseLayer;

        var popupLayer = new PopupLayer("PLAY",this);
        this.addChild(popupLayer,20);
        this.addChild(baseLayer,10);

//        gSharedEngine.setMusicVolume(1);
//        gSharedEngine.setEffectsVolume(1);
//        gSharedEngine.playMusic(MUSIC_BACKGROUND,true);
    },
    onExit:function(){
		cc.log("MyGameScene.onExit()");
		this._super();
    }
});