<?php if (!defined('EG')) die('Direct access not allowed!');
include(tpf(ElementitemaModel::p("RESOCONTO_MAIL","", array(
	"titolo"	=>	"Mail il resoconto dell'acquisto",
	"percorso"	=>	"Elementi/Ordini/Resoconto/Email",
)))); 
