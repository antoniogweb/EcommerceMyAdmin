if (typeof stringa_testo_copiato_clipboard == "undefined")
	var stringa_testo_copiato_clipboard = "Il link della lista è stato copiato negli appunti.";

function copyToClipboard(testo) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val(testo).select();
  document.execCommand("copy");
  $temp.remove();
}

$(document).ready(function(){
	
	$( "body" ).on( "click", ".share-link", function(e) {
		e.preventDefault();
		
		var that = $(this);
		
		if (navigator.share) {
			navigator.share({
				title: that.attr("share-title"),
				text: that.attr("share-text"),
				url: that.attr("href"),
			})
		}
		else
		{
			copyToClipboard(that.attr("href"));
			
			alert(stringa_testo_copiato_clipboard);
		}
	});

	$( "body" ).on( "click", ".ajlink", function(e){
		e.preventDefault();  //prevent form from submitting

		var url = $(this).attr("href");

		$.ajaxQueue({
			url: url,
			cache:false,
			async: true,
			dataType: "html",
			success: function(content){

				location.reload();

			}
		});
	});
});
