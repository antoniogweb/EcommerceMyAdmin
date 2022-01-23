$(document).ready(function(){
	if ($(".my-rating").length > 0 && $("[name='voto']").length > 0)
	{
		var voto = $("[name='voto']").val();
		
		if (voto == "")
			voto = 0;
		
		$(".my-rating").starRating({
			starSize: 30,
			disableAfterRate: false,
			useFullStars: true,
			starShape: 'rounded',
			minRating: 1,
			initialRating: voto,
			useGradient: false,
			ratedColor: 'gold',
			ratedColors: ['gold', 'gold', 'gold', 'gold', 'gold'],
			callback: function(currentRating, $el){
				$("[name='voto']").val(currentRating);
			}
		});
	}
});
