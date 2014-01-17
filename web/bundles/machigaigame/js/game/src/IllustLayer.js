var IllustLayer = cc.Layer.extend({
    q_def_path: "../download/",
    q_code: null,
    level: null,
    illusts:{},
    FIRST: 1,
    SECOND: 2,
    offset: null,
    originalSize: null,
    fullContentsRect: null,
    currentScale: null,

    currentIllustSize:function(){
      return cc.size(this.originalSize.widh * this.currentScale, this.originalSize * this.currentScale);
    },
    ctor:function (rect) {
        this._super();
        this.init(rect);
    },
    move:function(dx,dy){
      this.offset = cc.p( this.offset.x + dx, this.offset.y + dy);
      this.updateIllusts();
    },
    getIllustAreaSize:function(){
      return ( cc.p(this.getIllustAreaWidth, this.getIllustAreaHeight));
    },
    getIllustAreaWidth:function(){
      return ( this.fullContentsRect.width / 2.0 );
    },
    getIllustAreaHeight:function(){
      return ( this.fullContentsRect.height / 2.0 );
    },
    getCenterPoint:function(index){
      var direction =  (1.5 - index) * 2.0;
      var dx = this.getIllustAreaWidth() / 2.0;
      var dy = this.getIllustAreaHeight() / 2.0;
//      cc.log("getCenterPoint: direction = " + direction);
//      cc.log("getCenterPoint: dx, dy = " + dx + ", " + dy);
      return cc.p(dx, direction * dy);
    },
    getCenterPointToWorld:function(index){
      var point = this.getCenterPoint();
      return cc.p(this.getPosition().x + point.x, this.getPosition().y + point.y);
    },
    imgPath:function(index){
      switch(index){
        case 1:
          postfix = "/first";
          break;
        case 2:
          postfix = "/second";
          break;
      }
      return this.q_def_path + this.level + '/' + this.qcode + "/first";
    },
    setIllustFullTargetRect:function(rect){
      this.fullContentsRect = rect;
//      cc.log("setIllustFullTargetRect =" + rect.x + "," + rect.y + ", " + rect.width + ", " + rect.height);
    },
/*    getFrame:function(index){
      var rotated = false;
      return cc.SpriteFrame.create( this.imgPath(index), this.rect, rotated, this.offset, this.originalSize);
    },
*/
    updateIllusts:function(){
//      cc.log("IllustLayer.updateIllusts()");
      for (var index = this.FIRST; index <= this.SECOND; index++) {
        if( this.illusts[index] !== undefined){
          this.illusts[index].removeFromParent();
        }

//        this.illusts[index] = cc.Sprite.createWithSpriteFrame(this.getFrame(index));
        var rect = this.calculateRectArea();
        this.illusts[index] = cc.Sprite.create(this.imgPath(index));
        this.illusts[index].setAnchorPoint(0.5,0.5);
        this.addChild( this.illusts[index]);
//        cc.log("(x, y) = (" + this.getCenterPoint(index).x +  ", " + this.getCenterPoint(index).y + ")");
        //はみ出しを確認


        cc.log("this.currentScale = " + this.currentScale);
        this.illusts[index].setPosition(this.getCenterPoint(index).x + this.offset.x, this.getCenterPoint(index).y + this.offset.y);
        this.scaleIllust(index);
      }
    },
    scaleIllust:function(index,scale){
        if(scale >= 0) this.currentScale = scale;
        cc.log("this.currentScale = " + this.currentScale);
        this.illusts[index].setScale(this.currentScale);
    },
    scaleIllusts:function(scale){
      this.scaleIllust(this.FIRST,scale);
      this.scaleIllust(this.SECOND,scale);
    },
    calculateRectArea:function(){
      var pos = this.offset;
      var illustSize = this.currentIllustSize();
      var areaSize = this.getIllustAreaSize();
      var amount = null;
      // 方向計算(正のamountの場合処理が必要)
      var xamount = (illustSize.x /2 + pos.x)  - areaSize.x / 2.0;
      var xamount2 = - (pos.x - illustSize.x / 2.0 ) + (areaSize.x / 2.0);
      var yamount = (illustSize.y /2 + pos.y)  - areaSize.x / 2.0;
      var yamount2 = - (pos.y - illustSize.y / 2.0 ) + (areaSize.y / 2.0);
      if( xamount  > 0 ) cxp = xamount;
      if( xamount2 > 0 ) cxm = yamount;

//      return [bool, cx, cy];
    },

    init:function (rect) {
        var bRet = false;
        if (this._super()) {
            //イラストの表示範囲を設定
            this.setIllustFullTargetRect(rect);

            this.qcode = "105";
            this.level = 1;

//            this.rect = new cc.Rect(0,0, 300,300);
            this.offset = new cc.Point(100,50);
            this.originalSize = new cc.Size(1000,1000);
            this.currentScale = 2.0;

            cc.log("this.currentScale = " + this.currentScale);

            this.setAnchorPoint(cc.p(0.0, 0.5));
            this.setPosition(0, 640);

            this.updateIllusts();
    
            bRet = true;
        }
        return bRet;
    },
/*
    onTouchesBegan: function(touches, event){
      //cc.log('onTouchesBegan:' + touches.length);
      for (var i=0; i < touches.length; i++) {
        this.processEvent(touches[i]);
      }
    },
    onTouchesMoved: function(touches, event){
      //cc.log('onTouchesMoved' + touches.length);
      for (var i=0; i < touches.length; i++) {
        this.processEvent(touches[i]);
      }
    },
    onTouchBegan:function (touch, event) {
      processEvent(touch);
    },
    onTouchMoved:function (touch, event) {
      processEvent(touch);
    },
    processEvent: function(touch){
/*       if(this.eating || !touch){
        return;
      }
   
      this.eating = true;

      var touchLocation = touch.getLocation();
      var local = this.convertToNodeSpace(touchLocation);
      var offset = (local.x + App.center().x) / App.size().width;
      var hae = null;
      for (var i = 0; i < 5; i++) {
        var _hae = this.getChildByTag(i);
        if (!_hae) {
          continue;
        }
        var haeLocal = _hae.convertToNodeSpace(touchLocation);
        var r = _hae.rect();
        r.x = 0;
        r.y = 0;
        if (cc.rectContainsPoint(r, haeLocal)){
          cc.log('hae' + _hae.getTag() + ' isTouch!');
          hae = _hae;
          this.haecount--;
          break;
        }
      }
      if(!hae){
        this.eating = false;
        this.kaeru.kerokero();
        return;
      }
      this.kaeru.beron(local, function() {
        if (hae) {
          hae.runAction(cc.Sequence.create([
            cc.MoveTo.create(0.2, cc.p(-30 + (offset * 100), -250)),
            cc.FadeOut.create(0.05),
            cc.CallFunc.create(function(){
              hae.removeFromParent();
              this.eating = false;
              this.kaeru.perori(function() {
                if(!self.eating){
                  this.start();
                 }
              }.bind(this));
            }.bind(this))
          ]));
        } else {
          this.kaeru.kyorokyoro();
          this.eating = false;
        }
      }.bind(this));
    },
*/    
});