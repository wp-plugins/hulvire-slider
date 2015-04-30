	<?php $data = $_POST["postovaneudaje"]; $delay = $_POST["popupdelay"]; ?>

	<?php echo $data; ?>


<script>

jQuery(document).ready(function($){
	$('.popup_content').delay(<?php echo $delay; ?>).slideDown(500);
	// PopUp CLOSE'==========================================
	$('.popup_close').mouseover(function(){
			$(this).css({'color':'#fff'});
		}).mouseout(function(){
			$(this).css({'color':'#E1312D'});
		}).click(function(){
			$('.popup_content').delay(100).slideUp(600);
		});
				
});
	
</script>
