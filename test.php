<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 

include_once (__DIR__.'/head.php');

// $db = DB::getInstance();

// $db->beginTransaction();

// $db->query("INSERT INTO COMPANY(NAME, ACTIVE) VALUES(?, ?)", array("Kaan", 1));
// $result = $db->all();
// if(is_null($result)){
// 	echo "CANNOT INSERT";
// }else{
// 	echo "INSERT";
// }
// $db->commit();
// $db->rollback();

?>

<ul class="chatline">
			
		</ul>

<script type="text/javascript">
	var timetext = "10:03";
	var username = "Kaan Celen";
	var text = "<a href='/positive/files/chat/sad.txt' target='_blank'>Hello</a>";
	var newline = '<li>(<i>'+timetext+'</i>)<b>'+username+'</b> : '+text+'</li>';
	$('.chatline').append(newline);
</script>