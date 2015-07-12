var companies = getCookie("companies");
var last_enter_offer_req = getCookie("last_enter_offer_req");
var last_enter_policy_req = getCookie("last_enter_policy_req");

var le_offer_flag = getCookie("le_offer_flag");
var le_policy_flag = getCookie("le_policy_flag");

//Re paint not checked tabs
if(le_offer_flag == 'on'){
	$('#personel_1').removeClass('active');
	$('#personel_1').addClass('poll-alert-1');
}
if(le_policy_flag == 'on'){
	$('#personel_2').removeClass('active');
	$('#personel_2').addClass('poll-alert-2');
}

var data = new FormData();
data.append('companies', JSON.parse(companies));
data.append('last_enter_offer_req', last_enter_offer_req);
data.append('last_enter_policy_req', last_enter_policy_req);

setInterval(function(){
	//make ajax request
    $.ajax({
        url: '/positive/ajax/offerPolicyPolling.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(data, textStatus, jqXHR){
        	if(data){
        		if(data[0] > 0){
        			$('#personel_1').removeClass('active');
                	$('#personel_1').addClass('poll-alert-1');
        		}
        		if(data[1] > 0){
        			$('#personel_2').removeClass('active');
                	$('#personel_2').addClass('poll-alert-2');
        		}
        	}
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('offerPolicyPolling ajax error : ' + textStatus);
        },
        complete: function(jqXHR, textStatus){
            console.log("offerPolicyPolling ajax call complete : " + textStatus);
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