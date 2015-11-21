<script src="/positive/js/offer_request_change_model.js"></script>
<div id="myModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
	    <div class="modal-header">
	    	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    	<h4 class="modal-title">Talep Bilgilerini Düzenle</h4>
	    </div>
	    <div class="modal-body">
	    	<label class="login-error" id="offer-request-error"></label>
			<div id="not_desc_fields">
				<?php if($offerRequest[OfferRequest::POLICY_TYPE] != PolicyType::DIGER){?>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Plaka No</span>
					<input type="text" class="form-control" aria-describedby="basic-addon1" id="plaka" name="plaka" placeholder="99 XXX 99999" value="<?php echo $offerRequest[OfferRequest::PLAKA];?>">
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">
						TC Kimlik No
					</span>
					<input type="text" <?php echo (empty($offerRequest[OfferRequest::TCKN])?"readonly":"");?> class="form-control" aria-describedby="basic-addon1" id="tckn" name="tckn" value="<?php echo $offerRequest[OfferRequest::TCKN];?>">
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">
						Vergi No
					</span>
					<input type="text" <?php echo (empty($offerRequest[OfferRequest::VERGI])?"readonly":"");?> class="form-control" aria-describedby="basic-addon1" id="vergiNo" name="vergiNo" value="<?php echo $offerRequest[OfferRequest::VERGI];?>">
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">
						Belge No
					</span>
					<input type="text" <?php echo (empty($offerRequest[OfferRequest::BELGE])?"readonly":"");?> class="form-control" aria-describedby="basic-addon1" id="belgeNo" name="belgeNo" value="<?php echo $offerRequest[OfferRequest::BELGE];?>">
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">
						ASBİS
					</span>
					<input type="text" <?php echo (empty($offerRequest[OfferRequest::ASBIS])?"readonly":"");?> class="form-control" aria-describedby="basic-addon1" id="asbis" name="asbis" value="<?php echo $offerRequest[OfferRequest::ASBIS];?>">
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">
						Marka Kodu
					</span>
					<input type="text" class="form-control" aria-describedby="basic-addon1" id="marka_kodu" name="marka_kodu" value="<?php echo $offerRequest[OfferRequest::MARKA_KODU];?>">
				</div>
				<br>
			</div>
			<?php }?>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">
					Ek Bilgi
				</span>
				<textarea rows="4" cols="30" class="form-control" aria-describedby="basic-addon1" id="description" name="description" placeholder="Müşteri adı soyadı, sigorta ile alakalı diğer ek bilgiler"><?php echo $offerRequest[OfferRequest::DESCRIPTION];?></textarea>
			</div>
			<h5><small><b>En fazla 2048 karakter</b></small></h5>
			<br>
			<button class="btn btn-lg btn-primary btn-block" type="button" id="offer-request-button" onclick="validateOfferRequestChange('<?php echo $offerRequest[OfferRequest::POLICY_TYPE];?>',<?php echo $offerRequest[OfferRequest::ID]; ?>);">Düzelt</button>
	    </div>
    </div>
  </div>
</div>