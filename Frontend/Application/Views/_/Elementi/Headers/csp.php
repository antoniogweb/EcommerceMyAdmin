<?php

if (getIp() != "127.0.0.1")
{
	header_remove('X-Powered-By');

	$csp = [
		"default-src 'self'",
		"base-uri 'self'",
		"frame-ancestors 'self'",
		"object-src 'none'",

		// Script JS
		"script-src 'self' 'unsafe-inline' https://www.googletagmanager.com https://tagmanager.google.com https://www.google-analytics.com https://*.google-analytics.com https://region1.google-analytics.com https://maps.googleapis.com https://maps.gstatic.com https://www.google.com https://www.gstatic.com https://googleads.g.doubleclick.net https://connect.facebook.net https://acdn.adnxs.com https://ib.adnxs.com https://www.google.it https://www.googleadservices.com https://stats.g.doubleclick.net",

		// XHR / fetch / beacon
		"connect-src 'self' https://www.googletagmanager.com https://tagmanager.google.com https://www.google-analytics.com https://*.google-analytics.com https://region1.google-analytics.com https://maps.googleapis.com https://maps.gstatic.com https://www.google.com https://www.gstatic.com https://googleads.g.doubleclick.net https://connect.facebook.net https://acdn.adnxs.com https://ib.adnxs.com https://www.google.it https://www.facebook.com https://www.youtube.com https://*.youtube.com https://www.googleadservices.com https://stats.g.doubleclick.net",

		// Immagini / pixel / thumbnail
		"img-src 'self' data: blob: https:",

		// CSS
		"style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://tagmanager.google.com",

		// Font
		"font-src 'self' data: https://fonts.gstatic.com",

		// Iframe
		"frame-src 'self' https://www.googletagmanager.com https://tagmanager.google.com https://www.google.com https://www.recaptcha.net https://googleads.g.doubleclick.net https://connect.facebook.net https://acdn.adnxs.com https://ib.adnxs.com https://www.google.it https://www.facebook.com https://www.youtube.com https://youtube.com https://*.youtube.com https://www.youtube-nocookie.com https://*.youtube-nocookie.com",

		"child-src 'self' https://www.youtube.com https://youtube.com https://*.youtube.com https://www.youtube-nocookie.com https://*.youtube-nocookie.com",

		"worker-src 'self' blob:",
		"upgrade-insecure-requests"
	];
	
	if (v("carica_header_csp_report_only"))
		header("Content-Security-Policy-Report-Only: " . implode('; ', $csp));
	else
		header("Content-Security-Policy: " . implode('; ', $csp));
}