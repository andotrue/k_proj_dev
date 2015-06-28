var Question = cc.Class.extend({
	QUESTION_ID: null,
	QCODE: null,
	TITLE: null,
	LIMIT_TIME: null,
	PERMITTED_OTETSUKI_COUNT: null,
	kanahei_POINT_DATA: null,

	ctor:function(){
	},
	init:function(questionId, qcode, title, limitTime, permittedOtetsukiCount, kanaheiPointData){
		if(kanaheiPointData === undefined ) throw "Question.ctor: kanaheiPointData is needed!";
		this.QUESTION_ID = questionId;
		this.QCODE = qcode;
		this.TITLE = title;
		this.LIMIT_TIME = limitTime;
		this.PERMITTED_OTETSUKI_COUNT = permittedOtetsukiCount;
		this.kanahei_POINT_DATA = kanaheiPointData;
	},
});