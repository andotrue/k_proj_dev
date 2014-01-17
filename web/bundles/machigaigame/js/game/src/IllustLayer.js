var IllustLayer = cc.Layer.extend({

    ctor:function () {
        this._super();
        this.init();
    },
    init:function () {
        var bRet = false;
        if (this._super()) {
            var MondaiArea = cc.Sprite.create( gsDir + "background/mondaiarea.png");
            var Ng = cc.Sprite.create( gsDir + "other/ng.png" );
            var Ok = cc.Sprite.create( gsDir + "other/ok.png" );

            //Layerの子要素に。
            this.addChild(MondaiArea);
            this.addChild(Ng);
            this.addChild(Ok);

            //Positionの設定
            MondaiArea.setPosition(640,960);
            MondaiArea.setScaleY(3.8);
            Ng.setPosition(640,960);
            Ok.setPosition(640,960);

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