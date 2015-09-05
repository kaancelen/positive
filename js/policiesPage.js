function policyTabChange(type){
	if(type == 0){
		$('#policy_tabs_iptal').removeClass("active");
		$('#policy_tabs_uretim').addClass("active");
		
		$('#cancel_req_table').css('display', 'none');
		$('#policy_req_table').css('display', '');
	}else if(type == 1){
		$('#policy_tabs_uretim').removeClass("active");
		$('#policy_tabs_iptal').addClass("active");
		
		$('#policy_req_table').css('display', 'none');
		$('#cancel_req_table').css('display', '');
	}
}