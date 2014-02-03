var Question = cc.Class.extend({
	QUESTION_ID: null,
	QCODE: null,
	TITLE: null,
	LIMIT_TIME: null,
	PERMITTED_OTETSUKI_COUNT: null,
	MACHIGAI_POINT_DATA: null,

	ctor:function(){
	},
	init:function(questionId, qcode, title, limitTime, permittedOtetsukiCount, machigaiPointData){
		if(machigaiPointData === undefined ) throw "Question.ctor: machigaiPointData is needed!";
		this.QUESTION_ID = questionId;
		this.QCODE = qcode;
		this.TITLE = title;
		this.LIMIT_TIME = limitTime;
		this.PERMITTED_OTETSUKI_COUNT = permittedOtetsukiCount;
		this.MACHIGAI_POINT_DATA = machigaiPointData;
	},
});