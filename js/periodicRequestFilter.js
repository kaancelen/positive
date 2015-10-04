var minutePerCompany = 3;

function sortTable(){
    var tbl = document.getElementById("request_table").tBodies[0];
    var store = [];
    for(var i=0, len=tbl.rows.length; i<len; i++){
        var row = tbl.rows[i];
        var sortnr = parseFloat(row.cells[0].textContent || row.cells[0].innerText);
        if(!isNaN(sortnr)) store.push([sortnr, row]);
    }
    store.sort(function(x,y){
        return x[0] - y[0];
    });
    for(var i=0, len=store.length; i<len; i++){
        tbl.appendChild(store[i][1]);
    }
    store = null;
}

function lookAtMe(request_id){
	var row = $('#request_'+request_id);
	if(row){
		row.find('#look_gif').attr('src', '/positive/images/look.gif');
	}
}

function lookForRequests(){
	sortTable();
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