$(document).ready(function(){
	if ($(".my-rating").length > 0 && $("[name='voto']").length > 0)
	{
		var voto = $("[name='voto']").val();
		
		if (voto == "")
			voto = 0;
		
		initProductRating($(".my-rating"), {
			starSize: 30,
			disableAfterRate: false,
			minRating: 1,
			initialRating: voto,
			ratedColor: 'gold',
			hoverColor: 'gold',
			callback: function(currentRating, $el){
				$("[name='voto']").val(currentRating);
				
			}
		});
	}
});
