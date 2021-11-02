<?php 

// All EasyGiant code is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// See COPYRIGHT.txt and LICENSE.txt.

if (!defined('EG')) die('Direct access not allowed!');

class Route
{

	//controller,action couples that can be reached by the browser
	//set 'all' if you want that all the controller,action couples can be reached by the browser
	public static $allowed = array(
		'home,index',
		'home,settacookie',
		'home,xmlprodotti',
		'news,index',
		'news,dettaglio',
		'contenuti,index',
		'contenuti,search',
		'contenuti,promozione',
		'contenuti,comb',
		'contenuti,notfound',
		'contenuti,nonpermesso',
		'contenuti,sitemap',
		'contenuti,robots',
		'contenuti,jsoncategorie',
		'contenuti,jsoncategoriefiglie',
		'contenuti,processaschedulazione',
		'contenuti,documento',
		'contenuti,accettacookie',
		'cart,index',
		'cart,ajax',
		'cart,add',
		'cart,delete',
		'cart,update',
		'wishlist,index',
		'wishlist,ajax',
		'wishlist,add',
		'wishlist,delete',
		'ordini,index',
		'ordini,summary',
		'ordini,ipn',
		'ordini,ipncarta',
		'ordini,ritornodapaypal',
		'ordini,ritornodacarta',
		'ordini,totale',
		'ordini,corrieri',
		'ordini,scaricafattura',
// 		'ordini,simulapaypal',
		'thumb,contenuto',
		'thumb,dettaglio',
		'thumb,carrello',
		'thumb,tooltip',
		'thumb,carrelloajax',
		'thumb,news',
		'thumb,dettaglionews',
		'thumb,slidethumb',
		'thumb,slide',
		'thumb,slidemobile',
		'thumb,slidelayer',
		'thumb,slidesotto',
		'thumb,slidesottothumb',
		'thumb,layer',
		'thumb,home',
		'thumb,categoria',
		'thumb,categoria2x',
		'thumb,sfondocategoria',
		'thumb,categoriamenu',
		'thumb,tag',
		'thumb,tagbig',
		'thumb,famiglia',
		'thumb,famigliabig',
		'thumb,dettaglioapp',
		'thumb,dettagliobig',
		'thumb,dettagliobigapp',
		'thumb,blog',
		'thumb,blogdetail',
		'thumb,blogfirst',
		'thumb,widget',
		'thumb,widget2x',
		'thumb,valoreattributo',
		'thumb,testimonial',
		'thumb,team',
		'thumb,gallery',
		'thumb,modale',
		'thumb,modalepiccola',
		'regusers,login',
		'regusers,logout',
		'regusers,forgot',
		'regusers,change',
		'regusers,password',
		'regusers,modify',
		'regusers,add',
		'regusers,notice',
		'regusers,indirizzo',
		'regusers,spedizione',
		'regusers,infoaccount',
		'regusers,impostaspedizioneperapp',
		'regusers,conferma',
		'riservata,index',
		'riservata,ordini',
		'riservata,indirizzi',
		'riservata,privacy',
// 		'riservata,cancellaaccount',
	);
	
	//it can be 'yes' or 'no'
	//set $rewrite to 'yes' if you want that EasyGiant rewrites the URLs according to what specified in $map
	public static $rewrite = 'yes';
	
	//define the urls of your website
	//you have to set $rewrite to 'yes'
	public static $map = array(
		'area-riservata'			=>	'riservata/index',
		'ordini-effettuati'			=>	'riservata/ordini',
		'riservata/indirizzi'		=>	'riservata/indirizzi',
		'riservata/privacy'			=>	'riservata/privacy',
// 		'riservata/cancellaaccount'	=>	'riservata/cancellaaccount',
		'regusers/login'			=>	'regusers/login',
		'esci'						=>	'regusers/logout',
		'password-dimenticata'		=>	'regusers/forgot',
		'reimposta-password/(.*)'	=>	'regusers/change/${1}',
		'modifica-password'			=>	'regusers/password',
		'modifica-account'			=>	'regusers/modify',
		'crea-account'				=>	'regusers/add',
		'conferma-account/(.*)'		=>	'regusers/conferma/${1}',
		'avvisi'					=>	'regusers/notice',
		'gestisci-spedizione/(.*)'	=>	'regusers/spedizione/${1}',
		'indirizzo-di-spedizione/(.*)'	=>	'regusers/indirizzo/${1}',
		'info-account.html'			=>	'regusers/infoaccount',
		'imposta-spedizione-per-app/(.*)'	=>	'regusers/impostaspedizioneperapp/${1}',
		'grazie-per-l-acquisto'		=>	'ordini/ritornodapaypal',
		'grazie-per-l-acquisto-carta'	=>	'ordini/ritornodacarta',
		'ordini/scaricafattura/(.*)'=>	'ordini/scaricafattura/${1}',
// 		'ordini/simulapaypal'		=>	'ordini/simulapaypal',
		'notifica-pagamento'		=>	'ordini/ipn',
		'notifica-pagamento-carta'	=>	'ordini/ipncarta',
		'resoconto-acquisto/(.*)'	=>	'ordini/summary/${1}',
		'checkout'					=>	'ordini/index',
		'ordini/totale'				=>	'ordini/totale',
		'ordini/corrieri/(.*)'		=>	'ordini/corrieri/${1}',
		'thumb/carrelloajax/(.*)'	=>	'thumb/carrelloajax/${1}',
		'thumb/tooltip/(.*)'		=>	'thumb/tooltip/${1}',
		'thumb/carrello/(.*)'		=>	'thumb/carrello/${1}',
		'thumb/contenuto/(.*)'		=>	'thumb/contenuto/${1}',
		'thumb/dettaglio/(.*)'		=>	'thumb/dettaglio/${1}',
		'thumb/news/(.*)'			=>	'thumb/news/${1}',
		'thumb/dettaglionews/(.*)'	=>	'thumb/dettaglionews/${1}',
		'thumb/slidethumb/(.*)'		=>	'thumb/slidethumb/${1}',
		'thumb/slide/(.*)'			=>	'thumb/slide/${1}',
		'thumb/slidemobile/(.*)'	=>	'thumb/slidemobile/${1}',
		'thumb/slidelayer/(.*)'		=>	'thumb/slidelayer/${1}',
		'thumb/slidesotto/(.*)'		=>	'thumb/slidesotto/${1}',
		'thumb/slidesottothumb/(.*)'=>	'thumb/slidesottothumb/${1}',
		'thumb/layer/(.*)'			=>	'thumb/layer/${1}',
		'thumb/home/(.*)'			=>	'thumb/home/${1}',
		'thumb/categoriamenu/(.*)'		=>	'thumb/categoriamenu/${1}',
		'thumb/categoria/(.*)'		=>	'thumb/categoria/${1}',
		'thumb/categoria2x/(.*)'	=>	'thumb/categoria2x/${1}',
		'thumb/sfondocategoria/(.*)'=>	'thumb/sfondocategoria/${1}',
		'thumb/famiglia/(.*)'		=>	'thumb/famiglia/${1}',
		'thumb/famigliabig/(.*)'	=>	'thumb/famigliabig/${1}',
		'thumb/tag/(.*)'			=>	'thumb/tag/${1}',
		'thumb/tagbig/(.*)'			=>	'thumb/tagbig/${1}',
		'thumb/dettaglioapp/(.*)'	=>	'thumb/dettaglioapp/${1}',
		'thumb/dettagliobig/(.*)'	=>	'thumb/dettagliobig/${1}',
		'thumb/dettagliobigapp/(.*)'	=>	'thumb/dettagliobigapp/${1}',
		'thumb/blog/(.*)'			=>	'thumb/blog/${1}',
		'thumb/blogdetail/(.*)'		=>	'thumb/blogdetail/${1}',
		'thumb/blogfirst/(.*)'		=>	'thumb/blogfirst/${1}',
		'thumb/widget/(.*)'			=>	'thumb/widget/${1}',
		'thumb/widget2x/(.*)'		=>	'thumb/widget2x/${1}',
		'thumb/valoreattributo/(.*)'=>	'thumb/valoreattributo/${1}',
		'thumb/testimonial/(.*)'	=>	'thumb/testimonial/${1}',
		'thumb/team/(.*)'			=>	'thumb/team/${1}',
		'thumb/gallery/(.*)'		=>	'thumb/gallery/${1}',
		'thumb/modale/(.*)'			=>	'thumb/modale/${1}',
		'thumb/modalepiccola/(.*)'	=>	'thumb/modalepiccola/${1}',
		'home/index'				=>	'home/index',
		'home/settacookie'			=>	'home/settacookie',
		'home/xmlprodotti'			=>	'home/xmlprodotti',
		'contenuti/notfound'		=>	'contenuti/notfound',
		'contenuti/nonpermesso'		=>	'contenuti/nonpermesso',
		'accept-cookies'			=>	'contenuti/accettacookie',
		'carrello/aggiorna'			=>	'cart/update',
		'carrello/elimina/(.*)'		=>	'cart/delete/${1}',
		'carrello/aggiungi/(.*)'	=>	'cart/add/${1}',
		'carrello/semplificato'		=>	'cart/ajax',
		'carrello/vedi'				=>	'cart/index/full',
		'carrello/partial'			=>	'cart/index/partial',
		'wishlist/elimina/(.*)'		=>	'wishlist/delete/${1}',
		'wishlist/aggiungi/(.*)'	=>	'wishlist/add/${1}',
		'wishlist/semplificato'		=>	'wishlist/ajax',
		'wishlist/vedi'				=>	'wishlist/index/full',
		'wishlist/partial'			=>	'wishlist/index/partial',
		'risultati-ricerca'			=>	'contenuti/search',
		'prodotti-in-promozione.html'	=>	'contenuti/promozione',
		'controlla/combinazione'	=>	'contenuti/comb',
		'dettaglio-notizia/(.*)' 	=>	'news/dettaglio/${1}',
		'archivio-news'				=>	'news/index',
		'sitemap.xml'				=>	'contenuti/sitemap',
		'robots.txt'				=>	'contenuti/robots',
		'contenuti/jsoncategorie/(.*)'	=>	'contenuti/jsoncategorie/${1}',
		'contenuti/jsoncategoriefiglie/(.*)'	=>	'contenuti/jsoncategoriefiglie/${1}',
		'contenuti/documento/(.*)'	=>	'contenuti/documento/${1}',
		'contenuti/processaschedulazione/(.*)'	=>	'contenuti/processaschedulazione/${1}',
		'contenuti/documento/(.*)'	=>	'contenuti/documento/${1}',
		
// 		'(.*)/(.*)/(.*)/(.*)/(.*)' 	=>	'contenuti/index/${1}/${2}/${3}/${4}/${5}',
// 		'(.*)/(.*)/(.*)/(.*)' 		=>	'contenuti/index/${1}/${2}/${3}/${4}',
// 		'(.*)/(.*)/(.*)' 			=>	'contenuti/index/${1}/${2}/${3}',
// 		'(.*)/(.*)' 				=>	'contenuti/index/${1}/${2}',
		'([^.]*).html'				=>	'contenuti/index/${1}',
		'(.*)'						=>	'contenuti/notfound/${1}',
	);
}
