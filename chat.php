<?php include_once (__DIR__.'/service/ChatService.php');?>
<?php include_once (__DIR__.'/classes/chat.php');?>
<script src="/positive/js/chat.js"></script>
<h4 style="text-align:center">Konuşma</h4>
<hr>
<input type="hidden" id="chat_request_id">
<script type="text/javascript">
	$('#chat_request_id').val(<?php echo $chat_request_id; ?>);
</script>
<div class="chatbox">
	<div class="chat_messages">
		<ul class="chatline">
			
		</ul>
	</div>
	<div id="send_messages">
		<label for="chat_input" class="login-error" id="chat_error"></label>
		<textarea rows="4" cols="30" class="form-control" id="chat_input"></textarea>
		<button class="btn btn-lg btn-primary btn-block" type="button" id="chat_send_btn" onclick="validateChatInput();">Gönder</button>
	</div>
</div>
<?php 
	$chatService = new ChatService();
	$allChat = $chatService->getEntries($chat_request_id);
	foreach ($allChat as $chat){
?>
<script type="text/javascript">
	insertEntry('<?php echo $chat[Chat::TEXT];?>', '<?php echo $chat[Chat::USER_NAME];?>', '<?php echo DateUtil::hour_format($chat[Chat::CREATION_DATE]);?>');
</script>
<?php } ?>
<script type="text/javascript">
	pullChat(<?php echo $chat_request_id; ?>);
	$("#chat_input").keyup(function (e) {
	    if (e.keyCode == 13) {
	    	$('#chat_send_btn').trigger("click");//trigger send button
	    }
	});
</script>