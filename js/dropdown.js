var options = [];
//set cookie
var cookieCompanies = getCookie('companies');
if(cookieCompanies){
	options = JSON.parse(cookieCompanies);
	for(var i=0; i<options.length; i++){
		$('#comp_'+options[i]).prop('checked', true);
	}
}

$( '.dropdown-menu a' ).on( 'click', function( event ) {
   var $target = $( event.currentTarget ),
       val = $target.attr( 'data-value' ),
       $inp = $target.find( 'input' ),
       idx;

   if ( ( idx = options.indexOf( val ) ) > -1 ) {
      options.splice( idx, 1 );
      setTimeout( function() { $inp.prop( 'checked', false ) }, 0);
   } else {
      options.push( val );
      setTimeout( function() { $inp.prop( 'checked', true ) }, 0);
   }

   $( event.target ).blur();
   $('#num_of_selected').html(options.length);
   options = options.filter(function(n){ return n != undefined });//check for null before set again
   setCookie('companies', JSON.stringify(options), 1);
   
   return false;
});

function onDropdownChange(){
	agent_name = $('#selected_agent').val();
	policy_type = $('#selected_policy_type').val();
	
	$("table tr").each(function(index) {
        if (index > 1) {
            $row = $(this);
            var agent_name_id = $row.find("td:nth-child(4)").text();
            var policy_type_id = $row.find("td:nth-child(5)").text();
            
            if( (agent_name === 'NULL' && policy_type === 'NULL') ||
        		(agent_name === 'NULL' && policy_type_id.indexOf(policy_type) === 0) ||
        		(policy_type === 'NULL' && agent_name_id.indexOf(agent_name) === 0) ||
        		(agent_name_id.indexOf(agent_name) === 0 && policy_type_id.indexOf(policy_type) === 0)){
            	
            	$row.show();
            }else{
            	$row.hide();
            }
        }
    });
}

function getOfferRatio(request_id){
	var data = new FormData();
	data.append('request_id', request_id);
	
	//make ajax request
    $.ajax({
        url: '/positive/ajax/get_offer_ratio.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(data, textStatus, jqXHR){
        	if(data){
        		$('#ratio_'+request_id).find('img').css('visibility', 'hidden');
        		$('#ratio_'+request_id).html(data);
        	}
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('get_offer_ratio ajax error : ' + textStatus);
        },
        complete: function(jqXHR, textStatus){
            console.log("get_offer_ratio ajax call complete : " + textStatus);
        }
    });
}