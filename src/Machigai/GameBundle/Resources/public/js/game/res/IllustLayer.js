var IllustLayer = cc.Layer.extend({
    _q_def_path: "/machigai/game/download/",
    q_code: null,
    level: null,
    illusts:{},
    FIRST: 1,
    SECOND: 2,
    offset: null,
    originalSize: null,
    fullContentsRect: null, //このレイヤーのサイズ
    illustFrameRects: [], //イラストの表示される範囲
    currentScale: null,
    frames: [],

    initIllustFrameRects:function(){
      cc.log("IllustLayer.initIllustFrameRects:");
      illustFrameRects =[];

      this.illustFrameRects.push(rect1);
      this.illustFrameRects.push(rect2);
      cc.log("FRAME_RECTS:global: ( " + x1 + ", " + y1 + ", " + this.FRAME_WIDTH + ", " + this.FRAME_HEIGHT +" )");
      cc.log("FRAME_RECTS:global: ( " + x2 + ", " + y2 + ", " + this.FRAME_WIDTH + ", " + this.FRAME_HEIGHT +" )");
    },
    currentIllustSize:function(){
      return cc.size(this.originalSize.widh * this.currentScale, this.originalSize * this.currentScale);
    },
    move:function(dx,dy,touch){
//      this.offset = cc.p( this.offset.x + dx, this.offset.y + dy);
      this.updateIllusts(dx, dy,touch);
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
      cc.log("IllustLayer.imgPath(index) =" + this._q_def_path + this.level + '/' + this.qcode + "/first");
      return this._q_def_path + this.level + '/' + this.qcode + "/first";
    },
    setIllustFullTargetRect:function(rect){
      this.fullContentsRect = rect;
//      cc.log("setIllustFullTargetRect =" + rect.x + "," + rect.y + ", " + rect.width + ", " + rect.height);
    },
    makeRect:function(index){
      if(index == 1){
        return this.illustFrameRects[0];
      }else{
        return this.illustFrameRects[1];
      }
    },

    updateIllusts:function(dx, dy,touch){
      cc.log("IllustLayer.updateIllusts(): dx, dy, touch, offset = " + dx  + ", " + dy);
      //イラスト入れ替え用に
      cc.log("IllustFrame.update");
        this.frames[0].update(dx,dy,touch);
        this.frames[1].update(dx,dy,touch);
//        this.illusts[index] = cc.Sprite.createWithSpriteFrame(this.getFrame(index));
//        cc.log("(x, y) = (" + this.getCenterPoint(index).x +  ", " + this.getCenterPoint(index).y + ")");
        //はみ出しを確認
     },
    scaleIllust:function(index,scale){
        if(scale >= 0) this.currentScale = scale;
        cc.log("this.currentScale = " + this.currentScale);
        this.frames[index].updateScale(this.currentScale);
    },
    scaleIllusts:function(scale){
      this.scaleIllust(0,scale);
      this.scaleIllust(1,scale);
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

    ctor:function (rect, level, qcode) {
      cc.log("IllustLayer.ctor: rect, level, qcode = " + rect + ", " + level + ", " + qcode );
        self = this;
        this._super();

        this.qcode = qcode;
        this.level = level;

        this.init(rect, level, qcode);
    },

    init:function (rect, level, qcode) {
        var bRet = false;
        if (this._super()) {
            this.setAnchorPoint(cc.p(0, 0));
            this.setPosition(0,0);
            var frame1 = new IllustFrame(this.imgPath(0), rect, 1);
            var frame2 = new IllustFrame(this.imgPath(1), rect, 2);
            frame1.setFriend(frame2);
            frame2.setFriend(frame1);
            self.addChild(frame1);
            self.addChild(frame2);
            this.frames.push(frame1);
            this.frames.push(frame2);

            //イラストの表示範囲を設定
//            this.initIllustFrameRects();
            this.setIllustFullTargetRect(rect);
/*
//            this.rect = new cc.Rect(0,0, 300,300);
            this.offset = new cc.Point(0,0);
            
            var sprite = cc.Sprite.create(this.imgPath(1));
            this.originalSize = sprite._contentSize;
            this.currentScale = 1.0;

            cc.log("this.currentScale = " + this.currentScale);

            this.setPosition(360, 640);

            this.updateIllusts();
    */
            bRet = true;
        }
        return bRet;
    },
/*
   
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