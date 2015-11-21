<script src="/positive/js/changePrimModal.js"></script>
<div id="myModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
	    <div class="modal-header">
	    	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    	<h4 class="modal-title">Prim Bilgilerini Düzenle</h4>
	    </div>
	    <div class="modal-body">
	    	<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">
					Prim
				</span>
				<input type="text" class="form-control" aria-describedby="basic-addon1" id="prim" name="prim">
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">
					Komisyon
				</span>
				<input type="text" class="form-control" aria-describedby="basic-addon1" id="komisyon" name="komisyon">
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">
					Prod Komisyon
				</span>
				<input type="text" readonly class="form-control" aria-describedby="basic-addon1" id="prod_komisyon" name="prod_komisyon">
			</div>
			<br>
			<input type="hidden" id="ust_komisyon" name="ust_komisyon">
			<input type="hidden" id="bagli_komisyon" name="bagli_komisyon">
	    	<button class="btn btn-lg btn-primary btn-block" type="button" id="offer-request-button" onclick="updateOffer(<?php echo $policyReqDetail[PolicyRequest::OFFER_ID];?>);">Düzelt</button>
	    </div>
    </div>
  </div>
</div>
<?php 
	$agentService = new AgentService();
	$agentRelation = $agentService->getAgentRelation($policyReqDetail[PolicyRequest::BRANCH_ID]);
?>
<script type="text/javascript">
	$('#prim').mask('000.000.000.000.000,00', {reverse: true});
	$('#komisyon').mask('000.000.000.000.000,00', {reverse: true});
	
	$('#prod_komisyon').change(function(){
		var prod_komisyon_value = (Number)(this.value);
		this.value = prod_komisyon_value.format(2, 3, '.', ',');
	});
	$('#ust_komisyon').change(function(){
		var ust_komisyon_value = (Number)(this.value);
		this.value = ust_komisyon_value.format(2, 3, '.', ',');
	});
	$('#bagli_komisyon').change(function(){
		var bagli_komisyon_value = (Number)(this.value);
		this.value = bagli_komisyon_value.format(2, 3, '.', ',');
	});
	
	$('#komisyon').keyup(function() {
		var komisyon = $(this).val();
		komisyon = komisyon.replace('.', '').replace(',', '.');
		var prod_komisyon = (komisyon * <?php echo $agentRelation[AgentRelation::KOMISYON]; ?>) / 100;
		$('#prod_komisyon').val(prod_komisyon).trigger('change');
	
		var ust_komisyon = (komisyon * <?php echo $agentRelation[AgentRelation::UST_KOMISYON]; ?>) / 100;
		$('#ust_komisyon').val(ust_komisyon).trigger('change');
	
		var bagli_komisyon = (komisyon * <?php echo $agentRelation[AgentRelation::BAGLI_KOMISYON]; ?>) / 100;
		$('#bagli_komisyon').val(bagli_komisyon).trigger('change');
	});
</script>