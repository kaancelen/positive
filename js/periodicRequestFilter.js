var minutePerCompany = 3;

function lookAtMe(request_id){
	var row = $('#request_'+request_id);
	if(row){
		row.find('#look_gif').attr('src', '/positive/images/look.gif');
	}
}

function lookForRequests(){
	table = $('#request_table');
	$("#request_table tr.row-offer-nothing").each(function(){
		var $this = $(this);
		var id = $this.find("#req_id").html();
		var ratio = $this.find("#ratio").html();
		var date = $this.find("#date").html().replace(/\//g,'-').replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3");
		
		ratio = ratio.split('/');
		var numOfComp = ratio[1];
		var timeout_timestamp = (minutePerCompany*numOfComp)*(60*1000);
		
		var now_timestamp = Date.now();
		var req_timestamp = Date.parse(date);
		
		if((now_timestamp - req_timestamp) > timeout_timestamp){
			lookAtMe(id);
			table.prepend($this);
		}
	});
}

setInterval(function(){
	lookForRequests();
},
30000);