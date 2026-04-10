<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (VariabiliModel::assistenteTestiBackendAttivo() || VariabiliModel::assistenteFrontendAttivo()) { ?>
<li class="<?php echo tm($tm, array("airichieste","aimodelli"));?> treeview">
	<a href="#">
		<i class="fa  fa-commenting-o"></i>
		<span><?php echo gtext("Assistente virtuale IA")?></span>
	</a>
	<ul class="treeview-menu">
		<?php if (VariabiliModel::assistenteTestiBackendAttivo()) { ?>
		<li class="dropdown-header"><?php echo gtext("Generazione IA");?></li>
		<li><a href="<?php echo $this->baseUrl."/airichieste/main?rag=0";?>"><i class="fa fa-lightbulb-o"></i> <?php echo gtext("Generazione testi IA")?></a></li>
		<?php } ?>
		<?php if (VariabiliModel::assistenteFrontendAttivo()) { ?>
		<li class="dropdown-header"><?php echo gtext("Chatbot");?></li>
		<li><a href="<?php echo $this->baseUrl."/airichieste/main?rag=1";?>"><i class="fa fa-comments-o"></i> <?php echo gtext("Chat IA")?></a></li>
		<?php } ?>
		<?php if (v("attiva_gestione_modelli_ai")) { ?>
		<li class="dropdown-header"><?php echo gtext("Modelli");?></li>
		<li class="<?php echo tm($tm, "aimodelli");?>"><a href="<?php echo $this->baseUrl."/aimodelli/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Configurazione modelli")?></a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
