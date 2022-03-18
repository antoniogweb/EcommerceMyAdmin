<?php if (!defined('EG')) die('Direct access not allowed!');
$params = CaptchaModel::getModulo()->getParams();
?>

<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $params["secret_client"];?>"></script>
<script>
$(document).ready(function() {
	$("body").on("click", "button,input[type='submit']", function(e){
		
		var that = $(this);
		var value = that.attr("name");
		console.log(value);
		var thisForm = $(this).closest("form");
		
		if (thisForm.find("[name='<?php echo $params["campo_nascosto"];?>']").length > 0)
		{
			e.preventDefault();
			
			grecaptcha.ready(function() {
				grecaptcha.execute('<?php echo $params["secret_client"];?>', {action: 'submit'}).then(function(token) {
					$("[name='<?php echo $params["campo_nascosto"];?>']").val(token);
					
					if (typeof value != "undefined")
						thisForm.append("<input type='hidden' name='"+value+"' value='"+value+"' />")
					
					thisForm.trigger("submit");
				});
			});
		}
		
	});
});
</script>
