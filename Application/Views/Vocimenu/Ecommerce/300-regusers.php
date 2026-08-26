<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_clienti")) { ?>
<li class="<?php echo tm($tm, array("regusers","ruoli","tipiazienda","reggroups", "regusersgroupstemp"));?> treeview">
	<a href="#">
		<i class="fa fa-user-o"></i>
		<span><?php echo gtextPlain("Clienti");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/".v("url_elenco_clienti")."/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi cliente");?></a></li>
		<li <?php if ($this->controller === "regusers" && $this->action == "main" && !RegusersModel::schermataAgenti()) { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/".v("url_elenco_clienti")."/main/1?agente=0";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista clienti");?></a></li>
		<?php if (v("attiva_agenti")) { ?>
		<li class="dropdown-header"><?php echo gtextPlain("Agenti");?></li>
		<li><a href="<?php echo $this->baseUrl."/".v("url_elenco_clienti")."/form/insert/0?agente=1";?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi agente");?></a></li>
		<li <?php if ($this->controller === "regusers" && $this->action == "main" && RegusersModel::schermataAgenti()) { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/".v("url_elenco_clienti")."/main/1?agente=1";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista agenti");?></a></li>
		<?php } ?>
		<?php if (v("attiva_ruoli")) { ?>
		<li class="dropdown-header"><?php echo gtextPlain("Ruoli");?></li>
		<li class="<?php echo tm($tm, array("ruoli"));?>"><a href="<?php echo $this->baseUrl."/ruoli/main/1";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista ruoli");?></a></li>
		<?php } ?>
		<?php if (v("attiva_tipi_azienda")) { ?>
		<li class="dropdown-header"><?php echo gtextPlain("Tipi azienda");?></li>
		<li class="<?php echo tm($tm, array("tipiazienda"));?>"><a href="<?php echo $this->baseUrl."/tipiazienda/main/1";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista tipi aziende");?></a></li>
		<?php } ?>
		<?php if (v("attiva_gruppi")) { ?>
		<li class="dropdown-header"><?php echo gtextPlain("Gruppi");?></li>
		<li class="<?php echo tm($tm, array("reggroups"));?>"><a href="<?php echo $this->baseUrl."/reggroups/main/1";?>"><i class="fa fa-group"></i> <?php echo gtextPlain("Lista gruppi");?></a></li>
			<?php if (v("gruppi_inseriti_da_approvare_alla_registrazione")) {
				$numeroDaApprovare = RegusersgroupstempModel::numerodaapprovare();
			?>
			<li class="<?php echo tm($tm, array("regusersgroupstemp"));?>">
				<a href="<?php echo $this->baseUrl."/regusersgroupstemp/main/1";?>">
					<i class="fa fa-thumbs-up"></i> <?php echo gtextPlain("Da approvare")?>
					<?php if ($numeroDaApprovare) { ?>
					<span class="label label-warning"><?php echo $numeroDaApprovare;?></span>
					<?php } ?>
				</a>
			</li>
			<?php } ?>
		<?php } ?>
	</ul>
</li>
<?php } ?>
