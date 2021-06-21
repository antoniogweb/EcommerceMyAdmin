<?php
$titoloPagina = mfield($marchioCorrente, "titolo");
$noNumeroProdotti = true;
include(tpf("/Elementi/Pagine/page_top.php"));
?>
<div class="uk-text-left">
	<?php echo htmlentitydecode(attivaModuli(mfield($marchioCorrente, "descrizione")));?>
</div>
<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
?>
