<?php if (!defined('EG')) die('Direct access not allowed!');

if (v("salva_satistiche_visualizzazione_pagina_su_file"))
{
	if (isset($nomePaginaPerTracking) && $nomePaginaPerTracking && isset($idPaginaPerTracking))
		PagesstatsModel::salvaSuFile($idPaginaPerTracking);
}
