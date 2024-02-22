<?php if (!defined('EG')) die('Direct access not allowed!');?>
<?php if (v("codice_gtm") || v("codice_gtm_analytics")) { ?>
<script>
	// Define dataLayer and the gtag function.
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}

	// Set default consent to 'denied' as a placeholder
	// Determine actual values based on your own requirements
	gtag('consent', 'default', {
		'ad_storage': 'denied',
		'ad_user_data': 'denied',
		'ad_personalization': 'denied',
		'analytics_storage': 'denied'
	});
</script>
<?php } ?>

<?php if (v("codice_gtm")) {
	echo htmlentitydecode(v("codice_gtm"));
}

include(tpf("/Elementi/Analytics/analytics".v("versione_google_analytics").".php"));
