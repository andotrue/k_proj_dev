var Stars = cc.Class.extend({
    _MAX: null,
    _count: 0,
    _objWidth: 30,
    _objHeight: 40,
    _objX: 420,
    _objY: 1256,
    _sprites: [],
    parent: null,
    _file_path_off: gsDir + "other/game_star_off.png",
    _file_path_on: gsDir + "other/game_star_on.png",
    ctor:function(parent, machigai_limit){
        if(parent === undefined) throw("Star.ctor: parent must not be undefined!");
        this.init(parent);
    },
    init:function(parent, machigai_limit){
        this.parent = parent;
        this._MAX = machigai_limit;
        cc.log("parent._id = " + this.parent.__instance_id);
        for (var i = 0; i < this._MAX; i++) {
            this._changeSprite(i, this._file_path_off);
        }
    },
    count:function(){
        return this._count;
    },
    increment:function(){
        if(this.count() >= this._MAX) throw "Stars: Over the Max Star counts.";
        var cursor = this.count();
        this._changeSprite(cursor, this._file_path_on);
        this._count += 1;
        return this.count();
    },
    _changeSprite:function(index, file_path){
        cc.log("Stars._changeSprite: index = " + index + ", file_path = " + file_path);
        var star  = cc.Sprite.create( file_path );
        if (this._sprites[index] !== undefined)
            this._sprites[index].removeFromParent();
        this._sprites[index] = star;
        this.parent.addChild(star);
        star.setPosition(this._objX + this._objWidth * index ,this._objY);
    }
});