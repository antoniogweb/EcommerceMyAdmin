if (typeof password_regular_expression_caratteri_maiuscoli == "undefined")
	var password_regular_expression_caratteri_maiuscoli = "(?=.*?[A-Z])";

if (typeof password_regular_expression_caratteri_minuscoli == "undefined")
	var password_regular_expression_caratteri_minuscoli = "(?=.*?[a-z])";

if (typeof password_regular_expression_caratteri_numerici == "undefined")
	var password_regular_expression_caratteri_numerici = "(?=.*?[0-9])";

if (typeof password_regular_expression_caratteri_speciali == "undefined")
	var password_regular_expression_caratteri_speciali = "#?!@$%^*-";

if (typeof password_regular_expression_numero_caratteri == "undefined")
	var password_regular_expression_numero_caratteri = 8;

function checkPassword(input)
{
	var password = input.val();
	
	var box = input.parent();
	var boxAvviso = box.find(".box_avvisi_password");
	
	var check = true;
	
	if (boxAvviso.length)
	{
		if (password.length < password_regular_expression_numero_caratteri)
		{
			boxAvviso.find(".avviso_numero_caratteri").removeClass("uk-hidden");
			check = false;
		}
		else
			boxAvviso.find(".avviso_numero_caratteri").addClass("uk-hidden");
		
		var minuscole = new RegExp('^' + password_regular_expression_caratteri_minuscoli + '.{1,}$');
		
		if (!minuscole.test(password))
		{
			boxAvviso.find(".avviso_caratteri_minuscoli").removeClass("uk-hidden");
			check = false;
		}
		else
			boxAvviso.find(".avviso_caratteri_minuscoli").addClass("uk-hidden");
		
		var maiuscole = new RegExp('^' + password_regular_expression_caratteri_maiuscoli + '.{1,}$');
		
		if (!maiuscole.test(password))
		{
			boxAvviso.find(".avviso_caratteri_maiuscoli").removeClass("uk-hidden");
			check = false;
		}
		else
			boxAvviso.find(".avviso_caratteri_maiuscoli").addClass("uk-hidden");
		
		var numerici = new RegExp('^' + password_regular_expression_caratteri_numerici + '.{1,}$');
		
		if (!numerici.test(password))
		{
			boxAvviso.find(".avviso_caratteri_numerici").removeClass("uk-hidden");
			check = false;
		}
		else
			boxAvviso.find(".avviso_caratteri_numerici").addClass("uk-hidden");
		
		
		var speciali = new RegExp('^(?=.*?[' + password_regular_expression_caratteri_speciali + ']).{1,}$');
		
		if (!speciali.test(password))
		{
			boxAvviso.find(".avviso_caratteri_speciali").removeClass("uk-hidden");
			check = false;
		}
		else
			boxAvviso.find(".avviso_caratteri_speciali").addClass("uk-hidden");
	}
	
	return check;
}

$(document).ready(function(){
	
	$("body").on("click", ".help_wizard_password", function(e){
		$(".box_avvisi_password").addClass("uk-hidden");
	});
	
	$("body").on("keyup", ".help_wizard_password", function(e){
		var box = $(this).parent();
		var boxAvviso = box.find(".box_avvisi_password");
		
		if (boxAvviso.length)
		{
			if (!checkPassword($(this)))
				boxAvviso.removeClass("uk-hidden");
			else
				boxAvviso.addClass("uk-hidden");
		}
	});
	
	$("body").on("keydown", ".help_wizard_password", debounce(function(e){
		var box = $(this).parent();
		var boxAvviso = box.find(".box_avvisi_password");
		
		if (boxAvviso.length)
			boxAvviso.addClass("uk-hidden");
	},5000));
});
