var IllustFrame = cc.Layer.extend({
	illust2: null,
	sprite: null,
	
	ctor:function(){

	},
	init:function(playInfo){
        cc.log("BaseLayer.ctor");
        this._super();
        this.playInfo = playInfo;
        this.init(parent);

	},

	paring:function(illust2){
		this.illust2 = illust2;
	},

	onEnter:function(){

	},
	onExit:function(){

	},

});
