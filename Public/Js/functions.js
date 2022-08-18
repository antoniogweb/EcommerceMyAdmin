var tiny_editor_config = {
	// Location of TinyMCE script
	script_url :  baseUrlSrc+ '/Public/Js/tiny_mce/tiny_mce_src.js',
	convert_urls : false,

	force_br_newlines : true,
	force_p_newlines : false,
	forced_root_block : '',
	entity_encoding : "raw",
			width : "100%",
			height : "250",
	
	// General options
	theme : "advanced",
	plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

	// Theme options
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,bullist,numlist,justifyleft,justifycenter,justifyright,justifyfull",
	theme_advanced_buttons2 : "link,unlink,image,formatselect,forecolor,backcolor,sub,sup,code",
	
	file_browser_callback : "ajaxfilemanager",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	accessibility_warnings : false,
	accessibility_focus : false
};

var form_modificato = false;

if (typeof(ajaxfilemanager) !== typeof(Function))
{
	function ajaxfilemanager(field_name, url, type, win) {
		var ajaxfilemanagerurl = baseUrl + "/upload/main/1/1/1/1/0/0/1/0/1/0/1?base=";
		var fileBrowserWindow = new Array();
		fileBrowserWindow["file"] = ajaxfilemanagerurl;
		fileBrowserWindow["title"] = "Ajax File Manager";
		fileBrowserWindow["width"] = "782";
		fileBrowserWindow["height"] = "440";
		fileBrowserWindow["resizable "] = "yes";
		fileBrowserWindow["inline"] = "yes";
		fileBrowserWindow["close_previous"] = "no";
		tinyMCE.activeEditor.windowManager.open(fileBrowserWindow, {
			window : win,
			input : field_name
		});
		
		return false;
	}
}

var inputFieldErrorBorderStyle = "2px solid red";
var dataFormat = "dd-mm-yy";

//fa apparire la lightbox
function open_lightbox(titolo, contenuto)
{
	$("#lightbox_title").text(titolo);
	$("#lightbox_content").html(contenuto);
	
	var bodyHeight = $("body").height();
	var viewportHeight = $(window).height();
	var viewportWidth = $(window).width();
	
	var height = bodyHeight > viewportHeight ? bodyHeight : viewportHeight;
	
	$(".black_overlay").css("height",Math.round(height)+"px").css("display","block");
	$(".white_content").css("display","block").css("top",(Math.round(viewportHeight/2)-200)+"px").css("left",(Math.round(viewportWidth/2)-210)+"px");
	
	$(".close_lightbox, .black_overlay").click(function(){
		
		$(".close_lightbox, .black_overlay").unbind();
		$(".black_overlay, .white_content").css("display","none");
		
		return false;
	});
}

function close_lightbox()
{
	$(".close_lightbox, .black_overlay").unbind();
	$(".black_overlay, .white_content").css("display","none");
}

jQuery(function($){
	$.datepicker.regional['it'] = {
		closeText: 'Chiudi',
		prevText: '&#x3c;Prec',
		nextText: 'Succ&#x3e;',
		currentText: 'Oggi',
		monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno',
			'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'],
		monthNamesShort: ['Gen','Feb','Mar','Apr','Mag','Giu',
			'Lug','Ago','Set','Ott','Nov','Dic'],
		dayNames: ['Domenica','Luned&#236','Marted&#236','Mercoled&#236','Gioved&#236','Venerd&#236','Sabato'],
		dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'],
		dayNamesMin: ['Do','Lu','Ma','Me','Gi','Ve','Sa'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['it']);
});

var closedd = false;

var child;
var timer;

function checkChild() {
    if (child.closed || closedd) {
        clearInterval(timer);
		
		reloadPage();
		
// 		location.reload();
    }
}

function reloadPage()
{
	if ($(".formClass").length > 0)
	{
		$(".formClass").find(".submit_entry button").attr("name","gAction");
		$(".formClass").find(".submit_entry input").attr("name","gAction");
		$(".formClass").submit();
	}
	else
	{
		location.reload();
// 		window.location=window.location;
	}
}

function aggAlteIfr()
{
	if (partial && window.frameElement && window.parent.$(".admin_panel").length > 0)
	{
		setTimeout(function(){
			var altezza = $(".wrapper").height();
			window.parent.$("iframe.iframe_dialog").attr("height",altezza + "px");
			window.parent.$('#my_modal').modal({refresh:true});
		}, 100);
	}
}

function controllaVisibilita()
{
	if ($(".formClass").length > 0)
	{
		$(".formClass").find("[visible-f]").each(function(){
			
			var field = $(this).attr("visible-f");
			var value = $(this).attr("visible-v");
			
			if ($("[name='"+field+"']").length > 0)
			{
				if ($("[name='"+field+"']").val() == value)
					$(this).css("display", "block");
				else
					$(this).css("display", "none");
				
			}
		});
	}
}

$(document).ready(function(){

// 	$(".thumb").each(function(){
// 		
// 		var thumb = $(this).text();
// 		var path = 	$(this).attr("data-field-path");
// 		var field = $(this).attr("data-field");
// 
// 		if (thumb != "" && path != undefined)
// 		{
// 			$(this).addClass("box_immagine_upload").html("<img src='"+parentBaseUrl + "/" + path + "/" + thumb + "'>").append("<a class='elimina_allegato' data-field='"+field+"' title='cancella immagine' href=''><img src='"+baseUrl+"/Public/Img/Icons/elementary_2_5/delete.png' /></a>");
// 		}
// 		
// 	});
// 	
// 	$(".file").each(function(){
// 		
// 		var file = $(this).text();
// 		var path = 	$(this).attr("data-field-path");
// 		var field = $(this).attr("data-field");
// 
// 		if (file != "" && path != undefined)
// 		{
// 			$(this).addClass("box_immagine_upload").html("<span class='file_container'><a target='_blank' href='"+parentBaseUrl+"/"+path+"/"+file+"'>"+file+"</a></span>").append("<a class='elimina_allegato' data-field='"+field+"' title='cancella file' href=''><img src='"+baseUrl+"/Public/Img/Icons/elementary_2_5/delete.png' /></a>");
// 		}
// 		
// 	});
	
	$("body").on("click", ".elimina_allegato", function(e){
		
		e.preventDefault();
		
		var field = $(this).attr("data-field");
		
		$(this).parents("form").append("<input type='hidden' name='"+field+"--del--' value='' />");
		
		$(this).parents(".thumb,.file").remove();
	});

	$(".first_level_item").mouseover(function(){
		$(this).find("ul").first().css("display","block");
	}).mouseout(function(){
		$(this).find("ul").css("display","none");
	});

	$(".second_level_item").mouseover(function(){
		$(this).find("ul").css("display","block");
	}).mouseout(function(){
		$(this).find("ul").css("display","none");
	});
	
	$(".stampa_pagina").click(function(){
		
		window.print();
		
		return false;
	});
	
	if ($(".mainMenu").length > 0)
	{
		var top_menu = '<br />' + $(".mainMenu").html();
// 		$(".main").append(top_menu);
	}
	
	$(".save_button").click(function(e){
		
		e.preventDefault();
		
		$(".submit_entry").find("button").trigger('click');
	});
	
	$(".resetta_button").click(function(e){
		
		e.preventDefault();
		
		window.location.href=window.location.href;
		
	});

	$(".elimina_button").click(function(e){
	
		var that = $(this);
		
		e.preventDefault();
		
		if (window.confirm("Vuoi veramente cancellare l'elemento?")) {
		
			if ($(".formClass").find("input[type='hidden']").length > 0)
			{
				var id = $(".formClass").find("input[type='hidden']").val();
				var t_name = $(".formClass").find("input[type='hidden']").attr("name");
			}
			else
			{
				var id = that.attr("id");
				var t_name = that.attr("rel");
			}
			
			if (id != 0)
			{
				var formHtml = '<form class="hidden_form" method="POST" action="'+baseUrl+'/'+controllerName+'/main'+viewStatus+'"><input type="hidden" value="cancella" name="delAction"><input type="hidden" value="'+id+'" name="'+t_name+'"></form>';
				
				$("body").append(formHtml);
				
				$(".hidden_form").submit();
			}
		}
	});
	
	window.closeModal = function(){
		$('#my_modal').modal('hide');
	};
	
	$("body").on("click", ".iframe_td a, a.iframe, img.iframe", function(e){
		
		var t_href = $(this).attr("href");
		
// 		console.log(t_href);
		
		e.preventDefault();
		
		$('.modal').on('shown.bs.modal',function(){      //correct here use 'shown.bs.modal' event which comes in bootstrap3
			$(this).find('iframe').attr('src',t_href)
		});
		
		$('.modal').on('hide.bs.modal',function(){      //correct here use 'shown.bs.modal' event which comes in bootstrap3
			reloadPage();
		});
		
		$('#my_modal').modal({show:true});
		
	});
	
	$(".elimina_categoria_associata").click(function(e){
		
		e.preventDefault();
		
		var id_page = $(this).attr("rel");
		
		$.ajax({
			url: baseUrl + "/" + controllerName + "/eliminacategoria/" + id_page,
			async: false,
			cache:false,
			success: function(content){
				reloadPage();
			}
		});
		
	});
	
	if ($(".formClass").length > 0)
	{
		$("body").on("change", ".formClass input, .formClass select, .formClass textarea", function(e){
// 		$(".formClass input, .formClass select, .formClass textarea").change(function(){
			
			form_modificato = true;
// 			console.log("AAA");
		});
		
		$(".nav_dettaglio a, .nav-sidebar a, .panel-title a").click(function(e){
			
			if (form_modificato)
			{
				if (!window.confirm("Se non salvi perderai le modifiche effettuate. Confermi il salvataggio?")) {
					
				}
				else
				{
					$(".submit_entry").find("button").trigger('click');
// 					$(".formClass").submit();
					e.preventDefault();
				}
			}
			
		});
	}
	
	$(".box_fatture_del_link").click(function(){
		
		if (confirm("Confermi l'eliminazione?"))
		{
			return true;
		}
		
		return false;
		
	});
	
	
	
	$( ".data_field,.date_input" ).datepicker( {
		dateFormat: dataFormat
	} );
	
	
	$("body").on("click", ".save_combinazioni", function(e){
		
		e.preventDefault();
		
		var that = $(this);
		
		that.find("i").addClass("fa-spin");
		
		var valori = [];
		
		$("table tr.listRow").each(function(){
			
			var id_c = $(this).find("[name='codice']").attr("id-c");
			var id_cl = $(this).find("[name='price']").attr("id-cl");
			var codice = $(this).find("[name='codice']").val();
			var prezzo = $(this).find("[name='price']").val();
			var peso = $(this).find("[name='peso']").val();
			
			var temp = {
				id_c: id_c,
				id_cl: id_cl,
				codice: codice,
				prezzo: prezzo,
				peso: peso
			};
			
			if ($(this).find("[name='giacenza']").length > 0)
				temp.giacenza = $(this).find("[name='giacenza']").val();
			
			if ($(this).find("[name='immagine']").length > 0)
				temp.immagine = $(this).find("[name='immagine']").val();
			
			valori.push(temp);
		});
		
// 		console.log(valori);
		
		$.ajaxQueue({
			url: baseUrl + "/combinazioni/salva",
			cache:false,
			async: true,
			dataType: "html",
			type: "POST",
			data: {
				valori: JSON.stringify(valori)
			},
			success: function(content){
				
				that.find("i").removeClass("fa-spin");
				
			}
		});
		
	});
	
	$("body").on("click", ".save_redirect", function(e){
		
		e.preventDefault();
		
		var that = $(this);
		
		that.parent().find("i.fa-refresh").addClass("fa-spin");
		
		$.ajaxQueue({
			url: that.attr("href"),
			cache:false,
			async: true,
			dataType: "html",
			success: function(content){
				
				setTimeout(function(){
				
					that.find("i").removeClass("fa-spin");
					
					alert("File redirect rigenerato correttamente");
					
				}, 1000);
			}
		});
		
	});
	
	$("body").on("change", ".edit-traduzione", function(e){
		
		var that = $(this);
		
		var id_t = $(this).attr("id-t");
		var valore = $(this).val();
		
		that.parent().find("i.fa-refresh").css("display", "block");
		
		$.ajaxQueue({
			url: baseUrl + "/traduzioni/aggiorna/",
			cache:false,
			async: true,
			dataType: "html",
			type: "POST",
			data: {
				id_t: id_t,
				valore: valore
			},
			success: function(content){
				
				that.find("i").removeClass("fa-spin");
				
				setTimeout(function(){
					
					that.parent().find("i.fa-refresh").css("display", "none");
					
					that.parent().find("i.fa-check").css("display", "block");
					
					setTimeout(function(){
					
						that.parent().find("i.fa-check").css("display", "none");
						
					}, 1000);
						
				}, 500);
			}
		});
	});
	
	$("body").on("click", ".elimina_traduzione", function(e){
		
		e.preventDefault();
		
		var that = $(this);
		
		that.find("i").removeClass("fa-trash").addClass("fa-refresh").addClass("fa-spin");
		
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
	
	$("body").on("click", ".svuota_cache", function(e){
		
		e.preventDefault();
		
		var that = $(this);
		
// 		that.find("i").removeClass("fa-trash").addClass("fa-refresh").addClass("fa-spin");
		
		var url = $(this).attr("href");
		
		$.ajaxQueue({
			url: url,
			cache:false,
			async: true,
			dataType: "html",
			success: function(content){
				
				alert("Cache svuotata")
				
			}
		});
		
	});
	
	$('.colorpicker-element').colorpicker();
	
	$("body").on("submit", ".moveupForm form, .movedownForm form", function(e){
        e.preventDefault();  //prevent form from submitting
        var data = $(this).serializeArray();
		var url = $(this).attr("action");
		
		$.ajaxQueue({
			url: url,
			cache:false,
			async: true,
			dataType: "html",
			method: "POST",
			data: data,
			success: function(content){
				
				window.location.href=window.location.href;
// 				location.reload();
				
			}
		});
    });
	
	$(".fileinput-button-custom").on("click",  function(e){
        e.stopPropagation();
		$(this).find("input")[0].click();
    });
	
	
	$('.fileinput-button-custom input').change(function() {
		var filename = $(this).val().replace(/C:\\fakepath\\/i, '');
		$(".fileinput-button-custom span").text(filename);
		
		$('.upload_traduzioni').css("display","inline");
    });
	
	setTimeout(function(){
		
		aggAlteIfr();
		
	}, 100);
	
	$( "body" ).on( "click", "a", function(e){
		aggAlteIfr();
	});
	
	$(document).ajaxSuccess(function() {
		aggAlteIfr();
	});
	
	$( "body" ).on( "click", ".make_spinner", function(e){
		if ($(this).find("i").length > 0)
			$(this).find("i").attr("class","fa fa-refresh fa-spin");
	});
	
	$( "body" ).on( "click", ".ajlink", function(e){
		e.preventDefault();  //prevent form from submitting
		
		var url = $(this).attr("href");
		
		if ($(this).find("i").length > 0)
			$(this).find("i").attr("class","fa fa-refresh fa-spin");
		
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
	
	$( "body" ).on( "click", ".bulk_trigger", function(e){
		e.preventDefault();  //prevent form from submitting
		
		var azione = $(this).attr("data-azione");
// 		var classefa = $(this).attr("class");
		$(this).attr("class","fa fa-refresh fa-spin")
		
		$(this).closest('tr').find("td").first().find("input").prop('checked', true);
		
		$(this).parents('table').find('.bulk_actions_select').val(azione).trigger('change');
	});
	
	// Autocomplete typehead
	$(".auto").each(function(){
		var urlAuto = baseUrl + "/" + $(this).attr("source");
		var that = $(this);
		
		$.get(urlAuto, function(data){
			that.typeahead({ source:data });
		},'json');
	});
	
	controllaVisibilita();
	
	$( "body" ).on( "change", "[on-c='check-v']", function(e){
		controllaVisibilita();
	});
	
	if ($("[select2]").length > 0)
	{
		$("[select2]").each(function(){
			
			var url = $(this).attr("select2");
			
			if (url != "")
			{
				$(this).find("select").select2({
					ajax: {
						url: baseUrl + url,
						processResults: function (data) {
							// Transforms the top-level key of the response object from 'items' to 'results'
							return {
								results: data.results
							};
						},
						delay: 1000
					},
					minimumInputLength: 2,
					language: {
						inputTooShort: function(args) {
							return "Digitare 2 o più caratteri";
						},
						searching: function() {
							return "In attesa..";
						},
						noResults: function() {
							return "Non ci sono risultati";
						},
						errorLoading: function() {
							return "In attesa..";
						}
					}
				});
			}
			else
				$(this).find("select").select2();
		});
	}
	
	// this is the id of the form
	$("form.ajax_submit").submit(function(e) {

		e.preventDefault(); // avoid to execute the actual submit of the form.

		var form = $(this);
		var url = form.attr('action');
		
		$.ajaxQueue({
			type: "POST",
			url: url,
			data: form.serialize(), // serializes the form's elements.
			success: function(data)
			{
				location.reload();
			}
		});

		
	});
	
	$( "body" ).on( "click", ".sidebar-toggle", function(e){
		var display = $(".logo-lg").css("display");
		var url = baseUrl + "/panel/salvasidebar/";
		
		var tipo = display == "none" ? 2 : 1;
		
		$.ajaxQueue({
			url: url + tipo,
			success: function(data)
			{
				
			}
		});
	});
	
	//events binded to alert notices
	$( "div[rel='hidden_alert_notice']" ).each(function(){
		
		var t_name = $(this).text();
		
		var input = $("[name='"+t_name+"']");
		
		if (input.length > 0)
		{
			if (input.attr("type") == "checkbox" || input.attr("type") == "radio" )
			{
				input.parent().css("border",inputFieldErrorBorderStyle);
			}
			else
			{
				input.css("border",inputFieldErrorBorderStyle);
			}
		}
		
	});
	
	//events binded to the checkbox for bulk selection
	$("body").on("click", ".bulk_select_checkbox", function(e){
		
		var bulk_select_class = $(this).attr("data-class");
		
		if ($(this).is(":checked"))
		{
			$("." + bulk_select_class).prop('checked', true);
		}
		else
		{
			$("." + bulk_select_class).prop('checked', false);
		}
		
	});
	
});

 (function( $ ) {
	$.widget( "custom.combobox", {
		_create: function() {
			this.wrapper = $( "<span>" )
			.addClass( "custom-combobox" )
			.insertAfter( this.element );
			this.element.hide();
			this._createAutocomplete();
			this._createShowAllButton();
		},
		_createAutocomplete: function() {
			var selected = this.element.children( ":selected" ),
			value = selected.val() ? selected.text() : "";
			this.input = $( "<input>" )
			.appendTo( this.wrapper )
			.val( value )
			.attr( "title", "" )
			.addClass( "form-control custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
			.autocomplete({
				delay: 0,
				minLength: 0,
				source: $.proxy( this, "_source" )
			})
			.tooltip({
				tooltipClass: "ui-state-highlight"
			});
			this._on( this.input, {
				autocompleteselect: function( event, ui ) {
					ui.item.option.selected = true;
					this._trigger( "select", event, {
						item: ui.item.option
					});
				},
				autocompletechange: "_removeIfInvalid"
			});
		},
		_createShowAllButton: function() {
			var input = this.input,
			wasOpen = false;
			$( "<a>" )
			.attr( "tabIndex", -1 )
			.attr( "title", "Mostra tutti" )
			.html("<img src='"+baseUrlSrc+"/Public/Img/ordina_decrescente.jpg' />")
			.tooltip()
			.appendTo( this.wrapper )
			.button({
				icons: {
					primary: "ui-icon-triangle-1-s"
				},
				text: false
			})
			.removeClass( "ui-corner-all" )
			.addClass( "custom-combobox-toggle ui-corner-right" )
			.mousedown(function() {
				wasOpen = input.autocomplete( "widget" ).is( ":visible" );
			})
			.click(function() {
				input.focus();
				// Close if already visible
				if ( wasOpen ) {
					return;
				}
				// Pass empty string as value to search for, displaying all results
				input.autocomplete( "search", "" );
			});
		},
		_source: function( request, response ) {
			var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
			response( this.element.children( "option" ).map(function() {
				var text = $( this ).text();
				if ( this.value && ( !request.term || matcher.test(text) ) )
				return {
					label: text,
					value: text,
					option: this
				};
			}) );
		},
		_removeIfInvalid: function( event, ui ) {
			// Selected an item, nothing to do
			if ( ui.item ) {
				return;
			}
			// Search for a match (case-insensitive)
			var value = this.input.val(),
			valueLowerCase = value.toLowerCase(),
			valid = false;
			this.element.children( "option" ).each(function() {
				if ( $( this ).text().toLowerCase() === valueLowerCase ) {
					this.selected = valid = true;
					return false;
				}
			});
			// Found a match, nothing to do
			if ( valid ) {
				return;
			}
			// Remove invalid value
			this.input
			.val( "" )
			.attr( "title", value + " didn't match any item" )
			.tooltip( "open" );
			this.element.val( "" );
			this._delay(function() {
				this.input.tooltip( "close" ).attr( "title", "" );
			}, 2500 );
			this.input.data( "ui-autocomplete" ).term = "";
		},
		_destroy: function() {
			this.wrapper.remove();
			this.element.show();
		}
	});
})( jQuery );
$(function() {
	$( "#combobox" ).combobox();
	$( "#combobox1" ).combobox();
	$( "#combobox2" ).combobox();
	$( "#toggle" ).click(function() {
		$( "#combobox" ).toggle();
		$( "#combobox1" ).combobox();
		$( "#combobox2" ).combobox();
	});
});
