<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action === "link") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/pageslink/form/insert";?>?partial=Y&nobuttons=N&id_page=<?php echo $id_page;?>">Aggiungi link</a></p>

<?php } ?>

<?php if ($this->action === "testi") { ?>

<?php include(ROOT."/Application/Views/gestisci_associato_contenuti.php");?>

<?php } ?>

<?php include($this->viewPath("gestisci_associato_lingue"));?>

