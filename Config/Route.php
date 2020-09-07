<?php 

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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
		'menusec,main',
		'menusec,form',
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
		'ordini,main',
		'ordini,form',
		'ordini,vedi',
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
		'caratteristichevalori,form',
		
		'categorie,main',
		'categorie,form',
		'categorie,meta',
		'categorie,gruppi',
		'categorie,classisconto',
		'categorie,contenuti',
		'categorie,ordinacontenuti',
		
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
		
		'testi,main',
		'testi,form',
		
		'traduzioni,main',
		'traduzioni,form',
		'traduzioni,aggiorna',
		'traduzioni,elimina',
		
		'scaglioni,form',
		
		'classisconto,main',
		'classisconto,form',
		
		'spedizioni,form',
		
		'corrieri,main',
		'corrieri,form',
		'corrieri,prezzi',
		
		'corrierispese,form',
		'impostazioni,form',
		
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
		'slide,layer',
		'slide,ordinalayer',
		
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
		'team,layer',
		
// 		'slidesotto,main',
// 		'slidesotto,aggiungicategoria',
// 		'slidesotto,form',
// 		'slidesotto,immagini',
// 		'slidesotto,eliminacategoria',
// 		'slidesotto,correlati',
// 		'slidesotto,attributi',
// 		'slidesotto,caratteristiche',
// 		'slidesotto,move',
// 		'slidesotto,pubblica',
// 		'slidesotto,inevidenza',
// 		'slidesotto,updatevalue',
// 		'slidesotto,meta',
// 		'slidesotto,ordina',
		
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
		
		'layer,form',
		'layer,thumb',
		
		'blogcat,main',
		'blogcat,form',
		'blogcat,meta',
		'blogcat,gruppi',
		
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
		
		'downloadcat,main',
		'downloadcat,form',
		'downloadcat,meta',
		'downloadcat,gruppi',
		
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
		
		'marchi,main',
		'marchi,form',
		
		'combinazioni,main',
		'combinazioni,form',
		'combinazioni,salva',
		
		'tipicontenuto,main',
		'tipicontenuto,form',
		
		'cron,migrazioni',
		
		'documenti,form',
		'documenti,documento',
		'documenti,thumb',
		
		'pageslink,form',
		
		'import,prodotti',
		'import,utenti',
		
		'ruoli,main',
		'ruoli,form',
		
		'tipidocumento,main',
		'tipidocumento,form',
		
		'personalizzazioni,main',
		'personalizzazioni,form',
		
// 		'regusers,ordina',
	);
	
	//it can be 'yes' or 'no'
	//set $rewrite to 'yes' if you want that EasyGiant rewrites the URLs according to what specified in $map
	public static $rewrite = 'no';
	
	//define the urls of your website
	//you have to set $rewrite to 'yes'
	public static $map = array();

}
