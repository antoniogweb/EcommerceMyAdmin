<?php
if (isset($filtri))
	echo $filtri;
?>

<?php
$fornitori = array(0 => gtext("Seleziona un fornitore")) + FornitoriModel::g()->clear()->select("id_fornitore,ragione_sociale")->orderBy("ragione_sociale")->toList("id_fornitore", "ragione_sociale")->send();

// echo Html_Form::select("id_fornitore", "", $fornitori, "form-control", null, "yes");
?>
