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
        var screenSize = cc.EGLView.getInstance().getFrameSize();
        var screenSize = cc.size(320,568);
        var resourceSize = cc.size(640, 860);
        var designSize = cc.size(720, 1280);

        var searchPaths = [];
        var resDirOrders = [];

//        searchPaths.push("res");
//        cc.FileUtils.getInstance().setSearchPaths(searchPaths);

        var platform = cc.Application.getInstance().getTargetPlatform();
        if (platform == cc.TARGET_PLATFORM.MOBILE_BROWSER) {
//            resDirOrders.push("HD");
        }
        else if (platform == cc.TARGET_PLATFORM.PC_BROWSER) {
            if (screenSize.height >= 800) {
//                resDirOrders.push("HD");
            }
            else {
                resourceSize = cc.size(1080, 1920);
                designSize = cc.size(1080, 1920);
//                resDirOrders.push("Normal");
            }
        }

//        cc.FileUtils.getInstance().setSearchResolutionsOrder(resDirOrders);
//        director.setContentScaleFactor(resourceSize.width / designSize.width);
        cc.EGLView.getInstance().setDesignResolutionSize(designSize.width, designSize.height, cc.RESOLUTION_POLICY.SHOW_ALL);
//        cc.EGLView.getInstance().resizeWithBrowserSize(true);

        // turn on display FPS
        director.setDisplayStats(this.config['showFPS']);

        // set FPS. the default value is 1.0/60 if you don't call this
        director.setAnimationInterval(1.0 / this.config['frameRate']);

        //load resources
        cc.LoaderScene.preload(g_resources, function () {
            director.replaceScene(new this.startScene());
        }, this);

        return true;
    }
});

var myApp = new Application(MyGameScene);