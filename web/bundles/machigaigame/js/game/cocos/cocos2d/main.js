var Application = cc.Application.extend({
    config:document['ccConfig'],
    ctor:function (scene) {
        this._super();
        this.startScene = scene;
        cc.COCOS2D_DEBUG = this.config['COCOS2D_DEBUG'];
        cc.initDebugSetting();
        cc.setup(this.config['tag']);
        cc.AppController.shareAppController().didFinishLaunchingWithOptions();
    },
    applicationDidFinishLaunching:function () {
        // initialize director
        var director = cc.Director.getInstance();

        cc.EGLView.getInstance()._adjustSizeToBrowser();
        cc.EGLView.getInstance().adjustViewPort(true);
//        director.setContentScaleFactor(resourceSize.width / designSize.width);
        cc.EGLView.getInstance().setDesignResolutionSize(720, 1280, cc.RESOLUTION_POLICY.SHOW_ALL);
        cc.EGLView.getInstance().resizeWithBrowserSize(true);

        // turn on display FPS
        director.setDisplayStats(this.config['showFPS']);

        // set FPS. the default value is 1.0/60 if you don't call this
        director.setAnimationInterval(1.0 / this.config['frameRate']);

        //load resources
        cc.LoaderScene.preload(g_resources, function () {
            director.replaceScene(new this.startScene());
        }, this);

        var qcode = 2;

        return true;
    },

    startGame:function(){

    }

});

var myApp = new Application(MyGameScene);