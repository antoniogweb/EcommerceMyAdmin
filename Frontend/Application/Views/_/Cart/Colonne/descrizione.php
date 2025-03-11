<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!$p["cart"]["id_p"] && $p["cart"]["prodotto_attivo"]) { ?>
	<a class="uk-link-heading <?php if (User::$isMobile) { ?>uk-text-bold<?php } ?>" href="<?php echo $this->baseUrl."/".$urlAliasProdotto;?>">
<?php } ?>
<?php echo field($p,"title");?>
<?php if (!$p["cart"]["id_p"] && $p["cart"]["prodotto_attivo"]) { ?>
</a>
<?php } ?>
<?php if ($p["cart"]["attributi"]) { echo "<br /><span class='uk-text-small'>".$p["cart"]["attributi"]."</span>"; } ?>

<?php if ($p["cart"]["attributi"] && !$p["cart"]["id_p"] && $p["cart"]["prodotto_attivo"] && !VariabiliModel::combinazioniLinkVeri() && v("mostra_pulsante_modifica_se_ha_combinazioni")) { ?>
	<div class="uk-margin">
		<a class="uk-text-meta" href="<?php echo $this->baseUrl."/".$urlAliasProdotto."?id_cart=".$p["cart"]["id_cart"];?>"><?php echo gtext("Modifica");?></a>
	</div>
<?php } ?>
<?php include(tpf("Cart/main_testo_disponibilita.php"));?>
