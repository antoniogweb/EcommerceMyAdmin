<?php 

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of EcommerceMyAdmin
//
// EcommerceMyAdmin is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// EcommerceMyAdmin is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with EcommerceMyAdmin.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');

// Estensioni per pagine e categorie
if (!defined('ESTENSIONI_URL'))
	define ('ESTENSIONI_URL','.html');

class Route
{
	//controller,action couples that can be reached by the browser
	//set 'all' if you want that all the controller,action couples can be reached by the browser
	public static $allowed = array(
		'home,index',
		'home,settacookie',
		'home,xmlprodotti',
// 		'news,index',
// 		'news,dettaglio',
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
		'contenuti,confermacontatto',
		'contenuti,listaregalo',
		'cart,index',
		'cart,ajax',
		'cart,add',
		'cart,delete',
		'cart,update',
		'cart,eliminacookielista',
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
		'ordini,modifica',
		'ordini,couponattivo',
		'ordini,annullapagamento',
		'ordini,checklogin',
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
		'thumb,sfondocategoria2',
		'thumb,sfondopagina',
		'thumb,categoriamenu',
		'thumb,tag',
		'thumb,tagbig',
		'thumb,famiglia',
		'thumb,famigliabig',
		'thumb,dettaglioapp',
		'thumb,dettagliobig',
		'thumb,dettagliofeed',
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
		'thumb,gallerybig',
		'thumb,modale',
		'thumb,modalepiccola',
		'thumb,servizio',
		'thumb,servizio2x',
		'thumb,tipodocumento',
		'thumb,tipodocumento2x',
		'thumb,progetto',
		'thumb,progettodetail',
		'thumb,socidetail',
		'thumb,ricetta',
		'thumb,storia',
		'thumb,colore',
		'thumb,listaregalo',
		'thumb,pagamento',
		'thumb,evento',
		'thumb,eventodetail',
		'regusers,login',
		'regusers,loginapp',
		'regusers,deleteaccountdaapp',
		'regusers,logout',
		'regusers,forgot',
		'regusers,change',
		'regusers,password',
		'regusers,modify',
		'regusers,add',
		'regusers,notice',
		'regusers,indirizzo',
		'regusers,spedizione',
// 		'regusers,infoaccount',
		'regusers,impostaspedizioneperapp',
		'regusers,conferma',
		'regusers,reinviamailconferma',
		'regusers,richieditokenconferma',
		'riservata,index',
		'riservata,ordini',
		'riservata,indirizzi',
		'riservata,privacy',
		'riservata,feedback',
		'listeregalo,index',
		'listeregalo,gestisci',
		'listeregalo,modifica',
		'listeregalo,aggiungi',
		'listeregalo,elencoprodotti',
		'listeregalo,elimina',
		'listeregalo,aggiornaprodotti',
		'listeregalo,invialink',
		'listeregalo,elencolink',
		'listeregalo,invianuovamentelink',
		'feed,prodotti',
		'motoriricerca,cerca',
		
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
		'riservata/feedback'		=>	'riservata/feedback',
// 		'riservata/cancellaaccount'	=>	'riservata/cancellaaccount',
		'liste-regalo/?'			=>	'listeregalo/index',
		'listeregalo/gestisci/([0-9]{1,9})'=>	'listeregalo/gestisci/${1}',
		'listeregalo/modifica/([0-9]{1,9})'=>	'listeregalo/modifica/${1}',
		'listeregalo/elimina/([0-9]{1,9})'=>	'listeregalo/elimina/${1}',
		'listeregalo/elencoprodotti/(.*)'=>	'listeregalo/elencoprodotti/${1}',
		'listeregalo/aggiungi/(.*)'=>	'listeregalo/aggiungi/${1}',
		'listeregalo/aggiornaprodotti'=>	'listeregalo/aggiornaprodotti',
		'listeregalo/invialink/(.*)'=>	'listeregalo/invialink/${1}',
		'listeregalo/elencolink/(.*)'=>	'listeregalo/elencolink/${1}',
		'listeregalo/invianuovamentelink/(.*)'=>	'listeregalo/invianuovamentelink/${1}',
		'regusers/login'			=>	'regusers/login',
		'regusers/loginapp/(.*)'	=>	'regusers/loginapp/${1}',
		'regusers/deleteaccountdaapp/(.*)'	=>	'regusers/deleteaccountdaapp/${1}',
		'esci'						=>	'regusers/logout',
		'password-dimenticata'		=>	'regusers/forgot',
		'account-verification'		=>	'regusers/richieditokenconferma',
		'reimposta-password/(.*)'	=>	'regusers/change/${1}',
		'modifica-password'			=>	'regusers/password',
		'modifica-account'			=>	'regusers/modify',
		'crea-account'				=>	'regusers/add',
		'conferma-account/(.*)'		=>	'regusers/conferma/${1}',
		'send-confirmation'			=>	'regusers/reinviamailconferma',
		'avvisi'					=>	'regusers/notice',
		'gestisci-spedizione/(.*)'	=>	'regusers/spedizione/${1}',
		'indirizzo-di-spedizione/(.*)'	=>	'regusers/indirizzo/${1}',
// 		'info-account.html'			=>	'regusers/infoaccount',
		'imposta-spedizione-per-app/(.*)'	=>	'regusers/impostaspedizioneperapp/${1}',
		'grazie-per-l-acquisto'		=>	'ordini/ritornodapaypal',
		'grazie-per-l-acquisto-carta'	=>	'ordini/ritornodacarta',
		'ordini/scaricafattura/(.*)'=>	'ordini/scaricafattura/${1}',
		'ordini/modifica/(.*)'		=>	'ordini/modifica/${1}',
		'ordini/couponattivo'		=>	'ordini/couponattivo',
		'ordini/annullapagamento/(.*)'	=>	'ordini/annullapagamento/${1}',
// 		'ordini/simulapaypal'		=>	'ordini/simulapaypal',
		'notifica-pagamento'		=>	'ordini/ipn',
		'notifica-pagamento-carta'	=>	'ordini/ipncarta',
		'resoconto-acquisto/(.*)'	=>	'ordini/summary/${1}',
		'checkout'					=>	'ordini/index',
		'autenticazione'			=>	'ordini/checklogin',
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
		'thumb/servizio/(.*)'		=>	'thumb/servizio/${1}',
		'thumb/servizio2x/(.*)'		=>	'thumb/servizio2x/${1}',
		'thumb/tipodocumento/(.*)'	=>	'thumb/tipodocumento/${1}',
		'thumb/tipodocumento2x/(.*)'=>	'thumb/tipodocumento2x/${1}',
		'thumb/sfondocategoria/(.*)'=>	'thumb/sfondocategoria/${1}',
		'thumb/sfondocategoria2/(.*)'=>	'thumb/sfondocategoria2/${1}',
		'thumb/sfondopagina/(.*)'	=>	'thumb/sfondopagina/${1}',
		'thumb/famiglia/(.*)'		=>	'thumb/famiglia/${1}',
		'thumb/famigliabig/(.*)'	=>	'thumb/famigliabig/${1}',
		'thumb/tag/(.*)'			=>	'thumb/tag/${1}',
		'thumb/tagbig/(.*)'			=>	'thumb/tagbig/${1}',
		'thumb/dettaglioapp/(.*)'	=>	'thumb/dettaglioapp/${1}',
		'thumb/dettagliobig/(.*)'	=>	'thumb/dettagliobig/${1}',
		'thumb/dettagliofeed/(.*)'	=>	'thumb/dettagliofeed/${1}',
		'thumb/dettagliobigapp/(.*)'=>	'thumb/dettagliobigapp/${1}',
		'thumb/blog/(.*)'			=>	'thumb/blog/${1}',
		'thumb/blogdetail/(.*)'		=>	'thumb/blogdetail/${1}',
		'thumb/blogfirst/(.*)'		=>	'thumb/blogfirst/${1}',
		'thumb/widget/(.*)'			=>	'thumb/widget/${1}',
		'thumb/widget2x/(.*)'		=>	'thumb/widget2x/${1}',
		'thumb/valoreattributo/(.*)'=>	'thumb/valoreattributo/${1}',
		'thumb/testimonial/(.*)'	=>	'thumb/testimonial/${1}',
		'thumb/team/(.*)'			=>	'thumb/team/${1}',
		'thumb/gallery/(.*)'		=>	'thumb/gallery/${1}',
		'thumb/gallerybig/(.*)'		=>	'thumb/gallerybig/${1}',
		'thumb/modale/(.*)'			=>	'thumb/modale/${1}',
		'thumb/modalepiccola/(.*)'	=>	'thumb/modalepiccola/${1}',
		'thumb/progetto/(.*)'		=>	'thumb/progetto/${1}',
		'thumb/progettodetail/(.*)'	=>	'thumb/progettodetail/${1}',
		'thumb/socidetail/(.*)'		=>	'thumb/socidetail/${1}',
		'thumb/ricetta/(.*)'		=>	'thumb/ricetta/${1}',
		'thumb/storia/(.*)'			=>	'thumb/storia/${1}',
		'thumb/colore/(.*)'			=>	'thumb/colore/${1}',
		'thumb/listaregalo/(.*)'	=>	'thumb/listaregalo/${1}',
		'thumb/pagamento/(.*)'		=>	'thumb/pagamento/${1}',
		'thumb/evento/(.*)'			=>	'thumb/evento/${1}',
		'thumb/eventodetail/(.*)'		=>	'thumb/eventodetail/${1}',
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
		'carrello/eliminacookielista'	=>	'cart/eliminacookielista',
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
		'contenuti/listaregalo/?'	=>	'contenuti/listaregalo',
		'lista-regalo/(.*)\/(.*)\.html'=>	'contenuti/listaregalo/${1}/${2}',
		'conferma-contatto/(.*)'	=>	'contenuti/confermacontatto/${1}',
		'feed/prodotti/(.*)'		=>	'feed/prodotti/${1}',
		'motoriricerca/cerca/(.*)'	=>	'motoriricerca/cerca/${1}',
		
// 		'(.*)/(.*)/(.*)/(.*)/(.*)' 	=>	'contenuti/index/${1}/${2}/${3}/${4}/${5}',
// 		'(.*)/(.*)/(.*)/(.*)' 		=>	'contenuti/index/${1}/${2}/${3}/${4}',
// 		'(.*)/(.*)/(.*)' 			=>	'contenuti/index/${1}/${2}/${3}',
// 		'(.*)/(.*)' 				=>	'contenuti/index/${1}/${2}',
		'([^.]*)'.ESTENSIONI_URL	=>	'contenuti/index/${1}',
		'(.*)'						=>	'contenuti/notfound/${1}',
	);
}
