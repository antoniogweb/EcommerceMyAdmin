<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="tipo_cliente class_tipo_cliente uk-margin">
<?php
if (v("solo_utenti_privati"))
	echo Html_Form::hidden("tipo_cliente",$values['tipo_cliente'],"privato");
else
{
	$divisoreTipoCliente = User::$isPhone ? "<div class='tipo_cliente_divisore'></div>" : "";
	
	if (count($tipiClienti) > 1)
	{
		$tipoCliente = array();
		
		foreach ($tipiClienti as $codiceTipo => $titoloTipo)
		{
			$tipoCliente["<span style='margin-left:8px;'></span>".gtext($titoloTipo)."<span style='margin-right:20px;'></span>$divisoreTipoCliente"] = $codiceTipo; 
		}
		
		echo Html_Form::radio("tipo_cliente",$values['tipo_cliente'],$tipoCliente,"radio_cliente");
	}
	else
	{
		foreach ($tipiClienti as $codiceTipo => $titoloTipo)
		{
			echo Html_Form::hidden("tipo_cliente",$codiceTipo,$codiceTipo);
		}
	}
}
?>
</div> 
