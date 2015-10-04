var last_enter_policy_page = getCookie("last_enter_policy_page");
var le_policy_page_flag = getCookie("le_policy_page_flag");

var last_enter_offer_resp = getCookie("last_enter_offer_resp");
var le_offer_resp_flag = getCookie("le_offer_resp_flag");

var last_enter_policy_req_page = getCookie("last_enter_policy_req_page");
var le_policy_req_page_flag = getCookie("le_policy_req_page_flag");

//Re paint not checked tabs
if(le_offer_resp_flag == 'on'){
    $('#branch_2').removeClass('active');
    $('#branch_2').addClass('poll-alert-1');
}
if(le_policy_page_flag == 'on'){
	$('#branch_4').removeClass('active');
	$('#branch_4').addClass('poll-alert-2');
}
if(le_policy_req_page_flag == 'on'){
	$('#branch_5').removeClass('active');
	$('#branch_5').addClass('poll-alert-2');
}

var data = new FormData();
data.append('last_enter_policy_page', last_enter_policy_page);
data.append('last_enter_offer_resp', last_enter_offer_resp);
data.append('last_enter_policy_req_page', last_enter_policy_req_page);

setInterval(function(){
	//make ajax request
    $.ajax({
        url: '/positive/ajax/policyPolling.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(data, textStatus, jqXHR){
        	if(data){
        		if(data[0] > 0){
        			$('#branch_2').removeClass('active');
                    $('#branch_2').addClass('poll-alert-1');
        		}
                if(data[1] > 0){
                    $('#branch_4').removeClass('active');
                    $('#branch_4').addClass('poll-alert-2');
                }
                if(data[2] > 0){
                	$('#branch_5').removeClass('active');
                    $('#branch_5').addClass('poll-alert-2');
                }

                console.log(data);
        	}
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('policyPolling ajax error : ' + textStatus);
        },
        complete: function(jqXHR, textStatus){
            console.log("policyPolling ajax call complete : " + textStatus);
        }
    });
},
10000);

function uriDateToTimestamp(uriDate){
	var temp = decodeURIComponent(uriDate);
	temp = temp.replace("+", " ");
	var timestamp = Math.round((new Date(temp).getTime() / 1000));
	return timestamp;
}