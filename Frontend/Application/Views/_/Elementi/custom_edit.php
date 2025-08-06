<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (User::$adminLogged && $idTipoFiglio) { ?>
<?php echo "<".v("tag_blocco_testo")." class='blocco_testo'>"."<span id-fascia='$idFascia' id-tipo-figlio='$idTipoFiglio' title='modifica il testo' class='edit_blocco_custom' href='#'><i class='fa fa-pencil'></i></span>"."</".v("tag_blocco_testo").">";?>
<?php } ?>
