$(function(){
	menu();
});

function menu(){
	$('#main-nav').hcOffcanvasNav({
		//disableAt: 960,
		customToggle: $('.toggle'),
		navTitle: '',
		levelTitles: true,
		levelTitleAsBack: true,
		position: 'right',
		pushContent: '.uk-offcanvas-content',
		width: 230
	});
}
