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

class Route
{

	//controller,action couples that can be reached by the browser
	//set 'all' if you want that all the controller,action couples can be reached by the browser
	public static $allowed = array(
		'panel,main',
		'panel,salvasidebar',
		'users,login',
		'users,logout',
		'users,main',
		'users,form',
		'users,gruppi',
		'categories,main',
		'categories,form',
		'categories,meta',
		'categories,gruppi',
		'categories,contenuti',
		'categories,ordinacontenuti',
		
		'pages,main',
		'pages,aggiungicategoria',
		'pages,form',
		'pages,immagini',
		'pages,eliminacategoria',
		'pages,correlati',
		'pages,attributi',
		'pages,caratteristiche',
		'pages,move',
		'pages,pubblica',
		'pages,meta',
		'pages,inevidenza',
		'pages,updatevalue',
		'pages,ordina',
		'pages,scaglioni',
		'pages,contenuti',
		'pages,ordinacontenuti',
		'pages,esportaprodotti',
		'pages,ordinadocumenti',
		
		'password,form',
		'menu,main',
		'menu,form',
		'menu,ordina',
// 		'menusec,main',
// 		'menusec,form',
		'upload,main',
		'upload,thumb',
		'thumb,contenuto',
		'thumb,mainimage',
		'thumb,immagineinlistaprodotti',
		'thumb,news',
		'immagini,view',
		'immagini,moveup',
		'immagini,movedown',
		'immagini,erase',
		'immagini,ordina',
		'immagini,rotateo',
		'immagini,form',
// 		'immagini,rotatea',
		'ordini,main',
		'ordini,form',
		'ordini,vedi',
		'ordini,integrazioni',
		'ordini,righe',
		'ordini,setstato',
		'promozioni,main',
		'promozioni,form',
		'promozioni,categorie',
		'promozioni,pagine',
		'regusers,main',
		'regusers,form',
		'regusers,gruppi',
		'regusers,spedizioni',
		'regusers,ordini',
		
		'reggroups,main',
		'reggroups,form',
		'reggroups,tipi',
		
		'regusersgroupstemp,main',
		
		'news,main',
		'news,form',
		'news,ordina',
		'news,documento',
		'news,thumb',
		'attributi,main',
		'attributi,form',
		'attributi,updatevalue',
		'attributi,valori',
		'attributi,ordinavalori',
		'attributivalori,form',
		'attributivalori,thumb',
		'fatture,main',
		'fatture,vedi',
		'fatture,crea',
		'caratteristiche,main',
		'caratteristiche,form',
		'caratteristiche,updatevalue',
		'caratteristiche,lista',
		'caratteristiche,valori',
		'caratteristiche,ordinavalori',
		'caratteristiche,ordina',
		'caratteristiche,elenco',
		
		'caratteristichevalori,main',
		'caratteristichevalori,form',
		'caratteristichevalori,thumb',
		'caratteristichevalori,elenco',
		
		'categorie,main',
		'categorie,form',
		'categorie,meta',
		'categorie,gruppi',
		'categorie,classisconto',
		'categorie,contenuti',
		'categorie,ordinacontenuti',
		'categorie,ordina',
		
		'prodotti,main',
		'prodotti,aggiungicategoria',
		'prodotti,form',
		'prodotti,immagini',
		'prodotti,eliminacategoria',
		'prodotti,correlati',
		'prodotti,accessori',
		'prodotti,attributi',
		'prodotti,caratteristiche',
		'prodotti,move',
		'prodotti,pubblica',
		'prodotti,inevidenza',
		'prodotti,updatevalue',
		'prodotti,meta',
		'prodotti,ordina',
		'prodotti,scaglioni',
		'prodotti,contenuti',
		'prodotti,ordinacontenuti',
		'prodotti,documento',
		'prodotti,documenti',
		'prodotti,ordinadocumenti',
		'prodotti,testi',
		'prodotti,personalizzazioni',
		'prodotti,ordinapersonalizzazioni',
		'prodotti,ordinacaratteristiche',
		'prodotti,tag',
		'prodotti,ordinatag',
		'prodotti,paginecorrelate',
		'prodotti,ordinacorrelate',
		'prodotti,feedback',
		'prodotti,ordinafeedback',
		'prodotti,regioni',
		'prodotti,lingue',
		'prodotti,aggiungicaratteristica',
		
		'testi,main',
		'testi,form',
		
		'traduzioni,main',
		'traduzioni,form',
		'traduzioni,aggiorna',
		'traduzioni,elimina',
		'traduzioni,carica',
		
		'scaglioni,form',
		'feedback,form',
		'feedback,approvarifiuta',
		
		'classisconto,main',
		'classisconto,form',
		
		'spedizioni,form',
		
		'corrieri,main',
		'corrieri,ordina',
		'corrieri,form',
		'corrieri,prezzi',
		
		'corrierispese,form',
		'impostazioni,form',
		'impostazioni,variabili',
		'impostazioni,svuotacache',
		'impostazioni,tema',
		'impostazioni,attivatema',
		'impostazioni,ecommerce',
		'impostazioni,google',
		
		'slide,main',
		'slide,aggiungicategoria',
		'slide,form',
		'slide,immagini',
		'slide,eliminacategoria',
		'slide,correlati',
		'slide,attributi',
		'slide,caratteristiche',
		'slide,move',
		'slide,pubblica',
		'slide,inevidenza',
		'slide,updatevalue',
		'slide,meta',
		'slide,ordina',
		'slide,testi',
		'slide,ordinacontenuti',
		
		'avvisi,main',
		'avvisi,aggiungicategoria',
		'avvisi,form',
		'avvisi,immagini',
		'avvisi,eliminacategoria',
		'avvisi,correlati',
		'avvisi,attributi',
		'avvisi,caratteristiche',
		'avvisi,move',
		'avvisi,pubblica',
		'avvisi,inevidenza',
		'avvisi,updatevalue',
		'avvisi,meta',
		'avvisi,ordina',
		
		'team,main',
		'team,aggiungicategoria',
		'team,form',
		'team,immagini',
		'team,eliminacategoria',
		'team,correlati',
		'team,attributi',
		'team,caratteristiche',
		'team,move',
		'team,pubblica',
		'team,inevidenza',
		'team,updatevalue',
		'team,meta',
		'team,ordina',
		
		'pagine,main',
		'pagine,aggiungicategoria',
		'pagine,form',
		'pagine,immagini',
		'pagine,eliminacategoria',
		'pagine,correlati',
		'pagine,attributi',
		'pagine,caratteristiche',
		'pagine,move',
		'pagine,pubblica',
		'pagine,inevidenza',
		'pagine,updatevalue',
		'pagine,meta',
		'pagine,ordina',
		'pagine,contenuti',
		'pagine,ordinacontenuti',
		'pagine,testi',
		'pagine,paginecorrelate',
		'pagine,ordinacorrelate',
		
		'layer,form',
		'layer,thumb',
		
		'blogcat,main',
		'blogcat,form',
		'blogcat,meta',
		'blogcat,gruppi',
		'blogcat,ordina',
		'blogcat,contenuti',
		
		'blog,main',
		'blog,aggiungicategoria',
		'blog,form',
		'blog,immagini',
		'blog,eliminacategoria',
		'blog,correlati',
		'blog,attributi',
		'blog,caratteristiche',
		'blog,move',
		'blog,pubblica',
		'blog,inevidenza',
		'blog,updatevalue',
		'blog,meta',
		'blog,ordina',
		'blog,contenuti',
		'blog,ordinacontenuti',
		'blog,link',
		'blog,testi',
		'blog,lingue',
		'blog,tag',
		'blog,ordinatag',
		
		'downloadcat,main',
		'downloadcat,form',
		'downloadcat,meta',
		'downloadcat,gruppi',
		'downloadcat,ordina',
		
		'download,main',
		'download,aggiungicategoria',
		'download,form',
		'download,immagini',
		'download,eliminacategoria',
		'download,correlati',
		'download,attributi',
		'download,caratteristiche',
		'download,move',
		'download,pubblica',
		'download,inevidenza',
		'download,updatevalue',
		'download,meta',
		'download,ordina',
		'download,contenuti',
		'download,ordinacontenuti',
		'download,link',
		'download,testi',
		'download,documenti',
		'download,ordinadocumenti',
		
		'referenze,main',
		'referenze,aggiungicategoria',
		'referenze,form',
		'referenze,immagini',
		'referenze,eliminacategoria',
		'referenze,correlati',
		'referenze,attributi',
		'referenze,caratteristiche',
		'referenze,move',
		'referenze,pubblica',
		'referenze,inevidenza',
		'referenze,updatevalue',
		'referenze,meta',
		'referenze,ordina',
		'referenze,contenuti',
		'referenze,ordinacontenuti',
		
		'iva,main',
		'iva,form',
		'iva,ordina',
		
		'contenutitradotti,form',
		
		'contenuti,form',
		'contenuti,thumb',
		'contenuti,gruppi',
		'contenuti,traduzione',
		
		'marchi,main',
		'marchi,form',
		'marchi,ordina',
		'marchi,meta',
		
		'combinazioni,main',
		'combinazioni,form',
		'combinazioni,salva',
		
		'tipicontenuto,main',
		'tipicontenuto,form',
		'tipicontenuto,ordina',
		
		'cron,migrazioni',
		
		'documenti,form',
		'documenti,lingue',
		'documenti,documento',
		'documenti,thumb',
		'documenti,gruppi',
		'documenti,traduzione',
		'documenti,caricamolti',
		'documenti,caricazip',
		'documenti,upload',
		
		'pageslink,form',
		
		'import,prodotti',
		'import,utenti',
		'import,news',
		'import,contenuti',
		
		'ruoli,main',
		'ruoli,form',
		
		'tipiazienda,main',
		'tipiazienda,form',
		
		'tipidocumento,main',
		'tipidocumento,form',
		'tipidocumento,estensioni',
		
		'tipidocumentoestensioni,form',
		
		'personalizzazioni,main',
		'personalizzazioni,form',
		
		'tag,main',
		'tag,form',
		'tag,ordina',
		'tag,meta',
		
		'nazioni,main',
		'nazioni,form',
		'nazioni,regioni',
		'nazioni,regusers',
// 		'nazioni,importa',
		
		'regioni,main',
		'regioni,form',
		
		'fasceprezzo,main',
		'fasceprezzo,form',
		
		'tipologiecaratteristiche,main',
		'tipologiecaratteristiche,form',
		'tipologiecaratteristiche,ordina',
		
		'faq,main',
		'faq,form',
		'faq,move',
		'faq,pubblica',
		'faq,inevidenza',
		'faq,updatevalue',
		'faq,meta',
		'faq,ordina',
		
		'testimonial,main',
		'testimonial,form',
		'testimonial,move',
		'testimonial,pubblica',
		'testimonial,inevidenza',
		'testimonial,updatevalue',
		'testimonial,ordina',
		
		'eventicat,main',
		'eventicat,form',
		'eventicat,meta',
		'eventicat,gruppi',
		'eventicat,ordina',
		
		'eventi,main',
		'eventi,form',
		'eventi,immagini',
		'eventi,correlati',
		'eventi,move',
		'eventi,pubblica',
		'eventi,inevidenza',
		'eventi,updatevalue',
		'eventi,meta',
		'eventi,ordina',
		'eventi,contenuti',
		'eventi,ordinacontenuti',
		'eventi,testi',
		
		'gallery,main',
		'gallery,form',
		'gallery,move',
		'gallery,pubblica',
		'gallery,inevidenza',
		'gallery,updatevalue',
		'gallery,ordina',
		'gallery,meta',
		'gallery,immagini',
		
		'icone,main',
		'icone,form',
		'icone,move',
		'icone,pubblica',
		'icone,inevidenza',
		'icone,updatevalue',
		'icone,ordina',
		
		'help,main',
		'help,form',
		'help,ordina',
		'help,elementi',
		'help,ordinaelementi',
		'help,mostranascondi',
		'help,pdf',
		
		'helpitem,form',
		
		'pagamenti,main',
		'pagamenti,form',
		'pagamenti,ordina',
		
		'captcha,main',
		'captcha,form',
		
		'cart,main',
		'pagesstats,main',
		'contatti,main',
		'righe,main',
		
		'modali,main',
		'modali,form',
		'modali,move',
		'modali,pubblica',
		'modali,inevidenza',
		'modali,updatevalue',
		'modali,ordina',
		
		'templateemail,main',
		'templateemail,form',
		'templateemail,move',
		'templateemail,pubblica',
		'templateemail,inevidenza',
		'templateemail,updatevalue',
		'templateemail,ordina',
		
		'eventiretargeting,main',
		'eventiretargeting,form',
		'eventiretargeting,ordina',
		'eventiretargeting,invii',
		
		'sitemap,main',
		'sitemap,form',
		'sitemap,ordina',
		
		'menuadmin,main',
		'menuadmin,form',
// 		'menuadmin,ordina',

		'elementitema,main',
		'elementitema,form',
		'elementitema,importa',
		'elementitema,resetta',
		'elementitema,esporta',
		'elementitema,crea',
		'elementitema,elencotemi',
		
		'integrazioni,main',
		'integrazioni,form',
		'integrazioni,invia',
		
		'opzioni,main',
		'opzioni,importacategoriegoogle',
		
		'applicazioni,main',
		'applicazioni,form',
		'applicazioni,migrazioni',
		'applicazioni,variabili',
		
		'servizi,main',
		'servizi,form',
		'servizi,move',
		'servizi,pubblica',
		'servizi,inevidenza',
		'servizi,updatevalue',
		'servizi,ordina',
		
		'integrazioninewsletter,main',
		'integrazioninewsletter,form',
		
		'sedi,main',
		'sedi,form',
		'sedi,move',
		'sedi,pubblica',
		'sedi,inevidenza',
		'sedi,updatevalue',
		'sedi,ordina',
		'sedi,paginecorrelate',
		'sedi,ordinacorrelate',
		
		'sedicat,main',
		'sedicat,form',
		'sedicat,meta',
		'sedicat,gruppi',
		'sedicat,ordina',
		'sedicat,contenuti',
		
		'soci,main',
		'soci,aggiungicategoria',
		'soci,form',
		'soci,immagini',
		'soci,eliminacategoria',
		'soci,correlati',
		'soci,attributi',
		'soci,caratteristiche',
		'soci,move',
		'soci,pubblica',
		'soci,inevidenza',
		'soci,updatevalue',
		'soci,meta',
		'soci,ordina',
		'soci,contenuti',
		'soci,ordinacontenuti',
		'soci,testi',
		'soci,aggiungicaratteristica',
		
		'progetti,main',
		'progetti,aggiungicategoria',
		'progetti,form',
		'progetti,immagini',
		'progetti,eliminacategoria',
		'progetti,correlati',
		'progetti,attributi',
		'progetti,caratteristiche',
		'progetti,move',
		'progetti,pubblica',
		'progetti,inevidenza',
		'progetti,updatevalue',
		'progetti,meta',
		'progetti,ordina',
		'progetti,contenuti',
		'progetti,ordinacontenuti',
		'progetti,testi',
		'progetti,aggiungicaratteristica',
		
		'alimenti,main',
		'alimenti,aggiungicategoria',
		'alimenti,form',
		'alimenti,immagini',
		'alimenti,eliminacategoria',
		'alimenti,correlati',
		'alimenti,attributi',
		'alimenti,caratteristiche',
		'alimenti,move',
		'alimenti,pubblica',
		'alimenti,inevidenza',
		'alimenti,updatevalue',
		'alimenti,meta',
		'alimenti,ordina',
		'alimenti,contenuti',
		'alimenti,ordinacontenuti',
		'alimenti,testi',
		'alimenti,paginecorrelate',
		'alimenti,ordinacorrelate',
		
		'alimenticat,main',
		'alimenticat,form',
		'alimenticat,meta',
		'alimenticat,gruppi',
		'alimenticat,ordina',
		'alimenticat,contenuti',
		
		'ricette,main',
		'ricette,aggiungicategoria',
		'ricette,form',
		'ricette,immagini',
		'ricette,eliminacategoria',
		'ricette,correlati',
		'ricette,attributi',
		'ricette,caratteristiche',
		'ricette,move',
		'ricette,pubblica',
		'ricette,inevidenza',
		'ricette,updatevalue',
		'ricette,meta',
		'ricette,ordina',
		'ricette,contenuti',
		'ricette,ordinacontenuti',
		'ricette,testi',
		
		'storia,main',
		'storia,aggiungicategoria',
		'storia,form',
		'storia,immagini',
		'storia,eliminacategoria',
		'storia,correlati',
		'storia,attributi',
		'storia,caratteristiche',
		'storia,move',
		'storia,pubblica',
		'storia,inevidenza',
		'storia,updatevalue',
		'storia,meta',
		'storia,ordina',
		'storia,contenuti',
		'storia,ordinacontenuti',
		'storia,testi',
		
		'redirect,main',
		'redirect,form',
		'redirect,rigenera',
	);
	
	//it can be 'yes' or 'no'
	//set $rewrite to 'yes' if you want that MvcMyLibrary rewrites the URLs according to what specified in $map
	public static $rewrite = 'no';
	
	//define the urls of your website
	//you have to set $rewrite to 'yes'
	public static $map = array();

}
