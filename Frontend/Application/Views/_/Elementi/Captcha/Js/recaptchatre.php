<?php if (!defined('EG')) die('Direct access not allowed!');
$params = CaptchaModel::getModulo()->getParams();
?>

<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $params["secret_client"];?>"></script>
<script>
$("body").on("click", "button,input[type='submit']", function(e){
	
	if ($(this).closest("form").find("[name='recaptchatre']").length > 0)
	{
		e.preventDefault();
		
		grecaptcha.ready(function() {
			grecaptcha.execute('<?php echo $params["secret_client"];?>', {action: 'submit'}).then(function(token) {
				console.log(token);
			});
        });
	}
	
});
</script>
